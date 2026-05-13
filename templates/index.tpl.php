<?php
session_start();

if (file_exists('./logicals/' . $keres['fajl'] . '.php')) {
	include("./logicals/{$keres['fajl']}.php");
}
?>
<!DOCTYPE html>
<html lang="hu">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>
		<?= $ablakcim['cim'] . ((isset($ablakcim['motto'])) ? (' | ' . $ablakcim['motto']) : '') ?>
	</title>

	<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
	<link rel="stylesheet" href="./styles/stilus.css" type="text/css">

	<?php if (file_exists('./styles/' . $keres['fajl'] . '.css')) { ?>
		<link rel="stylesheet" href="./styles/<?= $keres['fajl'] ?>.css" type="text/css">
	<?php } ?>
</head>

<body>

	<header class="w3-black w3-border-bottom w3-border-orange">
		<div class="w3-content w3-padding" style="max-width:1100px">

			<div class="w3-row">

				<div class="w3-col s12 m8 w3-left-align">
					<img
						src="./images/<?= $fejlec['kepforras'] ?>"
						alt="<?= $fejlec['kepalt'] ?>"
						class="logo">

					<div class="fejlec-szoveg">
						<h1 class="w3-xlarge" style="margin:0;">
							<?= $fejlec['cim'] ?>
						</h1>

						<?php if (isset($fejlec['motto']) && $fejlec['motto'] != '') { ?>
							<p class="w3-small" style="margin:4px 0 0 0;">
								<?= $fejlec['motto'] ?>
							</p>
						<?php } ?>
					</div>
				</div>

				<div class="w3-col s12 m4 w3-right-align w3-small user-info">
					<?php if (isset($_SESSION['login'])) { ?>
						Bejelentkezett:<br>
						<strong>
							<?= $_SESSION['csn'] . " " . $_SESSION['un'] . " (" . $_SESSION['login'] . ")" ?>
						</strong>
					<?php } ?>
				</div>

			</div>

		</div>
	</header>

	<div class="w3-content w3-margin-top" style="max-width:1100px">

		<nav class="w3-bar w3-white w3-border w3-round edc-menu">
			<?php foreach ($oldalak as $url => $oldal) { ?>

				<?php
				$nincsBelepveEsLathatja = ($oldal['menun'][0] == 1 && !isset($_SESSION['login']));
				$beVanLepveEsLathatja = ($oldal['menun'][1] == 1 && isset($_SESSION['login']));
				?>

				<?php if ($nincsBelepveEsLathatja || $beVanLepveEsLathatja) { ?>

					<a
						href="<?= ($url == '/') ? './' : $url ?>"
						class="w3-bar-item w3-button w3-mobile edc-menu-link <?= (($keres['fajl'] == $oldal['fajl']) ? 'w3-orange w3-text-white' : '') ?>"
					>
						<?= $oldal['szoveg'] ?>
					</a>

				<?php } ?>

			<?php } ?>
		</nav>

	</div>

	<main class="w3-content w3-margin-top" style="max-width:1100px">
		<section id="content" class="w3-card w3-white w3-padding-large w3-round">
			<?php include("./templates/pages/{$keres['fajl']}.tpl.php"); ?>
		</section>
	</main>

	<footer class="w3-center w3-padding-32 w3-text-grey">
		<?php if (isset($lablec['copyright'])) { ?>
			&copy;&nbsp;<?= $lablec['copyright'] ?>
		<?php } ?>

		<?php if (isset($lablec['ceg'])) { ?>
			&nbsp;<?= $lablec['ceg'] ?>
		<?php } ?>
	</footer>

</body>

</html>