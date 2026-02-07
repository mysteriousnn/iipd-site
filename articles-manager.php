<?php
session_start();

// Функция для преобразования текста в HTML
function convertSimpleTextToHTML($text) {
    $text = nl2br(htmlspecialchars($text));
    
    // Преобразуем ссылки [текст](URL)
    $text = preg_replace_callback(
        '/\[([^\]]+)\]\(([^)]+)\)/',
        function($matches) {
            $linkText = htmlspecialchars($matches[1]);
            $linkUrl = htmlspecialchars($matches[2]);
            return '<a href="' . $linkUrl . '" class="content-link" target="_blank" rel="noopener noreferrer">' . $linkText . '</a>';
        },
        $text
    );
    
    // **жирный текст**
    $text = preg_replace('/\*\*([^*]+)\*\*/', '<strong>$1</strong>', $text);
    
    // *курсив*
    $text = preg_replace('/\*([^*]+)\*/', '<em>$1</em>', $text);
    
    // Списки
    $lines = explode("\n", $text);
    $result = [];
    $inList = false;
    
    foreach ($lines as $line) {
        $trimmedLine = trim($line);
        
        if (preg_match('/^[-•*]\s+(.+)$/', $trimmedLine, $matches)) {
            if (!$inList) {
                $result[] = '<ul class="article-content-list">';
                $inList = 'ul';
            }
            $result[] = '<li class="article-content-item">' . $matches[1] . '</li>';
        } elseif (preg_match('/^\d+\.\s+(.+)$/', $trimmedLine, $matches)) {
            if (!$inList) {
                $result[] = '<ol class="article-content-list">';
                $inList = 'ol';
            }
            $result[] = '<li class="article-content-item">' . $matches[1] . '</li>';
        } else {
            if ($inList) {
                $result[] = $inList === 'ul' ? '</ul>' : '</ol>';
                $inList = false;
            }
            if ($trimmedLine !== '') {
                $result[] = '<p class="article-content-text">' . $trimmedLine . '</p>';
            }
        }
    }
    
    if ($inList) {
        $result[] = $inList === 'ul' ? '</ul>' : '</ol>';
    }
    
    return implode("\n", $result);
}

$password = 'your_secret_password_here'; // ИЗМЕНИТЕ ЭТОТ ПАРОЛЬ!
$articlesFile = 'articles-data.json';

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
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Вход в редактор статей - iipd</title>
            <style>
                body {
                    background: linear-gradient(135deg, #1a1a1a, #2d2d2d);
                    color: #ffffff;
                    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                    min-height: 100vh;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    margin: 0;
                    padding: 20px;
                }
                
                .login-container {
                    background: rgba(38, 38, 38, 0.95);
                    padding: 40px;
                    border-radius: 15px;
                    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.5);
                    width: 100%;
                    max-width: 400px;
                    border: 1px solid rgba(255, 255, 255, 0.1);
                }
                
                .login-title {
                    text-align: center;
                    margin-bottom: 30px;
                    font-size: 1.8rem;
                    color: #ffffff;
                }
                
                .form-group {
                    margin-bottom: 20px;
                }
                
                .form-label {
                    display: block;
                    margin-bottom: 8px;
                    color: rgba(255, 255, 255, 0.8);
                    font-weight: 600;
                }
                
                .form-input {
                    width: 100%;
                    padding: 14px;
                    background: rgba(255, 255, 255, 0.05);
                    border: 1px solid rgba(255, 255, 255, 0.1);
                    border-radius: 10px;
                    color: #ffffff;
                    font-size: 1rem;
                    transition: all 0.3s ease;
                }
                
                .form-input:focus {
                    outline: none;
                    border-color: #888;
                    box-shadow: 0 0 0 2px rgba(136, 136, 136, 0.2);
                }
                
                .login-button {
                    width: 100%;
                    padding: 14px;
                    background: linear-gradient(135deg, #888, #aaa);
                    color: #ffffff;
                    border: none;
                    border-radius: 10px;
                    font-size: 1rem;
                    font-weight: 600;
                    cursor: pointer;
                    transition: all 0.3s ease;
                }
                
                .login-button:hover {
                    transform: translateY(-2px);
                    box-shadow: 0 5px 15px rgba(136, 136, 136, 0.3);
                }
                
                .error-message {
                    background: rgba(196, 92, 90, 0.1);
                    color: #c45c5a;
                    padding: 12px;
                    border-radius: 8px;
                    margin-top: 20px;
                    text-align: center;
                    border: 1px solid rgba(196, 92, 90, 0.3);
                }
                
                .back-link {
                    display: block;
                    text-align: center;
                    margin-top: 20px;
                    color: rgba(255, 255, 255, 0.6);
                    text-decoration: none;
                    transition: color 0.3s ease;
                }
                
                .back-link:hover {
                    color: #ffffff;
                }
            </style>
        </head>
        <body>
            <div class="login-container">
                <h1 class="login-title">Вход в редактор статей</h1>
                <form method="POST">
                    <div class="form-group">
                        <label class="form-label">Пароль</label>
                        <input type="password" name="password" class="form-input" required>
                    </div>
                    <button type="submit" class="login-button">Войти</button>
                    <?php if (isset($error)): ?>
                        <div class="error-message"><?php echo $error; ?></div>
                    <?php endif; ?>
                </form>
                <a href="/articles" class="back-link">← Вернуться к статьям</a>
            </div>
        </body>
        </html>
        <?php
        exit;
    }
}

// Чтение существующих статей
if (file_exists($articlesFile)) {
    $articles = json_decode(file_get_contents($articlesFile), true);
    if (!$articles) $articles = [];
} else {
    $articles = [];
}

// Удаление статьи
if (isset($_GET['delete'])) {
    $idToDelete = $_GET['delete'];
    $articles = array_filter($articles, function($article) use ($idToDelete) {
        return $article['id'] !== $idToDelete;
    });
    file_put_contents($articlesFile, json_encode(array_values($articles), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    header('Location: articles-manager.php');
    exit;
}

// Редактирование статьи
$editingArticle = null;
if (isset($_GET['edit'])) {
    $idToEdit = $_GET['edit'];
    foreach ($articles as $article) {
        if ($article['id'] === $idToEdit) {
            $editingArticle = $article;
            break;
        }
    }
}

// Добавление/редактирование статьи
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'update') {
        // Обновление существующей статьи
        $idToUpdate = $_POST['id'];
        foreach ($articles as &$article) {
            if ($article['id'] === $idToUpdate) {
                $article['icon'] = $_POST['icon'];
                $article['title'] = htmlspecialchars($_POST['title']);
                $article['description'] = htmlspecialchars($_POST['description']);
                $article['button_text'] = htmlspecialchars($_POST['button_text']);
                $article['order'] = intval($_POST['order']);
                
                // Обработка контента
                $contentTitle = htmlspecialchars($_POST['content_title']);
                $contentText = $_POST['content_text'];
                
                // Преобразуем простой текст в HTML
                $htmlContent = convertSimpleTextToHTML($contentText);
                
                // Формируем полный HTML контент
                $fullContent = '<div class="article-content-section">';
                if ($contentTitle) {
                    $fullContent .= '<h2 class="article-content-title">' . $contentTitle . '</h2>';
                }
                $fullContent .= $htmlContent . '</div>';
                
                $article['content'] = $fullContent;
                $article['url_slug'] = generateUrlSlug($_POST['title']);
                $article['updated_at'] = date('Y-m-d H:i:s');
                
                break;
            }
        }
        
        file_put_contents($articlesFile, json_encode($articles, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        header('Location: articles-manager.php');
        exit;
    } elseif (isset($_POST['title'])) {
        // Добавление новой статьи
        $newArticle = [
            'id' => uniqid(),
            'icon' => $_POST['icon'],
            'title' => htmlspecialchars($_POST['title']),
            'description' => htmlspecialchars($_POST['description']),
            'button_text' => htmlspecialchars($_POST['button_text']),
            'order' => intval($_POST['order']),
            'url_slug' => generateUrlSlug($_POST['title']),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        // Обработка контента
        $contentTitle = htmlspecialchars($_POST['content_title']);
        $contentText = $_POST['content_text'];
        
        // Преобразуем простой текст в HTML
        $htmlContent = convertSimpleTextToHTML($contentText);
        
        // Формируем полный HTML контент
        $fullContent = '<div class="article-content-section">';
        if ($contentTitle) {
            $fullContent .= '<h2 class="article-content-title">' . $contentTitle . '</h2>';
        }
        $fullContent .= $htmlContent . '</div>';
        
        $newArticle['content'] = $fullContent;
        
        $articles[] = $newArticle;
        file_put_contents($articlesFile, json_encode($articles, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        
        $success = 'Статья успешно добавлена!';
    }
}

// Функция для генерации URL slug
function generateUrlSlug($title) {
    $slug = mb_strtolower($title, 'UTF-8');
    $slug = preg_replace('/[^a-z0-9а-яё\s-]/u', '', $slug);
    $slug = preg_replace('/[\s-]+/', '-', $slug);
    $slug = trim($slug, '-');
    return $slug;
}

// Сортировка статей по порядку
usort($articles, function($a, $b) {
    return $a['order'] - $b['order'];
});

// Парсим контент для редактирования
$editingContentTitle = '';
$editingContentText = '';
if ($editingArticle && !empty($editingArticle['content'])) {
    $content = $editingArticle['content'];
    
    // Извлекаем заголовок
    if (preg_match('/<h2 class="article-content-title">([^<]+)<\/h2>/', $content, $matches)) {
        $editingContentTitle = htmlspecialchars_decode($matches[1]);
    }
    
    // Извлекаем основной контент
    $content = preg_replace('/<h2 class="article-content-title">[^<]+<\/h2>/', '', $content);
    $content = preg_replace('/<div class="article-content-section">/', '', $content);
    $content = preg_replace('/<\/div>$/', '', $content);
    
    // Преобразуем HTML обратно в простой текст
    $content = htmlspecialchars_decode($content);
    $content = strip_tags($content, '<a><strong><em><ul><ol><li><p><br>');
    
    // Восстанавливаем простой формат
    $content = preg_replace('/<strong>([^<]+)<\/strong>/', '**$1**', $content);
    $content = preg_replace('/<em>([^<]+)<\/em>/', '*$1*', $content);
    $content = preg_replace('/<a href="([^"]+)"[^>]*>([^<]+)<\/a>/', '[$2]($1)', $content);
    $content = preg_replace('/<p class="article-content-text">([^<]+)<\/p>/', "$1\n", $content);
    $content = preg_replace('/<li class="article-content-item">([^<]+)<\/li>/', "- $1\n", $content);
    $content = preg_replace('/<br\s*\/?>/', "\n", $content);
    $content = preg_replace('/<\/?ul[^>]*>/', '', $content);
    $content = preg_replace('/<\/?ol[^>]*>/', '', $content);
    
    $editingContentText = trim($content);
}

// Список иконок для статей
$articleIcons = [
    'fas fa-language', 'fas fa-hands-helping', 'fas fa-comments', 'fas fa-user-friends',
    'fas fa-book', 'fas fa-graduation-cap', 'fas fa-lightbulb', 'fas fa-brain',
    'fas fa-heart', 'fas fa-comment-dots', 'fas fa-users', 'fas fa-globe',
    'fas fa-network-wired', 'fas fa-handshake', 'fas fa-smile', 'fas fa-laptop-code',
    'fas fa-mobile-alt', 'fas fa-wifi', 'fas fa-cloud', 'fas fa-share-alt',
    'fas fa-envelope', 'fas fa-paper-plane', 'fas fa-comment', 'fas fa-quote-right',
    'fas fa-star', 'fas fa-flag', 'fas fa-trophy', 'fas fa-award',
    'fas fa-crown', 'fas fa-gem', 'fas fa-key', 'fas fa-lock',
    'fas fa-unlock', 'fas fa-shield-alt', 'fas fa-user-check', 'fas fa-user-plus',
    'fas fa-search', 'fas fa-filter', 'fas fa-sort', 'fas fa-chart-line',
    'fas fa-chart-bar', 'fas fa-chart-pie', 'fas fa-calendar', 'fas fa-clock',
    'fas fa-history', 'fas fa-futbol', 'fas fa-gamepad', 'fas fa-music',
    'fas fa-film', 'fas fa-palette', 'fas fa-camera', 'fas fa-microphone',
    'fas fa-headphones', 'fas fa-tv', 'fas fa-newspaper', 'fas fa-blog',
    'fas fa-rss', 'fas fa-atom', 'fas fa-flask', 'fas fa-microscope',
    'fas fa-dna', 'fas fa-stethoscope', 'fas fa-heartbeat', 'fas fa-leaf',
    'fas fa-seedling', 'fas fa-tree', 'fas fa-mountain', 'fas fa-umbrella',
    'fas fa-sun', 'fas fa-moon', 'fas fa-cloud-sun', 'fas fa-cloud-rain',
    'fas fa-snowflake', 'fas fa-wind', 'fas fa-fire', 'fas fa-water'
];
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Редактор статей - iipd</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --bg-primary: #1a1a1a;
            --bg-secondary: #262626;
            --text-primary: #ffffff;
            --text-secondary: rgba(255, 255, 255, 0.85);
            --border-color: rgba(255, 255, 255, 0.08);
            --accent-gradient: linear-gradient(135deg, #888, #aaa);
            --yellow-color: #ecc33c;
            --green-color: #a6bb7c;
            --red-color: #c45c5a;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            background-color: var(--bg-primary);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: var(--text-primary);
            line-height: 1.6;
            padding: 20px;
            min-height: 100vh;
        }
        
        .admin-container {
            max-width: 1400px;
            margin: 0 auto;
        }
        
        .admin-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid var(--border-color);
        }
        
        .admin-title {
            font-size: 2rem;
            font-weight: 700;
            background: var(--accent-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .logout-button {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            background: rgba(196, 92, 90, 0.1);
            color: #c45c5a;
            border: 1px solid rgba(196, 92, 90, 0.3);
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .logout-button:hover {
            background: rgba(196, 92, 90, 0.2);
        }
        
        .content-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 40px;
        }
        
        @media (max-width: 1024px) {
            .content-grid {
                grid-template-columns: 1fr;
            }
        }
        
        .add-article-form, .articles-list {
            background: var(--bg-secondary);
            border-radius: 15px;
            padding: 30px;
            border: 1px solid var(--border-color);
        }
        
        .form-title, .list-title {
            font-size: 1.5rem;
            margin-bottom: 25px;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .form-title i, .list-title i {
            color: var(--yellow-color);
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-label {
            display: block;
            margin-bottom: 8px;
            color: var(--text-secondary);
            font-weight: 600;
        }
        
        .form-input, .form-select, .form-textarea {
            width: 100%;
            padding: 12px 15px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            color: var(--text-primary);
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        
        .form-textarea {
            min-height: 100px;
            resize: vertical;
        }
        
        .form-textarea.large {
            min-height: 300px;
            font-size: 14px;
            line-height: 1.6;
        }
        
        .form-input:focus, .form-select:focus, .form-textarea:focus {
            outline: none;
            border-color: var(--yellow-color);
            box-shadow: 0 0 0 2px rgba(236, 195, 60, 0.2);
        }
        
        .form-select option {
            background: var(--bg-secondary);
            color: var(--text-primary);
        }
        
        .icon-preview {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-top: 10px;
            padding: 15px;
            background: rgba(255, 255, 255, 0.03);
            border-radius: 8px;
            border: 1px solid var(--border-color);
        }
        
        .icon-preview i {
            font-size: 2rem;
            color: var(--yellow-color);
        }
        
        .icon-preview span {
            color: var(--text-secondary);
            font-size: 0.9rem;
        }
        
        .submit-button {
            width: 100%;
            padding: 15px;
            background: var(--accent-gradient);
            color: #ffffff;
            border: none;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        
        .submit-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(136, 136, 136, 0.3);
        }
        
        .success-message {
            background: rgba(166, 187, 124, 0.1);
            color: #a6bb7c;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border: 1px solid rgba(166, 187, 124, 0.3);
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .articles-container {
            margin-top: 20px;
        }
        
        .article-item {
            background: rgba(255, 255, 255, 0.03);
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 15px;
            border: 1px solid var(--border-color);
            transition: all 0.3s ease;
        }
        
        .article-item:hover {
            background: rgba(255, 255, 255, 0.05);
            border-color: rgba(255, 255, 255, 0.15);
        }
        
        .article-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .article-order {
            padding: 4px 12px;
            background: rgba(136, 136, 136, 0.1);
            color: #888;
            border: 1px solid rgba(136, 136, 136, 0.3);
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        
        .article-title {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .article-title i {
            color: var(--yellow-color);
        }
        
        .article-description {
            color: var(--text-secondary);
            margin-bottom: 15px;
            font-size: 0.95rem;
        }
        
        .article-details {
            display: flex;
            gap: 20px;
            margin-bottom: 15px;
            flex-wrap: wrap;
        }
        
        .article-detail {
            display: flex;
            align-items: center;
            gap: 8px;
            color: var(--text-secondary);
            font-size: 0.9rem;
        }
        
        .article-actions {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }
        
        .edit-button {
            background: rgba(236, 195, 60, 0.1);
            color: #ecc33c;
            border: 1px solid #ecc33c;
            padding: 8px 16px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 0.85rem;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        
        .edit-button:hover {
            background: rgba(236, 195, 60, 0.2);
        }
        
        .delete-button {
            background: rgba(196, 92, 90, 0.1);
            color: #c45c5a;
            border: 1px solid #c45c5a;
            padding: 8px 16px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 0.85rem;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        
        .delete-button:hover {
            background: rgba(196, 92, 90, 0.2);
        }
        
        .empty-message {
            text-align: center;
            padding: 40px;
            color: rgba(255, 255, 255, 0.5);
            background: rgba(255, 255, 255, 0.03);
            border-radius: 10px;
            border: 1px dashed rgba(255, 255, 255, 0.1);
        }
        
        .navigation-links {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid var(--border-color);
        }
        
        .nav-link {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 12px 24px;
            background: rgba(255, 255, 255, 0.05);
            color: var(--text-secondary);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .nav-link:hover {
            background: rgba(255, 255, 255, 0.1);
            color: var(--text-primary);
        }
        
        .edit-form-container {
            background: rgba(255, 255, 255, 0.03);
            border-radius: 10px;
            padding: 25px;
            margin-bottom: 20px;
            border: 2px solid #ecc33c;
        }
        
        .edit-form-title {
            font-size: 1.3rem;
            color: #ecc33c;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .edit-form-title i {
            font-size: 1.2rem;
        }
        
        .form-buttons {
            display: flex;
            gap: 15px;
            margin-top: 20px;
        }
        
        .cancel-button {
            background: rgba(255, 255, 255, 0.05);
            color: rgba(255, 255, 255, 0.85);
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 14px 24px;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            flex: 1;
            text-decoration: none;
            text-align: center;
        }
        
        .cancel-button:hover {
            background: rgba(255, 255, 255, 0.1);
        }
        
        .icon-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(50px, 1fr));
            gap: 10px;
            margin-top: 10px;
            max-height: 200px;
            overflow-y: auto;
            padding: 10px;
            background: rgba(255, 255, 255, 0.03);
            border-radius: 8px;
            border: 1px solid var(--border-color);
        }
        
        .icon-option {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 40px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--border-color);
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        
        .icon-option:hover {
            background: rgba(236, 195, 60, 0.1);
            border-color: #ecc33c;
        }
        
        .icon-option.selected {
            background: rgba(236, 195, 60, 0.2);
            border-color: #ecc33c;
            color: #ecc33c;
        }
        
        .icon-option i {
            font-size: 1.2rem;
        }
        
        .icon-search {
            margin-bottom: 10px;
        }
        
        .formatting-help {
            margin-top: 10px;
            padding: 15px;
            background: rgba(166, 187, 124, 0.1);
            border-radius: 8px;
            border: 1px solid rgba(166, 187, 124, 0.3);
        }
        
        .formatting-help h4 {
            color: #a6bb7c;
            margin-bottom: 10px;
            font-size: 0.9rem;
        }
        
        .formatting-help code {
            background: rgba(255, 255, 255, 0.1);
            padding: 2px 6px;
            border-radius: 4px;
            font-family: monospace;
            font-size: 0.85rem;
        }
        
        .insert-link-btn {
            background: rgba(166, 187, 124, 0.1);
            color: #a6bb7c;
            border: 1px solid #a6bb7c;
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.9rem;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-top: 10px;
            transition: all 0.3s ease;
        }
        
        .insert-link-btn:hover {
            background: rgba(166, 187, 124, 0.2);
        }
        
        .url-preview {
            margin-top: 10px;
            padding: 10px;
            background: rgba(136, 136, 136, 0.1);
            border-radius: 6px;
            font-size: 0.85rem;
            color: var(--text-secondary);
            word-break: break-all;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <div class="admin-header">
            <h1 class="admin-title">Редактор статей</h1>
            <a href="?logout" class="logout-button">
                <i class="fas fa-sign-out-alt"></i> Выйти
            </a>
        </div>
        
        <?php if (isset($success)): ?>
            <div class="success-message">
                <i class="fas fa-check-circle"></i> <?php echo $success; ?>
            </div>
        <?php endif; ?>
        
        <div class="content-grid">
            <!-- Форма добавления/редактирования -->
            <div class="add-article-form">
                <h2 class="form-title">
                    <i class="fas <?php echo isset($editingArticle) ? 'fa-edit' : 'fa-plus-circle'; ?>"></i>
                    <?php echo isset($editingArticle) ? 'Редактировать статью' : 'Добавить новую статью'; ?>
                </h2>
                
                <?php if (isset($editingArticle)): ?>
                    <!-- Форма редактирования -->
                    <div class="edit-form-container">
                        <h3 class="edit-form-title">
                            <i class="fas fa-pencil-alt"></i>
                            Редактирование: <?php echo htmlspecialchars($editingArticle['title']); ?>
                        </h3>
                        <form method="POST" id="article-form">
                            <input type="hidden" name="action" value="update">
                            <input type="hidden" name="id" value="<?php echo $editingArticle['id']; ?>">
                            
                            <div class="form-group">
                                <label class="form-label">Иконка</label>
                                <input type="text" name="icon" id="icon-input" class="form-input" 
                                       value="<?php echo $editingArticle['icon']; ?>" 
                                       required placeholder="fas fa-language" list="icon-list">
                                <div class="icon-preview">
                                    <i id="icon-preview" class="<?php echo $editingArticle['icon']; ?>"></i>
                                    <span id="icon-preview-text">Предпросмотр иконки</span>
                                </div>
                                <input type="text" class="form-input icon-search" placeholder="Поиск иконки..." id="icon-search">
                                <div class="icon-grid" id="icon-grid">
                                    <?php foreach ($articleIcons as $icon): ?>
                                        <div class="icon-option <?php echo $icon === $editingArticle['icon'] ? 'selected' : ''; ?>" 
                                             data-icon="<?php echo $icon; ?>">
                                            <i class="<?php echo $icon; ?>"></i>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Заголовок статьи</label>
                                <input type="text" name="title" id="title-input" class="form-input" 
                                       value="<?php echo htmlspecialchars($editingArticle['title']); ?>" 
                                       required placeholder="Например: Язык современности">
                                <div class="url-preview" id="url-preview">
                                    URL: /articles/<?php echo $editingArticle['url_slug']; ?>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Описание статьи (карточка)</label>
                                <textarea name="description" class="form-textarea" required 
                                          placeholder="Краткое описание, которое будет отображаться в карточке..."><?php echo htmlspecialchars($editingArticle['description']); ?></textarea>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Текст кнопки</label>
                                <input type="text" name="button_text" class="form-input" 
                                       value="<?php echo htmlspecialchars($editingArticle['button_text']); ?>" 
                                       required placeholder="Например: Читать статью">
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Заголовок страницы статьи</label>
                                <input type="text" name="content_title" class="form-input" 
                                       value="<?php echo htmlspecialchars($editingContentTitle); ?>" 
                                       placeholder="Заголовок, который будет отображаться на странице статьи">
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Текст статьи</label>
                                <textarea name="content_text" class="form-textarea large" rows="10"
                                          placeholder="Введите текст статьи здесь. Просто пишите текст, добавляйте ссылки в формате [текст ссылки](URL)."><?php echo htmlspecialchars($editingContentText); ?></textarea>
                                
                                <div class="formatting-help">
                                    <h4>Форматирование текста:</h4>
                                    <p><strong>Ссылки:</strong> [текст ссылки](https://example.com)</p>
                                    <p><strong>Жирный текст:</strong> **жирный текст**</p>
                                    <p><strong>Курсив:</strong> *курсивный текст*</p>
                                    <p><strong>Списки:</strong> - пункт списка или 1. пункт нумерованного списка</p>
                                    <p><strong>Перенос строки:</strong> просто нажмите Enter</p>
                                </div>
                                
                                <button type="button" class="insert-link-btn" onclick="insertLink()">
                                    <i class="fas fa-link"></i> Вставить ссылку
                                </button>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Порядок отображения</label>
                                <input type="number" name="order" class="form-input" 
                                       value="<?php echo $editingArticle['order']; ?>" 
                                       required min="0" placeholder="0">
                                <small style="color: var(--text-secondary); font-size: 0.85rem; display: block; margin-top: 5px;">
                                    Меньше число = выше в списке. Статьи сортируются по этому полю.
                                </small>
                            </div>
                            
                            <div class="form-buttons">
                                <button type="submit" class="submit-button">
                                    <i class="fas fa-save"></i> Сохранить изменения
                                </button>
                                <a href="articles-manager.php" class="cancel-button">
                                    <i class="fas fa-times"></i> Отмена
                                </a>
                            </div>
                        </form>
                    </div>
                <?php else: ?>
                    <!-- Форма добавления новой статьи -->
                    <form method="POST" id="article-form">
                        <div class="form-group">
                            <label class="form-label">Иконка</label>
                            <input type="text" name="icon" id="icon-input" class="form-input" 
                                   required placeholder="fas fa-language" list="icon-list">
                            <div class="icon-preview">
                                <i id="icon-preview" class="fas fa-question-circle"></i>
                                <span id="icon-preview-text">Выберите иконку</span>
                            </div>
                            <input type="text" class="form-input icon-search" placeholder="Поиск иконки..." id="icon-search">
                            <div class="icon-grid" id="icon-grid">
                                <?php foreach ($articleIcons as $icon): ?>
                                    <div class="icon-option" data-icon="<?php echo $icon; ?>">
                                        <i class="<?php echo $icon; ?>"></i>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Заголовок статьи</label>
                            <input type="text" name="title" id="title-input" class="form-input" required placeholder="Например: Язык современности">
                            <div class="url-preview" id="url-preview" style="display: none;">
                                URL: /articles/<span id="url-slug"></span>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Описание статьи (карточка)</label>
                            <textarea name="description" class="form-textarea" required placeholder="Краткое описание, которое будет отображаться в карточке..."></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Текст кнопки</label>
                            <input type="text" name="button_text" class="form-input" required placeholder="Например: Читать статью">
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Заголовок страницы статьи</label>
                            <input type="text" name="content_title" class="form-input" 
                                   placeholder="Заголовок, который будет отображаться на странице статьи">
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Текст статьи</label>
                            <textarea name="content_text" class="form-textarea large" rows="10"
                                      placeholder="Введите текст статьи здесь. Просто пишите текст, добавляйте ссылки в формате [текст ссылки](URL)."></textarea>
                            
                            <div class="formatting-help">
                                <h4>Форматирование текста:</h4>
                                <p><strong>Ссылки:</strong> [текст ссылки](https://example.com)</p>
                                <p><strong>Жирный текст:</strong> **жирный текст**</p>
                                <p><strong>Курсив:</strong> *курсивный текст*</p>
                                <p><strong>Списки:</strong> - пункт списка или 1. пункт нумерованного списка</p>
                                <p><strong>Перенос строки:</strong> просто нажмите Enter</p>
                            </div>
                            
                            <button type="button" class="insert-link-btn" onclick="insertLink()">
                                <i class="fas fa-link"></i> Вставить ссылку
                            </button>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Порядок отображения</label>
                            <input type="number" name="order" class="form-input" value="0" required min="0" placeholder="0">
                            <small style="color: var(--text-secondary); font-size: 0.85rem; display: block; margin-top: 5px;">
                                Меньше число = выше в списке. Статьи сортируются по этому полю.
                            </small>
                        </div>
                        
                        <button type="submit" class="submit-button">
                            <i class="fas fa-paper-plane"></i> Добавить статью
                        </button>
                    </form>
                <?php endif; ?>
            </div>
            
            <!-- Список статей -->
            <div class="articles-list">
                <h2 class="list-title">
                    <i class="fas fa-list"></i>
                    Существующие статьи (<?php echo count($articles); ?>)
                </h2>
                
                <?php if (empty($articles)): ?>
                    <div class="empty-message">
                        <i class="fas fa-inbox"></i><br>
                        Пока нет статей
                    </div>
                <?php else: ?>
                    <div class="articles-container">
                        <?php foreach ($articles as $article): ?>
                            <div class="article-item">
                                <div class="article-header">
                                    <span class="article-order">
                                        Порядок: <?php echo $article['order']; ?>
                                    </span>
                                    <span style="color: rgba(255, 255, 255, 0.6); font-size: 0.9rem;">
                                        <?php echo date('d.m.Y', strtotime($article['created_at'])); ?>
                                    </span>
                                </div>
                                
                                <h3 class="article-title">
                                    <i class="<?php echo $article['icon']; ?>"></i>
                                    <?php echo $article['title']; ?>
                                </h3>
                                
                                <p class="article-description"><?php echo $article['description']; ?></p>
                                
                                <div class="article-details">
                                    <div class="article-detail">
                                        <i class="fas fa-mouse-pointer"></i>
                                        <span>Кнопка: "<?php echo $article['button_text']; ?>"</span>
                                    </div>
                                    <div class="article-detail">
                                        <i class="fas fa-link"></i>
                                        <span>URL: /articles/<?php echo $article['url_slug']; ?></span>
                                    </div>
                                    <?php if (!empty($article['content'])): ?>
                                    <div class="article-detail">
                                        <i class="fas fa-code"></i>
                                        <span style="color: var(--green-color);">Есть контент</span>
                                    </div>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="article-actions">
                                    <a href="?edit=<?php echo $article['id']; ?>" class="edit-button">
                                        <i class="fas fa-edit"></i> Редактировать
                                    </a>
                                    <a href="?delete=<?php echo $article['id']; ?>" class="delete-button" onclick="return confirm('Удалить эту статью?')">
                                        <i class="fas fa-trash"></i> Удалить
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="navigation-links">
            <a href="/articles" class="nav-link">
                <i class="fas fa-newspaper"></i> К статьям
            </a>
            <a href="articles-manager.php" class="nav-link">
                <i class="fas fa-sync"></i> Обновить список
            </a>
        </div>
    </div>
    
    <script>
        // Обновление предпросмотра иконки
        const iconInput = document.getElementById('icon-input');
        const iconPreview = document.getElementById('icon-preview');
        const iconPreviewText = document.getElementById('icon-preview-text');
        const iconGrid = document.getElementById('icon-grid');
        const iconSearch = document.getElementById('icon-search');
        const titleInput = document.getElementById('title-input');
        const urlPreview = document.getElementById('url-preview');
        const urlSlug = document.getElementById('url-slug');
        
        // Иконки для поиска
        const allIcons = <?php echo json_encode($articleIcons); ?>;
        
        // Обновление предпросмотра иконки при вводе
        if (iconInput) {
            iconInput.addEventListener('input', function() {
                updateIconPreview(this.value);
            });
        }
        
        // Генерация URL slug при изменении заголовка
        if (titleInput) {
            titleInput.addEventListener('input', function() {
                generateUrlSlug(this.value);
            });
        }
        
        // Обработка выбора иконки из сетки
        document.querySelectorAll('.icon-option').forEach(option => {
            option.addEventListener('click', function() {
                const icon = this.getAttribute('data-icon');
                if (iconInput) {
                    iconInput.value = icon;
                    updateIconPreview(icon);
                    
                    // Снимаем выделение со всех иконок
                    document.querySelectorAll('.icon-option').forEach(opt => {
                        opt.classList.remove('selected');
                    });
                    // Выделяем выбранную
                    this.classList.add('selected');
                }
            });
        });
        
        // Поиск иконок
        if (iconSearch) {
            iconSearch.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                const filteredIcons = allIcons.filter(icon => 
                    icon.toLowerCase().includes(searchTerm)
                );
                
                updateIconGrid(filteredIcons);
            });
        }
        
        function updateIconPreview(iconClass) {
            if (iconPreview && iconClass && iconClass.trim() !== '') {
                iconPreview.className = iconClass;
                iconPreviewText.textContent = iconClass;
            } else if (iconPreview) {
                iconPreview.className = 'fas fa-question-circle';
                iconPreviewText.textContent = 'Выберите иконку';
            }
        }
        
        function updateIconGrid(icons) {
            if (!iconGrid) return;
            
            iconGrid.innerHTML = '';
            icons.forEach(icon => {
                const option = document.createElement('div');
                option.className = 'icon-option';
                option.setAttribute('data-icon', icon);
                option.innerHTML = `<i class="${icon}"></i>`;
                
                option.addEventListener('click', function() {
                    const icon = this.getAttribute('data-icon');
                    if (iconInput) {
                        iconInput.value = icon;
                        updateIconPreview(icon);
                        
                        // Снимаем выделение со всех иконок
                        document.querySelectorAll('.icon-option').forEach(opt => {
                            opt.classList.remove('selected');
                        });
                        // Выделяем выбранную
                        this.classList.add('selected');
                    }
                });
                
                iconGrid.appendChild(option);
            });
        }
        
        function generateUrlSlug(title) {
            if (!title || !urlPreview || !urlSlug) return;
            
            let slug = title.toLowerCase();
            slug = slug.replace(/[^a-z0-9а-яё\s-]/gu, '');
            slug = slug.replace(/[\s-]+/g, '-');
            slug = slug.trim('-');
            
            urlSlug.textContent = slug;
            urlPreview.style.display = 'block';
        }
        
        // Функция для вставки ссылки
        function insertLink() {
            const textarea = document.querySelector('textarea[name="content_text"]');
            if (!textarea) return;
            
            const url = prompt('Введите URL ссылки (например: https://t.me/ishupodrygyilidryga):');
            if (!url) return;
            
            const text = prompt('Введите текст ссылки:', 'наш Telegram');
            if (text === null) return;
            
            const linkText = `[${text}](${url})`;
            
            // Вставляем в текущую позицию курсора
            const start = textarea.selectionStart;
            const end = textarea.selectionEnd;
            const currentText = textarea.value;
            
            textarea.value = currentText.substring(0, start) + linkText + currentText.substring(end);
            textarea.focus();
            textarea.setSelectionRange(start + linkText.length, start + linkText.length);
        }
        
        // Обработка выхода
        if (window.location.search.includes('logout')) {
            window.location.href = 'articles-manager.php';
        }
        
        // Инициализация при загрузке
        <?php if (isset($editingArticle)): ?>
            updateIconPreview('<?php echo $editingArticle['icon']; ?>');
        <?php endif; ?>
    </script>
</body>
</html>
