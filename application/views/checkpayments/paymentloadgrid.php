<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="container">
	<div class="col-md-20">
            <?php if (isset($links)) { ?>
                <?php echo $links ?>
            <?php } ?>
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
    		
            <?php if (isset($results)) { ?>
                    <?php foreach ($results as $data) { ?>
                        <tr>
                            <td><input id="beneficiario<?= $data->id ?>" name="beneficiario" type="text" value="<?= $data->beneficiario ?>" /></td>
                            <td><input id="id_cargo<?= $data->id ?>" name="id_cargo" type="text" value="<?= $data->id_cargo ?>" /></td>
                            <td><input id="referencia_credito<?= $data->id ?>" name="referencia_credito" type="text" value="<?= $data->referencia_credito ?>" /></td>
                            <td><input id="id_tipo_documento_identidad<?= $data->id ?>" name="id_tipo_documento_identidad" type="text" value="<?= $data->id_tipo_documento_identidad ?>" /></td>
                            <td><input id="documento_identidad<?= $data->id ?>" name="documento_identidad" type="text" value="<?= $data->documento_identidad ?>" /></td>
                            <td><input id="id_tipo_cuenta<?= $data->id ?>" name="id_tipo_cuenta" type="text" value="<?= $data->id_tipo_cuenta ?>" /></td>
                            <td><input id="numero_cuenta<?= $data->id ?>" name="numero_cuenta" type="text" value="<?= $data->numero_cuenta ?>" /></td>
                            <td><input id="credito<?= $data->id ?>" name="credito" type="text" value="<?= $data->credito ?>" /></td>
                            <td><input id="id_tipo_pago<?= $data->id ?>" name="id_tipo_pago" type="text" value="<?= $data->id_tipo_pago ?>" /></td>
                            <td><input id="id_banco<?= $data->id ?>" name="id_banco" type="text" value="<?= $data->id_banco ?>" /></td>
                            <td><input id="id_duracion_cheque<?= $data->id ?>" name="id_duracion_cheque" type="text" value="<?= $data->id_duracion_cheque ?>" /></td>
                            <td><input id="correo_beneficiario<?= $data->id ?>" name="correo_beneficiario" type="text" value="<?= $data->correo_beneficiario ?>" /></td>
                            <td><input id="fecha<?= $data->id ?>" name="fecha" type="text" value="<?= $data->fecha ?>" /></td>
							<td><a href="#" class="imgDeleteBtn"><img alt="Eliminar"  src="<?= base_url()?>Content/Images/Icons/delete_24.png"></a></td>
                            
                        </tr>
                    <?php } ?>

            <?php }  ?>


    		</tbody>
    	</table>
    	
 
            <?php if (isset($links)) { ?>
                <?php echo $links ?>
            <?php } ?>
    	
	</div>
</div>