<?php

$assets = $params['assets'] ?? [];
if (!$assets) {
    return;
}

foreach ($assets as $asset) {
    $value = $asset->getValue();
    if ($asset->getType() === 'js') {
        ?>
        <script src="<?= $value ?>"></script>
        <?php
    }
    else if ($asset->getType() === 'css') {
        ?>
        <link rel="stylesheet" href="<?= $value ?>">
        <?php
    }
    else {
        echo "Unknow type";
    }
}