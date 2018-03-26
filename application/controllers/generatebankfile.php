<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Generatebankfile extends CI_Controller {
    
    public function index()
    {
        
        $this->load->view('templates/header');
        $this->load->view('templates/navigation');
        
        
        $this->load->model('proyecto_model');
        $resultProyecto=$this->proyecto_model->getProyecto();
        
        $this->load->model('gerencia_model');
        $resulGerencia=$this->gerencia_model->getGerencia();

        $this->load->model('tiposcuentas_model');
        $resulTiposCuentas=$this->tiposcuentas_model->getTiposCuentas();
        
        $data=array('proyecto'=>$resultProyecto, 'gerencia'=>$resulGerencia,'tipocuenta'=>$resulTiposCuentas);
        
        if($data != null){
            $this->load->view('payments/generatebankfile/generatebankfile',$data);
        }
        

        $this->load->view('templates/footer');
        
    }
    
    public function generateCSV()
    {
        

        
    }

    public function generateTXT()
    {
        

        
    }

}

