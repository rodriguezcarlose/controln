<?php defined('BASEPATH') OR exit('No direct script access allowed');
if (!isset($this->session->userdata['logged_in'])) {
    redirect(base_url()."index.php/user/login");
}
?>
</br>
<div class="row">
	<div class="col-md-12">
		<div class="page-header">

    		<h3>Historico de N&oacute;minas</h3>
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
                            		<td>
                            		<?php 
                                		if ($data->pendiente != null){ ?>
                                		   <a href=<?= base_url() . 'index.php/historyPayments/viewdetails/1/'.$data->id;?>><?= $data->pendiente?></a> 
                                	<?php 
                               			}else{
                                		    echo "0";
                                		}
                                		
                            		?>
                            		</td>
                            		
                            		<td>
                            		<?php 
                            		if ($data->procesada != null){ ?>
                                		   <a href=<?= base_url() . 'index.php/historyPayments/viewdetails/2/'.$data->id;?>><?= $data->procesada?></a> 
                                	<?php 
                               			}else{
                                		    echo "0";
                                		}
                                		
                            		?>
                            		</td>
									<td>
									<?php 
									if ($data->pagada != null){ ?>
                                		   <a href=<?= base_url() . 'index.php/historyPayments/viewdetails/3/'.$data->id;?>><?= $data->pagada?></a> 
                                	<?php 
                               			}else{
                                		    echo "0";
                                		}
                                		
                            		?>
									</td>
									<td>
									<?php 
									if ($data->rechazada != null){ ?>
                                		   <a href=<?= base_url() . 'index.php/historyPayments/viewdetails/3/'.$data->id;?>><?= $data->rechazada?></a> 
                                	<?php 
                               			}else{
                                		    echo "0";
                                		}
                                		
                            		?>
									</td>
                            		<td><a href=<?= base_url() . 'index.php/historyPayments/viewdetails/0/'.$data->id;?>><?= $data->total == null ? 0 : $data->total?></a></td>
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

