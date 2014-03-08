<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Modify_hike extends CI_Controller {
    
    public function __construct(){  
        parent::__construct();
        $this->load->model('user_model');
        $this->load->model('hikes_model'); 
    }
    
   
    public function modify($hikeId)
    {        
        $this->form_validation->set_rules('name', 'nom','required|xss_clean|htmlspecialchars');
        $this->form_validation->set_rules('city','ville associée','required|xss_clean|htmlspecialchars');
        $this->form_validation->set_rules('distance','longueur','required|xss_clean|htmlspecialchars');
        $this->form_validation->set_rules('time','durée','required|xss_clean|htmlspecialchars');
        $this->form_validation->set_rules('description','description','required|xss_clean|htmlspecialchars');
        
        if($this->form_validation->run()) {
            
            $data = array(
                'hikeId'=>$hikeId,
                'name'=>$this->input->post('name'),
                'type'=>$this->input->post('type'),
                'difficulty'=>$this->input->post('difficulty'),
                'time'=>$this->input->post('time'),
                'distance'=>$this->input->post('distance'),
                'description'=>$this->input->post('description'),
                'creator'=>$this->session->userdata('userId'),
                //'city'=>$this->input->post('city'),
            );
            
            $this->hikes_model->modify_hike($data);
            
            $data['success'] = 'Création réussie';		
            
            // Chargement des vues
            $data2 = array(
                'titre'=> 'Mes randonnées',
                'page_en_cours'=> 'MesRandonnees',
                'connected'=> $this->session->userdata('logged_in'),
            );
            
            redirect('my_hikes','refresh');
        }
        else {        
            
            if($this->user_model->isLoggedIn()){
                
                $data = array(
                    'titre'=>'Créer une randonnée',
                    'page_en_cours'=>'MesRandonnees',
                    'connected'=> $this->session->userdata('logged_in'),
                );
                
                // Chargement des vues
                $this->load->view('header', $data);
                $this->load->view('menu', $data);
                $this->load->view('create_hike');
                $this->load->view('footer');
            } else {
                redirect('admin/login','refresh');
            }  
        }
    }  
}