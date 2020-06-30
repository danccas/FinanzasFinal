<?php
Route::config(function($app) {
  $app->attr('root', dirname(__FILE__) . '/');
  $app->attr('librarys', $app->attr('root') . 'app/librarys/');
  $app->attr('controllers', $app->attr('root') . 'app/controllers/');
  $app->attr('views', $app->attr('root') . 'app/views/');

  $app->library('Misc');
  $app->library('doris.pdo', 'Doris');
  $app->library('Pagination');
  $app->library('Tablefy');
  $app->library('Session');
  $app->libraryOwn('Identify');
 
  Doris::registerDSN('finanzas', 'mysql://root@localhost:3306/finanzas');
});
