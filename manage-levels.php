<?php
session_start();

$password = 'mama123';
$jsonFile = 'load-levels.json';


if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}


if (isset($_POST['login'])) {
    if ($_POST['login'] === $password) {
        $_SESSION['admin'] = true;
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    } else {
        $loginError = 'Неверный пароль';
    }
}


if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
   
    ?>
    <!DOCTYPE html>
    <html>
    <head><title>Вход</title></head>
    <body>
        <form method="post">
            <label>Пароль: <input type="password" name="login"></label>
            <button type="submit">Войти</button>
            <?php if (isset($loginError)) echo '<p style="color:red">' . $loginError . '</p>'; ?>
        </form>
    </body>
    </html>
    <?php
    exit;
}


if (!file_exists($jsonFile)) {
    $defaultData = [
        'current' => 'yellow',
        'levels' => [
            'red' => [
                'title' => 'Красный уровень загруженности',
                'level_text' => 'Красный уровень',
                'description' => 'Ожидание публикации анкеты - больше одного дня.',
                'details' => 'В данный момент у нас очень высокий поток анкет. Из-за большого количества заявок обработка занимает больше времени. Пожалуйста, проявите терпение - ваша анкета будет обязательно обработана!',
                'indicator_position' => 16.6,
                'icon' => 'fa-exclamation',
                'current_level_icon' => 'fa-exclamation-triangle',
                'button_text' => 'Что такое уровни загруженности?',
                'button_link' => 'https://ishupodrygyilidryga.fun/status'
            ],
            'yellow' => [
                'title' => 'Жёлтый уровень загруженности',
                'level_text' => 'Жёлтый уровень',
                'description' => 'Ожидание публикации анкеты - больше одного дня / в течении дня.',
                'details' => 'Загруженность анкет средняя. Ваша анкета будет обработана в течение дня. Обращаем внимание, что время обработки может варьироваться в зависимости от времени суток.',
                'indicator_position' => 50,
                'icon' => 'fa-exclamation-circle',
                'current_level_icon' => 'fa-exclamation-circle',
                'button_text' => 'Что такое уровни загруженности?',
                'button_link' => 'https://ishupodrygyilidryga.fun/status'
            ],
            'green' => [
                'title' => 'Зелёный уровень загруженности',
                'level_text' => 'Зелёный уровень',
                'description' => 'Ожидание публикации анкеты - в день создания.',
                'details' => 'Низкая загруженность анкет. Ваша анкета будет обработана в день создания. Это оптимальное время для отправки анкеты!',
                'indicator_position' => 83.4,
                'icon' => 'fa-check-circle',
                'current_level_icon' => 'fa-check-circle',
                'button_text' => 'Что такое уровни загруженности?',
                'button_link' => 'https://ishupodrygyilidryga.fun/status'
            ]
        ]
    ];
    file_put_contents($jsonFile, json_encode($defaultData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    $data = $defaultData;
} else {
    $data = json_decode(file_get_contents($jsonFile), true);
  
    foreach ($data['levels'] as $level => &$levelData) {
        if (!isset($levelData['button_text'])) $levelData['button_text'] = 'Что такое уровни загруженности?';
        if (!isset($levelData['button_link'])) $levelData['button_link'] = 'https://ishupodrygyilidryga.fun/status';
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save'])) {
    $newData = [
        'current' => $_POST['current_level'],
        'levels' => []
    ];
    foreach (['red', 'yellow', 'green'] as $level) {
        $newData['levels'][$level] = [
            'title' => $_POST["title_$level"],
            'level_text' => $_POST["level_text_$level"],
            'description' => $_POST["description_$level"],
            'details' => $_POST["details_$level"],
            'indicator_position' => (float) $_POST["indicator_position_$level"],
            'icon' => $_POST["icon_$level"],
            'current_level_icon' => $_POST["current_level_icon_$level"],
            'button_text' => $_POST["button_text_$level"],
            'button_link' => $_POST["button_link_$level"]
        ];
    }
    file_put_contents($jsonFile, json_encode($newData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    $data = $newData;
    $message = 'Данные сохранены.';
}

$levels = ['red', 'yellow', 'green'];
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Управление уровнями загруженности</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: sans-serif; margin: 20px; background: #f5f5f5; }
        .container { max-width: 1200px; margin: 0 auto; background: white; padding: 20px; border-radius: 5px; }
        h1 { margin-top: 0; }
        .level-tabs { display: flex; gap: 10px; margin-bottom: 20px; border-bottom: 1px solid #ddd; padding-bottom: 10px; }
        .level-tab { padding: 10px 20px; background: #e0e0e0; border-radius: 5px; cursor: pointer; }
        .level-tab.active { background: #4CAF50; color: white; }
        .level-form { display: none; }
        .level-form.active { display: block; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input[type="text"], textarea, select { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; }
        textarea.large { min-height: 150px; }
        button { padding: 10px 20px; background: #4CAF50; color: white; border: none; border-radius: 4px; cursor: pointer; }
        .message { background: #d4edda; color: #155724; padding: 10px; border-radius: 4px; margin-bottom: 15px; }
        .logout { float: right; background: #f44336; color: white; padding: 5px 10px; text-decoration: none; border-radius: 4px; }
        .logout:hover { background: #d32f2f; }
    </style>
</head>
<body>
<div class="container">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <h1>Управление уровнями загруженности</h1>
        <a href="?logout=1" class="logout" onclick="return confirm('Выйти?');">Выйти</a>
    </div>
    <?php if (isset($message)) echo '<div class="message">' . htmlspecialchars($message) . '</div>'; ?>

    <form method="post">
        <div class="form-group">
            <label>Текущий уровень:</label>
            <select name="current_level">
                <?php foreach ($levels as $lvl): ?>
                    <option value="<?= $lvl ?>" <?= $data['current'] == $lvl ? 'selected' : '' ?>>
                        <?= ucfirst($lvl) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="level-tabs">
            <?php foreach ($levels as $index => $lvl): ?>
                <div class="level-tab <?= $index === 0 ? 'active' : '' ?>" data-level="<?= $lvl ?>">
                    <?= ucfirst($lvl) ?> уровень
                </div>
            <?php endforeach; ?>
        </div>

        <?php foreach ($levels as $index => $lvl): ?>
            <div class="level-form <?= $index === 0 ? 'active' : '' ?>" id="form-<?= $lvl ?>">
                <div class="form-group">
                    <label>Заголовок</label>
                    <input type="text" name="title_<?= $lvl ?>" value="<?= htmlspecialchars($data['levels'][$lvl]['title']) ?>" required>
                </div>
                <div class="form-group">
                    <label>Текст уровня (короткий)</label>
                    <input type="text" name="level_text_<?= $lvl ?>" value="<?= htmlspecialchars($data['levels'][$lvl]['level_text']) ?>" required>
                </div>
                <div class="form-group">
                    <label>Краткое описание (выделенное жирным)</label>
                    <input type="text" name="description_<?= $lvl ?>" value="<?= htmlspecialchars($data['levels'][$lvl]['description']) ?>" required>
                </div>
                <div class="form-group">
                    <label>Подробное описание</label>
                    <textarea name="details_<?= $lvl ?>" class="large" required><?= htmlspecialchars($data['levels'][$lvl]['details']) ?></textarea>
                </div>
                <div class="form-group">
                    <label>Позиция индикатора (%)</label>
                    <input type="number" step="0.1" name="indicator_position_<?= $lvl ?>" value="<?= $data['levels'][$lvl]['indicator_position'] ?>" required>
                </div>
                <div class="form-group">
                    <label>Иконка индикатора (класс Font Awesome)</label>
                    <input type="text" name="icon_<?= $lvl ?>" value="<?= htmlspecialchars($data['levels'][$lvl]['icon']) ?>" required>
                </div>
                <div class="form-group">
                    <label>Иконка текущего уровня (класс Font Awesome)</label>
                    <input type="text" name="current_level_icon_<?= $lvl ?>" value="<?= htmlspecialchars($data['levels'][$lvl]['current_level_icon']) ?>" required>
                </div>
                <div class="form-group">
                    <label>Текст кнопки</label>
                    <input type="text" name="button_text_<?= $lvl ?>" value="<?= htmlspecialchars($data['levels'][$lvl]['button_text']) ?>" required>
                </div>
                <div class="form-group">
                    <label>Ссылка кнопки</label>
                    <input type="text" name="button_link_<?= $lvl ?>" value="<?= htmlspecialchars($data['levels'][$lvl]['button_link']) ?>" required>
                </div>
            </div>
        <?php endforeach; ?>

        <button type="submit" name="save">Сохранить</button>
    </form>
</div>

<script>
    document.querySelectorAll('.level-tab').forEach(tab => {
        tab.addEventListener('click', () => {
            document.querySelectorAll('.level-tab').forEach(t => t.classList.remove('active'));
            document.querySelectorAll('.level-form').forEach(f => f.classList.remove('active'));
            tab.classList.add('active');
            document.getElementById('form-' + tab.dataset.level).classList.add('active');
        });
    });
</script>
</body>
</html>
