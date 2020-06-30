<?php
Route::library('formity2');

Identify::direccionar_logueado();

#if(Identify::verificacion_logeo()) {
#  header("Location: " . RAIZ_WEB);
#  exit();
#}

$db = Doris::init('finanzas');

$form = Formity::getInstance('login');
$form->setUniqueId('login');
$form->buttons = ['Registrarme'];
$form->addField('nombres', 'input:text')->setIcon('user')->setMin(10)->setMax(100);
$form->addField('usuario', 'input:text')->setIcon('user')->setMin(5)->setMax(25)->setRegex('[\w]+');
$form->addField('clave', 'input:password')->setIcon('lock')->setMin(5)->setMax(25);

if($form->byRequest()) {
  if($form->isValid($error)) {
    $data = $form->getData();
    $existe = $db->get("SELECT * FROM usuario WHERE usuario = :usuario", true, array(
      'usuario' => $data['usuario'],
    ));
    if(empty($existe)) {
      $clave = $data['clave'];
      $data['clave'] = md5($clave);
      $db->insert('usuario', $data);
      $rp = Identify::login($db, $data['usuario'], $clave, $error);
      if(!empty($rp)) {
        Route::redirect('.');
      } else {
        Route::response(404);
      }
    } else {
      $form->setError('El usuario ya se encuentra registrado, intente con otro.');
    }
  }
}
Route::data('clear', true);
Route::view('registro');
