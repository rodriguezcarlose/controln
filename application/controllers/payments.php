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
        
        
        //inicio de la consulta hacia la BD
        $start_index = 0;
        
        //cantidad de registros a consultar
        $per_page = 10;
        
//        $this->form_validation->set_rules('beneficiario', 'beneficiario', 'required|alpha_numeric_spaces', array('required' => 'El Campo Beneficiario es requerido','alpha_numeric_spaces' => 'El Campo Beneficiario no debe contener caracteres especiales ni numeros'));
        
        //cargamos los datos que van en los select del formulario
       
        
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
        
        
        //$data->tiposcuentas = $consulta;
       

        $data->tab ="1";
        
            
        
        if (isset($_SESSION['table_temp_nom']) ) {
            
            if (isset($_SESSION['start_index_load_payment']) ){
                $start_index = $_SESSION['start_index_load_payment'] + $this->per_page;
            }

            $tablename=$_SESSION['table_temp_nom'];
            
           // $start_index = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
            $total_records = $this->payments_model->get_total($tablename);
            
            if (($start_index + $this->per_page) > $total_records){
                
                $params["guardar"] = true;
                
            } else{
                $_SESSION['start_index_load_payment'] = $start_index ;
                
            }
                
            
            
            if ($total_records > 0 )
            {
                // get current page records
                $params["results"] = $this->payments_model->get_current_page_records($tablename,$this->per_page, $start_index);
                
                $params["results"]=$this->validateCSV($params["results"]);

            }
        }
        
        if ($this->form_validation->run() == false) {
            
            $this->load->view('templates/header');
            $this->load->view('templates/Navigation');
            $this->load->view('payments/paymentsload/load',$data);
            if (isset($_SESSION['table_temp_nom']) ) {
                $this->load->view('payments/paymentsload/paymentloadgrid',$params);
            }
            $this->load->view('templates/footer');
            
        }else{
            $data->beneficiario = $this->input->post('beneficiario');
            $this->load->view('templates/header');
            $this->load->view('templates/Navigation');
            $this->load->view('payments/paymentsload/load',$data);
            if (isset($_SESSION['table_temp_nom']) ) {
                $this->load->view('payments/paymentsload/paymentloadgrid',$params);
            }
            $this->load->view('templates/footer');
            
        }
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
        
        $data->tab ="2";
        

        if (!$this->upload->do_upload('userfile')){
            $data->tab ="2";
            $data->error = $this->upload->display_errors();
            $this->load->view('templates/header');
            $this->load->view('templates/Navigation',$data);
            $this->load->view('payments/paymentsload/load',$data);
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
                //$num = count ($datafile);
                $i++;
            }
            
            //insertamos en la tabla temporal el arreglo con los registros
            $this->payments_model->insertTablepaymentsTem($tablename,$data->records);
            
                       
            // cerramos el archivo
            fclose ($fp);

            // eliminamos el archivo cargado
            unlink($this->upload->data('file_path').$this->upload->data('file_name'));
            
            
            
            $_SESSION['table_temp_nom'] = $tablename;
            
            /*
             * 
             * Agrgar c�digo para eliminar el archivo cargado, ya que lo tenemos en BD
             * 
             */
            
            redirect(base_url().'payments/load');
            
        }
    }
    
    
   
    
    public function siguiente(){
        
        $start_index=0;
        $tablename ="";
        $total_records =0;
        $queryresult = array();
        
        //estoy hay que cargarlo en memoria para no estar consultando cada vez que se necesite.
        $this->tiposcuentas =  $this->TiposCuentas_model->getTiposCuentas();
        $this->bancos =  $this->Banco_model->getBancos();
        $this->TipoDocumentoIdentidad =  $this->TipoDocumentoIdentidad_model->getTipoDocumentoIdentidad();
        $this->TipoPago =  $this->TipoPago_model->getTipoPago();
        $this->DuracionCheque =  $this->DuracionCheque_model->getDuracionCheque();
        $this->Cargo =  $this->Cargo_model->getCargos();
        //+++++++++++++++++
        
        //consultamos de sesion el nombre de la tabla temporal que se esta trabajando.
        if (isset($_SESSION['table_temp_nom']) ) {
            $tablename=$_SESSION['table_temp_nom'];
            $total_records = $this->payments_model->get_total($tablename);
        }
        
        if (isset($_SESSION['start_index_load_payment']) ) {
            $start_index = $_SESSION['start_index_load_payment'];
        }
        if ($total_records > 0){
            $queryresult = $this->payments_model->get_current_page_records($tablename,$this->per_page, $start_index);
            $queryresult=$this->validateCSV($queryresult);
        }
        
        //cantidad de registros esperadpos en el formulario
        $cuount =$this->input->post ("cantreg");

        $data = array();
        for ($i = 0; $i<= $cuount; $i++){
            
            
            $row = array(
                "id"=> $this->input->post ("id".$i),
                "beneficiario"=> $this->input->post ("beneficiario".$i),
                "id_cargo"=> $this->input->post ("id_cargo".$i),
                "referencia_credito"=> $this->input->post ("referencia_credito".$i),
                "id_tipo_documento_identidad"=>$this->input->post ("id_tipo_documento_identidad".$i),
                "documento_identidad"=> $this->input->post ("documento_identidad".$i),
                "id_tipo_cuenta"=> $this->input->post ("id_tipo_cuenta".$i),
                "numero_cuenta"=> $this->input->post ("numero_cuenta".$i),
                "credito"=> $this->input->post ("credito".$i),
                "id_tipo_pago"=> $this->input->post ("id_tipo_pago".$i),
                "id_banco"=> $this->input->post ("id_banco".$i),
                "id_duracion_cheque"=> $this->input->post ("id_duracion_cheque".$i),
                "correo_beneficiario"=> $this->input->post ("correo_beneficiario".$i),
                "fecha"=> $this->input->post ("fecha".$i),
            );
            
            $this->payments_model->updateTableTem( $tablename,$row);
            array_push($data,$row);
        }
        
        
        
        
    }
    
    
    
    
    public function guardar(){
        
        $star_index = 0;
        if (isset($_SESSION['start_index_load_payment']) ) {
            $star_index = $_SESSION['start_index_load_payment'];
        }
        echo $star_index;
        
        $tablename = "";
       // $data = new stdClass();
        $data = array();
        
        if (isset($_SESSION['table_temp_nom']) ) {
            $tablename=$_SESSION['table_temp_nom'];
            $total_records = $this->payments_model->get_total($tablename);
            
            for ($i = 0; $i<= $total_records; $i++){
                
                $row = array("beneficiario"=> $this->input->post ("beneficiario".$i),
                    "referencia_credito"=> $this->input->post ("referencia_credito".$i),
                    "id_cargo"=> $this->input->post ("id_cargo".$i),
                    "id_tipo_documento_identidad"=>$this->input->post ("id_tipo_documento_identidad".$i),
                    "documento_identidad"=> $this->input->post ("documento_identidad".$i),
                    "id_tipo_cuenta"=> $this->input->post ("id_tipo_cuenta".$i),
                    "numero_cuenta"=> $this->input->post ("numero_cuenta".$i),
                    "credito"=> $this->input->post ("credito".$i),
                    "id_tipo_pago"=> $this->input->post ("id_tipo_pago".$i),
                    "id_banco"=> $this->input->post ("id_banco".$i),
                    "id_duracion_cheque"=> $this->input->post ("id_duracion_cheque".$i),
                    "correo_beneficiario"=> $this->input->post ("correo_beneficiario".$i),
                    "fecha"=> $this->input->post ("fecha".$i),
                );
               // echo ("beneficiario1/".$this->input->post ("beneficiario1".$i))."</br>";
                
                //validamos el registro leido
                //$row = $this->validateCSV($row);
                
                //agregamos el registro en el arreglo
                array_push($data,$row);
            }
            
           // $this->validateCSV($data->records);
            
            
            foreach ($data as $result){
                    echo ($result->beneficiario);

            }
            
        }
       // echo count($data);
        echo "beneficiario/".$data[0]["beneficiario"];
      $j = 1;
      echo $this->input->post ("beneficiario".$j);
        
        
        
        //id_cargo99
        
        //echo "id_cargo98->".$this->input->post ('id_cargo98');
       //echo $total_records;
        
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
        
        foreach ($params as $validateparams) {
            
            //validación del Campo Beneficiario
            $this->form_validation->alpha_spaces($validateparams->beneficiario) == true ? $validateparams->vbeneficiario = true : $validateparams->vbeneficiario = false;
            
            
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
            
            //Validadcion Tipo Documento Identidad
            $validatetipodoc = false;
            foreach ($this->TipoDocumentoIdentidad->result() as $vTipoDocumentoIdentidad){
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
            foreach ($this->tiposcuentas->result() as $vtiposcuentas){
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
            foreach ( $this->TipoPago->result() as $vtipoPago){
                if( $validateparams->id_tipo_pago == $vtipoPago->id || $validateparams->id_tipo_pago == null){
                    $validatetipopago = true;
                }
            }
            $validateparams->vid_tipo_pago = $validatetipopago;
            
            //Validacion del Banco
            $validatebanco = false;
            foreach ($this->bancos->result() as $vbancos){
                if( $validateparams->id_banco == $vbancos->id){
                    $validatebanco = true;
                }
            }
            $validateparams->vid_banco = $validatebanco;
            
            
            //Validacion Duración Cheque
            $validatebanco = false;
            foreach ($this->DuracionCheque->result() as $vduracionCheque){
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

