<?php
session_start();

header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

// Функция для преобразования текста в HTML
function convertSimpleTextToHTML($text) {
    $text = trim($text);
    $text = nl2br(htmlspecialchars($text));
    
    $text = preg_replace_callback(
        '/\[([^\]]+)\]\(([^)]+)\)/',
        function($matches) {
            $linkText = htmlspecialchars($matches[1]);
            $linkUrl = htmlspecialchars($matches[2]);
            return '<a href="' . $linkUrl . '" class="content-link" target="_blank" rel="noopener noreferrer">' . $linkText . '</a>';
        },
        $text
    );
    
    $text = preg_replace('/\*\*([^*]+)\*\*/', '<strong>$1</strong>', $text);
    $text = preg_replace('/\*([^*]+)\*/', '<em>$1</em>', $text);
    
    return $text;
}

// Функция для генерации slug
function generateSlug($text) {
    $text = mb_strtolower($text, 'UTF-8');
    
    $translit = [
        'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e',
        'ё' => 'yo', 'ж' => 'zh', 'з' => 'z', 'и' => 'i', 'й' => 'y', 'к' => 'k',
        'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o', 'п' => 'p', 'р' => 'r',
        'с' => 's', 'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'ts',
        'ч' => 'ch', 'ш' => 'sh', 'щ' => 'shch', 'ъ' => '', 'ы' => 'y', 'ь' => '',
        'э' => 'e', 'ю' => 'yu', 'я' => 'ya',
        ' ' => '-', '_' => '-', '.' => '', ',' => '', '!' => '', '?' => '',
        ':' => '', ';' => '', '(' => '', ')' => '', '[' => '', ']' => '',
        '{' => '', '}' => '', '"' => '', "'" => '', '`' => '', '~' => '',
        '@' => '', '#' => '', '$' => '', '%' => '', '^' => '', '&' => '',
        '*' => '', '+' => '', '=' => '', '|' => '', '\\' => '', '/' => ''
    ];
    
    $text = strtr($text, $translit);
    $text = preg_replace('/[^a-z0-9\-]/', '', $text);
    $text = preg_replace('/-+/', '-', $text);
    $text = trim($text, '-');
    
    if (empty($text)) {
        $text = 'block-' . uniqid();
    }
    
    return $text;
}

// Функция для создания уникального slug
function makeUniqueSlug($slug, $blocks, $excludeId = null) {
    $originalSlug = $slug;
    $counter = 1;
    
    while (true) {
        $exists = false;
        foreach ($blocks as $block) {
            if ($excludeId && $block['id'] === $excludeId) {
                continue;
            }
            if (isset($block['content_slug']) && $block['content_slug'] === $slug) {
                $exists = true;
                break;
            }
        }
        
        if (!$exists) {
            break;
        }
        
        $slug = $originalSlug . '-' . $counter;
        $counter++;
    }
    
    return $slug;
}

// БОЛЬШОЙ МАССИВ ИКОНОК, СГРУППИРОВАННЫХ ПО КАТЕГОРИЯМ
$iconCategories = [
    'Основные' => [
        'fas fa-home', 'fas fa-star', 'fas fa-heart', 'fas fa-cog', 'fas fa-search',
        'fas fa-bell', 'fas fa-user', 'fas fa-users', 'fas fa-flag', 'fas fa-globe',
        'fas fa-envelope', 'fas fa-phone', 'fas fa-map-marker', 'fas fa-calendar',
        'fas fa-clock', 'fas fa-image', 'fas fa-camera', 'fas fa-video', 'fas fa-music',
        'fas fa-file', 'fas fa-folder', 'fas fa-database', 'fas fa-server', 'fas fa-cloud',
    ],
    
    'Социальные сети' => [
        'fab fa-telegram', 'fab fa-whatsapp', 'fab fa-vk', 'fab fa-youtube', 'fab fa-github',
        'fab fa-discord', 'fab fa-twitter', 'fab fa-facebook', 'fab fa-instagram', 'fab fa-tiktok',
        'fab fa-reddit', 'fab fa-linkedin', 'fab fa-skype', 'fab fa-pinterest', 'fab fa-snapchat',
        'fab fa-twitch', 'fab fa-spotify', 'fab fa-apple', 'fab fa-android', 'fab fa-windows',
    ],
    
    'Коммуникация' => [
        'fas fa-comment', 'fas fa-comments', 'fas fa-comment-dots', 'fas fa-comment-alt',
        'fas fa-comment-medical', 'fas fa-sms', 'fas fa-at', 'fas fa-reply', 'fas fa-share',
        'fas fa-retweet', 'fas fa-quote-left', 'fas fa-quote-right', 'fas fa-bullhorn',
        'fas fa-megaphone', 'fas fa-broadcast-tower', 'fas fa-rss', 'fas fa-podcast',
    ],
    
    'Действия' => [
        'fas fa-plus', 'fas fa-minus', 'fas fa-times', 'fas fa-check', 'fas fa-edit',
        'fas fa-trash', 'fas fa-save', 'fas fa-download', 'fas fa-upload', 'fas fa-share-alt',
        'fas fa-external-link-alt', 'fas fa-link', 'fas fa-unlink', 'fas fa-copy',
        'fas fa-paste', 'fas fa-cut', 'fas fa-redo', 'fas fa-undo', 'fas fa-sync',
        'fas fa-history', 'fas fa-filter', 'fas fa-sort', 'fas fa-sort-up', 'fas fa-sort-down',
    ],
    
    'Навигация' => [
        'fas fa-arrow-left', 'fas fa-arrow-right', 'fas fa-arrow-up', 'fas fa-arrow-down',
        'fas fa-chevron-left', 'fas fa-chevron-right', 'fas fa-chevron-up', 'fas fa-chevron-down',
        'fas fa-angle-left', 'fas fa-angle-right', 'fas fa-angle-up', 'fas fa-angle-down',
        'fas fa-caret-left', 'fas fa-caret-right', 'fas fa-caret-up', 'fas fa-caret-down',
        'fas fa-long-arrow-alt-left', 'fas fa-long-arrow-alt-right', 'fas fa-long-arrow-alt-up',
        'fas fa-long-arrow-alt-down', 'fas fa-expand', 'fas fa-compress', 'fas fa-expand-alt',
        'fas fa-compress-alt', 'fas fa-arrows-alt', 'fas fa-arrows-alt-h', 'fas fa-arrows-alt-v',
    ],
    
    'Интерфейс' => [
        'fas fa-bars', 'fas fa-th', 'fas fa-th-large', 'fas fa-list', 'fas fa-list-alt',
        'fas fa-list-ol', 'fas fa-list-ul', 'fas fa-table', 'fas fa-columns', 'fas fa-grip-horizontal',
        'fas fa-grip-vertical', 'fas fa-border-all', 'fas fa-border-none', 'fas fa-border-style',
        'fas fa-window-maximize', 'fas fa-window-minimize', 'fas fa-window-restore', 'fas fa-times-circle',
        'fas fa-check-circle', 'fas fa-info-circle', 'fas fa-question-circle', 'fas fa-exclamation-circle',
        'fas fa-exclamation-triangle', 'fas fa-ban', 'fas fa-radiation', 'fas fa-skull-crossbones',
    ],
    
    'Безопасность' => [
        'fas fa-lock', 'fas fa-unlock', 'fas fa-key', 'fas fa-fingerprint', 'fas fa-user-lock',
        'fas fa-user-secret', 'fas fa-shield-alt', 'fas fa-shield-virus', 'fas fa-virus',
        'fas fa-virus-slash', 'fas fa-biohazard', 'fas fa-mask', 'fas fa-hand-sparkles',
        'fas fa-handshake-slash', 'fas fa-passport', 'fas fa-id-card', 'fas fa-id-badge',
    ],
    
    'Транспорт' => [
        'fas fa-car', 'fas fa-bus', 'fas fa-train', 'fas fa-subway', 'fas fa-plane',
        'fas fa-rocket', 'fas fa-ship', 'fas fa-bicycle', 'fas fa-motorcycle',
        'fas fa-truck', 'fas fa-ambulance', 'fas fa-fighter-jet', 'fas fa-helicopter',
        'fas fa-satellite', 'fas fa-space-shuttle', 'fas fa-taxi', 'fas fa-traffic-light',
        'fas fa-road', 'fas fa-map', 'fas fa-map-marked', 'fas fa-map-marked-alt',
        'fas fa-map-pin', 'fas fa-map-signs', 'fas fa-compass', 'fas fa-street-view',
    ],
    
    'Погода' => [
        'fas fa-sun', 'fas fa-moon', 'fas fa-cloud', 'fas fa-cloud-sun', 'fas fa-cloud-moon',
        'fas fa-cloud-rain', 'fas fa-cloud-showers-heavy', 'fas fa-snowflake', 'fas fa-wind',
        'fas fa-tornado', 'fas fa-umbrella', 'fas fa-thermometer-half', 'fas fa-temperature-high',
        'fas fa-temperature-low', 'fas fa-smog', 'fas fa-meteor', 'fas fa-water', 'fas fa-fire',
        'fas fa-volcano', 'fas fa-mountain', 'fas fa-tree', 'fas fa-seedling', 'fas fa-leaf',
    ],
    
    'Еда и напитки' => [
        'fas fa-utensils', 'fas fa-utensil-spoon', 'fas fa-coffee', 'fas fa-mug-hot',
        'fas fa-beer', 'fas fa-wine-glass', 'fas fa-wine-glass-alt', 'fas fa-cocktail',
        'fas fa-glass-whiskey', 'fas fa-birthday-cake', 'fas fa-cookie', 'fas fa-cookie-bite',
        'fas fa-ice-cream', 'fas fa-hamburger', 'fas fa-pizza-slice', 'fas fa-bacon',
        'fas fa-drumstick-bite', 'fas fa-egg', 'fas fa-fish', 'fas fa-lemon', 'fas fa-apple-alt',
        'fas fa-pepper-hot', 'fas fa-cheese', 'fas fa-bread-slice',
    ],
    
    'Спорт' => [
        'fas fa-futbol', 'fas fa-basketball-ball', 'fas fa-baseball-ball', 'fas fa-volleyball-ball',
        'fas fa-football-ball', 'fas fa-golf-ball', 'fas fa-hockey-puck', 'fas fa-tennis-ball',
        'fas fa-bowling-ball', 'fas fa-swimmer', 'fas fa-running', 'fas fa-biking',
        'fas fa-skiing', 'fas fa-skiing-nordic', 'fas fa-skating', 'fas fa-snowboarding',
        'fas fa-hiking', 'fas fa-mountain', 'fas fa-dumbbell', 'fas fa-weight', 'fas fa-medal',
        'fas fa-trophy', 'fas fa-award', 'fas fa-crown', 'fas fa-helmet-battle',
    ],
    
    'Образование' => [
        'fas fa-graduation-cap', 'fas fa-school', 'fas fa-university', 'fas fa-book',
        'fas fa-book-open', 'fas fa-book-reader', 'fas fa-atlas', 'fas fa-globe-americas',
        'fas fa-globe-africa', 'fas fa-globe-asia', 'fas fa-globe-europe', 'fas fa-map',
        'fas fa-chalkboard', 'fas fa-chalkboard-teacher', 'fas fa-pencil-alt', 'fas fa-pen',
        'fas fa-pen-fancy', 'fas fa-pen-nib', 'fas fa-marker', 'fas fa-highlighter',
        'fas fa-eraser', 'fas fa-calculator', 'fas fa-ruler', 'fas fa-ruler-combined',
        'fas fa-compass', 'fas fa-microscope', 'fas fa-flask', 'fas fa-atom', 'fas fa-dna',
        'fas fa-vial', 'fas fa-vials', 'fas fa-prescription-bottle', 'fas fa-pills',
    ],
    
    'Технологии' => [
        'fas fa-laptop', 'fas fa-desktop', 'fas fa-tablet-alt', 'fas fa-mobile-alt',
        'fas fa-mobile', 'fas fa-gamepad', 'fas fa-keyboard', 'fas fa-mouse', 'fas fa-hdd',
        'fas fa-microchip', 'fas fa-microchip', 'fas fa-server', 'fas fa-database',
        'fas fa-hdd', 'fas fa-save', 'fas fa-sd-card', 'fas fa-usb', 'fas fa-plug',
        'fas fa-power-off', 'fas fa-battery-full', 'fas fa-battery-three-quarters',
        'fas fa-battery-half', 'fas fa-battery-quarter', 'fas fa-battery-empty',
        'fas fa-bolt', 'fas fa-satellite-dish', 'fas fa-wifi', 'fas fa-bluetooth',
        'fas fa-broadcast-tower', 'fas fa-signal', 'fas fa-sim-card', 'fas fa-memory',
        'fas fa-hashtag', 'fas fa-code', 'fas fa-code-branch', 'fas fa-terminal',
        'fas fa-laptop-code', 'fas fa-robot', 'fas fa-android', 'fab fa-apple',
        'fab fa-windows', 'fab fa-linux', 'fab fa-ubuntu', 'fab fa-redhat',
    ],
    
    'Бизнес и финансы' => [
        'fas fa-chart-line', 'fas fa-chart-bar', 'fas fa-chart-pie', 'fas fa-chart-area',
        'fas fa-money-bill', 'fas fa-money-bill-wave', 'fas fa-money-bill-alt',
        'fas fa-credit-card', 'fas fa-wallet', 'fas fa-piggy-bank', 'fas fa-university',
        'fas fa-landmark', 'fas fa-hand-holding-usd', 'fas fa-coins', 'fas fa-gem',
        'fas fa-diamond', 'fas fa-crown', 'fas fa-award', 'fas fa-trophy', 'fas fa-medal',
        'fas fa-shopping-cart', 'fas fa-shopping-bag', 'fas fa-shopping-basket',
        'fas fa-store', 'fas fa-store-alt', 'fas fa-tags', 'fas fa-tag', 'fas fa-receipt',
        'fas fa-file-invoice', 'fas fa-file-invoice-dollar', 'fas fa-balance-scale',
        'fas fa-balance-scale-left', 'fas fa-balance-scale-right', 'fas fa-briefcase',
        'fas fa-suitcase', 'fas fa-suitcase-rolling', 'fas fa-passport', 'fas fa-id-card',
        'fas fa-id-card-alt', 'fas fa-address-card', 'fas fa-address-book',
    ],
    
    'Здоровье и медицина' => [
        'fas fa-heart', 'fas fa-heartbeat', 'fas fa-heart-broken', 'fas fa-lungs',
        'fas fa-lungs-virus', 'fas fa-brain', 'fas fa-allergies', 'fas fa-bacteria',
        'fas fa-bacterium', 'fas fa-virus', 'fas fa-virus-slash', 'fas fa-head-side-mask',
        'fas fa-head-side-cough', 'fas fa-head-side-cough-slash', 'fas fa-head-side-virus',
        'fas fa-hand-holding-medical', 'fas fa-hand-sparkles', 'fas fa-hands-wash',
        'fas fa-handshake-alt-slash', 'fas fa-hospital', 'fas fa-hospital-alt',
        'fas fa-clinic-medical', 'fas fa-notes-medical', 'fas fa-stethoscope',
        'fas fa-thermometer', 'fas fa-prescription-bottle', 'fas fa-pills',
        'fas fa-syringe', 'fas fa-tablets', 'fas fa-capsules', 'fas fa-band-aid',
        'fas fa-user-md', 'fas fa-user-nurse', 'fas fa-ambulance', 'fas fa-procedures',
        'fas fa-wheelchair', 'fas fa-blind', 'fas fa-deaf', 'fas fa-sign-language',
        'fas fa-assistive-listening-systems', 'fas fa-walking', 'fas fa-running',
    ],
    
    'Искусство и развлечения' => [
        'fas fa-music', 'fas fa-film', 'fas fa-theater-masks', 'fas fa-tv',
        'fas fa-gamepad', 'fas fa-dice', 'fas fa-dice-d6', 'fas fa-dice-d20',
        'fas fa-chess', 'fas fa-chess-king', 'fas fa-chess-queen', 'fas fa-chess-bishop',
        'fas fa-chess-knight', 'fas fa-chess-rook', 'fas fa-chess-pawn', 'fas fa-palette',
        'fas fa-paint-brush', 'fas fa-paint-roller', 'fas fa-magic', 'fas fa-hat-wizard',
        'fas fa-mask', 'fas fa-crown', 'fas fa-guitar', 'fas fa-drum', 'fas fa-trumpet',
        'fas fa-video', 'fas fa-photo-video', 'fas fa-microphone', 'fas fa-microphone-alt',
        'fas fa-headphones', 'fas fa-headphones-alt', 'fas fa-radio', 'fas fa-record-vinyl',
        'fas fa-compact-disc', 'fas fa-camera', 'fas fa-camera-retro', 'fas fa-video',
        'fas fa-video-slash', 'fas fa-film', 'fas fa-clapperboard', 'fas fa-ticket-alt',
        'fas fa-concierge-bell', 'fas fa-umbrella-beach', 'fas fa-cocktail', 'fas fa-glass-cheers',
    ],
    
    'Дом и сад' => [
        'fas fa-home', 'fas fa-couch', 'fas fa-chair', 'fas fa-bed', 'fas fa-bath',
        'fas fa-shower', 'fas fa-toilet', 'fas fa-toilet-paper', 'fas fa-sink',
        'fas fa-tv', 'fas fa-blender', 'fas fa-oven', 'fas fa-microwave', 'fas fa-refrigerator',
        'fas fa-snowflake', 'fas fa-fan', 'fas fa-lightbulb', 'fas fa-lamp', 'fas fa-door-open',
        'fas fa-door-closed', 'fas fa-window-maximize', 'fas fa-window-minimize',
        'fas fa-window-restore', 'fas fa-key', 'fas fa-warehouse', 'fas fa-garage',
        'fas fa-garage-open', 'fas fa-garage-car', 'fas fa-tools', 'fas fa-toolbox',
        'fas fa-hammer', 'fas fa-screwdriver', 'fas fa-wrench', 'fas fa-ruler',
        'fas fa-ruler-combined', 'fas fa-paint-roller', 'fas fa-brush', 'fas fa-tree',
        'fas fa-seedling', 'fas fa-leaf', 'fas fa-spa', 'fas fa-umbrella-beach',
        'fas fa-swimming-pool', 'fas fa-hot-tub', 'fas fa-campground', 'fas fa-fire',
        'fas fa-fire-alt', 'fas fa-fireplace', 'fas fa-thermometer-half',
    ],
];

// Создаем плоский массив всех иконок для поиска
$allIcons = [];
foreach ($iconCategories as $category => $icons) {
    $allIcons = array_merge($allIcons, $icons);
}

$password = 'ваш_секретный_пароль';
$blocksFile = 'blocks-data.json';

// Проверка авторизации
if (!isset($_SESSION['auth']) || $_SESSION['auth'] !== true) {
    if (isset($_POST['password'])) {
        if ($_POST['password'] === $password) {
            $_SESSION['auth'] = true;
        } else {
            $error = 'Неверный пароль';
        }
    }
    
    if (!isset($_SESSION['auth']) || $_SESSION['auth'] !== true) {
        ?>
        <!DOCTYPE html>
        <html lang="ru">
        <head>
            <meta charset="UTF-8">
            <title>Вход в админ-панель</title>
            <style>
                body { font-family: sans-serif; max-width: 400px; margin: 50px auto; padding: 20px; }
                input { width: 100%; padding: 10px; margin: 10px 0; }
                button { width: 100%; padding: 10px; background: #4CAF50; color: white; border: none; cursor: pointer; }
                .error { color: red; margin: 10px 0; }
            </style>
        </head>
        <body>
            <h2>Вход в админ-панель блоков</h2>
            <form method="POST">
                <input type="password" name="password" placeholder="Пароль" required>
                <button type="submit">Войти</button>
                <?php if (isset($error)): ?><div class="error"><?php echo $error; ?></div><?php endif; ?>
            </form>
        </body>
        </html>
        <?php
        exit;
    }
}

// Чтение существующих блоков
if (file_exists($blocksFile)) {
    $blocks = json_decode(file_get_contents($blocksFile), true);
    if (!$blocks) $blocks = [];
} else {
    $blocks = [];
}

// Удаление блока
if (isset($_GET['delete'])) {
    $idToDelete = $_GET['delete'];
    $blocks = array_filter($blocks, function($block) use ($idToDelete) {
        return $block['id'] !== $idToDelete;
    });
    file_put_contents($blocksFile, json_encode(array_values($blocks), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    header('Location: admin-blocks.php');
    exit;
}

// Редактирование блока
$editingBlock = null;
if (isset($_GET['edit'])) {
    $idToEdit = $_GET['edit'];
    foreach ($blocks as $block) {
        if ($block['id'] === $idToEdit) {
            $editingBlock = $block;
            break;
        }
    }
}

// Добавление/редактирование блока
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'update') {
        $idToUpdate = $_POST['id'];
        foreach ($blocks as &$block) {
            if ($block['id'] === $idToUpdate) {
                $block['type'] = $_POST['type'];
                $block['icon'] = $_POST['icon'];
                $block['title'] = htmlspecialchars($_POST['title']);
                $block['description'] = htmlspecialchars($_POST['description']);
                $block['button_text'] = htmlspecialchars($_POST['button_text']);
                $block['order'] = intval($_POST['order']);
                
                $block['content_type'] = $_POST['content_type'] ?? 'link';
                
                if ($block['content_type'] === 'content') {
                    $block['content_title'] = htmlspecialchars($_POST['content_title']);
                    $contentText = trim($_POST['content_text'] ?? '');
                    
                    $block['content_markdown'] = $contentText;
                    
                    if (!empty($contentText)) {
                        $htmlContent = convertSimpleTextToHTML($contentText);
                        $block['content'] = '<div class="content-section">' . $htmlContent . '</div>';
                    } else {
                        $block['content'] = '';
                    }
                    
                    $slug = $_POST['content_slug'] ?? '';
                    if (empty($slug)) {
                        $slug = generateSlug($block['content_title'] ?: $block['title']);
                    }
                    $block['content_slug'] = makeUniqueSlug($slug, $blocks, $idToUpdate);
                    
                    $block['link'] = '';
                } else {
                    $block['link'] = htmlspecialchars($_POST['link']);
                    $block['content'] = '';
                    $block['content_title'] = '';
                    $block['content_slug'] = '';
                    $block['content_markdown'] = '';
                }
                
                break;
            }
        }
        
        file_put_contents($blocksFile, json_encode($blocks, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        header('Location: admin-blocks.php');
        exit;
    } elseif (isset($_POST['title'])) {
        $newBlock = [
            'id' => uniqid(),
            'type' => $_POST['type'],
            'icon' => $_POST['icon'],
            'title' => htmlspecialchars($_POST['title']),
            'description' => htmlspecialchars($_POST['description']),
            'button_text' => htmlspecialchars($_POST['button_text']),
            'order' => intval($_POST['order']),
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        $contentType = $_POST['content_type'] ?? 'link';
        $newBlock['content_type'] = $contentType;
        
        if ($contentType === 'content') {
            $newBlock['content_title'] = htmlspecialchars($_POST['content_title']);
            $contentText = trim($_POST['content_text'] ?? '');
            
            $newBlock['content_markdown'] = $contentText;
            
            if (!empty($contentText)) {
                $htmlContent = convertSimpleTextToHTML($contentText);
                $newBlock['content'] = '<div class="content-section">' . $htmlContent . '</div>';
            } else {
                $newBlock['content'] = '';
            }
            
            $slug = $_POST['content_slug'] ?? '';
            if (empty($slug)) {
                $slug = generateSlug($newBlock['content_title'] ?: $newBlock['title']);
            }
            $newBlock['content_slug'] = makeUniqueSlug($slug, $blocks);
            
            $newBlock['link'] = '';
        } else {
            $newBlock['link'] = htmlspecialchars($_POST['link']);
            $newBlock['content'] = '';
            $newBlock['content_title'] = '';
            $newBlock['content_slug'] = '';
            $newBlock['content_markdown'] = '';
        }
        
        $blocks[] = $newBlock;
        file_put_contents($blocksFile, json_encode($blocks, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        
        $success = 'Блок успешно добавлен!';
    }
}

// Сортировка блоков
usort($blocks, function($a, $b) {
    if ($a['type'] !== $b['type']) {
        return strcmp($a['type'], $b['type']);
    }
    return $a['order'] - $b['order'];
});

$isEditing = isset($_GET['edit']);

// Парсим контент для редактирования
$editingContentTitle = '';
$editingContentText = '';
$editingContentSlug = '';
$editingContentType = 'link';
if ($isEditing && $editingBlock) {
    $editingContentType = $editingBlock['content_type'] ?? 'link';
    $editingContentSlug = $editingBlock['content_slug'] ?? '';
    $editingContentTitle = $editingBlock['content_title'] ?? '';
    
    if (isset($editingBlock['content_markdown']) && !empty($editingBlock['content_markdown'])) {
        $editingContentText = $editingBlock['content_markdown'];
    } else if (isset($editingBlock['content']) && !empty($editingBlock['content'])) {
        $content = $editingBlock['content'];
        
        $content = str_replace('<div class="content-section">', '', $content);
        $content = str_replace('</div>', '', $content);
        
        $content = htmlspecialchars_decode($content);
        
        $content = preg_replace_callback('/<a href="([^"]+)"[^>]*>([^<]+)<\/a>/', function($matches) {
            return '[' . $matches[2] . '](' . $matches[1] . ')';
        }, $content);
        
        $content = preg_replace('/<strong>([^<]+)<\/strong>/', '**$1**', $content);
        $content = preg_replace('/<em>([^<]+)<\/em>/', '*$1*', $content);
        
        $content = preg_replace('/<br\s*\/?>/', "\n", $content);
        
        $editingContentText = trim($content);
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Управление блоками</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: sans-serif; margin: 20px; background: #f5f5f5; }
        .container { max-width: 1200px; margin: 0 auto; }
        .header { display: flex; justify-content: space-between; margin-bottom: 20px; }
        .logout { color: red; text-decoration: none; }
        .content { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        @media (max-width: 768px) { .content { grid-template-columns: 1fr; } }
        .form-section, .list-section { background: white; padding: 20px; border-radius: 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        h2, h3 { margin-top: 0; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input[type="text"], textarea, select { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; }
        textarea.large { min-height: 150px; font-family: monospace; }
        .checkbox { display: flex; align-items: center; gap: 10px; }
        button, .button { padding: 10px 15px; background: #4CAF50; color: white; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; display: inline-block; }
        .button.cancel { background: #777; }
        .button.edit { background: #2196F3; }
        .button.delete { background: #f44336; }
        .button.view { background: #9C27B0; }
        .block-item { border: 1px solid #ddd; padding: 15px; margin-bottom: 10px; border-radius: 4px; background: white; }
        .block-header { display: flex; justify-content: space-between; margin-bottom: 10px; }
        .block-type { background: #e0e0e0; padding: 2px 8px; border-radius: 3px; font-size: 12px; }
        .block-type.main { background: #4CAF50; color: white; }
        .block-type.additional { background: #FF9800; color: white; }
        .block-actions { display: flex; gap: 5px; margin-top: 10px; }
        .success { background: #d4edda; color: #155724; padding: 10px; border-radius: 4px; margin-bottom: 15px; }
        .edit-form { background: #e3f2fd; padding: 20px; border-radius: 5px; margin-bottom: 20px; }
        .content-toggle { display: flex; margin-bottom: 10px; border: 1px solid #ddd; border-radius: 4px; overflow: hidden; }
        .toggle-option { flex: 1; padding: 10px; text-align: center; background: #f5f5f5; cursor: pointer; }
        .toggle-option.active { background: #2196F3; color: white; }
        .content-type { font-size: 12px; padding: 2px 6px; border-radius: 3px; margin-left: 8px; }
        .content-type.content { background: #4CAF50; color: white; }
        .content-type.link { background: #9C27B0; color: white; }
        .icon-grid-container { max-height: 400px; overflow-y: auto; border: 1px solid #ddd; border-radius: 4px; padding: 10px; background: #f9f9f9; margin-top: 10px; }
        .icon-category { margin-bottom: 15px; }
        .icon-category h4 { margin: 0 0 8px 0; color: #555; border-bottom: 1px solid #ddd; padding-bottom: 5px; }
        .icon-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(40px, 1fr)); gap: 5px; }
        .icon-option { padding: 8px; text-align: center; cursor: pointer; border: 1px solid #ddd; border-radius: 4px; background: white; font-size: 14px; }
        .icon-option:hover { border-color: #2196F3; background: #e3f2fd; }
        .icon-option.selected { border-color: #2196F3; background: #2196F3; color: white; }
        .icon-search { width: 100%; padding: 8px; margin-bottom: 10px; border: 1px solid #ddd; border-radius: 4px; }
        .formatting-help { font-size: 12px; color: #666; margin-top: 10px; padding: 10px; background: #f0f0f0; border-radius: 4px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Управление блоками</h1>
            <a href="?logout" class="logout">Выйти</a>
        </div>
        
        <?php if (isset($success)): ?>
            <div class="success"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <div class="content">
            <!-- Форма добавления/редактирования -->
            <div class="form-section">
                <h2><?php echo $isEditing ? 'Редактировать блок' : 'Добавить блок'; ?></h2>
                
                <?php if ($isEditing && $editingBlock): ?>
                    <div class="edit-form">
                        <h3>Редактирование: <?php echo htmlspecialchars($editingBlock['title']); ?></h3>
                        <form method="POST">
                            <input type="hidden" name="action" value="update">
                            <input type="hidden" name="id" value="<?php echo $editingBlock['id']; ?>">
                            
                            <div class="form-group">
                                <label>Тип блока</label>
                                <select name="type">
                                    <option value="main" <?php echo $editingBlock['type'] === 'main' ? 'selected' : ''; ?>>Основной</option>
                                    <option value="additional" <?php echo $editingBlock['type'] === 'additional' ? 'selected' : ''; ?>>Дополнительный</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label>Иконка</label>
                                <input type="text" name="icon" id="icon-input" value="<?php echo $editingBlock['icon']; ?>" required placeholder="Начните вводить название или выберите ниже">
                                <input type="text" class="icon-search" id="icon-search" placeholder="Поиск иконки...">
                                
                                <div class="icon-grid-container" id="icon-grid-container">
                                    <?php foreach ($iconCategories as $category => $icons): ?>
                                        <div class="icon-category" data-category="<?php echo htmlspecialchars($category); ?>">
                                            <h4><?php echo htmlspecialchars($category); ?></h4>
                                            <div class="icon-grid">
                                                <?php foreach ($icons as $icon): ?>
                                                    <div class="icon-option <?php echo $icon === $editingBlock['icon'] ? 'selected' : ''; ?>" 
                                                         data-icon="<?php echo $icon; ?>"
                                                         title="<?php echo $icon; ?>">
                                                        <i class="<?php echo $icon; ?>"></i>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label>Заголовок</label>
                                <input type="text" name="title" value="<?php echo htmlspecialchars($editingBlock['title']); ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label>Описание</label>
                                <textarea name="description" required><?php echo htmlspecialchars($editingBlock['description']); ?></textarea>
                            </div>
                            
                            <div class="form-group">
                                <label>Текст кнопки</label>
                                <input type="text" name="button_text" value="<?php echo htmlspecialchars($editingBlock['button_text']); ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label>Тип контента</label>
                                <div class="content-toggle">
                                    <div class="toggle-option <?php echo $editingContentType === 'link' ? 'active' : ''; ?>" data-type="link">Ссылка</div>
                                    <div class="toggle-option <?php echo $editingContentType === 'content' ? 'active' : ''; ?>" data-type="content">Контент</div>
                                </div>
                                <input type="hidden" name="content_type" id="content_type" value="<?php echo $editingContentType; ?>">
                                
                                <div id="link-fields" style="<?php echo $editingContentType !== 'link' ? 'display:none;' : ''; ?>">
                                    <input type="url" name="link" value="<?php echo htmlspecialchars($editingBlock['link'] ?? ''); ?>" placeholder="https://example.com">
                                </div>
                                
                                <div id="content-fields" style="<?php echo $editingContentType !== 'content' ? 'display:none;' : ''; ?>">
                                    <div class="form-group">
                                        <label>Заголовок страницы</label>
                                        <input type="text" name="content_title" value="<?php echo htmlspecialchars($editingContentTitle); ?>">
                                    </div>
                                    
                                    <div class="form-group">
                                        <label>Текст (Markdown)</label>
                                        <textarea name="content_text" class="large"><?php echo htmlspecialchars($editingContentText); ?></textarea>
                                        <div class="formatting-help">
                                            <strong>Форматирование:</strong><br>
                                            • Ссылки: [текст](https://example.com)<br>
                                            • Жирный: **текст**<br>
                                            • Курсив: *текст*<br>
                                            • Просто Enter для новой строки
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label>Slug (URL часть)</label>
                                        <input type="text" name="content_slug" value="<?php echo htmlspecialchars($editingContentSlug); ?>">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label>Порядок</label>
                                <input type="number" name="order" value="<?php echo $editingBlock['order']; ?>" required>
                            </div>
                            
                            <button type="submit">Сохранить</button>
                            <a href="admin-blocks.php" class="button cancel">Отмена</a>
                        </form>
                    </div>
                <?php else: ?>
                    <form method="POST">
                        <div class="form-group">
                            <label>Тип блока</label>
                            <select name="type" required>
                                <option value="main">Основной</option>
                                <option value="additional">Дополнительный</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label>Иконка</label>
                            <input type="text" name="icon" id="icon-input" required placeholder="Начните вводить название или выберите ниже">
                            <input type="text" class="icon-search" id="icon-search" placeholder="Поиск иконки...">
                            
                            <div class="icon-grid-container" id="icon-grid-container">
                                <?php foreach ($iconCategories as $category => $icons): ?>
                                    <div class="icon-category" data-category="<?php echo htmlspecialchars($category); ?>">
                                        <h4><?php echo htmlspecialchars($category); ?></h4>
                                        <div class="icon-grid">
                                            <?php foreach ($icons as $icon): ?>
                                                <div class="icon-option" 
                                                     data-icon="<?php echo $icon; ?>"
                                                     title="<?php echo $icon; ?>">
                                                    <i class="<?php echo $icon; ?>"></i>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label>Заголовок</label>
                            <input type="text" name="title" required>
                        </div>
                        
                        <div class="form-group">
                            <label>Описание</label>
                            <textarea name="description" required></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label>Текст кнопки</label>
                            <input type="text" name="button_text" required>
                        </div>
                        
                        <div class="form-group">
                            <label>Тип контента</label>
                            <div class="content-toggle">
                                <div class="toggle-option active" data-type="link">Ссылка</div>
                                <div class="toggle-option" data-type="content">Контент</div>
                            </div>
                            <input type="hidden" name="content_type" id="content_type" value="link">
                            
                            <div id="link-fields">
                                <input type="url" name="link" required value="https://ishupodrygyilidryga.fun" placeholder="https://example.com">
                            </div>
                            
                            <div id="content-fields" style="display:none;">
                                <div class="form-group">
                                    <label>Заголовок страницы</label>
                                    <input type="text" name="content_title">
                                </div>
                                
                                <div class="form-group">
                                    <label>Текст (Markdown)</label>
                                    <textarea name="content_text" class="large"></textarea>
                                    <div class="formatting-help">
                                        <strong>Форматирование:</strong><br>
                                        • Ссылки: [текст](https://example.com)<br>
                                        • Жирный: **текст**<br>
                                        • Курсив: *текст*<br>
                                        • Просто Enter для новой строки
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label>Slug (URL часть)</label>
                                    <input type="text" name="content_slug">
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label>Порядок</label>
                            <input type="number" name="order" value="0" required>
                        </div>
                        
                        <button type="submit">Добавить блок</button>
                    </form>
                <?php endif; ?>
            </div>
            
            <!-- Список блоков -->
            <div class="list-section">
                <h2>Блоки (<?php echo count($blocks); ?>)</h2>
                
                <?php if (empty($blocks)): ?>
                    <p>Нет блоков</p>
                <?php else: ?>
                    <?php foreach ($blocks as $block): ?>
                        <div class="block-item">
                            <div class="block-header">
                                <span class="block-type <?php echo $block['type']; ?>">
                                    <?php echo $block['type'] === 'main' ? 'Основной' : 'Дополнительный'; ?>
                                </span>
                                <span>Порядок: <?php echo $block['order']; ?></span>
                            </div>
                            
                            <h3>
                                <i class="<?php echo $block['icon']; ?>"></i>
                                <?php echo $block['title']; ?>
                                <span class="content-type <?php echo ($block['content_type'] ?? 'link') === 'content' ? 'content' : 'link'; ?>">
                                    <?php echo ($block['content_type'] ?? 'link') === 'content' ? 'Контент' : 'Ссылка'; ?>
                                </span>
                            </h3>
                            
                            <p><?php echo $block['description']; ?></p>
                            
                            <div>
                                <strong>Кнопка:</strong> "<?php echo $block['button_text']; ?>"
                                <?php if (($block['content_type'] ?? 'link') === 'content'): ?>
                                    <br><strong>Slug:</strong> <?php echo $block['content_slug'] ?? '—'; ?>
                                <?php else: ?>
                                    <br><strong>Ссылка:</strong> <?php echo $block['link']; ?>
                                <?php endif; ?>
                            </div>
                            
                            <div class="block-actions">
                                <a href="?edit=<?php echo $block['id']; ?>" class="button edit">Редактировать</a>
                                <?php if (($block['content_type'] ?? 'link') === 'content' && !empty($block['content_slug'])): ?>
                                    <a href="block.php?slug=<?php echo urlencode($block['content_slug']); ?>" class="button view" target="_blank">Просмотр</a>
                                <?php endif; ?>
                                <a href="?delete=<?php echo $block['id']; ?>" class="button delete" onclick="return confirm('Удалить?')">Удалить</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        // Выбор иконки
        document.addEventListener('click', function(e) {
            if (e.target.closest('.icon-option')) {
                const iconOption = e.target.closest('.icon-option');
                const icon = iconOption.getAttribute('data-icon');
                document.getElementById('icon-input').value = icon;
                
                // Снимаем выделение со всех
                document.querySelectorAll('.icon-option').forEach(opt => {
                    opt.classList.remove('selected');
                });
                // Выделяем выбранную
                iconOption.classList.add('selected');
            }
        });
        
        // Поиск иконок
        const iconSearch = document.getElementById('icon-search');
        if (iconSearch) {
            iconSearch.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                const categories = document.querySelectorAll('.icon-category');
                
                categories.forEach(category => {
                    const icons = category.querySelectorAll('.icon-option');
                    let visibleCount = 0;
                    
                    icons.forEach(icon => {
                        const iconName = icon.getAttribute('data-icon').toLowerCase();
                        if (iconName.includes(searchTerm)) {
                            icon.style.display = '';
                            visibleCount++;
                        } else {
                            icon.style.display = 'none';
                        }
                    });
                    
                    // Показывать/скрывать категорию в зависимости от видимых иконок
                    category.style.display = visibleCount > 0 ? 'block' : 'none';
                });
            });
        }
        
        // Переключение типа контента
        document.querySelectorAll('.toggle-option').forEach(option => {
            option.addEventListener('click', function() {
                const type = this.getAttribute('data-type');
                
                // Обновляем активный класс
                document.querySelectorAll('.toggle-option').forEach(opt => {
                    opt.classList.remove('active');
                });
                this.classList.add('active');
                
                // Обновляем скрытое поле
                document.getElementById('content_type').value = type;
                
                // Показываем/скрываем поля
                if (type === 'link') {
                    document.getElementById('link-fields').style.display = 'block';
                    document.getElementById('content-fields').style.display = 'none';
                } else {
                    document.getElementById('link-fields').style.display = 'none';
                    document.getElementById('content-fields').style.display = 'block';
                }
            });
        });
        
        // Автогенерация slug из заголовка
        const titleInput = document.querySelector('input[name="content_title"]');
        const slugInput = document.querySelector('input[name="content_slug"]');
        
        if (titleInput && slugInput) {
            titleInput.addEventListener('blur', function() {
                if (!slugInput.value.trim()) {
                    const title = this.value;
                    let slug = title.toLowerCase()
                        .replace(/[а-яё]/g, function(char) {
                            const translit = {
                                'а':'a','б':'b','в':'v','г':'g','д':'d','е':'e','ё':'yo','ж':'zh',
                                'з':'z','и':'i','й':'y','к':'k','л':'l','м':'m','н':'n','о':'o',
                                'п':'p','р':'r','с':'s','т':'t','у':'u','ф':'f','х':'h','ц':'ts',
                                'ч':'ch','ш':'sh','щ':'shch','ъ':'','ы':'y','ь':'','э':'e','ю':'yu','я':'ya'
                            };
                            return translit[char] || char;
                        })
                        .replace(/[^a-z0-9]+/g, '-')
                        .replace(/^-+|-+$/g, '')
                        .replace(/--+/g, '-');
                    
                    slugInput.value = slug;
                }
            });
        }
    </script>
</body>
</html>
