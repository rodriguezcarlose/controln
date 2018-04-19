<?php
defined('BASEPATH') or exit('No direct script access allowed');

class EstatusReclamo_model extends CI_Model
{

    public function geteEstatus()
    {
        return $this->db->get('estatus_reclamo');
    }
}