<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * RolMenu_model class.
 *
 * @extends CI_Model
 */
class Audit_model extends CI_Model {
    
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
    
    
    /**
     * get_rol_menud_from_rolid function.
     *
     * @access public
     * @param mixed $id
     * @return int the id menu
     */
    public function getCargos() {
        
        $result=$this->db->query("SELECT * FROM cargo ");
        
        if ($result->num_rows()>0){
            return $result;
        }else {
            return null;
        }
        
    }
    public function getCandidatos() {
        
        $result=$this->db->query("SELECT * FROM candidato ");
        
        if ($result->num_rows()>0){
            return $result;
        }else {
            return null;
        }
        
    }
    
    public function getOrganizacionPoliticas() {
        
        $result=$this->db->query("SELECT * FROM organizacion_politica ");
        
        if ($result->num_rows()>0){
            return $result;
        }else {
            return null;
        }
        
    }
  
}