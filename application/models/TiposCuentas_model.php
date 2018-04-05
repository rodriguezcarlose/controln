<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * TiposCuentas_model class.
 *
 * @extends CI_Model
 */
class TiposCuentas_model extends CI_Model {
    
    /**
     * __construct function.
     *
     * @access public
     * @return void
     */
    public function __construct() {
        
        parent::__construct();
        $this->load->database();
        
    }
    
    
    public function  getTiposCuentas(){
        
       return $this->db->get('tipos_cuentas');
       
    }
    
    public function  getTipoCuentabyTipo($tipo){
        
        $result=$this->db->query("SELECT id, tipo, descripcion
                                FROM 	tipos_cuentas tdc
                                WHERE tdi.tipo='" . $tipo . "'");
        
        if ($result->num_rows()>0){
            
            return $result;
            
        }else {
            
            return null;
        }
        
    }
}
