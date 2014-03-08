<div class='content'>
    <div class='my-treks-box  content-box'>
        <h1>Mes randonnées</h1>
        <?php
$this->load->model('user_model');
$this->load->model('hikes_model');
$query = $this->user_model->getUserHikes();

if($query['number_rows'] > 0){    
    foreach ($query['query']->result() as $row) { // Ce qu'on affiche pour chaque randonnée
        $cityInfos = $this->hikes_model->getCityNameById($row->city);
        
        ?>
        
        <div class='hike-card'>
            <div class='hike-infos'>
                <?php
                echo "<b>" . $row->name . " :</b> " . $row->type . "<br>";
                echo "<b>Difficulté : </b>";
                $d = $row->difficulty;
                for ($i=0; $i<$d; $i++) { 
                    echo "<i class='icon-shoe'></i>"; 
                }
                for ($i=0; $i<(5-$d); $i++) { 
                    echo "<i class='icon-shoe2'></i>"; 
                }
                echo "<br>";
                echo "<b>Longueur : </b>" .   $row->distance . " Km<br>";
                echo "<b>Durée : </b>" .      $row->time . "<br>"; 
                echo "<b>Ville : </b>" . $cityInfos->libCity . " (" . $cityInfos->numDep . ")<br>";
//                echo "<b>Description : </b>" . $row->description . "<br>";
                echo "<b>Date de création : </b>" . $row->date_of_creation . "<br>";
                ?>
            </div>
            
            <div class="hike-buttons">
<!--                Voir en détails<a class="icons-buttons icon-details" onclick="showDetails(<?=$row->randonnee_id ?>)"></a>-->
                <div>Voir en détails<a class="icons-buttons icon-details"></a></div>
                <div>Modifier<a class="icons-buttons icon-settings" href="<?=base_url() ?>my_hikes/modify/<?=$row->randonnee_id ?>"></a></div>
                <!--                <a class="icons-buttons icon-settings" href="">Modifier</a>-->
                <div>Supprimer<a class="icons-buttons icon-delete" href="<?=base_url() ?>my_hikes/delete/<?=$row->randonnee_id ?>"></a></div>
            </div>
<!--            <div id="map"></div>-->
            
        </div>
        
        <?php
                    
                    
                    
                    
    }
        ?>
        
        
        
        
        <?php
} /* END if($query['number_rows'] > 0) */

        ?>
        <!--        <br><br>-->
        <a class="button new-hike" href="<?=base_url() ?>my_hikes/create_hike">Créer une nouvelle randonnée</a>
    </div> <!--END my-treks-box  content-box -->
</div> <!--END content -->
<script>
//    function showDetails(hikeID) {
//        var hikeCard = event.target.parentNode.parentNode;
//        var map = document.createElement("div");
//        map.setAttribute('id', 'map');
//        hikeCard.appendChild(map);
//        
////        if (!document.getElementById('mapScript')) {
////                    var scriptElement = document.createElement('script');
////                    scriptElement.src = base_url + 'assets/js/map.js';
////                    scriptElement.setAttribute('id', 'mapScript');
////                    document.body.appendChild(scriptElement);
////                }
//        
//    }
</script>
<!---------------------------- Carte OpenStreetMap ---------------------------->
<!--<script src="http://cdn.leafletjs.com/leaflet-0.7.2/leaflet.js"></script>
<script src="<?= base_url() ?>assets/js/map.js"></script>
<link rel="stylesheet" href="http://cdn.leafletjs.com/leaflet-0.7.2/leaflet.css" />
<script type="text/javascript" src="http://code.jquery.com/jquery-1.10.1.min.js"></script>-->