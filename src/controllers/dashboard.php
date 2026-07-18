<?php

declare(strict_types=1);

require_once __DIR__ . '/../../config/auth.php';
require_once __DIR__ . '/../helpers/view.php';

requireLogin();

render('dashboard', [], true);
