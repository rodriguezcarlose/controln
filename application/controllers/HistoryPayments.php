
<?php
defined('BASEPATH') or exit('No direct script access allowed');

class HistoryPayments extends CI_Controller
{

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
            } // Si no se llega desde un enlace se redirecciona al inicio
            else {
                // Podemos aqui crear un mensaje antes de redirigir que informe
                redirect(base_url());
            }
        }
        
        $this->load->model('Payments_model');
        $this->load->model('EstatusNomina_model');
        $this->load->model('Proyecto_model');
        $this->load->model('Gerencia_model');
        $this->load->library('pagination');
    }
    
    public function index(){
        $data = new stdClass();
        
        $data->proyecto =  $this->Proyecto_model->getProyecto();
        $data->gerencia =  $this->Gerencia_model->getGerencia();
        $data->estatusnomina =  $this->EstatusNomina_model->geteEstatus();
       
        //verificamos si se realizo un filtro para la busqueda
        $proyectoSelect = $this->input->post("id_proyecto");
        $gerenciaSelect = $this->input->post("id_gerencia");
        $estatusNomSelect = $this->input->post("id_estatus");
        $descripcionSelect = $this->input->post("descripcion");
        
      //  echo "seleccion: proyecto: ". $proyectoSelect." gerencia: ".$gerenciaSelect." estatus ".$estatusNomSelect." descripción ".$descripcionSelect;
        
        
        
        if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true && isset($_SESSION['id_rol']) &&  ($_SESSION['id_rol'] === 1 || $_SESSION['id_rol'] === 4) ){
            $history = $this->Payments_model->getHistoryPayments($gerenciaSelect,$proyectoSelect,$estatusNomSelect,$descripcionSelect);
        }else if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true && isset($_SESSION['id_rol']) ){
            $history = $this->Payments_model->getHistoryPayments($_SESSION['gerencia'],$proyectoSelect,$estatusNomSelect, $descripcionSelect);
        }
        
        $resultEstatus=$this->Payments_model->getEstausNominaDetalle();
        $data->estatusNom= $resultEstatus;
        
        $data->history = $history;
        
        $this->load->view('templates/header');
        $this->load->view('templates/navigation', $data);
        $this->load->view('payments/history/historypayments',$data);
        $this->load->view('templates/footer');
    }
    
    public function viewdetails($estatus,$id){
        $data = new stdClass();
        
        $start_index = ($this->uri->segment(5)) ? $this->uri->segment(5) : 0;
        //$per_page = 10;
        $total_records = $this->Payments_model->get_total_detail($estatus,$id);
        $settings = $this->config->item('pagination');
        $settings['per_page'] = 25;
        $settings['total_rows'] = $total_records;
        $settings['base_url'] = base_url().'index.php/historyPayments/viewdetails/'.$estatus."/".$id;
        $settings["uri_segment"] = 5;
        $this->pagination->initialize($settings);
        $detail = $this->Payments_model->getPaymentsDetail($id,$settings['per_page'],$start_index,$estatus);
        $data->detail = $detail;
        $data->links = $this->pagination->create_links();
        $data->total_records = $total_records;
        $data->id_nomina = $id;
        
        
        
        switch ($estatus){
            case 1:
                $data->estatus = "Pendientes";
                break;
            case 2:
                $data->estatus = "Procesados";
                break;
            case 3:
                $data->estatus = "Pagados";
                break;
            case 4:
                $data->estatus = "Rechazados";
                break;
            case 0:
                $data->estatus = "total";
                break;
        }
        
        $this->load->view('templates/header');
        $this->load->view('templates/navigation', $data);
        $this->load->view('payments/history/details',$id);
        $this->load->view('templates/footer');
        
        
    }
    
    public function updateNominaProccessed($idnomina){
        
       
        $data = new stdClass();
        
        
        $data->proyecto =  $this->Proyecto_model->getProyecto();
        $data->gerencia =  $this->Gerencia_model->getGerencia();
        $data->estatusnomina =  $this->EstatusNomina_model->geteEstatus();
        //verificamos si se realizo un filtro para la busqueda
        $proyectoSelect = $this->input->post("id_proyecto");
        $gerenciaSelect = $this->input->post("id_gerencia");
        $estatusNomSelect = $this->input->post("id_estatus");
        $descripcionSelect = $this->input->post("descripcion");
        
        $result = $this->Payments_model->updateNominaProccessed($idnomina);
        
        if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true && isset($_SESSION['id_rol']) &&  $_SESSION['id_rol'] === 1 ){
            $history = $this->Payments_model->getHistoryPayments($gerenciaSelect,$proyectoSelect,$estatusNomSelect,$descripcionSelect);
        }else if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true && isset($_SESSION['id_rol']) ){
            $history = $this->Payments_model->getHistoryPayments($_SESSION['gerencia'],$proyectoSelect,$estatusNomSelect, $descripcionSelect);
        }
        $resultEstatus=$this->Payments_model->getEstausNominaDetalle();
        $data->estatusNom= $resultEstatus;
        $data->history = $history;
        
        if ($result){
            $data->success = "Operaci&oacute;n Realizada con Exito.";
            $this->load->view('templates/header');
            $this->load->view('templates/navigation', $data);
            $this->load->view('payments/history/historypayments',$data);
            $this->load->view('templates/footer');
            
           /**********************************************para la gerencia*******************************/
            $this->load->library('email');
            $configexcle = array(
                'protocol' => 'smtp',
                'smtp_host' => 'ssl://mail.ex-cle.com',
                'smtp_port' => 465,
                'smtp_user' => 'noresponder@ex-cle.com',
                'smtp_pass' => 'oiu987ygv',
                'mailtype' => 'html',
                'charset' => 'utf-8',
                'newline' => "\r\n",
                'wordwrap' => true
            );
            $to_email= $this->Payments_model->getEmailgerencia($idnomina);
            $to = "";
            foreach ($to_email->result() as $email){
                $to = $email->correo;
            }
            $to_subject= $this->Payments_model->getEmailsubject($idnomina);
            $subject = "";
            foreach ($to_subject->result() as $descripcion){
                $subject = $descripcion->descripcion;
            }
            $lote = "";
            foreach ($to_subject->result() as $numerolote){
                $lote = $numerolote->numero_lote;
            }
            $rechazados= $this->Payments_model->getemailrechazados($idnomina);
            $rechazo = 0;
            $this->email->initialize($configexcle);
            $this->email->from('noresponder@ex-cle.com');
            $this->email->to($to);
            $this->email->subject('Control Nomina  Pagada (' . "$subject".')');
            $this->email->message('Se notifica que la <strong>Gerencia Administrativa</strong> Pago la nómina: <strong>' . $subject . '</strong> Lote numero: <strong>' . $lote .'</strong> Registrando: "<strong>' . $rechazo. '</strong>" Rechazados.');
            $this->email->send();
            /**************************************para la gerencia administrativa*******************************/
            $this->email->clear();
            $to_administrativo= $this->Payments_model->getEmailadministrativo();
            $to_email= $this->Payments_model->getEmailgerencia($idnomina);
            $to = "";
            foreach ($to_administrativo->result() as $email){
                $to = $email->correo;
            }
            $to_subject= $this->Payments_model->getEmailsubject($idnomina);
            $subject = "";
            foreach ($to_subject->result() as $descripcion){
                $subject = $descripcion->descripcion;
            }
            $lote = "";
            foreach ($to_subject->result() as $numerolote){
                $lote = $numerolote->numero_lote;
            }
            $rechazados= $this->Payments_model->getemailrechazados($idnomina);
            $rechazo = 0;
            $this->email->initialize($configexcle);
            $this->email->from('noresponder@ex-cle.com');
            $this->email->to($to);
            $this->email->subject('Control - Nomina  Pagada (' . "$subject".')');
            $this->email->message('Nómina: <strong>' . $subject . '</strong> Lote numero: <strong>' . $lote .'</strong>  Pagada Exitosamente.');
            $this->email->send();
        }else{
            $data->error = "Operaci&oacute;nFallida.";
            $this->load->view('templates/header');
            $this->load->view('templates/navigation', $data);
            $this->load->view('payments/history/historypayments',$data);
            $this->load->view('templates/footer');
        }
        
        
        
        
        
    }
    
}