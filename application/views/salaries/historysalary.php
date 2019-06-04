<?php
defined('BASEPATH') or exit('No direct script access allowed');
if (! isset($this->session->userdata['logged_in'])) {
    redirect(base_url() . "index.php/user/login");
}
?>
</br>
<div class="col-md-12">
	<div class="page-header">

		<h3>Hist&oacute;rico Para Contabilidad</h3>
		</br>
    		<?php
    echo form_open();
    $data = 0;
    ?>      
	    		<div class="field small-3 column">
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
            	
    				<div class="field small-3 column">
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
          
			<div class="field small-3 column">
			<label for="Enum:">Descripci&oacute;n:</label> <input
				id="descripcion" name="descripcion" type="text" value="" />
		</div>

		<div class="field small-3 column">
			<label for="String:">Buscar Fecha de Pago:</label>
			<div class="ec-dp-container">
				<input data-datetimepicker="" class="ec-dp-input"
					style="width: 0px; height: 0px; margin: 0px; padding: 0px; position: absolute; visibility: hidden;">
				<input id="fecha_creacion" name="fecha_creacion" type="date">
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
					<td>Fecha de Pago</td>
					<td>Pagada BS</td>
					<td>Rechazada BS</td>
					<td>Cant. Pagos</td>
					<td>Total BS</td>
					<td>Monto M&aacute;ximo</td>
					<td>Saldo A Favor</td>
				</tr>
			</thead>
			<tbody>
            		
                            <?php foreach ($history->result() as $data) {  ?>
                            	<tr>
					<td><?=$data->proyecto ?></td>
					<td><?=$data->descripcion ?></td>
					<td><?=$data->gerencia ?></td>
					<td><?=$data->numero_lote ?></td>
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
                            if (number_format($data->pagada_BS, 0, ' , ', '.') != null) {
                                ?>
                                <?=number_format($data->pagada_BS, 0 , ' , ' ,  '.')?>
								<?php
                            } else {
                                echo "0";
                            }
                            
                            ?></td>
					<td>
								<?php
                            if (number_format($data->rechazada_BS, 0, ' , ', '.') != null) {
                                ?>
                                <?=number_format($data->rechazada_BS, 0 , ' , ' ,  '.')?>
                                <?php
                            } else {
                                echo "0";
                            }
                            
                            ?></td>

					<td><?= $data->total == null ? 0 : $data->total?></td>


					<td>
							<?php
                            if (number_format($data->total_BS, 0, ' , ', '.') != null) {
                                ?>
							<?=number_format($data->total_BS, 0 , ' , ' ,  '.')?>
							<?php
                            } else {
                                echo "0";
                            }
                            
                            ?></td>

					<td>
								<?php
                            if (number_format($data->monto_maximo, 0, ' , ', '.') != null) {
                                ?>
                                <?=number_format($data->monto_maximo, 0 , ' , ' ,  '.')?>
                                <?php
                            } else {
                                echo "0";
                            }
                            
                            ?></td> 
                         <?php $diferencia=$data->monto_maximo - $data->total_BS?>  	
                            
							<td>
								<?php
                            if (number_format($diferencia, 0, ' , ', '.') != null && $diferencia > 0) {
                                ?>
                                <?=number_format($diferencia, 0 , ' , ' ,  '.')?>
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
        ?>   
                    
        	
		 </div>
</div>
</div>

