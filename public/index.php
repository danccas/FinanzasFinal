<?php
require_once(__DIR__ . '/../core/route.php');

Route::import(__DIR__ . '/../conf.php');

Route::init()->debug(true);

Route::g()->libs->session->init();

Route::any('demo', function() {
  debug($_SESSION);
});
Route::any('identificacion', function() {
  Route::controller('identificacion');
});
Route::any('registro', function() {
  Route::controller('registro');
});
Route::any('', function() {
  Route::controller('dashboard');
});
Route::else(function() {
  Route::response(404);
});
