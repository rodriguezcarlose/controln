<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
   <h2 class="show-for-small-only"></h2>    
	<br>
   <div class="container">
    <div class="row">
    	<h3>Consulta de Estatus de Pago</h3>

    	<?= form_open() ?>
            <div class="form-group">
                <label for="Enum:">Nacionalidad</label>
            	    <select data-val="true" data-val-required="The Enum field is required." id="nacionalidad" name="nacionalidad" >
                        <option selected="selected" value="V">VENEZOLANA</option>
                        <option value="E">EXTRANJERO</option>
                        <option value="P">PASAPORTE</option>
            		</select>
            </div>
        	<div class="form-group">
        		<label for="String:">Cedula/pasaporte</label>
        		<input id="cedula" name="cedula" placeholder="" type="text" value="" maxlength = "9" onkeypress="return validar_texto(event)"  />
        	</div>
            <center>
    				<?php 
        				switch ($_SERVER['HTTP_HOST']) {
        				    case "controlnomina.com":
        				        echo '<div class="g-recaptcha" data-sitekey="6Lco3UwUAAAAALLL9KeaIHOD4Bg6iS0Bwv1HehNC"></div>';
        				        break;
        				    case "172.16.4.172":
        				        echo '<div class="g-recaptcha" data-sitekey="6Lco3UwUAAAAALLL9KeaIHOD4Bg6iS0Bwv1HehNC"></div>';
        				        break;
        				    case "10.31.5.2":
        				        echo '<div class="g-recaptcha" data-sitekey="6Lco3UwUAAAAALLL9KeaIHOD4Bg6iS0Bwv1HehNC"></div>';
        				        break;
        				    case "10.31.193.147":
        				        echo '<div class="g-recaptcha" data-sitekey="6LcREFQUAAAAAO58EooEWhWqm2Zk-M0II-sQlSD2"></div>';
        				        break;
        				        break;
        				    case "190.202.115.30":
        				        echo '<div class="g-recaptcha" data-sitekey="6LeWyVUUAAAAAKimO7-YmOwsFmuM2XfSr64Ikfuy"></div>';
        				        break;
        				        $fase = 3;
        				        break;
        				    case "201.249.171.30":
        				        echo '<div class="g-recaptcha" data-sitekey="6LenTVUUAAAAADHplTqw3eykF-AuuxdnM3sK_keY"></div>';
        				        break;
        				}
    				?>
    		</center>
            <div class="small-12 column text-right buttonPanel">
                <input id="btnCloseModalEditor" class="button small right alert" value="Cancelar" type="button">
                <input id="btnEnviar" class="button small right" value="Consultar" type="submit">
            </div>
        <?= form_close() ?>

    </div>
</div>
