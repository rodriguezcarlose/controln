<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Checkidentity extends CI_Controller {
    
    public function index()
    {
        
        
        
    }
    
    public function consultar()
    {
        // set validation rules
        $this->form_validation->set_rules('nacionalidad', 'nacionalidad', 'required', array('required' => 'El Campo Nacionalidad es requerido'));
        $this->form_validation->set_rules('cedula', 'cedula', 'required|numeric|min_length[5]|max_length[8]',array('required' => 'El Campo Cedula es requerido','numeric' => 'El Campo Cedula solo permite numeros','min_length' => 'El Campo Cedula debe indicar al menos 5 digitos','max_length' => 'El Campo Cedula debe indicar máximo 8 digitos'));
        
        //validación del captcha
         $this->form_validation->set_rules('g-recaptcha-response', '', 'required',array('required' => 'El Campo capcha es requerido'));
        
        
        if ($this->form_validation->run() == false) {
            
            // validation not ok, send validation errors to the view
            $this->load->view('templates/header');
            $this->load->view('templates/navigation');
            $this->load->view('checkpayments/checkidentity');
            $this->load->view('templates/footer');
            
        } else {
            
            $nacionalidad = $this->input->post('nacionalidad');
            $cedula = $this->input->post('cedula');
            
            $this->load->view('templates/header');
            $this->load->view('templates/navigation');
            
            $this->load->model('payments_model');
            $result=$this->payments_model->getPaymentsByIdentity($nacionalidad,$cedula);
            $dataPayments=array('consulta'=>$result);
            
            if($result != null){
                $this->load->view('checkpayments/payments',$dataPayments);
            }

            $this->load->model('claims_model');
            $result=$this->claims_model->getClaimsByIdentity($nacionalidad,$cedula);
            $dataClaims=array('consulta'=>$result);
            
            if($result != null){
                $this->load->view('checkpayments/claims',$dataClaims);
            }
            
            
            $this->load->view('templates/footer');
            

        }
        
    }
}
