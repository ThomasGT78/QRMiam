<?php

/****************************************************************************************************
*										FUNCTION pop_up()   										*
****************************************************************************************************/

add_shortcode('pop_up_cahier', 'pop_up_cahier');

function pop_up_cahier() {
	ob_start();
    if ($_GET['username'] == 'DevTest8' OR $_GET['username'] == 'teste') { // CONDITION POUR TEST à effacer après
		$username = $_GET['username'];
        $user_id = get_userID_By_username($username);
        $membership_product_id = get_user_meta($user_id,'membership_product_id',true);
		$user_etablissement_name = get_user_meta($user_id,'user_registration_nom_etablissement',true);
        
		$user_etablissement_type = get_user_meta($user_id,'user_registration_check_box_1591092840',true);
        // If Premium-Annual or Premium-Mensual or Premium-Essay
		if ($membership_product_id == 1107 || $membership_product_id == 236950 || $membership_product_id == 238609) {
			$is_premium = 1;
		} else if ($membership_product_id == 1105 || $membership_product_id == 236951) {
			$is_lite = 1;
		}

        // USED FOR TEST
        if ($user_id == 40 OR $user_id == 645){
			$is_premium = 1;
            $is_test = 1;
		}
       
        // Get the second banner of admin profile used for the pop-up
        $list_user_banners = get_user_banner_By_Id(40);
        $img_popup = $list_user_banners[1];
        $popup_link = $img_popup['link'];
        $popup_path = $img_popup['file'];

        
        if ($is_test) { 
        if ($is_premium) { 
            /*************
            *    HTML    *
            *************/ 
            ?>
            <div id="window_pop_up" class="">
                <div id="block_pop_up">
                    <div id="top_bar">
                        <label id="label_top_bar">Fermez ici &nbsp &nbsp<span id="cross">X</span></label>
                    </div>
                    <div id="body_block" class="">
                        <div id="title_cahier" class="centered">
                            <h1>CAHIER DE RAPPEL</h1>
                        </div>
                        
                        <div id="info_covid" class="centered">
                            <h3>Face au COVID-19</h3>
                            <h3>Tous concernés !</h3>
                            <p>Merci de renseigner vos informations</p>
                        </div>
                        <form>
                            <div class="div-marg-20px"><input type="text" class="input-text" id="name" placeholder="NOM (Name)"/></div>
                            <div class="div-marg-20px"><input type="text" class="input-text" id="firstName" placeholder="Prénom (Firstname)"/></div>
                            <div class="div-marg-20px"><input type="tel" class="input-text" id="phone" placeholder="Téléphone (Phone)"/></div>

                            <div class="div-btn-cahier"><button type="button" class="cn_btn gif_load_onClick et_pb_button">Envoyer</button></div>
                        </form>
                        <div id="info-cnil">
                            <p>*</p>
                            <p>
                                Les informations recueillies sur ce formulaire sont enregistrées et utilisées uniquement par notre établissement : <?= $user_etablissement_name ?>
                            </p>
                            <p>
                                Conformément au protocole sanitaire applicable aux <?= $user_etablissement_type ?>, vos données seront uniquement utilisées pour faciliter la recherche des « cas contacts » par les autorités sanitaires, et ne seront pas réutilisées à d’autres fins.
                            </p>
                            <p>
                                En cas de contamination de l’un des clients au moment de votre présence, ces informations pourront être communiquées aux autorités sanitaires compétentes (agents de l’assurance maladie et/ou de l’agence régionale de santé), afin de vous contacter et de vous indiquer le protocole sanitaire à suivre. 
                            </p>
                            <p>
                                Vos données seront conservées pendant 15 jours à compter de leur collecte, et seront supprimées à l’issue de ce délai.
                            </p>
                            <p>
                                Vous pouvez accéder aux données vous concernant, les rectifier ou exercer votre droit à la limitation du traitement de vos données. 
                            </p>
                            <p>
                                Pour exercer ces droits ou pour toute question sur le traitement de vos données, vous pouvez contacter [coordonnées téléphonique, postales ou électroniques pour contacter la personne de votre établissement qui sera chargée de répondre à la demande] 
                            </p>
                            <p>
                                Si vous estimez, après nous avoir contactés, que vos droits sur vos données ne sont pas respectés, vous pouvez adresser une réclamation à la CNIL.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <?php
            /*******************
            *    JavaScript    *
            *******************/ 
            ?>
            <script type="text/javascript">
            (function( $ ) {
                
                // Efface le menu qui reste au dessus
                $('.header-content').addClass('hidden');
                $('.et_pb_menu_inner_container').addClass('hidden');
                $('.et_pb_module').addClass('nopadding');

                // Close pop-up when click on the exit X
                $('#label_top_bar').click(function() {
                    $('#window_pop_up').hide();
                    $('.header-content').removeClass('hidden'); // réaffiche le menu
                    $('.et_pb_menu_inner_container').removeClass('hidden');
                    $('.et_pb_module').removeClass('nopadding');
                });

            })( jQuery ); // function( $ )
            </script> 
        
                <?php
                /************
                *    CSS    *
                ************/ 
                ?>
            <style type="text/css" media="screen">
            #window_pop_up{
                width: 100%;
                height: 1000%;
                position: fixed;
                top: 0;
                left: 0;
                background-color: rgba(0, 0, 0, 0.95);
                z-index: 5001;
                display: flex;
                justify-content: center;
                align-content: space-around;
            }
            #block_pop_up{
                margin-top: 5%;
                width: 50%;
                min-width: 200px;
                height: max-content;
                background-color: #F4F4F4;
                z-index: 5001;
                border-radius: 5px;
            }
            #top_bar{
                width: 100%;
                z-index: 5001;
                color: white;
                text-align: right;
                /* background-color: rgba(0, 0, 0, 0.95); */
                background-color: #b22222;
                text-align: right;
                font-size: 1.2rem;
                padding: 2px;
                border-radius: 5px 5px 0 0;
            }
            #label_top_bar{
                /* background-color: #b22222; */
                padding-right: 4px;
            }
            #body_block{
                width: 100%;
                padding: 15px;
                z-index: 5001;
            }
            #cross{
                font-weight: bold;
                font-size: 1.4rem;
            }
            .centered{
                text-align: center;
            }
            #title_cahier{
                margin: 10px 0;
            }
            #info_covid{
                margin: 5px 0;
            }
            .input-text {
				width: 100%;
				padding: 10px 15px;
				font-size: 1.1em;
				color: black;
				text-align: center;
				border: rgb(95, 95, 95) solid 2px !important;
				border-radius: 4px;
				height: 27px;
			}
            .div-marg-20px{
				overflow: hidden; 
				margin: 20px 0;
            }
            .div-btn-cahier{
				text-align: right;
                margin: 30px 10px 0 0;
            }

            .hidden{
                display: none !important;
            }
            .nopadding{
                padding: 0;
            }
            #info-cnil{
                font-size: 0.5rem;
            }
            #info-cnil p{
                line-height: 0.7rem;
            }

            /* MEDIA QUERIES */
            @media(max-width: 779px){
                #block_pop_up{
                    width: 90%;
                    z-index: 5001;
                }
            }
            </style><?php
        } // if ($is_lite)
        } // if ($is_test)

            $ReturnString = ob_get_contents(); ob_end_clean(); 
            return $ReturnString;
            
    } // if ($_GET['username'])
} // END FUNCTION pop_up_cahier()


?>