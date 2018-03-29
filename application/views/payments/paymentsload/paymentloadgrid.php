<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="container">
	<div class="col-md-20">

		<?php if (isset($guardar) && $guardar == true){
		             echo form_open('payments/siguiente');
		       }else{
		           echo  form_open('payments/siguiente');
	           }
	     ?>

        
        
       		 <?php 
       		   //Paginacion
       		   /* if (isset($links)) {
        	           echo $links;
                    } */
            ?> 
           

<div class="tabs-content content">
	<div class="row">
			<div class="page-header">
				<h3>Datos N&oacute;mina</h3>
			</div>
				<div class="form-group">
					<label for="username">Usuario</label>
					<input type="text" class="form-control" id="username" name="username" placeholder="Usuario">
				</div>
				
				<div class="form-group">
					<label for="password">Clave</label>
					<input type="password" class="form-control" id="password" name="password" placeholder="Clave">
				</div>
				
	</div><!-- .row -->
</div><!-- .container -->



            
        	<div class="small-12 column text-right buttonPanel">
        		<?php if (isset($guardar) && $guardar == true){?>
	            	<input type="submit" id="btnEnviar" class="button small right" value="Guardar"  />
	            <?php }else{?>
	            	<input type="submit" id="btnEnviar" class="button small right" value="Siguiente"  />
	           	<?php }?>
            </div>   
            
            

            
            
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
        		
                <?php 
                    $cant= 0;
                    $inicio = 0;
                    if (isset($results) ) { ?>
                        <?php foreach ($results as $data) { 
                            $cant ++;
                            $inicio = $data->id;
                            ?>
                                <tr>
                                    <td>
                                    	<input id="beneficiario<?= $data->id ?>" 
                                    			name="beneficiario<?= $data->id ?>"  
                                    	        <?= $data->vbeneficiario==true ? 
                                    	           'type="text2"' : 
                                    	           'type="text3" title="El Campo Beneficiario no debe contener numeros ni caracteres especiales"'?> 
                                    	            value="<?= $data->beneficiario ?>" />
                                    </td>
                                    <td><input id="referencia_credito<?= $data->id ?>" name="referencia_credito<?= $data->id ?>"  type="text2" value="<?= $data->referencia_credito ?>" /></td>
        							
                                    <td>
                                       <select  id="id_cargo<?= $data->id ?>" name="id_cargo<?= $data->id ?>" 
                                            <?= $data->vcargo==true ? 
                                    	           'class = "fontsizetable"' : 
                                    	           'class = "fontsizetablecolorred" title="Debe Seleccionar un Cargo valido de la lista" '?> >
                                            
                                            
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
                                    	
                                    	<select  id="id_tipo_documento_identidad<?= $data->id ?>" name="id_tipo_documento_identidad<?= $data->id ?>" 
                                    	<?= $data->vid_tipo_documento_identidad==true ? 
                                    	           'class = "fontsizetable"' : 
                                    	           'class = "fontsizetablecolorred" title="Debe Seleccionar tipo de Documento de Identidad Valido de la lista" '?> >
                                    	
                                    	
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
                                    
                                    <td>
                                    
                                    	<?php 
                                    	if($data->vrdocumento_identidad > 1){
                                    	 ?>
                                    	 <input id="documento_identidad<?= $data->id ?>" 
                                        			name="documento_identidad<?= $data->id ?>"  
                                        	           type="text3" title="El Campo Nro: Rif / No puede estar repetido dentro de una misma nomina" 
                                        	            value="<?= $data->documento_identidad ?>" />	
                                    	 <?php 
                                    	}else 
                                    	?>
                                    	<?php if ($data->vrdocumento_identidad <= 1){?>
                                        	<input id="documento_identidad<?= $data->id ?>" 
                                        			name="documento_identidad<?= $data->id ?>"  
                                        			<?= $data->vdocumento_identidad==true ? 
                                        	           'type="text2"' : 
                                        	           'type="text3" title="El Campo Nro: Rif / CI debe ser numerico. Debe ser menos a 8 digitos. Es requerido"'?> 
                                        	            value="<?= $data->documento_identidad ?>" />
    									<?php }?>                                    	   
                                 	</td>
                                   
                                   
                                   <td>
                                              
                                        <select  id="id_tipo_cuenta<?= $data->id ?>" name="id_tipo_cuenta<?= $data->id ?>" 
                                        <?= $data->vid_tipo_cuenta==true ? 
                                    	           'class = "fontsizetable"' : 
                                    	           'class = "fontsizetablecolorred" title="Debe Seleccionar un tipo de cuenta valido de la lista" '?> >   
                                            
                                            
                                            
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
                                    
                                    <td>
                                    
    
                                        	<input id="numero_cuenta<?= $data->id ?>" 
                                        			name="numero_cuenta<?= $data->id ?>"  
                                        			<?= $data->vnumero_cuenta==true ? 
                                        	           'type="text2"' : 
                                        	           'type="text3" title="El Campo numero de cuenta debe ser numerico. debe contener 20 digitos. Es requerido"'?> 
                                        	            value="<?= $data->numero_cuenta ?>" />
                                        	            
    								</td>                                    	       
        
                                    
                                    
                                    
                                    <td>
                                    	<input id="credito<?= $data->id ?>" 
                                        			name="credito<?= $data->id ?>"  
                                        			<?= $data->vcredito==true ? 
                                        	           'type="text2"' : 
                                        	           'type="text3" title="El Campo monto debe ser numerico."'?> 
                                        	            value="<?= $data->credito ?>" />
                                    
                                    
                                    </td>
                                    
                                    
                                     <td>
                                    	
                                    	<select  id="id_tipo_pago<?= $data->id ?>" name="id_tipo_pago<?= $data->id ?>" 
                                    	<?= $data->vid_tipo_pago==true ? 
                                    	           'class = "fontsizetable"' : 
                                    	           'class = "fontsizetablecolorred" title="El tipo de Pago seleccionado no es valido. Debe Seleccionar un tipo de pago valido de la lista" '?> > 
                                    	
                                    	
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
                                    	
                                    	<select  id="id_banco<?= $data->id ?>" name="id_banco<?= $data->id ?>" 
                                    	<?= $data->vid_banco==true ? 
                                    	           'class = "fontsizetable"' : 
                                    	           'class = "fontsizetablecolorred" title="El Banco seleccionado no es valido. Debe Seleccionar un Banco valido de la lista" '?> > 
                                    	
                                    	
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
    
                                    	
                                    	<select  id="id_duracion_cheque<?= $data->id ?>" name="id_duracion_cheque<?= $data->id ?>" 
                                    	<?= $data->vid_duracion_cheque==true ? 
                                    	           'class = "fontsizetable"' : 
                                    	           'class = "fontsizetablecolorred" title="La Duración de cheque seleccionado no es valido. Debe Seleccionar una Duración de cheque valida de la lista" '?> > 
                                    	
                                            <?php if (isset($DuracionCheque)) { 
                                                 $seleccionado = false;   ?>
                                    			<?php foreach ($DuracionCheque->result() as $dataSelect) { 
                                    			    if ( $data-> id_duracion_cheque  == $dataSelect->duracion){
                                    			        $seleccionado = true;  
                                            			 ?>
                                            				<option  selected="selected" value="<?= $dataSelect->duracion ?>"><?= $dataSelect->duracion ?></option>
                                            			<?php 
                                            			    }else{
                                            			?>
                                            				<option value="<?= $dataSelect->duracion ?>"><?= $dataSelect->duracion ?></option>
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
                                    	<input id="correo_beneficiario<?= $data->id ?>" 
                                        			name="correo_beneficiario<?= $data->id ?>"  
                                        			<?= $data->vcorreo_beneficiario==true ? 
                                        	           'type="text2"' : 
                                        	           'type="text3" title="El Campo Email es invalido."'?> 
                                        	            value="<?= $data->correo_beneficiario ?>" />
                                    	
                                    	
                                    	</td>
                                    <td><input id="fecha<?= $data->id ?>" name="fecha<?= $data->id ?>" type="text" value="<?= $data->fecha ?>" /></td>
        							<td><a href="#" class="imgDeleteBtn"><img alt="Eliminar"  src="<?= base_url()?>Content/Images/Icons/delete_24.png"></a></td>
                                </tr>
                                
                                <input type="hidden" name="id<?=$data->id?>" value="<?=$data->id?>">
                               
    
                        <?php } ?>
    
    
    
                <?php }  ?>
    
    
        		</tbody>
        	</table>
        	<input type="hidden" name="cantreg" value="<?= $cant?>">
        	<input type="hidden" name="inicio" value="<?= $inicio?>">

    	
 		
       		 <?php /* if (isset($links)) {
        	           echo $links;
                    } */
            ?>
        	<div class="small-12 column text-right buttonPanel">
        		<?php if (isset($guardar) && $guardar == true){?>
	            	<input type="submit" id="btnEnviar" class="button small right" value="Guardar"  />
	            <?php }else{?>
	            	<input type="submit" id="btnEnviar" class="button small right" value="Siguiente"  />
	           	<?php }?>
            </div>   	
		<?= form_close()?>     
	</div>
</div>

