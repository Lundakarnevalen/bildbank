<?php
$locale = 'sv_SE.UTF-8';

if ($locale !== setlocale(LC_CTYPE & LC_COLLATE, $locale)) {
    header("Content-Type: text/plain; charset=UTF-8"); ?>

    You have to install the locale sv_SE.UTF-8 on your system.

    First check if its supported on your system by looking at
    cat /usr/share/i18n/SUPPORTED | grep sv_SE

    If it's supported you can install it by running
    sudo locale-gen sv_SE
    sudo locale-gen sv_SE.UTF-8
    sudo update-locale

    If it's not supported you're out of luck.
    <?php
    exit;
}
?>
<!DOCTYPE html>
<html lang="sv">
<head>
	<meta charset="utf-8" />
	<title>Bildbanken - Lundakarnevalen</title>
</head>
<body>
<?php
    $events = glob(__DIR__ . '/photo/*', GLOB_ONLYDIR);
    $photos = array();

    foreach ($events as $e) {
        $pho = glob($e . '/*', GLOB_MARK);
        $pho = preg_grep('/\.jpg|jpeg|png|gif$/i', $pho);
        $pho = array_map('basename', $pho);

        $dir = basename($e);
        $photos[$dir] = $pho;
    }
?>
<?php foreach ($photos as $dir => $ps) : ?>
    <?= htmlspecialchars($dir); ?>
    <br />
    <?php foreach ($ps as $p) : ?>
        <?= htmlspecialchars($p); ?>
        <br />
    <?php endforeach; ?>
    <br />
<?php endforeach; ?>
</body>
</html>
