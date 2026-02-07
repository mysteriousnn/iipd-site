<?php
// block.php
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

$blocksFile = 'blocks-data.json';

// Получаем slug из URL
$slug = $_GET['slug'] ?? '';

// Загружаем блоки
if (file_exists($blocksFile)) {
    $blocks = json_decode(file_get_contents($blocksFile), true);
    if (!$blocks) $blocks = [];
} else {
    $blocks = [];
}

// Ищем блок по slug
$currentBlock = null;
foreach ($blocks as $block) {
    if (isset($block['content_slug']) && $block['content_slug'] === $slug) {
        $currentBlock = $block;
        break;
    }
}

// Если блок не найден, перенаправляем на главную
if (!$currentBlock) {
    header('Location: /');
    exit;
}

// Определяем заголовок для отображения
$displayTitle = $currentBlock['content_title'] ?? $currentBlock['title'];
$displayContent = $currentBlock['content'] ?? '<p style="text-align:center;color:rgba(255,255,255,0.5);font-style:italic;padding:40px;">Контент не найден</p>';

// Функция для обработки длинных ссылок в контенте
function wrapLongLinks($content) {
    // Обрабатываем ссылки, добавляя им возможность переноса
    $content = preg_replace_callback(
        '/<a\s[^>]*href=["\']([^"\']+)["\'][^>]*>(.*?)<\/a>/is',
        function($matches) {
            $url = $matches[1];
            $text = $matches[2];
            
            // Если текст ссылки очень длинный (более 30 символов), добавляем класс для переноса
            if (strlen($text) > 30) {
                return preg_replace(
                    '/<a\s([^>]*)>(.*?)<\/a>/is',
                    '<a $1 class="long-link">$2</a>',
                    $matches[0]
                );
            }
            return $matches[0];
        },
        $content
    );
    
    return $content;
}

$displayContent = wrapLongLinks($displayContent);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <!-- Google Analytics (GA4) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-HKF6P1HJ9Q"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'G-HKF6P1HJ9Q');
    </script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Добавьте эти мета-теги для предотвращения кеширования -->
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    
    <!-- Мета-теги для соцсетей -->
    <meta property="og:title" content="<?php echo htmlspecialchars($displayTitle); ?> - Ищу интернет подругу/друга">
    <meta property="og:description" content="<?php echo htmlspecialchars(strip_tags(substr($displayContent, 0, 150))); ?>...">
    <link rel="icon" href="/iipdPin.ico" type="image/x-icon">
    <meta property="og:url" content="<?php echo (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; ?>">
    <meta property="og:type" content="article">
    
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo htmlspecialchars($displayTitle); ?> - Ищу интернет подругу/друга">
    <meta name="twitter:description" content="<?php echo htmlspecialchars(strip_tags(substr($displayContent, 0, 150))); ?>...">
    <meta name="twitter:image" content="/iipdPin.ico">
    
    <title><?php echo htmlspecialchars($displayTitle); ?> - Ищу интернет подругу/друга</title>
    <link rel="icon" href="iipdPin.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
<style>
    /* ВСЕ стили из index.html */
    :root {
        /* Темная тема (по умолчанию) */
        --bg-primary: #1a1a1a;
        --bg-secondary: #262626;
        --bg-accent: rgba(38, 38, 38, 0.98);
        --text-primary: #ffffff;
        --text-secondary: rgba(255, 255, 255, 0.85);
        --accent-gradient: linear-gradient(135deg, #888, #aaa);
        --accent-orange: linear-gradient(135deg, #999, #bbb);
        --border-color: rgba(255, 255, 255, 0.08);
        --shadow-color: rgba(0, 0, 0, 0.4);
        --header-height: 70px;
        --menu-border-radius: 15px;
        --scrollbar-track: rgba(255, 255, 255, 0.05);
        --scrollbar-thumb: rgba(136, 136, 136, 0.5);
        --scrollbar-thumb-hover: rgba(136, 136, 136, 0.7);
        --theme-toggle-color: #ffffff;
        --theme-toggle-bg: rgba(255, 255, 255, 0.05);
        --orange-color: #ff5c20;
        --success-color: #a6bb7c;
        --success-bg: #2a3320; /* Непрозрачный зеленый фон для темной темы */
        --success-light: #e8f5e9; /* Непрозрачный светлый фон для светлой темы */
    }
    
    .light-theme {
        /* Светлая тема */
        --bg-primary: #f5f5f7;
        --bg-secondary: #ffffff;
        --bg-accent: rgba(255, 255, 255, 0.98);
        --text-primary: #1a1a1a;
        --text-secondary: rgba(26, 26, 26, 0.85);
        --accent-gradient: linear-gradient(135deg, #666, #888);
        --accent-orange: linear-gradient(135deg, #777, #999);
        --border-color: rgba(0, 0, 0, 0.12);
        --shadow-color: rgba(0, 0, 0, 0.1);
        --scrollbar-track: rgba(0, 0, 0, 0.05);
        --scrollbar-thumb: rgba(136, 136, 136, 0.3);
        --scrollbar-thumb-hover: rgba(136, 136, 136, 0.5);
        --theme-toggle-color: #1a1a1a;
        --theme-toggle-bg: rgba(0, 0, 0, 0.08);
        --menu-button-bg: rgba(0, 0, 0, 0.12);
        --menu-button-border: rgba(0, 0, 0, 0.18);
        --menu-button-text: #1a1a1a;
        --success-color: #5a8d3e;
        --success-bg: #e8f5e9; /* Непрозрачный зеленый фон для светлой темы */
    }
    
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        -webkit-tap-highlight-color: transparent;
    }
    
    html {
        scroll-behavior: auto;
        overflow: hidden;
        height: 100%;
    }
    
    body {
        background-color: var(--bg-primary);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        color: var(--text-primary);
        padding-top: var(--header-height);
        min-height: 100vh;
        position: relative;
        overflow-x: hidden;
        line-height: 1.6;
        height: 100vh;
        overflow-y: auto;
        -webkit-overflow-scrolling: touch;
        display: flex;
        flex-direction: column;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }
    
    .channel-description,
    .channel-text,
    .info-text,
    .button-description,
    .subtitle {
        -webkit-user-select: text;
        -moz-user-select: text;
        -ms-user-select: text;
        user-select: text;
    }
    
    body::-webkit-scrollbar {
        width: 0;
        height: 0;
        display: none;
    }
    
    body {
        scrollbar-width: none;
        -ms-overflow-style: none;
    }
    
    /* ===== Header & Navigation ===== */
    .header {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: var(--header-height);
        z-index: 3000;
        background-color: rgba(26, 26, 26, 0.95);
        box-shadow: 0 4px 25px rgba(0, 0, 0, 0.6);
        padding: 10px 0;
    }
    
    .light-theme .header {
        background-color: rgba(245, 245, 247, 0.95);
        box-shadow: 0 4px 25px rgba(0, 0, 0, 0.1);
    }

.container {
    width: 100%;
    max-width: 100%;
    margin: 0 auto;
    padding: 0 20px;
    height: 100%;
}
    
    .main-nav {
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: relative;
        height: 100%;
    }
    
    /* УПРОЩЕННЫЙ ЛОГОТИП - как в других файлах */
    .logo {
        position: absolute;
        left: 50%;
        top: 50%;
        transform: translate(-50%, -50%);
        text-decoration: none;
        cursor: pointer;
        z-index: 1002;
        user-select: none;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        padding: 0;
    }
    
    .logo:hover {
        opacity: 0.8;
    }
    
    .logo-icon {
        font-size: 1.8rem;
        color: var(--text-primary);
    }
    
    .logo-text {
        font-family: 'Segoe UI', 'Arial', sans-serif;
        font-weight: 700;
        font-size: 1.8rem;
        letter-spacing: 0.5px;
        color: var(--text-primary);
    }
    
    .light-theme .logo-icon,
    .light-theme .logo-text {
        color: var(--text-primary);
    }
    
    /* Кнопка переключения темы */
    .theme-toggle {
        position: absolute;
        right: 80px; /* Увеличено расстояние от правого края */
        top: 50%;
        transform: translateY(-50%);
        width: 44px;
        height: 44px;
        background: var(--theme-toggle-bg);
        border: 1px solid var(--border-color);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--theme-toggle-color);
        cursor: pointer;
        text-decoration: none;
        z-index: 1002;
        -webkit-touch-callout: none;
    }
    
    .theme-toggle:hover {
        background: rgba(255, 255, 255, 0.1);
        color: var(--text-primary);
        border-color: rgba(255, 255, 255, 0.2);
    }
    
    .light-theme .theme-toggle:hover {
        background: rgba(0, 0, 0, 0.15);
        border-color: rgba(0, 0, 0, 0.25);
    }
    
    .theme-toggle i {
        font-size: 1.2rem;
    }
    
    /* Иконка луны для темной темы */
    .theme-toggle .fa-moon {
        display: block;
    }
    
    .theme-toggle .fa-sun {
        display: none;
    }
    
    /* В светлой теме показываем солнце, скрываем луну */
    .light-theme .theme-toggle .fa-moon {
        display: none;
    }
    
    .light-theme .theme-toggle .fa-sun {
        display: block;
    }
    
    .telegram-icon {
        position: absolute;
        right: 20px;
        top: 50%;
        transform: translateY(-50%);
        width: 44px;
        height: 44px;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid var(--border-color);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--text-secondary);
        cursor: pointer;
        text-decoration: none;
        z-index: 1002;
        -webkit-touch-callout: none;
    }
    
    .telegram-icon:hover {
        background: rgba(255, 255, 255, 0.1);
        color: var(--text-primary);
        border-color: rgba(255, 255, 255, 0.2);
    }
    
    .light-theme .telegram-icon {
        background: rgba(0, 0, 0, 0.05);
        color: rgba(26, 26, 26, 0.85);
    }
    
    .light-theme .telegram-icon:hover {
        background: rgba(0, 0, 0, 0.1);
        color: var(--text-primary);
        border-color: rgba(0, 0, 0, 0.2);
    }
    
    .telegram-icon i {
        font-size: 1.3rem;
    }
    
    .menu-toggle {
        background-color: var(--bg-secondary);
        border: 1px solid var(--border-color);
        color: var(--text-primary);
        padding: 12px 24px;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 10px;
        user-select: none;
        z-index: 1002;
        position: relative;
        top: 2px;
        border: none;
        outline: none;
        -webkit-tap-highlight-color: transparent;
        -webkit-touch-callout: none;
    }
    
    /* Усиленная видимость кнопки меню в светлой теме */
    .light-theme .menu-toggle {
        background-color: var(--menu-button-bg);
        border-color: var(--menu-button-border);
        color: var(--menu-button-text);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }
    
    button, 
    .menu-toggle,
    .dropdown-toggle,
    .button-link,
    .nav-link,
    .dropdown-item {
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        -webkit-tap-highlight-color: rgba(0, 0, 0, 0);
        -webkit-tap-highlight-color: transparent;
    }
    
    .menu-toggle:active,
    .dropdown-toggle:active,
    .button-link:active,
    .nav-link:active,
    .dropdown-item:active,
    .telegram-icon:active,
    .theme-toggle:active {
        background-color: rgba(255, 255, 255, 0.15) !important;
    }
    
    .light-theme .menu-toggle:active,
    .light-theme .dropdown-toggle:active,
    .light-theme .button-link:active,
    .light-theme .nav-link:active,
    .light-theme .dropdown-item:active,
    .light-theme .telegram-icon:active,
    .light-theme .theme-toggle:active {
        background-color: rgba(0, 0, 0, 0.15) !important;
    }
    
    .nav-list {
        display: none;
        flex-direction: column;
        width: calc(100% - 40px);
        max-width: 400px;
        position: fixed;
        top: calc(var(--header-height) + 15px);
        left: 50%;
        transform: translateX(-50%);
        background-color: var(--bg-secondary);
        padding: 0;
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.7);
        border: 1px solid var(--border-color);
        border-radius: var(--menu-border-radius);
        z-index: 1001;
        max-height: 85vh;
        overflow-y: auto;
        -webkit-overflow-scrolling: touch;
        overscroll-behavior: contain;
        list-style: none;
    }
    
    .light-theme .nav-list {
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
    }
    
    .nav-list::-webkit-scrollbar {
        width: 8px;
    }
    
    .nav-list::-webkit-scrollbar-track {
        background: var(--scrollbar-track);
        border-radius: 4px;
        margin: 8px 0;
    }
    
    .nav-list::-webkit-scrollbar-thumb {
        background: var(--scrollbar-thumb);
        border-radius: 4px;
        border: 2px solid var(--bg-secondary);
    }
    
    .nav-list::-webkit-scrollbar-thumb:hover {
        background: var(--scrollbar-thumb-hover);
    }
    
    .nav-list {
        scrollbar-width: thin;
        scrollbar-color: var(--scrollbar-thumb) var(--scrollbar-track);
    }
    
    .nav-list.active {
        display: flex;
    }
    
    .nav-item {
        position: relative;
        width: 100%;
    }
    
    .nav-item:first-child .nav-link {
        border-radius: var(--menu-border-radius) var(--menu-border-radius) 0 0;
    }
    
    .nav-footer {
        background-color: rgba(0, 0, 0, 0.2);
        padding: 20px;
        border-top: 1px solid var(--border-color);
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 15px;
        border-radius: 0 0 var(--menu-border-radius) var(--menu-border-radius);
        flex-shrink: 0;
    }
    
    .light-theme .nav-footer {
        background-color: rgba(0, 0, 0, 0.05);
    }
    
    .nav-footer-text {
        font-size: 0.9rem;
        color: var(--text-secondary);
        text-align: center;
        line-height: 1.5;
    }
    
    .nav-footer-link {
        color: #aaa;
        text-decoration: none;
        font-weight: 600;
        border-bottom: 1px solid transparent;
    }
    
    .nav-footer-link:hover {
        color: var(--text-primary);
        border-bottom: 1px solid var(--text-primary);
    }
    
    .nav-footer-year {
        color: rgba(255, 255, 255, 0.6);
        font-size: 0.85rem;
        margin-top: 5px;
    }
    
    .light-theme .nav-footer-year {
        color: rgba(26, 26, 26, 0.6);
    }
    
    .nav-item:last-child .nav-link {
        border-radius: 0;
    }
    
    .nav-link {
        color: var(--text-secondary);
        text-decoration: none;
        padding: 18px 24px;
        font-weight: 600;
        font-size: 1rem;
        border: none;
        display: flex;
        align-items: center;
        justify-content: flex-start;
        width: 100%;
        background-color: transparent;
        position: relative;
        cursor: pointer;
        -webkit-tap-highlight-color: transparent;
        -webkit-touch-callout: none;
        user-select: none;
        flex-shrink: 0;
    }
    
    .nav-link i {
        font-size: 1.1rem;
        margin-right: 12px;
        width: 24px;
        text-align: center;
    }
    
    .link-text {
        flex-grow: 1;
        text-align: left;
    }
    
    .dropdown-menu {
        width: 100%;
        background-color: var(--bg-secondary);
        padding: 0;
        margin: 0;
        display: none;
        overflow: hidden;
        flex-shrink: 0;
    }
    
    .dropdown.active .dropdown-menu {
        display: block;
    }
    
    .nav-item.dropdown:nth-last-child(3) .nav-link {
        border-radius: 0;
    }
    
    .nav-item.dropdown:nth-last-child(3) .dropdown-menu {
        border-radius: 0;
    }
    
    .nav-item.dropdown:nth-last-child(2) .nav-link {
        border-radius: 0;
    }
    
    .nav-item.dropdown:nth-last-child(2) .dropdown-menu {
        border-radius: 0;
    }
    
    .dropdown-item {
        display: flex;
        align-items: center;
        padding: 16px 48px;
        color: var(--text-secondary);
        text-decoration: none;
        position: relative;
        min-height: 48px;
        justify-content: flex-start;
        cursor: pointer;
        -webkit-tap-highlight-color: transparent;
        -webkit-touch-callout: none;
        user-select: none;
        flex-shrink: 0;
    }
    
    .dropdown-item:last-child {
        border-radius: 0;
    }
    
    .dropdown-item::before {
        content: '';
        position: absolute;
        left: 32px;
        top: 50%;
        transform: translateY(-50%) rotate(45deg);
        width: 8px;
        height: 8px;
        background: #888;
        flex-shrink: 0;
    }
    
    .dropdown-item-text {
        flex: 1;
        padding-left: 8px;
        text-align: left;
    }
    
    .dropdown-item::after {
        content: ' ➥';
        color: #888;
        font-weight: bold;
        margin-left: auto;
    }
    
    .dropdown-toggle::after {
        content: '';
        display: inline-block;
        width: 8px;
        height: 8px;
        border-right: 2px solid currentColor;
        border-bottom: 2px solid currentColor;
        transform: rotate(45deg);
        margin-left: 12px;
        flex-shrink: 0;
    }
    
    .dropdown.active .dropdown-toggle::after {
        transform: rotate(-135deg);
    }
    
    .nav-link:hover, 
    .nav-link.active,
    .dropdown.active .dropdown-toggle {
        background-color: rgba(255, 255, 255, 0.08);
        color: var(--text-primary);
    }
    
    .light-theme .nav-link:hover, 
    .light-theme .nav-link.active,
    .light-theme .dropdown.active .dropdown-toggle {
        background-color: rgba(0, 0, 0, 0.08);
    }
    
    .dropdown-item:hover {
        background-color: rgba(255, 255, 255, 0.08);
        color: var(--text-primary);
    }
    
    .light-theme .dropdown-item:hover {
        background-color: rgba(0, 0, 0, 0.08);
    }
    
    /* ===== Main Content ===== */
    .content {
        padding: 20px 0 40px 0;
        flex: 1 0 auto;
        width: 100%;
    }
    
    /* Decorative Background */
    .decorative-bg {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
        z-index: 0;
        opacity: 0.02;
        background: transparent;
    }
    
    .light-theme .decorative-bg {
        opacity: 0.03;
        background: transparent;
    }
    
    .menu-overlay {
        position: fixed;
        top: var(--header-height);
        left: 0;
        width: 100%;
        height: calc(100% - var(--header-height));
        background: rgba(0, 0, 0, 0.5);
        z-index: 999;
        display: none;
        backdrop-filter: blur(2px);
    }
    
    .menu-overlay.active {
        display: block;
    }
    
    /* ===== LOAD LEVEL SECTION (для контента блока) ===== */
    .load-level-section {
        position: relative;
        background-color: var(--bg-secondary);
        border-radius: var(--menu-border-radius);
        padding: 35px 30px;
        margin-bottom: 40px;
        border: 1px solid var(--border-color);
        box-shadow: 0 5px 25px rgba(0, 0, 0, 0.4);
        overflow: hidden;
        border-left: 5px solid #888;
    }
    
    .light-theme .load-level-section {
        box-shadow: 0 5px 25px rgba(0, 0, 0, 0.1);
    }
    
    .level-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
        flex-wrap: wrap;
        gap: 15px;
        position: relative;
        z-index: 2;
    }
    
    .level-title {
        font-size: 1.8rem;
        font-weight: 700;
        color: var(--text-primary);
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .level-icon {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(136, 136, 136, 0.1);
        border-radius: 10px;
        font-size: 1.4rem;
        color: #888;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        aspect-ratio: 1 / 1;
        flex-shrink: 0;
    }
    
    .current-level {
        font-size: 1.1rem;
        font-weight: 600;
        padding: 10px 25px;
        background: rgba(136, 136, 136, 0.1);
        border: 1px solid #888;
        color: #888;
        border-radius: 50px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .level-description {
        background: rgba(255, 255, 255, 0.03);
        border-radius: 15px;
        padding: 25px;
        margin-bottom: 30px;
        border: 1px solid var(--border-color);
        position: relative;
        z-index: 2;
    }
    
    .light-theme .level-description {
        border: 1px solid rgba(0, 0, 0, 0.1);
    }
    
    .level-description-text {
        font-size: 1.1rem;
        line-height: 1.7;
        color: var(--text-secondary);
        word-wrap: break-word;
        overflow-wrap: break-word;
        word-break: break-word;
        hyphens: auto;
    }
    
    /* Стили для длинных ссылок в контенте */
    .level-description-text a {
        word-break: break-word;
        overflow-wrap: anywhere;
        hyphens: auto;
    }
    
    /* Специальный класс для очень длинных ссылок */
    .long-link {
        display: inline-block;
        max-width: 100%;
        word-break: break-all !important;
        overflow-wrap: anywhere !important;
        hyphens: auto !important;
    }
    
    /* Обработка длинных URL внутри текста */
    .level-description-text {
        overflow-wrap: break-word;
        word-wrap: break-word;
        -ms-word-break: break-all;
        word-break: break-word;
        -webkit-hyphens: auto;
        -moz-hyphens: auto;
        hyphens: auto;
    }
    
    /* Для мобильных устройств делаем перенос более агрессивным */
    @media (max-width: 768px) {
        .level-description-text {
            word-break: break-word;
            overflow-wrap: break-word;
        }
        
        .level-description-text a {
            word-break: break-all;
            overflow-wrap: anywhere;
            display: inline-block;
            max-width: 100%;
        }
        
        .long-link {
            word-break: break-all !important;
            overflow-wrap: anywhere !important;
            display: inline-block;
            max-width: 100%;
        }
    }
    
    /* Для очень маленьких экранов */
    @media (max-width: 480px) {
        .level-description-text a {
            font-size: 0.95rem;
            line-height: 1.4;
        }
        
        .long-link {
            font-size: 0.9rem;
            line-height: 1.3;
        }
    }
    
    .level-description-text a {
        color: #aaa;
        text-decoration: underline;
        font-weight: 600;
    }
    
    .level-description-text a:hover {
        color: var(--text-primary);
    }
    
    .light-theme .level-description-text a {
        color: #666;
    }
    
    .light-theme .level-description-text a:hover {
        color: var(--text-primary);
    }
    
    .level-description-text p {
        margin-bottom: 1rem;
    }
    
    .level-description-text ul,
    .level-description-text ol {
        margin-bottom: 1rem;
        padding-left: 1.5rem;
    }
    
    .level-description-text li {
        margin-bottom: 0.5rem;
    }
    
    .level-description-text strong {
        font-weight: 700;
        color: var(--text-primary);
    }
    
    .level-description-text em {
        font-style: italic;
    }
    
    .level-description-text h3,
    .level-description-text h4 {
        margin: 1.5rem 0 1rem 0;
        color: var(--text-primary);
        font-weight: 600;
    }
    
    .level-description-text h3 {
        font-size: 1.3rem;
    }
    
    .level-description-text h4 {
        font-size: 1.1rem;
    }
    
    /* ===== КНОПКИ ДЕЙСТВИЙ ===== */
    .comment-actions {
        display: flex;
        gap: 15px;
        margin-top: 30px;
        padding-top: 20px;
        border-top: 1px solid var(--border-color);
    }
    
    @media (max-width: 480px) {
        .comment-actions {
            flex-direction: column;
            gap: 10px;
        }
    }
    
    .button-link {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        width: 100%;
        background: rgba(255, 255, 255, 0.05);
        color: white;
        text-decoration: none;
        padding: 14px 20px;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.95rem;
        border: 1px solid var(--border-color);
        cursor: pointer;
        position: relative;
        z-index: 2;
        transition: all 0.3s ease;
    }
    
    .light-theme .button-link {
        background: rgba(0, 0, 0, 0.05);
        color: var(--text-primary);
    }
    
    .button-link:hover {
        background: rgba(255, 255, 255, 0.1);
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    }
    
    .light-theme .button-link:hover {
        background: rgba(0, 0, 0, 0.1);
    }
    
    .share-button {
        background: rgba(59, 130, 246, 0.1);
        color: #3b82f6;
        border: 1px solid #3b82f6;
    }
    
    .share-button:hover {
        background: rgba(59, 130, 246, 0.2);
    }
    
    .back-button {
        background: rgba(255, 255, 255, 0.05);
        color: var(--text-secondary);
        border: 1px solid var(--border-color);
    }
    
    .back-button:hover {
        background: rgba(255, 255, 255, 0.1);
        color: var(--text-primary);
    }
    
    /* ===== УВЕДОМЛЕНИЕ О КОПИРОВАНИИ (НОВЫЙ ДИЗАЙН) ===== */
    .copy-notification {
        position: fixed;
        top: 90px;
        right: 20px;
        background: var(--success-bg); /* Непрозрачный фон */
        color: var(--success-color);
        padding: 16px 24px;
        border-radius: 12px;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.3);
        z-index: 10000;
        display: flex;
        align-items: center;
        gap: 12px;
        max-width: 350px;
        border: 1px solid var(--success-color);
        opacity: 0;
        transform: translateX(100px) translateY(-20px);
        transition: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        pointer-events: none;
    }
    
    .copy-notification.show {
        opacity: 1;
        transform: translateX(0) translateY(0);
    }
    
    .copy-notification.hide {
        opacity: 0;
        transform: translateX(100px) translateY(-20px);
    }
    
    .copy-notification-icon {
        width: 36px;
        height: 36px;
        background: var(--success-color);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        color: white;
        flex-shrink: 0;
    }
    
    .copy-notification-content {
        flex-grow: 1;
    }
    
    .copy-notification-title {
        font-weight: 700;
        font-size: 1.1rem;
        margin-bottom: 4px;
        color: var(--success-color);
    }
    
    .copy-notification-text {
        font-size: 0.95rem;
        opacity: 0.9;
        line-height: 1.4;
    }
    
    .light-theme .copy-notification {
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
        background: var(--success-bg); /* Непрозрачный фон */
        color: var(--success-color);
        border: 1px solid var(--success-color);
    }
    
    /* Анимация для иконки проверки */
    @keyframes checkmark {
        0% {
            transform: scale(0);
            opacity: 0;
        }
        50% {
            transform: scale(1.2);
        }
        100% {
            transform: scale(1);
            opacity: 1;
        }
    }
    
    .copy-notification.show .copy-notification-icon i {
        animation: checkmark 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55);
    }
    
    /* ===== Media Queries ===== */
    @media (min-width: 769px) {
        .nav-list {
            width: 400px;
            max-width: 400px;
        }
    }
    
    @media (max-width: 768px) {
        .nav-list {
            width: calc(100% - 40px);
            max-width: calc(100% - 40px);
            max-height: 80vh;
        }
        
        /* Адаптация логотипа для планшетов */
        .logo {
            gap: 8px;
        }
        
        .logo-icon {
            font-size: 1.6rem;
        }
        
        .logo-text {
            font-size: 1.6rem;
        }
        
        .load-level-section {
            padding: 25px 20px;
        }
        
        .level-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }
        
        .current-level {
            align-self: flex-start;
        }
        
        .level-title {
            font-size: 1.6rem;
        }
        
        /* Адаптация кнопки темы для планшетов */
        .theme-toggle {
            right: 70px;
        }
        
        .copy-notification {
            top: 80px;
            right: 15px;
            left: 15px;
            max-width: calc(100% - 30px);
            transform: translateY(-30px);
        }
        
        .copy-notification.show {
            transform: translateY(0);
        }
        
        .copy-notification.hide {
            transform: translateY(-30px);
        }
    }
    
    @media (max-width: 480px) {
        .content {
            padding: 20px 0 30px 0;
        }
        
        .nav-list {
            max-height: 75vh;
            top: calc(var(--header-height) + 10px);
        }
        
        /* Адаптация логотипа для мобильных - ТОЧНО КАК В INDEX.HTML */
        .logo {
            gap: 6px;
            left: 50%;
            transform: translateX(-50%) translateY(-50%);
        }
        
        .logo-icon {
            font-size: 1.4rem;
        }
        
        .logo-text {
            font-size: 1.4rem;
            letter-spacing: 0;
        }
        
        .nav-list::-webkit-scrollbar {
            width: 6px;
        }
        
        .container {
            padding: 0 15px;
        }
        
        .load-level-section {
            padding: 20px 15px;
            margin-bottom: 30px;
        }
        
        .level-title {
            font-size: 1.4rem;
        }
        
        .level-icon {
            width: 35px;
            height: 35px;
            font-size: 1.2rem;
        }
        
        .current-level {
            font-size: 1rem;
            padding: 8px 20px;
        }
        
        .level-description {
            padding: 20px;
        }
        
        .button-link {
            padding: 12px 16px;
        }
        
        /* Header элементы для мобильных - ТОЧНО КАК В INDEX.HTML */
        .telegram-icon {
            right: 15px;
            width: 40px;
            height: 40px;
        }
        
        .theme-toggle {
            right: 70px; /* Правильное положение - ТОЧНО КАК В INDEX.HTML */
            width: 40px;
            height: 40px;
        }
        
        .telegram-icon i {
            font-size: 1.1rem;
        }
        
        .theme-toggle i {
            font-size: 1.1rem;
        }
        
        .menu-toggle {
            padding: 10px 20px;
            font-size: 0.9rem;
        }
        
        .nav-link {
            padding: 16px 20px;
            font-size: 0.95rem;
        }
        
        .dropdown-item {
            padding: 14px 40px;
        }
        
        .dropdown-item::before {
            left: 24px;
        }
        
        .nav-footer {
            padding: 15px;
        }
        
        .nav-footer-text {
            font-size: 0.85rem;
        }
        
        .copy-notification {
            padding: 14px 18px;
            gap: 10px;
            top: 75px;
            right: 10px;
            left: 10px;
            max-width: calc(100% - 20px);
        }
        
        .copy-notification-icon {
            width: 32px;
            height: 32px;
            font-size: 1rem;
        }
        
        .copy-notification-title {
            font-size: 1rem;
        }
        
        .copy-notification-text {
            font-size: 0.9rem;
        }
    }
    
    @media (max-width: 380px) {
        .telegram-icon {
            right: 12px;
            width: 38px;
            height: 38px;
        }
        
        .theme-toggle {
            right: 67px; /* Правильное положение для очень маленьких экранов */
            width: 38px;
            height: 38px;
        }
        
        .telegram-icon i {
            font-size: 1rem;
        }
        
        .theme-toggle i {
            font-size: 1rem;
        }
        
        .menu-toggle {
            padding: 8px 16px;
            font-size: 0.85rem;
        }
        
        .load-level-section {
            padding: 15px;
        }
        
        .copy-notification {
            padding: 12px 15px;
            gap: 8px;
        }
        
        .copy-notification-icon {
            width: 28px;
            height: 28px;
            font-size: 0.9rem;
        }
        
        .copy-notification-title {
            font-size: 0.95rem;
        }
        
        .copy-notification-text {
            font-size: 0.85rem;
        }
    }
    
    @media (max-width: 350px) {
        .logo {
            gap: 4px;
        }
        
        .logo-icon {
            font-size: 1.2rem;
        }
        
        .logo-text {
            font-size: 1.2rem;
        }
        
        .telegram-icon {
            right: 10px;
            width: 36px;
            height: 36px;
        }
        
        .theme-toggle {
            right: 65px; /* Правильное положение для экстремально маленьких экранов */
            width: 36px;
            height: 36px;
        }
        
        .menu-toggle {
            padding: 8px 14px;
            font-size: 0.8rem;
        }
        
        .container {
            padding: 0 12px;
        }
    }
    
    @media (max-width: 320px) {
        .telegram-icon {
            right: 8px;
            width: 34px;
            height: 34px;
        }
        
        .theme-toggle {
            right: 63px;
            width: 34px;
            height: 34px;
        }
        
        .telegram-icon i {
            font-size: 0.9rem;
        }
        
        .theme-toggle i {
            font-size: 0.9rem;
        }
        
        .menu-toggle {
            padding: 7px 12px;
            font-size: 0.75rem;
        }
        
        .logo-icon {
            font-size: 1.1rem;
        }
        
        .logo-text {
            font-size: 1.1rem;
        }
    }
    
    @media (max-height: 500px) {
        .nav-list {
            max-height: 65vh;
        }
    }
    
    @media (min-width: 1400px) {
        .container {
            max-width: 1300px;
        }
    }
    
    /* Изменение цвета выделения текста */
    ::selection {
        background-color: rgba(136, 136, 136, 0.4);
        color: #ffffff;
    }

    ::-moz-selection {
        background-color: rgba(136, 136, 136, 0.4);
        color: #ffffff;
    }

    ::-webkit-selection {
        background-color: rgba(136, 136, 136, 0.4);
        color: #ffffff;
    }
    
    .light-theme ::selection {
        background-color: rgba(136, 136, 136, 0.3);
        color: #1a1a1a;
    }
    
    .light-theme ::-moz-selection {
        background-color: rgba(136, 136, 136, 0.3);
        color: #1a1a1a;
    }
    
    .light-theme ::-webkit-selection {
        background-color: rgba(136, 136, 136, 0.3);
        color: #1a1a1a;
    }
    
    /* 1. Убираем максимальную ширину у контейнера */
.container {
    width: 100%;
    max-width: 100%; /* Убираем ограничение 1200px */
    margin: 0 auto;
    padding: 0 20px;
    height: 100%;
}

/* 2. Добавляем адаптивные отступы для очень широких экранов */
@media (min-width: 1400px) {
    .container {
        padding: 0 40px;
    }
    
    .buttons-grid {
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    }
    
    .additional-grid {
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    }
}

@media (min-width: 1920px) {
    .container {
        padding: 0 60px;
    }
    
    .buttons-grid {
        grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
    }
    
    .additional-grid {
        grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
    }
}

/* 3. Делаем секции более адаптивными */
.channel-description,
.load-level-section,
.comments-section,
.chat-animation-section,
.info-section {
    width: 100%;
    max-width: 100%;
}

/* 4. Улучшаем сетку для широких экранов */
@media (min-width: 1600px) {
    .buttons-grid {
        grid-template-columns: repeat(auto-fill, minmax(370px, 1fr));
        gap: 30px;
    }
}

/* 5. Гарантируем, что контент растягивается */
.content {
    width: 100%;
    max-width: 100%;
}

/* Стили для ссылок в комментариях */
.comment-link {
    text-decoration: none;
    color: inherit;
    display: block;
}

.comment-link:hover {
    text-decoration: none;
}

/* ===== ПОДВАЛ ===== */
.footer {
    background-color: var(--bg-secondary);
    border-top: 1px solid var(--border-color);
    padding: 30px 0 20px;
    margin-top: auto;
    flex-shrink: 0;
}

.footer-content {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 40px;
    margin-bottom: 40px;
}

.footer-section {
    display: flex;
    flex-direction: column;
}

.footer-logo {
    display: flex;
    align-items: center;
    gap: 10px;
    color: var(--text-primary);
    text-decoration: none;
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 15px;
}

.footer-logo i {
    font-size: 1.8rem;
}

.footer-logo-text {
    background: var(--accent-gradient);
    -webkit-background-clip: text;
    background-clip: text;
    -webkit-text-fill-color: transparent;
}

.footer-description {
    color: var(--text-secondary);
    font-size: 0.95rem;
    line-height: 1.5;
    margin-bottom: 20px;
}

.footer-social {
    display: flex;
    gap: 15px;
    margin-top: 10px;
}

.social-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    text-decoration: none;
        transition: all 0.3s ease;
    font-size: 1.1rem;
}

.social-icon.telegram {
    background: linear-gradient(135deg, #0088cc, #005c8a);
}

.social-icon.chat {
    background: linear-gradient(135deg, #25D366, #128C7E);
}

.social-icon.bot {
    background: linear-gradient(135deg, #888, #666);
}

.social-icon:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
}

.footer-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 20px;
    position: relative;
    padding-bottom: 10px;
}

.footer-title::after {
    content: '';
    position: absolute;
    left: 0;
    bottom: 0;
    width: 40px;
    height: 3px;
    background: var(--accent-gradient);
    border-radius: 2px;
}

.footer-links {
    list-style: none;
    padding: 0;
    margin: 0;
}

.footer-links li {
    margin-bottom: 12px;
}

.footer-links a {
    color: var(--text-secondary);
    text-decoration: none;
    font-size: 0.95rem;
    transition: all 0.2s ease;
    display: inline-block;
}

.footer-links a:hover {
    color: var(--text-primary);
    transform: translateX(5px);
}

.footer-bottom {
    text-align: center;
    padding-top: 30px;
    border-top: 1px solid var(--border-color);
}

.copyright {
    color: var(--text-secondary);
    font-size: 0.9rem;
    margin-bottom: 10px;
}

.footer-note {
    color: var(--text-secondary);
    font-size: 0.9rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.footer-note i {
    color: #ff5c20;
    font-size: 0.8rem;
}

/* Адаптивность для подвала */
@media (max-width: 768px) {
    .footer {
        padding: 15px 0 20px;
    }
    
    .footer-content {
        gap: 30px;
        margin-bottom: 30px;
    }
    
    .footer-social {
        justify-content: flex-start;
    }
}

@media (max-width: 480px) {
    .footer-content {
        grid-template-columns: 1fr;
        gap: 20px; /* Уменьшаем отступ между секциями */
    }
    
    .footer-section {
        text-align: left; /* Было: center - меняем на left */
    }
    
    .footer-logo {
        justify-content: flex-start; /* Было: center - выравниваем по левому краю */
    }
    
    .footer-title::after {
        left: 0; /* Было: left: 50%; transform: translateX(-50%) */
        transform: none; /* Убираем трансформацию */
    }
    
    .footer-social {
        justify-content: flex-start; /* Было: center - выравниваем по левому краю */
    }
    
    .footer-links a:hover {
        transform: translateX(5px); /* Возвращаем анимацию наведения */
    }
}
    
</style>
</head>
<body>
    <!-- Уведомление о копировании -->
    <div id="copy-notification" class="copy-notification">
        <div class="copy-notification-icon">
            <i class="fas fa-check"></i>
        </div>
        <div class="copy-notification-content">
            <div class="copy-notification-title">Ссылка скопирована!</div>
            <div class="copy-notification-text">Теперь вы можете поделиться этой страницей</div>
        </div>
    </div>
    
    <!-- Decorative Background -->
    <div class="decorative-bg"></div>
    
    <!-- Menu Overlay -->
    <div class="menu-overlay" id="menu-overlay"></div>
    
    <!-- Header (ТОЧНО ТАКОЙ ЖЕ КАК В index.html) -->
    <header class="header" id="header">
        <div class="container">
            <nav class="main-nav">
                <!-- Кнопка переключения темы -->
                <button class="theme-toggle" id="theme-toggle" title="Переключить тему">
                    <i class="fas fa-moon"></i>
                    <i class="fas fa-sun"></i>
                </button>
                
                <!-- Telegram Icon -->
                <a href="https://t.me/ishupodrygyilidryga" class="telegram-icon" target="_blank" rel="noopener noreferrer" title="Перейти в Telegram канал">
                    <i class="fab fa-telegram"></i>
                </a>
                
                <!-- УПРОЩЕННЫЙ ЛОГОТИП - как в других файдах -->
                <a href="https://ishupodrygyilidryga.fun" class="logo" id="logo" title="Перейти на главную страницу">
                    <i class="fas fa-users logo-icon"></i>
                    <span class="logo-text">iipd</span>
                </a>
                
                <!-- Menu Toggle -->
                <button class="menu-toggle" id="menu-toggle">
                    <i class="fas fa-bars"></i>
                    <span>Меню</span>
                </button>
                
                <!-- Navigation Menu -->
                <ul class="nav-list" id="nav-list">
                    <li class="nav-item">
                        <a href="https://ishupodrygyilidryga.fun" class="nav-link">
                            <span class="link-text">
                                <i class="fas fa-home"></i> Главная
                            </span>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a href="https://t.me/ishupodrygyilidryga" class="nav-link" target="_blank" rel="noopener noreferrer">
                            <span class="link-text">
                                <i class="fab fa-telegram"></i> Наш канал
                            </span>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a href="https://t.me/ankety_bot" class="nav-link" target="_blank" rel="noopener noreferrer">
                            <span class="link-text">
                                <i class="fas fa-robot"></i> Бот для анкет
                            </span>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a href="https://t.me/lymikanon" class="nav-link" target="_blank" rel="noopener noreferrer">
                            <span class="link-text">
                                <i class="fas fa-comments"></i> Наш чат
                            </span>
                        </a>
                    </li>
                    
                    <!-- Tgstat с подменю -->
                    <li class="nav-item dropdown">
                        <button class="nav-link dropdown-toggle">
                            <span class="link-text">
                                <i class="fas fa-chart-line"></i> Tgstat
                            </span>
                        </button>
                        <div class="dropdown-menu">
                            <a href="https://uk.tgstat.com/channel/@ishupodrygyilidryga" class="dropdown-item" target="_blank" rel="noopener noreferrer">
                                <span class="dropdown-item-text">iipd</span>
                            </a>
                            <a href="https://uk.tgstat.com/channel/@surrealtouch" class="dropdown-item" target="_blank" rel="noopener noreferrer">
                                <span class="dropdown-item-text">surrealtouch</span>
                            </a>
                        </div>
                    </li>
                    
                    <!-- Стать Админом с подменю -->
                    <li class="nav-item dropdown">
                        <button class="nav-link dropdown-toggle">
                            <span class="link-text">
                                <i class="fas fa-user-shield"></i> Стать Админом
                            </span>
                        </button>
                        <div class="dropdown-menu">
                            <a href="https://t.me/info_ishupodrygyilidryga/27" class="dropdown-item" target="_blank" rel="noopener noreferrer">
                                <span class="dropdown-item-text">По Анкетам</span>
                            </a>
                            <a href="https://t.me/info_ishupodrygyilidryga/897" class="dropdown-item" target="_blank" rel="noopener noreferrer">
                                <span class="dropdown-item-text">По Аватаркам</span>
                            </a>
                        </div>
                    </li>
                    
                    <!-- Footer in menu -->
                    <li class="nav-footer">
                        <p class="nav-footer-text">
                            Ищу интернет подругу/друга (WEB) © 
                            <a href="https://ishupodrygyilidryga.fun" class="nav-footer-link" target="_blank" rel="noopener noreferrer">ishupodrygyilidryga.fun</a>
                        </p>
                        <p class="nav-footer-year"><?php echo date('Y'); ?></p>
                    </li>
                </ul>
            </nav>
        </div>
    </header>
    
    <!-- Main Content -->
    <main class="content">
        <div class="container">
            <!-- Контент блока -->
            <section class="load-level-section" id="block-content">
                <div class="level-header">
                    <h2 class="level-title">
                        <span class="level-icon">
                            <i class="<?php echo htmlspecialchars($currentBlock['icon'] ?? 'fas fa-info-circle'); ?>"></i>
                        </span>
                        <?php echo htmlspecialchars($displayTitle); ?>
                    </h2>
                    <div class="current-level">
                        <i class="fas fa-cube"></i>
                        <span>Информация</span>
                    </div>
                </div>
                
                <div class="level-description">
                    <div class="level-description-text">
                        <?php echo $displayContent; ?>
                    </div>
                </div>
                
                <!-- Действия -->
                <div class="comment-actions">
                    <button id="share-block" class="button-link share-button">
                        <i class="fas fa-share-alt"></i>
                        <span>Поделиться страницей</span>
                    </button>
                    <a href="/" class="button-link back-button">
                        <i class="fas fa-arrow-left"></i>
                        <span>На главную</span>
                    </a>
                </div>
            </section>
        </div>
    </main>
    
                <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <!-- Основная информация -->
                <div class="footer-section">
                    <a href="https://ishupodrygyilidryga.fun" class="footer-logo">
                        <i class="fas fa-users"></i>
                        <span class="footer-logo-text">iipd</span>
                    </a>
                    <p class="footer-description">
                        Канал для поиска новых друзей и интересного общения в интернете
                    </p>
                    <div class="footer-social">
                        <a href="https://t.me/ishupodrygyilidryga" class="social-icon telegram" target="_blank" rel="noopener noreferrer" title="Telegram канал">
                            <i class="fab fa-telegram"></i>
                        </a>
                        <a href="https://t.me/lymikanon" class="social-icon chat" target="_blank" rel="noopener noreferrer" title="Наш чат">
                            <i class="fas fa-comments"></i>
                        </a>
                        <a href="https://t.me/ankety_bot" class="social-icon bot" target="_blank" rel="noopener noreferrer" title="Бот для анкет">
                            <i class="fas fa-robot"></i>
                        </a>
                    </div>
                </div>
                
                <!-- Быстрые ссылки -->
                <div class="footer-section">
                    <h3 class="footer-title">Быстрые ссылки</h3>
                    <ul class="footer-links">
                        <li><a href="https://ishupodrygyilidryga.fun">Главная</a></li>
                        <li><a href="https://t.me/ishupodrygyilidryga" target="_blank" rel="noopener noreferrer">Канал</a></li>
                        <li><a href="https://t.me/ankety_bot" target="_blank" rel="noopener noreferrer">Бот анкет</a></li>
                        <li><a href="https://t.me/lymikanon" target="_blank" rel="noopener noreferrer">Чат</a></li>
                        <li><a href="https://t.me/helpforsilencebot" target="_blank" rel="noopener noreferrer">Поддержка</a></li>
                    </ul>
                </div>
                
                <!-- Информация -->
                <div class="footer-section">
                    <h3 class="footer-title">Информация</h3>
                    <ul class="footer-links">
                        <li><a href="https://ishupodrygyilidryga.fun/status">Статус загруженности</a></li>
                        <li><a href="https://t.me/info_ishupodrygyilidryga/27" target="_blank" rel="noopener noreferrer">Стать админом по Анкетам</a></li>
                        <li><a href="https://t.me/info_ishupodrygyilidryga/897" target="_blank" rel="noopener noreferrer">Стать админом по Аватаркам</a></li>
                        <li><a href="https://uk.tgstat.com/channel/@ishupodrygyilidryga" target="_blank" rel="noopener noreferrer">Tgstat iipd</a></li>
                        <li><a href="https://uk.tgstat.com/channel/@surrealtouch" target="_blank" rel="noopener noreferrer">Tgstat surrealtouch</a></li>
                    </ul>
                </div>
            </div>
            
            <!-- Копирайт -->
            <div class="footer-bottom">
                <p class="copyright">
                    &copy; 2026 Ищу интернет подругу/друга (WEB). Все права защищены.
                </p>
                <p class="footer-note">
                    Создано с <i class="fas fa-heart"></i> для друзей
                </p>
            </div>
        </div>
    </footer>


    <script>
        // Оптимизированный JavaScript без анимаций
        (function() {
            'use strict';
            
            // DOM Elements
            const dom = {
                menuToggle: document.getElementById('menu-toggle'),
                navList: document.getElementById('nav-list'),
                menuOverlay: document.getElementById('menu-overlay'),
                body: document.body,
                dropdowns: document.querySelectorAll('.dropdown'),
                dropdownToggles: document.querySelectorAll('.dropdown-toggle'),
                themeToggle: document.getElementById('theme-toggle'),
                copyNotification: document.getElementById('copy-notification'),
                shareButton: document.getElementById('share-block')
            };
            
            // State
            let isMenuOpen = false;
            let isLightTheme = false;
            let copyNotificationTimeout = null;
            
            // Function to apply theme consistently
            function applyTheme(theme) {
                if (theme === 'light') {
                    dom.body.classList.add('light-theme');
                    isLightTheme = true;
                } else {
                    dom.body.classList.remove('light-theme');
                    isLightTheme = false;
                }
            }
            
            // Initialize theme from localStorage or default
            const savedTheme = localStorage.getItem('theme');
            if (savedTheme === 'light') {
                isLightTheme = true;
                dom.body.classList.add('light-theme');
            } else {
                isLightTheme = false;
                dom.body.classList.remove('light-theme');
                // Сохраняем темную тему как значение по умолчанию
                if (!savedTheme) {
                    localStorage.setItem('theme', 'dark');
                }
            }
            
            // Listen for theme changes from other tabs
            window.addEventListener('storage', function(e) {
                if (e.key === 'theme') {
                    applyTheme(e.newValue);
                }
            });
            
            // Function to prevent text selection
            const preventTextSelection = function(e) {
                e.preventDefault();
                return false;
            };
            
            // Menu Functions
            const menu = {
                open: function() {
                    dom.navList.classList.add('active');
                    dom.menuOverlay.classList.add('active');
                    dom.body.style.overflow = 'hidden';
                    dom.menuToggle.innerHTML = '<i class="fas fa-times"></i><span>Закрыть</span>';
                    isMenuOpen = true;
                    dom.menuToggle.setAttribute('aria-expanded', 'true');
                },
                
                close: function() {
                    dom.navList.classList.remove('active');
                    dom.menuOverlay.classList.remove('active');
                    dom.body.style.overflow = '';
                    dom.menuToggle.innerHTML = '<i class="fas fa-bars"></i><span>Меню</span>';
                    isMenuOpen = false;
                    this.closeAllDropdowns();
                    dom.menuToggle.setAttribute('aria-expanded', 'false');
                },
                
                toggle: function() {
                    if (isMenuOpen) {
                        this.close();
                    } else {
                        this.open();
                    }
                },
                
                closeAllDropdowns: function() {
                    dom.dropdowns.forEach(dropdown => {
                        dropdown.classList.remove('active');
                        const toggle = dropdown.querySelector('.dropdown-toggle');
                        if (toggle) {
                            toggle.setAttribute('aria-expanded', 'false');
                        }
                    });
                },
                
                toggleDropdown: function(dropdown) {
                    const isActive = dropdown.classList.contains('active');
                    
                    this.closeAllDropdowns();
                    
                    if (!isActive) {
                        dropdown.classList.add('active');
                        const toggle = dropdown.querySelector('.dropdown-toggle');
                        if (toggle) {
                            toggle.setAttribute('aria-expanded', 'true');
                        }
                    }
                }
            };
            
            // Функция для показа уведомления о копировании
            function showCopyNotification() {
                // Скрыть предыдущее уведомление
                hideCopyNotification();
                
                // Показать новое
                dom.copyNotification.classList.remove('hide');
                dom.copyNotification.classList.add('show');
                
                // Автоматически скрыть через 3 секунды
                copyNotificationTimeout = setTimeout(() => {
                    hideCopyNotification();
                }, 3000);
                
                // Отслеживание в Google Analytics
                if (typeof gtag !== 'undefined') {
                    gtag('event', 'link_copied', {
                        'event_category': 'engagement',
                        'event_label': 'Block Content Shared',
                        'page_path': window.location.pathname + window.location.search
                    });
                }
            }
            
            // Функция для скрытия уведомления
            function hideCopyNotification() {
                if (copyNotificationTimeout) {
                    clearTimeout(copyNotificationTimeout);
                    copyNotificationTimeout = null;
                }
                
                dom.copyNotification.classList.remove('show');
                dom.copyNotification.classList.add('hide');
                
                // Удалить класс hide после завершения анимации
                setTimeout(() => {
                    dom.copyNotification.classList.remove('hide');
                }, 400);
            }
            
            // Функция для копирования ссылки в буфер обмена
            function copyToClipboard(text) {
                // Пробуем использовать современный Clipboard API
                if (navigator.clipboard && window.isSecureContext) {
                    return navigator.clipboard.writeText(text);
                } else {
                    // Старый метод для старых браузеров
                    const textArea = document.createElement('textarea');
                    textArea.value = text;
                    textArea.style.position = 'fixed';
                    textArea.style.left = '-999999px';
                    textArea.style.top = '-999999px';
                    document.body.appendChild(textArea);
                    textArea.focus();
                    textArea.select();
                    
                    return new Promise((resolve, reject) => {
                        document.execCommand('copy') ? resolve() : reject();
                        textArea.remove();
                    });
                }
            }
            
            // Event Listeners
            const initEventListeners = function() {
                // Menu toggle
                dom.menuToggle.addEventListener('click', function(e) {
                    e.stopPropagation();
                    menu.toggle();
                });
                
                // Theme toggle
                dom.themeToggle.addEventListener('click', function(e) {
                    e.stopPropagation();
                    
                    const newTheme = isLightTheme ? 'dark' : 'light';
                    applyTheme(newTheme);
                    localStorage.setItem('theme', newTheme);
                    
                    // Отправка события в Google Analytics
                    if (typeof gtag !== 'undefined') {
                        gtag('event', 'theme_toggle', {
                            'event_category': 'ui_interaction',
                            'event_label': newTheme === 'light' ? 'Light Theme' : 'Dark Theme',
                            'page_path': window.location.pathname + window.location.search
                        });
                    }
                });
                
                // Dropdown toggles
                dom.dropdownToggles.forEach(toggle => {
                    toggle.addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        const dropdown = this.parentElement;
                        menu.toggleDropdown(dropdown);
                    });
                });
                
                // Close menu on overlay click
                dom.menuOverlay.addEventListener('click', function() {
                    menu.close();
                });
                
                // Close menu on regular link click
                document.querySelectorAll('.nav-link:not(.dropdown-toggle)').forEach(link => {
                    link.addEventListener('click', function() {
                        menu.close();
                    });
                });
                
                // Close menu when clicking outside
                document.addEventListener('click', function(e) {
                    if (isMenuOpen && 
                        !e.target.closest('.nav-list') && 
                        !e.target.closest('.menu-toggle')) {
                        menu.close();
                    }
                });
                
                // Close menu on escape key
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape' && isMenuOpen) {
                        menu.close();
                    }
                });
                
                // Кнопка "Поделиться"
                if (dom.shareButton) {
                    dom.shareButton.addEventListener('click', function() {
                        const currentUrl = window.location.href;
                        
                        copyToClipboard(currentUrl)
                            .then(() => {
                                showCopyNotification();
                            })
                            .catch(err => {
                                console.error('Ошибка копирования:', err);
                                // Все равно показываем уведомление
                                showCopyNotification();
                            });
                    });
                }
                
                // Закрыть уведомление при клике на него
                if (dom.copyNotification) {
                    dom.copyNotification.addEventListener('click', hideCopyNotification);
                }
                
                // Prevent text selection on interactive elements
                const interactiveElements = document.querySelectorAll(
                    'button, .menu-toggle, .dropdown-toggle, .button-link, .nav-link, .dropdown-item, .telegram-icon, .theme-toggle'
                );
                
                interactiveElements.forEach(el => {
                    el.addEventListener('contextmenu', preventTextSelection);
                    el.addEventListener('selectstart', preventTextSelection);
                    el.addEventListener('mousedown', preventTextSelection);
                    
                    el.addEventListener('touchstart', function(e) {
                        if (e.touches.length > 1) {
                            e.preventDefault();
                        }
                    }, { passive: false });
                });
            };
            
            // Initialize
            const init = function() {
                // Initialize event listeners
                initEventListeners();
                
                // Set accessibility attributes
                dom.menuToggle.setAttribute('aria-expanded', 'false');
                dom.menuToggle.setAttribute('aria-label', 'Открыть меню навигации');
                dom.themeToggle.setAttribute('aria-label', 'Переключить тему');
                
                // Add aria-labels to dropdown toggles
                dom.dropdownToggles.forEach(toggle => {
                    toggle.setAttribute('aria-expanded', 'false');
                    toggle.setAttribute('aria-haspopup', 'true');
                });
                
                // Отслеживание посещения страницы блока в Google Analytics
                if (typeof gtag !== 'undefined') {
                    gtag('event', 'block_view', {
                        'event_category': 'engagement',
                        'event_label': '<?php echo htmlspecialchars($displayTitle); ?>',
                        'block_slug': '<?php echo htmlspecialchars($slug); ?>'
                    });
                }
            }
            
            // Start when DOM is ready
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', init);
            } else {
                init();
            }
        })();
    </script>
</body>
</html>
