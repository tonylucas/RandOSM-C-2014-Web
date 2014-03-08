<?php
class Contact_model extends CI_Model
{
    function __construct() {
        parent::__construct();
    }
    
    function contact($data) {
        $this->db->insert('ci_contact',$data);        
    }
}