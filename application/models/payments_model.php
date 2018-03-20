<?php

class Payments_model extends CI_Model
{
    public function getPaymentsByIdentity($nacionalidad = '', $cedula=''){
           
        $result=$this->db->query("SELECT 	tdi.nombre nacionalidad, 
                                    nd.documento_identidad,
                                    nd.beneficiario nombre_apellido,
                                    c.nombre nombre_cargo,
                                    g.nombre nombre_gerencia,
                                    p.nombre nombre_proyecto,
                                    b.nombre nombre_banco,
	                                CONCAT(SUBSTR(nd.numero_cuenta,1,4),'-****-****-****-',SUBSTR(nd.numero_cuenta,LENGTH(nd.numero_cuenta)-3,4)) numero_cuenta,
                                    nd.fecha,
                                    endet.nombre estatus
                                    FROM    nomina n, 
                                            nomina_detalle nd, 
                                            cargo c, 
                                            gerencia g, 
                                            proyecto p, 
                                            banco b, 
                                            estatus_nomina_detalle endet,
                                            tipo_documento_identidad tdi	
                                    WHERE n.id=nd.id_nomina
                                    AND n.id_gerencia=g.id
                                    AND n.id_proyecto=p.id 
                                    AND nd.id_tipo_documento_identidad=tdi.id
                                    AND tdi.nombre='" . $nacionalidad . "' " .
                                    "AND nd.documento_identidad='" . $cedula . "' " .
                                    "AND nd.id_cargo=c.id 
                                    AND nd.id_banco=b.id
                                    AND nd.id_estatus=endet.id
                                    ORDER BY nd.fecha DESC");
        
        if ($result->num_rows()>0){
            
            return $result;
            
        }else {
            
            return null;
        }
        
    }
    
    
}

