<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * TipoPago_model class.
 *
 * @extends CI_Model
 */
class Proyecto_model extends CI_Model {
    
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
    
    
    public function  getProyecto(){
        
        return $this->db->get('proyecto');
        
    }
    
    
    
}
