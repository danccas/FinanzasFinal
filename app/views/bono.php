<style>
.table-data th, .table-data td {
  vertical-align: middle;
}
.table-data td > .material-icons {
  font-size: 20px;
}
.cronograma h2 {
  font-size: 20px;
}
.cronograma th {
  font-size:11px;
}
</style>
<div class="container">
<h1 class="has-text-white"><?= $bono['razon_social'] ?> #<?= $bono['id'] ?></h1>
<div class="columns is-multiline">
  <div class="column is-3">
    <div class="card" style="min-height: 170px">
      <div class="card-header">
        <p class="card-header-title">Valor</p>
      </div>
      <div class="card-content">
        <table class="table is-fullwidth">
        <tr>
          <th>Nomimal</th>
          <td><?= $bono['valor_nominal'] ?></td>
        </tr>
        <tr>
          <th>Comercial</th>
          <td><?= $bono['valor_comercial'] ?></td>
        </tr>
        <tr>
          <th>Fecha de Emisión</th>
          <td><?= fecha($bono['fecha_emision']) ?></td>
        </tr>
      </table>
      </div>
    </div>
  </div>
  <div class="column is-5">
    <div class="card" style="min-height: 170px">
      <div class="card-content">
        <table class="table is-fullwidth">
        <tr>
          <th>Número de Años</th>
          <td><?= $bono['numero_anhos'] ?></td>
        </tr>
        <tr>
          <th>Tipo de Tasa</th>
          <td><?= $bono['tipo_tasa'] ?></td>
        </tr>
        <tr>
          <th>Frecuencia de Pago</th>
          <td><?= $bono['frecuencia_pago'] ?></td>
        </tr>
        <tr>
          <th>Días por año</th>
          <td><?= $bono['dias_por_anho'] ?></td>
        </tr>
        <tr>
          <th>Capitalización</th>
          <td><?= $bono['capitalizacion'] ?></td>
        </tr>
        <tr>
          <th>Tasa de Interes</th>
          <td><?= $bono['tasa_interes'] ?>%</td>
        </tr>
        <tr>
          <th>Tasa de Descuentos</th>
          <td><?= $bono['tasa_descuento'] ?>%</td>
        </tr>
        <tr>
          <th>Impuesto a la renta</th>
          <td><?= $bono['impuesto_renta'] ?>%</td>
        </tr>
      </table>
      </div>
    </div>
  </div>
  <div class="column is-4">
    <div class="card" style="min-height: 170px">
      <div class="card-content">
        <table class="table is-fullwidth">
        <tr>
          <th>Porcentaje Prima</th>
          <td><?= $bono['porcentaje_prima'] ?>%</td>
        </tr>
        <tr>
          <th>Porcentaje Estructuración</th>
          <td><?= $bono['porcentaje_estructuracion'] ?>%</td>
        </tr>
        <tr>
          <th>Porcentaje Colocación</th>
          <td><?= $bono['porcentaje_colocacion'] ?>%</td>
        </tr>
        <tr>
          <th>Porcentaje Flotación</th>
          <td><?= $bono['porcentaje_flotacion'] ?>%</td>
        </tr>
        <tr>
          <th>Porcentaje CAVALI</th>
          <td><?= $bono['porcentaje_cavali'] ?>%</td>
        </tr>
      </table>
      </div>
    </div>
  </div>
  <div class="column is-12">
    <div class="card">
      <div class="card-content cronograma">
        <?= Tablefy::getInstance('pagos')->render(array('class' => 'table is-small')); ?>

        <div style="max-width: 400px;margin: 0 auto;margin-top: 30px;">
        <table class="table is-fullwidth">
        <tr>
          <th>COK del Periodo</th>
          <td><?= number_format($bono['cok'] * 100, 4) ?>%</td>
        </tr>
        <tr>
          <th>TE del Periodo</th>
          <td><?= number_format($bono['tep'] * 100, 4) ?>%</td>
        </tr>
        <tr>
          <th>Precio Actual</th>
          <td><?= $bono['precio_actual'] ?></td>
        </tr>
        <tr>
          <th>Utilidad/Perdida</th>
          <td><?= $bono['utilidad_perdida'] ?></td>
        </tr>
        <tr>
          <th>TCEA Emisor</th>
          <td><?= $bono['tcea_emisor'] ?></td>
        </tr>
        <tr>
          <th>TCEA Emisor c/ Escudo</th>
          <td><?= $bono['tcea_emisor_escudo'] ?></td>
        </tr>
        <tr>
          <th>TREA Bonista</th>
          <td><?= $bono['trea_bonista'] ?></td>
        </tr>
      </table>
        </div>
      </div>
    </div>
  </div> 
</div>
</div>
