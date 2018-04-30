<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
if (!isset($this->session->userdata['logged_in'])) {
    redirect(base_url()."index.php/user/login");
}?>
</br>
<div class="container">
	<div class="col-md-20">


		<?=form_open('/')?>
			<h3>Cargar Pagos Rechazados</h3>
			</br>	
			<h4>Los siguientes registros no fueron procesados ya que no encontraron en la Base de Datos, por favor valide los datos.</h4>
        	<?php if (isset($nodatarecords) ) { ?>
        		
        		<table id="dataTable">
                		<thead>
                			<tr>
                    			<td>Nro. Ticket</td>
                                <td>Nro. D&eacute;bito</td>
                                <td>Nro. Cr&eacute;dito</td>	
                                <td>Nro. RIF/CI</td>
                                <td>Beneficiario</td>
                                <td>Tipo Cuenta</td>
                                <td>N&uacute;mero Cuenta</td>
                                <td>Monto</td>
                                <td>Fecha</td>
                			</tr>
                		</thead>
                		<tbody>
                		 <?php foreach ($nodatarecords as $dataRecords) { ?>
                    		 <tr>
                                        <td>
                                        	<?php echo ($dataRecords["ticket"])?>
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
                		 	</tr>
                		 <?php }?>
                		</tbody>
                		
                </table>
                
                <div class="small-12 column text-right buttonPanel">
					<input type="submit" id="btnEnviar" class="button small right" value="Aceptar" />
				</div>   
        	
        	<?php }?>
        	
       	<?= form_close()?>     
	</div>
</div>

