<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>

<br><br>
<div class="row">
	
    <div class="dataTableActions text-left">
        <h3>Gestion de Reclamos</h3>
    </div>
    <table id="dataTable">
            <thead>
         
                <tr>
                   <td><h4>Cedula</h4></td> 
                    <td><h4>Nombre</h4></td>
                    <td><h4>Apellido</h4></td>
                    <td><h4>Proyecto</h4></td>
                    <td><h4>Tipo Reclamo</h4></td>
                    <td><h4>Fecha</h4></td>
                    <td><h4>Estatus</h4></td>
                    <td><h4>Ver Detalle</h4></td>
                </tr>
                   
                       <?php foreach ($query as $row){
                                 ?>
                       <tr>          
                            <td><?= $row->documento_identidad ?></td>
                            <td><?= $row->nombre?></td>
                            <td><?= $row->apellido?></td>
                            <td><?= $row->descripcion ?></td>
                            <td><?= $row->nombre_error ?></td>
                            <td><?= $row->fecha_reclamo ?></td>
                            <td><?= $row->nombre_reclamo ?></td>
                            <td><a href=<?= base_url() . 'index.php/claim/details/'.$row->id;?> >Detalle</a></td>
                   
               			</tr> <?php }?>
            </thead>
        <tbody></tbody>
    </table>
  </div>
