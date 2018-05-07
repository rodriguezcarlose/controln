<?php defined('BASEPATH') OR exit('No direct script access allowed');
if (!isset($this->session->userdata['logged_in'])) {
    redirect(base_url()."index.php/user/login");
}
?>
</br>
<div class="row">
	<div class="col-md-12">
		<div class="page-header">

    		<h3>Pagos</h3>
    		</br>
    		<?php echo form_open();
    		$data= 0;
                    
                    if (isset($history) ) { ?>
    
                    	<table id="dataTable">
                    		<thead>
                    			<tr>
                    				<td>Proyecto</td>
                        			<td>Descripci&oacute;n N&oacute;mina</td>
                                    <td>Lote</td>
                                    <td>Estatus</td>
                                    <td>Fecha de Creaci&oacute;n</td>
                                    <td>Pendientes</td>
                                    <td>Procesados</td>
                                    <td>Pagados</td>
                                    <td>Rechazados</td>
                                    <td>Total</td>
                                     <td>Detalle</td>
                     			</tr>
                    		</thead>
                    		<tbody>
            		
                            <?php foreach ($history->result() as $data) {  ?>
                            	<tr>
                            		<td><?=$data->proyecto ?></td>
                            		<td><?=$data->descripcion ?></td>
                            		<td><?=$data->numero_lote ?></td>
                            		<td><?=$data->estatus ?></td>
                            		<td><?=$data->fecha_creacion ?></td>
                            		<td><?= $data->pendiente == null ? 0 : $data->pendiente?></td>
                            		<td><?= $data->procesada == null ? 0 : $data->procesada?></td>
                            		<td><?= $data->pagada == null ? 0 : $data->pagada?></td>
                            		<td><?= $data->rechazada == null ? 0 : $data->rechazada?></td>
                                    <td>Total</td>
                            		<td><a href=<?= base_url() . 'index.php/historyPayments/viewdetails/'.$data->id;?>>Detalle</a></td>
                            	</tr>
                            <?php } ?>
        
                    		</tbody>
                    	</table>
                    	<input type="hidden" name="id"  value="<?= $data && $data->id ?>">
                    	
    
                    	<div class="small-12 column text-right buttonPanel">
            	            	<input type="submit" id="btnEnviar" class="button small right" value="Aceptar"  />
                        </div>   	
        		 <?php } 
        		 echo form_close();?>   
		 </div> 
	</div>
</div>

