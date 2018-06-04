<?php
/**
 * Created by PhpStorm.
 * User: Humberto Fernández
 * Date: 4/6/2018
 * Time: 9:39 AM
 */

class Contingencia extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        // load db
        $this->load->database();

        // load Pagination library
        $this->load->library('pagination');

        // load URL helper
        $this->load->helper('url');

        // Load form helper library
        $this->load->helper('form');

        // Load form validation library
        $this->load->library('form_validation');

        // Load session library
        $this->load->library('session');

        $this->load->model('Error_model');
        $this->load->model('TipoReemplazo_model');
        $this->load->model('MaquinaVotacion_model');
        $this->load->model('Proceso_model');
        $this->load->model('Fase_model');
        $this->load->model('UsuarioMaquina_model');
        $this->load->model('User_model');
        $this->load->model('Contingencia_model');
        $data = new stdClass();
    }

    // Show index page
    public function index()
    {
        $this->load->view('templates/header');
        $this->load->view('templates/navigation');
        $this->load->view('contingencia/search_voting_machine');
        $this->load->view('templates/footer');
    }

    public function consulta()
    {
        $data = new stdClass();
        // validaciones de formulario
        $this->form_validation->set_rules('codigo_centrovotacion', 'C&oacute;digo de centro de votaci&oacute;n', 'trim|required|xss_clean|numeric|exact_length[9]', array(
            'required' => 'El centro de votaci&oacute;n es requerido',
            'numeric' => 'El centro de votaci&oacute;n s&oacute;lo permite n&uacute;meros',
            'exact_length' => 'El centro de votaci&oacute;n debe ser de 9 digitos'
        ));
        $this->form_validation->set_rules('mesa', 'Mesa', 'trim|required|xss_clean|numeric|min_length[1]|max_length[2]', array(
            'required' => 'La mesa es requerida',
            'numeric' => 'La mesa solo permite numeros',
            'min_length' => 'La mesa debe indicar al menos 1 digitos',
            'max_length' => 'La mesa debe indicar m&aacute;ximo 2 digitos'
        ));

        if ($this->form_validation->run() == FALSE) {
            log_message('info', 'Voting_machine|resettest|validacion run');

            $this->load->view('templates/header');
            $this->load->view('templates/navigation');
            $this->load->view('contingencia/search_voting_machine');
            $this->load->view('templates/footer');
        } else {
            log_message('info', 'Voting_machine|resettest|validacion else run');

            $centrovotacion = $this->input->post('codigo_centrovotacion');
            $mesa = $this->input->post('mesa');

            echo "<script>console.log( 'centro votación: " . $centrovotacion . "' );</script>";
            echo "<script>console.log( 'mesa: " . $mesa . "' );</script>";

            $result = $this->MaquinaVotacion_model->getDetailVotingMachine($centrovotacion, $mesa);
            $maquina_votacion = $result->row();
            $id_maquina = $maquina_votacion->id;
            $contingencia = $this->Contingencia_model->getReemplazosByMv($id_maquina);

            $dataVotingMachine = array(
                'consulta' => $result,
                'contingencia' => $contingencia
            );

            if ($result != null) {
                $this->load->view('templates/header');
                $this->load->view('templates/navigation');
                $this->load->view('contingencia/detail_voting_machine', $dataVotingMachine);
                $this->load->view('templates/footer');
            } else {
                $data->error = "No se encontrar&oacute;n los datos consultados.";
                $this->load->view('templates/header');
                $this->load->view('templates/navigation', $data);
                $this->load->view('contingencia/search_voting_machine');
                $this->load->view('templates/footer');
            }
//
//            if ($result != null) {
//                log_message('info', 'Voting_machine|resettest|result null');
//                $dataVotingMachine = $result->result();
//                $dataVotingMachine[0]->id;
//                $result = $this->MaquinaVotacion_model->resetVotingMachine($dataVotingMachine[0]->id, $seleccionada);
//
//                if ($result) {
//                    $data->success = "Reiniciada Exitosamente";
//                } else {
//                    $data->error = "Error al Reiniciar M&aacute;quina Votaci&oacute;n";
//                }
//                $this->load->view('templates/header');
//                $this->load->view('templates/navigation', $data);
//                $this->load->view('test/reset_test_voting_machine');
//            } else {
//                log_message('info', 'Voting_machine|resettest|result else');
//                $data->error = "No se encontr&oacute; el n&uacute;mero consultado.";
//                $this->load->view('templates/header');
//                $this->load->view('templates/navigation', $data);
//                $this->load->view('test/reset_test_voting_machine');
//                $this->load->view('templates/footer');
//            }
        }
        log_message('info', 'Voting_machine|resettest|fin');
    }

    public function cancelar()
    {
        $this->index();
    }

}