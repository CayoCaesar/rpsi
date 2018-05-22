<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class Paging extends CI_Controller {
    public function __construct()
  {
        parent::__construct();
     
        $this->load->database();
        
        // load Pagination library
        $this->load->library('pagination');
         
        // load URL helper
        $this->load->helper('url');
    }
     
    public function index() 
    {
        // load db and model
      
        $this->load->model('Users_model');
 
        // init params
        $params = array();
        $limit_per_page = 1;
        $start_index = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $total_records = $this->Users_model->get_total();
 
        if ($total_records > 0) 
        {
            // get current page records
            $params["results"] = $this->Users_model->get_current_page_records($limit_per_page, $start_index);
             
            $config['total_rows'] = $total_records;
            $config['per_page'] = $limit_per_page;
            $config["uri_segment"] = 3;
             
            $this->pagination->initialize($config);
             
            // build paging links
            $params["links"] = $this->pagination->create_links();
        }
        $this->load->view('templates/header');
        $this->load->view('templates/navigation',$this->data);
        $this->load->view('test/user_listing', $params);
        $this->load->view('templates/footer');
    }
     
    public function custom()
    {
        // load db and model
        $this->load->database();
        $this->load->model('Users_model');
     
        // init params
        $params = array();
        $limit_per_page = 2;
        $page = ($this->uri->Users_model(3)) ? ($this->uri->segment(3) - 1) : 0;
        $total_records = $this->Users_model->get_total();
     
        if ($total_records > 0)
        {
            // get current page records
            $params["results"] = $this->Users_model->get_current_page_records($limit_per_page, $page*$limit_per_page);
                 
            
      
            $config['total_rows'] = $total_records;
            $config['per_page'] = $limit_per_page;
            $config["uri_segment"] = 3;
             
            // custom paging configuration
            $config['num_links'] = 2;
            $config['use_page_numbers'] = TRUE;
            $config['reuse_query_string'] = TRUE;
             
            $config['full_tag_open'] = '<div class="pagination">';
            $config['full_tag_close'] = '</div>';
             
            $config['first_link'] = 'First Page';
            $config['first_tag_open'] = '<span class="firstlink">';
            $config['first_tag_close'] = '</span>';
             
            $config['last_link'] = 'Last Page';
            $config['last_tag_open'] = '<span class="lastlink">';
            $config['last_tag_close'] = '</span>';
             
            $config['next_link'] = 'Next Page';
            $config['next_tag_open'] = '<span class="nextlink">';
            $config['next_tag_close'] = '</span>';
 
            $config['prev_link'] = 'Prev Page';
            $config['prev_tag_open'] = '<span class="prevlink">';
            $config['prev_tag_close'] = '</span>';
 
            $config['cur_tag_open'] = '<span class="curlink">';
            $config['cur_tag_close'] = '</span>';
 
            $config['num_tag_open'] = '<span class="numlink">';
            $config['num_tag_close'] = '</span>';
             
            $this->pagination->initialize($config);
                 
            // build paging links
            $params["links"] = $this->pagination->create_links();
        }
     
        $this->load->view('templates/header');
        $this->load->view('templates/navigation');
        $this->load->view('test/user_listing', $params);
        $this->load->view('templates/footer');
    }
}
?>