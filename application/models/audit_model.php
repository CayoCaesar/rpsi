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

    public function saveVotesAudit($cod_voto, $id_boleta, $id_maquina) {

        $result=$this->db->query("INSERT INTO voto (cod_voto, id_opcion_boleta, id_maquina) 
                                  VALUES ('" . $cod_voto . "', '" . $id_boleta . "', '" . $id_maquina . "') 
                                  ON DUPLICATE KEY UPDATE cod_voto = '" . $cod_voto . "', id_opcion_boleta = '" . $id_boleta . "', id_maquina = '" . $id_maquina . "'");

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

    public function getVotesAuditReportPDFByMv($id_maquina) {

        $result=$this->db->query("SELECT 
                                 COUNT(*) as num_votos,
                                 cargo.descripcion as cargo,
                                 organizacion_politica.siglas AS organizacion_politica,
                                 candidato.candidato AS candidato
                                FROM voto
                                INNER JOIN opcion_boleta ON id_opcion_boleta = opcion_boleta.id
                                INNER JOIN postulacion ON opcion_boleta.id_postulacion = postulacion.id
                                INNER JOIN cargo ON postulacion.id_cargo = cargo.id
                                INNER JOIN candidato ON postulacion.id_candidato = candidato.id
                                INNER JOIN organizacion_politica ON opcion_boleta.id_organizacion_politica = organizacion_politica.id
                                WHERE id_maquina = '" . $id_maquina . "'
                                GROUP BY organizacion_politica.siglas
                                order by cargo.descripcion, candidato.candidato");
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
                                    WHERE codigo_centrovotacion='" . $codigo_centrovotacion . "' AND mesa='" . $mesa . "'
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