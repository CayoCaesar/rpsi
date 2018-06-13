<?php 
class audit extends CI_Controller{
    public function __construct()
    {
        parent::__construct();
       /* if ($this->uri->uri_string()) {
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
            }            // Si no se llega desde un enlace se redirecciona al inicio
            else {
                // Podemos aqui crear un mensaje antes de redirigir que informe
                redirect(base_url());
            }
        }*/
       
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
        $data = new stdClass();
       // $this->load->model('Error_model'); modelo a cargar

    }
    
   
    public function consultar()
    {
       
        $this->form_validation->set_rules('codigo_centrovotacion', 'C&oacute;digo de centro de votacion', 'trim|required|xss_clean|exact_length[9]', array(
            'required' => 'El centro de votaci&oacute;n es requerido',
            'numeric' => 'El centro de votaci&oacute;n solo permite numeros',
            'exact_length' => 'El centro de votaci&oacute;n debe indicar 9 digitos'
        ));
        $this->form_validation->set_rules('mesa', 'Mesa', 'trim|required|xss_clean|numeric|min_length[1]|max_length[2]', array(
            'required' => 'La mesa es requerida',
            'numeric' => 'La mesa solo permite numeros',
            'min_length' => 'La mesa debe indicar al menos 1 digitos',
            'max_length' => 'La mesa debe indicar m&aacute;ximo 2 digitos'
        ));
        if ($this->form_validation->run() == FALSE) {
            
            $this->load->view('templates/header');
            $this->load->view('templates/navigation');
            $this->load->view('audit/audit_consultar');
            $this->load->view('templates/footer');
        } else {
            
            $centrovotacionmesa = $this->input->post('codigo_centrovotacionmesa');
            echo("<script>console.log('centrovotacionmesa: ".json_encode($centrovotacionmesa)."');</script>");
            
            
       
                
                $result = $this->MaquinaVotacion_model->getDetailVotingMachine($centrovotacion, $mesa);
                echo("<script>console.log('maquina de votación: ".json_encode($result->result())."');</script>");
                $dataVotingMachine = array(
                    'consulta' => $result
                );
                
                /**
                 * $result = $this->MaquinaVotacion_model->getDetailVotingMachine($centrovotacion, $mesa);
                 
                 if ($result != null) {
                 $dataVotingMachine = $result->result();
                 $dataVotingMachine[0]->id;
                 */
                
                if ($result != null) {
                    $fila=$result->result();
                     }
                                             
        }
    }
    
    public function consultada()
    {
      //  $data = $this->data;
        $data = new stdClass();
        
        if ($this->input->post('id') != null) {
            $idmaquina = $this->input->post('id'); // anteriormente se obtenía el valor por la constante post, sin embargo se perdía el valor cuando se actualizaba la páginación.
        } else {
            $idmaquina = $this->UsuarioMaquina_model->getMaquinaIDByUser($_SESSION['id']);
        }
        $data->consulta = $this->MaquinaVotacion_model->getDetailTestVotingMachine($idmaquina);
        
        // obtenemos el centro y mesa de votacion
        $query=$data->consulta->result_array();
        $centro_votacion = $query[0]['codigo_centrovotacion'];
        $mesa = $query[0]['mesa'];
        
        $data->errormv = $this->Error_model->getError();
        $data->tiporeemplazo = $this->TipoReemplazo_model->getTipoReemplazo();
        $fila = $data->consulta->result();
        $usuariomaquina = array();
        $usuariomaquina["id_usuario"] = $_SESSION['id'];
        $usuariomaquina["id_maquina"] = $fila[0]->id;
        
        if ($this->UsuarioMaquina_model->getmaquina($usuariomaquina) > 0) {
            $data = new stdClass();
            $data->error = "la m&aacute;quina ya se ecuentra selccionada por otro usuario.";
            $this->data = $data;
            $this->index();
        } else {
            if ($this->UsuarioMaquina_model->getusuarioMaquina($usuariomaquina) == 0) {
                // marcamos la mesa como seleccionada para el usuario
                $this->UsuarioMaquina_model->selccionarMesa($usuariomaquina);
            }
        }
        $this->load->view('templates/header');
        $this->load->view('templates/navigation');
        $this->load->view('audit/audit_detail', $data);
        $this->load->view('templates/footer');
       
      
    }
  
    
}
?>