
<div class='content'>
    <div class='trek-making-box content-box'>
        <h1>Modifier une randonnée</h1>
        <?php
echo form_open('modify_hike/modify/'.$hikeId);


echo form_label('Nom', 'name');
echo form_input("name", $name, "id='name'");

echo form_label('Ville associée', 'city');
echo form_input("city", $city, "id='autocomplete'");

echo form_label('Longueur de la randonnée en Kilomètres', 'distance');
echo form_input("distance", $distance, "id='distance'");

echo form_label('Durée de la randonnée en heures', 'time');
echo form_input("time", $time, "id='time'");

echo form_label('Description de la randonnée', 'description');
echo form_input("description", $description, "id='description'");



/* Liste déroulante */
echo form_label('Type', 'type');
$types = array(
    'À pied'  => 'À pied',
    'VTT'    => 'VTT',
    'Cheval'   => 'Cheval',
    'Monocycle' => 'Monocycle',
    'Autre...' => 'Autre...',
);
echo form_dropdown("type", $types, $type);

/* Boutons radio */
echo form_label('Difficulté', 'difficulty');?>
        <div class="radio_buttons">
            <input type="radio" name="difficulty" value="1" <?php if($difficulty==1) { echo 'checked="checked"' ; } ?>> 1
            <input type="radio" name="difficulty" value="2" <?php if($difficulty==2) { echo 'checked="checked"' ; } ?>> 2
            <input type="radio" name="difficulty" value="3" <?php if($difficulty==3) { echo 'checked="checked"' ; } ?>> 3
            <input type="radio" name="difficulty" value="4" <?php if($difficulty==4) { echo 'checked="checked"' ; } ?>> 4
            <input type="radio" name="difficulty" value="5" <?php if($difficulty==5) { echo 'checked="checked"' ; } ?>> 5
        </div>     
        
        
        
        
        <!--Bouton modifier-->
        <?php echo form_submit('submit','Modifier');

echo form_close();
echo validation_errors();
echo @$error_credentials; ?>
    </div>
</div>
<!-- Pour l'autocomplétion du champ ville -->
<script>
    $(function() {
        $("#autocomplete").autocomplete({ // Ce script permet l'autocomplétion du champs "Ville" à l'étape 1
            source: function(request, response) {
                $.ajax({ url: "<?php echo site_url('my_hikes/suggestions'); ?>",
                        data: { term: $("#autocomplete").val()},
                        dataType: "json",
                        type: "POST",
                        success: function(data){
                            response(data);
                        }
                       });
            },
            minLength: 2
        });
    });
</script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>
