<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="container">
	<div class="col-md-20">
            <?php if (isset($links)) { ?>
                <?php echo $links ?>
            <?php } ?>
    	<table id="dataTable">
    		<thead>
    			<tr>
        			<td>Beneficiario</td>
                    <td>Referencia</td>
                    <td>Cargo</td>
                    <td>RIF/CI</td>
                    <td>Nro. RIF/CI</td>
                    <td>Tipo Cuenta</td>
                    <td>Nro. Cuenta</td>
                    <td>Monto</td>
                    <td>Tipo Pago</td>
                    <td>Banco</td>
                    <td>Duraci&oacute;n Cheque</td>
                    <td>Email</td>
                    <td>Fecha</td>
                    <td></td>
    			</tr>
    		</thead>
    		<tbody>
    		
            <?php if (isset($results) ) { ?>
                    <?php foreach ($results as $data) { ?>


                            <tr>
                                <td><input id="beneficiario<?= $data->id ?>" name="beneficiario"  <?= $data->vbeneficiario==true ? 'type="text2"' : 'type="text3" title="El Campo Beneficiario no debe contener numeros ni caracteres especiales"'?>value="<?= $data->beneficiario ?>" /></td>
                                <td><input id="referencia_credito<?= $data->id ?>" name="referencia_credito" type="text2" value="<?= $data->referencia_credito ?>" /></td>
    							
                                <td>
    
                                	<select  id="id_cargo<?= $data->id ?>" name="id_cargo<?= $data->id ?>" class = "fontsizetable">
                                        <?php if (isset($Cargo)) { 
                                             $seleccionado = false;   ?>
                                			<?php foreach ($Cargo->result() as $dataSelect) { 
                                			    if ( $data->id_cargo == $dataSelect->id){
                                			        $seleccionado = true;  
                                        			 ?>
                                        				<option  selected="selected" value="<?= $dataSelect->id ?>"><?= $dataSelect->nombre ?></option>
                                        			<?php 
                                        			    }else{
                                        			?>
                                        				<option value="<?= $dataSelect->id ?>"><?= $dataSelect->nombre ?></option>
                                					<?php 
                                        			    }
                                					?>
                                          	<?php } 
                                          	       if (!$seleccionado){
                                          	?>
                                          				<option  selected="selected" value="">Seleccione</option>
                                          	<?php 
                                          	       }else{
                                          	?>
                                          				<option value="" >Seleccione</option>
                                          	<?php 
                                          	       }
                                          	?>
                                          	
                       					<?php }  ?>
                                	</select>
    
                                </td>
                                
    							<td>
                                	<select  id="id_tipo_documento_identidad<?= $data->id ?>" name="id_tipo_documento_identidad<?= $data->id ?>" class = "fontsizetable">
                                        <?php if (isset($TipoDocumentoIdentidad)) { 
                                             $seleccionado = false;   ?>
                                			<?php foreach ($TipoDocumentoIdentidad->result() as $dataSelect) { 
                                			    if ( $data->id_tipo_documento_identidad == $dataSelect->nombre){
                                			        $seleccionado = true;  
                                        			 ?>
                                        				<option  selected="selected" value="<?= $dataSelect->nombre ?>"><?= $dataSelect->descripcion ?></option>
                                        			<?php 
                                        			    }else{
                                        			?>
                                        				<option value="<?= $dataSelect->nombre ?>"><?= $dataSelect->descripcion ?></option>
                                					<?php 
                                        			    }
                                					?>
                                          	<?php } 
                                          	       if (!$seleccionado){
                                          	?>
                                          				<option  selected="selected" value="">Seleccione</option>
                                          	<?php 
                                          	       }else{
                                          	?>
                                          				<option value="">Seleccione</option>
                                          	<?php 
                                          	       }
                                          	?>
                                          	
                       					<?php }  ?>
                                	</select>
                                </td>                            
                                
                                <td><input id="documento_identidad<?= $data->id ?>" name="documento_identidad" type="text2" value="<?= $data->documento_identidad ?>" /></td>
                               
                               <td>
                                	<select  id="id_tipo_cuenta<?= $data->id ?>" name="id_tipo_cuenta<?= $data->id ?>" class = "fontsizetable">
                                        <?php if (isset($tiposcuentas)) { 
                                             $seleccionado = false;   ?>
                                			<?php foreach ($tiposcuentas->result() as $dataSelect) { 
                                			    if ( $data->id_tipo_cuenta == $dataSelect->tipo){
                                			        $seleccionado = true;  
                                        			 ?>
                                        				<option  selected="selected" value="<?= $dataSelect->tipo ?>"><?= $dataSelect->descripcion ?></option>
                                        			<?php 
                                        			    }else{
                                        			?>
                                        				<option value="<?= $dataSelect->tipo ?>"><?= $dataSelect->descripcion ?></option>
                                					<?php 
                                        			    }
                                					?>
                                          	<?php } 
                                          	       if (!$seleccionado){
                                          	?>
                                          				<option  selected="selected" value="">Seleccione</option>
                                          	<?php 
                                          	       }else{
                                          	?>
                                          				<option value="">Seleccione</option>
                                          	<?php 
                                          	       }
                                          	?>
                                          	
                       					<?php }  ?>
                                	</select>
                                </td>             
                               
                               
                               
                                
                                <td><input id="numero_cuenta<?= $data->id ?>" name="numero_cuenta" <?= strlen($data->numero_cuenta)==20 ? 'type="text2"' : 'type="text3" title="Este campo debe ser numerico de 20 caracteres"'?> value="<?= $data->numero_cuenta ?>" /></td>
                                
                                <td><input id="credito<?= $data->id ?>" name="credito" type="text2" value="<?= $data->credito ?>" /></td>
                                
                                
                                 <td>
                                	<select  id="id_tipo_pago<?= $data->id ?>" name="id_tipo_pago<?= $data->id ?>" class = "fontsizetable">
                                        <?php if (isset($TipoPago)) { 
                                             $seleccionado = false;   ?>
                                			<?php foreach ($TipoPago->result() as $dataSelect) { 
                                			    if ( $data-> id_tipo_pago  == $dataSelect->id){
                                			        $seleccionado = true;  
                                        			 ?>
                                        				<option  selected="selected" value="<?= $dataSelect->id ?>"><?= $dataSelect->descripcion ?></option>
                                        			<?php 
                                        			    }else{
                                        			?>
                                        				<option value="<?= $dataSelect->id ?>"><?= $dataSelect->descripcion ?></option>
                                					<?php 
                                        			    }
                                					?>
                                          	<?php } 
                                          	       if (!$seleccionado){
                                          	?>
                                          				<option  selected="selected" value="">Seleccione</option>
                                          	<?php 
                                          	       }else{
                                          	?>
                                          				<option value="">Seleccione</option>
                                          	<?php 
                                          	       }
                                          	?>
                                          	
                       					<?php }  ?>
                                	</select>
                                </td>   
                                
    							<td>
                                	<select  id="id_banco<?= $data->id ?>" name="id_banco<?= $data->id ?>" class = "fontsizetable">
                                        <?php if (isset($bancos)) { 
                                             $seleccionado = false;   ?>
                                			<?php foreach ($bancos->result() as $dataSelect) { 
                                			    if ( $data->id_banco   == $dataSelect->id){
                                			        $seleccionado = true;  
                                        			 ?>
                                        				<option  selected="selected" value="<?= $dataSelect->id ?>"><?= $dataSelect->nombre ?></option>
                                        			<?php 
                                        			    }else{
                                        			?>
                                        				<option value="<?= $dataSelect->id ?>"><?= $dataSelect->nombre ?></option>
                                					<?php 
                                        			    }
                                					?>
                                          	<?php } 
                                          	       if (!$seleccionado){
                                          	?>
                                          				<option  selected="selected" value="">Seleccione</option>
                                          	<?php 
                                          	       }else{
                                          	?>
                                          				<option value="">Seleccione</option>
                                          	<?php 
                                          	       }
                                          	?>
                                          	
                       					<?php }  ?>
                                	</select>
                                </td>                               
    
    							<td>
                                	<select  id="id_duracion_cheque<?= $data->id ?>" name="id_duracion_cheque<?= $data->id ?>" class = "fontsizetable">
                                        <?php if (isset($DuracionCheque)) { 
                                             $seleccionado = false;   ?>
                                			<?php foreach ($DuracionCheque->result() as $dataSelect) { 
                                			    if ( $data-> id_duracion_cheque  == $dataSelect->duracion){
                                			        $seleccionado = true;  
                                        			 ?>
                                        				<option  selected="selected" value="<?= $dataSelect->id ?>"><?= $dataSelect->duracion ?></option>
                                        			<?php 
                                        			    }else{
                                        			?>
                                        				<option value="<?= $dataSelect->id ?>"><?= $dataSelect->duracion ?></option>
                                					<?php 
                                        			    }
                                					?>
                                          	<?php } 
                                          	       if (!$seleccionado){
                                          	?>
                                          				<option  selected="selected" value="">Seleccione</option>
                                          	<?php 
                                          	       }else{
                                          	?>
                                          				<option value="">Seleccione</option>
                                          	<?php 
                                          	       }
                                          	?>
                                          	
                       					<?php }  ?>
                                	</select>
                                </td>     
    
    
                                <td><input id="correo_beneficiario<?= $data->id ?>" name="correo_beneficiario" type="text2" value="<?= $data->correo_beneficiario ?>" class = "fontsizetable"/></td>
                                <td><input id="fecha<?= $data->id ?>" name="fecha" type="text" value="<?= $data->fecha ?>" /></td>
    							<td><a href="#" class="imgDeleteBtn"><img alt="Eliminar"  src="<?= base_url()?>Content/Images/Icons/delete_24.png"></a></td>
                            </tr>

                    <?php } ?>



            <?php }  ?>


    		</tbody>
    	</table>
    	
 
            <?php if (isset($links)) { ?>
                <?php echo $links ?>
            <?php } ?>
    	
	</div>
</div>

