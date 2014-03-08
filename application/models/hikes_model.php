<?php
class Hikes_model extends CI_Model
{
    function __construct() {
        parent::__construct();
    }
    
    function getUserHikes() {
        $this->db->select('randonnee_id, name, city, type, difficulty, time, distance, description, date_of_creation, creator');
        $this->db->from('randonnee');
        $this->db->where('creator', $this->session->userdata('userId'));
        //$this->db->group_by("difficulty"); 
        $query = $this->db->get();
        
        $row = $query->row();
        
        if($query->num_rows() > 0){
            return array('query' => $query, 'number_rows' => $this->db->count_all_results());
        }
    }
    
    function create_hike($data) {
        $requete = $this->db->insert('randonnee', $data);
        return $this->db->insert_id();
    }
    
    function modify_hike($data) { // Le paramètre $data de cette fonction contient $hikeId et les données du tableau ci-dessous
        $data2= array();
        $data2['name'] = $data['name'];
        $data2['type'] = $data['type'];
        $data2['difficulty'] = $data['difficulty'];
        $data2['time'] = $data['time'];
        $data2['distance'] = $data['name'];
        $data2['description'] = $data['description'];
        
        $this->db->where('randonnee_id', $data['hikeId']);
        $this->db->update('randonnee', $data2);
    }
    
    function delete_hike($hikeId) {
        $this->db->delete('randonnee', array('randonnee_id' => $hikeId)); 
    }
    
    
    function GetAutocomplete($options = array()) {
        $this->db->select('libCity, numDep');
        $this->db->like('libCity', $options['keyword'], 'after');
        $query = $this->db->get('city');
        return $query->result();
    } 
    function getCityIdByName($name){
        $this->db->select('idCity');
        $this->db->from('city');
        $this->db->where('libCity', $name);
        
        $query = $this->db->get();
        $row = $query->row();
        if($query->num_rows() === 1){
            return $query;
        }
    }
    function getCityNameById($id){        
        $this->db->select('libCity, numDep');
        $this->db->from('city');
        $this->db->where('idCity', $id);
        
        $query = $this->db->get();
        
        foreach ($query->result() as $row){
            return $row;
        }
    }
    function checkCity($cityField){ // Vérifie que la ville entrée est bien une ville contenue dans la base de donnée   
        $this->db->select('libCity');
        $this->db->from('city');
        $this->db->where('libCity', $cityField);
        
        $query = $this->db->get();
        $row = $query->row();
        if($query->num_rows() === 1){
            return "true";
        }
    }
    function buildHikeId($creatorId) {
        $query = "select max(randonnee_id) from randonnee where creator='" . $creatorId . "'";
        $answer = $this->db->query($query);
        foreach ($answer->result() as $row){
            return $row->randonnee_id;
        }
        
    }
}