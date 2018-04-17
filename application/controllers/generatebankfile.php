<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Generatebankfile extends CI_Controller {
    
    /**
     * __construct function.
     *
     * @access public
     * @return void
     */
    public function __construct() {
        
        parent::__construct();
        
        
        //Para impedir el acceso directo desde la URL
        //Validamos si es el path principal ? , si lo es deje accesar desde url
        if ($this->uri->uri_string()) {
            //Carga Libraria User_agent
            $this->load->library('user_agent');
            //Verifica si llega desde un enlace
            if ($this->agent->referrer()) {
                //Busca si el enlace llega de una URL diferente
                $post = strpos($this->agent->referrer(), base_url());
                if ($post === FALSE) {
                    //Podemos aqui crear un mensaje antes de redirigir que informe
                    redirect(base_url());
                }
            }
            //Si no se llega desde un enlace se redirecciona al inicio
            else {
                //Podemos aqui crear un mensaje antes de redirigir que informe
                redirect(base_url());
            }
        }
    }
    
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
        
            if ($resultPayments!=null){
                if($data != null){
                    $this->load->view('payments/generatebankfile/generatebankfile',$data);
                }
            }else{
                
                $this->load->view('payments/generatebankfile/generatebankfileNotFound');
            }

        $this->load->view('templates/footer');
        
    } 
    
    public function generateCSV()
    {
        
        /*Para la generaci�n del CSV solo se requiere la nomina*/
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
        
        /*Para la generaci�n del TXT del Banco todos los campos son obligatorios*/
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
            $estatus_nomina_procesada=3;
            $estatus_nominadetalle_procesada=2;
            
            //Guardo datos en el BD tabla empresa_ordenante
            $resultTipoDocumento=$this->tipodocumentoidentidad_model->getTipoDocumentoIdentidadbyTipo(substr($rif,0,1));
            $tdi=$resultTipoDocumento->result();
            
            $value = array ('id_tipo_documento_identidad'=>$tdi[0]->id, 
                            'rif' => substr($rif,1), 
                            'nombre' => $empresa, 
                            'numero_negociacion' => $negociacion, 
                            'id_tipo_cuenta' => $tipocuenta, 
                            'numero_cuenta' => $numerocuenta );
            
            if ($tipocuenta=="1"){
                $tipocuenta="00";
            }else{
                $tipocuenta="01";
            }
            
            
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

            
            $resultPayments=$this->payments_model->updateEstatusNominabyId($nomina,$estatus_nomina_procesada);
            $resultPayments=$this->payments_model->updateEstatusNominaDetallebyId($nomina,$estatus_nominadetalle_procesada);
            
            
            
            //Genero el TXT del banco
            $resultPayments=$this->payments_model->getPaymentsGenerateTXTFile($nomina);
            $this->load->helper('file');
            $salt="\r\n";
            

                      
            // Debito y Credito de cada pago del archivo TXT
            $id_archivos=0;
            $nombre_archivo='PROV_' . date('Ymd') . '_' . $lote . '_'; 
            $cantidad_registros=0;
            $suma_registros=0;
            foreach ($resultPayments->result() as $fila){
                // Si la cantidad de registros es cero, se crea un nuevo archivo.
                if ($cantidad_registros==0){
                    // Encabezado del archivo TXT
                    $header=    "HEADER  " .
                        str_pad($lote, 8, "0", STR_PAD_LEFT) .
                        str_pad($negociacion, 8, "0", STR_PAD_LEFT) .
                        substr($rif,0,1) .
                        str_pad(substr($rif,1),9,"0", STR_PAD_LEFT) .
                        $fecha .
                        $fecha .
                        $salt;
                        $id_archivos=$id_archivos+1;
                        
                        write_file($nombre_archivo . $id_archivos . '.txt', $header,'w');
                        
                }
                
                $monto_entero=0;
                $monto_decimal=0;
                $aux_monto=explode(".", $fila->monto_credito);
                if (count($aux_monto)>0){$monto_entero=$aux_monto[0];}
                if (count($aux_monto)>1){$monto_decimal=$aux_monto[1];}

                
                $debito=    "DEBITO  " . 
                            str_pad($fila->numero_referencia_credito, 8, "0", STR_PAD_LEFT) . 
                            substr($rif,0,1) . 
                            str_pad(substr($rif,1),9,"0", STR_PAD_LEFT) . 
                            str_pad(strtoupper($empresa),35," ", STR_PAD_RIGHT) . 
                            $fecha . 
                            $tipocuenta .
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
                          //  "501" . 
                            $fila->email_beneficiario .
                            $salt;
                $cantidad_registros=$cantidad_registros+1;
                $suma_registros=$suma_registros+$fila->monto_credito;
                write_file($nombre_archivo . $id_archivos . '.txt',$debito,'a');
                write_file($nombre_archivo . $id_archivos . '.txt',$credito,'a');
                
                //El archivo TXT soporta hasta 500 transacciones, si el archivo tiene mas de 500 se cierra en esta condici�n para abrir otro archivo.
                if ($cantidad_registros==500){
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
                        write_file($nombre_archivo . $id_archivos . '.txt',$footer,'a');
                       
                        $cantidad_registros=0;
                        $suma_registros=0;
                }
            
            }
            //El archivo TXT soporta hasta 500 transacciones, si el archivo tiene menos de 500 se cierra en esta condici�n.
            if ($cantidad_registros<500){
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
                    write_file($nombre_archivo . $id_archivos . '.txt',$footer,'a');
                    
                    
                    $cantidad_registros=0;
                    $suma_registros=0;
            }
            
            $this->load->library('zip');
            
            for($i=0;$i<=$id_archivos;$i=$i+1){
                //Se Agregan los archivos de nominas a un archivos al ZIP
                $this->zip->read_file($nombre_archivo . $i . '.txt');
                
                //Se Eliminan los archivos temporales de nomina
                unlink($nombre_archivo . $i . '.txt');
            }
            
            
            //Se Descargar el archivo Zip con las nominas
            $this->zip->download('PROV_' . date('Ymd') . '_' . $lote. '.zip');
            
        }
    }

}

