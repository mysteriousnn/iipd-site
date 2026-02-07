<?php
session_start();

// ДОБАВЬТЕ ЭТИ 3 СТРОКИ В НАЧАЛЕ КАЖДОГО АДМИН-ФАЙЛА
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

$password = 'ваш_секретный_пароль'; // ИЗМЕНИТЕ ЭТОТ ПАРОЛЬ!
$dataFile = 'trials-data.json';

// Проверка авторизации
if (!isset($_SESSION['auth_trials']) || $_SESSION['auth_trials'] !== true) {
    header('Location: admin-trials.php');
    exit;
}

// Чтение существующих данных
if (file_exists($dataFile)) {
    $content = json_decode(file_get_contents($dataFile), true);
    if (!$content) $content = [];
} else {
    header('Location: admin-trials.php');
    exit;
}

// Получение ID блока для редактирования
$blockId = $_GET['id'] ?? '';
$blockToEdit = null;

foreach ($content as $block) {
    if ($block['id'] === $blockId) {
        $blockToEdit = $block;
        break;
    }
}

if (!$blockToEdit) {
    header('Location: admin-trials.php');
    exit;
}

// Обработка сохранения изменений
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($content as &$block) {
        if ($block['id'] === $blockId) {
            if ($block['type'] === 'text' || $block['type'] === 'alert' || $block['type'] === 'info') {
                $block['content'] = $_POST['content'];
                if ($block['type'] === 'alert' || $block['type'] === 'info') {
                    $block['title'] = $_POST['title'] ?? '';
                }
            } elseif ($block['type'] === 'image' || $block['type'] === 'video') {
                $block['caption'] = $_POST['caption'] ?? '';
                if ($block['type'] === 'video') {
                    $block['thumbnail'] = $_POST['thumbnail'] ?? '';
                }
                
                // Обновление URL, если предоставлен новый
                if (!empty($_POST['external_url'])) {
                    $block['content'] = $_POST['external_url'];
                }
            }
            $block['updated_at'] = date('Y-m-d H:i:s');
            break;
        }
    }
    
    file_put_contents($dataFile, json_encode($content, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    $success = 'Блок успешно обновлен!';
    $blockToEdit = $content[array_search($blockId, array_column($content, 'id'))];
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Редактирование блока - Пробные админы</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <style>
        :root {
            --bg-primary: #1a1a1a;
            --bg-secondary: #262626;
            --text-primary: #ffffff;
            --text-secondary: rgba(255, 255, 255, 0.85);
            --border-color: rgba(255, 255, 255, 0.08);
            --purple-color: #8b5cf6;
            --blue-color: #3b82f6;
            --red-color: #c45c5a;
            --yellow-color: #ecc33c;
            --green-color: #a6bb7c;
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
            max-width: 1000px;
            margin: 0 auto;
        }
        
        .admin-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid var(--border-color);
            flex-wrap: wrap;
            gap: 20px;
        }
        
        .admin-title {
            font-size: 1.8rem;
            font-weight: 700;
            background: linear-gradient(135deg, #8b5cf6, #3b82f6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .header-actions {
            display: flex;
            gap: 15px;
            align-items: center;
        }
        
        .back-button {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            background: rgba(255, 255, 255, 0.05);
            color: var(--text-secondary);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .back-button:hover {
            background: rgba(255, 255, 255, 0.1);
            color: var(--text-primary);
        }
        
        .edit-form {
            background: var(--bg-secondary);
            border-radius: 15px;
            padding: 30px;
            border: 1px solid var(--border-color);
        }
        
        .form-title {
            font-size: 1.5rem;
            margin-bottom: 25px;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .form-title i {
            color: var(--yellow-color);
        }
        
        .block-info {
            display: flex;
            gap: 20px;
            margin-bottom: 25px;
            padding: 20px;
            background: rgba(255, 255, 255, 0.03);
            border-radius: 10px;
            border: 1px solid var(--border-color);
        }
        
        .block-type {
            font-size: 0.9rem;
            padding: 4px 12px;
            border-radius: 20px;
            font-weight: 600;
        }
        
        .type-text { background: rgba(139, 92, 246, 0.1); color: #8b5cf6; }
        .type-image { background: rgba(59, 130, 246, 0.1); color: #3b82f6; }
        .type-video { background: rgba(236, 195, 60, 0.1); color: #ecc33c; }
        .type-alert { background: rgba(255, 92, 32, 0.1); color: #ff5c20; }
        .type-info { background: rgba(166, 187, 124, 0.1); color: #a6bb7c; }
        
        .block-date {
            color: var(--text-secondary);
            font-size: 0.9rem;
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
        
        .form-input, .form-textarea {
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
            min-height: 300px;
            resize: vertical;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .form-input:focus, .form-textarea:focus {
            outline: none;
            border-color: var(--purple-color);
            box-shadow: 0 0 0 2px rgba(139, 92, 246, 0.2);
        }
        
        .submit-button {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #8b5cf6, #3b82f6);
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
            box-shadow: 0 5px 15px rgba(139, 92, 246, 0.3);
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
        
        .editor-container {
            background: #ffffff;
            border-radius: 8px;
            margin-bottom: 15px;
            min-height: 400px;
        }
        
        #editor {
            min-height: 400px;
            color: #1a1a1a;
        }
        
        .media-preview {
            margin-bottom: 20px;
            text-align: center;
        }
        
        .media-preview img {
            max-width: 100%;
            max-height: 300px;
            border-radius: 8px;
            margin-bottom: 10px;
        }
        
        .media-preview video {
            max-width: 100%;
            max-height: 300px;
            border-radius: 8px;
            margin-bottom: 10px;
        }
        
        .form-buttons {
            display: flex;
            gap: 15px;
            margin-top: 30px;
        }
        
        .cancel-button {
            flex: 1;
            padding: 15px;
            background: rgba(255, 255, 255, 0.05);
            color: rgba(255, 255, 255, 0.85);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            text-align: center;
        }
        
        .cancel-button:hover {
            background: rgba(255, 255, 255, 0.1);
        }
        
        @media (max-width: 768px) {
            .admin-header {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .header-actions {
                width: 100%;
                justify-content: space-between;
            }
            
            .edit-form {
                padding: 20px;
            }
            
            .form-buttons {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <div class="admin-header">
            <h1 class="admin-title">Редактирование блока</h1>
            <div class="header-actions">
                <a href="admin-trials.php" class="back-button">
                    <i class="fas fa-arrow-left"></i> Назад к списку
                </a>
            </div>
        </div>
        
        <?php if (isset($success)): ?>
            <div class="success-message">
                <i class="fas fa-check-circle"></i> <?php echo $success; ?>
            </div>
        <?php endif; ?>
        
        <div class="edit-form">
            <div class="block-info">
                <div class="block-type type-<?php echo $blockToEdit['type']; ?>">
                    <?php 
                    $typeNames = [
                        'text' => 'Текст',
                        'image' => 'Изображение',
                        'video' => 'Видео',
                        'alert' => 'Внимание',
                        'info' => 'Информация'
                    ];
                    echo $typeNames[$blockToEdit['type']] ?? $blockToEdit['type'];
                    ?>
                </div>
                <div class="block-date">
                    Создан: <?php echo date('d.m.Y H:i', strtotime($blockToEdit['created_at'])); ?>
                    <?php if (isset($blockToEdit['updated_at'])): ?>
                        <br>Обновлен: <?php echo date('d.m.Y H:i', strtotime($blockToEdit['updated_at'])); ?>
                    <?php endif; ?>
                </div>
            </div>
            
            <form method="POST">
                <h2 class="form-title">
                    <i class="fas fa-edit"></i>
                    Редактировать содержимое
                </h2>
                
                <?php if ($blockToEdit['type'] === 'text'): ?>
                    <div class="form-group">
                        <label class="form-label">Текст</label>
                        <div class="editor-container">
                            <div id="editor"></div>
                        </div>
                        <textarea name="content" id="text-content" style="display: none;" required><?php echo htmlspecialchars($blockToEdit['content']); ?></textarea>
                    </div>
                    
                <?php elseif ($blockToEdit['type'] === 'image'): ?>
                    <div class="media-preview">
                        <img src="<?php echo $blockToEdit['content']; ?>" alt="Текущее изображение">
                        <p style="color: var(--text-secondary); margin-top: 10px;">
                            Текущее изображение: <?php echo basename($blockToEdit['content']); ?>
                        </p>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Новый URL изображения (оставьте пустым, чтобы не менять)</label>
                        <input type="url" name="external_url" class="form-input" placeholder="https://example.com/new-image.jpg">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Подпись к изображению</label>
                        <input type="text" name="caption" class="form-input" value="<?php echo htmlspecialchars($blockToEdit['caption'] ?? ''); ?>" placeholder="Описание изображения...">
                    </div>
                    
                <?php elseif ($blockToEdit['type'] === 'video'): ?>
                    <div class="media-preview">
                        <video controls style="max-width: 100%;">
                            <source src="<?php echo $blockToEdit['content']; ?>" type="video/mp4">
                            Ваш браузер не поддерживает видео тег.
                        </video>
                        <p style="color: var(--text-secondary); margin-top: 10px;">
                            Текущее видео: <?php echo basename($blockToEdit['content']); ?>
                        </p>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Новый URL видео (оставьте пустым, чтобы не менять)</label>
                        <input type="url" name="external_url" class="form-input" placeholder="https://example.com/new-video.mp4">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Подпись к видео</label>
                        <input type="text" name="caption" class="form-input" value="<?php echo htmlspecialchars($blockToEdit['caption'] ?? ''); ?>" placeholder="Описание видео...">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Обложка видео</label>
                        <input type="url" name="thumbnail" class="form-input" value="<?php echo htmlspecialchars($blockToEdit['thumbnail'] ?? ''); ?>" placeholder="https://example.com/thumbnail.jpg">
                    </div>
                    
                <?php elseif ($blockToEdit['type'] === 'alert'): ?>
                    <div class="form-group">
                        <label class="form-label">Заголовок предупреждения</label>
                        <input type="text" name="title" class="form-input" value="<?php echo htmlspecialchars($blockToEdit['title'] ?? ''); ?>" placeholder="Например: Важно!">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Текст предупреждения</label>
                        <div class="editor-container">
                            <div id="editor"></div>
                        </div>
                        <textarea name="content" id="text-content" style="display: none;" required><?php echo htmlspecialchars($blockToEdit['content']); ?></textarea>
                    </div>
                    
                <?php elseif ($blockToEdit['type'] === 'info'): ?>
                    <div class="form-group">
                        <label class="form-label">Заголовок информации</label>
                        <input type="text" name="title" class="form-input" value="<?php echo htmlspecialchars($blockToEdit['title'] ?? ''); ?>" placeholder="Например: Полезно знать">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Текст информации</label>
                        <div class="editor-container">
                            <div id="editor"></div>
                        </div>
                        <textarea name="content" id="text-content" style="display: none;" required><?php echo htmlspecialchars($blockToEdit['content']); ?></textarea>
                    </div>
                    
                <?php endif; ?>
                
                <div class="form-buttons">
                    <button type="submit" class="submit-button">
                        <i class="fas fa-save"></i> Сохранить изменения
                    </button>
                    <a href="admin-trials.php" class="cancel-button">
                        <i class="fas fa-times"></i> Отмена
                    </a>
                </div>
            </form>
        </div>
    </div>
    
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
    <script>
        // Инициализация редактора
        const editor = new Quill('#editor', {
            theme: 'snow',
            modules: {
                toolbar: [
                    [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
                    ['bold', 'italic', 'underline', 'strike'],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    [{ 'script': 'sub'}, { 'script': 'super' }],
                    [{ 'indent': '-1'}, { 'indent': '+1' }],
                    [{ 'direction': 'rtl' }],
                    [{ 'color': [] }, { 'background': [] }],
                    [{ 'align': [] }],
                    ['link', 'image', 'video', 'blockquote', 'code-block'],
                    ['clean']
                ]
            }
        });
        
        // Загрузка существующего контента в редактор
        const textarea = document.getElementById('text-content');
        if (textarea && textarea.value) {
            editor.root.innerHTML = textarea.value;
        }
        
        // Обновление textarea при изменении редактора
        editor.on('text-change', function() {
            if (textarea) {
                textarea.value = editor.root.innerHTML;
            }
        });
        
        // Предотвращение ухода без сохранения
        let formChanged = false;
        const form = document.querySelector('form');
        
        if (form) {
            const inputs = form.querySelectorAll('input, textarea, [contenteditable]');
            inputs.forEach(input => {
                if (input.hasAttribute('contenteditable')) {
                    input.addEventListener('input', () => {
                        formChanged = true;
                    });
                } else {
                    input.addEventListener('input', () => {
                        formChanged = true;
                    });
                    input.addEventListener('change', () => {
                        formChanged = true;
                    });
                }
            });
            
            form.addEventListener('submit', () => {
                formChanged = false;
            });
            
            window.addEventListener('beforeunload', (e) => {
                if (formChanged) {
                    e.preventDefault();
                    e.returnValue = 'У вас есть несохраненные изменения. Вы уверены, что хотите покинуть страницу?';
                }
            });
        }
    </script>
</body>
</html>
