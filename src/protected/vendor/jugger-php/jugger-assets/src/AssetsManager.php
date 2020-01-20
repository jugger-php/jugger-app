<?php

namespace jugger\assets;

class AssetsManager
{
    protected $appRoot;
    protected $baseDir;
    protected $groupsAssets = [];

    public function __construct(string $appRoot, string $baseDir = 'assets')
    {
        $this->appRoot = rtrim($appRoot, '/');
        $this->baseDir = $baseDir;
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

        $publicDir = "{$this->appRoot}/{$this->baseDir}/". substr($hash, 0, 2);
        $publicPath = $publicDir .'/'. $name;
        if (!file_exists($publicDir)) {
            mkdir($publicDir, 0755, true);
        }

        $pathTime = filemtime($path);
        $publicPathTime = file_exists($publicPath) ? filemtime($publicPath) : 0;
        if ($pathTime !== $publicPathTime) {
            copy($path, $publicPath);
            touch($publicPath, $pathTime);
        }

        $publicUrl = substr($publicPath, strlen($this->appRoot));
        return $publicUrl.'?'.$pathTime;
    }
}