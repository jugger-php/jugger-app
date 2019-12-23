<?php

namespace jugger\assets;

class AssetsManager
{
    protected $groupsAssets = [];

    public function add(string $group, Asset $asset)
    {
        $name = $asset->getName() ?: md5(serialize($asset));
        $group = strtolower($group);
        
        $groupsAssets = $this->groupsAssets[$group] ?? [];
        $groupsAssets[$name] = $asset;
        $this->groupsAssets[$group] = $groupsAssets;
    }

    public function addJs(string $value, array $params = [], string $name = null)
    {
        $asset = new Asset($value, $params);
        $asset->setType('js');
        if ($name) {
            $asset->setName($name);
        }
        $this->add('js', $asset);
    }

    public function addCss(string $value, array $params = [], string $name = null)
    {
        $asset = new Asset($value, $params);
        $asset->setType('css');
        if ($name) {
            $asset->setName($name);
        }
        $this->add('css', $asset);
    }

    public function getGroup(string $name): array
    {
        return $this->groupsAssets[$name] ?? [];
    }

    public function registerFile(string $path)
    {
        if (!file_exists($path)) {
            return;
        }

        $name = pathinfo($path, \PATHINFO_BASENAME);
        $hash = md5($path);

        $publicDir = '/assets/'. substr($hash, 0, 2);
        $publicPath = $_SERVER['DOCUMENT_ROOT'] . $publicDir .'/'. $name;
        if (!file_exists($_SERVER['DOCUMENT_ROOT'] . $publicDir)) {
            @mkdir($publicDir, 0755, true);
        }

        $pathTime = filemtime($path);
        $publicPathTime = filemtime($publicPath);
        if ($pathTime !== $publicPathTime) {
            copy($path, $publicPath);
        }

        echo '<pre>';
        var_dump($publicPath);
        echo '</pre>';
        die();
    }
}