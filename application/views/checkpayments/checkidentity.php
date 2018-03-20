<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
   <h2 class="show-for-small-only"></h2>    
	<br><br>
   <div class="container">
    <div class="row">
    	<h3>Consulta de Estatus de Pago</h3>

    	<?= form_open() ?>
            <div class="field small-3 columns">
                <label for="Enum:">Nacionalidad:</label>
            	    <select data-val="true" data-val-required="The Enum field is required." id="nacionalidad" name="nacionalidad" >
                        <option selected="selected" value="V">V</option>
                        <option value="E">E</option>
                        <option value="P">P</option>
            		</select>
            </div>
        	<div class="field small-3 columns">
        		<label for="String:">Cedula/pasaporte:</label>
        		<input id="cedula" name="cedula" placeholder="" type="number" value="" />
        	</div>
        	
        	<br><br><br>
        	
            <div class="g-recaptcha" data-sitekey="6Lco3UwUAAAAALLL9KeaIHOD4Bg6iS0Bwv1HehNC"></div>

            <div class="small-12 column text-right buttonPanel">
                <input id="btnCloseModalEditor" class="button small right alert" value="Cancelar" type="button">
                <input id="btnEnviar" class="button small right" value="Consultar" type="submit">
            </div>
        <?= form_close() ?>

    </div>
</div>
