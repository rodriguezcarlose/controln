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
        $resulTiposCuentas=$this->tiposcuentas_model->getTiposCuentas();
        
        $data=array('paymentsGenerateBankFile'=>$resultPayments, 'tipocuenta'=>$resulTiposCuentas);
        
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
            $delimiter = ";";
            $newline = "\n";
            $enclosure = '"';
            $archivo= $this->dbutil->csv_from_result($resultPayments, $delimiter, $newline, $enclosure);
            
            /*
             $this->load->helper('file');
             write_file('c:\opt\archivo.csv', $archivo);
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
            
            $nomina = $this->input->post('nomina');
            echo $nomina;
            

        }
    }

}

