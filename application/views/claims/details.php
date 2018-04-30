<?php defined('BASEPATH') or exit('No direct script access allowed');
if (!isset($this->session->userdata['logged_in'])) {
    redirect(base_url()."index.php/user/login");
}
?>
<br>
<div class="container">
	<div class="col-md-20">
		<div class="row">
			<div class="page-header">
				<h3>Detalle del Reclamo</h3>
			</div>
			<table id="dataTable">
				<thead>
					<tr>
						<td>
							<label for="num:">id:</label>
						</td>
						<td>
							<label for="num:">Nacionalidad:</label>
						</td>
    					<td>
    						<label for="Cedula:">Cedula:</label>
    	               	</td>
    
    					<td>
    						<label for="Nombre:">Nombre:</label>
            			</td>
    
    					<td>
    						<label for="Apellido:">Apellido:</label>
    		            </td>

						<td>
							<label for="Telefono">Telefono:</label>
			            </td>

						<td>
							<label for="Correo">Correo Electronico:</label>
			            </td>
            		</tr>
				<thead>
				<tbody> 
					<tr>
						<td>
    						<?= $query[0]->id ?>
    					</td>
    					<td>
    						<?= $query[0]->nacionalidad ?>
    					</td>
    					<td>
    						<?= $query[0]->cedula ?>
    					</td>
    					<td>
    						<?= $query[0]->npersona ?>
    					</td>
    					<td>
    						<?= $query[0]->apellido ?>
    					</td>
    					<td>
    						<?= $query[0]->telefono ?>
    					</td>
    					<td>
    						<?= $query[0]->correo ?>
    					</td>
					</tr>
					
				</tbody>
			</table>
			
			
			<table id="dataTable">
				<thead>
					<tr>
						<td>
							<label for="num:">Banco:</label>
						</td>
    					<td>
    						<label for="Cedula:">Numero de Cuenta:</label>
    	               	</td>
    
    					<td>
    						<label for="Nombre:">Tipo de Cuenta:</label>
            			</td>
    
    					<td>
    						<label for="Apellido:">Imagen Soporte:</label>
    		            </td>
            		</tr>
				<thead>
				<tbody> 
					<tr>
    					<td>
    						<?= $query[0]->banco ?>
    					</td>
    					<td>
    						<?= $query[0]->numero_cuenta ?>
    					</td>
    					<td>
    						<?= $query[0]->descripcion ?>
    					</td>
    					<td>
    					<?php if ($query[0]->soportereclamos !== null){?>
    						<a href="<?php echo base_url();?>index.php/claim/download/<?= $query[0]->soportereclamos;?>">Descargar</a>
    					<?php }?>
    					</td>
					</tr>
					
				</tbody>
			</table>
			

			<table id="dataTable">
				<thead>
					<tr>
						<td>
							<label for="num:">Proyecto:</label>
						</td>
    					<td>
    						<label for="Cedula:">Gerencia:</label>
    	               	</td>
    
    					<td>
    						<label for="Nombre:">Cargo:</label>
            			</td>
    
    					<td>
    						<label for="Apellido:">Tipo Error:</label>
    		            </td>

						<td>
							<label for="Telefono">Dias Trabajados:</label>
			            </td>
            		</tr>
				<thead>
				<tbody> 
					<tr>
    					<td>
    						<?= $query[0]->proyecto ?>
    					</td>
    					<td>
    						<?= $query[0]->gerencia ?>
    					</td>
    					<td>
    						<?= $query[0]->cargo ?>
    					</td>
    					<td>
    						<?= $query[0]->nombre_error ?>
    					</td>
    					<td>
    						<?= $query[0]->cantidad_dias ?>
    					</td>
					</tr>
					
				</tbody>
			</table>
			<table id="dataTable">
				<thead>
					<tr>
						<td>
							<label for="comentario">Nota Explicativa:</label>
						</td>
    				
    
            		</tr>
				<thead>
				<tbody> 
					<tr>
    					<td>
    						<?= $query[0]->comentario ?>
    					</td>
					</tr>
				</tbody>
			</table>
			
			<?= form_open("Claim/updateClaim")?>
				<input type="hidden" name = "idreclamo" id = "idreclamo" value="<?= $query[0]->id?>"/>
    			<table id="dataTable">
    				<thead>
    					<tr>
    						<td>Estatus:</td>
    						<td>Comentario</td>
    					</tr>
    				</thead>
    				
    				<tbody>
    					<tr>
    					<td>
    						<select id="estatus_reclamo" name="estatus_reclamo">
            					<?php
                                if (isset($estatusReclamo)) {
                                    foreach ($estatusReclamo->result() as $data) {
                                        if ($query[0]->id_reclamo == $data->id){
                                ?>
            								<option selected="selected" value="<?= $data->id ?>"><?= $data->nombre_reclamo ?></option>
            					<?php
                                        }else{
                                ?>
                                			<option value="<?= $data->id ?>"><?= $data->nombre_reclamo ?></option>
                                <?php 
                                            
                                        }
                                    }
                                }
                                ?>
    						</select>	
    					</td>
    					<td>
    						<textarea rows="5" cols="50" name="comentario_gerencia" id="comentario_gerencia" maxlength = "255"><?= $query[0]->comentario_gerencia?></textarea>
    					</td>
    					</tr>
    				</tbody>
    			</table>
    			<div class="small-12 column text-right buttonPanel">
    				<input type="submit" id="btnEnviar" class="button small right" value="Actualizar" />
    			</div>
			<?= form_close()?>
		</div>
	</div>
</div>
			