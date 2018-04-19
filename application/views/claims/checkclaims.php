<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<br>
<br>
<div class="container">
	<div class="col-md-20">
		<div class="row">
			<div class="page-header">
				<h3>Gestion de Reclamos</h3>
			</div>
			<br>
			<table id="dataTable">
				<thead>
					<tr>
						<td>Cedula</td>
						<td>Nombre</td>
						<td>Apellido</td>
						<td>Proyecto</td>
						<td>Tipo Reclamo</td>
						<td>Fecha</td>
						<td>Estatus</td>
						<td>Ver Detalle</td>
					</tr>
				</thead>
				<tbody>   
					<?php
                    foreach ($query as $row) {
                    ?>
					<tr>
						<td><?= $row->documento_identidad ?></td>
						<td><?= $row->nombre?></td>
						<td><?= $row->apellido?></td>
						<td><?= $row->descripcion ?></td>
						<td><?= $row->nombre_error ?></td>
						<td><?= $row->fecha_reclamo ?></td>
						<td><?= $row->nombre_reclamo ?></td>
						<td><a href=<?= base_url() . 'index.php/claim/details/'.$row->id;?>>Detalle</a></td>
					</tr> 
				<?php 
                    }
                ?>
				<tbody>
			</table>
		</div>
	</div>
</div>
