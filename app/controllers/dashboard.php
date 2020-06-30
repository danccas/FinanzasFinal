<?php

Identify::direccionar_no_logueado();

Route::library('formity2');
Route::libraryOwn('bono');

$db = Doris::init('finanzas');


$form = Formity::getInstance('bono');
$form->setTitle('Formulario');
$ls = $db->get("SELECT * FROM empresa");
$ls = result_parse_to_options($ls, 'id', 'razon_social');
$form->addField('empresa_id:Empresa', 'select')->setOptions($ls);

$ls = $db->get("SELECT * FROM moneda");
$ls = result_parse_to_options($ls, 'id', 'nombre');
$form->addField('moneda_id:Moneda', 'select')->setOptions($ls);

$form->addField('fecha_emision', 'input:date');
$form->addField('valor_nominal', 'input:number')->setMin(100)->setMax(100000)->setStep(1);
$form->addField('valor_comercial', 'input:number')->setMin(100)->setMax(100000)->setStep(1);

$form->addField('numero_anhos', 'input:number')->setMin(1)->setMax(100)->setStep(1);

$ls = $db->get("SELECT * FROM tipo_tasa");
$ls = result_parse_to_options($ls, 'id', 'nombre');
$form->addField('tipo_tasa_id:Tipo tasa', 'select')->setOptions($ls);

$ls = $db->get("SELECT * FROM frecuencia_pago");
$ls = result_parse_to_options($ls, 'id', 'nombre');
$form->addField('frecuencia_pago_id:Frecuencia de Pago', 'select')->setOptions($ls);

$form->addField('dias_por_anho', 'select')->setOptions(array(360 => '360 días', 365 => '365 días'));

$ls = $db->get("SELECT * FROM capitalizacion");
$ls = result_parse_to_options($ls, 'id', 'nombre');
$form->addField('capitalizacion_id:Capitalización', 'select')->setOptions($ls);

$form->addField('tasa_interes', 'input:number')->setMin(0)->setMax(100)->setStep(0.05);
$form->addField('tasa_descuento', 'input:number')->setMin(0)->setMax(100)->setStep(0.05);
$form->addField('impuesto_renta', 'input:number')->setMin(0)->setMax(100)->setStep(0.05);
$form->addField('porcentaje_prima', 'input:number')->setMin(0)->setMax(100)->setStep(0.05);
$form->addField('porcentaje_estructuracion', 'input:number')->setMin(0)->setMax(100)->setStep(0.05);
$form->addField('porcentaje_colocacion', 'input:number')->setMin(0)->setMax(100)->setStep(0.05);
$form->addField('porcentaje_flotacion', 'input:number')->setMin(0)->setMax(100)->setStep(0.05);
$form->addField('porcentaje_cavali', 'input:number')->setMin(0)->setMax(100)->setStep(0.05);


Route::any('', function() use($db) {
  $table = Tablefy::getInstance('bonos');
  $table->setTitle('Relación de bonos');
  $table->setHeader('COD','MONED','EMPRESA','V.NOM','V.COMERC','AÑOS','TASA','FRECUENC.','CAPITALIZ.','T.INTERES','TASA DESCUENTO','IMPUESTO RENTA');
  $table->setData(function($e) use($db) {
    return $db->get("
      SELECT
        E.razon_social,
        B.*,
        TT.nombre as tipo_tasa,
        C.nombre as capitalizacion,
        C.cantidad_dias as capitalizacion_dias,
        FP.nombre as frecuencia_pago,
        FP.cantidad_dias as frecuencia_pago_dias,
        M.nombre as moneda
      FROM bono B
      JOIN moneda M ON M.id = B.moneda_id
      JOIN empresa E ON E.id = B.empresa_id
      JOIN tipo_tasa TT ON TT.id = B.tipo_tasa_id
      JOIN capitalizacion C ON C.id = B.capitalizacion_id
      JOIN frecuencia_pago FP ON FP.id = B.frecuencia_pago_id
      ORDER BY B.id ASC");
  }, function($n) {
    return array(
      $n['id'],
      $n['moneda'],
      $n['razon_social'],
      $n['valor_nominal'],
      $n['valor_comercial'],
      $n['numero_anhos'],
      $n['tipo_tasa'],
      $n['frecuencia_pago'],
      $n['capitalizacion'],
      number_format($n['tasa_interes'], 2) . '%',
      number_format($n['tasa_descuento'], 2) . '%',
      number_format($n['impuesto_renta'], 2) .'%',
    );
  });
  $table->setOption('Ingresar', function($n) use($db) {
    $detalle = obtener_pagos_de_bono($n);

    $table = Tablefy::getInstance('pagos');
    $table->setTitle('Relación de pagos');
    $table->setHeader('N°','FECHA DE PAGO','INFLACIÓN ANUAL','INFLACIÓN DEL PERIODO','PLAZO GRACIA','BONO','BONO INDEXADO','CUPÓN (Int.)','CUOTA','AMORT.','PRIMA','ESCUDO','FLUJO EMISOR','FLUJO EMISOR/ESCUDO','FLUJO BONISTA');
    $table->setData($detalle['pagos']);
    Route::nav('Editar&', function() use($db, $n) {
      $form = Formity::getInstance('bono');
      if($form->byRequest()) {
        if($form->isValid()) {
          $data = $form->getData();
          $form_id = $db->update('bono', $data, array('id' => $n['id']));
          Route::Refresh();
        }
      } else {
        $form->setPreData($n);
      }
      Route::render($form);
    });
    Route::theme('bono', array('bono' => $detalle));
  });
  $table->prepare();
  Route::setTitle('Bonos');
  Route::setDescription('Se muestra la relación de bonos registrados en la plataforma.');
  Route::nav('Nuevo&', function() use($db) {
    $form = Formity::getInstance('bono');
    if($form->byRequest()) {
      if($form->isValid()) {
        $data = $form->getData();
        $form_id = $db->insert('bono', $data);
        Route::Refresh();
      }
    }
    Route::render($form);
  });
  Route::render($table);
});
