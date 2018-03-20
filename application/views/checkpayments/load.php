<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<fieldset class="ribbon no-pad no-pad-bottom">
    <!-- Otra información -->
    <div class="HorizontalTabLayout">
        <div class="TabHeader">
            <ul class="tabs content" data-tab>
                <li class="tab-title active"><a href="#Tab1-1">Agregar Beneficiario</a></li>
                <li class="tab-title"><a href="#Tab2-1">Cargar Archivo Nomina</a></li>
            </ul>
        </div>
        <div class="TabContent ribbon">
            <div class="tabs-content content">
                <div id="Tab1-1" class="content active">
                    <h3>Agregar Beneficiario</h3>
 					<?= form_open()?>
            		<div class="field small-3 column">
                        <label for="String:">Beneficiario:</label>
                        <input id="beneficiario" name="beneficiario" type="text" value="" />
                    </div>
                    <div class="field small-3 column">
                        <label for="Number:">Nro. Referencia:</label>
                        <input id="referencia" name="referencia" type="text" value="" />
                    </div>
                    <div class="field small-3 column">
                        <label for="Enum:">Letra RIF/CI:</label>
                        <select id="letra" name="letra" >
                            <option selected="selected" value="">Seleccione</option>
                            <option value="V">Venezolano(a)</option>
                            <option value="E">Extranjero(A)</option>
                            <option value="E">Pasaporte</option>
            			</select>
                    </div>
                    <div class="field small-3 column">
                        <label for="Number:">Nro. RIF/CI:</label>
                        <input id="rifci" name="rifci" type="text" value="" />
                    </div>
        
                    <div class="field small-3 column">
                        <label for="Enum:">Tipo de Cuenta:</label>
                        <select id="tipocuenta" name="tipocuenta">
                            <option selected="selected" value="">Seleccione</option>
                            <option value="C">Corriente</option>
                            <option value="A">Ahorro</option>
            			</select>
                    </div>
                    <div class="field small-3 column">
                        <label for="Number:">Nro. Cuenta:</label>
                        <input id="cuenta" name="cuenta" type="text" value="" maxlength = "20"/>
                    </div>
                     <div class="field small-3 column">
                        <label for="Number:">Monto:</label>
                        <input id="monto" name="monto" type="text" value="" />
                    </div>
                    <div class="field small-3 column">
                        <label for="Enum:">Tipo de Pago:</label>
                        <select id="tipopago" name="tipopago">
                            <option selected="selected" value="">Seleccione</option>
                            <option value="1">Abono de Cuenta BdV</option>
                            <option value="2">Transferencia Swift</option>
                            <option value="3">Cheque de Gerencia</option>
            			</select>
                    </div>
                    <div class="field small-3 column">
                        <label for="Enum:">Banco:</label>
                        <select id="banco" name="banco">
                            <option selected="selected" value="">Seleccione</option>
                            <option value="1">Banco</option>
                            <option value="2">Banco</option>
                            <option value="3">Banco</option>
            			</select>
                    </div>
                    <div class="field small-3 column">
                        <label for="Enum:">Duraci&oacute;n Cheque:</label>
                        <select  id="duracioncheque" name="duracioncheque">
                            <option selected="selected" value="">Seleccione</option>
                            <option value="1">30</option>
                            <option value="2">45</option>
                            <option value="3">60</option>
                            <option value="4">90</option>
                            <option value="5">120</option>
                            	<option value="6">180</option>
            			</select>
                    </div>
                    <div class="field small-3 column">
                        <label for="Number:">Email:</label>
                        <input id="email" name="email" type="text" value="" />
                    </div>
                    <div class="field small-3 columns">
                        <label for="Date:">Fecha:</label>
                        <input id="fecha" name="fecha" type="text" value="" />
                    </div>
                    <div class="small-12 column text-right buttonPanel">
                        <input type="reset" id="btnCloseModalEditor" class="button small right alert" value="Limpiar"/>
                        <input type="submit" id="btnEnviar" class="button small right" value="Agregar" />
                    </div>
					<?= form_close()?>	
                </div>
                <div id="Tab2-1" class="content">
                    <h3>Cargar Archivo Nomina</h3>
                    <?php echo form_open_multipart('payments/do_upload');?>
                        <input type="file" name="userfile" size="20" />
                        <br /><br />
                        <input type="submit" value="Cargar" />
                    </form>
                </div>
            </div>
        </div>
    </div>
</fieldset>