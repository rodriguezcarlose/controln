<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<fieldset class="ribbon no-pad no-pad-bottom">
    <div class="HorizontalTabLayout">
        <div class="TabHeader">
            <ul class="tabs content" data-tab>
               <?php 
                	if (isset($tab)){
                	    if ($tab == "1"){
                	        echo '<li class="tab-title active"><a href="#Tab1-1">Generar Archivo TXT</a></li>';
                	        echo '<li class="tab-title"><a href="#Tab2-1">Generar Archivo CSV</a></li>';
                	    }else{
                	        echo '<li class="tab-title"><a href="#Tab1-1">Generar Archivo TXT</a></li>';
                	        echo '<li class="tab-title active"><a href="#Tab2-1">Generar Archivo CSV</a></li>';
                	    }
                	}else{
                	    echo '<li class="tab-title active"><a href="#Tab1-1">Generar Archivo TXT</a></li>';
                	    echo '<li class="tab-title"><a href="#Tab2-1">Generar Archivo CSV</a></li>';
                	}
            	?>

            </ul>
        </div>
        <div class="TabContent ribbon">
            <div class="tabs-content content">
            
            	<?php 
                	if (isset($tab)){
                	    if ($tab == "1"){
                	        echo '<div id="Tab1-1" class="content active">';
                	    }else{
                	        echo '<div id="Tab1-1" class="content">';
                	    }
                	}else{
                	    echo '<div id="Tab1-1" class="content active">';
                	}
            	?>
 
             	<h3>Generar Archivo TXT Pago Banco</h3>
				<?php $fila=$empresaOrdenante->result(); ?>        
            	<?= form_open("generatebankfile/generateTXT") ?>
                                
                	<div class="row">
                    	<div class="large-12 columns">
                    		<label for="String:">Nombre de la Empresa Ordenante</label>
                    		<input id="empresa" name="empresa" placeholder="" type="text" value="<?= $fila[0]->nombre_empresa; ?>" />
                    	</div>
                	</div>
                	<div class="row">
                    	<div class="large-4 medium-4 columns">
                    		<label for="String:">RIF de la Empresa</label>
                    		<input id="rif" name="rif" placeholder="" type="text" value="<?= $fila[0]->nombre_tipo_documento_identidad . $fila[0]->rif; ?>" />
                    	</div>        	
                    	
                    	<div class="large-4 medium-4 columns">
                    		<label for="String:">N&uacute;mero de Referencia de Lote</label>
                    		<input id="lote" name="lote" placeholder="<?= $fila[0]->numero_ref_lote; ?>" type="text" value="" />
                    	</div>        	
            
                    	<div class="large-4 medium-4 columns">
                    		<label for="String:">N&uacute;mero de Negociaci&oacute;n</label>
                    		<input id="negociacion" name="negociacion" placeholder="" type="text" value="<?= $fila[0]->numero_negociacion; ?>" />
                    	</div>        	
        			</div>
        			<div class="row">
                    	<div class="large-4 medium-4 columns">
                    		<label for="String:">Fecha de Env&iacute;o</label>
            				<div class="ec-dp-container">
            					<input data-datetimepicker="" class="ec-dp-input" style="width: 0px; height: 0px; margin: 0px; padding: 0px; position: absolute; visibility: hidden;">
            					<input id="fecha" name="fecha" class="ec-dp-show-input tip-top" placeholder="dd/mm/YYYY" data-iso-datetime="">
            				</div>
                    	</div>        	
            
                        <div class="large-4 medium-4 columns">
                            <label for="Enum:">Tipo de Cuenta</label>
                        	    <select data-val="true" data-val-required="The Enum field is required." id="tipocuenta" name="tipocuenta" >
                                   		<option value="<?= $fila[0]->id_tipo_cuenta; ?>"><?= $fila[0]->descripcion; ?></option>
                                   	<?php foreach ($tipoCuenta->result() as $fila2){ ?>
                                   		<option value="<?= $fila2->id; ?>"><?= $fila2->descripcion; ?></option>
            						<?php } ?>
                        		</select>
                        </div>	
        	           	<div class="large-4 medium-4 columns">
                    		<label for="String:">N&uacute;mero de Cuenta</label>
                    		<input id="numerocuenta" name="numerocuenta" placeholder="" type="text" value="<?= $fila[0]->numero_cuenta; ?>" />
                    	</div>        	        	
                	</div>
                	<div class="row">
                    	<div class="large-12 columns">
                            <label for="Enum:">Nomina</label>
                        	    <select data-val="true" data-val-required="The Enum field is required." id="nomina" name="nomina" >
                                   	<?php foreach ($paymentsGenerateBankFile->result() as $fila3){ ?>
                                   		<option value="<?= $fila3->id; ?>"><?= '(' . $fila3->id . ') ' . $fila3->fecha_creacion . ' [' . $fila3->estatus . ']' . ' PROYECTO:' . $fila3->nombre_proyecto . '. GERENCIA:' .  $fila3->nombre_gerencia  . '. DESCRIPCION:'. $fila3->descripcion; ?></option>
            						<?php } ?>
                        		</select>
                        </div>
                    </div>
        
        
                    <div class="small-12 column text-right buttonPanel">
                        <input id="btnEnviar" class="button small right" value="Generar" type="submit">
                    </div>
                <?= form_close() ?>
            
 
 
             </div>
                
                <?php 
                	if (isset($tab)){
                	    if ($tab == "2"){
                	        echo '<div id="Tab2-1" class="content active">';
                	    }else{
                	        echo '<div id="Tab2-1" class="content">';
                	    }
                	}else{
                	    echo '<div id="Tab2-1" class="content">';
                	}
            	?>

             	<h3>Generar Archivo CSV</h3>
        
            	<?= form_open("generatebankfile/generateCSV") ?>
                                
                	<div class="row">
                    	<div class="large-12 columns">
                            <label for="Enum:">Nomina</label>
                        	    <select data-val="true" data-val-required="The Enum field is required." id="nomina" name="nomina" >
                                   	<?php foreach ($paymentsGenerateBankFile->result() as $fila){ ?>
                                   		<option value="<?= $fila->id; ?>"><?= '(' . $fila->id . ') ' . $fila->fecha_creacion . ' [' . $fila->estatus . ']' . ' PROYECTO:' . $fila->nombre_proyecto . '. GERENCIA:' .  $fila->nombre_gerencia  . '. DESCRIPCION:'. $fila->descripcion; ?></option>
            						<?php } ?>
                        		</select>
                        </div>
                    </div>
        
        
                    <div class="small-12 column text-right buttonPanel">
                        <input id="btnEnviar" class="button small right" value="Generar" type="submit">
                    </div>
                <?= form_close() ?>
                </div>
            </div>
      <?php  echo '</div>'; ?>
<?php  echo '</div>'; ?>
</fieldset>


