<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Payments extends CI_Controller {
    
    
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
        /*if ($this->uri->uri_string()) {
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
         }*/
        
    }
    
    public function index()
    {
        
        
        
    }
    
    public function load(){
        array('error' => ' ' );
        
        $data = new stdClass();
        $data->beneficiario = "";
        
        $this->form_validation->set_rules('beneficiario', 'beneficiario', 'required|alpha_numeric_spaces', array('required' => 'El Campo Beneficiario es requerido','alpha_numeric_spaces' => 'El Campo Beneficiario no debe contener caracteres especiales ni numeros'));
        
        if ($this->form_validation->run() == false) {
            
            $this->load->view('templates/header');
            $this->load->view('templates/Navigation');
            $this->load->view('checkpayments/load',$data);
            $this->load->view('templates/footer');
            
        }else{
            $data->beneficiario = $this->input->post('beneficiario');
            $this->load->view('templates/header');
            $this->load->view('templates/Navigation');
            $this->load->view('checkpayments/load',$data);
            $this->load->view('templates/footer');
            
        }
    }
    
    

    
    public function do_upload()
    {
        $data = new stdClass();
        $config['upload_path']          = './uploads/';
        $config['allowed_types']        = 'csv';
        $config['max_size']             = 100;
        $config['max_width']            = 1024;
        $config['max_height']           = 768;
        
        $this->load->library('upload', $config);
        
        if ( ! $this->upload->do_upload('userfile'))
        {
            $data->error = $this->upload->display_errors();
            $this->load->view('templates/header');
            $this->load->view('templates/Navigation',$data);
            $this->load->view('checkpayments/load',$data);
            $this->load->view('templates/footer');
        }
        else
        {
            $data = array('upload_data' => $this->upload->data());
            
            $this->load->view('templates/header');
            $this->load->view('templates/Navigation',$data);
            $this->load->view('checkpayments/load',$data);
            $this->load->view('templates/footer');
        }
    }
    
    
}

