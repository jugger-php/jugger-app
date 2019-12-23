<?php

namespace jugger\assetsWidget;

use jugger\widget\Widget;

class AssetsWidget extends Widget
{
    protected function runInternal(array $params): string
    {
        return $this->renderTemplate([
            'assets' => (array) ($params['items'] ?? []),
        ]);
    }
}