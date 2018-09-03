<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Claim extends CI_Controller
{

    public function success()
    {
        $this->session->set_flashdata('success', 'User Updated successfully');
        return $this->load->view('myPages');
    }

    private $validation = true;
    private $data;

    /**
     * __construct function.
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        
        // Para impedir el acceso directo desde la URL
        // Validamos si es el path principal ? , si lo es deje accesar desde url
        if ($this->uri->uri_string()) {
            // Carga Libraria User_agent
            $this->load->library('user_agent');
            // Verifica si llega desde un enlace
            if ($this->agent->referrer()) {
                // Busca si el enlace llega de una URL diferente
                $post = strpos($this->agent->referrer(), base_url());
                if ($post === FALSE) {
                    // Podemos aqui crear un mensaje antes de redirigir que informe
                    redirect(base_url());
                }
            }            // Si no se llega desde un enlace se redirecciona al inicio
            else {
                // Podemos aqui crear un mensaje antes de redirigir que informe
                redirect(base_url());
            }
        }
        
        $this->load->library("session");
        
        $this->load->model('TiposCuentas_model');
        $this->load->model('Banco_model');
        $this->load->model('TipoDocumentoIdentidad_model');
        $this->load->model('TipoPago_model');
        $this->load->model('Cargo_model');
        $this->load->model('Claims_model');
        $this->load->model('Proyecto_model');
        $this->load->model('Gerencia_model');
        $this->load->model('Cargo_model');
        $this->load->model('Tipoerror_model');
        $this->load->model('TiposCuentas_model');
    }

    public function index()
    {
        if ($this->data === null){
            $data = new stdClass();
        }else{
            $data = $this->data;
        }
        
        $data->bancos = $this->Banco_model->getBancos();
        $data->tipodocumentoidentidad = $this->TipoDocumentoIdentidad_model->getTipoDocumentoIdentidad();
        $data->proyecto = $this->Proyecto_model->getProyecto();
        $data->gerencia = $this->Gerencia_model->getGerencia();
        $data->cargo = $this->Cargo_model->getCargos();
        $data->tipoerror = $this->Tipoerror_model->gettipoerror();
        $data->tiposcuentas = $this->TiposCuentas_model->gettiposcuentas();
        
        $this->load->view('templates/header');
        $this->load->view('templates/navigation', $data);
        $this->load->view('claims/addclaim');
        $this->load->view('templates/footer');
    }

    public function do_upload()
    {
        
        // // permite dejar los campos con la informacion sin ser borrada luego de las validaciones
        $data = new stdClass();
        $data->bancos = $this->Banco_model->getBancos();
        $data->tipodocumentoidentidad = $this->TipoDocumentoIdentidad_model->getTipoDocumentoIdentidad();
        $data->proyecto = $this->Proyecto_model->getProyecto();
        $data->gerencia = $this->Gerencia_model->getGerencia();
        $data->cargo = $this->Cargo_model->getCargos();
        $data->tipoerror = $this->Tipoerror_model->gettipoerror();
        $data->tiposcuentas = $this->TiposCuentas_model->gettiposcuentas();
        
        $data->id_tipo_documento_identidad = $this->input->post("id_tipo_documento_identidad");
        $data->documento_identidad = $this->input->post("documento_identidad");
        $data->nombre = $this->input->post("nombre");
        $data->apellido = $this->input->post("apellido");
        $data->telefono = $this->input->post("telefono");
        $data->correo = $this->input->post("correo");
        $data->id_banco = $this->input->post("id_banco");
        $data->id_tipos_cuentas = $this->input->post("id_tipos_cuentas");
        $data->numero_cuenta = $this->input->post("numero_cuenta");
        $data->id_proyecto = $this->input->post("id_proyecto");
        $data->id_gerencia = $this->input->post("id_gerencia");
        $data->id_cargo = $this->input->post("id_cargo");
        $data->id_tipo_error = $this->input->post("id_tipo_error");
        $data->cantidad_dias = $this->input->post("cantidad_dias");
        $data->soportereclamos = $this->input->post("file_name");
        
        $config['upload_path'] = './soportereclamostemp/';
        $config['allowed_types'] = '*';
        $config['max_size'] = 3000;
        $config['max_width'] = 1400;
        $config['max_height'] = 1000;
        $config['file_name'] = "SOPORTE" . now();
        $this->load->library('upload', $config);
        
        if (! $this->upload->do_upload('file_name')) {
            $data->error = $this->upload->display_errors();
        } else {
            $data->success = "Archivo cargado con &eacute;xito.";
            $data->soportereclamos = $this->upload->data("file_name");
        }
        
        $this->load->view('templates/header');
        $this->load->view('templates/navigation', $data);
        $this->load->view('claims/addclaim', $data);
        $this->load->view('templates/footer');
    }

    public function eliminar()
    {
        $data = new stdClass();
        $data->bancos = $this->Banco_model->getBancos();
        $data->tipodocumentoidentidad = $this->TipoDocumentoIdentidad_model->getTipoDocumentoIdentidad();
        $data->proyecto = $this->Proyecto_model->getProyecto();
        $data->gerencia = $this->Gerencia_model->getGerencia();
        $data->cargo = $this->Cargo_model->getCargos();
        $data->tipoerror = $this->Tipoerror_model->gettipoerror();
        $data->tiposcuentas = $this->TiposCuentas_model->gettiposcuentas();
        
        $data->id_tipo_documento_identidad = $this->input->post("id_tipo_documento_identidad");
        $data->documento_identidad = $this->input->post("documento_identidad");
        $data->nombre = $this->input->post("nombre");
        $data->apellido = $this->input->post("apellido");
        $data->telefono = $this->input->post("telefono");
        $data->correo = $this->input->post("correo");
        $data->id_banco = $this->input->post("id_banco");
        $data->id_tipos_cuentas = $this->input->post("id_tipos_cuentas");
        $data->numero_cuenta = $this->input->post("numero_cuenta");
        $data->id_proyecto = $this->input->post("id_proyecto");
        $data->id_gerencia = $this->input->post("id_gerencia");
        $data->id_cargo = $this->input->post("id_cargo");
        $data->id_tipo_error = $this->input->post("id_tipo_error");
        $data->cantidad_dias = $this->input->post("cantidad_dias");
        
        $this->load->view('templates/header');
        $this->load->view('templates/navigation', $data);
        $this->load->view('claims/addclaim', $data);
        $this->load->view('templates/footer');
    }

    public function addclaims()
    {
        $data = new stdClass();
        $data->bancos = $this->Banco_model->getBancos();
        $data->tipodocumentoidentidad = $this->TipoDocumentoIdentidad_model->getTipoDocumentoIdentidad();
        $data->proyecto = $this->Proyecto_model->getProyecto();
        $data->gerencia = $this->Gerencia_model->getGerencia();
        $data->cargo = $this->Cargo_model->getCargos();
        $data->tipoerror = $this->Tipoerror_model->gettipoerror();
        $data->tiposcuentas = $this->TiposCuentas_model->gettiposcuentas();
        
        // /
        
        // // permite dejar los campos con la informacion sin ser borrada luego de las validaciones
        
        $data->id_tipo_documento_identidad = $this->input->post("id_tipo_documento_identidad");
        $data->documento_identidad = $this->input->post("documento_identidad");
        $data->nombre = $this->input->post("nombre");
        $data->apellido = $this->input->post("apellido");
        $data->telefono = $this->input->post("telefono");
        $data->correo = $this->input->post("correo");
        $data->id_banco = $this->input->post("id_banco");
        $data->id_tipos_cuentas = $this->input->post("id_tipos_cuentas");
        $data->numero_cuenta = $this->input->post("numero_cuenta");
        $data->id_proyecto = $this->input->post("id_proyecto");
        $data->id_gerencia = $this->input->post("id_gerencia");
        $data->id_cargo = $this->input->post("id_cargo");
        $data->id_tipo_error = $this->input->post("id_tipo_error");
        $data->cantidad_dias = $this->input->post("cantidad_dias");
        $data->soportereclamos = $this->input->post("soportereclamos");
        $data->comentario = $this->input->post("comentario");
        
        // set validation rules
        
        $this->form_validation->set_rules('id_tipo_documento_identidad', 'id_tipo_documento_identidad', 'required', array(
            'required' => 'El Campo Nacionalidad es requerido'
        ));
        $this->form_validation->set_rules('documento_identidad', 'documento_identidad', 'trim|required|numeric|min_length[5]|max_length[9]', array(
            'required' => 'El Campo Cedula es requerido',
            'numeric' => 'El Campo Cedula solo permite numeros',
            'min_length' => 'El Campo Cedula debe indicar al menos 5 digitos',
            'max_length' => 'El Campo Cedula debe indicar maximo 9 digitos'
        ));
        $this->form_validation->set_rules('nombre', 'nombre', 'trim|required|alpha_spaces|min_length[3]|max_length[30]', array(
            'required' => 'El Campo Nombre es requerido',
            'alpha_spaces' => 'El Campo Nombre solo permite Letra',
            'min_length' => 'El Campo Nombre debe indicar al menos 3 caracteres',
            'max_length' => 'El Campo Nombre debe indicar maximo 30 caracteres'
        ));
        $this->form_validation->set_rules('apellido', 'apellido', 'trim|required|alpha_spaces|min_length[3]|max_length[30]', array(
            'required' => 'El Campo Apellido es requerido',
            'alpha_spaces' => 'El Campo Apellido solo permite Letras',
            'min_length' => 'El Campo Apellido debe indicar al menos 8 caracteres',
            'max_length' => 'El Campo Apellido debe indicar maximo 40 caracteres'
        ));
        $this->form_validation->set_rules('telefono', 'telefono', 'trim|required|numeric|exact_length[11]', array(
            'required' => 'El Campo Telefono es requerido',
            'numeric' => 'El Campo Telefono solo permite numeros',
            'exact_length' => 'El Campo Telefono debe indicar 11 digitos'
        ));
        $this->form_validation->set_rules('correo', 'correo', 'trim|required|valid_email|min_length[8]|max_length[30]', array(
            'required' => 'El Campo Correo es requerido',
            'valid_email' => 'El campo Correo debe ser Valido con @/.com',
            'min_length' => 'El Campo Correo debe tenr un minimo de 8 caracteres',
            'max_length' => 'El Campo Correo debe tener un maximo 30 caracteres'
        ));
        $this->form_validation->set_rules('confirmacion', 'confirmacion', 'trim|required|matches[correo]', array(
            'required' => 'El Campo Confirmacion es requerido',
            'matches' => 'El Campo Confirmacion Correo Electronico debe coincidir con Correo Electronico'
        ));
        $this->form_validation->set_rules('id_banco', 'id_banco', 'required', array(
            'required' => 'El Campo Banco es requerido'
        ));
        $this->form_validation->set_rules('id_tipos_cuentas', 'id_tipos_cuentas', 'required', array(
            'required' => 'El Campo Tipo de Cuenta es requerido'
        ));
        $this->form_validation->set_rules('numero_cuenta', 'numero_cuenta', 'trim|required|numeric|exact_length[20]', array(
            'required' => 'El N&uacute;mero de cuenta es requerido',
            'numeric' => 'El N&uacute;mero de Cuenta solo permite numeros',
            'exact_length' => 'El N&uacute;mero de Cuenta debe ser de 20 d&iacute;gitos'
        ));
        $this->form_validation->set_rules('id_proyecto', 'id_proyecto', 'required', array(
            'required' => 'El Campo Proyecto es requerido'
        ));
        $this->form_validation->set_rules('id_gerencia', 'id_gerencia', 'required', array(
            'required' => 'El Campo Gerencia es requerido'
        ));
        $this->form_validation->set_rules('id_cargo', 'id_cargo', 'required', array(
            'required' => 'El Campo Cargo es requerido'
        ));
        $this->form_validation->set_rules('id_tipo_error', 'id_tipo_error', 'required', array(
            'required' => 'El Campo Tipo Error es requerido'
        ));
        $this->form_validation->set_rules('cantidad_dias', 'cantidad_dias', 'trim|required|numeric', array(
            'required' => 'El Campo Dias trabajados es requerido',
            'numeric' => 'El Campo Dias Trabajados solo permite numeros'
        ));
        
        // validaci�n del captcha
         $this->form_validation->set_rules('g-recaptcha-response', '', 'required',array('required' => 'El Campo capcha es requerido'));
        $codbanco = $this->Banco_model->getBancosbyId($this->input->post("id_banco"));
        $validate = true;
        
        foreach ($codbanco->result() as $records) {
            if ($records->codigo != substr($this->input->post("numero_cuenta"), 0, 4)) {
                $data->error = "El Nro. de Cuenta  no coincide con el Banco seleccionado.<br>";
                $validate = false;
            }
        }
        
        // validaci�n del captcha FUNCIONA CON INTERNET
        // $this->form_validation->set_rules('g-recaptcha-response', '', 'required',array('required' => 'El Campo capcha es requerido'));
        
        if ($this->form_validation->run() == false || $validate == false) {
            
            // validation not ok, send validation errors to the view
            $this->load->view('templates/header');
            $this->load->view('templates/navigation', $data);
            $this->load->view('claims/addclaim', $data);
            $this->load->view('templates/footer');
        } else {
            
            $this->Claims_model->addclaims($this->input->post);
            
            if ($this->input->post('soportereclamos')) {
                copy("./soportereclamostemp/" . $this->input->post('soportereclamos'), "./soportereclamos/" . $this->input->post('soportereclamos'));
                unlink("./soportereclamostemp/" . $this->input->post('soportereclamos'));
            }
            
            $nacionalidad = $this->input->post('id_tipo_documento_identidad');
            $cedula = $this->input->post('documento_identidad');
            $nombre = $this->input->post('nombre');
            $apellido = $this->input->post('apellido');
            $telefono = $this->input->post('telefono');
            $correo = $this->input->post('correo');
            $banco = $this->input->post('id_banco');
            $numerocuenta = $this->input->post('numero_cuenta');
            $proyecto = $this->input->post('id_proyecto');
            $gerencia = $this->input->post('id_gerencia');
            $cargo = $this->input->post('id_cargo');
            $tipoerror = $this->input->post('id_tipo_error');
            $cantidaddias = $this->input->post('cantidad_dias');
            $soportereclamos = $this->input->post('soportereclamos');
            $comentario = $this->input->post('comentario');
            
            $this->input->post = "";
            $data->id_tipo_documento_identidad = "";
            $data->documento_identidad = "";
            $data->nombre = "";
            $data->apellido = "";
            $data->telefono = "";
            $data->correo = "";
            $data->id_banco = "";
            $data->id_tipos_cuentas = "";
            $data->numero_cuenta = "";
            $data->id_proyecto = "";
            $data->id_gerencia = "";
            $data->id_cargo = "";
            $data->id_tipo_error = "";
            $data->cantidad_dias = "";
            $data->file_name = "";
            $data->soportereclamos = "";
            $data->comentario=""; 
    
            $this->data = new stdClass();
      
            $this->data->success = "Reclamo Enviado con Exito";
    
            //redirect ("claim/addclaims") ;
           //$this->load->view('templates/navigation', $data);
           //$this->load->view('claims/addclaim', $data);
           //$this->load->view('templates/footer');
            
            $this->index();
            //redirect(base_url() . "index.php/claim/addclaims");
            
            /*
             * $data->success = "Reclamo Enviado con Exito";
             * $this->load->view('templates/header');
             * $this->load->view('templates/navigation',$data);
             * $this->load->view('claims/addclaim',$data);
             * $this->load->view('templates/footer');
             */
        }
    }

    public function checkclaims()
    {
        $this->load->model('claims_model');
        $data['query'] = $this->claims_model->checkclaims();
        $this->load->view('templates/header');
        $this->load->view('templates/navigation');
        $this->load->view('claims/checkclaims', $data);
        
        $this->load->view('templates/footer');
    }

    public function details($idreclamo)
    {
        $data = new stdClass();
        $this->load->model('claims_model');
        $this->load->model('estatusReclamo_model');
        
        $data->query = $this->claims_model->details($idreclamo);
        $data->estatusReclamo = $this->estatusReclamo_model->geteEstatus($idreclamo);

        $this->load->view('templates/header');
        $this->load->view('templates/navigation');
        $this->load->view('claims/details', $data);
        $this->load->view('templates/footer');
    }

    public function download($archivosoporte = null)
    {
     
            force_download('soportereclamos/' . $archivosoporte, NULL);
    
    
    }
    public function updateClaim(){
        $this->load->model('claims_model');
        $result = $this->claims_model->updateClaim($this->input->post("idreclamo"), $this->input->post("estatus_reclamo"),  $this->input->post("comentario_gerencia"));
        if($result)
        {
            //exito
            $this->checkclaims();
        }else{
            //No
            $this->checkclaims();
        }
        
    }
  
    
}

