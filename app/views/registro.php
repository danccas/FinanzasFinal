<!DOCTYPE html>
<html lang="es-PE">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width,minimum-scale=1,initial-scale=1,user-scalable=yes"/>
  <title>Registrate Fácil</title>
  <meta property="og:image" content="/img/logo_social.png">
  <link href="/css/bulma.css" rel="stylesheet" type="text/css">
  <link href="/css/style.css" rel="stylesheet" type="text/css">
</head>
<body>
<div class="content-vertical-center">
<div class="container">
  <div class="login-home">
    <h1 class="has-text-white">#YoMeRegistro</h1>
    <div class="login-box">
      <img class="box-buo" src="/img/buo_negro.png" />
      <div class="form-registro naranja">
      <?= Formity::getInstance('login')->render(); ?>
      </div>
    </div>
  </div>
</div>
</div>
<script src="/js/jquery.min.js"></script>
</body>
</html>
