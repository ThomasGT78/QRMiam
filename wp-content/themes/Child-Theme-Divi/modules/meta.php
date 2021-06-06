<?php

// product_start_date_meta_box()
// product_start_date_meta_box_content()
// product_start_date_meta_box_save()
// my_custom_checkout_field_update_order_meta()

/**************************************************************************************************
*							FUNCTION product_start_date_meta_box()								  *
**************************************************************************************************/

add_action( 'add_meta_boxes', 'product_start_date_meta_box' );

function product_start_date_meta_box() {
	add_meta_box('product_start_date_meta_box', 'Membership Product','product_start_date_meta_box_content' ,'product','side','high');
}

/**************************************************************************************************
*						FUNCTION product_start_date_meta_box_content()							  *
**************************************************************************************************/

function product_start_date_meta_box_content( $post ) {
	$latest_issue_product_id = get_option('latest_issue_product_id');
	wp_nonce_field( plugin_basename( __FILE__ ), 'product_start_date_meta_box_content_nonce' );
	$post->ID;
	$membership_product = get_post_meta($post->ID, 'membership_product', true );
?>
	<input type="radio" id="yes" name="membership_product" value="yes" <?php if($membership_product=='yes'){echo "checked";} ?>>
	<label for="yes">Yes</label><br>
	<input type="radio" id="No" name="membership_product" value="no" <?php if($membership_product=='no'){echo "checked";} ?>>
	<label for="No">No</label><br>
<?php
} // function product_start_date_meta_box_content



/**************************************************************************************************
*							FUNCTION product_start_date_meta_box_save()							  *
**************************************************************************************************/

add_action( 'save_post', 'product_start_date_meta_box_save' );

function product_start_date_meta_box_save( $post_id ) {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
		return;
	if ( !wp_verify_nonce( $_POST['product_start_date_meta_box_content_nonce'], plugin_basename( __FILE__ ) ) )
		return;

	if ( 'page' == $_POST['post_type'] ) {
		if ( !current_user_can( 'edit_page', $post_id ) )
			return;
	} else {
		if ( !current_user_can( 'edit_post', $post_id ) )
			return;
	}
	update_post_meta($post_id, 'membership_product', $_POST['membership_product']);
} // function product_start_date_meta_box_save()



/****************************************************************************************************
*						FUNCTION my_custom_checkout_field_update_order_meta()						*
****************************************************************************************************/

add_action('woocommerce_checkout_update_order_meta', 'my_custom_checkout_field_update_order_meta');

function my_custom_checkout_field_update_order_meta( $order_id ) {
	$cn_order=wc_get_order($order_id);
	$cncc = get_post_meta($order_id);
	$timezone_format = _x( 'Y-m-d H:i:s', 'timezone date format' );
	$cn_time=date_format(date_create(date_i18n( $timezone_format )),"Y-m-d");
	$sn=0;

	foreach ( $cn_order->get_items() as $item_id => $item ) {
		$sn++;
		$product_id = $item->get_product_id(); //Get the product ID
		$quantity = $item->get_quantity(); //Get the product QTY
		$product_name = $item->get_name(); //Get the product NAME
		$product = $item->get_product();
		$description = $product->get_description();
		$cn_product_title=$product->get_title(); //Get the product NAME

		$membership_product = get_post_meta($product_id, 'membership_product', true );
		
		if ($membership_product == 'yes') {
			update_user_meta(get_current_user_id(),'membership_product_date',$cn_time);
			update_user_meta(get_current_user_id(),'membership_product_id',$product_id);
			update_user_meta(get_current_user_id(),'membership_product_order_id',$order_id);
			update_post_meta($order_id, 'odr_membership_product'.$product_id, $membership_product);
			update_post_meta($order_id, 'odr_membership_product_date'.$product_id, $cn_time);
		}
	}
} // function my_custom_checkout_field_update_order_meta()

