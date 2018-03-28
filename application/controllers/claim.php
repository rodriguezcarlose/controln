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
        $this->load->model("Claims_model");
        $this->load->model("Proyecto_model");
        $this->load->model("Gerencia_model");
        $this->load->model("Cargo_model");
        
        
    }
    
    public function index()
    {
        
       
        
    }
    
    public function addclaims()
    {
        
        $data = new stdClass();
        $data->bancos =  $this->Banco_model->getBancos();
        $data->codigo =  $this->Banco_model->getBancos();
        $data->tipodocumentoidentidad =$this->TipoDocumentoIdentidad_model->getTipoDocumentoIdentidad();
        $data->proyecto =  $this->Proyecto_model->getProyecto();
        $data->gerencia =  $this->Gerencia_model->getGerencia();
        $data->cargo =  $this->Cargo_model->getCargos();
        
        
        // set validation rules
        $this->form_validation->set_rules('Nacionalidad', 'Nacionalidad', 'required', array('required' => 'El Campo Nacionalidad es requerido'));
        $this->form_validation->set_rules('Cedula', 'Cedula', 'required|numeric|min_length[5]|max_length[8]',array('required' => 'El Campo Cedula es requerido','numeric' => 'El Campo Cedula solo permite numeros','min_length' => 'El Campo Cedula debe indicar al menos 5 digitos','max_length' => 'El Campo Cedula debe indicar m�ximo 8 digitos'));
        $this->form_validation->set_rules('Nombre', 'Nombre', 'required|character|min_length[8]|max_length[30]',array('required' => 'El Campo Nombre es requerido','character' => 'El Campo Nombre solo permite letras','min_length' => 'El Campo Nombre debe indicar al menos 8 caracteres','max_length' => 'El Campo Nombre debe indicar m�ximo 30 caracteres'));
        $this->form_validation->set_rules('Apellido', 'Apellido', 'required|character|min_length[8]|max_length[40]',array('required' => 'El Campo Apellido es requerido','character' => 'El Campo Apellido solo permite letras','min_length' => 'El Campo Apellido debe indicar al menos 8 caracteres','max_length' => 'El Campo Apellido debe indicar m�ximo 40 caracteres'));
        $this->form_validation->set_rules('Telefono', 'Telefono', 'required|numeric|min_length[11]|max_length[11]',array('required' => 'El Campo Telefono es requerido','numeric' => 'El Campo Cedula solo permite numeros','min_length' => 'El Campo Telefono debe indicar al menos 10 digitos','max_length' => 'El Campo Telefono debe indicar m�ximo 11 digitos'));
        $this->form_validation->set_rules('Banco', 'Banco', 'required', array('required' => 'El Campo Banco es requerido'));
        $this->form_validation->set_rules('Codigo Banco', 'Codigo Banco', 'required', array('required' => 'El Campo Codigo Banco es requerido'));
        $this->form_validation->set_rules('Proyecto', 'Proyecto', 'required', array('required' => 'El Campo Proyecto es requerido'));
        $this->form_validation->set_rules('Gerencia', 'Gerencia', 'required', array('required' => 'El Campo Gerencia es requerido'));
        $this->form_validation->set_rules('Cargo', 'Cargo', 'required', array('required' => 'El Campo Cargo es requerido'));
        $this->form_validation->set_rules('Tipo Error', 'Tipo Error', 'required', array('required' => 'El Campo Tipo Error es requerido'));
        
        
        //validaci�n del captcha
         $this->form_validation->set_rules('g-recaptcha-response', '', 'required',array('required' => 'El Campo capcha es requerido'));
        
         
         
        
        if ($this->form_validation->run() == false) {
            
            // validation not ok, send validation errors to the view
            $this->load->view('templates/header');
            $this->load->view('templates/navigation');
            $this->load->view('claims/addclaim',$data);
            $this->load->view('templates/footer');
            
        } else {
            
            $this->Claims_model->addclaim($this->input->post);
            
            $nacionalidad = $this->input->post('nacionalidad');
            $cedula = $this->input->post('cedula');
            $nombre = $this->input->post('Nombre');
            $apellido = $this->input->post('Apellido');
            $telefono = $this->input->post('telefono');
            $correo = $this->input->post('correo');
            $banco = $this->input->post('banco');
            $codigobanco = $this->input->post('codigobanco');
            $proyecto = $this->input->post('proyecto');
            $gerencia = $this->input->post('gerencia');
            $cargo = $this->input->post('cargo');
            $tipoerror = $this->input->post('tipoerror');
            
            
            $this->load->view('templates/header');
            $this->load->view('templates/navigation');
            
            $this->load->model('claims_model');
            $result=$this->claims_model->addclaim($nacionalidad,$cedula,$nombre,$apellido,$telefono,$correoelectronico,$banco,$codigobanco,$proyecto,$gerencia,$cargo,$tipoerror);
            $dataPayments=array('consulta'=>$result);
            
            if($result != null){
                $this->load->view('claims/claim',$dataPayments);
            }

            $this->load->model('claims_model');
            $result=$this->claims_model->addclaim($nacionalidad,$cedula,$nombre,$apellido,$telefono,$correoelectronico,$banco,$codigobanco,$proyecto,$gerencia,$cargo,$tipoerror);
            $dataClaims=array('consulta'=>$result);
            
            if($result != null){
                $this->load->view('claims/claim',$dataClaims);
            }
            
            
            $this->load->view('templates/footer');
            

        }
        
    }
    public  function insertimagen(){
       $post = $this-> input->post();
       $this->load->model('file');
       $file_name = $this->file->uploadimage('','No es Posible Subir la Imagen');
       $this->post->insert($post);
       
    }
}

