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

function cleanArticleHtml($html)
{
    // Only allowed html tags
    $allowed = '<p><br><h2><h3><strong><b><em><i><u><ul><ol><li><blockquote><a>';
    $html = strip_tags($html, $allowed);

    return trim($html);
}

function uploadCoverImage($oldPath = null)
{
    // If no new file was uploaded, keep the old image.
    if (!isset($_FILES['cover_image']) || $_FILES['cover_image']['error'] == UPLOAD_ERR_NO_FILE) {
        return $oldPath;
    }

    // If upload failed, keep the old image.
    if ($_FILES['cover_image']['error'] != UPLOAD_ERR_OK) {
        return $oldPath;
    }

    $tmp = $_FILES['cover_image']['tmp_name'];
    $info = getimagesize($tmp);

    if ($info === false) {
        return $oldPath;
    }

    $allowedTypes = [
        IMAGETYPE_JPEG => 'jpg',
        IMAGETYPE_PNG => 'png',
        IMAGETYPE_GIF => 'gif',
        IMAGETYPE_WEBP => 'webp'
    ];

    $imageType = $info[2];

    if (!isset($allowedTypes[$imageType])) {
        return $oldPath;
    }

    $uploadDir = __DIR__ . '/../images/blog';

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0775, true);
    }

    $extension = $allowedTypes[$imageType];
    $filename = date('Ymd_His') . '_' . bin2hex(random_bytes(4)) . '.' . $extension;
    $target = $uploadDir . '/' . $filename;

    if (!move_uploaded_file($tmp, $target)) {
        return $oldPath;
    }

    return 'images/blog/' . $filename;
}

$pdo = getPdo();
$method = $_SERVER['REQUEST_METHOD'];
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;



/*
|--------------------------------------------------------------------------
| GET api.php
| GET api.php?id=XXX
|--------------------------------------------------------------------------
*/
if ($method === 'GET') {
    header('Content-Type: application/json; charset=utf-8');

    if ($id > 0) {
        $stmt = $pdo->prepare('SELECT id, title, content, cover_image, created_at FROM blogposts WHERE id = ?');
        $stmt->execute([$id]);
        $post = $stmt->fetch();

        if (!$post) {
            http_response_code(404);
            echo json_encode(['error' => 'A blogpost nem található.'], JSON_UNESCAPED_UNICODE);
            exit;
        }

        echo json_encode($post, JSON_UNESCAPED_UNICODE);
        exit;
    }

    $stmt = $pdo->query('SELECT id, title, content, cover_image, created_at FROM blogposts ORDER BY created_at DESC, id DESC');
    echo json_encode($stmt->fetchAll(), JSON_UNESCAPED_UNICODE);
    exit;
}


/*
|--------------------------------------------------------------------------
| POST blogapi.php
| Creates or updates a blogpost.
|--------------------------------------------------------------------------
*/
if ($method === 'POST') {
    requireLogin();

    $id = (int)($_POST['id'] ?? 0);
    $title = trim($_POST['title'] ?? '');
    $content = cleanArticleHtml($_POST['content'] ?? '');

    if ($title === '') {
        exit('A cím kötelező.');
    }

    if ($id > 0) {
        $stmt = $pdo->prepare('SELECT cover_image FROM blogposts WHERE id = ?');
        $stmt->execute([$id]);
        $oldPost = $stmt->fetch();

        if (!$oldPost) {
            http_response_code(404);
            exit('A blogpost nem található.');
        }

        $coverImage = uploadCoverImage($oldPost['cover_image']);

        $stmt = $pdo->prepare('UPDATE blogposts SET title = ?, content = ?, cover_image = ? WHERE id = ?');
        $stmt->execute([$title, $content, $coverImage, $id]);
    } else {
        $coverImage = uploadCoverImage(null);

        $stmt = $pdo->prepare('INSERT INTO blogposts (title, content, cover_image, created_at) VALUES (?, ?, ?, NOW())');
        $stmt->execute([$title, $content, $coverImage]);
    }

    header('Location: /blog');
    exit;
}

/*
|--------------------------------------------------------------------------
| DELETE blogapi.php?id=XXX
|--------------------------------------------------------------------------
*/
if ($method === 'DELETE') {
    requireLogin();

    if ($id <= 0) {
        http_response_code(400);
        exit('Hiányzó blogpost azonosító.');
    }

    $stmt = $pdo->prepare('DELETE FROM blogposts WHERE id = ?');
    $stmt->execute([$id]);

    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['success' => true], JSON_UNESCAPED_UNICODE);
    exit;
}

http_response_code(405);
echo 'Nem támogatott HTTP metódus.';