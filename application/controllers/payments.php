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
        $this->load->library('pagination');
        //$this->load->library('common_validation');
        $this->load->model('payments_model');
        $this->load->model('TiposCuentas_model');
        $this->load->model('Banco_model');
        $this->load->model('TipoDocumentoIdentidad_model');
        $this->load->model('TipoPago_model');
        $this->load->model('DuracionCheque_model');
        $this->load->model('Cargo_model');
        
    }
    
    public function index()
    {
        
    }
    
    public function load(){

        
        $data = new stdClass();
        $params = array();
        $data->beneficiario = "";
        
        $this->form_validation->set_rules('beneficiario', 'beneficiario', 'required|alpha_numeric_spaces', array('required' => 'El Campo Beneficiario es requerido','alpha_numeric_spaces' => 'El Campo Beneficiario no debe contener caracteres especiales ni numeros'));
        
        //cargamos los datos que van en los select del formulario
        $data->tiposcuentas =  $this->TiposCuentas_model->getTiposCuentas();
        $data->bancos =  $this->Banco_model->getBancos();
        $data->TipoDocumentoIdentidad =  $this->TipoDocumentoIdentidad_model->getTipoDocumentoIdentidad();
        $data->TipoPago =  $this->TipoPago_model->getTipoPago();
        $data->DuracionCheque =  $this->DuracionCheque_model->getDuracionCheque();
        $data->Cargo =  $this->Cargo_model->getCargos();
        
        
        //$data->tiposcuentas = $consulta;
       

        $data->tab ="1";
        
        // si ya existe una tabla temporal en memoria, cargamos la paginación
       
        if (isset($_SESSION['table_temp_nom']) ) {
            // init params
            
            $tablename=$_SESSION['table_temp_nom'];
            
            $start_index = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
            $total_records = $this->payments_model->get_total($tablename);
            
            // load config file
            $this->config->load('pagination', TRUE);
            $settings = $this->config->item('pagination');
            $settings['total_rows'] = $total_records;
            $settings['base_url'] = base_url().'payments/load';
            
            if ($total_records > 0)
            {
                // get current page records
                $params["results"] = $this->payments_model->get_current_page_records($tablename,$settings['per_page'], $start_index);
                
                
                $params["results"]=$this->validateCSV($params["results"] ,  
                                                        $data->Cargo, $data->TipoDocumentoIdentidad, 
                                                        $data->tiposcuentas,
                                                        $data->TipoPago,
                                                        $data->bancos,
                                                        $data->DuracionCheque);
                
                
                // use the settings to initialize the library
                $this->pagination->initialize($settings);
                
                // build paging links
                $params["links"] = $this->pagination->create_links();
                
                
                
                
            }
            
            
        }
        
        if ($this->form_validation->run() == false) {
            
            $this->load->view('templates/header');
            $this->load->view('templates/Navigation');
            $this->load->view('checkpayments/load',$data);
            if (isset($_SESSION['table_temp_nom']) ) {
                $this->load->view('checkpayments/paymentloadgrid',$params);
            }
            $this->load->view('templates/footer');
            
        }else{
            $data->beneficiario = $this->input->post('beneficiario');
            $this->load->view('templates/header');
            $this->load->view('templates/Navigation');
            $this->load->view('checkpayments/load',$data);
            if (isset($_SESSION['table_temp_nom']) ) {
                $this->load->view('checkpayments/paymentloadgrid',$params);
            }
            $this->load->view('templates/footer');
            
        }
        
        
    }
    
    

    
    public function do_upload(){
        $data = new stdClass();
        $config['upload_path']          = './uploads/';
        $config['allowed_types']        = 'csv';
        $config['max_size']             = 100;
        $config['max_width']            = 1024;
        $config['max_height']           = 768;
        
        $this->load->library('upload', $config);
        
        $data->tab ="2";
        

        if (!$this->upload->do_upload('userfile')){
            $data->tab ="2";
            $data->error = $this->upload->display_errors();
            $this->load->view('templates/header');
            $this->load->view('templates/Navigation',$data);
            $this->load->view('checkpayments/load',$data);
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
                    );

                    //validamos el registro leido
                    //$row = $this->validateCSV($row);
                    
                    //agregamos el registro en el arreglo
                    array_push($data->records,$row);

                }
                $num = count ($datafile);
                $i++;
            }
            
            //insertamos en la tabla temporal el arreglo con los registros
            $this->payments_model->insertTablepaymentsTem($tablename,$data->records);
            
                       
            // cerramos el archivo
            fclose ($fp);
            
            
            
            $_SESSION['table_temp_nom'] = $tablename;
            
            /*
             * 
             * Agrgar c�digo para eliminar el archivo cargado, ya que lo tenemos en BD
             * 
             */
            
            redirect(base_url().'payments/load');
            
        }
    }
    
    
    /**
     * validateCSV function.
     *
     * @access private
     * @param mixed $data
     * @return $data
     */
    private function validateCSV($params, $cargo, $TipoDocumentoIdentidad, $tiposcuentas, $tipoPago, $bancos, $duracionCheque){
        
        $paramsResult = array();
       // $regisResult = array();
        $message = "";
        
        foreach ($params as $validateparams) {

            //validación del Campo Beneficiario
            $this->form_validation->alpha_spaces($validateparams->beneficiario) == true ? $validateparams->vbeneficiario = true : $validateparams->vbeneficiario = false;
            
            
            //validación del ampo Referencia
            
            /*
             * 
             * Agregar Validación
             * 
             */
            
            
            //validación del Cargo
            $validatecargo = false;
            foreach ($cargo->result() as $vcargo){
                if( $validateparams->id_cargo == $vcargo->id){
                    $validatecargo = true;
                }
            }
            $validateparams->vcargo = $validatecargo;
            
            //Validadcion Tipo Documento Identidad
            $validatetipodoc = false;
            foreach ($TipoDocumentoIdentidad->result() as $vTipoDocumentoIdentidad){
                if( $validateparams->id_tipo_documento_identidad == $vTipoDocumentoIdentidad->nombre){
                    $validatetipodoc = true;
                }
            }
            $validateparams->vid_tipo_documento_identidad = $validatetipodoc;
            
            
            //validamos que la cédula no este repetida dentro del misnmo archivo
            $repit = 0;
            foreach ($params as $validateparamsrepit){
                if ($validateparamsrepit->documento_identidad == $validateparams->documento_identidad)
                    $repit ++ ;
            }
            
            $validateparams->vrdocumento_identidad = $repit;
            //Validacion Documeto Identidad
            ($this->form_validation->numeric($validateparams->documento_identidad) 
                && strlen($validateparams->documento_identidad ) <= 8 
                && $this->form_validation->required($validateparams->documento_identidad)) == true 
                    ? $validateparams->vdocumento_identidad = true 
                    : $validateparams->vdocumento_identidad = false;

            //Validamos el campo tipo de cuenta
            $validatecargo = false;
            foreach ($tiposcuentas->result() as $vtiposcuentas){
                if( $validateparams->id_tipo_cuenta == $vtiposcuentas->tipo){
                    $validatecargo = true;
                }
            }
            $validateparams->vid_tipo_cuenta = $validatecargo;
                    
            //validacion del nuero de cuenta
            ($this->form_validation->numeric($validateparams->numero_cuenta)
            && strlen($validateparams->numero_cuenta ) == 20
            && $this->form_validation->required($validateparams->numero_cuenta)) == true
            ? $validateparams->vnumero_cuenta = true
            : $validateparams->vnumero_cuenta = false;
            
            
            //Validacion del monto
            ($this->form_validation->numeric($validateparams->credito)
            && $this->form_validation->required($validateparams->credito)) == true
            ? $validateparams->vcredito = true
            : $validateparams->vcredito = false;
            
            //validacion tipo de pago
            $validatetipopago = false;
            foreach ($tipoPago->result() as $vtipoPago){
                if( $validateparams->id_tipo_pago == $vtipoPago->id || $validateparams->id_tipo_pago == null){
                    $validatetipopago = true;
                }
            }
            $validateparams->vid_tipo_pago = $validatetipopago;
            
            //Validacion del Banco
            $validatebanco = false;
            foreach ($bancos->result() as $vbancos){
                if( $validateparams->id_banco == $vbancos->id){
                    $validatebanco = true;
                }
            }
            $validateparams->vid_banco = $validatebanco;
            
            
            //Validacion Duración Cheque
            $validatebanco = false;
            foreach ($duracionCheque->result() as $vduracionCheque){
                if( $validateparams->id_duracion_cheque == $vduracionCheque->duracion){
                    $validatebanco = true;
                }
            }
            $validateparams->vid_duracion_cheque = $validatebanco;
            
            
            //validación email
            ($this->form_validation->valid_email($validateparams->correo_beneficiario)) == true
            ? $validateparams->vcorreo_beneficiario = true
            : $validateparams->vcorreo_beneficiario = false;
            
            
            array_push($paramsResult,$validateparams);
            
        }
        

        return $paramsResult;
    }
    
    
}

