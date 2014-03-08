<link rel="stylesheet" href="<?=base_url() ?>assets/css/jquery-ui.css">

<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $(function() {
            $("#autocomplete").autocomplete({
                source: function(request, response) {
                    $.ajax({
                        url: "<?php echo site_url('admin/suggestions'); ?>",
                        data: {
                            term: $("#autocomplete").val()
                        },
                        dataType: "json",
                        type: "POST",
                        success: function(data) {
                            response(data);
                        }
                    });
                },
                minLength: 2
            });
        });
    });
</script>

<?php $page_en_cours='MonCompte' ; ?>
<div class='content'>
    <div class='subscribe-box content-box'>
        <h1>Mon compte</h1>
        <br />
        <!----- Si la modification a fonctionnée on affiche un message de succès ---->
        <?php if(isset($success)):?>
        <div class="success">
            <?php echo $success;?>
        </div>
        <br />

        <a href="<?= base_url() ?>/admin">Retour au site</a>

        <!----- Sinon, on affiche le formulaire de modification ---->
        <?php else:?>
        <?=form_open( 'admin/modify'); ?>

            <label for="pseudo">Pseudo :</label>
            <input type="text" name="pseudo" id="pseudo" value="<?= $this->session->userdata('username')?>" />
            <br />
            <label for="email">Adresse e-mail :</label>
            <input type="email" name="email" id="email" value="<?= $this->session->userdata('mail')?>" />
            <br />
            <label for="city">Ville :</label>
            <input type="text" name="city" id="autocomplete" value="<?= $this->session->userdata('city')?>" />
            <br />
            <input type="submit" value="Enregistrer" />

            <?=form_close();?>

                <div class="errors">
                    <p>
                        <?=form_error( 'pseudo', '<span class="error">', '</span');?>
                    </p>
                    <p>
                        <?=form_error( 'email', '<span class="error">', '</span');?>
                    </p>
                    <p>
                        <?=form_error( 'city', '<span class="error">', '</span');?>
                </div>
                <div>
                    Modifier mon mot de passe :
                    <a class="icons-buttons icon-settings" href="<?=base_url() ?>admin/pw_change/"></a>
                    <br />Supprimer le compte :
                    <a class="icons-buttons icon-delete" href="<?=base_url() ?>admin/delete/"></a>
                    <br />
                    <br />
                </div>
                <?php endif; ?>
    </div>
</div>