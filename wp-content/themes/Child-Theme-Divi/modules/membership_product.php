<?php

/****************************************************************************************************
*						FUNCTION membership_product()	/abonnement									*
****************************************************************************************************/

add_shortcode( 'membership_product' , 'membership_product' );

function membership_product() { // en at line 1797
	ob_start();
	
	$site_url = 'https://qrmiam.fr';

  	$args = array(
		'posts_per_page' => -1, 
		'post_type' => 'product',
		'meta_key'=>'membership_product',
		'meta_value'=>'yes',
		'orderby' => 'id',
		'order' => 'ASC',
	);

  	if (get_current_user_id()) {
		$membership_product_date = get_user_meta(get_current_user_id(),'membership_product_date',true);
		$membership_by = get_user_meta(get_current_user_id(),'membership_by',true);
		$membership_product_id = get_user_meta(get_current_user_id(),'membership_product_id',true);
		$membership_product_order_id = get_user_meta(get_current_user_id(),'membership_product_order_id', true);	
		$order = wc_get_order($membership_product_order_id);

		if ($membership_product_id == 1105) {
			$completed = 'completed';
		} else {
			if ($membership_product_order_id) {
				if ($order) {
					$order->get_status();
					if($order->get_status() == 'completed') {
						$completed = 'completed';
					} else {
						$completed = 'incompleted';
					}		
				}
			} // if ($membership_product_order_id)
		}
		if ($membership_product_id == 1107 && $membership_by == 'admin') {
			$completed = 'completed';
		}
		
		$odr_membership_product = get_post_meta($post->ID, 'odr_membership_product'.$membership_product_id, true );

		$odr_membership_product_date = get_post_meta($post->ID, 'odr_membership_product_date'.$membership_product_id, true );

  	} // if (get_current_user_id())
  	
  	?>
	
<!--*************************************************************************************************
*											STYLE CSS												*
**************************************************************************************************-->

  	<style type="text/css" media="screen">
  	/* .cn_none {display: none}
  	div#page-container { padding: 0 !important; margin: 0px !important; } */
	
	/* Style for the buttons to subscribe */
  	a.et_pb_button { 
		border-radius: 50px;
		border-color: #2f688d !important;
	  	background: #2f688d !important;
	}
  	a.et_pb_button:hover { padding: 10px 15px !important; }
  	</style>


<!--*************************************************************************************************
*								BLOCK HTML des prix des abonnements									*
**************************************************************************************************-->

  	<div class="et_pb_row et_pb_row_1 et_pb_equal_columns">
		<div class="et_pb_column et_pb_column_4_4 et_pb_column_1  et_pb_css_mix_blend_mode_passthrough et-last-child">
			<div class="et_pb_with_border et_pb_module et_pb_pricing_tables_0 et_pb_pricing clearfix et_pb_pricing_3 et_pb_second_featured et_pb_pricing_no_bullet et_had_animation" >
				<div class="et_pb_pricing_table_wrap">
  	<?php
   		$cn_product = new WP_Query( $args );
   		if($cn_product->have_posts()) {
   			$cnsn = 0;

			while ($cn_product->have_posts()) { 
				$cn_product->the_post();
				$product_id = get_the_ID();
				$cnsn++;
				global $product;
	?>
				<!-- DIV LIGHT / PREMIUM / COMMERCE -->
					<div class="et_pb_pricing_table et_pb_pricing_table_0 <?php if($cnsn == 2){ echo 'et_pb_featured_table';} ?>">

						<div class="et_pb_pricing_heading">
							<h2 class="et_pb_pricing_title"><?php echo get_the_title($product_id); ?></h2>
							<h1>Test</h1>
						</div> <!-- .et_pb_pricing_heading -->
						
						<div class="et_pb_pricing_content_top">
							<span class="et_pb_et_price">
								<span class="et_pb_dollar_sign" style="margin-left: -11px;">
									<?php
									if ($product_id!=1178) {
										echo get_woocommerce_currency_symbol();	
									}
									  ?>
								</span>
								<span class="et_pb_sum">
									<?php
									if ($product_id!=1178) {
										echo $product->get_price();
									} else {
										echo '<span style="font-size: 25px;">SUR DEMANDE</span>';
									} ?>
								</span>
								<span class="et_pb_frequency">
									<?php  if ($product_id!=1178) {?>
										<span class="et_pb_frequency_slash">/</span>an
									<?php } ?>
								</span>
							</span>
						</div> <!-- .et_pb_pricing_content_top -->
						<div class="et_pb_pricing_content">
							<?php the_content() ?>
						</div> <!-- .et_pb_pricing_content -->

						<!-- BUTTONS TO SUBSCRIBE  -->
						<div class="et_pb_button_wrapper">
							<?php
							if ($product_id!=1178) { // Si produit différent de commerce
							  	if (is_user_logged_in() == 1) { // Si loggé
							  		//echo "debug";
							  		if($membership_product_id == 1105) { //Si compte gratuit
							  			if($product_id == 1105) { // Si produit gratuit
							  				//Pas de bouton
							  			}
							  			if($product_id == 1107){ // Si produit premium
							  				//echo "debug";
							  				echo '<a class="et_pb_button et_pb_custom_button_icon et_pb_pricing_table_button" href="?add-to-cart='.$product_id.'" rel="nofollow" data-product_id="'.$product_id.'" data-icon="$">SOUSCRIRE</a>';
							  			}
							  		}

							  		if($membership_product_id == 1107) { // Si compte premium
							  			if($product_id == 1105) { // Si produit gratuit
							  			}

							  			if($product_id == 1107) { // Si produit premium
							  				echo'<a class="et_pb_button et_pb_custom_button_icon et_pb_pricing_table_button" href="#" rel="nofollow" data-product_id="1106" data-icon="$">Déjà abonné</a>';
							  			}
							  		}
							  	} // if (is_user_logged_in() == 1)
							  	else { // Si pas loggé
							  		if($product_id==1105) {
							  			echo'<a class="et_pb_button et_pb_custom_button_icon et_pb_pricing_table_button" href="'.$site_url.'/#formulaire" rel="nofollow" data-product_id="1106" data-icon="$">S\'inscrire</a>';
							  		}
							  		else {
										echo'<a class="et_pb_button et_pb_custom_button_icon et_pb_pricing_table_button" href="'.$site_url.'/#formulaire" rel="nofollow" data-product_id="1106" data-icon="$">S\'inscrire</a>';
							  		}
							  	} // Else pas loggé

							} // if ($product_id!=1178)
							else { //Si produit == commerce
								echo '<a class="et_pb_button et_pb_custom_button_icon et_pb_pricing_table_button" href="$'.$site_url.'/forment" rel="nofollow" > CONTACT </a>';									
							}	
							?>
						</div>
					</div>
				<?php 
			} // while
		} // if($cn_product->have_posts())
		?>				
						</div>
					</div>
				</div>
			</div>

		<?php
		$ReturnString = ob_get_contents(); ob_end_clean(); 

 		return $ReturnString;
		 
} // function membership_product() (Start at Line 1609)
