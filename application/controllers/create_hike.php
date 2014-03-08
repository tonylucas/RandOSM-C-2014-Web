<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Create_hike extends CI_Controller {
    
    public function __construct(){
        parent::__construct();
        $this->load->model('user_model');
        $this->load->model('hikes_model'); 
        $this->load->library('zip');
        $this->load->helper('directory');
        $this->load->helper('file');
    }
    
    public function create() {
        parse_str($_POST['dataString']);
        
        $cityName = explode(" ", $city);
        $query = $this->hikes_model->getCityIdByName($cityName[0]); // Ne prend que le nom de la ville, pas le numéro de département
        foreach ($query->result() as $row) {    
            $cityId = $row->idCity;
        }
        
        $this->load->helper('date');
        date_default_timezone_set('Europe/Paris');
        
        $data = array(
            'name'=>htmlentities($name),
            'difficulty'=>$difficulty,
            'time'=>$time,
            'distance'=>htmlentities($length),
            'creator'=>$this->session->userdata('userId'),
            'city'=>htmlentities($cityId),
            'description'=>htmlentities($description),
            'date_of_creation'=>mdate("%Y-%m-%d", time())
            //            'mis_pour_afficher_requete'=>"d",
        );
        
        if (isset($otherType)) $data['type'] = $otherType;
        else $data['type'] = $type;
        
        
        $hikeId = $this->hikes_model->create_hike($data); // Création de la randonnée dans la base de donnée
        
        /* ############################# Récupération des markers au format tableau JSON ############################# */
        $markers = $_POST['markers']; 
        
        
        /* ##################################### Création du fichier ZIP ##################################### */
        $session_id = $this->session->userdata('session_id');
        
        $zip = new ZipArchive;
        
        if ($zip->open('./hiking/'.$session_id.'.zip') === TRUE) {
            $zip->addFromString('./markers.json', $markers);
            $zip->close();
            rename("./hiking/".$session_id.".zip", "./hiking/".$hikeId.".zip");
            echo 'ok';
        } else {
            echo 'échec';
            print_r(directory_map('./hiking/'));
        }
        
        $this->rrmdir('./hiking/'.$session_id.'/');
    }
    
    /**
     * 
     * Create temporary zip to receive media folder
     * 
     *
    */
    function build_tmp_zip() {
        $session_id = $this->session->userdata('session_id');
        echo $session_id.'<br>';
        $base = "hiking/".$session_id."/";
        $photo = $base . "photo/";
        $audio = $base . "audio/";
        $video = $base . "video/";
        
        if(!is_dir($base)) {
            mkdir($base,0755,TRUE);
        } 
        if(!is_dir($photo)) {
            mkdir($photo,0755,TRUE);
        } if(!is_dir($audio)) {
            mkdir($audio,0755,TRUE);
        } if(!is_dir($video)) {
            mkdir($video,0755,TRUE);
        } 
        
        $this->zip->add_dir('media/'); // Création d'un répertoire media dans un zip.
        $from = $base;
        $folder_in_zip = 'media/';
        $this->zip->get_files_from_folder($from, $folder_in_zip); // Copie de $path dans $foler_in_zip
        $this->zip->archive('./hiking/'.$session_id.'.zip');    // Fermeture de l'archive
    }
    
    /**
     * 
     * Puts files from Dropzones to media directory in the zip file of the hike.
     * 
     * @param string type of media (photo, audio or video)
     *
    */
    function file_upload($type) { // $type means photo, audio or video.
        echo "----- file_upload-----";
        
        $session_id = $this->session->userdata('session_id');
        
        $base = "hiking/".$session_id."/";
        $photo = $base . "photo/";
        $audio = $base . "audio/";
        $video = $base . "video/";
        
        if(!is_dir($base)) {
            mkdir($base,0755,TRUE);
        } 
        if(!is_dir($photo)) {
            mkdir($photo,0755,TRUE);
        } if(!is_dir($audio)) {
            mkdir($audio,0755,TRUE);
        } if(!is_dir($video)) {
            mkdir($video,0755,TRUE);
        } 
        
        
        if (!empty($_FILES)) {
            $filesCount = count($_FILES['file']['name']);
            $targetPath = $base . $type . "/";
            for ($i = 0; $i < $filesCount; $i++) {         
                $tempFile = $_FILES['file']['tmp_name'][$i];
                $targetFile =  $targetPath . $_FILES['file']['name'][$i];
                move_uploaded_file($tempFile, $targetFile);
            }
            
            
            $this->zip->add_dir('media/'); // Création d'un répertoire media dans un zip.
            $from = $base;
            $folder_in_zip = 'media/';
            $this->zip->get_files_from_folder($from, $folder_in_zip); // Copie de $path dans $foler_in_zip
            $this->zip->archive('./hiking/'.$session_id.'.zip');    // Fermeture de l'archive
        }
    }
    
    function city_check($str) {
        if (!$this->hikes_model->checkCity($str)) {
            $this->form_validation->set_message('city_check', 'Le champs %s doit être l\'une des villes proposées lorsque vous entrez les premières lettres.');
            return FALSE;
        }
        else {
            return TRUE;
        }
    }
    
    function rrmdir($path) {
        $it = new RecursiveDirectoryIterator($path);
        $files = new RecursiveIteratorIterator($it, RecursiveIteratorIterator::CHILD_FIRST);
        foreach($files as $file) {
            if ($file->getFilename() === '.' || $file->getFilename() === '..') {
                continue;
            }
            if ($file->isDir()){
                rmdir($file->getRealPath());
            } else {
                unlink($file->getRealPath());
            }
        }
        rmdir($path);
    }
}