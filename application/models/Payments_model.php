<?php

class Payments_model extends CI_Model
{
    public function getPaymentsByIdentity($nacionalidad = '', $cedula=''){
           
        $result=$this->db->query("SELECT nd.id_tipo_documento_identidad nacionalidad, 
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
                                            estatus_nomina_detalle endet	
                                    WHERE n.id=nd.id_nomina
                                    AND n.id_gerencia=g.id
                                    AND n.id_proyecto=p.id 
                                    AND nd.id_tipo_documento_identidad='" . $nacionalidad . "' " .
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
     * createTablepaymentsTem function, para la creaci�n de una tabla temporal para la carga de archivos CSV de la nosminas
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
               `id_estatus`  varchar(255),
                PRIMARY KEY (`id`))");
        
    }
    
    /**
     * deleteTablepaymentsTem function, para la eliminac�n de una tabla temporal para la carga de archivos CSV de la nosminas
     *
     * @access public
     * @param $table
     * @return boolean
     */
    
    public function deleteTablepaymentsTem($table){
        $result=$this->db->query("DROP TABLE IF EXISTS ".$table.";");
        
    }
    
    
    /**
     * insertTablepaymentsTem function, para la inserci�n de tatos en la tabla temporar creada
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
            //si es un array y no est� vacio
            if(!empty($sql) && is_array($sql))
            {
                //si se lleva a cabo la inserci�n
                if($this->db->insert_batch($table_name, $sql))
                {
                    return TRUE;
                }else{
                    return FALSE;
                }
            }
        }
    }
    
    public function  getTablepaymentsTem($table_name){
        
        return $this->db->get($table_name);
        
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

        
        $result=$this->db->query("SELECT 	@rownum := @rownum + 1 AS numero_referencia,
                                            dn.beneficiario nombre_beneficiario,
                                            dn.referencia_credito numero_referencia_credito,
                                            dn.id_tipo_documento_identidad letra,
                                            dn.documento_identidad numero_ci_rif,
                                            tc.tipo tipo_cuenta,
                                            dn.numero_cuenta numero_cuenta_beneficiario,
                                            dn.credito monto_credito,
                                            tp.descripcion tipo_pago,
                                            dn.id_banco banco,
                                            dc.duracion duracion_cheque,
                                            dn.correo_beneficiario email_beneficiario,
                                            dn.fecha fecha_valor,
                                            c.id id_cargo,
                                            c.nombre cargo
                                   FROM 	(SELECT @rownum := 0) r, nomina_detalle dn 
                                            LEFT JOIN tipo_documento_identidad tdi ON  dn.id_tipo_documento_identidad=tdi.nombre
                                            LEFT JOIN tipos_cuentas tc ON  dn.id_tipo_cuenta=tc.tipo
                                            LEFT JOIN tipo_pago tp ON dn.id_tipo_pago=tp.id
                                            LEFT JOIN duracion_cheque dc ON dn.id_duracion_cheque=dc.duracion
                                            LEFT JOIN cargo c ON dn.id_cargo=c.id
                                   WHERE    dn.id_nomina=" . $nomina);
               
        if ($result->num_rows()>0){
            
            return $result;
            
        }else {
            
            return null;
        }
        
    }

    public function getPaymentsGenerateTXTFile($nomina = ''){
        
        
        $result=$this->db->query("SELECT 	@rownum := @rownum + 1 AS numero_referencia,
                                            dn.beneficiario nombre_beneficiario,
                                            dn.referencia_credito numero_referencia_credito,
                                            dn.id_tipo_documento_identidad,
                                            dn.documento_identidad numero_ci_rif,
                                            tc.tipo tipo_cuenta,
                                            dn.numero_cuenta numero_cuenta_beneficiario,
                                            dn.credito monto_credito,
                                            tp.id tipo_pago,
                                            b.swift banco,
                                            dc.duracion duracion_cheque,
                                            dn.correo_beneficiario email_beneficiario,
                                            dn.fecha fecha_valor,
                                            c.id id_cargo,
                                            c.nombre cargo
                                   FROM 	(SELECT @rownum := 0) r, nomina_detalle dn
                                            LEFT JOIN tipo_documento_identidad tdi ON  dn.id_tipo_documento_identidad=tdi.nombre
                                            LEFT JOIN tipos_cuentas tc ON  dn.id_tipo_cuenta=tc.tipo
                                            LEFT JOIN tipo_pago tp ON dn.id_tipo_pago=tp.id
                                            LEFT JOIN duracion_cheque dc ON dn.id_duracion_cheque=dc.duracion
                                            LEFT JOIN cargo c ON dn.id_cargo=c.id
                                            LEFT JOIN banco b ON dn.id_banco=b.id
                                   WHERE    dn.id_nomina=" . $nomina);
        
        if ($result->num_rows()>0){
            
            return $result;
            
        }else {
            
            return null;
        }
        
    }
    
    public function updateTableTem($table, $values){
        
        foreach ($values as $value){
            $this->db->set('beneficiario', $value["beneficiario"]);
            $this->db->set('referencia_credito', $value["referencia_credito"]);
            $this->db->set('id_cargo', $value["id_cargo"]);
            $this->db->set('id_tipo_documento_identidad', $value["id_tipo_documento_identidad"]);
            $this->db->set('documento_identidad', $value["documento_identidad"]);
            $this->db->set('id_tipo_cuenta', $value["id_tipo_cuenta"]);
            $this->db->set('numero_cuenta', $value["numero_cuenta"]);
            $this->db->set('credito', $value["credito"]);
            $this->db->set('id_tipo_pago', $value["id_tipo_pago"]);
            $this->db->set('id_banco', $value["id_banco"]);
            $this->db->set('id_duracion_cheque', $value["id_duracion_cheque"]);
            $this->db->set('correo_beneficiario', $value["correo_beneficiario"]);
            $this->db->set('fecha', $value["fecha"]);
            $this->db->where('id', $value["id"]);
            $this->db->update($table);
        }
    }
    
    
    public function insertPayment($descripcion,$proyecto, $gerencia, $usuario, $detalle){
        
        //$_SESSION['id']
        $this->db->trans_start();
        
        
        $this->db->select_max('id','maxid');
        $id_nomina = $this->db->get('nomina')->row(); 
        $id_nomina = (int)$id_nomina->maxid + 1;

        $this->db->set('id', $id_nomina);
        $this->db->set('descripcion', $descripcion);
        $this->db->set('id_estatus', $proyecto);
        $this->db->set('id_proyecto', $proyecto);
        $this->db->set('id_usuario', $usuario);
        $this->db->set('id_gerencia', $gerencia);
        $this->db->insert("nomina");
        
        foreach ($detalle as $detalleNomina){
            
            $detalleNomina->id_nomina = $id_nomina;
            unset ($detalleNomina->id);
        }
        
        $this->db->insert_batch("nomina_detalle", $detalle);
        $this->db->trans_complete();
        
        
        if ($this->db->trans_status() === FALSE){
            return false;
        }else{
            return true;
        }
        
    }
    
    
    public function updateNumeroLote($value){
        
            $this->db->set('numero_lote', $value["numero_lote"]);
            $this->db->where('id', $value["id"]);
            $this->db->update('nomina');

    }
    
    public function updateFechaValor($values){
        
        foreach ($values as $value){
            $this->db->set('fecha', $value["fecha"]);
            $this->db->where('id_nomina', $value["id_nomina"]);
            $this->db->update('nomina_detalle');
        }
    }
    
}

