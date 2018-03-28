<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
   <h2 class="show-for-small-only"></h2>    
	<br><br>

   
    	<h3>Reclamo de Pago </h3>
    	<br>
    	
    <h4>Datos Personales</h4>
    		
    <?=form_open("controllers/addclaim") ?> 
     	   <div class="field small-2 columns">
     	   
     	     <div class="row"> 
            <label for="num:">Nacionalidad:</label>
             <select data-val="true" data-val-required="The Nacionalidad field is required." id="Nacionalidad" name="Nacionalidad">
        		<option selected="selected" value="">Seleccione</option>
                           <?php if (isset($tipodocumentoidentidad)) { ?>
                   	<?php foreach ($tipodocumentoidentidad->result() as $data) { ?>
                   	<option value="<?= $data->id ?>"><?= $data->descripcion ?></option>
                             	<?php } ?>
         						 <?php }  ?>
           	</select>
             </div>
             
             </div>
             <div class="row1"> 
             <div class="field small-3 columns">
            <label for="Tlf:">Cedula:</label>
            <input data-val="true" data-val-number="The field Cedula must be a number." data-val-required="The Cedula: field is required." id="cedula:" name="cedula:" type="number" value="" />
            </div>
             </div>
              
             <div class="row">  
    	    <div class="field small-3 columns">
            <label for="Nombre:">Nombre:</label>
            <input id="Nombre" name="Nombre" type="text" value="" />
        	</div>  
        	</div>
        	 
        	<div class="row-1"> 
        	 <div class="field small-2 columns">
            <label for="Apellido:">Apellido:</label>
            <input id="Apellido" name="Apellido" type="text" value="" />
        	</div>   
        	 </div>
        	
            <div class="ro"> 
            <div class="field small-3 columns">
            <label for="Tlf:">Telefono:</label>
            <input data-val="true" data-val-number="The field Telefono: must be a number." data-val-required="The Tlf: field is required." id="Tlf:" name="Tlf:" type="number" value="" />
            </div>
           </div>
           
            <div class="row"> 
            <div class="field small-3 columns">
            <label for="Correo Electronico">Correo Electronico:</label>
            <input id="Correo Electronico" name="Correo Electronico" type="text" value="" />
        	</div>  
            </div>
        
<h4>Datos Bancarios</h4>
   
   
			<div class="field small-3 columns">
            <label for="Enum:">Banco:</label>
            <select data-val="true" data-val-required="The Banco field is required." id="Banco" name="Banco">

<option selected="selected" value="">Seleccione</option>
                           <?php if (isset($bancos)) { ?>
                   	<?php foreach ($bancos->result() as $data) { ?>
                   	<option value="<?= $data->id ?>"><?= $data->nombre ?></option>
                             	<?php } ?>
          <?php }  ?>
           	</select>
             </div>
            <div class="row1">        
			<div class="field small-2 columns">
            <label for="Enum:">Codigo Banco:</label>
            <select data-val="true" data-val-required="The Codigo Banco field is required." id="Codigo Banco" name="Codigo Banco">
<option selected="selected" value="">Seleccione</option>
                           <?php if (isset($bancos)) { ?>
                   	<?php foreach ($bancos->result() as $data) { ?>
                   	<option value="<?= $data->id ?>"><?= $data->codigo ?></option>
                             	<?php } ?>
          <?php }  ?>
           	</select>
               </div>
 				</div>
 				
 				<div class="field small-3 columns">
            <label for="numero de cuenta">Numero de Cuenta:</label>
            <input data-val="true" data-val-number="The field Numero de Cuenta must be a number." data-val-required="The Numero de Cuenta: field is required." id="Numero de Cuenta:" name="Numero de Cuenta:" type="number" value="" />
            </div>
			<?php 
			echo form_open_multipart('file_model/insertimagen');
			echo form_upload('');
			?>  
<h4>Datos Evento</h4>
				<div class="row1"> 
				<div class="field small-3 columns">
            <label for="num:">Proyecto:</label>
             <select data-val="true" data-val-required="The Proyecto field is required." id="Proyecto" name="Proyecto">
        		<option selected="selected" value="">Seleccione</option>
                           <?php if (isset($proyecto)) { ?>
                   	<?php foreach ($proyecto->result() as $data) { ?>
                   	<option value="<?= $data->id ?>"><?= $data->descripcion ?></option>
                             	<?php } ?>
          <?php }  ?>
           	</select>
                </div>
                </div>
				<div class="field small-3 columns">
            <label for="num:">Gerencia:</label>
             <select data-val="true" data-val-required="The Gerencia field is required." id="Gerencia" name="Gerencia">
        		<option selected="selected" value="">Seleccione</option>
                           <?php if (isset($gerencia)) { ?>
                   	<?php foreach ($gerencia->result() as $data) { ?>
                   	<option value="<?= $data->id ?>"><?= $data->nombre ?></option>
                             	<?php } ?>
          <?php }  ?>
           	</select>
                 </div>
			     <div class="field small-2 columns">
            <label for="num:">Cargo:</label>
             <select data-val="true" data-val-required="The Cargo field is required." id="cargo" name="cargo">
        		<option selected="selected" value="">Seleccione</option>
                           <?php if (isset($cargo)) { ?>
                   	<?php foreach ($cargo->result() as $data) { ?>
                   	<option value="<?= $data->id ?>"><?= $data->nombre ?></option>
                             	<?php } ?>
          <?php }  ?>
           	</select>
                 </div>
                 <div class="row-1"> 
                 <div class="field small-3 columns">
            <label for="Enum:">Tipo Error:</label>
            <select data-val="true" data-val-required="The Tipo Error field is required." id="Banco" name="Banco">

<option selected="selected" value="">Seleccione</option>
<option value="1">Pago Incompleto</option>
<option value="2">Pago No Recibido</option>

                     
           	</select>
                  </div>
 				</div>
				
    <div class=" left buttonPanel">

<input id="btn-" class="button small right" value="Enviar Reclamo" type="submit">

</div>
<?=form_close()  ?>

