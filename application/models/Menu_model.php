<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Menu_model class.
 *
 * @extends CI_Model
 */
class Menu_model extends CI_Model {
    
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
    
    
    /**
     * get_menu function.
     *
     * @access public
     * @param $rol
     * @return menu list according to role
     */
    public function get_menu($rol) {
        
        $query = $this->db->query('CALL getmenu ('.$rol.')');
        $result = $query->result();
        mysqli_next_result( $this->db->conn_id);
        $query->free_result();
        return $result;
        
    }
    
}
