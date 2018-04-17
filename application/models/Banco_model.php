<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Banco_model class.
 *
 * @extends CI_Model
 */
class Banco_model extends CI_Model {
    
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
    
    public function  getBancos(){
        
        
        return $this->db->get('banco');
        
    }
    
    public function  getBancosbyId($id){
        $this->db->select("codigo");
        $this->db->where("id",$id);
        return $this->db->get('banco');
        
    }
    
    
    
}
