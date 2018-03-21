<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * DuracionCheque_model class.
 *
 * @extends CI_Model
 */
class DuracionCheque_model extends CI_Model {
    
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
    
    
    public function  getDuracionCheque(){
        
        return $this->db->get('duracion_cheque');
        
    }
    
    
    
    
}
