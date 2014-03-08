<?php
class User_model extends CI_Model {
    
    
    
    function User_model(){
        parent::__construct();
        $this->username = '';
    }
    
    
    
    function validCredentials($username,$password){
        $this->load->library('encrypt');
        $password = hash ( "sha256", $password );
        
        /* Requête façon 1 */
        //$query = "SELECT * FROM user WHERE name = '" . $username . "' AND password = '" . $password . "'";
        //$answer = $this->db->query($query);
        
        /* Requête façon requete préparée */
        //$query = "SELECT * FROM user WHERE name = ? AND password = ?";
        //$answer = $this->db->query($query, array($username, $password));
        
        /* Requête façon CodeIgniter */
        $this->db->select('user_id, name, mail, city');
        $this->db->from('user');
        $this->db->where('name', $username);
        $this->db->where('password', $password);
        $query = $this->db->get();
        
        $row = $query->row();
        
        if($query->num_rows() === 1){
            $session_data = array('username' => $row->name, 'userId' => $row->user_id, 'mail' => $row->mail, 'city' => $row->city, 'logged_in' => true);
            $this->session->set_userdata($session_data);
            return true;
        }
        else {
            return false;
        }
    }
        
    function getUserHikes() { // Retourne toutes les randonnées de l'utilisateur
        $this->db->select('randonnee_id, name, type, difficulty, time, distance, description, date_of_creation, creator, city');
        $this->db->from('randonnee');
        $this->db->where('creator', $this->session->userdata('userId'));
        //$this->db->group_by("difficulty"); 
        $query = $this->db->get();
        
        $row = $query->row();
        
        if($query->num_rows() > 0){
            return array('query' => $query, 'number_rows' => $this->db->count_all_results());
        }
    }
        
    function getUserHike($hikeId) { // Retourne LA randonnée qui a cet ID
        $this->db->select('name, type, city, difficulty, time, distance, description');
        $this->db->from('randonnee');
        $this->db->where('creator', $this->session->userdata('userId'));
        $this->db->where('randonnee_id', $hikeId);
        //$this->db->group_by("difficulty"); 
        $query = $this->db->get();
        
        $row = $query->row();
        
        if($query->num_rows() === 1){
            return $query;
        }
    }
        
    function isLoggedIn(){
        if($this->session->userdata('logged_in'))
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    
    function delete() {
        $this->db->where('user_id', $this->session->userdata('userId'))->delete('user');
    }
    
    function user_modify($data) { 
        $data2= array();
        $data2['name'] = $data['name'];
        $data2['mail'] = $data['mail'];
        $data2['city'] = $data['city'];
        
        $this->db->where('user_id', $this->session->userdata('userId'));
        $this->db->update('user', $data2);     
        
        $this->db->select('user_id, name, mail, city');
        $this->db->from('user');
        $this->db->where('user_id', $this->session->userdata('userId'));
        $query = $this->db->get();
        
        $row = $query->row();
        
        if($query->num_rows() === 1){
            $session_data = array('username' => $row->name, 'userId' => $row->user_id, 'mail' => $row->mail, 'city' => $row->city,'logged_in' => true);
            $this->session->set_userdata($session_data);
            return true;
        }
        else {
            return false;
        }
    }
    
    function pw_modify($data) { 
        $data2= array();
        $data2['password'] = $data['password'];
        
        $this->db->where('user_id', $this->session->userdata('userId'));
        $this->db->update('user', $data2);    
    }
    
    function GetAutocomplete($options = array()) {
        $this->db->select('libCity');
        $this->db->like('libCity', $options['keyword'], 'after');
        $query = $this->db->get('city');
        return $query->result();
    }
    function getCityIdByItsName($name){
        $this->db->select('idCity');
        $this->db->from('city');
        $this->db->where('libCity', $name);
        return $this->db->get();
    }
    function getCityNameByItsId($id){        
        $this->db->select('libCity, numDep');
        $this->db->from('city');
        $this->db->where('idCity', $id);
        
        return $this->db->get();
    }
}