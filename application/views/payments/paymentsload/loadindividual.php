<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
</br>
<div class="container">
	<div class="col-md-20">
		<?= form_open()?>
    		<div class="row">
    				<div class="col-md-12">
    					<div class="page-header">
	        				<h3>Datos N&oacute;mina</h3>
        				</div>
    					<div class="field small-4 column">
                           	<label for="String:">Descripci&oacute;n N&oacute;mina:</label>
                           	<input id="descripcion" name="descripcion" type="text" value="<?= isset($descripcionnomina)?$descripcionnomina:"";?>" />
                        </div>	
                        <div class="field small-4 column">
                        	<label for="Enum:">Proyecto:</label>
                            <select id="id_proyecto" name="id_proyecto" >
                                    <?php if (isset($proyecto)) { 
                                            $selected = false;
                                        ?>
                            				<?php foreach ($proyecto->result() as $data) { 
                            			             if ($id_proyecto == $data->id){
                            			                 $selected = true;
                            			    ?>
                            						<option selected="selected" value="<?= $data->id ?>"><?= $data->nombre ?></option>
                            							
                            				<?php 
                            			             }else{
                            				?>
                            						<option value="<?= $data->id ?>"><?= $data->nombre ?></option>
                            						
                            				<?php 
                            			             } 
                            				?>
                                      	<?php } ?>
                                      	
                                      	<?php 
                                      	             if ($selected){
                            			?>
                            							<option value="">Seleccione</option>
                            							
                            				<?php 
                            			             }else{
                            				?>
                            							<option selected="selected" value="">Seleccione</option>
                            				<?php 
                            			             }
                                      	 ?>
                                      	
                   					<?php }  ?>
                    			</select>
                        	</div>
                            <div class="field small-4 column">
                                <label for="Enum:">Gerencia:</label>
                                <select id="id_gerencia" name="id_gerencia" >
                                    <?php if (isset($gerencia)) { 
                                            $selected = false;
                                        ?>
                            				<?php foreach ($gerencia->result() as $data) { 
                            				        if ($id_gerencia == $data->id){
                            			                 $selected = true;
                            			    ?>
                            						<option selected="selected" value="<?= $data->id ?>"><?= $data->nombre ?></option>
                            							
                            				<?php 
                            			             }else{
                            				?>
                            						<option value="<?= $data->id ?>"><?= $data->nombre ?></option>
                            						
                            				<?php 
                            			             } 
                            				?>
                                      	<?php } ?>
                                      	
                                      	
                                      	<?php 
                                      	             if ($selected){
                            			?>
                            							<option value="">Seleccione</option>
                            							
                            				<?php 
                            			             }else{
                            				?>
                            							<option selected="selected" value="">Seleccione</option>
                            				<?php 
                            			             }
                                      	 ?>
                   					<?php }  ?>
                    			</select>
                            </div>

    				</div>
				</div>

    		<div class="row">
    			<div class="col-md-12">
    				<div class="page-header">
						<h3>Datos Beneficiario</h3>
    				</div>
    				
	          		<div class="field small-3 column">
	                    <label for="String:">Beneficiario:</label>
    	                <input id="beneficiario" name="beneficiario" type="text" value="<?= isset($beneficiario)?$beneficiario:"";?>" />
                    </div>
                    
                    
                    <div class="field small-3 column">
    					<label for="String:">Cargo:</label>
    					<select  id="id_cargo" name="id_cargo" >
                        	<?php if (isset($Cargo)) { 
                                        $selected = false;   
                                        foreach ($Cargo->result() as $dataSelect) { 
                                            if ( $id_cargo == $dataSelect->id){
                                    		  $selected = true;   ?>
                                            	<option  selected="selected" value="<?= $dataSelect->id ?>"><?= $dataSelect->nombre ?></option>
                                      	<?php }else{?>
                                            	<option value="<?= $dataSelect->id ?>"><?= $dataSelect->nombre ?></option>
                                    	<?php } 
                                        } 
                                            if (!$selected){?>
                                           		<option  selected="selected" value="">Seleccione</option>
                                     <?php  }else{ ?>
                                            	<option value="" >Seleccione</option>
                                     <?php  }?>
                                              	
                           	<?php }  ?>
						</select>
                    </div>
                    
                    <div class="field small-3 column">
                    	<label for="Enum:">Letra RIF/CI:</label>
                        <select id="tipoDocumentoIdentidad" name="tipoDocumentoIdentidad" >
                        	<?php if (isset($TipoDocumentoIdentidad)) { 
                                        $selected = false;   
                                        foreach ($TipoDocumentoIdentidad->result() as $dataSelect) { 
                                            if ( $tipoDocumentoIdentidad == $dataSelect->nombre){
                                    		  $selected = true;   ?>
                                            	<option  selected="selected" value="<?= $dataSelect->nombre ?>"><?= $dataSelect->descripcion ?></option>
                                      	<?php }else{?>
                                            	<option value="<?= $dataSelect->nombre ?>"><?= $dataSelect->descripcion ?></option>
                                    	<?php } 
                                        } 
                                            if (!$selected){?>
                                           		<option  selected="selected" value="">Seleccione</option>
                                     <?php  }else{ ?>
                                            	<option value="" >Seleccione</option>
                                     <?php  }?>
                                              	
                           	<?php }  ?>
                		</select>
                 	</div>
                  	<div class="field small-3 column">
                            <label for="Number:">Nro. RIF/CI:</label>
                            <input id="rifci" name="rifci" type="text" value="<?= isset($rifci)?$rifci:"";?>" maxlength = "9"/>
                   	</div>
            
                   	<div class="field small-3 column">
                    	<label for="Enum:">Tipo de Cuenta:</label>
                       	<select id="tipocuenta" name="tipocuenta">
                               <?php if (isset($tiposcuentas)) { 
                                        $selected = false;   
                                        foreach ($tiposcuentas->result() as $dataSelect) { 
                                            if ( $tipocuenta == $dataSelect->tipo){
                                    		  $selected = true;   ?>
                                            	<option  selected="selected" value="<?= $dataSelect->tipo ?>"><?= $dataSelect->descripcion ?></option>
                                      	<?php }else{?>
                                            	<option value="<?= $dataSelect->tipo ?>"><?= $dataSelect->descripcion ?></option>
                                    	<?php } 
                                        } 
                                            if (!$selected){?>
                                           		<option  selected="selected" value="">Seleccione</option>
                                     <?php  }else{ ?>
                                            	<option value="" >Seleccione</option>
                                     <?php  }?>
                                              	
                           	<?php }  ?> 
                		</select>
                	</div>
                        
                  	<div class="field small-3 column">
                    	<label for="Enum:">Banco:</label>
                        <select id="banco" name="banco">
                            <?php if (isset($bancos)) { 
                                        $selected = false;   
                                        foreach ($bancos->result() as $dataSelect) { 
                                            if ( $banco == $dataSelect->id){
                                    		  $selected = true;   ?>
                                            	<option  selected="selected" value="<?= $dataSelect->id ?>"><?= $dataSelect->nombre ?></option>
                                      	<?php }else{?>
                                            	<option value="<?= $dataSelect->id ?>"><?= $dataSelect->nombre ?></option>
                                    	<?php } 
                                        } 
                                            if (!$selected){?>
                                           		<option  selected="selected" value="">Seleccione</option>
                                     <?php  }else{ ?>
                                            	<option value="" >Seleccione</option>
                                     <?php  }?>
                                              	
                           	<?php }  ?> 
                		</select>
					</div>
                        
                  	<div class="field small-3 column">
                            <label for="Number:">Nro. Cuenta:</label>
                            <input id="cuenta" name="cuenta" type="text" value="<?= isset($cuenta)?$cuenta:"";?>" maxlength = "20"/>
                  	</div>
                  	<div class="field small-3 column">
                            <label for="Number:">Monto:</label>
                            <input id="monto" name="monto" type="text" value="<?= isset($monto)?$monto:"";?>" />
                    </div>
    
					<div class="small-12 column text-right buttonPanel">
	                     <input type="submit" id="btnEnviar" class="button small right" value="Agregar" onclick = "this.form.action = 'individualload'"/>
                    </div>
    			</div>
    		</div>
    		
    		<?php if(isset($_SESSION['addrecords'])){ ?>
    			<br>
        			<table id="dataTable">
                    	<thead>
                    		<tr>
                    			<td>Nro: Registro</td>
                        		<td>Beneficiario</td>
                                <td>Cargo</td>
                                <td>RIF/CI</td>
                                <td>Nro. RIF/CI</td>
                                <td>Tipo Cuenta</td>
                                <td>Nro. Cuenta</td>
                                <td>Monto</td>
                                <td>Banco</td>
                    		</tr>
                    	</thead>
                    	<tbody>
                    	<?php 
                    	$id = 0;
                    	foreach ($_SESSION['addrecords'] as $datarecords){ 
                    	    $id ++;
                    	?>
                    		
                    		<tr>
                    			<td><?= $id?></td>
                    			<td><?= $datarecords["beneficiario"]?></td>
                    			<td>
                    			<?php 
                    			if (isset($Cargo)) { 
                                    foreach ($Cargo->result() as $dataSelect) { 
                    			         if ( $datarecords["id_cargo"] == $dataSelect->id){
                    			             echo $dataSelect->nombre;   
                    			         }
                                    } 
                    			}  ?>
                    			</td>
                    			<td>
                    			<?php 
                    			if (isset($TipoDocumentoIdentidad)) {
                    			    foreach ($TipoDocumentoIdentidad->result() as $dataSelect) {
                    			        if ( $datarecords["id_tipo_documento_identidad"] == $dataSelect->nombre){
                    			            echo $dataSelect->descripcion;
                    			        }
                    			    }
                    			}  ?>
                    			</td>

                    			<td><?= $datarecords["documento_identidad"]?></td>
                    			<td>
                    			<?php 
                    			if (isset($tiposcuentas)) {
                    			    foreach ($tiposcuentas->result() as $dataSelect) {
                    			        if ( $datarecords["id_tipo_cuenta"] == $dataSelect->tipo){
                    			            echo $dataSelect->descripcion;
                    			        }
                    			    }
                    			}  ?>
                    			</td>
                    			<td><?= $datarecords["numero_cuenta"]?></td>
                    			<td><?= $datarecords["credito"]?></td>
                    			<td><?php 
                    			if (isset($bancos)) {
                    			    foreach ($bancos->result() as $dataSelect) {
                    			        if ( $datarecords["id_banco"] == $dataSelect->id){
                    			            echo $dataSelect->nombre;
                    			        }
                    			    }
                    			} ?></td>
                    		</tr>

                    	
                    	    
                    	<?php 
                    	} ?>
                    	</tbody>
                    </table>
                    <div class="small-12 column text-right buttonPanel">
	                     <input type="submit" id="btnEnviar" class="button small right" value="Guardar y Liberar" onclick = "this.form.action = 'guardarIndividualload'"/>
                    </div>
                	
    		
    		<?php }?>
    		
		<?= form_close()?>	
	</div>
</div>