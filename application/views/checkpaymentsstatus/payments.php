<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*if (!isset($this->session->userdata['logged_in'])) {
    redirect(base_url()."index.php/user/login");
}*/
?>

<!DOCTYPE html>

<br><br>
<div class="row">
	<div class="dataTableActions text-left">
	<?php $fila=$consulta->result(); $datos= $fila[0]->nacionalidad .'-'. $fila[0]->documento_identidad .'. '. $fila[0]->nombre_apellido;?>
	<h5><?=  $datos; ?></h5>
    </div>
    <div class="dataTableActions text-right">
        <h3>Consulta de Estatus de Pago</h3>
    </div>
    <table id="dataTable">
            <thead>
                <tr>
                    <td>Proyecto</td>
                    <td>Gerencia</td>
                    <td>Rol</td>
                    <td>Banco</td>
                    <td>Nro. Cuenta</td>
                    <td>Fecha</td>
                    <td>Estatus</td>
                    <td>Acci&oacute;n</td>
                </tr>
                
                <?php foreach ($consulta->result() as $fila){ ?>
         
                    <tr>
                        <td><?= $fila->nombre_proyecto; ?></td>
                        <td><?= $fila->nombre_gerencia; ?></td>
                        <td><?= $fila->nombre_cargo; ?></td>
                        <td><?= $fila->nombre_banco; ?></td>
                        <td><?= $fila->numero_cuenta; ?></td>
                        <td><?= $fila->fecha; ?></td>
                       	<td><?= $fila->estatus;?></td>
                       	<td><a href=<?= base_url() . 'index.php/claim/addclaims';?> >reclamo</a></td>
                </tr>
         
                <?php }?>
            </thead>
        <tbody></tbody>
    </table>
    <label>Leyenda:</label>
    <?php foreach ($estatusNom->result() as $fila){ ?>
    	<label><?= $fila->nombre?>: <?= $fila->descripcion?></label>
    
    
    
    <?php }?>
    
    
</div>

