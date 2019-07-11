<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Cargo_model class.
 *
 * @extends CI_Model
 */
class Cargo_model extends CI_Model {
    
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
    
    public function  getCargos(){
        
        return $this->db->get('cargo');
        
    } 
    
}