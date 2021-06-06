<?php

// pippin_add_user_id_column()
// pippin_show_user_id_column_content()

/****************************************************************************************************
*								FUNCTION pippin_add_user_id_column()								*
****************************************************************************************************/

add_filter('manage_users_columns', 'pippin_add_user_id_column');

function pippin_add_user_id_column($columns) {
    $columns['membership'] = 'Membership';
    return $columns;
}


/****************************************************************************************************
*							FUNCTION pippin_show_user_id_column_content()							*
****************************************************************************************************/

add_action('manage_users_custom_column',  'pippin_show_user_id_column_content', 10, 3);

function pippin_show_user_id_column_content($value, $column_name, $user_id) {
    $user = get_userdata( $user_id );
    $membership_product_id = get_user_meta($user_id,'membership_product_id',true);
	if ( 'membership' == $column_name ){
		if($membership_product_id == 1105) { 
			$cn_membership='GRATUIT';
		} 
	    if($membership_product_id == 1107) { 
			$cn_membership='PREMIUM';
		}
		return $cn_membership;
	}
}
