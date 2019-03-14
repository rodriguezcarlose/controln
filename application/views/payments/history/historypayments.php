<?php

defined('BASEPATH') or exit('No direct script access allowed');
if (! isset($this->session->userdata['logged_in'])) {
    redirect(base_url() . "index.php/user/login");
}
?>
</br>
	<div class="col-md-12">
		<div class="page-header">

			<h3>Historico de N&oacute;minas</h3>
			</br>
    		<?php
    echo form_open();
    $data = 0;
    ?>      
	    		<div class="field small-2 column">
				<label for="Enum:">Proyecto:</label> <select id="id_proyecto"
					name="id_proyecto">
					<option value="">Todos</option>
                         <?php
                        if (isset($proyecto)) {
                            foreach ($proyecto->result() as $data) {
                                ?>
                         	<option value="<?= $data->id ?>"><?= $data->nombre ?></option>
                         <?php
                            }
                        }
                        ?>
          			</select>
			</div>
            	
            	<?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true && isset($_SESSION['id_rol']) &&  $_SESSION['id_rol'] === 1 ){ ?>
            	
    				<div class="field small-2 column">
				<label for="Enum:">Gerencia:</label> <select id="id_gerencia"
					name="id_gerencia">
					<option value="">Todas</option>
                             <?php
                if (isset($gerencia)) {
                    foreach ($gerencia->result() as $data) {
                        ?>
                             	<option value="<?= $data->id ?>"><?= $data->nombre ?></option>
                             <?php
                    }
                }
                ?>
              			</select>
			</div>
                <?php
            }
            ?>
            	<div class="field small-2 column">
				<label for="Enum:">Estatus:</label> <select id="id_estatus"
					name="id_estatus">
					<option value="">Todos</option>
                         <?php
                        if (isset($estatusnomina)) {
                            foreach ($estatusnomina->result() as $data) {
                                ?>
                         	<option value="<?= $data->id ?>"><?= $data->nombre ?></option>
                         <?php
                            }
                        }
                        ?>
          			</select>
			</div>
			<div class="field small-3 column">
				<label for="Enum:">Descripci&oacute;n:</label> <input
					id="descripcion" name="descripcion" type="text" value="" />
			</div>

			<div class="field small-3 column">
				<label for="String:">Buscar Fecha de Pago:</label> 
			<div class="ec-dp-container">
            					<input data-datetimepicker="" class="ec-dp-input" style="width: 0px; height: 0px; margin: 0px; padding: 0px; position: absolute; visibility: hidden;">
            					<input id="fecha_creacion" name="fecha_creacion" type= "date" >
            				</div>
			</div>


			<div class="field small-2 column">
				<input type="submit" value="Buscar" id="btnBuscar"
					class="button small" />
			</div>
    		    		  		
                    <?php if (isset($history) ) { ?>
    
                    	<table id="dataTable">
				<thead>
					<tr>
						<td>Proyecto</td>
						<td>Descripci&oacute;n N&oacute;mina</td>
						<td>Gerencia</td>
						<td>Lote</td>
						<td>Estatus</td>
						<td>Fecha de Creaci&oacute;n</td>
						<td>Fecha de Pago</td>
						<td>Pendiente</td>
						<td>Procesada</td>
						<td>Pagada</td>
						<td>Pagada BS</td>
						<td>Rechazada</td>
						<td>Rechazada BS</td>
						<td>Total</td>
						<td>Total BS</td
					
					</tr>
				</thead>
				<tbody>
            		
                            <?php foreach ($history->result() as $data) {  ?>
                            	<tr>
						<td><?=$data->proyecto ?></td>
						<td><?=$data->descripcion ?></td>
						<td><?=$data->gerencia ?></td>
						<td><?=$data->numero_lote ?></td>
						<td><?=$data->estatus ?></td>
						<td><?=$data->fecha_creacion ?></td>
<td>
                            		<?php
                            		if ($data->fecha_pago != '00/00/0000') {
                                ?>
                                		 
							<?= $data->fecha_pago?>
                                	<?php
                            } else {
                                echo "";
                            }
                            
                            ?>
                            		</td>						
						<td>
                            		<?php
                            if ($data->pendiente != null) {
                                ?>
                                		   <a
							href=<?= base_url() . 'index.php/historyPayments/viewdetails/1/'.$data->id;?>><?= $data->pendiente?></a> 
                                	<?php
                            } else {
                                echo "0";
                            }
                            
                            ?>
                            		</td>

						<td>
                            		<?php
                            if ($data->procesada != null) {
                                ?>
                                		   <a
							href=<?= base_url() . 'index.php/historyPayments/viewdetails/2/'.$data->id;?>><?= $data->procesada?></a> 
                                	<?php
                            } else {
                                echo "0";
                            }
                            
                            ?>
                            		</td>
						<td>
									<?php
                            if ($data->pagada != null) {
                                ?>
                                		   <a
							href=<?= base_url() . 'index.php/historyPayments/viewdetails/3/'.$data->id;?>><?= $data->pagada?></a> 
                                	<?php
                            } else {
                                echo "0";
                            }
                            
                            ?>
									</td>
										<td>
										<?php
										if (number_format($data->pagada_BS, 0 , ' , ' ,  '.') != null) {
                                ?>
                                <?=number_format($data->pagada_BS, 0 , ' , ' ,  '.')?>
								<?php
                            } else {
                                echo "0";
                            }
                            
                            ?></td> 
	 			
						<td>
									<?php
                            if ($data->rechazada != null) {
                                ?>
                                		   <a
							href=<?= base_url() . 'index.php/historyPayments/viewdetails/4/'.$data->id;?>><?= $data->rechazada?></a> 
                                	<?php
                            } else {
                                echo "0";
                            }
                            
                            ?>
						</td>
								<td>
								<?php
								if (number_format($data->rechazada_BS, 0 , ' , ' ,  '.') != null) {
                                ?>
                                <?=number_format($data->rechazada_BS, 0 , ' , ' ,  '.')?>
                                <?php
                            } else {
                                echo "0";
                            }
                            
                            ?></td> 

						<td><a
							href=<?= base_url() . 'index.php/historyPayments/viewdetails/0/'.$data->id;?>><?= $data->total == null ? 0 : $data->total?></a></td>
												
												
							<td>
							<?php
							if (number_format($data->total_BS, 0 , ' , ' ,  '.') != null) {
                                ?>
							<?=number_format($data->total_BS, 0 , ' , ' ,  '.')?>
							<?php
                            } else {
                                echo "0";
                            }
                            
                            ?></td> 
	 			
					</tr>
                            <?php } ?>
                            
                      		</tbody>
			</table>
			<input type="hidden" name="id" value="<?= $data && $data->id ?>">
                    	
	
        		 <?php
                    
}
                    echo form_close();
                    ?>   
                    
        		 <?php
                    
                              //echo $this->db->last_query();
                    
                    ?>   
                    
        		 <label>Leyenda Estatus N&oacute;mina:</label> <label>Cargada:
				La nomina esta cargada por la gerencia solicitante.</label> <label>Liberada:
				Liberada por el Gerente Solicitante, para que pueda ser enviada al
				Banco.</label> <label>Procesada: Enviada al Banco.</label> <label>Pagada:
				Pagada en el Banco.</label> <br> <label>Leyenda Detalles:</label>
            <?php foreach ($estatusNom->result() as $fila){ ?>
            	<label><?= $fila->nombre?>: <?= $fila->descripcion?></label>
		    <?php }?>
		 </div>
	</div>
</div>

