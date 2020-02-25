<?php

namespace app\modules\themes\index\assets;

class Builder
{
    public function getExcludeFiles(): array
    {
        return [
            '.git*',
        ];
    }

    public function isBuildSass(): bool
    {
        return true;
    }

    public function isBuildLess(): bool
    {
        return true;
    }

    public function isJsMinified(): bool
    {
        return true;
    }

    public function getJsOutputFileName(): string
    {
        return 'script.js';
    }

    public function getJsOrder(): array
    {
        return [
            'jquery',
            'bootstrap',
            'fancybox',
            'slick',
        ];
    }

    public function isCssMinified(): bool
    {
        return true;
    }

    public function getCssOutputFileName(): string
    {
        return 'style.css';
    }

    public function getCssOrder(): array
    {
        return [
            'jquery',
            'bootstrap',
            'fontawesome',
            'fancybox',
            'slick',
        ];
    }
}