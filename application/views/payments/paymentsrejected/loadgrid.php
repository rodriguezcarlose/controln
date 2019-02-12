<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if (!isset($this->session->userdata['logged_in'])) {
    redirect(base_url()."index.php/user/login");
}
?>
</br>
<div class="container">
	<div class="col-md-20">
		<div class="row">
			<div class="col-md-12">
				<div class="page-header">
					<?php echo form_open_multipart('paymentsrejected/do_upload');?>
   						<h3>Cargar Pagos Rechazados</h3>
   						<div class="field small-3 column">
                           	<label for="Enum:">Archivo:</label>
   							<input type="file" name="userfile" size="20"/>
   						</div>
                           <input type="submit" value="Cargar" id="btnCargar" class="button small"  />
                  	<?= form_close()?>
				</div>
			</div>
		</div>

		<?=form_open('paymentsrejected/loadgrid')?>
                    	
                      	         

        	<?php $i = 1?>
        	<?php if (isset($records) ) { ?>
        		
        		<table id="dataTable">
                		<thead>
                			<tr>
                				<td>ID</td>
                    			<td>Nro. Ticket</td>
                                <td>Nro. D&eacute;bito</td>
                                <td>Nro. Cr&eacute;dito</td>
                                <td>Nro. RIF/CI</td>
                                <td>Beneficiario</td>
                                <td>Tipo Cuenta</td>
                                <td>N&uacute;mero Cuenta</td>
                                <td>Monto</td>
                                <td>Fecha</td>
                        <td>Nomina</td>
                			</tr>
                		</thead>
                		<tbody>
                		 <?php foreach ($records as $dataRecords) { ?>
                		 	<?php $i ++;?>
                    		 <tr>
                    		 			<td><?= $i?></td>
                                        <td>
                                        	<?php echo ($dataRecords["ticket"])?>
                                        	<input type ="hidden"  name = "ticket" value="<?= $dataRecords["ticket"]?>" >
                                        </td>
                                        <td>
                                        	<?php echo ($dataRecords["debito"])?>
                                        </td>
                                        <td>
                                        	<?php echo ($dataRecords["credito"])?>
                                        </td>
                                        <td>
                                        	<?php echo ($dataRecords["documento_identidad"])?>
                                        </td>
                                        <td>
                                        	<?php echo ($dataRecords["beneficiario"])?>
                                        </td>
                                        <td>
                                        	<?php echo ($dataRecords["tipo_cuenta"])?>
                                        </td>
                                        <td>
                                        	<?php echo ($dataRecords["nro_cuenta"])?>
                                        </td>
                                        <td>
                                        	<?php echo ($dataRecords["monto"])?>
                                        </td>
                                        <td>
                                        	<?php echo ($dataRecords["fecha"])?>
                                        </td>
                             <td>
                        <?php echo ($dataRecords["nomina"]) ?>
                            </td>
                		 	</tr>
                		 <?php }?>
                		</tbody>
                		
                </table>
                
                <div class="small-12 column text-right buttonPanel">
					<input type="submit" id="btnEnviar" class="button small right" value="Procesar" />
				</div>   
        	
        	<?php }?>
        	
       	<?= form_close()?>     
	</div>
</div>

