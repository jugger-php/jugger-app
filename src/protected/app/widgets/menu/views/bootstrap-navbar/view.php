<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <?php
    if ($context->brand) {
        $text = $context->brand['text'];
        $link = $context->brand['link'] ?? '/';
        ?>
        <a class="navbar-brand" href="<?= $link ?>">
            <?= $text ?>
        </a>
        <?php
    }
    ?>
    <div class="navbar-collapse collapse">
        <ul class="navbar-nav">
            <?php foreach ($context->items as $name => $link) { ?>
                <?php
                $active = $link === $context->activeLink ? "active" : "";
                ?>
                <li class="nav-item">
                    <a class="nav-link <?= $active ?>" href="<?= $link ?>">
                        <?= $name ?>
                    </a>
                </li>
            <?php } ?>
        </ul>
    </div>
</nav>