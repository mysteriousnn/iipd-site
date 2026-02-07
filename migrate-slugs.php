<?php
// migrate-slugs.php
session_start();
$password = 'ваш_секретный_пароль'; // ИЗМЕНИТЕ ЭТОТ ПАРОЛЬ!

if (!isset($_SESSION['auth']) || $_SESSION['auth'] !== true) {
    if (isset($_POST['password']) && $_POST['password'] === $password) {
        $_SESSION['auth'] = true;
    } else {
        echo '<form method="POST"><input type="password" name="password" placeholder="Пароль"><button>Войти</button></form>';
        exit;
    }
}

// Функция для генерации slug из текста
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

$dataFile = 'comments-data.json';

if (file_exists($dataFile)) {
    $comments = json_decode(file_get_contents($dataFile), true);
    if (!$comments) $comments = [];
} else {
    $comments = [];
}

$updated = 0;
foreach ($comments as &$comment) {
    if (!isset($comment['slug']) || empty($comment['slug'])) {
        $slug = generateSlug($comment['title']);
        $comment['slug'] = makeUniqueSlug($slug, $comments, $comment['id']);
        $updated++;
    }
}

file_put_contents($dataFile, json_encode($comments, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

echo "<h1>Миграция завершена</h1>";
echo "<p>Обновлено комментариев: $updated</p>";
echo "<h3>Список комментариев с slug:</h3>";
echo "<ul>";
foreach ($comments as $comment) {
    echo "<li>" . htmlspecialchars($comment['title']) . " - <strong>" . htmlspecialchars($comment['slug']) . "</strong></li>";
}
echo "</ul>";
echo '<a href="/">На главную</a>';
?>
