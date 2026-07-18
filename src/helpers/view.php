<?php

declare(strict_types=1);

function render(string $pageTemplate, array $data = [], bool $showNav = false): void
{
    extract($data, EXTR_SKIP);

    require __DIR__ . '/../../templates/layouts/header.php';

    if ($showNav) {
        require __DIR__ . '/../../templates/layouts/nav.php';
    }

    require __DIR__ . '/../../templates/pages/' . $pageTemplate . '.php';
    require __DIR__ . '/../../templates/layouts/footer.php';
}
