<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<br>

<div class="container">
	<div class="row">
		<h3>Reclamo de Pago</h3>

		<h4>Datos Personales</h4>
    	<?=form_open_multipart() ?>
      	<div class="form-group">
			<div class="field small-3 columns">

				<label for="num:">Nacionalidad:</label> 
    				<select id=id_tipo_documento_identidad name="id_tipo_documento_identidad">
    					<option selected="selected" value="">Seleccione</option>
                              <?php
                                     if (isset($tipodocumentoidentidad)) {
                                             $selected = false;
                                            foreach ($tipodocumentoidentidad->result() as $data) {
                                                if ($id_tipo_documento_identidad == $data->nombre) {
                                                    $selected = true;
                               ?>
                           							<option selected="selected" value="<?= $data->nombre ?>"><?= $data->descripcion ?></option>
                            							
                            	<?php
                                                } else {
                                ?>
                            						<option value="<?= $data->nombre ?>"><?= $data->descripcion ?></option>
                               	<?php
                                                }
                                            } 
                                     }  
                                ?>  
               		</select>
			</div>

			<div class="field small-3 columns">
				<label for="Cedula:">Cedula:</label> <input id="documento_identidad"
					name="documento_identidad" type="text"
					value="<?php if(isset($documento_identidad)) echo $documento_identidad; ?>"
					maxlength="9" onkeypress="return validar_texto(event)" />


			</div>


			<div class="field small-3 columns">
				<label for="Nombre:">Nombre:</label> 
				<input id="nombre" name="nombre" type="text" value="<?php if(isset($nombre)) echo $nombre; ?>" maxlength="30" />
			</div>


			<div class="field small-3 columns">
				<label for="Apellido:">Apellido:</label> 
				<input id="apellido" name="apellido" type="text" value="<?php if(isset($apellido)) echo $apellido; ?>" maxlength="30" />
			</div>

			<div class="field small-4 columns">
				<label for="Telefono">Telefono:</label> <input id="telefono"
					name="telefono" type="text"
					value="<?php if(isset($telefono)) echo $telefono; ?>"
					maxlength="11" onkeypress="return validar_texto(event)" />
			</div>
			<div class="field small-4 columns">
				<label for="Correo">Correo Electronico:</label> <input id="correo"
					name="correo" type="text"
					value="<?php if(isset($correo)) echo $correo; ?>" maxlength="30" />
			</div>

			<div class="field small-4 columns">
				<label for="Confirmacion">Confirmacion Correo Electronico:</label> <input
					id="confirmacion" name="confirmacion" type="text" value="" />
			</div>

			<h4>Datos Bancarios</h4>

			<div class="field small-4 columns">
				<label for="Enum:">Banco:</label> 
					<select id="id_banco" name="id_banco">
					<option selected="selected" value="">Seleccione</option>
                   	<?php
                        if (isset($bancos)) {
                            $selected = false;
                            foreach ($bancos->result() as $data) {
                                if ($id_banco == $data->id) {
                                    $selected = true;
                    ?>
									<option selected="selected" value="<?= $data->id ?>"><?= $data->nombre ?></option>
       				<?php
                                } else {
                    ?>
                        			<option value="<?= $data->id ?>"><?= $data->nombre ?></option>
                    <?php
                                }
                             } 
                        }  
                    ?>  
           			</select>
			</div>

			<div class="field small-4 columns">
				<label for="numerocuenta">Numero de Cuenta:</label> <input
					id="numero_cuenta" name="numero_cuenta" type="text"
					value="<?php if(isset($numero_cuenta)) echo $numero_cuenta; ?>"
					maxlength="20" onkeypress="return validar_texto(event)" />
			</div>

			<div class="field small-4 columns">
				<label for="Enum:">Tipo de Cuenta:</label>
					<select id="id_tipos_cuentas" name="id_tipos_cuentas">
						<option selected="selected" value="">Seleccione</option>
                        <?php
                            if (isset($tiposcuentas)) {
                                $selected = false;
                                foreach ($tiposcuentas->result() as $data) {
                                    if ($id_tipos_cuentas == $data->id) {
                                        $selected = true;
                        ?>
                        				<option selected="selected" value="<?= $data->id ?>"><?= $data->descripcion ?></option>
                     	<?php
                                    } else {
                        ?>
                        				<option value="<?= $data->id ?>"><?= $data->descripcion ?></option>
         				<?php
                                    }
                                 } 
                            }  
                        ?>  
           			</select>
			</div>
             <?php 
             	 if (isset($soportereclamos) && $soportereclamos !== ""){
              ?>
    				<div class="field small-12 columns">
						<label for="Enum:">Soporte Cargado: <?= $soportereclamos?></label>
						<input type="submit" value="eliminar" name="btnCargar" id="btnCargar" class="button small" onclick="this.form.action = '<?=base_url()?>index.php/claim/eliminar'" />
						<input id="soportereclamos" name="soportereclamos" type="hidden" value="<?= $soportereclamos?>">
					</div>
				
			<?php 
             	 }else{
			?>
					<div class="field small-12 columns">
						<label for="Enum:">Archivo Soporte:</label> <input type="file" name="file_name" size="20" /> 
						<input type="submit" value="Cargar"name="file_name" id="btnCargar" class="button small" onclick="this.form.action = '<?=base_url()?>index.php/claim/do_upload'" />
					</div>
			<?php 
             	 }
            ?>
				
			<h4>Datos Evento</h4>
			<div class="field small-3 columns">
				<label for="num:">Proyecto:</label> 
				<select id="id_proyecto" name="id_proyecto">
					<option selected="selected" value="">Seleccione</option>
	     	<?php
                if (isset($proyecto)) {
                    $selected = false;
                    foreach ($proyecto->result() as $data) {
                        if ($id_proyecto == $data->id) {
                            $selected = true;
            ?>
            				<option selected="selected" value="<?= $data->id ?>"><?= $data->nombre ?></option>
			<?php
                        } else {
             ?>
							<option value="<?= $data->id ?>"><?= $data->nombre ?></option>
			<?php
                        }
                    } 
                }  
            ?>     
           		</select>
			</div>
			<div class="field small-3 columns">
				<label for="num:">Gerencia:</label> 
				<select id="id_gerencia" name="id_gerencia">
					<option selected="selected" value="">Seleccione</option>
                    <?php
                        if (isset($gerencia)) {
                            $selected = false;
                            foreach ($gerencia->result() as $data) {
                                    if ($id_gerencia == $data->id) {
                                        $selected = true;
                    ?>
										<option selected="selected" value="<?= $data->id ?>"><?= $data->nombre ?></option>
                    <?php
                                    } else {
                     ?>
                        				<option value="<?= $data->id ?>"><?= $data->nombre ?></option>
                    <?php
                                    }
                            } 
                        }  
                    ?>  
           		</select>
			</div>
			<div class="field small-3 columns">
				<label for="num:">Cargo:</label> <select id="id_cargo"
					name="id_cargo">
					<option selected="selected" value="">Seleccione</option>
                             <?php
                            
                            if (isset($cargo)) {
                                $selected = false;
                                ?>
                        				<?php
                                
                                foreach ($cargo->result() as $data) {
                                    if ($id_cargo == $data->id) {
                                        $selected = true;
                                        ?>
                        						<option selected="selected"
						value="<?= $data->id ?>"><?= $data->nombre ?></option>
                        							
                        				<?php
                                    } else {
                                        ?>
                        						<option value="<?= $data->id ?>"><?= $data->nombre ?></option>
                        						
                        				<?php
                                    }
                                    ?>
                                  	<?php } ?>
                           
               					<?php }  ?>  
           	</select>
			</div>

			<div class="field small-3 columns">
				<label for="Enum:">Tipo Error:</label> <select id="id_tipo_error"
					name="id_tipo_error">

					<option selected="selected" value="">Seleccione</option>
						    <?php
        
        if (isset($tipoerror)) {
            $selected = false;
            ?>
                        				<?php
            
            foreach ($tipoerror->result() as $data) {
                if ($id_tipo_error == $data->id) {
                    $selected = true;
                    ?>
                        						<option selected="selected"
						value="<?= $data->id ?>"><?= $data->nombre_error ?></option>
                        							
                        				<?php
                } else {
                    ?>
                        						<option value="<?= $data->id ?>"><?= $data->nombre_error ?></option>
                        						
                        				<?php
                }
                ?>
                                  	<?php } ?>
                           
               					<?php }  ?>  
           			</select>
			</div>

			<div class="field small-3 columns">
				<label for="cantidad_dias">Dias Trabajados:</label> 
				<input id="numeric" name="cantidad_dias" type="text" value="<?php if(isset($cantidad_dias)) echo $cantidad_dias; ?>" maxlength="3" onkeypress="return validar_texto(event)"/>
			</div>
			
			<div class="field small-12 columns">
      					<label for="comentario">Nota Explicativa:</label>
      					<textarea rows= "5" cols="50" name="comentario" id="comentario" type="text"  maxlength = "255"><?php if(isset($comentario)) echo $comentario; ?></textarea>
      					
				
		</div> 	
		
		
			<div class="small-12 column">
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
           	</div>
			<div class="small-12 column text-right buttonPanel">
				<input type="submit" id="btnEnviar" name="btnEnviar"
					class="button small right" value="Enviar Reclamo"
					onclick="this.form.action = '<?=base_url()?>index.php/claim/addclaims'"/>

			</div>
	</div>
		<? form_close()?>	
	</div>
</div>

