<!DOCTYPE html>

<html lang="fr">
    <head>
        <title>
        <?php echo $titre ?>
        </title>
        <meta http-equiv="Content-Type" content="text/html" charset="utf-8" />
        <link rel="stylesheet" type="text/css" media="screen" href="<?= base_url() ?>assets/css/style.css" />
<!--        <link rel="stylesheet" type="text/css" media="screen" href="<?= base_url() ?>assets/css/normalize.css" /> -->
        <link rel="icon" href="<?= base_url() ?>assets/images/favicon.ico" />
        <!-- ------ Pour accéder à base_url() depuis le javascript ------ -->
        <script><?php echo 'var base_url="' . base_url() . '";'?></script>
        
        <!-- -- Pour le popup login -- -->
        <link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/login-popup/css/component.css" />
        <script src="<?= base_url() ?>assets/login-popup/js/modernizr.custom.js"></script>
        <!-- for the blur effect -->
        
        <script> /* this is important for IEs */ var polyfilter_scriptpath = '/js/'; </script>
        <script src="<?= base_url() ?>assets/login-popup/js/cssParser.js"></script>
        <script src="<?= base_url() ?>assets/login-popup/js/css-filters-polyfill.js"></script>

        

    </head>
    <body>
        <?php if(isset($connected) && $connected) { ?>
        <div id="header">
            <!-- ################################################ Header Connecté ################################################ -->
            <div id="header-wraper">
                <a href="<?= base_url() ?>">
                <img src="<?= base_url() ?>assets/images/Rand_OSM_Logo.svg" alt="Logo Rand'OSM">
                </a>
                <div id="connected-box">
                    <span id="username"><?php echo $this->session->userdata('username'); ?></span>
                    <div class="icon-caret-down"></div>
                    <div id="account">
                        <a class="" href="<?= base_url() ?>logout/">Déconnexion</a><br />
                        <a class="" href="<?= base_url() ?>admin/">Mon compte</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="wraper">
            <?php }


            else { ?>
            <div id="header">
                <!-- ################################################ Header Déconnecté ################################################ -->
                <div id="header-wraper">
                    <a href="<?= base_url() ?>">
                    <img src="<?= base_url() ?>assets/images/Rand_OSM_Logo.svg" alt="Logo Rand'OSM">
                    </a>
                    <div id="login-subscribe">
                        <div class="container">
                            <button class="md-trigger button" data-modal="modal-16">Se connecter</button>
                        </div>
                        <a class="button" href="<?= base_url() ?>signup/">S'inscrire</a>
                    </div>
                </div>
            </div>
            <div class="wraper">
                
                
                <!-- --------- Login form ------------- -->
                <div class="md-modal md-effect-16" id="modal-16">
                    <div class="md-content">
                        <h3>Connexion</h3>
                        <div>
                                           <?php
                                echo form_open('admin/login');
                                
                                $attributes2 = array(
                                    'for' => 'password',
                                    'class' => 'icon-user',
                                );
                                
                                $attributes4 = array(
                                    'for' => 'login',
                                    'class' => 'icon-lock',
                                );
                                $attributes1 = array(
                                    'name'        => 'username',
                                    'id'          => 'username',
                                    'placeholder' => 'Identifiant',
                                    'autofocus'   => 'username',
                                    'required'    => 'username'
                                );
                                $attributes3 = array(
                                    'name'        => 'password',
                                    'id'          => 'password',
                                    'placeholder' => 'Mot de passe',
                                    'required'    => 'password'
                                );
                                
                                
                                echo form_input($attributes1);
                                echo form_label('', 'username', $attributes2);
                                
                                echo form_password($attributes3);
                                echo form_label('', 'password', $attributes4);
                                
                                echo form_submit('submit','Connexion');
                                
                                echo "<a href=''>Mot de passe oublié ?</a>";
                                echo form_close();
                                ?>
                        </div>
                    </div>
                </div>
                
                <div class="md-overlay"></div>
                <!-- --------------------- -->
                
                <?php } ?>




            <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
            <script>
                jQuery(function($){                   
                   $("#connected-box").click(function(){
                    if($("#account").css("display")=="block"){


                        $('#account').fadeOut(200);

                        $('#connected-box').animate(
                            {bottom : '0'},
                            {duration: 300, easing: 'swing'}
                       );
                    } else {
                       $('#connected-box').animate(
                            { bottom : '38px'}, // what we are animating
                            {
                                duration: 300, // how fast we are animating
                                easing: 'swing', // the type of easing
                                complete: function() { // the callback
                                    $( "#account" ).fadeIn(500);
                                }
                        });
                    }   
                   });  
                });
            </script>
            
            <!-- classie.js by @desandro: https://github.com/desandro/classie -->
            <script src="<?= base_url() ?>assets/login-popup/js/classie.js"></script>
            <script src="<?= base_url() ?>assets/login-popup/js/modalEffects.js"></script>