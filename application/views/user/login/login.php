<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<h2 class="show-for-small-only"></h2>
<br>
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<div class="page-header">
				<h3>Ingresar</h3>
			</div>
    			<?= form_open() ?>
    				<div class="form-group">
    					<label for="username">Usuario</label>
    					<input type="text" class="form-control" id="username" name="username" placeholder="Usuario">
    				</div>
    				
    				<div class="form-group">
    					<label for="password">Clave</label>
    					<input type="password" class="form-control" id="password" name="password" placeholder="Clave">
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
        				        break;
        				}
    				?>
    				</center>

    				

    				<div class="small-12 column text-right buttonPanel">
                        <input type="submit" id="btnEnviar" class="button small right" value="Aceptar"/>
           			</div>
           			
    			<?= form_close() ?>
		</div>
	</div><!-- .row -->
</div><!-- .container -->