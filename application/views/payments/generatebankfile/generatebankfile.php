<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
   <h2 class="show-for-small-only"></h2>    
	<br>
   <div class="container">
    <div class="row">
    	<h3>Generar Archivo Pago Banco</h3>

    	<?= form_open() ?>
                        
        	<div class="form-group">
        		<label for="String:">Nombre de la Empresa Ordenante</label>
        		<input id="empresa" name="empresa" placeholder="" type="text" value="" />
        	</div>
        	
        	<div class="form-group">
        		<label for="String:">RIF de la Empresa</label>
        		<input id="rif" name="rif" placeholder="" type="text" value="" />
        	</div>        	
        	
        	<div class="form-group">
        		<label for="String:">N&uacute;mero de Referencia de Lote</label>
        		<input id="lote" name="lote" placeholder="" type="text" value="" />
        	</div>        	

        	<div class="form-group">
        		<label for="String:">N&uacute;mero de Negociaci&oacute;n</label>
        		<input id="negociacion" name="negociacion" placeholder="" type="text" value="" />
        	</div>        	

        	<div class="form-group">
        		<label for="String:">Fecha de Env&iacute;o</label>
        		<input id="fecha" name="fecha" placeholder="" type="text" value="" />
        	</div>        	

            <div class="form-group">
                <label for="Enum:">Tipo de Cuenta</label>
            	    <select data-val="true" data-val-required="The Enum field is required." id="tipocuenta" name="tipocuenta" >
                       	<?php foreach ($tipocuenta->result() as $fila){ ?>
                       		<option value="<?= $fila->tipo; ?>"><?= $fila->descripcion; ?></option>
						<?php } ?>
            		</select>
            </div>	
        	
        	<div class="form-group">
        		<label for="String:">N&uacute;mero de Cuenta</label>
        		<input id="numerocuenta" name="numerocuenta" placeholder="" type="text" value="" />
        	</div>        	        	
        	
        	            <div class="form-group">
                <label for="Enum:">Proyecto</label>
            	    <select data-val="true" data-val-required="The Enum field is required." id="proyecto" name="proyecto" >
                       	<?php foreach ($proyecto->result() as $fila){ ?>
                       		<option value="<?= $fila->id; ?>"><?= $fila->nombre; ?></option>
						<?php } ?>
            		</select>
            </div>

            <div class="form-group">
                <label for="Enum:">Gerencia</label>
            	    <select data-val="true" data-val-required="The Enum field is required." id="gerencia" name="gerencia" >
                       	<?php foreach ($gerencia->result() as $fila){ ?>
                       		<option value="<?= $fila->id; ?>"><?= $fila->nombre; ?></option>
						<?php } ?>
            		</select>
            </div>
            
            <div class="form-group">
                <label for="Enum:">Consecutivo</label>
            	    <select data-val="true" data-val-required="The Enum field is required." id="consecutivo" name="consecutivo" >
                        <option selected="selected" value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
            		</select>
            </div>

            <div class="small-12 column text-right buttonPanel">
                <input id="btnCloseModalEditor" class="button small left " value="Generar CSV" type="button">
                <input id="btnEnviar" class="button small right" value="Generar TXT" type="submit">
            </div>
        <?= form_close() ?>

    </div>
</div>
