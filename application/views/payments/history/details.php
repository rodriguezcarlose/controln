<?php defined('BASEPATH') OR exit('No direct script access allowed');
if (!isset($this->session->userdata['logged_in'])) {
    redirect(base_url()."index.php/user/login");
}
?>
</br>
<div class="row">
	<div class="col-md-12">
		<div class="page-header">

    		<h3>Detalle Pagos <?= isset($estatus)? $estatus : "" ?></h3>
    		<?php        	           
        	        echo form_open();
                    $cant= 0;
                    if (isset($detail) ) { 
                        
                        if (isset($links)) {?>
                	    <div class = "row">
                	    	<div class="small-6 columns">
                	    		<div class="dataTables_info" id="dataTable_info" role="status" aria-live="polite">
                	    			Mostrando   <?= $total_records ?> Registros 
                	    		</div>
                	    	</div>
                	    	<?= $links?>
                	    </div>
        	           <?php  } ?>
    
                    	<table id="dataTable">
                    		<thead>
                    			<tr>
                    				<td>Referencia</td>
                    				<td>Beneficiario</td>
                        			<td>Cargo</td>
                                    <td>Documento Identidad</td>
                                    <td>Cuenta</td>
                                    <td>Cr&eacute;dito</td>
                                    <td>Tipo Cuenta</td>
                                    <td>Tipo Pago</td>
                                    <td>Banco</td>
                                    <td>Estatus</td>
                     			</tr>
                    		</thead>
                    		<tbody>
            		
                            <?php foreach ($detail->result() as $data) {  ?>
                            	<tr>
                            		<td><?=$data->referencia_credito ?></td>
                            		<td><?=$data->beneficiario ?></td>
                            		<td><?=$data->cargo ?></td>
                            		<td><?=$data->tipo_documento."-".$data->documento_identidad ?></td>
                            		<td><?=$data->cuenta ?></td>
                            		<td><?=$data->credito ?></td>
                            		<td><?=$data->tipo_cuenta ?></td>
                            		<td><?=$data->tipo_pago ?></td>
                            		<td><?=$data->banco ?></td>
                            		<td><?=$data->estatus ?></td>
                            	</tr>
                            <?php } ?>
        
                    		</tbody>
                    	</table>
                    	
                    	<?php  if (isset($links)) {?>
                	    <div class = "row">
                	    	<div class="small-6 columns">
                	    		<div class="dataTables_info" id="dataTable_info" role="status" aria-live="polite">
                	    			Mostrando <?= $total_records ?> Registros 
                	    		</div>
                	    	</div>
                	    	<?= $links?>
                	    </div>
        	           <?php  } ?>
        	           
                    	
                    	<input type="hidden" name="cantreg" value="<?= $cant?>">
    
    
    
    
    
                    	<div class="small-12 column text-right buttonPanel">
                    		<?php if($estatus=="Procesados" && $_SESSION['id_rol'] === 1){?>
                    			<input type="submit" id="btnEnviar" class="button small right" value="Volver"  onclick="this.form.action = '<?=base_url()?>index.php/historyPayments'" />
            	            	<input type="submit" id="btnEnviar" class="button small right" value="Procesar Pago" onclick="this.form.action = '<?=base_url()?>index.php/historyPayments/updateNominaProccessed/<?= $id_nomina?>'"  />
            	            <?php }else{?>
            	            	<input type="submit" id="btnEnviar" class="button small right" value="Volver"  onclick="this.form.action = '<?=base_url()?>index.php/historyPayments'" />
            	            <?php }?>
                        </div>   	
            		 
            		
        		 <?php } 
        		 echo form_close();?>   
		 </div> 
	</div>
</div>

