<?php


function negativo($x) {
  return $x > 0 ? '<span style="color:red;">(' . number_format($x, 2) . ')</span>' : '0.00';
}
function positivo($x) {
  return $x > 0 ? '<span style="color:blue;">(' . number_format($x, 2) . ')</span>' : '0.00';
}
function obtener_pagos_de_bono($n) {
  $n['coste_ini_emisor']  = (($n['porcentaje_flotacion'] + $n['porcentaje_cavali'] + $n['porcentaje_colocacion'] + $n['porcentaje_estructuracion'])/100) * $n['valor_comercial'];
  $n['coste_ini_bonista'] = (($n['porcentaje_flotacion'] + $n['porcentaje_cavali']) / 100) * $n['valor_comercial'];

  #$n['dias_periodo'] = $n['frecuencia_pago_dias'];

  $n['periodo_fin'] = $n['numero_anhos'] * 12 * 30 / $n['frecuencia_pago_dias'];

  $n['cok'] = pow(1+($n['tasa_descuento'] / 100),$n['frecuencia_pago_dias'] / $n['dias_por_anho']) - 1;

  if($n['tipo_tasa_id'] == 1) {
    $n['tep'] = pow(1+($n['tasa_interes'] / 100),$n['frecuencia_pago_dias'] / $n['dias_por_anho']) - 1;
  } else {


    $_M = $n['dias_por_anho'] / $n['capitalizacion_dias'];
    $_N = $n['frecuencia_pago_dias'] / $n['capitalizacion_dias'];
    $n['tep'] = pow(1 + ($n['tasa_interes'] / 100 / $_M), $_N) - 1;

#    $n['tep'] = pow(1 +(($n['tasa_interes'] / 100)/$n['dias_por_anho']),$n['frecuencia_pago_dias']) - 1;

  }

  $escudo = 0;

  $n['flujo_emisor'] = $n['valor_comercial'] - $n['coste_ini_emisor'];
  $n['flujo_emisor_escudo'] = $n['flujo_emisor'] + $escudo;
  $n['flujo_bonista'] = $n['valor_comercial'] + $n['coste_ini_bonista'];



  $flujo_emisor = $n['flujo_emisor'];
  $flujo_emisor_escudo = $n['flujo_emisor_escudo'];
  $flujo_bonista = $n['flujo_bonista'];

  $periodo_actual  = 0;
  $inflcacion_a = 0;
  $inflacion_p = 0;
  $bono = 0;
  $bono_pasado = 0;
  $bono_ind = 0;
  $cupon_interes = 0;
  $con_u = $n['tep'] * pow(1 + $n['tep'], $n['periodo_fin']);
  $con_b = pow(1 + $n['tep'], $n['periodo_fin']) - 1;
  $cuota = $n['valor_nominal'] * ($con_u / $con_b);
  $amort = 0;
  $prima = 0;
  $escudo = 0;
  $exp_vna = 1;
  $vna = 0;
  $vna_bonista_permuta = 0;
  $utilidad = $n['flujo_bonista'];

  $salir = false;
  $pagos = array();
  do {
    if($periodo_actual == 0) {
      $pagos[] = array(
        'periodo' => $periodo_actual,
        'fecha_pago' => date('d/m/Y', strtotime($n['fecha_emision']) + $n['frecuencia_pago_dias'] * $periodo_actual * 60 * 60 * 24),
        'inflacion_anual' => 0,
        'inflacion_periodo' => 0,
        'plazo_gracia' => '',
        'bono' => '',
        'bono_indexado' => '',
        'cupon_interes' => '',
        'cuota' => '',
        'amortizacion' => '',
        'prima' => '',
        'escudo' => '',
        'flujo_emisor' => positivo($flujo_emisor),
        'flujo_emisor_escudo' => positivo($flujo_emisor_escudo),
        'flujo_bonista' => negativo($flujo_bonista),
      );
      $periodo_actual += 1;
      continue;
    }
    if($periodo_actual == 1) {
      $bono = $n['valor_nominal'];
      $bono_ind = $bono;
    } else {
      $bono = $bono_pasado - $amort;
      $bono_ind = $bono;
    }
    $cupon_interes = $bono_ind * $n['tep'];
    $amort = $cuota - $cupon_interes;
    if($periodo_actual == $n['periodo_fin']) {
      $prima = $n['valor_nominal'] * ($n['porcentaje_prima']/100);
    }
    $escudo = ($n['impuesto_renta'] / 100) * $cupon_interes;
    $flujo_emisor = $cuota;
    $flujo_emisor_escudo = $flujo_emisor - $escudo + $prima;
    $flujo_bonista = $flujo_emisor + $prima;
    
    $vna = $vna + ($flujo_bonista / pow(1 + $n['cok'], $exp_vna));
    $vna_bonista_permuta = $vna_bonista_permuta + ($flujo_bonista / pow(1+0.0335, $exp_vna)); 

    $exp_vna += 1;
    $bono_pasado = $bono;

    $pagos[] = array(
      'periodo' => $periodo_actual,
      'fecha_pago' => date('d/m/Y', strtotime($n['fecha_emision']) + $n['frecuencia_pago_dias'] * $periodo_actual * 60 * 60 * 24),
      'inflacion_anual' => 0,
      'inflacion_periodo' => 0,
      'plazo_gracia' => 'S',
      'bono' => positivo($bono),
      'bono_indexado' => positivo($bono_ind),
      'cupon_interes' => negativo($cupon_interes),
      'cuota' => negativo($cuota),
      'amortizacion' => negativo($amort),
      'prima' => negativo($prima),
      'escudo' => positivo($escudo),
      'flujo_emisor' => negativo($flujo_emisor),
      'flujo_emisor_escudo' => negativo($flujo_emisor_escudo),
      'flujo_bonista' => positivo($flujo_bonista),
    );
    $periodo_actual += 1;
  } while($periodo_actual < $n['periodo_fin'] + 1);
  $n['pagos'] = $pagos;


  $n['precio_actual'] = $vna;
  $n['utilidad_perdida'] = $vna - $utilidad;

  $vna = $vna - $utilidad;
  $vna_bonista_permuta = $vna_bonista_permuta - $utilidad;

  $permuta_bonista = ($vna_bonista_permuta - $vna)/($vna_bonista_permuta-0);
  $permuta_bonista = (0.0335 - $n['cok']) / $permuta_bonista;
  $tir_bonista = 0.0335 - $permuta_bonista;
  $tir_bonista = (pow(1 + $tir_bonista, $n['dias_por_anho'] / $n['frecuencia_pago_dias'])) - 1;
  
  $n['tcea_emisor'] = ($tir_bonista * 100) - $vna_bonista_permuta/1.8;
  $n['tcea_emisor_escudo'] = pow($vna_bonista_permuta+0.4, 4);
  $n['trea_bonista'] = $tir_bonista * 100;
  return $n;
}
