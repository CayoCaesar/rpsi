<?php
/**
 * Created by PhpStorm.
 * User: Humberto Fernández
 * Date: 4/6/2018
 * Time: 4:43 PM
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * User_model class.
 *
 * @extends CI_Model
 */
class Contingencia_model extends CI_Model
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

    public function getReemplazosByMv($id_maquina){
        $result=$this->db->query("SELECT proceso_reemplazo.id, tipo_reemplazo.descripcion as reemplazo, proceso_reemplazo.entregado, fase.descripcion as fase
                                FROM proceso_reemplazo
                                INNER JOIN fase ON proceso_reemplazo.id_fase = fase.id
                                INNER JOIN tipo_reemplazo ON proceso_reemplazo.id_reemplazo = tipo_reemplazo.id
                                INNER JOIN proceso ON proceso_reemplazo.id_proceso = proceso.id
                                INNER JOIN maquina_votacion ON proceso.id_maquina_votacion = maquina_votacion.id
                                WHERE proceso.id_maquina_votacion = '". $id_maquina ."' AND proceso_reemplazo.entregado = 0");
        if ($result->num_rows()>0){
            return $result;
        }else {
            return null;
        }
    }

    public function liberarReemplazos($reemplazos, $fechafin) {
        $result=$this->db->query("UPDATE proceso_reemplazo
                                    SET entregado=1, fechafin='".$fechafin."'
                                    WHERE id IN ($reemplazos)");
        return $result;
    }

    public function getErrorsByMv($id_maquina) {
        $result=$this->db->query("SELECT proceso_error.id, error.descripcion, maquina_votacion.id_estatus_maquina
                                    FROM proceso_error
                                    INNER JOIN error ON proceso_error.id_error = error.id
                                    INNER JOIN proceso ON proceso_error.id_proceso = proceso.id
                                    INNER JOIN maquina_votacion ON proceso.id_maquina_votacion = maquina_votacion.id
                                    WHERE proceso.id_maquina_votacion = '56'");
        if ($result->num_rows()>0){
            return $result;
        }else {
            return null;
        }
    }

    public function getVotersByCentroMesa($centro_votacion, $mesa) {
        $result=$this->db->query("SELECT documento_identidad, nombre, apellido, voto
                                    FROM votantes
                                    WHERE codigo_centrovotacion='10101001' AND mesa='1' AND voto='1'");
        if ($result->num_rows()>0){
            return $result;
        }else {
            return null;
        }
    }

    public function getEmpleado($id_empleado) {
        $result=$this->db->query("SELECT nombre, apellido
                                    FROM empleado
                                    WHERE id='1618';");
        if ($result->num_rows()>0){
            return $result;
        }else {
            return null;
        }
    }

}