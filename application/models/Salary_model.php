<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Cargo_model class.
 *
 * @extends CI_Model
 */
class Salary_model extends CI_Model
{

    /**
     * __construct function.
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
    
     public function getGerenciaCargo($gerencia)
    {
        
        // echo "seleccion: proyecto: ". $proyecto." gerencia: ".$gerencia." estatus ".$estatus." descripción ".$descripcion;
        $sql = "SELECT cargo.`nombre` as cargo, sueldo, cantidad,total,id_cargo
                 FROM salario
                INNER JOIN cargo ON salario.`id_cargo`= cargo.`id`
                INNER JOIN gerencia ON cargo.`id_gerencia`= gerencia.`id`
                 WHERE id_gerencia = ' " . $gerencia . " '";
        
        $where = true;
        
        // agregamos las ondiciones del filtro de búsqueda
        if (! $gerencia == null && ! $gerencia == '') {
            if (! $where) {
                $sql = $sql . "WHERE id_gerencia=" . $gerencia . " ";
                $where = true;
            } else {
                $sql = $sql . "AND id_gerencia=" . $gerencia . " ";
            }
        }
      
        $result = $this->db->query($sql);
        return $result;
    }
    
    public function updateSalary($idcargo,$sueldo,$cantidad)
    {
        
       /* $array = array (
            "id_cargo"=>$idcargo,
            "sueldo"=>$sueldo,
            "cantidad"=>$cantidad,
        );*/
        $this->db->where("id_cargo",$idcargo);
        $this->db->set("sueldo",$sueldo);
        $this->db->set("cantidad",$cantidad);
        $this->db->set("total",$sueldo * $cantidad);
        $q= $this->db ->update("salario");
        
        
        
       return $this->db->affected_rows();
        
        
        
        /*$sql = "  UPDATE `salario` 
                     SET `sueldo` = " . $info['sueldo'] . ", `cantidad` =  " . $info['cantidad'] . ", 
                     `monto` = " . $info['sueldo'] . " * " . $info['cantidad']. "
                     where `id_cargo` =".$info['id_cargo']."";

        
        $result = $this->db->query($sql);*/
        
    }
    
    public function gettotal($id_gerencia)
    {
        $this->db->select('SUM(total) as total');
        $this->db->from('salario');
        $this->db->join('cargo', 'salario.id = cargo.id');
        $this->db->where('id_gerencia', $id_gerencia);
        $query = $this->db->get();
        return $query->result();
      
    }
}