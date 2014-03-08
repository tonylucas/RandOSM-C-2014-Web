<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class My_hikes extends CI_Controller {
    
    public function __construct(){
        parent::__construct();
        $this->load->helper('url'); 
        $this->load->helper('assets');
        
        $this->load->model('hikes_model');
        $this->load->model('user_model');
    }
    
    public function index()
    {
        if($this->user_model->isLoggedIn()){
            $data  = array();
            $data['titre'] = 'Mes randonnées';
            $data['connected'] = $this->session->userdata('logged_in');
            $data['page_en_cours'] = 'MesRandonnees';
            
            // Chargement des vues
            $this->load->view('header', $data);
            $this->load->view('menu', $data);
            $this->load->view('my_hikes', $data);
            $this->load->view('footer');
        } else {
            redirect('login','refresh');
        }
    }
    
    public function create_hike()
    {        
        if($this->user_model->isLoggedIn()){
            $data  = array();
            $data['titre'] = 'Créer une randonnée';
            $data['connected'] = $this->session->userdata('logged_in');
            $data['page_en_cours'] = 'MesRandonnees';
            
            // Chargement des vues
            $this->load->view('header', $data);
            $this->load->view('menu', $data);
            $this->load->view('create_hike/steps', $data);
            $this->load->view('footer');
        } else {
            redirect('admin/login','refresh');
        }
    }
    
    public function delete($hikeId)
    {
        if($this->user_model->isLoggedIn()){
            $this->hikes_model->delete_hike($hikeId);
            redirect('my_hikes/','refresh');
            
        } else {
            redirect('admin/login','refresh');
        }
    }
    
    public function modify($hikeId)
    {
        if($this->user_model->isLoggedIn()){
            $data  = array();
            $data['titre'] = 'Modifier une randonnée';
            $data['connected'] = $this->session->userdata('logged_in');
            $data['page_en_cours'] = 'MesRandonnees';
            
            // On met dans un tableau les infos de la randonnée et son ID
            $query = $this->user_model->getUserHike($hikeId);
            if ($query->num_rows() === 1)
            {
                foreach ($query->result() as $row)
                {    
                    $hikeParameters  = array();
                    $hikeParameters['hikeId'] = $hikeId;
                    $hikeParameters['name'] = $row->name;
                    $hikeParameters['city'] = $this->hikes_model->getCityNameById($row->city)->libCity;
                    $hikeParameters['type'] = $row->type;
                    $hikeParameters['difficulty'] = $row->difficulty;
                    $hikeParameters['time'] = $row->time;
                    $hikeParameters['distance'] = $row->distance;
                    $hikeParameters['description'] = $row->description;
                }
            }
            
            // Chargement des vues
            $this->load->view('header', $data);
            $this->load->view('menu', $data);
            $this->load->view('modify_hike', $hikeParameters);
            $this->load->view('footer');
            
        } else {
            redirect('admin/login','refresh');
        }
    }
    
    function suggestions() {
        $term = $this->input->post('term',TRUE);
        
        if (strlen($term) < 2) break;
        
        $rows = $this->hikes_model->GetAutocomplete(array('keyword' => $term));
        
        
        $json_array = array();
        foreach ($rows as $row)
            array_push($json_array, $row->libCity . " (". $row->numDep . ")");
        
        echo json_encode($json_array);
    }
    
    function checkCity() { // Vérifie que la ville entrée est bien référencée dans la base de donnée
        $cityField = explode(" ", $this->input->post('cityField',TRUE));
        $query = $this->hikes_model->checkCity($cityField[0]);
        if($this->hikes_model->checkCity($cityField[0])=="true"){
            echo "true";
        } else {
            echo "false";
        }
    }
}