<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Checkidentity extends CI_Controller {
    
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
        
        
        
    }
    
    public function consultar()
    {
        // set validation rules
        $this->form_validation->set_rules('nacionalidad', 'nacionalidad', 'required', array('required' => 'El Campo Nacionalidad es requerido'));
        $this->form_validation->set_rules('cedula', 'cedula', 'required|numeric|min_length[5]|max_length[9]',array('required' => 'El Campo Cedula es requerido','numeric' => 'El Campo Cedula solo permite numeros','min_length' => 'El Campo Cedula debe indicar al menos 5 digitos','max_length' => 'El Campo Cedula debe indicar m&aacute;ximo 9 digitos'));
        
        //validaci�n del captcha
         $this->form_validation->set_rules('g-recaptcha-response', '', 'required',array('required' => 'El Campo capcha es requerido'));
        
        
        if ($this->form_validation->run() == false) {
            
            // validation not ok, send validation errors to the view
            $this->load->view('templates/header');
            $this->load->view('templates/navigation');
            $this->load->view('checkpaymentsstatus/checkidentity');
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
                $this->load->view('checkpaymentsstatus/payments',$dataPayments);
            }

            $this->load->model('claims_model');
            $result=$this->claims_model->getClaimsByIdentity($nacionalidad,$cedula);
            $dataClaims=array('consulta'=>$result);
            
            if($result != null){
                $this->load->view('checkpaymentsstatus/claims',$dataClaims);
            }
            
            
            $this->load->view('templates/footer');
            

        }
        
    }
}
