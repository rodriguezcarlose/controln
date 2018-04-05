<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class claim extends CI_Controller {
    
    
    /**
     * __construct function.
     *
     * @access public
     * @return void
     */
    public function __construct() {
        
        parent::__construct();
        

        $this->load->model('TiposCuentas_model');
        $this->load->model('Banco_model');
        $this->load->model('TipoDocumentoIdentidad_model');
        $this->load->model('TipoPago_model');
        $this->load->model('Cargo_model');
        $this->load->model('Claims_model');
        $this->load->model('Proyecto_model');
        $this->load->model('Gerencia_model');
        $this->load->model('Cargo_model');
        $this->load->model('tipoerror_model');
        
    }
    
    public function index()
    {
        
       
        
    }
    
    public function addclaims()
    {
        
        $data = new stdClass();
        $data->bancos =  $this->Banco_model->getBancos();
        $data->tipodocumentoidentidad =$this->TipoDocumentoIdentidad_model->getTipoDocumentoIdentidad();
        $data->proyecto =  $this->Proyecto_model->getProyecto();
        $data->gerencia =  $this->Gerencia_model->getGerencia();
        $data->cargo =  $this->Cargo_model->getCargos();
        $data->tipoerror =  $this->tipoerror_model->gettipoerror();
        
        // set validation rules
        $this->form_validation->set_rules('id_tipo_documento_identidad', 'id_tipo_documento_identidad', 'required', array('required' => 'El Campo Nacionalidad es requerido'));
        $this->form_validation->set_rules('documento_identidad', 'documento_identidad', 'required|numeric|min_length[5]|max_length[9]',array('required' => 'El Campo Cedula es requerido','numeric' => 'El Campo Cedula solo permite numeros','min_length' => 'El Campo Cedula debe indicar al menos 5 digitos','max_length' => 'El Campo Cedula debe indicar maximo 9 digitos'));
        $this->form_validation->set_rules('nombre', 'nombre', 'required|alpha_spaces|min_length[3]|max_length[30]',array('required' => 'El Campo Nombre es requerido','alpha_spaces' => 'El Campo Nombre solo permite Letra','min_length' => 'El Campo Nombre debe indicar al menos 3 caracteres','max_length' => 'El Campo Nombre debe indicar maximo 30 caracteres'));
        $this->form_validation->set_rules('apellido', 'apellido', 'required|alpha_spaces|min_length[3]|max_length[30]',array('required' => 'El Campo Apellido es requerido','alpha_spaces' => 'El Campo Apellido solo permite Letras','min_length' => 'El Campo Apellido debe indicar al menos 8 caracteres','max_length' => 'El Campo Apellido debe indicar maximo 40 caracteres'));
        $this->form_validation->set_rules('telefono', 'telefono', 'required|numeric|exact_length[11]',array('required' => 'El Campo Telefono es requerido','numeric' => 'El Campo Telefono solo permite numeros','exact_length' => 'El Campo Telefono debe indicar 11 digitos'));
        $this->form_validation->set_rules('correo', 'correo', 'required|valid_email|min_length[8]|max_length[30]',array('required' => 'El Campo Correo es requerido','valid_email'=>'El campo Correo debe ser Valido con @/.com','min_length' => 'El Campo Correo debe tenr un minimo de 8 caracteres','max_length' => 'El Campo Correo debe tener un maximo 30 caracteres'));
        $this->form_validation->set_rules('confirmacion', 'confirmacion', 'required|matches[correo]',array('required'=>'El Campo Confirmacion es requerido','matches'=>'El Campo Confirmacion Correo Electronico debe coincidir con Correo Electronico'));
        $this->form_validation->set_rules('id_banco', 'id_banco', 'required', array('required' => 'El Campo Banco es requerido'));
        $this->form_validation->set_rules('numero_cuenta', 'numero_cuenta', 'required|numeric|exact_length[20]',array('required' => 'El N&uacute;mero de cuenta es requerido','numeric' => 'El N&uacute;mero de Cuenta solo permite numeros','exact_length' => 'El N&uacute;mero de Cuenta debe ser de 20 d&iacute;gitos'));
        $this->form_validation->set_rules('id_proyecto', 'id_proyecto', 'required', array('required' => 'El Campo Proyecto es requerido'));
        $this->form_validation->set_rules('id_gerencia', 'id_gerencia', 'required', array('required' => 'El Campo Gerencia es requerido'));
        $this->form_validation->set_rules('id_cargo', 'id_cargo', 'required', array('required' => 'El Campo Cargo es requerido'));
        $this->form_validation->set_rules('id_tipo_error', 'id_tipo_error', 'required', array('required' => 'El Campo Tipo Error es requerido'));
        
        
        //validaci�n del captcha
       //  $this->form_validation->set_rules('g-recaptcha-response', '', 'required',array('required' => 'El Campo capcha es requerido'));
        
         
         
        
        if ($this->form_validation->run() == false) {
            
            // validation not ok, send validation errors to the view
            $this->load->view('templates/header');
            $this->load->view('templates/navigation',$data);
            $this->load->view('claims/addclaim',$data);
            $this->load->view('templates/footer');
            
        } else {
            
            $this->Claims_model->addclaims($this->input->post);
            
            $nacionalidad = $this->input->post('id_tipo_documento_identidad');
            $cedula = $this->input->post('documento_identidad');
            $nombre = $this->input->post('Nombre');
            $apellido = $this->input->post('Apellido');
            $telefono = $this->input->post('telefono');
            $correo = $this->input->post('correo');
            $banco = $this->input->post('id_banco');
            $numerocuenta = $this->input->post('numero_cuenta');
            $proyecto = $this->input->post('id_proyecto');
            $gerencia = $this->input->post('id_gerencia');
            $cargo = $this->input->post('id_cargo');
            $tipoerror = $this->input->post('id_tipo_error');
            
            
            $this->load->view('templates/header');
            $this->load->view('templates/navigation');
            
            

            
            $this->load->view('claims/uploadclaim_success');
            
            
            $this->load->view('templates/footer');
            

        }
        
       
    }
}

