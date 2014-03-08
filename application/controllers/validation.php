<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Validation extends CI_Controller {
	
	function Validation(){
		parent::__construct();
		$this->load->model('validation_model');
	}
	
	function index(){
		$data2 = array();
		$name = $_GET['name'];
		$key = $_GET['key'];
		
		$state = $this->validation_model->getState($name, $key);
		$data2['name'] = $name;
		//$data2['state'] = $state;
		
		if($state != 0){
			//afficher message à l'aide de la vue, comme quoi compte déjà activé
			$data['titre'] = "Compte Rand'OSM validé";
			$data['page_en_cours'] = null;
			$this->load->view('header', $data);
			$this->load->view('menu', $data);
			$this->load->view('validation', $data2);
			$this->load->view('footer');
		
		}

		else{

			$data['titre'] = "Compte Rand'OSM validé";
			$data['page_en_cours'] = null;
			$this->validation_model->setState($name, 1);
			$this->load->view('header', $data);
			$this->load->view('menu', $data);
			$this->load->view('validation', $data2);
			$this->load->view('footer');
		}
	}
}
