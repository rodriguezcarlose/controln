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
        $this->load->model('payments_model');
    }
    
    public function index()
    {
        
    }
    
    public function load(){

        
        $data = new stdClass();
        $data->beneficiario = "";
        
        $this->form_validation->set_rules('beneficiario', 'beneficiario', 'required|alpha_numeric_spaces', array('required' => 'El Campo Beneficiario es requerido','alpha_numeric_spaces' => 'El Campo Beneficiario no debe contener caracteres especiales ni numeros'));
        $data->tab ="1";
        
        // si ya existe una tabla temporal en memoria, cargamos la paginación
        $params = array();
        if (isset($_SESSION['table_temp_nom']) ) {
            // init params
            
            $tablename=$_SESSION['table_temp_nom'];
            
            $start_index = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
            $total_records = $this->payments_model->get_total($tablename);
            
            // load config file
            $this->config->load('pagination', TRUE);
            $settings = $this->config->item('pagination');
            $settings['total_rows'] = $this->payments_model->get_total($tablename);
            $settings['base_url'] = base_url().'payments/load';
            
            if ($total_records > 0)
            {
                // get current page records
                $params["results"] = $this->payments_model->get_current_page_records($tablename,$settings['per_page'], $start_index);
                
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
                    
                    // creamos el arreglo con la información de cada registro que se pasara al metodo de inseción de datos en la 
                    // tabla temporal
                    $row = array("beneficiario"=> $datafile[0],
                        "id_cargo"=> $datafile[1],
                        "referencia_credito"=> $datafile[2],
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
             * Agrgar código para eliminar el archivo cargado, ya que lo tenemos en BD
             * 
             */
            
            redirect(base_url().'payments/load');
            
            
            //$this->load->view('user_listing', $params);
            
            //cargamos la vistas que mostrarán los registros cargados
            /*
            $data->tab ="1";
            $this->load->view('templates/header');
            $this->load->view('templates/Navigation',$data);
            $this->load->view('checkpayments/load',$data);
            $this->load->view('checkpayments/paymentloadgrid',$params);
            $this->load->view('templates/footer');*/
        }
    }
    
    
}

