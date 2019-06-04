<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Salary extends CI_Controller
{

    public function success()
    {
        $this->session->set_flashdata('success', 'Salary Updated successfully');
        return $this->load->view('myPages');
    }

    private $validation = true;

    private $data;

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
        $this->load->model('Salary_model');
        $this->load->model('Payments_model');
        $this->load->model('EstatusNomina_model');
        $this->load->library('pagination');
    }

    public function index()
    {
        if ($this->data === null) {
            $data = new stdClass();
        } else {
            $data = $this->data;
        }
        $data->gerencia = $this->Gerencia_model->getGerencia();
        $data->cargo = $this->Cargo_model->getCargos();
        
        $this->load->view('templates/header');
        $this->load->view('templates/navigation', $data);
        $this->load->view('salaries/salary');
        $this->load->view('templates/footer');
    }

    public function historysalaries()
    {
        $data = new stdClass();
        
        $data->proyecto = $this->Proyecto_model->getProyecto();
        $data->gerencia = $this->Gerencia_model->getGerencia();
        $data->estatusnomina = $this->EstatusNomina_model->geteEstatus();
        
        // verificamos si se realizo un filtro para la busqueda
        $proyectoSelect = $this->input->post("id_proyecto");
        $gerenciaSelect = $this->input->post("id_gerencia");
        $estatusNomSelect = $this->input->post("id_estatus");
        $descripcionSelect = $this->input->post("descripcion");
        $fecha_creacionSelect = $this->input->post("fecha_creacion");
        
        // echo "seleccion: proyecto: ". $proyectoSelect." gerencia: ".$gerenciaSelect." estatus ".$estatusNomSelect." descripción ".$descripcionSelect;
        
        if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true && isset($_SESSION['id_rol']) && ($_SESSION['id_rol'] === 1 || $_SESSION['id_rol'] === 4)) {
            $history = $this->Payments_model->getHistorySalaries($gerenciaSelect, $proyectoSelect, $estatusNomSelect, $descripcionSelect, $fecha_creacionSelect);
        } else if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true && isset($_SESSION['id_rol'])) {
            $history = $this->Payments_model->getHistorySalaries($_SESSION['gerencia'], $proyectoSelect, $estatusNomSelect, $descripcionSelect, $fecha_creacionSelect);
        }
        
        $resultEstatus = $this->Payments_model->getEstausNominaDetalle();
        $data->estatusNom = $resultEstatus;
        
        $data->history = $history;
        
        $this->load->view('templates/header');
        $this->load->view('templates/navigation', $data);
        $this->load->view('salaries/historysalary', $data);
        $this->load->view('templates/footer');
    }

    public function checksalary()
    {
        $data = new stdClass();
        $idgerencia = $this->input->post("id_gerencia");
        
        $data->gerencia = $this->Gerencia_model->getGerencia();
        $data->id_cargo = $this->Cargo_model->getCargos();
        
        $gerenciaSelect = $this->input->post("id_gerencia");
        
        $data->id_gerencia = $this->input->post("id_gerencia");
        
        $data->id_cargo = $this->input->post("id_cargo");
        
        $this->load->model('Gerencia_model');
        $this->load->model('Cargo_model');
        
        $gerenciaporcargo = $this->Salary_model->getGerenciaCargo($gerenciaSelect);
        $data->gerenciaporcargo = $gerenciaporcargo;
        
        $totalg = $this->Salary_model->gettotal($gerenciaSelect);
        $data->totalg = $totalg;
        
        $this->load->view('templates/header');
        $this->load->view('templates/navigation');
        $this->load->view('salaries/salary', $data);
        $this->load->view('templates/footer');
    }

    public function updateSalary()
    {
        $data = new stdClass();
        $gerenciaSelect = $this->input->post("id_gerencia");
        $gerenciaporcargo = $this->Salary_model->getGerenciaCargo($gerenciaSelect);
        $data->gerenciaporcargo = $gerenciaporcargo;
        
        $this->form_validation->set_rules('sueldo[]', 'sueldo', 'required|numeric|trim', array(
            'required' => 'El sueldo es requerido.',
            'numeric' => 'El sueldo deber ser n&uacutemerico.'
        ));
        $this->form_validation->set_rules('cantidad[]', 'cantidad', 'required|numeric|trim', array(
            'required' => 'La cantidad es requerida.',
            'numeric' => 'La cantidad deber ser n&uacutemerica.'
        ));
        
        if ($this->form_validation->run() == false) {
            // $this->session->set_flashdata('error', 'Salarios No Cargados');
            $this->index();
        } else {
            $cont = count($_POST["id_cargo"]);
            if ($cont > 0) {
                for ($i = 0; $i < $cont; $i ++) {
                    $idcargo = $_POST["id_cargo"][$i];
                    $sueldo = $_POST["sueldo"][$i];
                    $cantidad = $_POST["cantidad"][$i];
                    
                    $this->Salary_model->updateSalary($idcargo, $sueldo, $cantidad);
                }
            }
            $data->success = 'Se ha Actualizado la tabla Salarios con Exito';
            $this->load->view('templates/header');
            $this->load->view('templates/navigation', $data);
            $this->load->view('home');
            $this->load->view('templates/footer');
        }
    }
}

