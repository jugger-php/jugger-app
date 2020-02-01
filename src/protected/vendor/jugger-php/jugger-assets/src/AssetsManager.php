<?php

namespace jugger\assets;

class AssetsManager
{
    protected $publicPath;
    protected $assetsPath;
    protected $groupsAssets = [];

    public function __construct(string $publicPath, string $assetsPath)
    {
        $this->publicPath = rtrim($publicPath, '/');
        $this->assetsPath = rtrim($assetsPath, '/');
    }

    public function add(string $group, Asset $asset)
    {
        $name = $asset->getName() ?: md5(serialize($asset));
        $group = strtolower($group);
        
        if (!isset($this->groupsAssets[$group])) {
            $this->groupsAssets[$group] = [];
        }
        $this->groupsAssets[$group][$name] = $asset;
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

        $publicFilePath = "{$this->assetsPath}/". substr($hash, 0, 2) .'/'. $name;
        if (!file_exists($publicFilePath)) {
            mkdir($publicFilePath, 0755, true);
        }

        $pathTime = filemtime($path);
        $publicFilePathTime = file_exists($publicFilePath) ? filemtime($publicFilePath) : 0;
        if ($pathTime !== $publicFilePathTime) {
            copy($path, $publicFilePath);
            touch($publicFilePath, $pathTime);
        }

        $publicUrl = substr($publicFilePath, strlen($this->publicPath));
        return $publicUrl.'?'.$pathTime;
    }
}