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
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="Lundakarnevalens interna bildbank.">

  <title>Bildbanken - Lundakarnevalen</title>

  <!-- Bootstrap core CSS -->
  <link href="/bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="/bower_components/lightbox/css/lightbox.css" rel="stylesheet" />

  <!-- Custom styles for this template -->
  <link href="/css/style.css" rel="stylesheet">

  <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<body>
<?php
  if (isset($_GET['dir'])) {
    $dir = basename($_GET['dir']);
  }

  if (isset($dir) && is_dir(__DIR__ . "/photo/{$dir}")) {
    $photos = glob(__DIR__ . "/photo/{$dir}/*", GLOB_MARK);
    $photos = preg_grep('/\.jpg|jpeg|png|gif$/i', $photos);
    $photos = array_map('basename', $photos);
    $album = true;
    $directory = $dir;
  } else {
    $events = glob(__DIR__ . '/photo/*', GLOB_ONLYDIR);
    $photos = array();

    foreach ($events as $e) {
      $pho = glob($e . '/*', GLOB_MARK);
      $pho = preg_grep('/\.jpg|jpeg|png|gif$/i', $pho);
      $pho = array_map('basename', $pho);

      $dir = basename($e);
      $photos[$dir] = $pho;
    }

    $album = false;
  }
?>

<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Visa navigering</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="/">Lundakarnevalens Bildbank</a>
        </div>
        <div class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <?php if ($album) : ?>
              <li>
                <a href="/">Hem</a>
              </li>
              <li class="active">
                <a href="/?dir=<?= urlencode($directory); ?>">
                  <?= htmlspecialchars($directory); ?>
                </a>
              </li>
            <?php else : ?>
              <li class="active"><a href="/">Hem</a></li>
            <?php endif; ?>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </div>

  <div class="container">
    <div class="row">
      <?php $i = 0; ?>
      <?php foreach ($photos as $dir => $ps) : ?>
      <div class="col-md-4 album-preview">
        <?php if ($album) : ?>
          <h2 class="text-center">
            <?= htmlspecialchars($ps); ?>
          </h2>
          <a href="/photo/<?= htmlspecialchars($directory); ?>/<?= htmlspecialchars($ps); ?>"
            title="<?= htmlspecialchars($ps); ?>"
            data-lightbox="<?= htmlspecialchars($directory); ?>"
            data-title="<?= htmlspecialchars($ps); ?>">
            <img src="<?= htmlspecialchars(getThumb($directory, $ps)); ?>"
              alt="<?= htmlspecialchars($ps); ?>">
          </a>
        <?php else : ?>
          <h2 class="text-center">
            <?= htmlspecialchars($dir); ?>
          </h2>
          <a href="?dir=<?= urlencode($dir); ?>" title="Öppna album">

          <?php if (isset($ps[0])) : ?>
            <img src="<?= htmlspecialchars(getThumb($dir, $ps[0])); ?>"
              alt="<?= htmlspecialchars($dir); ?>"
              class="thumb">
          <?php else : ?>
            Ingen bild
          <?php endif; ?>

          </a>
        <?php endif; ?>

        <?php if (++$i % 3 == 0) : ?>
          </div>
          <div class="row">
        <?php endif; ?>
      </div>
      <?php endforeach; ?>
    </div>

  </div><!-- /.container -->

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
  <script src="/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
  <script src="/bower_components/lightbox/js/lightbox.min.js"></script>
</body>
</html>
<?php

function getThumb($dir, $img)
{
  $thumbDir = __DIR__ . '/thumb/' . $dir . '/';

  if (!file_exists($thumbDir . $img)) {
    $imgDir = __DIR__ . '/photo/' . $dir . '/';
    if (!createThumb($imgDir, $thumbDir, 600, $img)) {
      return '/photo/' . $dir . '/' . $img;
    }
  }

  return '/thumb/' . $dir . '/' . $img;
}

function createThumb($pathToImages, $pathToThumbs, $thumbWidth, $thumbName)
{
  // parse path for the extension
  $info = pathinfo($pathToImages . $thumbName);
  // continue only if this is a JPEG image
  $ext = strtolower($info['extension']);
  if ('jpg' == $ext || 'jpeg' == $ext) {
    // load image and get image size
    $img = imagecreatefromjpeg( "{$pathToImages}{$thumbName}" );
    $width = imagesx( $img );
    $height = imagesy( $img );

    // calculate thumbnail size
    $new_width = $thumbWidth;
    $new_height = floor( $height * ( $thumbWidth / $width ) );

    // create a new temporary image
    $tmp_img = imagecreatetruecolor( $new_width, $new_height );

    // copy and resize old image into new image
    imagecopyresized( $tmp_img, $img, 0, 0, 0, 0, $new_width, $new_height, $width, $height );

    // save thumbnail into a file
    if (!is_dir($pathToThumbs)) {
      mkdir($pathToThumbs, 0755, true);
    }
    imagejpeg( $tmp_img, "{$pathToThumbs}{$thumbName}" );
    return true;
  }
  return false;
}
