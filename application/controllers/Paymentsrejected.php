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
        $this->load->model('payments_model');
        
    }
    
    public function index() {
        
    }
    
    
    public function loadgrid(){
        
        $data = new stdClass();
        $nomina = 0;
        // $this->form_validation->set_rules('nomina', 'nomina', 'required', array('required' => 'Debe seleccionar una N&oacute;mina de la lista'));
        
        if (isset($_SESSION['data_rejected'])){
            $data = $_SESSION['data_rejected'];
        }
        
        if (!isset($_SESSION['data_rejected']) && !isset($data->records)) {
            $this->load->view('templates/header');
            $this->load->view('templates/navigation');
            $resultPayments=$this->payments_model->getPaymentsProcessed();
            $data->paymentsProcessed = $resultPayments;
            $this->load->view('payments/paymentsrejected/loadgrid',$data);
            $this->load->view('templates/footer');
        }else{
            //echo "procesar";
            
            
            
            
            //vamos cambiando el estatus de los rechazados uno a uno
            
            $records = $_SESSION['data_rejected'];
            
           // $data->records = array();
            
            $noDataRecords= array();
           // $data->records = array();
           
            $update_successful = false; 
            
            
            /*
             * $nominasDeatlles = $this->payments_model->getnominaByRefCredito($datafile[2]);
                    $non ='';
                    foreach ($nominasDeatlles->result() as $nominaDet) {
                        $non = $nominaDet->id_nomina;
                    }*/
            
            foreach ($data->records as $dataRecords) {    
                
                log_message('info', 'credito '.$dataRecords["credito"]);
                $count = $this->payments_model->getCantidadEstatusNominabyRefCredito($dataRecords["credito"]);
                $nomina = $this->payments_model->getnominaByRefCredito((int)$dataRecords["credito"]);
                if ($count > 0 ){
                    $resultPayments=$this->payments_model->updateEstatusNominaDetallebyReferenciaCredito($dataRecords["credito"],4);
                    $update_successful = true;
                }else{
                    array_push($noDataRecords,$dataRecords);
                    
                }
            }
            
            
            foreach ($nomina->result() as $nominaRe) {
                $non = $nominaRe->id_nomina;
            }
            unset($_SESSION['data_rejected']);
            
            if ($update_successful){
                log_message('info', '$non '.$non);
                // cambiamos el estatus de todos los registros a 3 (pagada) en detalkle nomina;
                $this->payments_model->updateEstatusNominaDetallePagadas($non,3);
                //cambiaos el estaus de la nómina a 4 (pagada en el banco
                $this->payments_model->updateEstatusNominabyId($non,4);
            }
            /**********************************************para la gerencia*******************************/
            //$data = new stdClass();
            $this->load->library('email');
            $configexcle = $this->config->item('smtp');
            log_message('info', 'nomina rechazada '.$non);
            $to_email= $this->payments_model->getEmailgerencia($non);
            $to = array();
            foreach ($to_email->result() as $email){
                array_push($to, $email->correo);
            }
            $to_subject= $this->payments_model->getEmailsubject($non);
            $subject = "";
            foreach ($to_subject->result() as $descripcion){
                $subject = $descripcion->descripcion;
            }
            $lote = "";
            foreach ($to_subject->result() as $numerolote){
                $lote = $numerolote->numero_lote;
            }
            $rechazados= $this->payments_model->getemailrechazados($non);
            $rechazo = "";
            foreach ($rechazados->result() as $conteo){
             $rechazo = $conteo->rechazada;
            }
            $this->email->initialize($configexcle);
            $this->email->from($configexcle['smtp_user']);
            $this->email->to($to);
            $this->email->subject('Control Nomina - Pagada (' . "$subject".')');
            $this->email->message('Se notifica que la <strong>Gerencia Administrativa</strong> Pago la nómina: <strong>' . $subject . '</strong> Lote numero: <strong>' . $lote .'</strong> Registrando: "<strong>' . $rechazo. '</strong>" Rechazados.');
            $this->email->send();
            /**************************************para la gerencia administrativa*******************************/
            $this->email->clear();
            $to_administrativo= $this->payments_model->getEmailadministrativo();
            $to_email= $this->payments_model->getEmailgerencia($non);
            $to = array();
            foreach ($to_administrativo->result() as $email){
                array_push($to, $email->correo);
            }
            $to_subject= $this->payments_model->getEmailsubject($non);
            $subject = "";
            foreach ($to_subject->result() as $descripcion){
                $subject = $descripcion->descripcion;
            }
            $lote = "";
            foreach ($to_subject->result() as $numerolote){
                $lote = $numerolote->numero_lote;
            }
            $rechazados= $this->payments_model->getemailrechazados($non);
            $rechazo = "";
            foreach ($rechazados->result() as $conteo){
                $rechazo = $conteo->rechazada;
            }
            $this->email->initialize($configexcle);
            $this->email->from($configexcle['smtp_user']);
            $this->email->to($to);
            $this->email->subject('Control Nomina - Pagada (' . "$subject".')');
            $this->email->message('Carga de Rechazos de la nómina: <strong>' . $subject . '</strong> Lote numero: <strong>' . $lote .'</strong> Procesada Exitosamente. Registrando: "<strong>' . $rechazo. '</strong>" Rechazados.');
            $this->email->send();
            /**************************************fin email*******************************/
            
            if ( count($noDataRecords) > 0){
                if(count($data->records) == count($noDataRecords))
                    $data->error = 'No se ha cargado ning&uacute;n registro, valide que haya seleccionado la n&oacutemina corecta.';
                else 
                    $data->success = 'Se ha cargado con &Eacute;xito el archivo de Rechazados, pero fallaron los siguientes registros.';
                
                $params["nodatarecords"] = $noDataRecords;
                //hay reachazos que no se encontraron en la BD
                $this->load->view('templates/header');
                $this->load->view('templates/navigation',$data);
                $this->load->view('payments/paymentsrejected/loadgridresult',$params);
                $this->load->view('templates/footer');
            }else{
                unset($data->records);
                $data->success = 'Se ha cargado con &Eacute;xito el archivo de Rechazados.';
                $resultPayments=$this->payments_model->getPaymentsProcessed();
                $data->paymentsProcessed = $resultPayments;
                $this->load->view('templates/header');
                $this->load->view('templates/navigation',$data);
                $this->load->view('payments/paymentsrejected/loadgrid');
                $this->load->view('templates/footer');

            }
            
           
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
        
        if (!$this->upload->do_upload('userfile')){
            $data->error = $this->upload->display_errors();
            $this->load->view('templates/header');
            $this->load->view('templates/navigation',$data);
            $resultPayments=$this->payments_model->getPaymentsProcessed();
            $data=array('paymentsProcessed'=>$resultPayments);
            $this->load->view('payments/paymentsrejected/loadgrid',$data);
            $this->load->view('templates/footer');
        } else {
            
            //cargamos el arcivo
            $file = $this->upload->data('file_path').$this->upload->data('file_name');
            
            //abrimos el archivo
            $fp = fopen ($file,"r");
            
            $data->records = array();
            
            
            $i = 0;
            while ($datafile = fgetcsv ($fp, 1000, ";")) {
                
                //descartamos la primera linea del archivo que contiene los nembres de los campos
                if (!$i == 0){
                    //consultamos la nomina al que pertenece el registro que se esta cargando.
                    $nominasDeatlles = $this->payments_model->getnominaByRefCredito($datafile[2]);
                    $non ='';
                    foreach ($nominasDeatlles->result() as $nominaDet) {
                        $non = $nominaDet->id_nomina;
                    }
                    $nominas = $this->payments_model->nominaById($non);
                     foreach ($nominas->result() as $nomina) {
                        $non = $nomina->descripcion;
                    }
                    
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
                        "nomina" => $non,
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
            
            $_SESSION['data_rejected'] = $data;

            //$this->loadgrid();
            $this->load->view('templates/header');
            $this->load->view('templates/navigation');
            //$resultPayments = $this->payments_model->getPaymentsProcessed();
            //$data->paymentsProcessed = $resultPayments;
            $this->load->view('payments/paymentsrejected/loadgrid', $data);
            $this->load->view('templates/footer');
        }
        
        
    }
    
    
}