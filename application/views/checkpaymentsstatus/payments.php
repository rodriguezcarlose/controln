<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>

<br><br>
<div class="row">
	<div class="dataTableActions text-left">
	<?php $fila=$consulta->result(); $datos= $fila[0]->nacionalidad .'-'. $fila[0]->documento_identidad .'. '. $fila[0]->nombre_apellido;?>
	<h5><?=  $datos; ?></h5>
    </div>
    <div class="dataTableActions text-right">
        <h3>Consulta de Estatus de Pago</h3>
    </div>
    <table id="dataTable">
            <thead>
                <tr>
                    <td>Proyecto</td>
                    <td>Gerencia</td>
                    <td>Rol</td>
                    <td>Banco</td>
                    <td>Nro. Cuenta</td>
                    <td>Fecha</td>
                    <td>Estatus</td>
                    <td>Acci&oacute;n</td>
                </tr>
                
                <?php foreach ($consulta->result() as $fila){ ?>
         
                    <tr>
                        <td><?= $fila->nombre_proyecto; ?></td>
                        <td><?= $fila->nombre_gerencia; ?></td>
                        <td><?= $fila->nombre_cargo; ?></td>
                        <td><?= $fila->nombre_banco; ?></td>
                        <td><?= $fila->numero_cuenta; ?></td>
                        <td><?= $fila->fecha; ?></td>
                       	<td><?= $fila->estatus;?></td>
                       	<td><a href=<?= base_url() . 'claim/addclaims';?> >reclamo</a></td>
                </tr>
         
                <?php }?>
            </thead>
        <tbody></tbody>
    </table>
</div>

<script type="text/javascript">

        function UpdateGrid() {
            datagrid.ajax.reload();
        }

        $(function () {

            $('#CreateElement').click(function (e) {
                e.preventDefault();
                modalEditor(this.href);
            });

            //Boton: limpiar formulario
            $('#btnClearForm').click(function (e) {
                $('form').trigger("reset");
            });

            /////////////////////////////////////
            //Table//////////////////////////////
            /////////////////////////////////////
            var renderButtonActions = function (data, type, full, meta) {
                var r = '';
                r = '<a href="/Demo/Details/' + data + '" target="_blank" class="imgDetailsBtn"><img alt="Ver" src="Content/Images/Icons/view_24.png"></a>' +
                    '<a href="/Demo/Edit/' + data + '" target="_blank" class="imgEditBtn"><img alt="Editar" src="Content/Images/Icons/edit_24.png"></a>' +
                    '<a href="#" class="imgDeleteBtn"><img alt="Editar" src="Content/Images/Icons/delete_24.png"></a>';
                return r;
            }

            function renderCheckBoxes(data, type, full, meta) {
                if (data)
                    return '<input type="checkbox" checked disabled value="' + data + '"/>';
                else
                    return '<input type="checkbox" disabled value="' + data + '"/>';
            }

            function renderTest(data, type, full, meta) {
                console.log(data);
                return data;
            }

            $.ajaxSetup({ cache: false });
            var datagrid = $('#dataTable').DataTable({
                "autoWidth": false,
                "pageLength": 5,
                "processing": false,
                "serverSide": false,
                "deferLoading": 0,
                "dataSrc": "",
                "ajax": {
                    "url": "REST/datatable.php",
                    "type": "POST",
                    "datatype": "json",
                    "data": ""
                },
                "columns": [
                    { "data": "String", "width": "120px" },
                    { "data": "Number", "class": "text-center" },
                    { "data": "DateTime", "class": "text-center", "render": jsonDateToString },
                    { "data": "EnumAsString", "class": "text-center" },
                    { "data": "Bool", "class": "text-center", "render": renderCheckBoxes },
                    { "data": "RadioAsString", "class": "text-center" },
                    { "data": "Id", "orderable": false, "render": renderButtonActions, "class": "text-center" }
                ],
                "language": {
                    "url": "/Scripts/DataTables/dataTables.spanish.lang.js"
                },
                "bFilter": false,
                "bLengthChange": false
            });


            datagrid.on("draw.dt", function (event) {
                setButtonEvents();
            });

        });

        function setButtonEvents() {
            $(".imgDetailsBtn").each(function () {
                $(this).off("click");
                $(this).click(function (e) {
                    e.preventDefault();
                    modalEditor(this.href);
                });
            });

            $(".imgEditBtn").each(function () {
                $(this).off("click");
                $(this).click(function (e) {
                    e.preventDefault();
                    modalEditor(this.href);
                });
            });

            $(".imgDeleteBtn").each(function () {
                $(this).off("click");
                $(this).click(function (e) {
                    e.preventDefault();
                    $.ecconfirm({
                        reveal: '.myConfirm',
                        className: "tiny warning",
                        title: "Advertencia",
                        message: "¿Está seguro que desea Eliminar?",
                        Confirm: function () {
                            console.log('Eliminado');
                        }
                    });
                });
            });


        };
</script>
