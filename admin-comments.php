<?php
session_start();

header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

// Функция для преобразования текста в HTML
function convertSimpleTextToHTML($text) {
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
        $text = 'comment-' . uniqid();
    }
    
    return $text;
}

// Функция для создания уникального slug
function makeUniqueSlug($slug, $comments, $excludeId = null) {
    $originalSlug = $slug;
    $counter = 1;
    
    while (true) {
        $exists = false;
        foreach ($comments as $comment) {
            if ($excludeId && $comment['id'] === $excludeId) {
                continue;
            }
            if (isset($comment['slug']) && $comment['slug'] === $slug) {
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

$password = 'ваш_секретный_пароль';
$dataFile = 'comments-data.json';

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
                .form-group { margin-bottom: 15px; }
                input[type="password"] { width: 100%; padding: 10px; }
                button { padding: 10px 20px; background: #4CAF50; color: white; border: none; cursor: pointer; }
                .error { color: red; margin-top: 10px; }
            </style>
        </head>
        <body>
            <h2>Вход в админ-панель</h2>
            <form method="POST">
                <div class="form-group">
                    <label>Пароль:</label>
                    <input type="password" name="password" required>
                </div>
                <button type="submit">Войти</button>
                <?php if (isset($error)): ?>
                    <div class="error"><?php echo $error; ?></div>
                <?php endif; ?>
            </form>
        </body>
        </html>
        <?php
        exit;
    }
}

// Чтение существующих комментариев
if (file_exists($dataFile)) {
    $comments = json_decode(file_get_contents($dataFile), true) ?: [];
} else {
    $comments = [];
}

// Конвертация старых комментариев
foreach ($comments as &$comment) {
    if (!isset($comment['text_simple']) && isset($comment['text'])) {
        $isFormatted = (strpos($comment['text'], '<div class="content-section">') !== false);
        
        if ($isFormatted) {
            $comment['text_formatted'] = $comment['text'];
            $comment['text_simple'] = strip_tags(htmlspecialchars_decode($comment['text']));
        } else {
            $comment['text_simple'] = htmlspecialchars_decode($comment['text']);
            $comment['text_formatted'] = '';
        }
        unset($comment['text']);
    }
    
    if (!isset($comment['short_text']) || empty($comment['short_text'])) {
        $plainText = $comment['text_simple'] ?? '';
        $comment['short_text'] = $plainText . ' читать далее...';
    }
    
    if (!isset($comment['pinned'])) {
        $comment['pinned'] = false;
    }
    
    if (!isset($comment['slug']) || empty($comment['slug'])) {
        $comment['slug'] = generateSlug($comment['title']);
        $comment['slug'] = makeUniqueSlug($comment['slug'], $comments, $comment['id']);
    }
    
    // Добавляем поле для хранения оригинального форматированного текста
    if (!isset($comment['text_markdown'])) {
        if (isset($comment['text_formatted']) && !empty($comment['text_formatted'])) {
            $content = $comment['text_formatted'];
            $content = str_replace('<div class="content-section">', '', $content);
            $content = str_replace('</div>', '', $content);
            
            // Используем более точное преобразование тегов
            $content = preg_replace_callback('/<a href="([^"]+)"[^>]*>([^<]+)<\/a>/', function($matches) {
                $url = htmlspecialchars_decode($matches[1]);
                $text = htmlspecialchars_decode($matches[2]);
                return '[' . $text . '](' . $url . ')';
            }, $content);
            
            $content = preg_replace('/<strong>([^<]+)<\/strong>/', '**$1**', $content);
            $content = preg_replace('/<em>([^<]+)<\/em>/', '*$1*', $content);
            
            // Убираем <br> без добавления лишних пробелов
            $content = preg_replace('/<br\s*\/?>/', "\n", $content);
            
            $content = htmlspecialchars_decode($content);
            
            // Убираем лишние пустые строки
            $content = preg_replace('/\n\s*\n\s*\n/', "\n\n", $content);
            $comment['text_markdown'] = trim($content);
        } else {
            $comment['text_markdown'] = '';
        }
    }
}

file_put_contents($dataFile, json_encode($comments, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

// Удаление комментария
if (isset($_GET['delete'])) {
    $idToDelete = $_GET['delete'];
    $comments = array_filter($comments, function($comment) use ($idToDelete) {
        return $comment['id'] !== $idToDelete;
    });
    file_put_contents($dataFile, json_encode(array_values($comments), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    header('Location: admin-comments.php');
    exit;
}

// Редактирование комментария
$editingComment = null;
if (isset($_GET['edit'])) {
    $idToEdit = $_GET['edit'];
    foreach ($comments as $comment) {
        if ($comment['id'] === $idToEdit) {
            $editingComment = $comment;
            break;
        }
    }
}

// Подготовка данных для редактирования
$simpleTextContent = '';
$formattedTextContent = '';
$pinnedStatus = false;
$slugContent = '';
if ($editingComment) {
    $simpleTextContent = htmlspecialchars_decode($editingComment['text_simple'] ?? '');
    $pinnedStatus = $editingComment['pinned'] ?? false;
    $slugContent = $editingComment['slug'] ?? '';
    
    // Берем текст из markdown поля, если есть
    if (isset($editingComment['text_markdown']) && !empty($editingComment['text_markdown'])) {
        $formattedTextContent = $editingComment['text_markdown'];
    }
}

// Добавление/редактирование комментария
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'update') {
        $idToUpdate = $_POST['id'];
        foreach ($comments as &$comment) {
            if ($comment['id'] === $idToUpdate) {
                $comment['date'] = $_POST['date'];
                $comment['title'] = htmlspecialchars($_POST['title']);
                $comment['pinned'] = isset($_POST['pinned']) ? (bool)$_POST['pinned'] : false;
                
                $slug = $_POST['slug'] ?? '';
                if (empty($slug)) {
                    $slug = generateSlug($_POST['title']);
                }
                $comment['slug'] = makeUniqueSlug($slug, $comments, $idToUpdate);
                
                $simpleText = trim($_POST['simple_content_text'] ?? '');
                $comment['text_simple'] = htmlspecialchars($simpleText);
                
                $formattedText = trim($_POST['formatted_content_text'] ?? '');
                $comment['text_markdown'] = $formattedText;
                
                if (!empty($formattedText)) {
                    $htmlContent = convertSimpleTextToHTML($formattedText);
                    $comment['text_formatted'] = '<div class="content-section">' . $htmlContent . '</div>';
                } else {
                    $comment['text_formatted'] = '';
                }
                
                $plainText = htmlspecialchars_decode($comment['text_simple']);
                $comment['short_text'] = $plainText . ' читать далее...';
                
                break;
            }
        }
        
        file_put_contents($dataFile, json_encode($comments, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        header('Location: admin-comments.php');
        exit;
    } elseif (isset($_POST['title'])) {
        $date = $_POST['date'] ?? date('d.m.Y');
        $pinned = isset($_POST['pinned']) ? (bool)$_POST['pinned'] : false;
        
        $slug = $_POST['slug'] ?? '';
        if (empty($slug)) {
            $slug = generateSlug($_POST['title']);
        }
        $slug = makeUniqueSlug($slug, $comments);
        
        $newComment = [
            'id' => uniqid(),
            'date' => $date,
            'title' => htmlspecialchars($_POST['title']),
            'text_simple' => '',
            'text_formatted' => '',
            'text_markdown' => '',
            'short_text' => '',
            'pinned' => $pinned,
            'slug' => $slug,
            'timestamp' => time()
        ];
        
        $simpleText = trim($_POST['simple_content_text'] ?? '');
        $newComment['text_simple'] = htmlspecialchars($simpleText);
        
        $formattedText = trim($_POST['formatted_content_text'] ?? '');
        $newComment['text_markdown'] = $formattedText;
        
        if (!empty($formattedText)) {
            $htmlContent = convertSimpleTextToHTML($formattedText);
            $newComment['text_formatted'] = '<div class="content-section">' . $htmlContent . '</div>';
        }
        
        $plainText = htmlspecialchars_decode($newComment['text_simple']);
        $newComment['short_text'] = $plainText . ' читать далее...';
        
        array_unshift($comments, $newComment);
        file_put_contents($dataFile, json_encode($comments, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        
        $success = 'Комментарий успешно добавлен!';
    }
}

$isEditing = isset($_GET['edit']);

// Сортируем комментарии
usort($comments, function($a, $b) {
    if ($a['pinned'] && !$b['pinned']) return -1;
    if (!$a['pinned'] && $b['pinned']) return 1;
    return strtotime($b['date']) - strtotime($a['date']);
});
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Управление комментариями</title>
    <style>
        body { font-family: sans-serif; margin: 20px; background: #f5f5f5; }
        .container { max-width: 1200px; margin: 0 auto; }
        .header { display: flex; justify-content: space-between; margin-bottom: 20px; }
        .logout { color: red; text-decoration: none; }
        .content { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        @media (max-width: 768px) { .content { grid-template-columns: 1fr; } }
        .form-section, .list-section { background: white; padding: 20px; border-radius: 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        h2 { margin-top: 0; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input[type="text"], textarea, select { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; }
        textarea.large { min-height: 150px; }
        .checkbox { display: flex; align-items: center; gap: 10px; }
        .editor-section { margin-bottom: 20px; padding: 15px; background: #f9f9f9; border-radius: 4px; }
        .formatting-help { font-size: 12px; color: #666; margin-top: 10px; padding: 10px; background: #e9e9e9; border-radius: 4px; }
        .formatting-help code { background: #ddd; padding: 2px 4px; border-radius: 3px; }
        button, .button { padding: 10px 15px; background: #4CAF50; color: white; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; display: inline-block; }
        .button.cancel { background: #777; }
        .button.edit { background: #2196F3; }
        .button.delete { background: #f44336; }
        .comment-item { border: 1px solid #ddd; padding: 15px; margin-bottom: 10px; border-radius: 4px; background: white; cursor: pointer; }
        .comment-item:hover { background: #f9f9f9; }
        .comment-header { display: flex; justify-content: space-between; margin-bottom: 10px; }
        .comment-title { font-weight: bold; margin: 0; }
        .comment-date { color: #666; font-size: 14px; }
        .comment-text { color: #444; margin-bottom: 10px; }
        .comment-actions { display: flex; gap: 5px; }
        .pinned { background: #fff8e1; border-color: #ffd54f; }
        .pinned-badge { background: #ff9800; color: white; padding: 2px 6px; border-radius: 3px; font-size: 12px; }
        .slug { font-family: monospace; color: #666; font-size: 12px; }
        .success { background: #d4edda; color: #155724; padding: 10px; border-radius: 4px; margin-bottom: 15px; }
        .edit-form { background: #e3f2fd; padding: 20px; border-radius: 5px; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Управление комментариями</h1>
            <a href="?logout" class="logout">Выйти</a>
        </div>
        
        <?php if (isset($success)): ?>
            <div class="success"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <div class="content">
            <!-- Форма добавления/редактирования -->
            <div class="form-section">
                <h2><?php echo $isEditing ? 'Редактировать комментарий' : 'Добавить новый комментарий'; ?></h2>
                
                <?php if ($isEditing && $editingComment): ?>
                    <div class="edit-form">
                        <h3>Редактирование: <?php echo htmlspecialchars($editingComment['title']); ?></h3>
                        <form method="POST">
                            <input type="hidden" name="action" value="update">
                            <input type="hidden" name="id" value="<?php echo $editingComment['id']; ?>">
                            
                            <div class="form-group">
                                <label>Дата</label>
                                <input type="text" name="date" value="<?php echo $editingComment['date']; ?>" placeholder="дд.мм.гггг" required>
                            </div>
                            
                            <div class="form-group">
                                <label>Заголовок</label>
                                <input type="text" name="title" value="<?php echo htmlspecialchars($editingComment['title']); ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label>Slug (уникальная часть URL)</label>
                                <input type="text" name="slug" value="<?php echo htmlspecialchars($slugContent); ?>">
                                <small>Если оставить пустым, будет сгенерирован автоматически.</small>
                            </div>
                            
                            <div class="form-group checkbox">
                                <input type="checkbox" name="pinned" id="pinned" value="1" <?php echo $pinnedStatus ? 'checked' : ''; ?>>
                                <label for="pinned">Закрепить комментарий</label>
                            </div>
                            
                            <div class="editor-section">
                                <h4>Простой текст (для карточки)</h4>
                                <textarea name="simple_content_text" class="large"><?php echo htmlspecialchars($simpleTextContent); ?></textarea>
                            </div>
                            
                            <div class="editor-section">
                                <h4>Форматированный текст (для страницы комментария)</h4>
                                <textarea name="formatted_content_text" class="large"><?php echo htmlspecialchars($formattedTextContent); ?></textarea>
                                
                                <div class="formatting-help">
                                    <strong>Форматирование:</strong><br>
                                    • Ссылки: [текст](https://example.com)<br>
                                    • Жирный: **текст**<br>
                                    • Курсив: *текст*<br>
                                    • Просто Enter для новой строки
                                </div>
                            </div>
                            
                            <div style="display: flex; gap: 10px; margin-top: 20px;">
                                <button type="submit">Сохранить</button>
                                <a href="admin-comments.php" class="button cancel">Отмена</a>
                            </div>
                        </form>
                    </div>
                <?php else: ?>
                    <form method="POST">
                        <div class="form-group">
                            <label>Дата</label>
                            <input type="text" name="date" value="<?php echo date('d.m.Y'); ?>" placeholder="дд.мм.гггг" required>
                        </div>
                        
                        <div class="form-group">
                            <label>Заголовок</label>
                            <input type="text" name="title" required>
                        </div>
                        
                        <div class="form-group">
                            <label>Slug (уникальная часть URL)</label>
                            <input type="text" name="slug">
                            <small>Если оставить пустым, будет сгенерирован автоматически.</small>
                        </div>
                        
                        <div class="form-group checkbox">
                            <input type="checkbox" name="pinned" id="pinned" value="1">
                            <label for="pinned">Закрепить комментарий</label>
                        </div>
                        
                        <div class="editor-section">
                            <h4>Простой текст (для карточки)</h4>
                            <textarea name="simple_content_text" class="large" required></textarea>
                        </div>
                        
                        <div class="editor-section">
                            <h4>Форматированный текст (для страницы комментария)</h4>
                            <textarea name="formatted_content_text" class="large"></textarea>
                            
                            <div class="formatting-help">
                                <strong>Форматирование:</strong><br>
                                • Ссылки: [текст](https://example.com)<br>
                                • Жирный: **текст**<br>
                                • Курсив: *текст*<br>
                                • Просто Enter для новой строки
                            </div>
                        </div>
                        
                        <button type="submit">Добавить комментарий</button>
                    </form>
                <?php endif; ?>
            </div>
            
            <!-- Список комментариев -->
            <div class="list-section">
                <h2>Существующие комментарии (<?php echo count($comments); ?>)</h2>
                
                <?php if (empty($comments)): ?>
                    <p>Пока нет комментариев</p>
                <?php else: ?>
                    <div>
                        <?php foreach ($comments as $comment): ?>
                            <div class="comment-item <?php echo $comment['pinned'] ? 'pinned' : ''; ?>" onclick="window.location.href='?edit=<?php echo $comment['id']; ?>'">
                                <div class="comment-header">
                                    <h3 class="comment-title">
                                        <?php echo $comment['title']; ?>
                                        <?php if ($comment['pinned']): ?>
                                            <span class="pinned-badge">Закреплено</span>
                                        <?php endif; ?>
                                    </h3>
                                    <span class="comment-date"><?php echo $comment['date']; ?></span>
                                </div>
                                
                                <?php if (!empty($comment['slug'])): ?>
                                    <div class="slug">Slug: <?php echo $comment['slug']; ?></div>
                                <?php endif; ?>
                                
                                <div class="comment-text">
                                    <?php echo htmlspecialchars_decode($comment['text_simple'] ?? ''); ?>
                                </div>
                                
                                <div class="comment-actions" onclick="event.stopPropagation();">
                                    <a href="?edit=<?php echo $comment['id']; ?>" class="button edit">Редактировать</a>
                                    <a href="comment.php?slug=<?php echo urlencode($comment['slug']); ?>" class="button edit" target="_blank">Просмотр</a>
                                    <a href="?delete=<?php echo $comment['id']; ?>" class="button delete" onclick="return confirm('Удалить этот комментарий?')">Удалить</a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <script>
        // Автогенерация slug из заголовка
        document.addEventListener('DOMContentLoaded', function() {
            const titleInput = document.querySelector('input[name="title"]');
            const slugInput = document.querySelector('input[name="slug"]');
            
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
            
            // Установка текущей даты
            const dateInput = document.querySelector('input[name="date"]');
            const setTodayBtn = document.createElement('button');
            setTodayBtn.type = 'button';
            setTodayBtn.textContent = 'Сегодня';
            setTodayBtn.style.marginLeft = '10px';
            setTodayBtn.style.padding = '5px 10px';
            setTodayBtn.style.fontSize = '12px';
            setTodayBtn.addEventListener('click', function() {
                const today = new Date();
                const day = String(today.getDate()).padStart(2, '0');
                const month = String(today.getMonth() + 1).padStart(2, '0');
                const year = today.getFullYear();
                dateInput.value = `${day}.${month}.${year}`;
            });
            
            if (dateInput) {
                dateInput.parentNode.appendChild(setTodayBtn);
            }
        });
    </script>
</body>
</html>
