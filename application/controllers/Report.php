<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Report class.
 *
 * @extends CI_Controller
 */

class Report extends CI_Controller
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
        
        // Para impedir el acceso directo desde la URL
        // Validamos si es el path principal ? , si lo es deje accesar desde url
        if ($this->uri->uri_string()) {
            // Carga Libraria User_agent
            $this->load->library('user_agent');
            // Verifica si llega desde un enlace
            if ($this->agent->referrer()) {
                // Busca si el enlace llega de una URL diferente
                $post = strpos($this->agent->referrer(), base_url());
                if ($post === FALSE) {
                    // Podemos aqui crear un mensaje antes de redirigir que informe
                    redirect(base_url());
                }
            } // Si no se llega desde un enlace se redirecciona al inicio
            else {
                // Podemos aqui crear un mensaje antes de redirigir que informe
                redirect(base_url());
            }
        }
        $this->load->model('MaquinaVotacion_model');
        $this->load->model('Error_model');
        $this->load->model('Contingencia_model');
    }

    public function index()
    {
        $data = new stdClass();
        
        $resultCountModeloEstatus = $this->MaquinaVotacion_model->getCountModeloEstatus();
        $resultCountMedioTransmision = $this->MaquinaVotacion_model->getCountMedioTransmision();
        $resultCountTipReemplazo = $this->MaquinaVotacion_model->getCountTipReemplazo();
        $mv = $this->MaquinaVotacion_model->getModelosMV();
        $mt = $this->MaquinaVotacion_model->getCountTotalMedioTransmision();
        $tr = $this->MaquinaVotacion_model->getCountTotalTipReemplazo();

        $resultCountErrorTipo= $this->Error_model->getCountErrorTipo();
        $resultTotalErrorTipo= $this->Error_model->getTotalErrorTipo();

        // echo count($mv->result());
        $reports = array();
        
        foreach ($mv->result() as $modelomv) {
            $report = array();
            $report["modelo_maquina"] = $modelomv->modelo_maquina;
            if (count($resultCountModeloEstatus) > 0) {
                foreach ($resultCountModeloEstatus->result() as $resultCountModelo) {
                    if ($resultCountModelo->modelo_maquina == $modelomv->modelo_maquina) {
                        $report[$resultCountModelo->estatus] = $resultCountModelo->cantidad;
                    }
                }
            }
            
            if (count($resultCountMedioTransmision) > 0) {
                foreach ($resultCountMedioTransmision->result() as $resultCountMedio) {
                    if ($resultCountMedio->modelo_maquina == $modelomv->modelo_maquina) {
                        $report[$resultCountMedio->medio_transmision] = $resultCountMedio->cantidad;
                    }
                }
            }
            
            if (count($resultCountTipReemplazo) > 0) {
                foreach ($resultCountTipReemplazo->result() as $resultCountTipo) {
                    if ($resultCountTipo->modelo_maquina == $modelomv->modelo_maquina) {
                        $report[$resultCountTipo->descripcion] = $resultCountTipo->cantidad;
                    }
                }
            }
            
            array_push($reports, $report);
        }
        
        $data->countModelo = $mv;
        $data->mediotrans = $mt;
        $data->reemplazo = $tr;
        $data->countErrorTipo = $resultCountErrorTipo;
        $data->totalErrorTipo = $resultTotalErrorTipo;

        $data->reports = $reports;
        
        $this->load->view('templates/header');
        $this->load->view('templates/navigation', $data);
        $this->load->view('report/report', $reports);
        $this->load->view('templates/footer');
    }

    // Show index page
    public function report_mv()
    {
        $this->load->view('templates/header');
        $this->load->view('templates/navigation');
        $this->load->view('report/search_voting_machine');
        $this->load->view('templates/footer');
    }

    public function consulta_report_mv()
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
            'numeric' => 'La mesa solo permite números',
            'min_length' => 'La mesa debe indicar al menos 1 digitos',
            'max_length' => 'La mesa debe indicar m&aacute;ximo 2 digitos'
        ));

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('templates/header');
            $this->load->view('templates/navigation');
            $this->load->view('report/search_voting_machine');
            $this->load->view('templates/footer');
        } else {

            $centrovotacion = $this->input->post('codigo_centrovotacion');
            $mesa = $this->input->post('mesa');

            $result = $this->MaquinaVotacion_model->getDetailVotingMachine($centrovotacion, $mesa);
            $maquina_votacion = $result->row();
            $id_maquina = $maquina_votacion->id;
            $contingencia = $this->Contingencia_model->getReemplazosByMv($id_maquina);
            $errores = $this->Contingencia_model->getErrorsByMv($id_maquina);
            $votantes = $this->Contingencia_model->getVotersByCentroMesa($centrovotacion, $mesa);
            $operador = $this->Contingencia_model->getEmpleado($_SESSION["id"]);

            $prueba = $result->result();
            //var_dump($prueba);

            $dataVotingMachine = array(
                'consulta' => $result,
                'contingencia' => $contingencia,
                'errors' => $errores,
                'voters' => $votantes,
                'user' => $operador
            );

            if ($result != null) {
                $this->load->view('templates/header');
                $this->load->view('templates/navigation');
                $this->load->view('report/detail_voting_machine', $dataVotingMachine);
                $this->load->view('templates/footer');
            } else {
                $data->error = "No se encontrar&oacute;n los datos consultados.";
                $this->load->view('templates/header');
                $this->load->view('templates/navigation', $data);
                $this->load->view('report/search_voting_machine');
                $this->load->view('templates/footer');
            }
        }
    }

    public function pdf_gen()
    {
        $centrovotacion = $this->input->post('codigo_centrovotacion');
        $mesa = $this->input->post('mesa');

        $result = $this->MaquinaVotacion_model->getDetailVotingMachine($centrovotacion, $mesa);
        $maquina_votacion = $result->row();
        $id_maquina = $maquina_votacion->id;
        $contingencia = $this->Contingencia_model->getReemplazosByMv($id_maquina);
        $errores = $this->Contingencia_model->getErrorsByMv($id_maquina);
        $votantes = $this->Contingencia_model->getVotersByCentroMesa($centrovotacion, $mesa);
        $operador = $this->Contingencia_model->getEmpleado($_SESSION["id"]);

        $dataVotingMachine = array(
            'consulta' => $result,
            'contingencia' => $contingencia,
            'errors' => $errores,
            'voters' => $votantes,
            'user' => $operador
        );
        //load the view and saved it into $html variable
        $html=$this->load->view('report/report_pdf', $dataVotingMachine, true);

        //this the the PDF filename that user will get to download
        $time = time();
        $pdfFilePath = "reporte_pruebas_mv_". $centrovotacion . "_" . $mesa . ".pdf";

        //load mPDF library
        $this->load->library('m_pdf');

        //generate the PDF from the given html
        $this->m_pdf->pdf->WriteHTML($html);

        //download it.
        $this->m_pdf->pdf->Output($pdfFilePath, "D");
    }

}