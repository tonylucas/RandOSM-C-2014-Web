<?php

class Validation_model extends CI_Model {

	function getState($name, $key){
			$this->db->select('state');
			$this->db->from('user');
			$this->db->where('name', $name);
			$this->db->where('ValidationKey', $key);
			
			$ret = $this->db->get()->row()->state;
			return $ret;
	}

	function setState($name, $bool){
		$data['state'] = $bool;
		$this->db->where('name', $name);
		$this->db->update('user', $data);
	}
}
		
