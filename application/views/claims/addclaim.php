<?php
defined('BASEPATH') OR exit('No direct script access allowed');?>
   <h2 class="show-for-small-only"></h2>    
   </br>
   <div class="container">
   		<div class="row">
    	<h3>Reclamo de Pago </h3>
    	
    <h4>Datos Personales</h4>
    		
    		<form method='post' action= "<?php base_url();?>addclaims" > 
      			<div class="form-group">
      					<div class="field small-3 columns">
     	   
            <label for="num:">Nacionalidad:</label>
                         	<select id="id_tipo_documento_identidad" name="id_tipo_documento_identidad">
        		<option selected="selected" value="">Seleccione</option>
                           <?php if (isset($tipodocumentoidentidad)) { ?>
                   	<?php foreach ($tipodocumentoidentidad->result() as $data) { ?>
                                       	<option value="<?= $data->nombre ?>"><?= $data->descripcion ?></option>
                             	<?php } ?>
         						 <?php }  ?>
           	</select>
             </div>
             

             <div class="field small-3 columns">
                        	<label for="Cedula:">Cedula:</label>
                        	<input id="documento_identidad" name="documento_identidad" type="text" value="" />
            </div>
              
    	    <div class="field small-3 columns">
            <label for="Nombre:">Nombre:</label>
            <input id="nombre" name="nombre" type="text" value="" />
        	</div>  

        	 
                    	<div class="field small-3 columns">
            <label for="Apellido:">Apellido:</label>
            <input id="apellido" name="apellido" type="text" value="" />
        	</div>   
        	
          		   		<div class="field small-4 columns">
                        	<label for="Telefono">Telefono:</label>
                        	<input id="telefono" name="telefono" type="text" value="" />
            </div>
                        <div class="field small-4 columns">
                        	<label for="Correo">Correo Electronico:</label>
                       		<input id="correo" name="correo" type="text" value="" />
           </div>
           
                     	<div class="field small-4 columns">
                        	<label for="Confirmacion">Confirmacion Correo Electronico:</label>
                       		<input id="confirmacion" name="confirmacion" type="text" value="" />
        	</div>  
        
<h4>Datos Bancarios</h4>
   
   
    			<div class="field small-6 columns">
            <label for="Enum:">Banco:</label>
                	<select id="id_banco" name="id_banco">

<option selected="selected" value="">Seleccione</option>
                           <?php if (isset($bancos)) { ?>
                   	<?php foreach ($bancos->result() as $data) { ?>
                   	<option value="<?= $data->id ?>"><?= $data->nombre ?></option>
                             	<?php } ?>
          <?php }  ?>
           	</select>
             </div>
           
 				<div class="field small-6 columns">
                    <label for="numerocuenta">Numero de Cuenta:</label>
                    <input id="numero_cuenta" name="numero_cuenta" type="text" value="" />
               </div>
 				
				<div class="field small-12 columns">	
				<?php echo form_open_multipart('claim/do_upload');?>
                           	<label for="Enum:">Imagen:</label>
   							<input type="file" name="userfile" size="20"/>
                   			<input type="submit" value="Cargar" name = "btnCargar" id="btnCargar" class="button small"  />
				<?= form_close()?>
			
<h4>Datos Evento</h4>
				<div class="field small-3 columns">
            <label for="num:">Proyecto:</label>
             			<select  id="id_proyecto" name="id_proyecto">
        		<option selected="selected" value="">Seleccione</option>
                           <?php if (isset($proyecto)) { ?>
                   	<?php foreach ($proyecto->result() as $data) { ?>
                   	<option value="<?= $data->id ?>"><?= $data->descripcion ?></option>
                             	<?php } ?>
          <?php }  ?>
           	</select>
                </div>
				<div class="field small-3 columns">
            <label for="num:">Gerencia:</label>
             			<select  id="id_gerencia" name="id_gerencia">
        		<option selected="selected" value="">Seleccione</option>
                           <?php if (isset($gerencia)) { ?>
                   	<?php foreach ($gerencia->result() as $data) { ?>
                   	<option value="<?= $data->id ?>"><?= $data->nombre ?></option>
                             	<?php } ?>
          <?php }  ?>
           	</select>
                 </div>
			     	<div class="field small-3 columns">
            <label for="num:">Cargo:</label>
             			<select  id="id_cargo" name="id_cargo">
        		<option selected="selected" value="">Seleccione</option>
                           <?php if (isset($cargo)) { ?>
                   	<?php foreach ($cargo->result() as $data) { ?>
                   	<option value="<?= $data->id ?>"><?= $data->nombre ?></option>
                             	<?php } ?>
          <?php }  ?>
           	</select>
                 </div>
                
                 <div class="field small-3 columns">
            <label for="Enum:">Tipo Error:</label>
            		<select  id="id_tipo_error" name="id_tipo_error">

<option selected="selected" value="">Seleccione</option>
						<?php if (isset($tipoerror)) { ?>
                   				<?php foreach ($tipoerror->result() as $data) { ?>
                   					<option value="<?= $data->id ?>"><?= $data->nombre_error ?></option>
                             	<?php } ?>
          					<?php }  ?>
           	</select>
                  </div>
            <div class="small-12 column text-right buttonPanel">
					<input type="submit" id="btnEnviar" name = "btnEnviar" class="button small right" value="Enviar Reclamo"  />
 				</div>
				
		</div>
		</form>
	</div>
</div>

	|

