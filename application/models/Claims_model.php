<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Claims_model extends CI_Model
{

    public function getClaimsByIdentity($nacionalidad = '', $cedula = '')
    {
        $result = $this->db->query("SELECT 	tdi.nombre nacionalidad,
                                        	r.documento_identidad,
                                        	CONCAT(r.nombre,' ',r.apellido) nombre_apellido,
                                        	c.nombre nombre_cargo,
                                        	g.nombre nombre_gerencia,
                                        	p.nombre nombre_proyecto,
                                        	b.nombre nombre_banco,
                                            CONCAT(SUBSTR(r.numero_cuenta,1,4),'-****-****-****-',SUBSTR(r.numero_cuenta,LENGTH(r.numero_cuenta)-3,4)) numero_cuenta,
                                        	r.fecha_reclamo,
                                        	er.nombre_reclamo estatus_reclamo
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
                                        AND tdi.nombre='" . $nacionalidad . "' " . "AND r.documento_identidad='" . $cedula . "' " . "AND r.id_cargo=c.id
                                        AND r.id_banco=b.id
                                        AND r.id_estatus_reclamo=er.id
                                        ORDER BY r.fecha_reclamo DESC");
        
        if ($result->num_rows() > 0) {
            
            return $result;
        } else {
            
            return null;
        }
    }

    public function __construct()
    {
        parent::__construct();
        $this->load->database('ctrln');
    }

    public function addclaims($data)
    {
        $data = array(
            'id_tipo_documento_identidad' => $this->input->post('id_tipo_documento_identidad'),
            'documento_identidad' => $this->input->post('documento_identidad'),
            'nombre' => $this->input->post('nombre'),
            'apellido' => $this->input->post('apellido'),
            'telefono' => $this->input->post('telefono'),
            'correo' => $this->input->post('correo'),
            'id_banco' => $this->input->post('id_banco'),
            'numero_cuenta' => $this->input->post('numero_cuenta'),
            'id_tipos_cuentas' => $this->input->post('id_tipos_cuentas'),
            'id_proyecto' => $this->input->post('id_proyecto'),
            'id_gerencia' => $this->input->post('id_gerencia'),
            'id_cargo' => $this->input->post('id_cargo'),
            'id_tipo_error' => $this->input->post('id_tipo_error'),
            'cantidad_dias' => $this->input->post('cantidad_dias'),
            'fecha_reclamo' => $this->input->post('fecha_reclamo'),
            'id_estatus_reclamo' => 1,
            'soportereclamos' => $this->input->post('soportereclamos'),
            'comentario' => $this->input->post('comentario')
            
        );
        $this->db->insert('reclamo', $data);
    }

    public function checkclaims()
    {
        $this->db->select("e.id_gerencia");
        $this->db->from('usuario u');
        $this->db->join('empleado e', 'e.id = u.id_empleado', 'inner');
        $this->db->where('u.id', $_SESSION['id']);
        $gerencia = $this->db->get()->row('id_gerencia');
        
        $this->db->select('r.id,r.documento_identidad,r.nombre,r.apellido,p.descripcion ,te.nombre_error ,r.fecha_reclamo,er.nombre_reclamo ');
        $this->db->from('reclamo r');
        $this->db->where('r.id_gerencia', $gerencia);
        $this->db->join('proyecto p', 'r.id_proyecto=p.id', 'inner');
        $this->db->join('tipo_error te', 'r.id_tipo_error=te.id', 'inner');
        $this->db->join('estatus_reclamo er', 'r.id_estatus_reclamo=er.id', 'inner');
        
        $this->db->group_by('r.id,r.documento_identidad,r.nombre,r.apellido,p.descripcion ,te.nombre_error ,r.fecha_reclamo,er.nombre_reclamo');
        $query = $this->db->get();
        return $query->result();
    }

    public function details($idreclamo)
    {
        
        // query que obtiene todos los detalles
        $this->db->select('r.id,tdi.descripcion as nacionalidad,r.documento_identidad as cedula,r.nombre as npersona,r.apellido,r.telefono,r.correo ,
        b.nombre as banco ,r.numero_cuenta,tc.descripcion,p.nombre as proyecto ,g.nombre as gerencia ,c.nombre as cargo,
        te.nombre_error,er.id as id_reclamo,er.nombre_reclamo,r.fecha_reclamo,r.soportereclamos,r.cantidad_dias,r.comentario,r.comentario_gerencia');
        $this->db->from('reclamo r');
        $this->db->join('tipo_documento_identidad tdi', 'r.id_tipo_documento_identidad=tdi.nombre', 'inner');
        $this->db->join('tipos_cuentas tc', 'r.id_tipos_cuentas=tc.id', 'inner');
        $this->db->join('banco b', 'r.id_banco=b.id', 'inner');
        $this->db->join('proyecto p', 'r.id_proyecto=p.id', 'inner');
        $this->db->join('gerencia g', 'r.id_gerencia=g.id', 'inner');
        $this->db->join('cargo c', 'r.id_cargo=c.id', 'inner');
        $this->db->join('tipo_error te', 'r.id_tipo_error=te.id', 'inner');
        $this->db->join('estatus_reclamo er', 'r.id_estatus_reclamo=er.id', 'inner');
        $this->db->where('r.id', $idreclamo);
        
        $this->db->order_by('id');
        $query = $this->db->get();
        
        return $query->result();
    }

    public function download($archivosoporte)
    {
        $this->db->select('r.soportereclamos');
        $this->db->from('r.reclamo');
        $data = $this->db->get('reclamo r');
        return $data->result();
    }
    
    public function updateClaim($id, $estatus, $comentario_gerencia)
    {
        $this->db->set("id_estatus_reclamo",$estatus);
        $this->db->set("comentario_gerencia",$comentario_gerencia);
        $this->db->where("id",$id);
        $this->db->update("reclamo");
        if ($this->db->trans_status() === FALSE){
            return false;
        }else{
            return true;
        }
    }
    
    
}

