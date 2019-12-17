<?php

namespace app\widgets\menu;

use jugger\widget\Widget;

class Menu extends Widget
{
    public $items = [];
    public $brand;
    public $activeLink;

    protected function runInternal(array $params): string
    {
        if (isset($params['brand'])) {
            if (is_array($params['brand'])) {
                $this->brand = $params['brand'];
            }
            else {
                $this->brand = [
                    'text' => (string) $params['brand'],
                ];
            }
        }

        $this->items = $params['items'];
        $this->activeLink = $params['activeLink'] ?? null;
        
        return $this->renderTemplate();
    }
}