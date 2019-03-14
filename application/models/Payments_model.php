<?php

class Payments_model extends CI_Model
{

    public function getPaymentsByIdentity($nacionalidad = '', $cedula = '')
    {
        $result = $this->db->query("SELECT nd.id_tipo_documento_identidad nacionalidad, 
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
                                    AND nd.id_tipo_documento_identidad='" . $nacionalidad . "' " . "AND nd.documento_identidad='" . $cedula . "' " . "AND nd.id_cargo=c.id 
                                    AND nd.id_banco=b.id
                                    AND nd.id_estatus=endet.id
                                    ORDER BY nd.fecha DESC");
        
        if ($result->num_rows() > 0) {
            
            return $result;
        } else {
            
            return null;
        }
    }

    /**
     * createTablepaymentsTem function, para la creaci�n de una tabla temporal para la carga de archivos CSV de la nosminas
     *
     * @access public
     * @param
     *            $table
     * @return boolean
     */
    public function createTablepaymentsTem($table)
    {
        $this->db->query("DROP TABLE IF EXISTS " . $table . ";");
        
        $result = $this->db->query("CREATE TABLE " . $table . " (
              `id` int(10) NOT NULL,
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
                `valid`  BOOLEAN NOT NULL,
                PRIMARY KEY (`id`))");
    }

    /**
     * deleteTablepaymentsTem function, para la eliminac�n de una tabla temporal para la carga de archivos CSV de la nosminas
     *
     * @access public
     * @param
     *            $table
     * @return boolean
     */
    public function deleteTablepaymentsTem($table)
    {
        $result = $this->db->query("DROP TABLE IF EXISTS " . $table . ";");
    }

    /**
     * insertTablepaymentsTem function, para la inserci�n de tatos en la tabla temporar creada
     *
     * @access public
     * @param
     *            $table
     * @param
     *            $sql
     * @return boolean
     */
    public function insertTablepaymentsTem($table_name, $sql)
    {
        
        // si existe la tabla
        if ($this->db->table_exists($table_name)) {
            // si es un array y no est� vacio
            if (! empty($sql) && is_array($sql)) {
                // si se lleva a cabo la inserci�n
                if ($this->db->insert_batch($table_name, $sql)) {
                    return TRUE;
                } else {
                    return FALSE;
                }
            }
        }
    }

    public function getTablepaymentsTem($table_name)
    {
        // $this->db->order_by('id','DESC');
        return $this->db->get($table_name);
    }

    public function getTablepaymentsTemlidNoValid($table_name)
    {
        // $this->db->order_by('id','DESC');
        $this->db->set("valid", "0");
        return $this->db->get($table_name);
    }

    /**
     * get_current_page_records function
     *
     * @access public
     * @param
     *            $table
     * @param
     *            $limit
     * @param
     *            $start
     * @return $data
     */
    public function get_current_page_records($table, $limit, $start, $valid, $order)
    {
        // si existe la tabla
        if ($this->db->table_exists($table)) {
            $this->db->where('valid', $valid);
            $this->db->limit($limit, $start);
            $this->db->order_by($order, 'ASC');
            $query = $this->db->get($table);
            // log_message('info', 'Payment|loadgrid '.$sql = $this->db->last_query());
            if ($query->num_rows() > 0) {
                foreach ($query->result() as $row) {
                    $data[] = $row;
                }
                
                return $data;
            }
            
            return false;
        } else {
            return false;
        }
    }

    public function getTableTempLimit($table, $limit, $start)
    {
        // si existe la tabla
        if ($this->db->table_exists($table)) {
            
            $this->db->limit($limit, $start);
            $query = $this->db->get($table);
            // log_message('info', 'Payment_model|getTableTempLimit '.$this->db->last_query());
            
            if ($query->num_rows() > 0) {
                foreach ($query->result() as $row) {
                    $data[] = $row;
                }
                
                return $data;
            }
            
            return false;
        } else {
            return false;
        }
    }

    /**
     * get_total function, Retorna la cantidad de Registros de la tabla
     *
     * @access public
     * @param
     *            $table
     * @return $data
     */
    public function get_total($table, $valid)
    {
        if ($this->db->table_exists($table)) {
            $this->db->where('valid', $valid);
            // log_message('info', 'Payment|get_total'.$sql = $this->db->last_query());
            return $this->db->count_all_results($table);
        }
    }

    public function getPaymentsGenerateBankFile()
    {
        $result = $this->db->query("SELECT 	n.id,
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
                                    AND n.id_estatus in (2)
                                    ORDER BY  en.nombre, n.fecha_creacion DESC, g.nombre,p.nombre");
        
        if ($result->num_rows() > 0) {
            
            return $result;
        } else {
            
            return null;
        }
    }

    /*
     * Funcion para retornar las nominas con estatus procesado (3) o Pagadas (4).
     */
    public function getPaymentsProcessed()
    {
        $result = $this->db->query("SELECT 	n.id,
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
                                    AND n.id_estatus in (3,4)
            
                                    ORDER BY  en.nombre, n.fecha_creacion DESC, g.nombre,p.nombre");
        
        if ($result->num_rows() > 0) {
            
            return $result;
        } else {
            
            return null;
        }
    }

    public function getEmailadministrativo()
    {
        $result = $this->db->query("SELECT usuario.correo
                                            FROM usuario
                                            INNER JOIN empleado ON usuario.`id_empleado` = empleado.`id`
                                            INNER JOIN gerencia ON empleado.`id_gerencia` = gerencia.id
                                            WHERE empleado.`id_gerencia` = 7");
        return $result;
    }

    public function getEmailsubject($idnomina)
    {
        $result = $this->db->query("SELECT
                                              n.descripcion, g.`nombre`,n.numero_lote
                                              FROM
                                              nomina n
                                              INNER JOIN gerencia g
                                              ON n.`id_gerencia`=g.`id`
                                              WHERE n.id='" . $idnomina . "'
                                             ");
        return $result;
    }

    public function getEmailgerencia($idnomina)
    {
        $result = $this->db->query("SELECT usuario.correo
                                                FROM usuario
                                                INNER JOIN empleado ON usuario.`id_empleado` = empleado.`id`
                                                INNER JOIN gerencia ON empleado.`id_gerencia` = gerencia.id
                                                INNER JOIN nomina ON gerencia.`id`=nomina.`id_gerencia`
                                                WHERE nomina.id = '" . $idnomina . "'
                                                ");
        return $result;
    }

    public function getemailrechazados($idnomina)
    {
        $result = $this->db->query("SELECT pagada.pagada,rechazada.rechazada
                                                FROM (`nomina` `n`)
                                                INNER JOIN `estatus_nomina` `en` ON `en`.`id` = `n`.`id_estatus`
                                                INNER JOIN `proyecto` `p` ON `p`.`id` = `n`.`id_proyecto`
                                                INNER JOIN `gerencia` `g` ON `g`.`id` = `n`.`id_gerencia`
                                                LEFT JOIN (SELECT DISTINCT id_nomina,id_estatus, COUNT(*) pendiente
                                                FROM nomina_detalle
                                                WHERE id_estatus = 1
                                                GROUP BY id_nomina,id_estatus) pendiente ON `pendiente`.`id_nomina` = `n`.`id`
                                                LEFT JOIN (SELECT DISTINCT id_nomina,id_estatus, COUNT(*) procesada
                                                FROM nomina_detalle
                                                WHERE id_estatus = 2
                                                GROUP BY id_nomina,id_estatus) procesada ON `procesada`.`id_nomina` = `n`.`id`
                                                LEFT JOIN (SELECT DISTINCT id_nomina,id_estatus, COUNT(*) pagada
                                                FROM nomina_detalle
                                                WHERE id_estatus = 3
                                                GROUP BY id_nomina,id_estatus) pagada ON `pagada`.`id_nomina` = `n`.`id`
                                                LEFT JOIN (SELECT DISTINCT id_nomina,id_estatus, COUNT(*) rechazada
                                                FROM nomina_detalle
                                                WHERE id_estatus = 4
                                                GROUP BY id_nomina,id_estatus) rechazada ON `rechazada`.`id_nomina` = `n`.`id`
                                                LEFT JOIN (SELECT DISTINCT id_nomina, COUNT(*) total
                                                FROM nomina_detalle
                                                GROUP BY id_nomina) total ON `total`.`id_nomina` = `n`.`id`
                                                WHERE n.id = '" . $idnomina . "'
                                                ORDER BY n.`fecha_creacion` DESC");
        return $result;
    }

    public function getPaymentsGenerateCSVFile($nomina = '')
    {
        
        /*
         * $result=$this->db->query("SELECT @rownum := @rownum + 1 AS numero_referencia,
         * dn.beneficiario nombre_beneficiario,
         * dn.referencia_credito numero_referencia_credito,
         * dn.id_tipo_documento_identidad letra,
         * dn.documento_identidad numero_ci_rif,
         * tc.tipo tipo_cuenta,
         * dn.numero_cuenta numero_cuenta_beneficiario,
         * dn.credito monto_credito,
         * tp.descripcion tipo_pago,
         * dn.id_banco banco,
         * dc.duracion duracion_cheque,
         * dn.correo_beneficiario email_beneficiario,
         * dn.fecha fecha_valor,
         * c.id id_cargo,
         * c.nombre cargo
         * FROM (SELECT @rownum := 0) r, nomina_detalle dn
         * LEFT JOIN tipo_documento_identidad tdi ON dn.id_tipo_documento_identidad=tdi.nombre
         * LEFT JOIN tipos_cuentas tc ON dn.id_tipo_cuenta=tc.tipo
         * LEFT JOIN tipo_pago tp ON dn.id_tipo_pago=tp.id
         * LEFT JOIN duracion_cheque dc ON dn.id_duracion_cheque=dc.duracion
         * LEFT JOIN cargo c ON dn.id_cargo=c.id
         * WHERE dn.id_nomina=" . $nomina);
         */
        $result = $this->db->query("SELECT 	@rownum := @rownum + 1 AS ID,
                                            dn.beneficiario NOMBRE_DEL_BENEFICIARIO,
                                            dn.referencia_credito REFERENCIA_DEL_CREDITO,
                                            c.id CARGO,
                                            dn.id_tipo_documento_identidad LETRA_RIF_CI,
                                            dn.documento_identidad NUMERO_RIF_CI,
                                            tc.tipo TIPO_DE_CUENTA,
                                            dn.numero_cuenta CUENTA_DEL_BENEFICIARIO,
                                            dn.credito MONTO_DEL_CREDITO,
                                            tp.descripcion TIPO_DE_PAGO,
                                            dn.id_banco BANCO,
                                            dc.duracion DURACION_DEL_CHEQUE,
                                            dn.correo_beneficiario EMAIL_DEL_BENEFICIARIO,
                                            dn.fecha FECHA_VALOR_DEL_DEBITO
                                   FROM 	(SELECT @rownum := 0) r, nomina_detalle dn
                                            LEFT JOIN tipo_documento_identidad tdi ON  dn.id_tipo_documento_identidad=tdi.nombre
                                            LEFT JOIN tipos_cuentas tc ON  dn.id_tipo_cuenta=tc.tipo
                                            LEFT JOIN tipo_pago tp ON dn.id_tipo_pago=tp.id
                                            LEFT JOIN duracion_cheque dc ON dn.id_duracion_cheque=dc.duracion
                                            LEFT JOIN cargo c ON dn.id_cargo=c.id
                                   WHERE    dn.id_nomina=" . $nomina);
        
        if ($result->num_rows() > 0) {
            
            return $result;
        } else {
            
            return null;
        }
    }

    public function getPaymentsGenerateTXTFile($nomina = '')
    {
        $result = $this->db->query("SELECT 	@rownum := @rownum + 1 AS numero_referencia,
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
                                   WHERE    dn.id_nomina=" . $nomina . " ORDER BY numero_referencia_credito");
        if ($result->num_rows() > 0) {
            return $result;
        } else {
            return null;
        }
    }

    public function updateTableTem($table, $values)
    {
        foreach ($values as $value) {
            $this->db->set('beneficiario', $value["beneficiario"]);
            // $this->db->set('referencia_credito', $value["referencia_credito"]);
            $this->db->set('id_cargo', $value["id_cargo"]);
            $this->db->set('id_tipo_documento_identidad', $value["id_tipo_documento_identidad"]);
            $this->db->set('documento_identidad', $value["documento_identidad"]);
            $this->db->set('id_tipo_cuenta', $value["id_tipo_cuenta"]);
            $this->db->set('numero_cuenta', $value["numero_cuenta"]);
            $this->db->set('credito', $value["credito"]);
            // $this->db->set('id_tipo_pago', $value["id_tipo_pago"]);
            $this->db->set('id_banco', $value["id_banco"]);
            // $this->db->set('id_duracion_cheque', $value["id_duracion_cheque"]);
            // $this->db->set('correo_beneficiario', $value["correo_beneficiario"]);
            // $this->db->set('fecha', $value["fecha"]);
            $this->db->where('id', $value["id"]);
            $this->db->update($table);
        }
    }

    /**
     * TableTem function, actialuza los registros de la tabla temporal, de acuerdo a los arreglos realizados en la vista
     * de los campos con eror
     *
     * @access public
     * @param
     *            $table
     * @return $data
     */
    public function updateTableTempRow($table, $row, $value)
    {
        
        // echo "tabla-> ".$table. " row: ".$row. " value: ".$value;
        $this->db->set('valid', $value);
        $this->db->where('id', $row);
        $this->db->update($table);
        // log_message('info', 'Payment|updateTableTempRow'.$sql = $this->db->last_query());
    }

    public function insertPayment($descripcion, $proyecto, $gerencia, $usuario, $detalle)
    {
        ini_set('max_execution_time', 0);
        $this->db->trans_start();
        
        $cantidadNominas = intval(count($detalle) / 500);
        
        if (count($detalle) / 500 > $cantidadNominas) {
            $cantidadNominas ++;
        }
        
        $totalPorNomina = 500;
        $nomimaInicial = 0;
        $nominaFin = count($detalle);
        
        for ($i = 1; $cantidadNominas >= $i; $i ++) {
            
            $this->db->select_max('id', 'maxid');
            $id_nomina = $this->db->get('nomina')->row();
            $id_nomina = (int) $id_nomina->maxid + 1;
            
            $this->db->set('id', $id_nomina);
            if ($cantidadNominas > 1)
                $this->db->set('descripcion', $descripcion . "_" . $i);
            else
                $this->db->set('descripcion', $descripcion);
            $this->db->set('id_estatus', 2);
            $this->db->set('id_proyecto', $proyecto);
            $this->db->set('id_usuario', $usuario);
            $this->db->set('id_gerencia', $gerencia);
            $this->db->insert("nomina");
            
            foreach ($detalle as $detalleNomina) {
                $detalleNomina->id_nomina = $id_nomina;
                unset($detalleNomina->id);
                unset($detalleNomina->valid);
            }
            
            if ($cantidadNominas > 1) {
                $detalle_nomina = array_slice($detalle, $nomimaInicial, $totalPorNomina);
                $nomimaInicial = $nomimaInicial + 500;
            } else {
                $detalle_nomina = array_slice($detalle, 0, count($detalle));
            }
            $this->db->insert_batch("nomina_detalle", $detalle_nomina);
        }
        $this->db->trans_complete();
        
        ini_set('max_execution_time', 30);
        if ($this->db->trans_status() === FALSE) {
            return 0;
        } else {
            return $id_nomina;
        }
    }

    public function insertPaymentIndividual($descripcion, $proyecto, $gerencia, $usuario, $detalle)
    {
        $this->db->trans_start();
        
        $this->db->select_max('id', 'maxid');
        $id_nomina = $this->db->get('nomina')->row();
        $id_nomina = (int) $id_nomina->maxid + 1;
        
        $this->db->set('id', $id_nomina);
        $this->db->set('descripcion', $descripcion);
        $this->db->set('id_estatus', 2);
        $this->db->set('id_proyecto', $proyecto);
        $this->db->set('id_usuario', $usuario);
        $this->db->set('id_gerencia', $gerencia);
        $this->db->insert("nomina");
        
        $detalleInsert = array();
        
        foreach ($detalle as $detalleNomina) {
            $detalleNomina["id_nomina"] = $id_nomina;
            array_push($detalleInsert, $detalleNomina);
        }
        
        $this->db->insert_batch("nomina_detalle", $detalleInsert);
        $this->db->trans_complete();
        
        if ($this->db->trans_status() === FALSE) {
            return false;
        } else {
            return true;
        }
    }

    public function updateNumeroLote($value)
    {
        $this->db->set('numero_lote', $value["numero_lote"]);
        $this->db->where('id', $value["id"]);
        $this->db->update('nomina');
    }

    public function countNumerLote($lote)
    {
        $this->db->where('numero_lote', $lote);
        return $this->db->count_all_results('nomina');
    }

    public function updateFechaValor($value){
        $this->db->set('fecha', $value["fecha"]);
        $this->db->where('id_nomina', $value["id_nomina"]);
        $this->db->update('nomina_detalle');
    }
    
    
    public function updateFechaPago($value){
        $this->db->set('fecha_pago', $value["fecha"]);
        $this->db->where('id', $value["id_nomina"]);
        $this->db->update('nomina');
    }


    public function updateEstatusNominabyId($idNomina, $idEstatus)
    {
        $this->db->set('id_estatus', $idEstatus);
        $this->db->where('id', $idNomina);
        $this->db->update('nomina');
    }
    

    public function updateEstatusNominaDetallebyId($idNomina, $idEstatus)
    {
        $this->db->set('id_estatus', $idEstatus);
        $this->db->where('id_nomina', $idNomina);
        $this->db->update('nomina_detalle');
    }

    public function updateEstatusNominaDetallePagadas($idNomina, $idEstatus)
    {
        $this->db->set('id_estatus', $idEstatus);
        $this->db->where('id_nomina', $idNomina);
        $this->db->where('id_estatus', 2);
        $this->db->update('nomina_detalle');
    }

    public function updateNominaProccessed($idnomina)
    {
        $this->db->trans_start();
        
        $this->db->set('id_estatus', 4);
        $this->db->where('id', $idnomina);
        $this->db->update('nomina');
        
        $this->db->set('id_estatus', 3);
        $this->db->where('id_nomina', $idnomina);
        $this->db->where('id_estatus', 2);
        $this->db->update('nomina_detalle');
        
        $this->db->trans_complete();
        
        if ($this->db->trans_status() === FALSE) {
            return false;
        } else {
            return true;
        }
    }

    public function updateEstatusNominaDetallebyReferenciaCredito($referenciaCredito, $idEstatus)
    {
        $this->db->set('id_estatus', $idEstatus);
        $this->db->where('referencia_credito', $referenciaCredito);
        // $this->db->where("id_nomina", $idnomina);
        return $this->db->update('nomina_detalle');
    }

    public function getCantidadEstatusNominabyReferenciaCredito($referenciaCredito, $idnomina)
    {
        $this->db->where('referencia_credito', $referenciaCredito);
        $this->db->where("id_nomina", $idnomina);
        return $this->db->count_all_results('nomina_detalle');
    }

    public function getCantidadEstatusNominabyRefCredito($referenciaCredito)
    {
        $this->db->where('referencia_credito', $referenciaCredito);
        // $this->db->where("id_nomina", $idnomina);
        return $this->db->count_all_results('nomina_detalle');
    }

    public function getnominaByRefCredito($referenciaCredito)
    {
        $this->db->select('id_nomina');
        $this->db->where('referencia_credito', $referenciaCredito);
        // $this->db->where("id_nomina", $idnomina);
        return $this->db->get('nomina_detalle');
    }

    public function nominaById($id)
    {
        $this->db->select("descripcion");
        $this->db->where('id', $id);
        // $this->db->where("id_nomina", $idnomina);
        return $this->db->get('nomina');
    }

    public function getEstausNominaDetalle()
    {
        return $this->db->get("estatus_nomina_detalle");
    }

    public function getHistoryPayments($gerencia, $proyecto, $estatus, $descripcion,$fecha_pago)
    {
        
        // echo "seleccion: proyecto: ". $proyecto." gerencia: ".$gerencia." estatus ".$estatus." descripción ".$descripcion;
        $sql = "SELECT `p`.`nombre` `proyecto`, `n`.`descripcion`, `g`.`nombre`  `gerencia`, `n`.`numero_lote`,DATE_FORMAT(`n`.`fecha_creacion`,'%d/%m/%Y') as fecha_creacion ,DATE_FORMAT(`n`.`fecha_pago`,'%d/%m/%Y') as fecha_pago, `en`.`nombre` `estatus`, `n`.`id`,
                                pendiente.pendiente,procesada.procesada,pagada.pagada,pagada_BS.pagada_BS,rechazada.rechazada,rechazada_BS.rechazada_BS,total.total,  total_BS.total_BS
                                FROM (`nomina` `n`)
                                INNER JOIN `estatus_nomina` `en` ON `en`.`id` = `n`.`id_estatus`
                                INNER JOIN `proyecto` `p` ON `p`.`id` = `n`.`id_proyecto`
                                INNER JOIN `gerencia` `g` ON `g`.`id` = `n`.`id_gerencia`
                                        LEFT JOIN (SELECT DISTINCT id_nomina,id_estatus, COUNT(*) pendiente
                                FROM nomina_detalle
        						WHERE id_estatus = 1
        						GROUP BY id_nomina,id_estatus) pendiente ON `pendiente`.`id_nomina` = `n`.`id`
                                        LEFT JOIN (SELECT DISTINCT id_nomina,id_estatus, COUNT(*) procesada
        						FROM nomina_detalle
        						WHERE id_estatus = 2
        						GROUP BY id_nomina,id_estatus) procesada ON `procesada`.`id_nomina` = `n`.`id`
                                        LEFT JOIN (SELECT DISTINCT id_nomina,id_estatus, COUNT(*) pagada
        						FROM nomina_detalle
        						WHERE id_estatus = 3
        						GROUP BY id_nomina,id_estatus) pagada ON `pagada`.`id_nomina` = `n`.`id`
						LEFT JOIN (SELECT DISTINCT id_nomina, SUM(credito) pagada_BS
        						FROM nomina_detalle
        						WHERE id_estatus = 3
        						GROUP BY id_nomina,id_estatus) pagada_BS ON `pagada_BS`.`id_nomina` = `n`.`id`
                                        LEFT JOIN (SELECT DISTINCT id_nomina,id_estatus, COUNT(*) rechazada
        						FROM nomina_detalle
        						WHERE id_estatus = 4
        						GROUP BY id_nomina,id_estatus) rechazada ON `rechazada`.`id_nomina` = `n`.`id`
					LEFT JOIN (SELECT DISTINCT id_nomina, SUM(credito) rechazada_BS
        						FROM nomina_detalle
        						WHERE id_estatus = 4
        						GROUP BY id_nomina,id_estatus) rechazada_BS ON `rechazada_BS`.`id_nomina` = `n`.`id`
                                        LEFT JOIN (SELECT DISTINCT id_nomina, COUNT(*) total
        						FROM nomina_detalle
        						GROUP BY id_nomina) total ON `total`.`id_nomina` = `n`.`id`
					LEFT JOIN (SELECT DISTINCT id_nomina, SUM(credito) total_BS
        						FROM nomina_detalle
        						GROUP BY id_nomina) total_BS ON `total_BS`.`id_nomina` = `n`.`id`";
        
        $where = false;
        
        // agregamos las ondiciones del filtro de búsqueda
        if (! $gerencia == null && ! $gerencia == '') {
            if (! $where) {
                $sql = $sql . "WHERE n.id_gerencia=" . $gerencia . " ";
                $where = true;
            } else {
                $sql = $sql . "AND n.id_gerencia=" . $gerencia . " ";
            }
        }
        
        if (! $proyecto == null && ! $proyecto == '') {
            if (! $where) {
                $sql = $sql . "WHERE n.id_proyecto=" . $proyecto . " ";
                $where = true;
            } else {
                $sql = $sql . "AND n.id_proyecto=" . $proyecto . " ";
            }
        }
        
        if (! $estatus == null && ! $estatus == '') {
            if (! $where) {
                $sql = $sql . "WHERE n.id_estatus=" . $estatus . " ";
                $where = true;
            } else {
                $sql = $sql . "AND n.id_estatus=" . $estatus . " ";
            }
        }
        
        if (! $descripcion == null && ! $descripcion == '') {
            if (! $where) {
                $sql = $sql . "WHERE n.descripcion like '%" . trim($descripcion) . "%'";
                $where = true;
            } else {
                $sql = $sql . "AND n.descripcion like '%" . trim($descripcion) . "%' ";
            }
        }
        if (! $fecha_pago == null && ! $fecha_pago == '') {
            if (! $where) {
                $sql = $sql . "WHERE n.fecha_pago like '%" .($fecha_pago) . "%'";
                $where = true;
            } else {
                $sql = $sql . "AND n.fecha_pago like'%" .($fecha_pago) . "%'";
            }
        }
        $sql = $sql . "ORDER BY n.fecha_creacion desc";
        $result = $this->db->query($sql);
        return $result;
    }

    public function getPaymentsDetail($id, $limit, $start, $estatus)
    {
        if ($estatus != "0") {
            $query = "SELECT DISTINCT `nd`.`beneficiario`, `c`.`nombre` `cargo`, `nd`.`id_tipo_documento_identidad` `tipo_documento`,
                                    `nd`.`documento_identidad`, `nd`.`numero_cuenta` `cuenta`, `nd`.`credito`, `tc`.`descripcion` `tipo_cuenta`,
                                    `tp`.`descripcion` `tipo_pago`, `b`.`nombre` `banco`, `en`.`nombre` `estatus`, `nd`.`referencia_credito`
                         FROM (`nomina_detalle` `nd`, `nomina_detalle`) 
                         INNER JOIN `cargo` `c` ON `c`.`id` = `nd`.`id_cargo`
                         INNER JOIN `tipos_cuentas` `tc` ON `tc`.`tipo` = `nd`.`id_tipo_cuenta` 
                         INNER JOIN `tipo_pago` `tp` ON `tp`.`id` = `nd`.`id_tipo_pago`
                         INNER JOIN `banco` `b` ON `b`.`id` = `nd`.`id_banco`
                         INNER JOIN `estatus_nomina_detalle` `en` ON `en`.`id` = `nd`.`id_estatus`
                        WHERE `nd`.`id_estatus` = " . $estatus . " AND `nd`.`id_nomina` = " . $id . "  LIMIT " . $start . "," . $limit;
        } else {
            $query = "SELECT DISTINCT `nd`.`beneficiario`, `c`.`nombre` `cargo`, `nd`.`id_tipo_documento_identidad` `tipo_documento`,
                                    `nd`.`documento_identidad`, `nd`.`numero_cuenta` `cuenta`, `nd`.`credito`, `tc`.`descripcion` `tipo_cuenta`,
                                    `tp`.`descripcion` `tipo_pago`, `b`.`nombre` `banco`, `en`.`nombre` `estatus`, `nd`.`referencia_credito`
                         FROM (`nomina_detalle` `nd`, `nomina_detalle`)
                         INNER JOIN `cargo` `c` ON `c`.`id` = `nd`.`id_cargo`
                         INNER JOIN `tipos_cuentas` `tc` ON `tc`.`tipo` = `nd`.`id_tipo_cuenta`
                         INNER JOIN `tipo_pago` `tp` ON `tp`.`id` = `nd`.`id_tipo_pago`
                         INNER JOIN `banco` `b` ON `b`.`id` = `nd`.`id_banco`
                         INNER JOIN `estatus_nomina_detalle` `en` ON `en`.`id` = `nd`.`id_estatus`
                        WHERE  `nd`.`id_nomina` = " . $id . "  LIMIT " . $start . "," . $limit;
        }
        
        $result = $this->db->query($query);
        
        return $result;
    }

    public function get_total_detail($estatus, $id)
    {
        if ($estatus != "0") {
            $this->db->where("id_estatus", $estatus);
        }
        $this->db->where("id_nomina", $id);
        $result = $this->db->count_all_results("nomina_detalle");
        // echo $this->db->last_query();
        return $result;
    }
}

