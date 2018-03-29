<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Payments extends CI_Controller {
    
    
    private $tiposcuentas = array();
    private $bancos = array();
    private $TipoDocumentoIdentidad = array();
    private $TipoPago = array();
    private $DuracionCheque = array();
    private $Cargo = array();
    //cantidad de registros a consultar
    private $per_page = 10;
    private $validation = true;
    private $descripcion = true;
    private $id_gerencia = true;
    private $id_proyecto = true;
    

    
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
        $this->load->library('pagination');
        //$this->load->library('common_validation');
        $this->load->model('payments_model');
        $this->load->model('TiposCuentas_model');
        $this->load->model('Banco_model');
        $this->load->model('TipoDocumentoIdentidad_model');
        $this->load->model('TipoPago_model');
        $this->load->model('DuracionCheque_model');
        $this->load->model('Cargo_model');
        $this->load->model('Proyecto_model');
        $this->load->model('Gerencia_model');
        
    }
    
    public function index()
    {
        
    }
    
    
    

    
    public function do_upload(){
        
        if (isset($_SESSION['table_temp_nom'])){
               $this->payments_model->deleteTablepaymentsTem($_SESSION['table_temp_nom']);
                unset($_SESSION['table_temp_nom']);
        
        }
        if (isset($_SESSION['start_index_load_payment'])){
            unset($_SESSION['start_index_load_payment']);
        }
        
        $data = new stdClass();
        $config['upload_path']          = './uploads/';
        $config['allowed_types']        = 'csv';
        $config['max_size']             = 100;
        $config['max_width']            = 1024;
        $config['max_height']           = 768;
        
        $this->load->library('upload', $config);
        

        if (!$this->upload->do_upload('userfile')){
            $data->tab ="2";
            $data->error = $this->upload->display_errors();
            $this->load->view('templates/header');
            $this->load->view('templates/Navigation',$data);
            $this->load->view('payments/paymentsload/loadgrid',$data);
            $this->load->view('templates/footer');
        }
        else{
            //$data->upload = array('upload_data' => $this->upload->data());
            
            //cargamos el arcivo
            $file = $this->upload->data('file_path').$this->upload->data('file_name');
            
            //abrimos el archivo
            $fp = fopen ($file,"r");
            
            $data->records = array();
                      
            
            $tablename = "detalle_nomina_temp".now();
           
            //Creamos una tabla temporal para cargar los archivos
            $this->payments_model->createTablepaymentsTem($tablename);
            
            $i = 0;
            while ($datafile = fgetcsv ($fp, 1000, ";")) {
               
                //descartamos la primera linea del archivo que contiene los nembres de los campos
                if (!$i == 0){
                    
                    //quitamos los caracteres especiales del nombre y convertimos todo a Mayuscula
                    $datafile[0] = strtoupper($this->form_validation->stripAccents($datafile[0]));
                    
                    // creamos el arreglo con la informaci�n de cada registro que se pasara al metodo de inseci�n de datos en la 
                    // tabla temporal
                    $row = array("beneficiario"=> $datafile[0],
                        "referencia_credito"=> $datafile[1],
                        "id_cargo"=> $datafile[2],
                        "id_tipo_documento_identidad"=> $datafile[3],
                        "documento_identidad"=> $datafile[4],
                        "id_tipo_cuenta"=> $datafile[5],
                        "numero_cuenta"=> $datafile[6],
                        "credito"=> $datafile[7],
                        "id_tipo_pago"=> $datafile[8],
                        "id_banco"=> $datafile[9],
                        "id_duracion_cheque"=> $datafile[10],
                        "correo_beneficiario"=> $datafile[11],
                        "fecha"=> $datafile[12],
                        "id_estatus"=>1,
                    );

                    
                    //agregamos el registro en el arreglo
                    array_push($data->records,$row);

                }
                $i++;
            }
            
            //insertamos en la tabla temporal el arreglo con los registros
            $this->payments_model->insertTablepaymentsTem($tablename,$data->records);
            
                       
            // cerramos el archivo
            fclose ($fp);

            // eliminamos el archivo cargado
            unlink($this->upload->data('file_path').$this->upload->data('file_name'));
            
            
            
            $_SESSION['table_temp_nom'] = $tablename;
            
           
            redirect(base_url().'payments/loadgrid');
            
        }
    }
    
    
   
    
    
    
    /**
     * validateCSV function.
     *
     * @access private
     * @param mixed $data
     * @return $data
     */
    private function validateCSV($params){
        
        
        
        $paramsResult = array();
        
        $this->validation = true;
        
        foreach ($params as $validateparams) {
            
            //validación del Campo Beneficiario
            //$this->form_validation->alpha_spaces($validateparams->beneficiario) == true ? $validateparams->vbeneficiario = true : $validateparams->vbeneficiario = false;
            
            
            
            if ( $this->form_validation->alpha_spaces($validateparams->beneficiario) == true){
                $validateparams->vbeneficiario = true;
            }else{
                $validateparams->vbeneficiario = false;
                $this->validation = false;
            }
            
            
            //validación del campo Referencia
            
            /*
             *
             * Agregar Validación
             *
             */
            
            
            //validación del Cargo
            $validatecargo = false;
            foreach ($this->Cargo->result() as $vcargo){
                if( $validateparams->id_cargo == $vcargo->id){
                    $validatecargo = true;
                }
            }
            $validateparams->vcargo = $validatecargo;
            
            $validatecargo == false ? $this->validation = false :  $this->validation = $this->validation;
            
            //Validadcion Tipo Documento Identidad
            $validatetipodoc = false;
            foreach ($this->TipoDocumentoIdentidad->result() as $vTipoDocumentoIdentidad){
                if( $validateparams->id_tipo_documento_identidad == $vTipoDocumentoIdentidad->nombre){
                    $validatetipodoc = true;
                }
            }
            $validateparams->vid_tipo_documento_identidad = $validatetipodoc;
            $validatetipodoc == false ? $this->validation = false :  $this->validation = $this->validation;
            
            
            //validamos que la cédula no este repetida dentro del misnmo archivo
            $repit = 0;
            foreach ($params as $validateparamsrepit){
                if ($validateparamsrepit->documento_identidad == $validateparams->documento_identidad)
                    $repit ++ ;
            }
            
            $validateparams->vrdocumento_identidad = $repit;
            $repit > 1 ? $this->validation = false :  $this->validation = $this->validation;
            
            //Validacion Documeto Identidad
            ($this->form_validation->numeric($validateparams->documento_identidad)
            && strlen($validateparams->documento_identidad ) <= 8
            && $this->form_validation->required($validateparams->documento_identidad)) == true
            ? $validateparams->vdocumento_identidad = true
            : $validateparams->vdocumento_identidad = false;
           
            $validateparams->vdocumento_identidad == false ? $this->validation = false :  $this->validation = $this->validation;
            
            
            //Validamos el campo tipo de cuenta
            $validatecargo = false;
            foreach ($this->tiposcuentas->result() as $vtiposcuentas){
                if( $validateparams->id_tipo_cuenta == $vtiposcuentas->tipo){
                    $validatecargo = true;
                }
            }
            $validateparams->vid_tipo_cuenta = $validatecargo;
            $validateparams->vid_tipo_cuenta == false ? $this->validation = false :  $this->validation = $this->validation;
            
            //validacion del nuero de cuenta
            ($this->form_validation->numeric($validateparams->numero_cuenta)
            && strlen($validateparams->numero_cuenta ) == 20
            && $this->form_validation->required($validateparams->numero_cuenta)) == true
            ? $validateparams->vnumero_cuenta = true
            : $validateparams->vnumero_cuenta = false;
            
            $validateparams->vnumero_cuenta == false ? $this->validation = false :  $this->validation = $this->validation;
            
            
            //Validacion del monto
            ($this->form_validation->numeric($validateparams->credito)
            && $this->form_validation->required($validateparams->credito)) == true
            ? $validateparams->vcredito = true
            : $validateparams->vcredito = false;
            $validateparams->vcredito == false ? $this->validation = false :  $this->validation = $this->validation;
            
            //validacion tipo de pago
            $validatetipopago = false;
            foreach ( $this->TipoPago->result() as $vtipoPago){
                if( $validateparams->id_tipo_pago == $vtipoPago->id || $validateparams->id_tipo_pago == null){
                    $validatetipopago = true;
                }
            }
            $validateparams->vid_tipo_pago = $validatetipopago;
            $validateparams->vid_tipo_pago == false ? $this->validation = false :  $this->validation = $this->validation;
            
            //Validacion del Banco
            $validatebanco = false;
            foreach ($this->bancos->result() as $vbancos){
                if( $validateparams->id_banco == $vbancos->id){
                    $validatebanco = true;
                }
            }
            $validateparams->vid_banco = $validatebanco;
            $validateparams->vid_banco == false ? $this->validation = false :  $this->validation = $this->validation;
            
            //Validacion Duración Cheque
            $validateduracioncheque = false;
            if ($validateparams->id_duracion_cheque != ""){
                foreach ($this->DuracionCheque->result() as $vduracionCheque){
                    if( $validateparams->id_duracion_cheque == $vduracionCheque->duracion){
                        $validateduracioncheque = true;
                    }
                }
            }else{
                $validateduracioncheque = true;
            }
            $validateparams->vid_duracion_cheque = $validateduracioncheque;
            $validateparams->vid_duracion_cheque == false ? $this->validation = false :  $this->validation = $this->validation;
            
            
            //validación email
            if ($validateparams->correo_beneficiario != ""){
                ($this->form_validation->valid_email($validateparams->correo_beneficiario)) == true
                ? $validateparams->vcorreo_beneficiario = true
                : $validateparams->vcorreo_beneficiario = false;
            }else{
                $validateparams->vcorreo_beneficiario = true;
            }
            $validateparams->vcorreo_beneficiario == false ? $this->validation = false :  $this->validation = $this->validation;
            
            array_push($paramsResult,$validateparams);
        }
        
        
        return $paramsResult;
    }
    
    public function induvidualload(){
        
        
    }
    
    public function loadgrid(){
        
        $start_index=0;
        $tablename ="";
        $total_records =0;
        $queryresult = array();
        $params = array();
        
        $data = new stdClass();
        
        
        //estoy hay que cargarlo en memoria para no estar consultando cada vez que se necesite.
        $this->tiposcuentas =  $this->TiposCuentas_model->getTiposCuentas();
        $this->bancos =  $this->Banco_model->getBancos();
        $this->TipoDocumentoIdentidad =  $this->TipoDocumentoIdentidad_model->getTipoDocumentoIdentidad();
        $this->TipoPago =  $this->TipoPago_model->getTipoPago();
        $this->DuracionCheque =  $this->DuracionCheque_model->getDuracionCheque();
        $this->Cargo =  $this->Cargo_model->getCargos();
        
        $data->tiposcuentas =  $this->tiposcuentas;
        $data->bancos =  $this->bancos;
        $data->TipoDocumentoIdentidad =  $this->TipoDocumentoIdentidad;
        $data->TipoPago =  $this->TipoPago;
        $data->DuracionCheque =  $this->DuracionCheque;
        $data->Cargo =  $this->Cargo;
        $data->proyecto =  $this->Proyecto_model->getProyecto();
        $data->gerencia =  $this->Gerencia_model->getGerencia();
        
        
        
        $data->descripcionnomina = $this->input->post("descripcion");
        $data->id_proyecto = $this->input->post("id_proyecto");
        $data->id_gerencia = $this->input->post("id_gerencia");
        
        
        $this->form_validation->set_rules('descripcion', 'descripcion', 'required', array('required' => 'El Campo Descripci&oacute;n N&oacute;mina es requerido'));
        $this->form_validation->set_rules('id_proyecto', 'id_proyecto', 'required', array('required' => 'El Campo Proyecto es requerido'));
        $this->form_validation->set_rules('id_gerencia', 'id_gerencia', 'required', array('required' => 'El Campo Gerencia es requerido'));
        
        
        
        //consultamos de sesion el nombre de la tabla temporal que se esta trabajando.
        if (isset($_SESSION['table_temp_nom']) ) {
            $tablename=$_SESSION['table_temp_nom'];
            $total_records = $this->payments_model->get_total($tablename);
        }
        
        if (isset($_SESSION['start_index_load_payment']) ) {
            $start_index = $_SESSION['start_index_load_payment'];
        }
        if (($start_index + $this->per_page) >= $total_records){
            $params["guardar"] = true;
        }
        
        
        
        //cantidad de registros esperadpos en el formulario
        $cuount =$this->input->post ("cantreg");
        
        $inicio =$this->input->post ("inicio");
        
        $datarow = array();
        $row = array();
        for ($i = 0; $i< $cuount; $i++){
            $beneficiario = strtoupper($this->form_validation->stripAccents( $this->input->post ("beneficiario".$inicio)));
            $row =[
                "id"=> $this->input->post ("id".$inicio),
                "beneficiario"=>$beneficiario,
                "id_cargo"=> $this->input->post ("id_cargo".$inicio),
                "referencia_credito"=> $this->input->post ("referencia_credito".$inicio),
                "id_tipo_documento_identidad"=>$this->input->post ("id_tipo_documento_identidad".$inicio),
                "documento_identidad"=> $this->input->post ("documento_identidad".$inicio),
                "id_tipo_cuenta"=> $this->input->post ("id_tipo_cuenta".$inicio),
                "numero_cuenta"=> $this->input->post ("numero_cuenta".$inicio),
                "credito"=> $this->input->post ("credito".$inicio),
                "id_tipo_pago"=> $this->input->post ("id_tipo_pago".$inicio),
                "id_banco"=> $this->input->post ("id_banco".$inicio),
                "id_duracion_cheque"=> $this->input->post ("id_duracion_cheque".$inicio),
                "correo_beneficiario"=> $this->input->post ("correo_beneficiario".$inicio),
                "fecha"=> $this->input->post ("fecha".$inicio),
            ];
            $inicio ++;
            array_push($datarow,$row);
        }
        $this->payments_model->updateTableTem( $tablename,$datarow);
        
        
        if ($total_records > 0){
            $params["results"] = $this->payments_model->get_current_page_records($tablename,$this->per_page, $start_index);
            $params["results"]=$this->validateCSV( $params["results"]);
        }
        
        if($this->validation && ($start_index + $this->per_page < $total_records)){
            $_SESSION['start_index_load_payment'] = $start_index + $this->per_page;
        }
        
        
        
        if (!$this->validation){
            $data->error = 'Error. Valide todos los campos que est&eacuten; resaltados en rojo en la tabla, para poder continuar.';
        }
        
        
        
        if ($this->form_validation->run()&&($start_index + $this->per_page > $total_records) && $this->validation){
           // redirect(base_url().'payments/guardar');
            $this->guardar($this->input->post('descripcion'), $this->input->post('id_proyecto'), $this->input->post('id_gerencia'));
            
        }else{
                $this->load->view('templates/header');
                $this->load->view('templates/Navigation',$data);
                $this->load->view('payments/paymentsload/loadgrid',$params);
                $this->load->view('templates/footer');
            }
    }
    
    public function guardar($descripcion, $proyecto, $gerencia){
        
        
        $data = new stdClass();
        
        
        if (isset($_SESSION['table_temp_nom']) ) {
            $detalle =$this->payments_model->getTablepaymentsTem($_SESSION['table_temp_nom']);
           // $this->payments_model->insertPayment($this->input->post("descripcion"),$this->input->post("id_proyecto"),$this->input->post("id_gerencia"),$_SESSION['id'],$detalle);
           $resultado =  $this->payments_model->insertPayment($descripcion,$proyecto,$gerencia, $_SESSION['id'],$detalle->result());
           if ($resultado){
               $data->success = 'Se ha crado con &Eacutexito la nomina.';
           }else{
               $data->error = 'Ha acorrido un error inesperado, por favor intente de nuevo.';
           }
        }else{
            $data->error = 'Ha acorrido un error inesperado, tabla origen no encontrada. Por favor intente de nuevo.';
        }
        
        if (isset($_SESSION['table_temp_nom'])){
            $this->payments_model->deleteTablepaymentsTem($_SESSION['table_temp_nom']);
            unset($_SESSION['table_temp_nom']);
            
        }
        if (isset($_SESSION['start_index_load_payment'])){
            unset($_SESSION['start_index_load_payment']);
        }
        
        $this->load->view('templates/header');
        $this->load->view('templates/Navigation',$data);
        $this->load->view('payments/paymentsload/loadgrid');
        $this->load->view('templates/footer');
        
    }
    
}

