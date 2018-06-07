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
        $result=$this->db->query("SELECT proceso_reemplazo.id, tipo_reemplazo.descripcion, proceso_reemplazo.entregado
                                FROM proceso_reemplazo
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

}