<?php
session_start();

require_once __DIR__ . '/../includes/pdo.php';

function isLoggedIn()
{
    return !empty($_SESSION['login']);
}

function requireLogin()
{
    if (!isLoggedIn()) {
        http_response_code(403);
        exit('Nincs jogosultság.');
    }
}


function uploadImage()
{
    $tmp = $_FILES['gimage']['tmp_name'];
    $info = getimagesize($tmp);

    if ($info === false) {
        return null;
    }

    $allowedTypes = [
        IMAGETYPE_JPEG => 'jpg',
        IMAGETYPE_PNG => 'png',
        IMAGETYPE_GIF => 'gif',
        IMAGETYPE_WEBP => 'webp'
    ];

    $imageType = $info[2];

    if (!isset($allowedTypes[$imageType])) {
        return null;
    }

    $uploadDir = __DIR__ . '/../images/gallery';

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0775, true);
    }

    $extension = $allowedTypes[$imageType];
    $filename = date('Ymd_His') . '_' . bin2hex(random_bytes(4)) . '.' . $extension;
    $target = $uploadDir . '/' . $filename;

    if (!move_uploaded_file($tmp, $target)) {
        return null;
    }

    return 'images/gallery/' . $filename;
}

$pdo = getPdo();
$method = $_SERVER['REQUEST_METHOD'];

/*
|--------------------------------------------------------------------------
| POST galleryapi.php
| Creates or updates a gallery image.
|--------------------------------------------------------------------------
*/
if ($method === 'POST') {
    requireLogin();

    $id = (int)($_POST['id'] ?? 0);
    $title = trim($_POST['title'] ?? '');


    if ($title === '') {
        $title = $_FILES['gimage']['name'] ?? 'Nincs cím';
    }

    $gimage = uploadImage();

    $stmt = $pdo->prepare('INSERT INTO gallery (title, gimage) VALUES (?, ?)');
    $stmt->execute([$title, $gimage]);


    header('Location: /kepek');
    exit;
}

/*
|--------------------------------------------------------------------------
| DELETE galleryapi.php?id=XXX
|--------------------------------------------------------------------------
*/
if ($method === 'DELETE') {
    requireLogin();
    $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

    if ($id <= 0) {
        http_response_code(400);
        exit('Hiányzó kép azonosító.');
    }

    $stmt = $pdo->prepare('DELETE FROM gallery WHERE id = ?');
    $stmt->execute([$id]);

    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['success' => true], JSON_UNESCAPED_UNICODE);
    exit;
}

http_response_code(405);
echo 'Nem támogatott HTTP metódus.';
