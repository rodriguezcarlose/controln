<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<br>

<div class="container">
	<div class="row">
		<h3>Ingreso de Salarios</h3>

		<h4>Seleccione una Gerencia:</h4>
				<?= form_open('salary/checksalary') ?>

		<div class="field small-12 columns">
			<label for="num:">Gerencia:</label> <select id="id_gerencia"
				name="id_gerencia">
				<option selected="selected" value="">Seleccione</option>
                    <?php
                    if (isset($gerencia)) {
                        $selected = false;
                        foreach ($gerencia->result() as $data) {
                            if ($id_gerencia == $data->id) {
                                $selected = true;
                                ?>
										<option selected="selected" value="<?= $data->id ?>"><?= $data->nombre ?></option>
                    <?php
                            } else {
                                ?>
                        				<option value="<?= $data->id ?>"><?= $data->nombre ?></option>
                    <?php
                            }
                        }
                    }
                    ?>  
           		</select>
		</div>

		<div class="field small-12 columns">
			<input type="submit" id="btnEnviar" name="btnEnviar"
				class="button small right" " value="   Buscar" />
		</div>
            	<?= form_close() ?>
			
		<?php echo form_open_multipart('Salary/updateSalary');?>
      	<div class="form-group">
			    <?php if (isset($gerenciaporcargo) ) { ?>
    
                    	<table id="dataTable">
				<thead>
					<tr>
						<td>Cargo</td>
						<td>Sueldo</td>
						<td>Cantidad</td>
						<td>Total</td>
					</tr>
				</thead>
				<tbody>
            		
                            <?php
        foreach ($gerenciaporcargo->result() as $data) {
            ?>
            		<tr>
						<td><input id="id_cargo" name="id_cargo[]" type="hidden"
							value="<?= $data->id_cargo?>" /> <?= $data->cargo?> </td>
						<td><br>
						<input id="sueldo" name="sueldo[]" type="text"
							value="<?=isset($data->sueldo)?$data->sueldo:""; ?> "
							maxlength="8" /></td>
						<td><br>
						<input id="cantidad" name="cantidad[]" type="text"
							value="<?=$data->cantidad?>" maxlength="4" /></td>
						<?php $total=$data->sueldo * $data->cantidad?>  	
						<td><?=number_format($total, 0 , ' , ' ,  '.')?> </td>
					</tr>
                            <?php
        }
        ?>                           
          		</tbody>
			</table>
			<div class="field small-12 column">
				<label for="Enum:">Total: <?php echo number_format($totalg[0]->total, 0 , ' , ' ,  '.') ?>    </label>
			</div>

			<div class="small-12 column text-right buttonPanel">
				<input type="submit" id="btnEnviar" name="btnEnviar"
					class="button small right" value="Cargar  Salarios" />
			</div>
	
        		 <?php
    }
    ?>             	
	</div>
		<? form_close()?>	
	</div>
</div>

