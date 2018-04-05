<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Banco_model class.
 *
 * @extends CI_Model
 */
class EmpresaOrdenante_model extends CI_Model {
    
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
    
    public function  getEmpresaOrdenante(){   
    
        $result=$this->db->query("SELECT 	eo.nombre nombre_empresa,
                                            eo.id_tipo_documento_identidad,
                                            tdi.nombre nombre_tipo_documento_identidad,
                                            eo.rif,
                                            lot.numero_ref_lote,
                                            eo.numero_negociacion,
                                            eo.id_tipo_cuenta,
                                            tc.descripcion,
                                            eo.numero_cuenta
                                FROM 	empresa_ordenante eo,
                                        tipos_cuentas tc,
                                        tipo_documento_identidad tdi,
                                        (SELECT MAX(numero_lote) + 1 numero_ref_lote FROM nomina) lot
                                WHERE eo.id_tipo_cuenta=tc.id
                                AND eo.id_tipo_documento_identidad=tdi.id");
    
        if ($result->num_rows()>0){
            
            return $result;
            
        }else {
            
            return null;
        }
    
    }
    
    public function updateEmpresaOrdenante($value){
        
            $this->db->set('id_tipo_documento_identidad', $value["id_tipo_documento_identidad"]);
            $this->db->set('rif', $value["rif"]);
            $this->db->set('nombre', $value["nombre"]);
            $this->db->set('numero_negociacion', $value["numero_negociacion"]);
            $this->db->set('id_tipo_cuenta', $value["id_tipo_cuenta"]);
            $this->db->set('numero_cuenta', $value["numero_cuenta"]);
            $this->db->update('empresa_ordenante');

    }
    
}

