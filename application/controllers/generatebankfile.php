<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Generatebankfile extends CI_Controller {
    
    public function index()
    {
        
        $this->load->view('templates/header');
        $this->load->view('templates/navigation');
        
        
        $this->load->model('payments_model');
        $resultPayments=$this->payments_model->getPaymentsGenerateBankFile();
        

        $this->load->model('tiposcuentas_model');
        $resultTipoCuenta=$this->tiposcuentas_model->getTiposCuentas();
        
        $this->load->model('empresaordenante_model');
        $resultEmpresa=$this->empresaordenante_model->getEmpresaOrdenante();
        $data=array('paymentsGenerateBankFile'=>$resultPayments, 'empresaOrdenante'=>$resultEmpresa, 'tipoCuenta'=>$resultTipoCuenta);
        
        if($data != null){
            $this->load->view('payments/generatebankfile/generatebankfile',$data);
        }
        

        $this->load->view('templates/footer');
        
    } 
    
    public function generateCSV()
    {
        
        /*Para la generación del CSV solo se requiere la nomina*/
        $this->form_validation->set_rules('nomina', 'nomina', 'required',array('required' => 'La N&oacute;mina a generar es requerida'));

        if ($this->form_validation->run() == false) {
            
            // validation not ok, send validation errors to the view
            $this->index();
            
        } else {
            
            $nomina = $this->input->post('nomina');
            
            $this->load->model('payments_model');
            $resultPayments=$this->payments_model->getPaymentsGenerateCSVFile($nomina);
            
            $this->load->dbutil();
            $delimiter = "\t";
            $newline = "\n";
            $enclosure = '"';
            $archivo= $this->dbutil->csv_from_result($resultPayments, $delimiter, $newline, $enclosure);
            
            /*
             $this->load->helper('file');
             
            **/
            
            $this->load->helper('download');
            force_download('archivo' . date('Y-m-d H:i:s') .'.csv', $archivo);
            
        }
        
    }

    public function generateTXT()
    {
        
        /*Para la generación del TXT del Banco todos los campos son obligatorios*/
        $this->form_validation->set_rules('empresa', 'empresa', 'required|alpha_numeric_spaces', array('required' => 'El Nombre de la Empresa es requerido', 'alpha_numeric_spaces' => 'El Nombre de la Empresa tiene caracteres inv&aacute;lidos'));
        $this->form_validation->set_rules('rif', 'rif', 'required|alpha_numeric', array('required' => 'El RIF de la Empresa es requerido','alpha_numeric'=>'El RIF de la Empresa tiene caracteres inv&aacute;lidos'));
        $this->form_validation->set_rules('lote', 'lote', 'required|numeric',array('required' => 'El N&uacute;mero de Lote es requerido','numeric' => 'El N&uacute;mero de Lote solo permite numeros'));
        $this->form_validation->set_rules('negociacion', 'negociacion', 'required|numeric',array('required' => 'El N&uacute;mero de Negociaci&oacute;n es requerido','numeric' => 'El N&uacute;mero de Negociaci&oacute;n solo permite numeros'));
        $this->form_validation->set_rules('fecha', 'fecha', 'required',array('required' => 'La Fecha es requerida'));
        $this->form_validation->set_rules('tipocuenta', 'tipocuenta', 'required',array('required' => 'El Tipo de Cuenta es requerido'));
        $this->form_validation->set_rules('numerocuenta', 'numerocuenta', 'required|numeric|exact_length[20]',array('required' => 'El N&uacute;mero de cuenta es requerido','numeric' => 'El N&uacute;mero de Cuenta solo permite numeros','exact_length' => 'El N&uacute;mero de Cuenta debe ser de 20 d&iacute;gitos'));
        $this->form_validation->set_rules('nomina', 'nomina', 'required',array('required' => 'La N&oacute;mina a generar es requerida'));
        
        
        if ($this->form_validation->run() == false) {

            // validation not ok, send validation errors to the view
            $this->index();
            
            
        } else {
            
            $empresa = $this->input->post('empresa');
            $rif = $this->input->post('rif');
            $lote = $this->input->post('lote');
            $negociacion = $this->input->post('negociacion');
            $fecha = $this->input->post('fecha');
            $tipocuenta = $this->input->post('tipocuenta');
            $numerocuenta = $this->input->post('numerocuenta');
            $nomina = $this->input->post('nomina');
            $this->load->model('empresaordenante_model');
            $this->load->model('tipodocumentoidentidad_model');
            $this->load->model('payments_model');
            //Guardo datos en el BD tabla empresa_ordenante
            $resultTipoDocumento=$this->tipodocumentoidentidad_model->getTipoDocumentoIdentidadbyTipo(substr($rif,0,1));
            $tdi=$resultTipoDocumento->result();
            $value = array ('id_tipo_documento_identidad'=>$tdi[0]->id, 
                            'rif' => substr($rif,1), 
                            'nombre' => $empresa, 
                            'numero_negociacion' => $negociacion, 
                            'id_tipo_cuenta' => $tipocuenta, 
                            'numero_cuenta' => $numerocuenta );
            $resultEmpresaOrdenante=$this->empresaordenante_model->updateEmpresaOrdenante($value);
            //Asocio el numero de lote a la nomina y modifico la nomina detalle con la fecha de envio
            $value = array ('numero_lote'=>$lote,
                            'id'=>$nomina
                            );
            $resultPayments=$this->payments_model->updateNumeroLote($value);
            $value = array ('fecha'=>$fecha,
                'id_nomina'=>$nomina
            );
            $resultPayments=$this->payments_model->updateFechaValor($value);
            //Si la nomina detalle tiene mas de 500 registros genero otro archivo, es decir, un archivo cada 500 registros.
            //Genero el TXT del banco
            $resultPayments=$this->payments_model->getPaymentsGenerateTXTFile($nomina);
            $this->load->helper('file');
            $salt="\r\n";
            // Encabezado del archivo TXT
            $header=    "HEADER  " . 
                        str_pad($lote, 8, "0", STR_PAD_LEFT) . 
                        str_pad($negociacion, 8, "0", STR_PAD_LEFT) . 
                        substr($rif,0,1) . 
                        str_pad(substr($rif,1),9,"0", STR_PAD_LEFT) . 
                        $fecha . 
                        $fecha .
                        $salt;
            write_file('txttemp', $header,'w');
            // Debito y Credito de cada pago del archivo TXT
            $cantidad_registros=0;
            $suma_registros=0;
            foreach ($resultPayments->result() as $fila){
                $monto_entero=0;
                $monto_decimal=0;
                $aux_monto=explode(".", $fila->monto_credito);
                if (count($aux_monto)>0){$monto_entero=$aux_monto[0];}
                if (count($aux_monto)>1){$monto_decimal=$aux_monto[1];}
                $debito=    "DEBITO  " . 
                            str_pad($fila->numero_referencia_credito, 8, "0", STR_PAD_LEFT) . 
                            substr($rif,0,1) . 
                            str_pad(substr($rif,1),9,"0", STR_PAD_LEFT) . 
                            str_pad($empresa,35," ", STR_PAD_RIGHT) . 
                            $fecha . 
                            $numerocuenta . 
                            str_pad($monto_entero, 15, "0", STR_PAD_LEFT) . 
                            "," . 
                            str_pad($monto_decimal, 2, "0", STR_PAD_LEFT) . 
                            "VEB40" .
                            $salt;
                if (strlen($fila->nombre_beneficiario)>30) {
                    $nombre=substr($fila->nombre_beneficiario,0,30);
                }else {
                    $nombre=$fila->nombre_beneficiario;
                }
                if ($fila->tipo_cuenta=="C"){
                    $tc="00";
                }else{
                    $tc="01";
                }
                if ($fila->tipo_pago=="1"){
                    $tp="10";
                }else{
                    if ($fila->tipo_pago=="2"){
                        $tp="00";
                    }else{
                        $tp="20";
                    }
                }
                $credito=   "CREDITO " . 
                            str_pad($fila->numero_referencia_credito, 8, "0", STR_PAD_LEFT) . 
                            $fila->id_tipo_documento_identidad . 
                            str_pad($fila->numero_ci_rif, 9, "0", STR_PAD_LEFT) . 
                            str_pad($nombre, 30, " ", STR_PAD_RIGHT) . 
                            $tc . $fila->numero_cuenta_beneficiario . 
                            str_pad($monto_entero, 15, "0", STR_PAD_LEFT) . 
                            "," . 
                            str_pad($monto_decimal, 2, "0", STR_PAD_LEFT) . 
                            $tp . 
                            str_pad($fila->banco,11, " ", STR_PAD_RIGHT) . 
                            $fila->duracion_cheque . 
                            "501" . 
                            $fila->email_beneficiario .
                            $salt;
                $cantidad_registros=$cantidad_registros+1;
                $suma_registros=$suma_registros+$fila->monto_credito;
                write_file('txttemp',$debito,'a');
                write_file('txttemp',$credito,'a');
            }
            
            $monto_entero=0;
            $monto_decimal=0;
            $aux_monto=explode(".", $suma_registros);
            if (count($aux_monto)>0){$monto_entero=$aux_monto[0];}
            if (count($aux_monto)>1){$monto_decimal=$aux_monto[1];}

            //Pie del archivo con resumen del total
            $footer=    "TOTAL   " . 
                        str_pad($cantidad_registros, 5, "0", STR_PAD_LEFT) .
                        str_pad($cantidad_registros, 5, "0", STR_PAD_LEFT) .
                        str_pad($monto_entero, 15, "0", STR_PAD_LEFT) .
                        "," .
                        str_pad($monto_decimal, 2, "0", STR_PAD_LEFT).
                        $salt;
            write_file('txttemp',$footer,'a');
            //Se carga el archivo y se descarga
            $archivo= read_file('txttemp');
            $this->load->helper('download');
            force_download('PROV_' . date('Ymd') .'.txt', $archivo);
        }
    }

}

