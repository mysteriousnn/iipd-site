<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

$dataFile = 'comments-data.json';

// Чтение существующих комментариев
if (file_exists($dataFile)) {
    $comments = json_decode(file_get_contents($dataFile), true);
    if (!$comments) $comments = [];
} else {
    $comments = [];
}

// Функция для обрезки текста
function truncateText($text, $maxLength = 200) {
    // НЕ обрезаем текст, просто добавляем "читать далее..."
    return $text . ' <span class="read-more">читать далее...</span>';
}

// Определяем, нужно ли обрезать текст (для главной страницы)
$trimText = isset($_GET['trim']) && $_GET['trim'] === 'true';

// Добавление нового комментария (POST запрос)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $correctPassword = 'ваш_секретный_пароль'; // ИЗМЕНИТЕ ЭТОТ ПАРОЛЬ!
    
    if ($password !== $correctPassword) {
        echo json_encode(['error' => 'Неверный пароль']);
        exit;
    }
    
    // Если это обновление существующего комментария
    if (isset($_POST['action']) && $_POST['action'] === 'update') {
        $commentId = $_POST['id'];
        
        // Находим комментарий для обновления
        $found = false;
        foreach ($comments as &$comment) {
            if ($comment['id'] === $commentId) {
                // Обновляем данные
                $comment['date'] = htmlspecialchars($_POST['date'] ?? $comment['date']);
                $comment['title'] = htmlspecialchars($_POST['title'] ?? $comment['title']);
                $comment['text'] = htmlspecialchars($_POST['text'] ?? $comment['text']);
                $comment['link'] = htmlspecialchars($_POST['link'] ?? $comment['link']);
                $comment['timestamp'] = $_POST['timestamp'] ?? $comment['timestamp'];
                $found = true;
                break;
            }
        }
        
        if ($found) {
            // Сохраняем в файл
            file_put_contents($dataFile, json_encode($comments, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            echo json_encode(['success' => true, 'message' => 'Комментарий обновлен']);
        } else {
            echo json_encode(['error' => 'Комментарий не найден']);
        }
        exit;
    }
    
    // Иначе создаем новый комментарий
    $newComment = [
        'id' => uniqid(),
        'date' => isset($_POST['date']) ? htmlspecialchars($_POST['date']) : date('d.m.Y'),
        'title' => htmlspecialchars($_POST['title'] ?? ''),
        'text' => htmlspecialchars($_POST['text'] ?? ''),
        'link' => htmlspecialchars($_POST['link'] ?? '#'),
        'timestamp' => isset($_POST['timestamp']) ? intval($_POST['timestamp']) : time()
    ];
    
    // Добавляем новый комментарий в начало массива
    array_unshift($comments, $newComment);
    
    // Сохраняем в файл
    file_put_contents($dataFile, json_encode($comments, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    
    echo json_encode(['success' => true, 'comment' => $newComment]);
    exit;
}

// Получение всех комментариев (GET запрос)
if ($trimText) {
    // Для главной страницы - добавляем "читать далее..." к каждому комментарию
    $formattedComments = [];
    foreach ($comments as $comment) {
        $formattedComment = $comment;
        // Добавляем "читать далее..." к тексту
        $formattedComment['short_text'] = $comment['text'] . ' <span class="read-more">читать далее...</span>';
        $formattedComments[] = $formattedComment;
    }
    echo json_encode($formattedComments);
} else {
    // Для админки - полный текст без изменений
    echo json_encode($comments);
}
?>
