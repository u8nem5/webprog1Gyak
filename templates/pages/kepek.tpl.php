<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../includes/pdo.php';

function galleryUserLoggedIn()
{
    return !empty($_SESSION['login']);
}

$apiUrl = 'logicals/galleryapi.php';

$pdo = getPdo();

$stmt = $pdo->query('SELECT id, title, gimage FROM gallery ORDER BY id DESC');
$images = $stmt->fetchAll();

$isLoggedIn = galleryUserLoggedIn();
?>

<div class="gallery-header">
    <h2>Képgaléria</h2>
</div>

<?php if ($isLoggedIn): ?>
    <form class="gallery-upload w3-card w3-padding" method="post" action="<?= $apiUrl ?>" enctype="multipart/form-data">
        <h3>Új kép feltöltése</h3>

        <label>Képaláírás</label>
        <input class="w3-input w3-border" type="text" name="title">

        <label>Kép</label>
        <input class="w3-input w3-border" type="file" name="gimage" accept="image/*" required>

        <button class="w3-button w3-orange" type="submit">Feltöltés</button>
    </form>
<?php endif; ?>

<?php if (empty($images)): ?>
    <p>Még nincs kép a galériában.</p>
<?php endif; ?>

<div class="gallery-grid">
    <?php foreach ($images as $image): ?>
        <div class="gallery-item w3-card w3-padding">
            <img src="<?= htmlspecialchars($image['gimage']) ?>" alt="<?= htmlspecialchars($image['title']) ?>">

            <div class="gallery-title">
                <?= htmlspecialchars($image['title']) ?>
            </div>

            <?php if ($isLoggedIn): ?>
                <div class="gallery-actions">
                    <button class="w3-button w3-border" type="button" onclick="deleteImage(<?= (int)$image['id'] ?>)">
                        Törlés
                    </button>
                </div>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</div>

<script>
function deleteImage(id) {
    if (!confirm('Biztosan törlöd ezt a képet?')) {
        return;
    }

    fetch(<?= json_encode($apiUrl) ?> + '?id=' + id, {
        method: 'DELETE'
    })
    .then(function () {
        window.location.reload();
    });
}
</script>