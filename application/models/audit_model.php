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

    public function saveVotesAudit($cod_voto, $id_boleta, $id_maquina, $finalizado) {

        $result=$this->db->query("INSERT INTO voto (cod_voto, id_opcion_boleta, id_maquina, finalizado) 
                                  VALUES ('" . $cod_voto . "', '" . $id_boleta . "', '" . $id_maquina . "', '" . $finalizado . "') 
                                  ON DUPLICATE KEY UPDATE cod_voto = '" . $cod_voto . "', id_opcion_boleta = '" . $id_boleta . "', id_maquina = '" . $id_maquina . "', finalizado = '" . $finalizado . "'");

        if ($result){
            return $result;
        }else {
            return null;
        }

    }

    public function getVotesAuditByMv($id_maquina) {

        $result=$this->db->query("SELECT
                                    voto.cod_voto,
                                    cargo.descripcion AS cargo,
                                    candidato.candidato AS candidato,
                                    organizacion_politica.organizacion_politica AS organizacion_politica
                                    FROM voto
                                    INNER JOIN opcion_boleta ON id_opcion_boleta = opcion_boleta.id
                                    INNER JOIN postulacion ON opcion_boleta.id_postulacion = postulacion.id
                                    INNER JOIN cargo ON postulacion.id_cargo = cargo.id
                                    INNER JOIN candidato ON postulacion.id_candidato = candidato.id
                                    INNER JOIN organizacion_politica ON opcion_boleta.id_organizacion_politica = organizacion_politica.id
                                    WHERE id_maquina = '" . $id_maquina . "'");
        if ($result->num_rows()>0) {
            return $result;
        } else {
            return null;
        }
    }

    public function finishAudit($id_maquina) {

        $result=$this->db->query("UPDATE voto
                                    SET finalizado = '1'
                                    WHERE id_maquina = '" . $id_maquina . "'");
        if ($result) {
            return $result;
        } else {
            return null;
        }
    }

    public function getAuditStatus($id_maquina) {

        $result=$this->db->query("SELECT finalizado
                                    FROM voto
                                    WHERE finalizado = '0' and id_maquina = '" . $id_maquina . "'");

        if ($result->num_rows()>0) {
            return $result;
        } else {
            return null;
        }
    }

    public function getCargoCandidatoParido($codigo_centrovotacion, $mesa) {

        $result=$this->db->query("SELECT
                                    opcion_boleta.id as id_opcion_boleta,
                                    cargo.id as id_cargo,
                                    cargo.descripcion as cargo,
                                    candidato.candidato,
                                    organizacion_politica.organizacion_politica
                                    FROM opcion_boleta
                                    INNER JOIN postulacion ON opcion_boleta.id_postulacion=postulacion.id
                                    INNER JOIN cargo ON postulacion.id_cargo=cargo.id
                                    INNER JOIN candidato ON postulacion.id_candidato=candidato.id
                                    INNER JOIN organizacion_politica ON opcion_boleta.id_organizacion_politica=organizacion_politica.id
                                    WHERE codigo_centrovotacion='010101001' AND mesa='1'
                                    ORDER BY opcion_boleta.orden ASC");

        if ($result->num_rows()>0) {
            return $result;
        } else {
            return null;
        }
    }

    public function getCurrentVote($id_maquina) {

        $result=$this->db->query("SELECT MAX(cod_voto)
                                    FROM voto
                                    INNER JOIN maquina_votacion on voto.id_maquina=maquina_votacion.id
                                    WHERE voto.id_maquina='" . $id_maquina . "'");

        if ($result->num_rows()>0) {
            return $result;
        } else {
            return null;
        }
    }

}