<?php

/**
* @snippet       Merge Two "My Account" Tabs @ WooCommerce Account
* @how-to        Get CustomizeWoo.com FREE
* @author        Rodolfo Melogli
* @compatible    WooCommerce 5.0
* @donate $9     https://businessbloomer.com/bloomer-armada/
*/
 
// -------------------------------
// 1. First, hide the tab that needs to be merged/moved (edit-address in this case)
 
add_filter( 'woocommerce_account_menu_items', 'bbloomer_remove_address_my_account', 999 );
 
function bbloomer_remove_address_my_account( $items ) {
   unset( $items['edit-address'] );
   return $items;
}
 
// -------------------------------
// 2. Second, print the ex tab content (woocommerce_account_edit_address) into an existing tab (woocommerce_account_edit-account_endpoint). See notes below!
 
add_action( 'woocommerce_account_edit-account_endpoint', 'woocommerce_account_edit_address' );
 
// NOTES
// 1. to select a given tab, use 'woocommerce_account_ENDPOINTSLUG_endpoint' hook
// 2. to print a given tab content, use any of these:
// 'woocommerce_account_orders'
// 'woocommerce_account_view_order'
// 'woocommerce_account_downloads'
// 'woocommerce_account_edit_address'
// 'woocommerce_account_payment_methods'
// 'woocommerce_account_add_payment_method'
// 'woocommerce_account_edit_account'


/**
 * @snippet       Rename "My Account" Link @ WooCommerce/WP Nav Menu
 * @how-to        Get CustomizeWoo.com FREE
 * @author        Rodolfo Melogli
 * @compatible    WooCommerce 4.5
 * @donate $9     https://businessbloomer.com/bloomer-armada/
 */
 
 add_filter( 'wp_nav_menu_items', 'bbloomer_dynamic_menu_item_label', 9999, 2 ); 
 
 function bbloomer_dynamic_menu_item_label( $items, $args ) { 
    if ( ! is_user_logged_in() ) { 
       $items = str_replace( "My Account", "Login", $items ); 
    } 
    return $items; 
 }

 // WOOCOMMERCE MY ACCOUNT : print the ex tab content (woocommerce_account_edit_address) into an existing tab (woocommerce_account). See notes below!
 
add_action( 'woocommerce_my_account', 'woocommerce_account_edit_address' );
add_action( 'woocommerce_my_account', 'woocommerce_account_add_payment_method' );