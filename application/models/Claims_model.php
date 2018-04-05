<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Claims_model extends CI_Model
{
    public function getClaimsByIdentity($nacionalidad = '', $cedula=''){
        
        $result=$this->db->query("SELECT 	tdi.nombre nacionalidad,
                                        	r.documento_identidad,
                                        	CONCAT(r.nombre,' ',r.apellido) nombre_apellido,
                                        	c.nombre nombre_cargo,
                                        	g.nombre nombre_gerencia,
                                        	p.nombre nombre_proyecto,
                                        	b.nombre nombre_banco,
                                            CONCAT(SUBSTR(r.numero_cuenta,1,4),'-****-****-****-',SUBSTR(r.numero_cuenta,LENGTH(r.numero_cuenta)-3,4)) numero_cuenta,
                                        	r.fecha_reclamo,
                                        	er.nombre estatus_reclamo
                                        FROM 	reclamo r, 
                                        	cargo c, 
                                        	gerencia g, 
                                        	proyecto p, 
                                        	banco b, 
                                        	estatus_reclamo er, 
                                        	tipo_documento_identidad tdi
                                        WHERE r.id_gerencia=g.id
                                        AND r.id_proyecto=p.id
                                        AND r.id_tipo_documento_identidad=tdi.id
                                        AND tdi.nombre='" . $nacionalidad . "' " .
                                        "AND r.documento_identidad='" . $cedula . "' " .
                                        "AND r.id_cargo=c.id
                                        AND r.id_banco=b.id
                                        AND r.id_estatus_reclamo=er.id
                                        ORDER BY r.fecha_reclamo DESC");
        
        if ($result->num_rows()>0){
            
            return $result;
            
        }else {
            
            return null;
        }
        
    }
    public function __construct() {
        parent::__construct();
        $this->load->database('ctrln');
    }
    
    public function addclaims($data)
    {
        $data=array(
            'id_tipo_documento_identidad'=>$this->input->post('id_tipo_documento_identidad'),
            'documento_identidad'=>$this->input->post('documento_identidad'),
            'nombre'=>$this->input->post('nombre'),
            'apellido'=>$this->input->post('apellido'),
            'telefono'=> $this->input->post('telefono'),
            'correo'=>$this->input->post('correo'),
            'id_banco'=> $this->input->post('id_banco'),
            'numero_cuenta'=> $this->input->post('numero_cuenta'),
            'id_proyecto'=>$this->input->post('id_proyecto'),
            'id_gerencia'=> $this->input->post('id_gerencia'),
            'id_cargo'=>$this->input->post('id_cargo'),
            'id_tipo_error'=>$this->input->post('id_tipo_error'),
       );
        $this->db->insert('reclamo',$data);
    }
    
    public function  getUploadclaim(){
        return $this->db->get('imagen_reclamo');
    }
}

