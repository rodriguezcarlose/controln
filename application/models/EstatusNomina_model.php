<?php
defined('BASEPATH') or exit('No direct script access allowed');

class EstatusNomina_model extends CI_Model
{
    
    public function geteEstatus()
    {
        $result = $this->db->get('estatus_nomina');
        //echo $this->db->last_query();
        return $result;
    }
}