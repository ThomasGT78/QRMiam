<?php

/****************************************************************************************************
*									FUNCTION first_visit()										*
****************************************************************************************************/


add_shortcode('first_visit', 'first_visit');

function first_visit() {
    if (get_current_user_id()) {
        $membership_product_id = get_user_meta(get_current_user_id(),'membership_product_id',true);
        if($membership_product_id){
            // header("Location: https://qrmiam.fr/accueil/mon-compte/");
            // die();
        } else {
            header("Location: https://qrmiam.fr/accueil/abonnement/");
            die();
        }
    }
}