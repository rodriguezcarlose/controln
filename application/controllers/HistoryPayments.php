
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
      /*  if ($this->uri->uri_string()) {
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
        }*/
        
        $this->load->model('Payments_model');
        $this->load->library('pagination');
    }
    
    public function index(){
        $data = new stdClass();
        
        if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true && isset($_SESSION['id_rol']) &&  $_SESSION['id_rol'] === 1 ){
           $history = $this->Payments_model->getHistoryPayments(null);
        }else if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true && isset($_SESSION['id_rol']) ){
            $history = $this->Payments_model->getHistoryPayments($_SESSION['gerencia']);
        }
        
        $data->history = $history;
        
        $this->load->view('templates/header');
        $this->load->view('templates/navigation', $data);
        $this->load->view('payments/history/histotrypayments');
        $this->load->view('templates/footer');
    }
    
    public function viewdetails($id){
        $data = new stdClass();
        
       // $id = $this->input->post("id");
        $start_index = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
        $per_page = 25;
        $total_records = $this->Payments_model->get_total_detail();
        $settings = $this->config->item('pagination');
        $settings['total_rows'] = $total_records;
        $settings['base_url'] = base_url().'index.php/historyPayments/viewdetails/'.$id;
        $settings["uri_segment"] = 4;
        
        // use the settings to initialize the library
        $this->pagination->initialize($settings);
        
        // build paging links
        //$params["links"] = $this->pagination->create_links();
        
        $detail = $this->Payments_model->getPaymentsDetail($id,$per_page,$start_index);
        $data->detail = $detail;
        $data->links = $this->pagination->create_links();
        $data->total_records = $total_records;
        
        $this->load->view('templates/header');
        $this->load->view('templates/navigation', $data);
        $this->load->view('payments/history/details',$id);
        $this->load->view('templates/footer');
        
        
    }
    
}