<?php

/****************************************************************************************************
*										FUNCTION pop_up()   										*
****************************************************************************************************/

add_shortcode('pop_up_advertizement', 'pop_up_advertizement');

function pop_up_advertizement() {
	ob_start();
    if ($_GET['username'] == 'DevTest8' OR $_GET['username'] == 'test') { // CONDITION POUR TEST à effacer après
		$username = $_GET['username'];
        $user_id = get_userID_By_username($username);
        $membership_product_id = get_user_meta($user_id,'membership_product_id',true);
		// If Premium-Annual or Premium-Mensual or Premium-Essay
		if ($membership_product_id == 1107 || $membership_product_id == 236950 || $membership_product_id == 238609) {
			$is_premium = 1;
		} else if ($membership_product_id == 1105 || $membership_product_id == 236951) {
			$is_lite = 1;
		}

        // USED FOR TEST
        if ($user_id == 40 OR $user_id == 645){
			$is_lite = 1;
            $is_test = 1;
		}
       
        // Get the second banner of admin profile used for the pop-up
        $list_user_banners = get_user_banner_By_Id(40);
        $img_popup = $list_user_banners[1];
        $popup_link = $img_popup['link'];
        $popup_path = $img_popup['file'];

        
        if ($is_test) { 
        if ($is_lite) { 
            /*************
            *    HTML    *
            *************/ 
            ?>
            <div id="window_pop_up" class="">
                <div id="block_pop_up">
                    <div id="top_bar">
                        <label id="top_bar_msg">La publicité disparaitra dans <span id="seconds">5</span> sec</label>
                    </div>
                    <div id="img_block" class="">
                        <a href="<?php echo $popup_link ?>" target="_blank"> <img src="<?php echo $popup_path ?>" alt="QRMiam"></a>
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
                var sec = document.getElementById('seconds');
                var topBarMsg = document.getElementById('top_bar_msg');
                
                // Efface le menu qui reste au dessus
                $('.header-content').addClass('hidden');
                $('.et_pb_menu_inner_container').addClass('hidden');
                $('.et_pb_module').addClass('nopadding');

                // Fonction pour le compte à rebour
                function countDown(){
                    var seconds = 4;
                    var decount = setInterval(decrementSeconds, 1000);

                    // fonction réalisée chaque seconde jusqu'à atteindre 0
                    function decrementSeconds() {
                        if(seconds > 0){
                            sec.innerHTML = seconds;
                            seconds--;
                            return seconds;
                        } else {
                            clearInterval(decount);
                            $('.header-content').removeClass('hidden');
                            $('.et_pb_menu_inner_container').removeClass('hidden');
                            $('.et_pb_module').removeClass('nopadding');
                            $('#window_pop_up').hide(); // close the pop-up by itself
                            // topBarMsg.innerHTML = "X";
                            // topBarMsg.className = "close-popup";
                        }
                    } //  function decrementSeconds()
                } // function countDown()

                countDown();

                // Close pop-up when click on the exit X
                $('#top_bar').on("click", '.close-popup', function() {
                    $('#window_pop_up').hide();
                    $('.header-content').removeClass('hidden');
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
                z-index: 5001;
            }
            #top_bar{
                width: 100%;
                z-index: 5001;
                color: white;
                text-align: right;
            }
            #top_bar_msg{
                text-align: right;
            }
            #img_block{
                width: auto;
                max-width: 100%;
                z-index: 5001;
            }
            .close-popup{
                font-weight: bold;
                font-size: 1.4rem;
            }
            .hidden{
                display: none !important;
            }
            .invisible{
                visibility: hidden;
            }
            .nopadding{
                padding: 0;
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
} // END FUNCTION pop_up_advertizement()


?>