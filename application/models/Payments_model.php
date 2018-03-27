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
    
    /**
     * createTablepaymentsTem function, para la creación de una tabla temporal para la carga de archivos CSV de la nosminas
     *
     * @access public
     * @param $table      
     * @return boolean
     */
    
    public function createTablepaymentsTem($table){
        $result=$this->db->query("CREATE TABLE ".$table. " (
              `id` int(10) NOT NULL AUTO_INCREMENT,
              `beneficiario` varchar(255),
              `id_cargo` varchar(255),
              `referencia_credito` varchar(255),
              `id_tipo_documento_identidad` varchar(255),
              `documento_identidad` varchar(255),
              `id_tipo_cuenta` varchar(255),
              `numero_cuenta` varchar(255),
              `credito` varchar(255),
              `id_tipo_pago` varchar(255),
              `id_banco` varchar(255),
              `id_duracion_cheque` varchar(255),
              `correo_beneficiario` varchar(255),
              `fecha` varchar(255),
                PRIMARY KEY (`id`))");
        
    }
    
    /**
     * deleteTablepaymentsTem function, para la eliminacón de una tabla temporal para la carga de archivos CSV de la nosminas
     *
     * @access public
     * @param $table
     * @return boolean
     */
    
    public function deleteTablepaymentsTem($table){
        $result=$this->db->query("DROP TABLE IF EXISTS ".$table.";");
        
    }
    
    
    /**
     * insertTablepaymentsTem function, para la inserción de tatos en la tabla temporar creada
     *
     * @access public
     * @param $table
     * @param $sql
     * @return boolean
     */
    
    public function insertTablepaymentsTem($table_name, $sql){
        
        //si existe la tabla
        if ($this->db->table_exists($table_name))
        {
            //si es un array y no está vacio
            if(!empty($sql) && is_array($sql))
            {
                //si se lleva a cabo la inserción
                if($this->db->insert_batch($table_name, $sql))
                {
                    return TRUE;
                }else{
                    return FALSE;
                }
            }
        }
    }
    
    
    /**
     * get_current_page_records function
     *
     * @access public
     * @param $table
     * @param $limit
     * @param $start
     * @return $data
     */
    
    public function get_current_page_records($table,$limit, $start){
        //si existe la tabla
        if ($this->db->table_exists($table))
        {
            $this->db->limit($limit, $start);
            $this->db->order_by('id','DESC');
            $query = $this->db->get($table);
            
            if ($query->num_rows() > 0)
            {
                foreach ($query->result() as $row)
                {
                    $data[] = $row;
                }
                
                return $data;
            }
            
            return false;
        }else{
            return false;
        }
    }
    
    
    /**
     * get_total function, Retorna la cantidad de Registros de la tabla 
     *
     * @access public
     * @param $table
     * @return $data
     */
    public function get_total($table) {
        if ($this->db->table_exists($table))
        {
            return $this->db->count_all($table);
        }

    }
        

    public function getPaymentsGenerateBankFile(){
        
        $result=$this->db->query("SELECT 	n.id,
                                    		n.descripcion,
                                    		n.fecha_creacion,
                                    		g.nombre nombre_gerencia,
                                    		p.nombre nombre_proyecto,
                                    		en.nombre estatus
                                    FROM    nomina n,
                                            gerencia g,
                                            proyecto p,
                                            estatus_nomina en
                                    WHERE n.id_gerencia=g.id
                                    AND n.id_proyecto=p.id
                                    AND n.id_estatus=en.id
                                    
                                    ORDER BY  en.nombre, n.fecha_creacion DESC, g.nombre,p.nombre");
        
        if ($result->num_rows()>0){
            
            return $result;
            
        }else {
            
            return null;
        }
        
    }
    
    
    
    public function getPaymentsGenerateCSVFile($nomina = ''){

        
        $result=$this->db->query("SELECT 	dn.id,
                                            dn.beneficiario,
                                            dn.referencia_credito,
                                            tdi.nombre,
                                            dn.documento_identidad,
                                            tc.tipo,
                                            dn.numero_cuenta,
                                            dn.monto,
                                            tp.descripcion,
                                            dn.id_banco,
                                            dc.duracion,
                                            dn.correo_beneficiario,
                                            dn.fecha,
                                            c.id,
                                            c.nombre
                                   FROM 	nomina_detalle dn,
                                            tipo_documento_identidad tdi,
                                            tipos_cuentas tc,
                                            tipo_pago tp,
                                            duracion_cheque dc,
                                            cargo c
                                   WHERE    dn.id_tipo_documento_identidad=tdi.id
                                            AND dn.id_tipo_cuenta=tc.id
                                            AND dn.id_tipo_pago=tp.id
                                            AND dn.id_duracion_cheque=dc.id
                                            AND dn.id_cargo=c.id
                                            AND dn.id_nomina=" . $nomina);
               
        if ($result->num_rows()>0){
            
            return $result;
            
        }else {
            
            return null;
        }
        
    }
    
    
}

