<?php

/****************************************************************************************************
*										FUNCTION pop_up()   										*
****************************************************************************************************/

add_shortcode('pop_up_page', 'pop_up');

function pop_up() {
	ob_start();
    if ($_GET['username'] == 'teste') { // CONDITION POUR TEST à effacer après
		$username = $_GET['username'];
        $user_id = get_userID_By_username($username);
        $membership_product_id = get_user_meta($user_id,'membership_product_id',true);
		// If Premium-Annual or Premium-Mensual or Premium-Essay
		if ($membership_product_id == 1107 || $membership_product_id == 236950 || $membership_product_id == 238609) {
			$is_premium = 1;
		} else if ($membership_product_id == 1105 || $membership_product_id == 236951) {
			$is_lite = 1;
		}

        // TEST
        if ($user_id == 40){
			$is_lite = 1;
		}
       
        
        

        $list_user_banners = get_user_banner_By_Id($user_id);
        $img_popup = $list_user_banners[1];
        $popup_link = $img_popup['link'];
        $popup_path = $img_popup['file'];


        if ($is_lite) { 
            /*************
            *    HTML    *
            *************/ 
            ?>
            <div id="window_pop_up" class="">
                <div id="block_pop_up">
                    <div id="top_bar">
                        <label id="top_bar_msg">Fermez dans <span id="seconds">5</span> sec</label>
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
                
                function countDown(){
                    var seconds = 4;
                    var decount = setInterval(decrementSeconds, 1000);

                    function decrementSeconds() {
                        if(seconds > 0){
                            sec.innerHTML = seconds;
                            seconds--;
                            return seconds;
                        } else {
                            clearInterval(decount);
                            topBarMsg.innerHTML = "X";
                            topBarMsg.className = "close-popup";
                        }
                    }
                }
                countDown();

                // $('.close-popup').click(function() {
                $('#top_bar').on("click", '.close-popup', function() {
                    console.log("OK");
                    $('#window_pop_up').hide();
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
                z-index: 1000;
                display: flex;
                justify-content: center;
                align-content: space-around;
            }
            #block_pop_up{
                width: 50%;
                min-width: 200px;
                z-index: 1001;
            }
            #top_bar{
                width: 100%;
                z-index: 1002;
                color: white;
                text-align: right;
            }
            #top_bar_msg{
                text-align: right;
            }
            #img_block{
                width: auto;
                max-width: 100%;
                z-index: 1002;
            }
            .hidden{
                display: none;
            }
            .close-popup{
                font-weight: bold;
                font-size: 1.2rem;
            }

            @media(max-width: 779px){
                #block_pop_up{
                    width: 90%;
                    z-index: 1001;
                }
            }
            </style>
            <?php }

            $ReturnString = ob_get_contents(); ob_end_clean(); 
            return $ReturnString;
    } // if ($_GET['username'])
} // END FUNCTION pop_up()

?>
<!-- 
<SCRIPT language="javascript">
    function ouvre_popup(page) {
    window.open(page,"nom_popup","menubar=no, status=no, scrollbars=no, menubar=no, width=200, height=100");
    }
</SCRIPT> -->