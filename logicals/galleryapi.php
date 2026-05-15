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

function redirectToGallery()
{
    header('Location: /kepek');
    exit;
}

$pdo = getPdo();
$method = $_SERVER['REQUEST_METHOD'];
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
/*
|--------------------------------------------------------------------------
| GET galleryapi.php
| GET galleryapi.php?id=XXX <-- This one is unnecessary for the project, but it can be used to get a single image.
|--------------------------------------------------------------------------
*/
if ($method === 'GET') {
    header('Content-Type: application/json; charset=utf-8');

    if ($id > 0) {
        $stmt = $pdo->prepare('SELECT id, title, gimage FROM gallery WHERE id = ?');
        $stmt->execute([$id]);
        $image = $stmt->fetch();

        if (!$image) {
            http_response_code(404);
            echo json_encode(['error' => 'A kép nem található.'], JSON_UNESCAPED_UNICODE);
            exit;
        }

        echo json_encode($image, JSON_UNESCAPED_UNICODE);
        exit;
    }

    $stmt = $pdo->query('SELECT id, title,gimage FROM gallery ORDER BY id DESC');
    echo json_encode($stmt->fetchAll(), JSON_UNESCAPED_UNICODE);
    exit;
}

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
        $title = getimagesize($_FILES['gimage']['tmp_name'])[1];
    }

    $gimage = uploadImage();

    $stmt = $pdo->prepare('INSERT INTO gallery (title, gimage) VALUES (?, ?)');
    $stmt->execute([$title, $gimage]);


    redirectToGallery();
}

/*
|--------------------------------------------------------------------------
| DELETE galleryapi.php?id=XXX
|--------------------------------------------------------------------------
*/
if ($method === 'DELETE') {
    requireLogin();

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
