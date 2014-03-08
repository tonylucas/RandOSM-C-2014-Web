<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Signup extends CI_Controller {
    
    public function __construct(){
        parent::__construct();
    }
    
    public function index()
    {        
        $this->form_validation->set_rules('name', 'pseudo','trim|required|xss_clean|is_unique[user.name]|htmlspecialchars|max_length[16]');
        $this->form_validation->set_rules('email','email','trim|required|xss_clean|valid_email|is_unique[user.mail]|htmlspecialchars|max_length[30]');
        $this->form_validation->set_rules('pass','mot de passe','trim|required|xss_clean|min_length[5]|htmlspecialchars|max_length[30]');
        $this->form_validation->set_rules('pass2','vérification du mot de passe','trim|required|matches[pass]');
        $data['page_en_cours'] = 'signup';
		
        if($this->form_validation->run()) {
            
	    $ValidationKey = md5(microtime()*100000); //Création d'une clé qui permettra d'activer le compte, à l'aide de l'envoi d'un e-mail de confirmation du compte.
	    $name = $this->input->post('name');

	    //Ce tableau contient les résultats du formulaire, ainsi qu'un clé pour activer le compte.
            $data = array(
                        'name'=>$this->input->post('name'),
                        'mail'=>$this->input->post('email'),
                        'password' => hash ( "sha256", $this->input->post('pass')),
			'ValidationKey' => $ValidationKey
                );
            
            $this->load->model('signup_model');
            $this->signup_model->signup($data);


            $sujet = 'Inscription';//Le titre de l'email
            $message = 'Vous avez bien été inscrit à notre site.<br/>Veuillez valider votre compte en cliquant sur ce lien :<br/><br/> http://randosm.theobouge.eu/validation?name='.urlencode($name).'&key='.urlencode($ValidationKey).'';//Le message de l'email
            $destinataire = $_POST['email'];//Email de l'utilisateur
            $headers = "From: \"Rand'OSM\"<theobou@icare.pulseheberg.net>\n";//Email du webmaster
            $headers .= "Content-Type: text/html; charset=\"iso-8859-1\"";
            mail($destinataire,$sujet,$message,$headers);
            
            $success['success'] = 'Inscription réussie';			

            $data2 = array(
                        'titre'=> 'Inscription',
                        'connected'=> $this->session->userdata('logged_in'),
                        'page_en_cours'=> 'Inscription',
            );
            
            // Chargement des vues
            $this->load->view('header', $data2);
            $this->load->view('menu', $data2);
            $this->load->view('signup', $success);
            $this->load->view('footer');
            
        }
        else {        
           $data2 = array(
                        'titre'=> 'Inscription',
                        'connected'=> $this->session->userdata('logged_in'),
                        'page_en_cours'=> 'Inscription',
            );
            
            // Chargement des vues
            $this->load->view('header', $data2);
            $this->load->view('menu', $data2);
            $this->load->view('signup');
            $this->load->view('footer');
        }
    }  
}
