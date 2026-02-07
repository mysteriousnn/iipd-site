<?php
session_start();

// ДОБАВЬТЕ ЭТИ 3 СТРОКИ В НАЧАЛЕ КАЖДОГО АДМИН-ФАЙЛА
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

$password = 'ваш_секретный_пароль'; // ИЗМЕНИТЕ ЭТОТ ПАРОЛЬ!
$trialsDataFile = 'trials-data.json';
$adminsDataFile = 'admins-data.json';

// Функция для обработки загрузки файлов
function handleFileUpload($file, $type, $folder = 'trials') {
    $allowedImageTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml'];
    
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return ['success' => false, 'error' => 'Ошибка загрузки файла'];
    }
    
    if ($type === 'image' && !in_array($file['type'], $allowedImageTypes)) {
        return ['success' => false, 'error' => 'Недопустимый формат изображения. Допустимые: JPG, PNG, GIF, WebP, SVG'];
    }
    
    // Создаем папку для медиа, если ее нет
    $uploadDir = "media/$folder/";
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    
    // Генерируем уникальное имя файла
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid() . '.' . $extension;
    $filepath = $uploadDir . $filename;
    
    if (move_uploaded_file($file['tmp_name'], $filepath)) {
        return ['success' => true, 'path' => $filepath, 'filename' => $filename];
    }
    
    return ['success' => false, 'error' => 'Не удалось сохранить файл'];
}

// Проверка авторизации
if (!isset($_SESSION['auth_trials']) || $_SESSION['auth_trials'] !== true) {
    if (isset($_POST['password'])) {
        if ($_POST['password'] === $password) {
            $_SESSION['auth_trials'] = true;
        } else {
            $error = 'Неверный пароль';
        }
    }
    
    if (!isset($_SESSION['auth_trials']) || $_SESSION['auth_trials'] !== true) {
        ?>
        <!DOCTYPE html>
        <html lang="ru">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Вход в админ-панель "Пробные админы"</title>
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
                    border-color: #8b5cf6;
                    box-shadow: 0 0 0 2px rgba(139, 92, 246, 0.2);
                }
                
                .login-button {
                    width: 100%;
                    padding: 14px;
                    background: linear-gradient(135deg, #8b5cf6, #3b82f6);
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
                    box-shadow: 0 5px 15px rgba(139, 92, 246, 0.3);
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
                <h1 class="login-title">Вход в админ-панель "Пробные админы"</h1>
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
                <a href="/" class="back-link">← Вернуться на главную</a>
                <a href="trials.html" class="back-link" style="margin-top: 10px;">← Посмотреть публичную страницу</a>
            </div>
        </body>
        </html>
        <?php
        exit;
    }
}

// Чтение существующих данных
if (file_exists($trialsDataFile)) {
    $content = json_decode(file_get_contents($trialsDataFile), true);
    if (!$content) $content = [];
} else {
    $content = [];
}

// Чтение данных админов
if (file_exists($adminsDataFile)) {
    $admins = json_decode(file_get_contents($adminsDataFile), true);
    if (!$admins) {
        $admins = [
            'approved_channel_admins' => [],
            'verified_channel_admins' => [],
            'approved_chat_admins' => [],
            'trial_admins' => []
        ];
    }
} else {
    $admins = [
        'approved_channel_admins' => [],
        'verified_channel_admins' => [],
        'approved_chat_admins' => [],
        'trial_admins' => []
    ];
}

// Функция для получения текста статуса
function getStatusText($status) {
    $statuses = [
        'active' => 'Активный',
        'inactive' => 'Не активный',
        'kick' => 'На кик',
        'weak' => 'Слабый актив',
        'medium' => 'Средний актив'
    ];
    return $statuses[$status] ?? 'Неизвестно';
}

// Функция для получения класса статуса
function getStatusClass($status) {
    $classes = [
        'active' => 'status-active',
        'inactive' => 'status-inactive',
        'kick' => 'status-kick',
        'weak' => 'status-weak',
        'medium' => 'status-medium'
    ];
    return $classes[$status] ?? 'status-inactive';
}

// Обработка добавления нового блока контента
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        // ОБРАБОТКА КОНТЕНТА
        if ($_POST['action'] === 'add_block') {
            $newBlock = [
                'id' => uniqid(),
                'type' => $_POST['type'],
                'position' => count($content),
                'created_at' => date('Y-m-d H:i:s')
            ];
            
            if ($_POST['type'] === 'text') {
                $newBlock['content'] = $_POST['content'];
            } elseif ($_POST['type'] === 'image') {
                // Обработка загрузки файла
                if (isset($_FILES['media_file']) && $_FILES['media_file']['error'] === UPLOAD_ERR_OK) {
                    $uploadResult = handleFileUpload($_FILES['media_file'], 'image', 'trials');
                    if ($uploadResult['success']) {
                        $newBlock['content'] = $uploadResult['path'];
                        $newBlock['caption'] = $_POST['caption'] ?? '';
                    } else {
                        $uploadError = $uploadResult['error'];
                    }
                } elseif (!empty($_POST['external_url'])) {
                    $newBlock['content'] = $_POST['external_url'];
                    $newBlock['caption'] = $_POST['caption'] ?? '';
                } else {
                    $uploadError = 'Необходимо загрузить файл или указать URL';
                }
            }
            
            if (!isset($uploadError)) {
                $content[] = $newBlock;
                file_put_contents($trialsDataFile, json_encode($content, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
                $success = 'Блок контента успешно добавлен!';
            }
        }
        
        // Обновление порядка блоков контента
        elseif ($_POST['action'] === 'update_order') {
            $newOrder = json_decode($_POST['order'], true);
            $reorderedContent = [];
            
            foreach ($newOrder as $position => $blockId) {
                foreach ($content as $block) {
                    if ($block['id'] === $blockId) {
                        $block['position'] = $position;
                        $reorderedContent[] = $block;
                        break;
                    }
                }
            }
            
            file_put_contents($trialsDataFile, json_encode($reorderedContent, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            $content = $reorderedContent;
            $success = 'Порядок блоков обновлен!';
        }
        
        // Удаление блока контента
        elseif ($_POST['action'] === 'delete_block') {
            $blockId = $_POST['block_id'];
            $newContent = array_filter($content, function($block) use ($blockId) {
                return $block['id'] !== $blockId;
            });
            
            // Обновляем позиции
            $newContent = array_values($newContent);
            foreach ($newContent as $index => &$block) {
                $block['position'] = $index;
            }
            
            file_put_contents($trialsDataFile, json_encode($newContent, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            $content = $newContent;
            $success = 'Блок контента успешно удален!';
        }
        
        // ОБРАБОТКА АДМИНОВ
        // Добавление админа
        elseif ($_POST['action'] === 'add_admin') {
            $category = $_POST['category'];
            $username = trim($_POST['username']);
            $status = $_POST['status'] ?? 'inactive';
            
            if (empty($username)) {
                $error = 'Введите имя пользователя';
            } else {
                $newAdmin = [
                    'id' => uniqid(),
                    'username' => htmlspecialchars($username),
                    'status' => $status,
                    'added_at' => date('Y-m-d H:i:s')
                ];
                
                // Обработка аватарки
                if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
                    $uploadResult = handleFileUpload($_FILES['avatar'], 'image', 'admins');
                    if ($uploadResult['success']) {
                        $newAdmin['avatar'] = $uploadResult['path'];
                    }
                } elseif (!empty($_POST['avatar_url'])) {
                    $newAdmin['avatar'] = $_POST['avatar_url'];
                }
                
                // Добавляем админа в соответствующую категорию
                if (isset($admins[$category])) {
                    $admins[$category][] = $newAdmin;
                    file_put_contents($adminsDataFile, json_encode($admins, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
                    $success = 'Админ успешно добавлен!';
                }
            }
        }
        
        // Удаление админа
        elseif ($_POST['action'] === 'delete_admin') {
            $adminId = $_POST['admin_id'];
            $category = $_POST['category'];
            
            if (isset($admins[$category])) {
                $admins[$category] = array_filter($admins[$category], function($admin) use ($adminId) {
                    return $admin['id'] !== $adminId;
                });
                
                // Переиндексируем массив
                $admins[$category] = array_values($admins[$category]);
                file_put_contents($adminsDataFile, json_encode($admins, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
                $success = 'Админ успешно удален!';
            }
        }
        
        // Редактирование админа
        elseif ($_POST['action'] === 'edit_admin') {
            $adminId = $_POST['admin_id'];
            $category = $_POST['category'];
            $username = trim($_POST['username']);
            $status = $_POST['status'] ?? 'inactive';
            
            if (empty($username)) {
                $error = 'Введите имя пользователя';
            } else {
                foreach ($admins[$category] as &$admin) {
                    if ($admin['id'] === $adminId) {
                        $admin['username'] = htmlspecialchars($username);
                        $admin['status'] = $status;
                        $admin['updated_at'] = date('Y-m-d H:i:s');
                        
                        // Обработка новой аватарки
                        if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
                            $uploadResult = handleFileUpload($_FILES['avatar'], 'image', 'admins');
                            if ($uploadResult['success']) {
                                $admin['avatar'] = $uploadResult['path'];
                            }
                        } elseif (!empty($_POST['avatar_url'])) {
                            $admin['avatar'] = $_POST['avatar_url'];
                        }
                        
                        break;
                    }
                }
                
                file_put_contents($adminsDataFile, json_encode($admins, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
                $success = 'Админ успешно обновлен!';
            }
        }
        
        // Редактирование блока контента
        elseif ($_POST['action'] === 'edit_block') {
            $blockId = $_POST['block_id'];
            foreach ($content as &$block) {
                if ($block['id'] === $blockId) {
                    if ($block['type'] === 'text') {
                        $block['content'] = $_POST['content'];
                    } elseif ($block['type'] === 'image') {
                        $block['caption'] = $_POST['caption'] ?? '';
                        
                        // Обновление URL, если предоставлен новый
                        if (!empty($_POST['external_url'])) {
                            $block['content'] = $_POST['external_url'];
                        }
                    }
                    $block['updated_at'] = date('Y-m-d H:i:s');
                    break;
                }
            }
            
            file_put_contents($trialsDataFile, json_encode($content, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            $success = 'Блок контента успешно обновлен!';
        }
    }
}

// Сортируем контент по позиции
usort($content, function($a, $b) {
    return $a['position'] - $b['position'];
});
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Админ-панель: Пробные админы</title>
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
            --orange-color: #ff5c20;
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
            flex-wrap: wrap;
            gap: 20px;
        }
        
        .admin-title {
            font-size: 2rem;
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
        
        .preview-button {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            background: rgba(166, 187, 124, 0.1);
            color: #a6bb7c;
            border: 1px solid rgba(166, 187, 124, 0.3);
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .preview-button:hover {
            background: rgba(166, 187, 124, 0.2);
        }
        
        .content-tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 30px;
            border-bottom: 2px solid var(--border-color);
            padding-bottom: 5px;
        }
        
        .content-tab {
            padding: 12px 25px;
            background: rgba(255, 255, 255, 0.05);
            border: none;
            border-radius: 8px 8px 0 0;
            color: var(--text-secondary);
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 1rem;
        }
        
        .content-tab:hover {
            background: rgba(255, 255, 255, 0.1);
        }
        
        .content-tab.active {
            background: rgba(139, 92, 246, 0.2);
            color: var(--purple-color);
            position: relative;
        }
        
        .content-tab.active::after {
            content: '';
            position: absolute;
            bottom: -7px;
            left: 0;
            width: 100%;
            height: 3px;
            background: var(--purple-color);
            border-radius: 3px;
        }
        
        .tab-content {
            display: none;
        }
        
        .tab-content.active {
            display: block;
        }
        
        /* СТИЛИ ДЛЯ УПРАВЛЕНИЯ КОНТЕНТОМ */
        .content-wrapper {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 40px;
        }
        
        @media (max-width: 1200px) {
            .content-wrapper {
                grid-template-columns: 1fr;
            }
        }
        
        .add-block-form, .blocks-list {
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
            color: var(--purple-color);
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
            min-height: 200px;
            resize: vertical;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .form-input:focus, .form-select:focus, .form-textarea:focus {
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
        
        .error-message {
            background: rgba(196, 92, 90, 0.1);
            color: #c45c5a;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border: 1px solid rgba(196, 92, 90, 0.3);
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .blocks-container {
            margin-top: 20px;
        }
        
        .block-item {
            background: rgba(255, 255, 255, 0.03);
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 15px;
            border: 1px solid var(--border-color);
            transition: all 0.3s ease;
            position: relative;
            cursor: move;
        }
        
        .block-item:hover {
            background: rgba(255, 255, 255, 0.05);
            border-color: rgba(255, 255, 255, 0.15);
        }
        
        .block-item.dragging {
            opacity: 0.5;
            border: 2px dashed var(--purple-color);
        }
        
        .block-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .block-type {
            font-size: 0.9rem;
            padding: 4px 12px;
            border-radius: 20px;
            font-weight: 600;
        }
        
        .type-text { background: rgba(139, 92, 246, 0.1); color: #8b5cf6; }
        .type-image { background: rgba(59, 130, 246, 0.1); color: #3b82f6; }
        
        .block-preview {
            max-height: 200px;
            overflow: hidden;
            margin-bottom: 15px;
            position: relative;
        }
        
        .block-preview::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 40px;
            background: linear-gradient(transparent, rgba(0, 0, 0, 0.5));
        }
        
        .block-preview img {
            width: 100%;
            height: auto;
            border-radius: 5px;
            max-width: 100%;
        }
        
        .block-preview .text-preview {
            color: var(--text-secondary);
            font-size: 0.95rem;
            line-height: 1.5;
        }
        
        .block-actions {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
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
            display: inline-flex;
            align-items: center;
            gap: 6px;
            cursor: pointer;
            border: none;
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
            display: inline-flex;
            align-items: center;
            gap: 6px;
            cursor: pointer;
            border: none;
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
        
        .drag-handle {
            position: absolute;
            top: 20px;
            left: 20px;
            color: rgba(255, 255, 255, 0.5);
            cursor: move;
            font-size: 1.2rem;
        }
        
        .block-position {
            position: absolute;
            top: 20px;
            right: 20px;
            color: rgba(255, 255, 255, 0.5);
            font-size: 0.9rem;
            background: rgba(0, 0, 0, 0.3);
            padding: 2px 8px;
            border-radius: 10px;
        }
        
        .file-upload-container {
            border: 2px dashed var(--border-color);
            border-radius: 8px;
            padding: 30px;
            text-align: center;
            margin-bottom: 15px;
            transition: all 0.3s ease;
        }
        
        .file-upload-container:hover {
            border-color: var(--purple-color);
            background: rgba(139, 92, 246, 0.05);
        }
        
        .file-upload-label {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 15px;
            cursor: pointer;
        }
        
        .file-upload-label i {
            font-size: 2.5rem;
            color: var(--purple-color);
        }
        
        .upload-hint {
            font-size: 0.9rem;
            color: var(--text-secondary);
            margin-top: 5px;
        }
        
        .form-section {
            margin-bottom: 25px;
            padding: 20px;
            background: rgba(255, 255, 255, 0.03);
            border-radius: 8px;
        }
        
        .form-section-title {
            font-size: 1.1rem;
            margin-bottom: 15px;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .form-section-title i {
            color: var(--blue-color);
        }
        
        .url-toggle {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 15px;
            padding: 10px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 8px;
        }
        
        .url-toggle label {
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            flex: 1;
            text-align: center;
            justify-content: center;
            padding: 8px;
            border-radius: 6px;
            transition: all 0.3s ease;
        }
        
        .url-toggle input[type="radio"] {
            display: none;
        }
        
        .url-toggle input[type="radio"]:checked + span {
            background: rgba(139, 92, 246, 0.2);
            color: var(--purple-color);
            font-weight: 600;
        }
        
        .url-toggle span {
            padding: 8px 16px;
            border-radius: 6px;
            width: 100%;
        }
        
        /* СТИЛИ ДЛЯ УПРАВЛЕНИЯ АДМИНАМИ */
        .admins-management {
            background: var(--bg-secondary);
            border-radius: 15px;
            padding: 30px;
            border: 1px solid var(--border-color);
        }
        
        .admins-categories {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 30px;
            margin-top: 30px;
        }
        
        .admins-category-card {
            background: rgba(255, 255, 255, 0.03);
            border-radius: 12px;
            padding: 25px;
            border: 1px solid var(--border-color);
        }
        
        .category-card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid;
        }
        
        .category-1 .category-card-header {
            border-bottom-color: #8b5cf6;
        }
        
        .category-2 .category-card-header {
            border-bottom-color: #3b82f6;
        }
        
        .category-3 .category-card-header {
            border-bottom-color: #10b981;
        }
        
        .category-4 .category-card-header {
            border-bottom-color: #f59e0b;
        }
        
        .category-title {
            font-size: 1.3rem;
            font-weight: 600;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .category-count {
            font-size: 0.9rem;
            color: var(--text-secondary);
            background: rgba(255, 255, 255, 0.05);
            padding: 4px 12px;
            border-radius: 20px;
        }
        
        .add-admin-form {
            margin-bottom: 25px;
            padding: 20px;
            background: rgba(255, 255, 255, 0.03);
            border-radius: 8px;
        }
        
        .admins-list {
            max-height: 400px;
            overflow-y: auto;
            padding-right: 10px;
        }
        
        .admins-list::-webkit-scrollbar {
            width: 6px;
        }
        
        .admins-list::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 3px;
        }
        
        .admins-list::-webkit-scrollbar-thumb {
            background: rgba(139, 92, 246, 0.5);
            border-radius: 3px;
        }
        
        .admin-item {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 15px;
            margin-bottom: 12px;
            background: rgba(255, 255, 255, 0.02);
            border-radius: 8px;
            border: 1px solid var(--border-color);
            transition: all 0.3s ease;
        }
        
        .admin-item:hover {
            background: rgba(255, 255, 255, 0.05);
            border-color: rgba(255, 255, 255, 0.15);
        }
        
        .admin-avatar-small {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            overflow: hidden;
            flex-shrink: 0;
            border: 2px solid;
        }
        
        .category-1 .admin-avatar-small {
            border-color: #8b5cf6;
        }
        
        .category-2 .admin-avatar-small {
            border-color: #3b82f6;
        }
        
        .category-3 .admin-avatar-small {
            border-color: #10b981;
        }
        
        .category-4 .admin-avatar-small {
            border-color: #f59e0b;
        }
        
        .admin-avatar-small img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .admin-avatar-small .avatar-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #8b5cf6, #3b82f6);
            color: white;
            font-size: 1.2rem;
            font-weight: 600;
        }
        
        .admin-info-small {
            flex: 1;
            min-width: 0;
        }
        
        .admin-username-small {
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 5px;
            word-break: break-word;
        }
        
        .admin-status-small {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 10px;
            border-radius: 15px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-top: 5px;
        }
        
        .admin-status-small.active {
            background: rgba(16, 185, 129, 0.15);
            color: #10b981;
            border: 1px solid rgba(16, 185, 129, 0.3);
        }
        
        .admin-status-small.inactive {
            background: rgba(107, 114, 128, 0.15);
            color: #6b7280;
            border: 1px solid rgba(107, 114, 128, 0.3);
        }
        
        .admin-status-small.kick {
            background: rgba(239, 68, 68, 0.15);
            color: #ef4444;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }
        
        .admin-status-small.weak {
            background: rgba(245, 158, 11, 0.15);
            color: #f59e0b;
            border: 1px solid rgba(245, 158, 11, 0.3);
        }
        
        .admin-status-small.medium {
            background: rgba(249, 115, 22, 0.15);
            color: #f97316;
            border: 1px solid rgba(249, 115, 22, 0.3);
        }
        
        .admin-date {
            font-size: 0.8rem;
            color: var(--text-secondary);
        }
        
        .admin-actions {
            display: flex;
            gap: 8px;
        }
        
        .admin-action-btn {
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 0.9rem;
        }
        
        .edit-admin-btn {
            background: rgba(236, 195, 60, 0.1);
            color: #ecc33c;
        }
        
        .edit-admin-btn:hover {
            background: rgba(236, 195, 60, 0.2);
        }
        
        .delete-admin-btn {
            background: rgba(196, 92, 90, 0.1);
            color: #c45c5a;
        }
        
        .delete-admin-btn:hover {
            background: rgba(196, 92, 90, 0.2);
        }
        
        /* СТИЛИ ДЛЯ РЕДАКТОРА ТЕКСТА (ТЕМНАЯ ТЕМА) */
        .editor-container {
            background: #1a1a1a;
            border-radius: 8px;
            margin-bottom: 15px;
            min-height: 300px;
            border: 1px solid var(--border-color);
        }
        
        #editor {
            min-height: 300px;
            color: var(--text-primary);
            font-size: 1rem;
        }
        
        /* Темная тема для Quill Editor */
        .ql-toolbar.ql-snow {
            border: 1px solid var(--border-color) !important;
            background: rgba(255, 255, 255, 0.05) !important;
            border-radius: 8px 8px 0 0;
        }
        
        .ql-container.ql-snow {
            border: 1px solid var(--border-color) !important;
            border-top: none !important;
            border-radius: 0 0 8px 8px;
            background: #1a1a1a;
        }
        
        .ql-editor {
            color: var(--text-primary) !important;
            min-height: 250px;
        }
        
        .ql-snow .ql-stroke {
            stroke: var(--text-primary) !important;
        }
        
        .ql-snow .ql-fill {
            fill: var(--text-primary) !important;
        }
        
        .ql-snow .ql-picker {
            color: var(--text-primary) !important;
        }
        
        .ql-snow .ql-picker-options {
            background: var(--bg-secondary) !important;
            border: 1px solid var(--border-color) !important;
        }
        
        /* МОДАЛЬНОЕ ОКНО */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.8);
            z-index: 1000;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .modal-overlay.active {
            display: flex;
        }
        
        .modal-content {
            background: var(--bg-secondary);
            border-radius: 15px;
            padding: 30px;
            max-width: 500px;
            width: 100%;
            max-height: 90vh;
            overflow-y: auto;
            border: 1px solid var(--border-color);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
        }
        
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 1px solid var(--border-color);
        }
        
        .modal-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .close-modal {
            background: none;
            border: none;
            color: var(--text-secondary);
            font-size: 1.5rem;
            cursor: pointer;
            transition: color 0.3s ease;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
        }
        
        .close-modal:hover {
            color: var(--text-primary);
            background: rgba(255, 255, 255, 0.05);
        }
        
        .current-avatar {
            text-align: center;
            margin-bottom: 20px;
        }
        
        .current-avatar img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid var(--purple-color);
        }
        
        .avatar-placeholder-large {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: linear-gradient(135deg, #8b5cf6, #3b82f6);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 2rem;
            font-weight: 600;
            margin: 0 auto 20px;
            border: 3px solid var(--purple-color);
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
            
            .content-tabs {
                flex-wrap: wrap;
            }
            
            .content-tab {
                flex: 1;
                min-width: 140px;
                justify-content: center;
            }
            
            .add-block-form, .blocks-list, .admins-management {
                padding: 20px;
            }
            
            .admins-categories {
                grid-template-columns: 1fr;
            }
            
            .content-wrapper {
                grid-template-columns: 1fr;
            }
        }
        
        @media (max-width: 480px) {
            body {
                padding: 15px;
            }
            
            .admin-title {
                font-size: 1.8rem;
            }
            
            .content-tab {
                padding: 10px 15px;
                font-size: 0.9rem;
            }
            
            .form-title, .list-title {
                font-size: 1.3rem;
            }
            
            .admin-item {
                flex-direction: column;
                text-align: center;
                gap: 10px;
            }
            
            .admin-actions {
                width: 100%;
                justify-content: center;
            }
            
            .modal-content {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <div class="admin-header">
            <h1 class="admin-title">Админ-панель: Пробные админы</h1>
            <div class="header-actions">
                <a href="trials.html" class="preview-button" target="_blank">
                    <i class="fas fa-eye"></i> Предпросмотр
                </a>
                <a href="?logout" class="logout-button">
                    <i class="fas fa-sign-out-alt"></i> Выйти
                </a>
            </div>
        </div>
        
        <?php if (isset($success)): ?>
            <div class="success-message">
                <i class="fas fa-check-circle"></i> <?php echo $success; ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($error)): ?>
            <div class="error-message">
                <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($uploadError)): ?>
            <div class="error-message">
                <i class="fas fa-exclamation-circle"></i> <?php echo $uploadError; ?>
            </div>
        <?php endif; ?>
        
        <div class="content-tabs">
            <button class="content-tab active" data-tab="content">
                <i class="fas fa-file-alt"></i> Управление контентом
            </button>
            <button class="content-tab" data-tab="admins">
                <i class="fas fa-users"></i> Управление админами
            </button>
        </div>
        
        <!-- ВКЛАДКА УПРАВЛЕНИЯ КОНТЕНТОМ -->
        <div class="tab-content active" id="content-tab">
            <div class="content-wrapper">
                <!-- Форма добавления блока -->
                <div class="add-block-form">
                    <h2 class="form-title">
                        <i class="fas fa-plus-circle"></i>
                        Добавить новый блок
                    </h2>
                    
                    <div class="form-tabs" id="form-tabs">
                        <div class="form-tab active" data-tab="text">
                            <i class="fas fa-font"></i>
                            Текст
                        </div>
                        <div class="form-tab" data-tab="image">
                            <i class="fas fa-image"></i>
                            Изображение
                        </div>
                    </div>
                    
                    <form method="POST" enctype="multipart/form-data" id="block-form">
                        <input type="hidden" name="action" value="add_block">
                        <input type="hidden" name="type" id="block-type" value="text">
                        
                        <!-- Текст -->
                        <div class="tab-panel active" id="text-panel">
                            <div class="form-group">
                                <label class="form-label">HTML редактор</label>
                                <div class="editor-container">
                                    <div id="editor"></div>
                                </div>
                                <textarea name="content" id="text-content" style="display: none;" required></textarea>
                            </div>
                        </div>
                        
                        <!-- Изображение -->
                        <div class="tab-panel" id="image-panel">
                            <div class="form-section">
                                <div class="form-section-title">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                    Способ загрузки
                                </div>
                                <div class="url-toggle">
                                    <label>
                                        <input type="radio" name="upload_type" value="file" checked>
                                        <span>Загрузить файл</span>
                                    </label>
                                    <label>
                                        <input type="radio" name="upload_type" value="url">
                                        <span>Внешняя ссылка</span>
                                    </label>
                                </div>
                            </div>
                            
                            <div id="image-file-upload" class="form-group">
                                <div class="file-upload-container">
                                    <label class="file-upload-label">
                                        <i class="fas fa-cloud-upload-alt"></i>
                                        <span>Выберите изображение</span>
                                        <input type="file" name="media_file" accept="image/*" class="form-input" style="display: none;" id="image-file">
                                        <span class="upload-hint">JPG, PNG, GIF, WebP, SVG до 10MB</span>
                                    </label>
                                </div>
                            </div>
                            
                            <div id="image-url-upload" class="form-group" style="display: none;">
                                <label class="form-label">URL изображения</label>
                                <input type="url" name="external_url" class="form-input" placeholder="https://example.com/image.jpg">
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Подпись к изображению (необязательно)</label>
                                <input type="text" name="caption" class="form-input" placeholder="Описание изображения...">
                            </div>
                        </div>
                        
                        <button type="submit" class="submit-button">
                            <i class="fas fa-plus"></i> Добавить блок
                        </button>
                    </form>
                </div>
                
                <!-- Список блоков -->
                <div class="blocks-list">
                    <h2 class="list-title">
                        <i class="fas fa-list"></i>
                        Существующие блоки (<?php echo count($content); ?>)
                    </h2>
                    
                    <div class="preview-container">
                        <div class="form-section-title">
                            <i class="fas fa-sort"></i>
                            Порядок отображения (перетащите для изменения)
                        </div>
                        <form method="POST" id="order-form" style="display: none;">
                            <input type="hidden" name="action" value="update_order">
                            <input type="hidden" name="order" id="block-order">
                        </form>
                    </div>
                    
                    <?php if (empty($content)): ?>
                        <div class="empty-message">
                            <i class="fas fa-inbox"></i><br>
                            Пока нет добавленных блоков
                        </div>
                    <?php else: ?>
                        <div class="blocks-container" id="blocks-container">
                            <?php foreach ($content as $index => $block): ?>
                                <div class="block-item" data-id="<?php echo $block['id']; ?>">
                                    <div class="drag-handle">
                                        <i class="fas fa-grip-vertical"></i>
                                    </div>
                                    <div class="block-position">
                                        Позиция: <?php echo $index + 1; ?>
                                    </div>
                                    <div class="block-header">
                                        <div class="block-type type-<?php echo $block['type']; ?>">
                                            <?php 
                                            $typeNames = [
                                                'text' => 'Текст',
                                                'image' => 'Изображение'
                                            ];
                                            echo $typeNames[$block['type']] ?? $block['type'];
                                            ?>
                                        </div>
                                        <div class="block-date">
                                            <?php echo date('d.m.Y', strtotime($block['created_at'])); ?>
                                        </div>
                                    </div>
                                    
                                    <div class="block-preview">
                                        <?php if ($block['type'] === 'text'): ?>
                                            <div class="text-preview">
                                                <?php echo substr(strip_tags($block['content']), 0, 200); ?>...
                                            </div>
                                        <?php elseif ($block['type'] === 'image'): ?>
                                            <img src="<?php echo $block['content']; ?>" alt="Превью" style="max-height: 150px; max-width: 100%;">
                                            <?php if (!empty($block['caption'])): ?>
                                                <div style="margin-top: 10px; color: var(--text-secondary); font-size: 0.9rem;">
                                                    <?php echo $block['caption']; ?>
                                                </div>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="block-actions">
                                        <button type="button" class="edit-button" onclick="editBlock('<?php echo $block['id']; ?>')">
                                            <i class="fas fa-edit"></i> Редактировать
                                        </button>
                                        <button type="button" class="delete-button" onclick="deleteBlock('<?php echo $block['id']; ?>')">
                                            <i class="fas fa-trash"></i> Удалить
                                        </button>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <div style="text-align: center; margin-top: 20px;">
                            <button type="button" class="submit-button" onclick="saveOrder()" style="max-width: 300px; margin: 0 auto;">
                                <i class="fas fa-save"></i> Сохранить порядок
                            </button>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- ВКЛАДКА УПРАВЛЕНИЯ АДМИНАМИ -->
        <div class="tab-content" id="admins-tab">
            <div class="admins-management">
                <h2 class="form-title">
                    <i class="fas fa-users-cog"></i>
                    Управление администраторами
                </h2>
                
                <div class="admins-categories">
                    <!-- Категория 1: Утвержденные Администраторы канала -->
                    <div class="admins-category-card category-1">
                        <div class="category-card-header">
                            <div class="category-title">
                                <i class="fas fa-user-check"></i>
                                Утвержденные Администраторы канала
                            </div>
                            <div class="category-count">
                                <?php echo count($admins['approved_channel_admins']); ?>
                            </div>
                        </div>
                        
                        <!-- Форма добавления админа -->
                        <div class="add-admin-form">
                            <form method="POST" enctype="multipart/form-data" class="admin-form" data-category="approved_channel_admins">
                                <input type="hidden" name="action" value="add_admin">
                                <input type="hidden" name="category" value="approved_channel_admins">
                                
                                <div class="form-group">
                                    <label class="form-label">Имя пользователя (никнейм)</label>
                                    <input type="text" name="username" class="form-input" placeholder="@username или Имя" required>
                                </div>
                                
                                <div class="form-group">
                                    <label class="form-label">Статус</label>
                                    <select name="status" class="form-select" required>
                                        <option value="active">Активный</option>
                                        <option value="inactive" selected>Не активный</option>
                                        <option value="kick">На кик</option>
                                        <option value="weak">Слабый актив</option>
                                        <option value="medium">Средний актив</option>
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <label class="form-label">Аватарка (необязательно)</label>
                                    <div class="file-upload-container" style="padding: 15px;">
                                        <label class="file-upload-label" style="gap: 10px;">
                                            <i class="fas fa-user-circle"></i>
                                            <span>Загрузить аватарку</span>
                                            <input type="file" name="avatar" accept="image/*" class="form-input" style="display: none;">
                                            <span class="upload-hint">JPG, PNG до 5MB</span>
                                        </label>
                                    </div>
                                    <div style="margin-top: 10px; text-align: center; color: var(--text-secondary);">или</div>
                                    <input type="url" name="avatar_url" class="form-input" style="margin-top: 10px;" placeholder="URL аватарки">
                                </div>
                                
                                <button type="submit" class="submit-button" style="padding: 12px;">
                                    <i class="fas fa-user-plus"></i> Добавить администратора
                                </button>
                            </form>
                        </div>
                        
                        <!-- Список админов -->
                        <div class="admins-list">
                            <?php if (empty($admins['approved_channel_admins'])): ?>
                                <div class="empty-message" style="padding: 20px; margin: 0;">
                                    <i class="fas fa-user-slash"></i>
                                    <p>Админы еще не добавлены</p>
                                </div>
                            <?php else: ?>
                                <?php foreach ($admins['approved_channel_admins'] as $admin): ?>
                                    <div class="admin-item" data-id="<?php echo $admin['id']; ?>">
                                        <div class="admin-avatar-small">
                                            <?php if (!empty($admin['avatar'])): ?>
                                                <img src="<?php echo $admin['avatar']; ?>" alt="<?php echo htmlspecialchars($admin['username']); ?>">
                                            <?php else: ?>
                                                <div class="avatar-placeholder">
                                                    <?php echo strtoupper(substr($admin['username'], 0, 1)); ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="admin-info-small">
                                            <div class="admin-username-small"><?php echo htmlspecialchars($admin['username']); ?></div>
                                            <div class="admin-status-small <?php echo getStatusClass($admin['status']); ?>">
                                                <?php echo getStatusText($admin['status']); ?>
                                            </div>
                                            <div class="admin-date">
                                                Добавлен: <?php echo date('d.m.Y', strtotime($admin['added_at'])); ?>
                                                <?php if (isset($admin['updated_at'])): ?>
                                                    <br>Обновлен: <?php echo date('d.m.Y', strtotime($admin['updated_at'])); ?>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="admin-actions">
                                            <button type="button" class="admin-action-btn edit-admin-btn" 
                                                    onclick="openEditAdminModal('<?php echo $admin['id']; ?>', 'approved_channel_admins', '<?php echo htmlspecialchars($admin['username'], ENT_QUOTES); ?>', '<?php echo $admin['avatar'] ?? ''; ?>', '<?php echo $admin['status'] ?? 'inactive'; ?>')">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button type="button" class="admin-action-btn delete-admin-btn" 
                                                    onclick="deleteAdmin('<?php echo $admin['id']; ?>', 'approved_channel_admins')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Категория 2: Заверенные Администраторы канала -->
                    <div class="admins-category-card category-2">
                        <div class="category-card-header">
                            <div class="category-title">
                                <i class="fas fa-user-shield"></i>
                                Заверенные Администраторы канала
                            </div>
                            <div class="category-count">
                                <?php echo count($admins['verified_channel_admins']); ?>
                            </div>
                        </div>
                        
                        <!-- Форма добавления админа -->
                        <div class="add-admin-form">
                            <form method="POST" enctype="multipart/form-data" class="admin-form" data-category="verified_channel_admins">
                                <input type="hidden" name="action" value="add_admin">
                                <input type="hidden" name="category" value="verified_channel_admins">
                                
                                <div class="form-group">
                                    <label class="form-label">Имя пользователя (никнейм)</label>
                                    <input type="text" name="username" class="form-input" placeholder="@username или Имя" required>
                                </div>
                                
                                <div class="form-group">
                                    <label class="form-label">Статус</label>
                                    <select name="status" class="form-select" required>
                                        <option value="active">Активный</option>
                                        <option value="inactive" selected>Не активный</option>
                                        <option value="kick">На кик</option>
                                        <option value="weak">Слабый актив</option>
                                        <option value="medium">Средний актив</option>
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <label class="form-label">Аватарка (необязательно)</label>
                                    <div class="file-upload-container" style="padding: 15px;">
                                        <label class="file-upload-label" style="gap: 10px;">
                                            <i class="fas fa-user-circle"></i>
                                            <span>Загрузить аватарку</span>
                                            <input type="file" name="avatar" accept="image/*" class="form-input" style="display: none;">
                                            <span class="upload-hint">JPG, PNG до 5MB</span>
                                        </label>
                                    </div>
                                    <div style="margin-top: 10px; text-align: center; color: var(--text-secondary);">или</div>
                                    <input type="url" name="avatar_url" class="form-input" style="margin-top: 10px;" placeholder="URL аватарки">
                                </div>
                                
                                <button type="submit" class="submit-button" style="padding: 12px;">
                                    <i class="fas fa-user-plus"></i> Добавить администратора
                                </button>
                            </form>
                        </div>
                        
                        <!-- Список админов -->
                        <div class="admins-list">
                            <?php if (empty($admins['verified_channel_admins'])): ?>
                                <div class="empty-message" style="padding: 20px; margin: 0;">
                                    <i class="fas fa-user-slash"></i>
                                    <p>Админы еще не добавлены</p>
                                </div>
                            <?php else: ?>
                                <?php foreach ($admins['verified_channel_admins'] as $admin): ?>
                                    <div class="admin-item" data-id="<?php echo $admin['id']; ?>">
                                        <div class="admin-avatar-small">
                                            <?php if (!empty($admin['avatar'])): ?>
                                                <img src="<?php echo $admin['avatar']; ?>" alt="<?php echo htmlspecialchars($admin['username']); ?>">
                                            <?php else: ?>
                                                <div class="avatar-placeholder">
                                                    <?php echo strtoupper(substr($admin['username'], 0, 1)); ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="admin-info-small">
                                            <div class="admin-username-small"><?php echo htmlspecialchars($admin['username']); ?></div>
                                            <div class="admin-status-small <?php echo getStatusClass($admin['status']); ?>">
                                                <?php echo getStatusText($admin['status']); ?>
                                            </div>
                                            <div class="admin-date">
                                                Добавлен: <?php echo date('d.m.Y', strtotime($admin['added_at'])); ?>
                                                <?php if (isset($admin['updated_at'])): ?>
                                                    <br>Обновлен: <?php echo date('d.m.Y', strtotime($admin['updated_at'])); ?>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="admin-actions">
                                            <button type="button" class="admin-action-btn edit-admin-btn" 
                                                    onclick="openEditAdminModal('<?php echo $admin['id']; ?>', 'verified_channel_admins', '<?php echo htmlspecialchars($admin['username'], ENT_QUOTES); ?>', '<?php echo $admin['avatar'] ?? ''; ?>', '<?php echo $admin['status'] ?? 'inactive'; ?>')">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button type="button" class="admin-action-btn delete-admin-btn" 
                                                    onclick="deleteAdmin('<?php echo $admin['id']; ?>', 'verified_channel_admins')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Категория 3: Администраторы чата (утвержденные) -->
                    <div class="admins-category-card category-3">
                        <div class="category-card-header">
                            <div class="category-title">
                                <i class="fas fa-comments"></i>
                                Администраторы чата (утвержденные)
                            </div>
                            <div class="category-count">
                                <?php echo count($admins['approved_chat_admins']); ?>
                            </div>
                        </div>
                        
                        <!-- Форма добавления админа -->
                        <div class="add-admin-form">
                            <form method="POST" enctype="multipart/form-data" class="admin-form" data-category="approved_chat_admins">
                                <input type="hidden" name="action" value="add_admin">
                                <input type="hidden" name="category" value="approved_chat_admins">
                                
                                <div class="form-group">
                                    <label class="form-label">Имя пользователя (никнейм)</label>
                                    <input type="text" name="username" class="form-input" placeholder="@username или Имя" required>
                                </div>
                                
                                <div class="form-group">
                                    <label class="form-label">Статус</label>
                                    <select name="status" class="form-select" required>
                                        <option value="active">Активный</option>
                                        <option value="inactive" selected>Не активный</option>
                                        <option value="kick">На кик</option>
                                        <option value="weak">Слабый актив</option>
                                        <option value="medium">Средний актив</option>
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <label class="form-label">Аватарка (необязательно)</label>
                                    <div class="file-upload-container" style="padding: 15px;">
                                        <label class="file-upload-label" style="gap: 10px;">
                                            <i class="fas fa-user-circle"></i>
                                            <span>Загрузить аватарку</span>
                                            <input type="file" name="avatar" accept="image/*" class="form-input" style="display: none;">
                                            <span class="upload-hint">JPG, PNG до 5MB</span>
                                        </label>
                                    </div>
                                    <div style="margin-top: 10px; text-align: center; color: var(--text-secondary);">или</div>
                                    <input type="url" name="avatar_url" class="form-input" style="margin-top: 10px;" placeholder="URL аватарки">
                                </div>
                                
                                <button type="submit" class="submit-button" style="padding: 12px;">
                                    <i class="fas fa-user-plus"></i> Добавить администратора
                                </button>
                            </form>
                        </div>
                        
                        <!-- Список админов -->
                        <div class="admins-list">
                            <?php if (empty($admins['approved_chat_admins'])): ?>
                                <div class="empty-message" style="padding: 20px; margin: 0;">
                                    <i class="fas fa-user-slash"></i>
                                    <p>Админы еще не добавлены</p>
                                </div>
                            <?php else: ?>
                                <?php foreach ($admins['approved_chat_admins'] as $admin): ?>
                                    <div class="admin-item" data-id="<?php echo $admin['id']; ?>">
                                        <div class="admin-avatar-small">
                                            <?php if (!empty($admin['avatar'])): ?>
                                                <img src="<?php echo $admin['avatar']; ?>" alt="<?php echo htmlspecialchars($admin['username']); ?>">
                                            <?php else: ?>
                                                <div class="avatar-placeholder">
                                                    <?php echo strtoupper(substr($admin['username'], 0, 1)); ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="admin-info-small">
                                            <div class="admin-username-small"><?php echo htmlspecialchars($admin['username']); ?></div>
                                            <div class="admin-status-small <?php echo getStatusClass($admin['status']); ?>">
                                                <?php echo getStatusText($admin['status']); ?>
                                            </div>
                                            <div class="admin-date">
                                                Добавлен: <?php echo date('d.m.Y', strtotime($admin['added_at'])); ?>
                                                <?php if (isset($admin['updated_at'])): ?>
                                                    <br>Обновлен: <?php echo date('d.m.Y', strtotime($admin['updated_at'])); ?>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="admin-actions">
                                            <button type="button" class="admin-action-btn edit-admin-btn" 
                                                    onclick="openEditAdminModal('<?php echo $admin['id']; ?>', 'approved_chat_admins', '<?php echo htmlspecialchars($admin['username'], ENT_QUOTES); ?>', '<?php echo $admin['avatar'] ?? ''; ?>', '<?php echo $admin['status'] ?? 'inactive'; ?>')">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button type="button" class="admin-action-btn delete-admin-btn" 
                                                    onclick="deleteAdmin('<?php echo $admin['id']; ?>', 'approved_chat_admins')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Категория 4: Пробные Администраторы -->
                    <div class="admins-category-card category-4">
                        <div class="category-card-header">
                            <div class="category-title">
                                <i class="fas fa-user-clock"></i>
                                Пробные Администраторы
                            </div>
                            <div class="category-count">
                                <?php echo count($admins['trial_admins']); ?>
                            </div>
                        </div>
                        
                        <!-- Форма добавления админа -->
                        <div class="add-admin-form">
                            <form method="POST" enctype="multipart/form-data" class="admin-form" data-category="trial_admins">
                                <input type="hidden" name="action" value="add_admin">
                                <input type="hidden" name="category" value="trial_admins">
                                
                                <div class="form-group">
                                    <label class="form-label">Имя пользователя (никнейм)</label>
                                    <input type="text" name="username" class="form-input" placeholder="@username или Имя" required>
                                </div>
                                
                                <div class="form-group">
                                    <label class="form-label">Статус</label>
                                    <select name="status" class="form-select" required>
                                        <option value="active">Активный</option>
                                        <option value="inactive" selected>Не активный</option>
                                        <option value="kick">На кик</option>
                                        <option value="weak">Слабый актив</option>
                                        <option value="medium">Средний актив</option>
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <label class="form-label">Аватарка (необязательно)</label>
                                    <div class="file-upload-container" style="padding: 15px;">
                                        <label class="file-upload-label" style="gap: 10px;">
                                            <i class="fas fa-user-circle"></i>
                                            <span>Загрузить аватарку</span>
                                            <input type="file" name="avatar" accept="image/*" class="form-input" style="display: none;">
                                            <span class="upload-hint">JPG, PNG до 5MB</span>
                                        </label>
                                    </div>
                                    <div style="margin-top: 10px; text-align: center; color: var(--text-secondary);">или</div>
                                    <input type="url" name="avatar_url" class="form-input" style="margin-top: 10px;" placeholder="URL аватарки">
                                </div>
                                
                                <button type="submit" class="submit-button" style="padding: 12px;">
                                    <i class="fas fa-user-plus"></i> Добавить администратора
                                </button>
                            </form>
                        </div>
                        
                        <!-- Список админов -->
                        <div class="admins-list">
                            <?php if (empty($admins['trial_admins'])): ?>
                                <div class="empty-message" style="padding: 20px; margin: 0;">
                                    <i class="fas fa-user-slash"></i>
                                    <p>Админы еще не добавлены</p>
                                </div>
                            <?php else: ?>
                                <?php foreach ($admins['trial_admins'] as $admin): ?>
                                    <div class="admin-item" data-id="<?php echo $admin['id']; ?>">
                                        <div class="admin-avatar-small">
                                            <?php if (!empty($admin['avatar'])): ?>
                                                <img src="<?php echo $admin['avatar']; ?>" alt="<?php echo htmlspecialchars($admin['username']); ?>">
                                            <?php else: ?>
                                                <div class="avatar-placeholder">
                                                    <?php echo strtoupper(substr($admin['username'], 0, 1)); ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="admin-info-small">
                                            <div class="admin-username-small"><?php echo htmlspecialchars($admin['username']); ?></div>
                                            <div class="admin-status-small <?php echo getStatusClass($admin['status']); ?>">
                                                <?php echo getStatusText($admin['status']); ?>
                                            </div>
                                            <div class="admin-date">
                                                Добавлен: <?php echo date('d.m.Y', strtotime($admin['added_at'])); ?>
                                                <?php if (isset($admin['updated_at'])): ?>
                                                    <br>Обновлен: <?php echo date('d.m.Y', strtotime($admin['updated_at'])); ?>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="admin-actions">
                                            <button type="button" class="admin-action-btn edit-admin-btn" 
                                                    onclick="openEditAdminModal('<?php echo $admin['id']; ?>', 'trial_admins', '<?php echo htmlspecialchars($admin['username'], ENT_QUOTES); ?>', '<?php echo $admin['avatar'] ?? ''; ?>', '<?php echo $admin['status'] ?? 'inactive'; ?>')">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button type="button" class="admin-action-btn delete-admin-btn" 
                                                    onclick="deleteAdmin('<?php echo $admin['id']; ?>', 'trial_admins')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Модальное окно для редактирования админа -->
    <div class="modal-overlay" id="edit-admin-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">
                    <i class="fas fa-user-edit"></i>
                    Редактировать администратора
                </h3>
                <button class="close-modal" onclick="closeEditAdminModal()">&times;</button>
            </div>
            
            <form method="POST" enctype="multipart/form-data" id="edit-admin-form">
                <input type="hidden" name="action" value="edit_admin">
                <input type="hidden" name="admin_id" id="edit-admin-id">
                <input type="hidden" name="category" id="edit-admin-category">
                
                <div class="current-avatar" id="current-avatar-container">
                    <!-- Аватар будет загружен динамически -->
                </div>
                
                <div class="form-group">
                    <label class="form-label">Имя пользователя (никнейм)</label>
                    <input type="text" name="username" id="edit-admin-username" class="form-input" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Статус</label>
                    <select name="status" id="edit-admin-status" class="form-select" required>
                        <option value="active">Активный</option>
                        <option value="inactive">Не активный</option>
                        <option value="kick">На кик</option>
                        <option value="weak">Слабый актив</option>
                        <option value="medium">Средний актив</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Новая аватарка (необязательно)</label>
                    <div class="file-upload-container" style="padding: 15px;">
                        <label class="file-upload-label" style="gap: 10px;">
                            <i class="fas fa-user-circle"></i>
                            <span>Загрузить новую аватарку</span>
                            <input type="file" name="avatar" accept="image/*" class="form-input" style="display: none;">
                            <span class="upload-hint">JPG, PNG до 5MB</span>
                        </label>
                    </div>
                    <div style="margin-top: 10px; text-align: center; color: var(--text-secondary);">или</div>
                    <input type="url" name="avatar_url" class="form-input" style="margin-top: 10px;" placeholder="URL новой аватарки">
                    <p style="font-size: 0.85rem; color: var(--text-secondary); margin-top: 5px;">
                        Оставьте поле пустым, чтобы сохранить текущую аватарку
                    </p>
                </div>
                
                <div class="form-buttons" style="display: flex; gap: 15px; margin-top: 25px;">
                    <button type="submit" class="submit-button" style="flex: 1;">
                        <i class="fas fa-save"></i> Сохранить изменения
                    </button>
                    <button type="button" class="cancel-button" onclick="closeEditAdminModal()" style="flex: 1;">
                        <i class="fas fa-times"></i> Отмена
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Модальное окно для редактирования блока контента -->
    <div class="modal-overlay" id="edit-block-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">
                    <i class="fas fa-edit"></i>
                    Редактировать блок
                </h3>
                <button class="close-modal" onclick="closeEditBlockModal()">&times;</button>
            </div>
            
            <form method="POST" enctype="multipart/form-data" id="edit-block-form">
                <input type="hidden" name="action" value="edit_block">
                <input type="hidden" name="block_id" id="edit-block-id">
                <input type="hidden" name="block_type" id="edit-block-type">
                
                <div id="edit-block-content">
                    <!-- Контент будет загружен динамически -->
                </div>
                
                <div class="form-buttons" style="display: flex; gap: 15px; margin-top: 25px;">
                    <button type="submit" class="submit-button" style="flex: 1;">
                        <i class="fas fa-save"></i> Сохранить изменения
                    </button>
                    <button type="button" class="cancel-button" onclick="closeEditBlockModal()" style="flex: 1;">
                        <i class="fas fa-times"></i> Отмена
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
    <script>
        // Инициализация редактора
        const textEditor = new Quill('#editor', {
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
        
        // Обработчик для редактора
        textEditor.on('text-change', function() {
            document.getElementById('text-content').value = textEditor.root.innerHTML;
        });
        
        // Управление основными вкладками
        const contentTabs = document.querySelectorAll('.content-tab');
        contentTabs.forEach(tab => {
            tab.addEventListener('click', function() {
                const tabName = this.dataset.tab;
                
                // Обновляем активную вкладку
                contentTabs.forEach(t => t.classList.remove('active'));
                this.classList.add('active');
                
                // Показываем соответствующий контент
                document.querySelectorAll('.tab-content').forEach(content => {
                    content.classList.remove('active');
                });
                document.getElementById(tabName + '-tab').classList.add('active');
            });
        });
        
        // Управление табами формы добавления блока
        const formTabs = document.querySelectorAll('.form-tab');
        const tabPanels = document.querySelectorAll('.tab-panel');
        
        formTabs.forEach(tab => {
            tab.addEventListener('click', function() {
                const tabName = this.dataset.tab;
                
                // Обновляем активный таб
                formTabs.forEach(t => t.classList.remove('active'));
                this.classList.add('active');
                
                // Обновляем скрытое поле типа
                document.getElementById('block-type').value = tabName;
                
                // Показываем соответствующий контент
                tabPanels.forEach(panel => {
                    panel.classList.remove('active');
                });
                document.getElementById(tabName + '-panel').classList.add('active');
                
                // Сбрасываем форму при переключении
                resetBlockForm();
            });
        });
        
        // Управление загрузкой файлов
        const uploadTypeRadios = document.querySelectorAll('input[name="upload_type"]');
        uploadTypeRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                const type = this.value;
                const activeTab = document.querySelector('.form-tab.active').dataset.tab;
                
                if (activeTab === 'image') {
                    document.getElementById('image-file-upload').style.display = 
                        type === 'file' ? 'block' : 'none';
                    document.getElementById('image-url-upload').style.display = 
                        type === 'url' ? 'block' : 'none';
                }
            });
        });
        
        // Drag & Drop для блоков контента
        let draggedItem = null;
        const blocksContainer = document.getElementById('blocks-container');
        
        if (blocksContainer) {
            const items = blocksContainer.querySelectorAll('.block-item');
            
            items.forEach(item => {
                // События для перетаскивания
                item.addEventListener('dragstart', function(e) {
                    draggedItem = this;
                    setTimeout(() => {
                        this.classList.add('dragging');
                    }, 0);
                });
                
                item.addEventListener('dragend', function() {
                    this.classList.remove('dragging');
                    draggedItem = null;
                });
                
                // Разрешаем перетаскивание
                item.setAttribute('draggable', 'true');
            });
            
            // Обработчики для контейнера
            blocksContainer.addEventListener('dragover', function(e) {
                e.preventDefault();
                const afterElement = getDragAfterElement(this, e.clientY);
                const draggable = document.querySelector('.dragging');
                
                if (afterElement == null) {
                    this.appendChild(draggable);
                } else {
                    this.insertBefore(draggable, afterElement);
                }
            });
        }
        
        function getDragAfterElement(container, y) {
            const draggableElements = [...container.querySelectorAll('.block-item:not(.dragging)')];
            
            return draggableElements.reduce((closest, child) => {
                const box = child.getBoundingClientRect();
                const offset = y - box.top - box.height / 2;
                
                if (offset < 0 && offset > closest.offset) {
                    return { offset: offset, element: child };
                } else {
                    return closest;
                }
            }, { offset: Number.NEGATIVE_INFINITY }).element;
        }
        
        // Сохранение порядка блоков
        function saveOrder() {
            const items = document.querySelectorAll('.block-item');
            const order = Array.from(items).map(item => item.dataset.id);
            
            document.getElementById('block-order').value = JSON.stringify(order);
            document.getElementById('order-form').submit();
        }
        
        // Удаление блока контента
        function deleteBlock(blockId) {
            if (confirm('Вы уверены, что хотите удалить этот блок?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.style.display = 'none';
                
                const actionInput = document.createElement('input');
                actionInput.type = 'hidden';
                actionInput.name = 'action';
                actionInput.value = 'delete_block';
                form.appendChild(actionInput);
                
                const blockIdInput = document.createElement('input');
                blockIdInput.type = 'hidden';
                blockIdInput.name = 'block_id';
                blockIdInput.value = blockId;
                form.appendChild(blockIdInput);
                
                document.body.appendChild(form);
                form.submit();
            }
        }
        
        // Удаление админа
        function deleteAdmin(adminId, category) {
            if (confirm('Вы уверены, что хотите удалить этого админа?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.style.display = 'none';
                
                const actionInput = document.createElement('input');
                actionInput.type = 'hidden';
                actionInput.name = 'action';
                actionInput.value = 'delete_admin';
                form.appendChild(actionInput);
                
                const adminIdInput = document.createElement('input');
                adminIdInput.type = 'hidden';
                adminIdInput.name = 'admin_id';
                adminIdInput.value = adminId;
                form.appendChild(adminIdInput);
                
                const categoryInput = document.createElement('input');
                categoryInput.type = 'hidden';
                categoryInput.name = 'category';
                categoryInput.value = category;
                form.appendChild(categoryInput);
                
                document.body.appendChild(form);
                form.submit();
            }
        }
        
        // Открытие модального окна для редактирования админа
        function openEditAdminModal(adminId, category, username, avatar, status) {
            document.getElementById('edit-admin-id').value = adminId;
            document.getElementById('edit-admin-category').value = category;
            document.getElementById('edit-admin-username').value = username;
            document.getElementById('edit-admin-status').value = status;
            
            const avatarContainer = document.getElementById('current-avatar-container');
            const firstLetter = username.charAt(0).toUpperCase();
            
            if (avatar) {
                avatarContainer.innerHTML = `
                    <img src="${avatar}" alt="${username}" style="width: 100px; height: 100px; border-radius: 50%; object-fit: cover; border: 3px solid var(--purple-color);">
                    <p style="margin-top: 10px; color: var(--text-secondary); font-size: 0.9rem;">Текущая аватарка</p>
                `;
            } else {
                avatarContainer.innerHTML = `
                    <div class="avatar-placeholder-large">${firstLetter}</div>
                    <p style="margin-top: 10px; color: var(--text-secondary); font-size: 0.9rem;">Нет аватарки</p>
                `;
            }
            
            document.getElementById('edit-admin-modal').classList.add('active');
        }
        
        // Закрытие модального окна редактирования админа
        function closeEditAdminModal() {
            document.getElementById('edit-admin-modal').classList.remove('active');
        }
        
        // Редактирование блока контента
        function editBlock(blockId) {
            // Загружаем данные блока через AJAX
            fetch('trials-data.json?v=' + Date.now())
                .then(response => response.json())
                .then(data => {
                    const block = data.find(b => b.id === blockId);
                    if (!block) return;
                    
                    document.getElementById('edit-block-id').value = blockId;
                    document.getElementById('edit-block-type').value = block.type;
                    
                    let formContent = '';
                    
                    if (block.type === 'text') {
                        formContent = `
                            <div class="form-group">
                                <label class="form-label">Текст</label>
                                <div class="editor-container">
                                    <div id="edit-text-editor"></div>
                                </div>
                                <textarea name="content" id="edit-text-content" style="display: none;" required>${block.content || ''}</textarea>
                            </div>
                        `;
                    } else if (block.type === 'image') {
                        formContent = `
                            <div class="form-group">
                                <label class="form-label">URL изображения (оставьте пустым, чтобы не менять)</label>
                                <input type="url" name="external_url" class="form-input" placeholder="https://example.com/new-image.jpg">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Подпись к изображению</label>
                                <input type="text" name="caption" class="form-input" value="${block.caption || ''}" placeholder="Описание изображения...">
                            </div>
                        `;
                    }
                    
                    document.getElementById('edit-block-content').innerHTML = formContent;
                    
                    // Инициализируем редактор, если он есть
                    if (block.type === 'text') {
                        setTimeout(() => {
                            const editEditor = new Quill('#edit-text-editor', {
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
                            
                            editEditor.root.innerHTML = block.content || '';
                            
                            editEditor.on('text-change', function() {
                                document.getElementById('edit-text-content').value = editEditor.root.innerHTML;
                            });
                        }, 100);
                    }
                    
                    document.getElementById('edit-block-modal').classList.add('active');
                })
                .catch(error => {
                    console.error('Ошибка загрузки данных блока:', error);
                    alert('Не удалось загрузить данные блока');
                });
        }
        
        // Закрытие модального окна редактирования блока
        function closeEditBlockModal() {
            document.getElementById('edit-block-modal').classList.remove('active');
        }
        
        // Сброс формы добавления блока
        function resetBlockForm() {
            textEditor.setContents([]);
            
            // Сбрасываем поля загрузки
            const fileInputs = document.querySelectorAll('#block-form input[type="file"]');
            fileInputs.forEach(input => {
                input.value = '';
            });
            
            const textInputs = document.querySelectorAll('#block-form input[type="text"], #block-form input[type="url"], #block-form textarea');
            textInputs.forEach(input => {
                if (!input.hasAttribute('required')) {
                    input.value = '';
                }
            });
            
            // Сбрасываем радиокнопки загрузки
            const fileRadios = document.querySelectorAll('#block-form input[name="upload_type"][value="file"]');
            fileRadios.forEach(radio => {
                radio.checked = true;
            });
            
            // Показываем загрузку файлов по умолчанию
            document.getElementById('image-file-upload').style.display = 'block';
            document.getElementById('image-url-upload').style.display = 'none';
        }
        
        // Обработка загрузки файлов
        const fileUploadLabels = document.querySelectorAll('.file-upload-label');
        fileUploadLabels.forEach(label => {
            const input = label.querySelector('input[type="file"]');
            
            label.addEventListener('click', function(e) {
                if (e.target !== input) {
                    input.click();
                }
            });
            
            input.addEventListener('change', function() {
                if (this.files.length > 0) {
                    const fileName = this.files[0].name;
                    const fileSize = (this.files[0].size / 1024 / 1024).toFixed(2);
                    
                    const span = label.querySelector('span:nth-child(2)');
                    if (span) {
                        span.textContent = fileName;
                    }
                    
                    const hint = label.querySelector('.upload-hint');
                    if (hint) {
                        hint.textContent = `${fileSize} MB`;
                    }
                }
            });
        });
        
        // Закрытие модальных окон при клике на overlay
        document.querySelectorAll('.modal-overlay').forEach(modal => {
            modal.addEventListener('click', function(e) {
                if (e.target === this) {
                    this.classList.remove('active');
                }
            });
        });
        
        // Обработка выхода
        if (window.location.search.includes('logout')) {
            fetch('admin-trials.php?logout', {method: 'GET'})
                .then(() => window.location.href = 'admin-trials.php');
        }
        
        // Предотвращение ухода без сохранения
        let formChanged = false;
        const blockForm = document.getElementById('block-form');
        const adminForms = document.querySelectorAll('.admin-form');
        const editAdminForm = document.getElementById('edit-admin-form');
        const editBlockForm = document.getElementById('edit-block-form');
        
        function setupFormChangeDetection(form) {
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
            }
        }
        
        setupFormChangeDetection(blockForm);
        setupFormChangeDetection(editAdminForm);
        setupFormChangeDetection(editBlockForm);
        adminForms.forEach(setupFormChangeDetection);
        
        window.addEventListener('beforeunload', (e) => {
            if (formChanged) {
                e.preventDefault();
                e.returnValue = 'У вас есть несохраненные изменения. Вы уверены, что хотите покинуть страницу?';
            }
        });
    </script>
</body>
</html>
