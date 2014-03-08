<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_Controller {
    
    function Admin() {
        parent::__construct();
        $this->load->model('user_model');
    }
    
    
    function index(){
        if($this->user_model->isLoggedIn()){
            redirect('admin/dashboard','refresh');
        } else {
            redirect('login','refresh');
        }
    }
    
    
    function login(){
        if($this->user_model->isLoggedIn()){
            redirect('admin/dashboard','refresh');
        }
        else {
            
            //on charge la validation de formulaires
            $this->load->library('form_validation');
            
            //on définit les règles de succès
            $this->form_validation->set_rules('username','Login','required|htmlspecialchars|xss_clean');
            $this->form_validation->set_rules('password','Mot de passe','required|htmlspecialchars|xss_clean');
            
            //si la validation a échouée on redirige vers le formulaire de login
            if(!$this->form_validation->run()){
                
                $data['titre'] = 'Connexion';
                $data['connected'] = $this->session->userdata('logged_in');
                $data['page_en_cours'] = 'Connexion';
                
                $this->load->view('header', $data);
                $this->load->view('menu', $data);
                $this->load->view('loginform', $data);
                $this->load->view('footer');
            }
            else {
                $username = $this->input->post('username');
                $password = $this->input->post('password');
                $validCredentials = $this->user_model->validCredentials($username,$password);
                
                if($validCredentials){
                    redirect('admin/dashboard','refresh');
                }
                else {
                    $data['titre'] = 'Connexion';
                    $data['error_credentials'] = 'Wrong Username/Password';
                    $data['page_en_cours'] = 'Connexion';
                    
                    $this->load->view('header', $data);
                    $this->load->view('menu', $data);
                    $this->load->view('loginform');
                    $this->load->view('footer');
                }
            }
        }
    }
    
    
    /* Charge la vue mon compte si bien connecté */
    function dashboard(){
        if($this->user_model->isLoggedIn()){
            $data  = array();
            $data['titre'] = 'Mon compte';
            $data['connected'] = $this->session->userdata('logged_in');
            $data['page_en_cours'] = 'MonCompte';
            
            $this->load->view('header', $data);
            $this->load->view('menu', $data);
            $this->load->view('admin', $data);
            $this->load->view('footer');
        } else {
            redirect('login','refresh');
        }
    }
    
    function delete($confirm = false){
        if($this->user_model->isLoggedIn()){
            $data  = array();
            $data['titre'] = 'Supprimer mon compte';
            $data['connected'] = $this->session->userdata('logged_in');
            $data['page_en_cours'] = 'MonCompte';
            $data['conf'] = $confirm;
            
            if($confirm==true) {
                $this->user_model->delete();
                $this->session->unset_userdata('username');
		$this->session->unset_userdata('logged_in');
		$this->session->sess_destroy();
            }
            else {
                
            }
            $this->load->view('header', $data);
            $this->load->view('menu', $data);
            $this->load->view('delete', $data);
            $this->load->view('footer');
        } else {
            redirect('login','refresh');
        }
    }
    
    
    function modify(){
        if($this->user_model->isLoggedIn()){            
            $data2['titre'] = 'Mon compte';
            $data2['connected'] = $this->session->userdata('logged_in');
            $data2['page_en_cours'] = 'MonCompte';
            if($this->input->post('pseudo') == $this->session->userdata('username') && $this->input->post('email') == $this->session->userdata('mail') && $this->input->post('city') == $this->session->userdata('city')) {
                $this->load->view('header', $data2);
                $this->load->view('menu', $data2);
                $this->load->view('admin');
                $this->load->view('footer');
            }
            else {
                if($this->input->post('pseudo') != $this->session->userdata('username')) {
                    $is_unique =  '|is_unique[user.name]';
                } else {
                    $is_unique =  '';
                }
                if($this->input->post('email') != $this->session->userdata('mail')) {
                    $is_unique2 =  '|is_unique[user.mail]';
                } else {
                    $is_unique2 =  '';
                }

                $this->form_validation->set_rules('pseudo', 'pseudo','trim|required|xss_clean|htmlspecialchars|max_length[16]'.$is_unique);
                $this->form_validation->set_rules('email','email','trim|required|xss_clean|valid_email|htmlspecialchars|max_length[30]'.$is_unique2);
                $this->form_validation->set_rules('city','ville','trim|xss_clean|htmlspecialchars|max_length[40]');

                if($this->form_validation->run()) {

                    $data = array(
                                'name'=>$this->input->post('pseudo'),
                                'mail'=>$this->input->post('email'),
                                'city'=>$this->input->post('city')
                        );

                    $this->load->model('user_model');
                    $this->user_model->user_modify($data);

                    $success['success'] = 'Modifications effectuées !';                

                    // Chargement des vues
                    $this->load->view('header', $data2);
                    $this->load->view('menu', $data2);
                    $this->load->view('admin', $success);
                    $this->load->view('footer');

                }
                else {      
                    // Chargement des vues
                    $this->load->view('header', $data2);
                    $this->load->view('menu', $data2);
                    $this->load->view('admin');
                    $this->load->view('footer');
                }
            }
        }
        else {
            redirect('login','refresh');
        }
    }
    
    function pw_change(){
        if($this->user_model->isLoggedIn()){
            $data  = array();
            $data['titre'] = 'Modifier mon mot de passe';
            $data['connected'] = $this->session->userdata('logged_in');
            $data['page_en_cours'] = 'MonCompte';
            
            $this->form_validation->set_rules('pass','mot de passe','trim|required|xss_clean|min_length[5]|htmlspecialchars|max_length[30]|callback_validCredential');
            $this->form_validation->set_rules('pass1','nouveau mot de passe','trim|required|xss_clean|min_length[5]|htmlspecialchars|max_length[30]|callback_isDifferent');
            $this->form_validation->set_rules('pass2','vérification du mot de passe','trim|required|matches[pass1]');

            if($this->form_validation->run()) {

                $data['password'] = hash ( "sha256", $this->input->post('pass1') ); 

                $this->load->model('user_model');
                $this->user_model->pw_modify($data);

                $success['success'] = 'Votre mot de passe a été modifié.';                

                // Chargement des vues
                $this->load->view('header', $data);
                $this->load->view('menu', $data);
                $this->load->view('pw_change', $success);
                $this->load->view('footer');

            }
            else {      
                // Chargement des vues
                $this->load->view('header', $data);
                $this->load->view('menu', $data);
                $this->load->view('pw_change');
                $this->load->view('footer');
            }
        }
        else {
            redirect('login','refresh');
        }
    }
    
    function validCredential($password){
        $password = hash ( "sha256", $password );
        $username = $this->session->userdata('username');
        $this->db->select('user_id');
        $this->db->from('user');
        $this->db->where('name', $username);
        $this->db->where('password', $password);
        $query = $this->db->get();
        
        if($query->num_rows() === 1){
            return true;
        }
        else {
            $this->form_validation->set_message('validCredential', 'Le %s n\'est pas correct.');
            return false;
        }
    }
    
    function isDifferent($password) {
        $password = hash ( "sha256", $password );
        $username = $this->session->userdata('username');
        $this->db->select('password');
        $this->db->from('user');
        $this->db->where('name', $username);
        $this->db->where('password', $password);
        $query = $this->db->get();
        
        $row = $query->row();
        
        if($query->num_rows() === 1){
            $this->form_validation->set_message('isDifferent', 'Le %s doit être différent de l\'ancien.');
            return false;
        }
        else {
            return true;
        }
    }
    
    function suggestions()
    {
        $term = $this->input->post('term',TRUE);
        
        if (strlen($term) < 2) break ;
        
        $rows = $this->user_model->GetAutocomplete(array('keyword' => $term));
        
        $json_array = array();
        foreach ($rows as $row)
            array_push($json_array, $row->libCity);
        
        echo json_encode($json_array);
    }
}
