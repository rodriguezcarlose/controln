<?php
defined('BASEPATH') OR exit('No direct script access allowed');?>
<h3 class="show-for-small-only"></h3>     </br>
	
<h3>Detalle del Reclamo </h3>
	
<table id="dataTable">
	<thead>		
		<div class"row">
			
			<td>
			<label for="num:">Nacionalidad:</label>
				<?= $query[0]->nacionalidad ?>                      	
			</td>
        
        	<td>
       			<label for="Cedula:">Cedula:</label>
       			 <?= $query[0]->cedula; ?>                      	
               </td>
                
			<td>
       			 <label for="Nombre:">Nombre:</label>
        		 <?= $query[0]->npersona ?>                      	
        	</td>
        
            <td>
                <label for="Apellido:">Apellido:</label>
                <?= $query[0]->apellido ?>                      	
            </td>
        
            <td>
                <label for="Telefono">Telefono:</label>
                <?= $query[0]->telefono ?>                      	
            </td>
        
            <td>
                <label for="Correo">Correo Electronico:</label>
                <?= $query[0]->correo ?>                      	
            </td>
</table>
  
<table id="dataTable">
        
            <td>
            <label for="Enum:">Banco:</label>
                <?= $query[0]->banco ?>                      	
                </td>
            
            <td>
                <label for="numerocuenta">Numero de Cuenta:</label>
                <?= $query[0]->numero_cuenta ?>                      	
            </td>
            
            <td>
                <label for="Enum:">Tipo de Cuenta:</label>
                <?= $query[0]->tipo ?>                      	
            </td>
            	
            <td>
            	<label for="Enum:">Imagen:</label>
            	<a href= "<?php echo base_url();?>index.php/claim/download/<?= $query[0]->soportereclamos;?>">Descargar </a>
            </td>
</table>
               	
<table id="dataTable">
	<thead>
            <td>
            <label for="num:">Proyecto:</label>
              
             <?= $query[0]->proyecto ?>                      	
            </td>
            
            <td>
                <label for="num:">Gerencia:</label>
                <?= $query[0]->gerencia ?>                      	
            </td>
            
             
             <td>
                <label for="num:">Cargo:</label>
                <?= $query[0]->cargo ?>                      	
            </td>
            
            <td>
                <label for="Enum:">Tipo Error:</label>
                <?= $query[0]->nombre_error ?>                      	
            </td>
            
            <td>
                <label for="cantidad_dias">Dias Trabajados:</label>
                <?= $query[0]->cantidad_dias ?>                      	
            </td>
	</thead>
</table>
