<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Paymentsrejected extends CI_Controller {
    
    
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
        $this->load->model('payments_model');
        
    }
    
    public function index()
    {
        
    }
    
    
    public function loadgrid(){
        $this->load->view('templates/header');
        $this->load->view('templates/Navigation');
        
        $resultPayments=$this->payments_model->getPaymentsProcessed();
        
        $data=array('paymentsProcessed'=>$resultPayments);
        
        $this->load->view('payments/paymentsrejected/loadgrid',$data);
        $this->load->view('templates/footer');
    
        
    }
    
    public function do_upload(){
        
        $data = new stdClass();
        $config['upload_path']          = './uploads/';
        $config['allowed_types']        = 'csv';
        $config['max_size']             = 100;
        $config['max_width']            = 1024;
        $config['max_height']           = 768;
        $this->load->library('upload', $config);
        
        if (!$this->upload->do_upload('userfile')){
            $data->error = $this->upload->display_errors();
            $this->load->view('templates/header');
            $this->load->view('templates/Navigation',$data);
            $resultPayments=$this->payments_model->getPaymentsProcessed();
            $data=array('paymentsProcessed'=>$resultPayments);
            $this->load->view('payments/paymentsrejected/loadgrid',$data);
            $this->load->view('templates/footer');
        }
        else{
            //$data->upload = array('upload_data' => $this->upload->data());
            
            //cargamos el arcivo
            $file = $this->upload->data('file_path').$this->upload->data('file_name');
            
            //abrimos el archivo
            $fp = fopen ($file,"r");
            
            $data->records = array();
            
            
            $i = 0;
            while ($datafile = fgetcsv ($fp, 1000, ";")) {
                
                //descartamos la primera linea del archivo que contiene los nembres de los campos
                if (!$i == 0){
                    
                    // creamos el arreglo con la informaci�n de cada registro
                    $row = array("ticket"=> $datafile[0],
                        "debito"=> $datafile[1],
                        "credito"=> $datafile[2],
                        "id_tipo_documento_identidad"=> $datafile[3],
                        "documento_identidad"=> $datafile[3],
                        "beneficiario"=> $datafile[4],
                        "tipo_cuenta"=> $datafile[5],
                        "nro_cuenta"=> $datafile[6],
                        "monto"=> $datafile[7],
                        "fecha"=> $datafile[8],
                   );
                    
                    
                    //agregamos el registro en el arreglo
                    array_push($data->records,$row);
                    
                }
                $i++;
            }
            
            // cerramos el archivo
            fclose ($fp);
            
            // eliminamos el archivo cargado
            unlink($this->upload->data('file_path').$this->upload->data('file_name'));
            
            
            $this->load->view('templates/header');
            $this->load->view('templates/Navigation',$data);
            $resultPayments=$this->payments_model->getPaymentsProcessed();
            $data=array('paymentsProcessed'=>$resultPayments);
            //$data->paymentsProcessed = $resultPayments;
            $this->load->view('payments/paymentsrejected/loadgrid',$data);
            $this->load->view('templates/footer');
            
           // redirect(base_url().'payments/loadgrid');
            
        }
        
        
    }
    
    
}