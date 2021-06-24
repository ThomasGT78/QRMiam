<?php

// get_template_part( 'Monday_queries/update_etablissement_name' );
/****************************************************************************************************
*									FUNCTION upload_document()										*
****************************************************************************************************/


add_shortcode('user_document_upload', 'upload_document');

function upload_document() {	// end at line 1160
	
	// get_template_part( '../css/upload_style' );
	// echo do_shortcode("[css_upload_style]");

	$site_url = 'https://qrmiam.fr';

	global $current_user; wp_get_current_user();
	date_default_timezone_set('Europe/Paris');
	ob_start();

	$upload = wp_upload_dir();
	$upload_dir = $upload['basedir'];
	$upload_url = $upload['baseurl'];

	

	// If Logged In
	if (get_current_user_id()) {	// end at line 1128
		$user_id = get_current_user_id();
		upload_profil_user_public($user_id);
		
		// if($user_id == 645){
		// 	delete_docs_unused();
		// }
		
		$membership_product_id = get_user_meta($user_id,'membership_product_id',true);
		// If Premium-Annual or Premium-Mensual or Premium-Essay
		if ($membership_product_id == 1107 || $membership_product_id == 236950 || $membership_product_id == 238609) {
			$is_premium = 1;
			$is_lite = 0;
			$is_active = 1;
		} else if ($membership_product_id == 1105 || $membership_product_id == 236951) {
			$is_premium = 0;
			$is_lite = 1;
			$is_active = 1;
		} else {
			$is_active = 0;
		}

		if($is_active){
			
			$user_monday_id = get_user_meta($user_id,'monday_item_id',true);
			
			// Get list of documents and banners from a client with his ID
			$list_user_documents = get_user_documents_By_Id(get_current_user_id());
			$list_user_documents_count = count($list_user_documents);

			$list_user_banners = get_user_banner_By_Id(get_current_user_id());
			$list_user_banners_count = count($list_user_banners);
			
			// get_user_meta() 
			$all_user_meta = get_user_meta(get_current_user_id());

			if($all_user_meta['name_style']){
				$has_name_style = true;
			} else {
				$has_name_style = false;
			}
			
			if($all_user_meta['name_size']){
				$has_name_size = true;
			} else {
				$has_name_size = false;
			}

			if(!isset($_POST['btn_update_etablishment'])){

				$user_etablissement_name = $all_user_meta['user_registration_nom_etablissement'][0];

				if($has_name_style){
					$name_style = $all_user_meta['name_style'][0];
				} else {
					$name_style = "Roboto";
				}
				
				if($has_name_size){
					$name_size = $all_user_meta['name_size'][0];
				} else {
					$name_size = 30;
				}
			}

			$membership_product_date = get_user_meta(get_current_user_id(),'membership_product_date',true);
			
			
			$cn_user = get_user_by( 'id', get_current_user_id() ); 

			// User Datas
			$etablissement_color = get_etablissement_color_By_Id(get_current_user_id());
			$user_etablissement_color = $etablissement_color[0]['etablissement_color'];
			
			



	/**************************************************************************************************
	*								DATA MANAGEMENT	WHEN FORM IS SUBMITTED							  *
	**************************************************************************************************/

			/*------------------------------------
			-	 ADD NEW DOCUMENT IS SUBMITED	--
			------------------------------------*/
			if (isset($_POST['submit_add_new_doc'])) {
				
				if ($_FILES['add_document']) { 
					
					// Number of documents oploaded
					$countfiles = count($_FILES['add_document']['name']);

					// CREATE FILE IN SERVER 
					for( $i=0 ; $i < $countfiles ; $i++ ) {
						$newpath = str_replace('\\', '/', $upload['basedir']);
						$path = $newpath.'/'.$cn_user->user_login;

						//explode file name from dot(.) 
						$ext1 = explode('.', basename($_FILES['add_document']['name'][$i]));
						$ext = end($ext1); //store extensions in the variable

						$target_path_sia =  md5(uniqid()) . "." . $ext1[count($ext1) - 1];
						$target_path_sia;

						if(move_uploaded_file($_FILES["add_document"]["tmp_name"][$i],$path. "/" . $target_path_sia)) {
							$filename = $path. "/" . $target_path_sia;
							$wp_upload_dir['url'] . '/' . basename( $filename );
							$uploaded_file[] = $upload_url . '/' . $cn_user->user_login . '/' . basename( $filename );
						} else {
								$uploaded_file='';
						}
					} // end for
					
					// CREATE DATA'S FILE IN DATABASE
					$file_data = array(
						'banner'=>'no',
						'ordering'=> $list_user_documents_count,
					);
					$response = insert_user_doc_By_Id($uploaded_file,$file_data);

					if ($response->success) {
						?> <div class="umsg"><h2>Documents téléchargés avec succès</h2></div> <?php	
					} else {
						?> <div class="umsg"><h2><?= "Erreur Document: ".$response->error ?></h2></div> <?php
					}

					// Get the new values updated
					$list_user_documents = get_user_documents_By_Id(get_current_user_id());
					$list_user_documents_count = count($list_user_documents);

				} // if ($_FILES['add_document'])
			} // if (isset($_POST['submit_add_new_doc']))

			
			/*------------------------------------
			-	  ADD NEW BANNER IS SUBMITED	--
			------------------------------------*/
			if (isset($_POST['submit_add_new_banner'])) {
				
				// fix the total number of banner that can be uploaded
				if ($user_id != 40 && $list_user_banners_count >= 1) {
					?> <div class="umsg"><h2>Vous ne pouvez uploader qu'une seule bannière</h2></div> <?php	
				}
				else if ($_FILES['add_banner']) { 
						$countfiles = count($_FILES['add_banner']['name']);
						
							$newpath = str_replace('\\', '/', $upload['basedir']);
							$path = $newpath.'/'.$cn_user->user_login;
							$ext1 = explode('.', basename($_FILES['add_banner']['name']));//explode file name from dot(.) 
							$ext = end($ext1); //store extensions in the variable
							
							$target_path_sia =  md5(uniqid()) . "." . $ext1[count($ext1) - 1];
							$target_path_sia;
							
							if(move_uploaded_file($_FILES["add_banner"]["tmp_name"],$path. "/" . $target_path_sia)){
								$filename = $path. "/" . $target_path_sia;
								$wp_upload_dir['url'] . '/' . basename( $filename );
								$uploaded_file[] = $upload_url . '/' . $cn_user->user_login . '/' . basename( $filename );
							} else {
								$uploaded_file = '';
							}

						// Get posted datas
						$new_banner_link_unsafe = $_POST['new_banner_link'];
						
						$con = mysqli_connect("localhost:3306","wp_k9v82","4uYnj0QB_9","wp_u7lia");
						$new_banner_link = mysqli_real_escape_string($con, $new_banner_link_unsafe);

						$file_data = array(
							'banner'=>'yes',
							'link'=>$new_banner_link,
							'ordering'=> $list_user_banners_count,
						);
						$response = insert_user_doc_By_Id($uploaded_file,$file_data);
						if ($response->success) {
							?> <div class="umsg"><h2>Banner téléchargés avec succès</h2></div> <?php	
						} else {
							?> <div class="umsg"><h2><?= "Erreur Bannière: ".$response->error ?></h2></div> <?php
						}
						
						// Get the new values updated
						$list_user_banners = get_user_banner_By_Id(get_current_user_id());
						$list_user_banners_count = count($list_user_banners);

					} // else if ($_FILES['add_banner'])
			} // if (isset($_POST['submit_add_new_banner']))
			
			
			
			/*------------------------
			-	 DELETE SUBMITED	--
			------------------------*/
			if (isset($_POST['btn_delete_document']) || isset($_POST['btn_delete_banner'])) {
				if (isset($_POST['btn_delete_document'])) {
					$img_id = $_POST['document_id'];
				} elseif (isset($_POST['btn_delete_banner'])) {
					$img_id = $_POST['banner_id'];
				}

				global $wpdb;
				$table_user_doce = $wpdb->prefix . 'cn_user_doce';

				$user_id = get_current_user_id();
				$file_path_raw = get_docs_path_By_docs_id($img_id);
				$file_absolute_path = $file_path_raw[0]['file'];
				$file_relative_path = str_replace("https://qrmiam.fr", ".", $file_absolute_path);
				
				
				$SQL = "DELETE FROM `".$table_user_doce."` WHERE `id`= $img_id";
				
				if (file_exists($file_relative_path)) {
					unlink($file_relative_path);
					if ($ss = iQuery($SQL,$rs)) { // return boolean
						?> <div class="umsg"><h2>Document effacé avec succès</h2></div> <?php	
					} else {
						?> <div class="umsg"><h2>Il y a eu un problème</h2></div> <?php	
					}
				} else {
					?> <div class="umsg"><h2>Le fichier n'existe pas!</h2></div> <?php
				}

				// Get the new values updated
				if (isset($_POST['btn_delete_document'])) {
					$list_user_documents = get_user_documents_By_Id(get_current_user_id());
					$list_user_documents_count = count($list_user_documents);
					
					$documents_order = [];
					foreach ($list_user_documents as $document){
						array_push($documents_order, $document['id']);
					}

					foreach ($documents_order as $key => $id) {
						$result_data = array(
							'ordering'=>$key
						);
						$response_ordering = iUpdateArrayMultiConds(
							$table_user_doce,
							$result_data,
							array('id' => $id));
						$response_ordering = json_decode($response_ordering);
					}
				} 
				if (isset($_POST['btn_delete_banner'])) {
					$list_user_banners = get_user_banner_By_Id(get_current_user_id());
					$list_user_banners_count = count($list_user_banners);

					// Manage order of not deleted banners
					$banners_order = [];
					foreach ($list_user_banners as $banner){
						array_push($banners_order, $banner['id']);
					}

					foreach ($banners_order as $key => $id) {
						$result_data = array(
							'ordering'=>$key
						);
						$response_ordering = iUpdateArrayMultiConds(
							$table_user_doce,
							$result_data,
							array('id' => $id));
						$response_ordering = json_decode($response_ordering);
					}
				}
			}

			
			/*------------------------------------
			-	 UPDATE ETABLISHMENT SUBMITED	--
			------------------------------------*/
			if (isset($_POST['btn_update_etablishment'])) {
				global $wpdb;
				$usermeta = $wpdb->prefix . 'usermeta';
				$users = $wpdb->prefix . 'users';

				
				// Update etablishment name
				if($_POST['input_etablissement_name']) {

					$new_etablissement_name_unsafe = $_POST['input_etablissement_name'];
					
					$con = mysqli_connect("localhost:3306","wp_k9v82","4uYnj0QB_9","wp_u7lia");
					$new_etablissement_name = mysqli_real_escape_string($con, $new_etablissement_name_unsafe);

					$result_etablissement_name = array('meta_value' => $new_etablissement_name);
					
					$meta_key = 'user_registration_nom_etablissement';

					$response_name = iUpdateArrayMultiConds(
						$usermeta, 
						$result_etablissement_name, 
						array(
							'user_id' => get_current_user_id(),
							'meta_key' => $meta_key
						)
					);
					$response_name = json_decode($response_name);
				
				} else {
					$new_etablissement_name = $user_etablissement_name;
				}

				// Update name style
				if($_POST['input_name_style']) {
					$new_name_style = $_POST['input_name_style'];
					
					if($has_name_style) {
						$data_name_style_to_update = array('meta_value' => $new_name_style);
						$response_style = iUpdateArrayMultiConds(
							$usermeta, 
							$data_name_style_to_update, 
							array(
								'user_id' => get_current_user_id(),
								'meta_key' => 'name_style'
							)
						);
						$response_style = json_decode($response_style);
					} else {
						$data_name_style_to_insert = array(
							'user_id' => get_current_user_id(),
							'meta_key' => 'name_style',
							'meta_value' => $new_name_style,
						);
						$response_style = iInsert(
							$usermeta, 
							$data_name_style_to_insert
						);
						$response_style = json_decode($response_style);
					}
					
				} else {
					$new_name_style = $name_style;
				}

				// Update name size
				if($_POST['input_name_size']) {
					$new_name_size = $_POST['input_name_size'];
					
					if($has_name_size) {
						$data_name_size_to_update = array('meta_value' => $new_name_size);
						$response_size = iUpdateArrayMultiConds(
							$usermeta, 
							$data_name_size_to_update, 
							array(
								'user_id' => get_current_user_id(),
								'meta_key' => 'name_size'
							)
						);
						$response_size = json_decode($response_size);
					} else {
						$data_name_size_to_insert = array(
							'user_id' => get_current_user_id(),
							'meta_key' => 'name_size',
							'meta_value' => $new_name_size,
						);
						$response_size = iInsert(
							$usermeta, 
							$data_name_size_to_insert
						);
						$response_size = json_decode($response_size);
					}
				
				} else {
					$new_name_size = $name_size;
				}

				// Update etablishment color
				if($_POST['input_etablissement_color']) {
					$new_etablissement_color = $_POST['input_etablissement_color'];

					$result_etablissement_color = array('etablissement_color' => $new_etablissement_color);
					
					$response_color = iUpdateArrayMultiConds(
						$users, 
						$result_etablissement_color, 
						array('id' => get_current_user_id())
					);
					$response_color = json_decode($response_color);

				} else {
					$new_etablissement_color = $user_etablissement_color;
				}
				
				// Update Item in Monday
				if ($response_name->success == 'success') {
					$result_update = update_etablissement_name ($new_etablissement_name, $user_monday_id);
				}

				// Display update confirmation
				if ($response_name->success == 'success' 
					&& $response_style->success == 'success'
					&& $response_size->success == 'success'
					&& $response_color->success == 'success') {
					// Get the new values updated
					$user_etablissement_name = $new_etablissement_name;
					$name_style = $new_name_style;
					$name_size = $new_name_size;
					$etablissement_color = get_etablissement_color_By_Id(get_current_user_id());
					$user_etablissement_color = $etablissement_color[0]['etablissement_color'];

					?> <div class="umsg"><h2>Mise à jour du nom d'établissement réussie</h2></div><?php
				} else {
					?> <div class="umsg"><h2>Problème! Mise à jour du nom d'établissement non effectuée!</h2></div> <?php
				}
				
			} // if (isset($_POST['btn_update_etablishment']))
			// UPDATE ETABLISHMENT SUBMITED


			/*------------------------------------------------
			-	  FUNCTION TO ORDER DOCUMENTS AND BANNER	--
			------------------------------------------------*/		

			// Displays select's <option> to choose the documents's order 
			function orderOption($documents_order, $document) {
				foreach ($documents_order as $key => $itemId) {
					$selectedOption = '<option value='.$key.' selected>'.($key+1).'</option>';
					$notSelectedOption = '<option value='.$key.'>'.($key+1).'</option>';
					// var_dump($documents_order[$key]);
					echo ($itemId == $document['id'])? $selectedOption : $notSelectedOption;
				} 
			}

			// $documents_order must be global with & to be used in orderOption()
			function actualizeOrder(&$documents_order, $option_selected, $document_id, $key_actual_doc_position) {
				array_splice($documents_order, $key_actual_doc_position, 1);
				$sliced = array_slice($documents_order, $option_selected);
				array_splice($documents_order, $option_selected);
				array_push($documents_order, $document_id);
				foreach ($sliced as $item){
					array_push($documents_order, $item );
				}

				global $wpdb;
				$table_user_doce = $wpdb->prefix . 'cn_user_doce';

				foreach ($documents_order as $key => $id) {
					$result_data = array(
						'ordering'=>$key
					);
					$response_ordering = iUpdateArrayMultiConds(
						$table_user_doce,
						$result_data,
						array('id' => $id));
					$response_ordering = json_decode($response_ordering);
				}

				// Display update confirmation
				if (isset($_POST['btn_update_document'])) {
					if ($response_ordering->success == 'success') {
						?> <div class="umsg"><h2>Ordre des documents modifié avec succès</h2></div> <?php	
					} else {
						?> <div class="umsg"><h2>Il y a eu un problème, modification de l'ordre des documents non effectuée!</h2></div> <?php
					}
				} elseif (isset($_POST['btn_update_banner'])) {
					if ($response_ordering->success == 'success') {
						?> <div class="umsg"><h2>Ordre des bannières modifié avec succès</h2></div> <?php	
					} else {
						?> <div class="umsg"><h2>Il y a eu un problème, modification de l'ordre des bannières non effectuée!</h2></div> <?php
					}
				}
			} // END actualizeOrder()


			/*--------------------------------
			-	  UPDATE BANNER SUBMITED	--
			--------------------------------*/	

			// Save the id's banner list in a table to set the order, will be used to manage the order
			$banners_order = [];
			foreach ($list_user_banners as $banner){
				array_push($banners_order, $banner['id']);
			}

			// Action after POST update banner
			if (isset($_POST['btn_update_banner'])) {
				global $wpdb;
				$table_user_doce = $wpdb->prefix . 'cn_user_doce';

				$banner_id = $_POST['banner_id'];
				
				$banner_link_unsafe = $_POST['banner_link'];
				$con = mysqli_connect("localhost:3306","wp_k9v82","4uYnj0QB_9","wp_u7lia");
				$banner_link = mysqli_real_escape_string($con, $banner_link_unsafe);

				/***	ORDER MANAGEMENT    ***/
				$key_actual_ban_position = $_POST['banner_position'];
				$option_selected = $_POST['banner_ordering'];

				actualizeOrder($banners_order, $option_selected, $banner_id, $key_actual_ban_position);
				

				/***	BANNER MANAGEMENT    ***/
				$result_data = array(
					'link' => $banner_link,
				);

				$response_banner = iUpdateArrayMultiConds(
					$table_user_doce,
					$result_data,
					array('id' => $banner_id));
				$response_banner = json_decode($response_banner);
				
				// Display update confirmation
				if ($response_banner->success == 'success') {
					?> <div class="umsg"><h2>Mise à jour de la bannière réussie</h2></div> <?php	
				} else {
					?> <div class="umsg"><h2>Il y a eu un problème, mise à jour de la bannière non effectuée!</h2></div> <?php
				}

				// Get the new values updated
				$list_user_banners = get_user_banner_By_Id(get_current_user_id());
				$list_user_banners_count = count($list_user_banners);

			} // if (isset($_POST['btn_update_banner']))
			// END UPDATE BANNER SUBMITED

			

			
			/*--------------------------------
			-	 UPDATE DOCUMENT SUBMITED	--
			--------------------------------*/

			// Save the id's document list in a table to set the order, will be used to manage the order
			$documents_order = [];
			foreach ($list_user_documents as $document){
				array_push($documents_order, $document['id']);
			}

			// Action after POST update document
			if (isset($_POST['btn_update_document'])) {
				global $wpdb;
				$table_user_doce = $wpdb->prefix . 'cn_user_doce';

				$document_id = $_POST['document_id'];

				/***	ORDER MANAGEMENT    ***/
				$key_actual_doc_position = $_POST['document_position'];
				$option_selected = $_POST['document_ordering'];

				actualizeOrder($documents_order, $option_selected, $document_id, $key_actual_doc_position);

				
				/***	DOCUMENT MANAGEMENT (IF PREMIUM)    ***/
				if ($is_premium){
					$title_unsafe = $_POST['title'];
					$con = mysqli_connect("localhost:3306","wp_k9v82","4uYnj0QB_9","wp_u7lia");
					$title = mysqli_real_escape_string($con, $title_unsafe);

					$title_color = $_POST['title_color'];
					$cn_from = $_POST['cn_from'];
					$cn_to = $_POST['cn_to'];
					
					$result_data = array(
						'title'=>$title,
						'color'=>$title_color,
						'cn_from'=>$cn_from,
						'cn_to'=>$cn_to,
					);

					// DataBase Action
					$response_document = iUpdateArrayMultiConds(
						$table_user_doce,
						$result_data,
						array('id' => $document_id));
					$response_document = json_decode($response_document);
					
					// Display update confirmation
					if ($response_document->success == 'success') {
						?> <div class="umsg"><h2>Mise à jour du document réussie</h2></div> <?php	
					} else {
						?> <div class="umsg"><h2>Il y a eu un problème, mise à jour du document non effectuée!</h2></div> <?php
					}
				} // if ($is_premium)

				// Get the new values updated
				$list_user_documents = get_user_documents_By_Id(get_current_user_id());
				$list_user_documents_count = count($list_user_documents);
				
			} // if (isset($_POST['btn_update_document']))
			// END UPDATE DOCUMENT SUBMITED




		?>




<!--*************************************************************************************************
*							PAGE HTML	-	Block Modifications du profil	/upload					*
**************************************************************************************************-->

		<div class="user-registration ur-frontend-form ur-frontend-form--flat">

			<!--------------------------------------------
			-	 FORM NOM et COULEUR d'ÉTABLISSEMENT	--
			--------------------------------------------->
			<form  method="post" accept-charset="utf-8" enctype="multipart/form-data">
				<div class="update-blocks update-name">
					<?php
					//global $current_user; wp_get_current_user();
					if ( is_user_logged_in() ) { 
						//echo 'Username: ' . $current_user->user_login . "\n"; echo 'User display name: ' . $current_user->display_name . "\n"; 
					} 
					else { 
						wp_loginout(); 
					} 
					?>
					
					<h2 class="text-center" id="etablissement_title" >
						<?php echo $user_etablissement_name; ?>
					</h2>

					<!-- Nom d'établissement -->
					<div class="div-marg-10px">
						<label for="input_etablissement_name">Nom de l'établissement </label>	
						<input type="text" class="input-text" name="input_etablissement_name" id="input_etablissement_name" value="<?php echo $user_etablissement_name; ?>">	
					</div>
					
					<!-- Font-Style -->
					<div class="div-marg-10px">
						<label>Style du nom </label>
						<select name="input_name_style" class="select-style">
							<option style="font-family: Arvo;" value="Arvo" <?php echo ($name_style == "Arvo")? "selected": "" ;?>>Arvo</option>

							<option style="font-family: Bebas Neue;" value="Bebas Neue" <?php echo ($name_style == "Bebas Neue")? "selected": "" ;?>>Bebas Neue</option>

							<option style="font-family: Bodoni Moda;" value="Bodoni Moda" <?php echo ($name_style == "Bodoni Moda")? "selected": "" ;?>>Bodoni Moda</option>

							<option style="font-family: Bree Serif;" value="Bree Serif" <?php echo ($name_style == "Bree Serif")? "selected": "" ;?>>Bree Serif</option>

							<option style="font-family: Crimson Text;" value="Crimson Text" <?php echo ($name_style == "Crimson Text")? "selected": "" ;?>>Crimson Text</option>
							
							<option style="font-family: Indie Flower;" value="Indie Flower" <?php echo ($name_style == "Indie Flower")? "selected": "" ;?>>Indie Flower</option>

							<option style="font-family: Libre Baskerville;" value="Libre Baskerville" <?php echo ($name_style == "Libre Baskerville")? "selected": "" ;?>>Libre Baskerville</option>

							<option style="font-family: Oswald;" value="Oswald" <?php echo ($name_style == "Oswald")? "selected": "" ;?>>Oswald</option>

							<option style="font-family: Roboto;" value="Roboto" <?php echo ($name_style == "Roboto")? "selected": "" ;?>>Roboto</option>
							
						</select>	
					</div>
					
					<!-- Font-Size -->
					<div class="div-marg-10px">
						<label>Taille du nom </label>	
						<input type="number" class="input-number" name="input_name_size" value="<?php echo $name_size; ?>" min=20 max=70 step="2">	
					</div>

					<!-- Couleur d'établissement -->
					<div>
						<label>Couleur du nom</label>	
						<input type="color" name="input_etablissement_color" class="input-color" value="<?php echo $user_etablissement_color; ?>">	
					</div>
					
					<!-- SUBMIT UPDATE DATAS ETABLISHMENT NAME -->
					<div class="div-btn-submit">
						<button type="submit" class="cn_btn gif_load_onClick et_pb_button" name="btn_update_etablishment">Mise à jour</button>	
					</div>
					
				</div>
			</form>
			<!-- FIN Form nom et couleur d'établissement -->
			<br>

			<!------------------------
			-	 FORM ADD IMAGES	--
			------------------------->
			<form  method="post" accept-charset="utf-8" enctype="multipart/form-data">
				<div class="update-blocks update-docs">
					
					<?php
				// USER PREMIUM
					if ($is_premium) {  
						$date = strtotime($membership_product_date);
						$new_date = strtotime('+ 1 year', $date);
						$cn_date = date('Y-m-d', $new_date);

						if ($new_date >= time()) {
					?>

						<div>
							<label><span>Ajouter une image&nbsp;</span><span>(jpg, png, PDF)</span></label>
							<input type="file" name="add_document[]" class="add_document input-file" placeholder="Image"  accept="application/pdf,image/*" required="required" multiple>
						</div>
					<?php
						}  // if ($new_date >= time())
					} // if ($is_premium)

				// USER LIGHT
					else { 
					?>
						<div>
							<label><span>Ajouter une image&nbsp;</span><span>(jpg, png)</span></label>
							<input type="file" name="add_document[]" class="add_document input-file" placeholder="Image"  accept="image/*" required="required" multiple>
						</div>
				<?php } ?>
					<div class="div-btn-submit">
						<button type="submit" class="cn_btn gif_load_onClick et_pb_button" name="submit_add_new_doc">Ajouter</button>
					</div>
				</div>
			</form>
			<!-- END form add images -->


			<!------------------------
			-	FORM ADD BANNERS	-- (only if Premium)
			------------------------->
			<?php
			if ($is_premium) {
				$date = strtotime($membership_product_date);
				$new_date = strtotime('+ 1 year', $date);
				$cn_date = date('Y-m-d', $new_date);

				if ($new_date >= time()) {
					?>

					<form method="post" accept-charset="utf-8" enctype="multipart/form-data" style="    margin-top: 10px;">
						<div class="update-blocks update-docs">
							<div class="div-marg-10px">
								<label><span>Télécharger une bannière&nbsp;</span><span>(jpg, png)</span></label>
								<input type="file" name="add_banner" class="add_banner input-file" placeholder="Image"  accept="image/*" required="required">
							</div>
							<div>
								<label>Lien de la bannière</label>
								<input type="text" name="new_banner_link" class="input-text" placeholder="Lien">
							</div>
							<div class="div-btn-submit">
								<button type="submit" class="cn_btn gif_load_onClick et_pb_button" name="submit_add_new_banner">Ajouter</button>
							</div>
						</div>
					</form>
					<!-- FIN form add banner -->
					
					<!--------------------------------
					-	 SHOW BANNERS and UPLOAD	-- (only if Premium)
					--------------------------------->
					<div class="uploaded-doc">
						<h2>Bannières en ligne</h2>

						<?php
							if ($list_user_banners) {
								foreach ($list_user_banners as $key_actual_ban_position => $banner) {
						?>
							<hr>
							<div class="div-update-item">
								<!--------------------
								-	IMAGE BANNERS	--
								--------------------->
								<div class="div-item-img div-item-img-banner">
									<a href="javascript: void(0);" onclick="open_img('<?php echo $banner['file']; ?>');"><img src="<?php echo $banner['file']; ?>" alt="" style="width: 100%;">	</a>												
								</div>
								<!--------------------
								-	UPLOAD BANNER	--
								--------------------->
								<div class="div-item-form">
									<form method="post" accept-charset="utf-8" class="form-item" >
										<div class="div-fields-item">
											<!-- Get banner id -->
											<input type="hidden" name="banner_id" value="<?php echo $banner['id']; ?>">
											<!-- Get banner position -->
											<input type="hidden" name="banner_position" value="<?php echo $key_actual_ban_position; ?>">

											<!-- ORDER -->
											<div class="div-item-order">
												<label>Ordre :</label>
												<select class="select-item-order" name="banner_ordering" >
													<?php 
													orderOption($banners_order, $banner);
													?>
												</select>
											</div>

											<!-- LINK -->
											<div class="div-banner-link">
												<label>Lien :</label>
												<input type="text" name="banner_link" class="input-text" placeholder="Lien" value="<?php echo $banner['link']; ?>">
												
											</div>
											<div class="div-a-test-link">
												<a href="<?php echo $banner['link']; ?>" target="mon site">Tester le lien</a>
											</div>
										</div>
										<!----------------------------
										-	BUTTON SUBMIT BANNER	--
										----------------------------->
										<div class="div-btn-item">
											<!--  BUTTON UPDATE BANNER  -->
											<div class="div-btn-item-update">
												<button type="submit" class="cn_btn gif_load_onClick et_pb_button " name="btn_update_banner">Mise à jour</button>	
											</div>

											<!--  BUTTON DELETE BANNER  -->
											<div class="div-btn-item-delete">
												<input type="hidden" name="banner_id" value="<?php echo $banner['id']; ?>">
												<button type="submit" class="cn_btn gif_load_onClick et_pb_button" name="btn_delete_banner">Effacer</button>
											</div> <!-- END button delete -->
										</div>
									</form> <!-- END form upload banner -->
								</div>
							</div>
						<?php
								} // foreach
							} // if ($list_user_banners) => has banner uploaded?
							
							// If doesn't have banner uploaded
							else {
						?>
							<h3 class="text-center">Pas de bannière pour le moment</h3>
						<?php
							} // END Banner and Upload
						?>
					</div>
				<?php
				} // if ($new_date >= time())
			}  // if ($is_premium) => Premium
				?>
			
			
			<!--------------------------------
			-	SHOW DOCUMENTS and UPLOAD	-- 
			--------------------------------->
			<div class="uploaded-doc">
				<h2>Documents en ligne</h2>
				
				<?php
				// If there are any documents uploaded
				if ($list_user_documents) {

					foreach ($list_user_documents as $key_actual_doc_position => $document) {
						// echo $key_actual_doc_position;
						// echo $document['ordering'];
						$fileExtension = explode('.', $document['file']);
				?>
					<!--------------------------------
					-	LIST DOCUMENTS and UPLOAD	-- 
					--------------------------------->
						<hr>
						<div class="div-update-item">

							<!------------------------
							-	 Display Document	-- 
							------------------------->
							<div class="div-item-img div-item-img-doc">
								<?php 
								// Display PDF
								if ($fileExtension[2] == 'pdf') { ?>
									<a target="_blank" href="<?php echo $document['file']; ?>?#zoom=0">
										<img src="<?= $site_url."/wp-content/uploads/2020/06/pdf_tb.png" ?>" alt="" style="width: 100%;">

										<p class="p-title-doc" style="color:<?php echo $document['color']; ?>;">
											<?php echo htmlspecialchars($document['title']); ?>
										</p>
									</a>
								<?php
								// Display IMAGE
								} else { ?>
									<a href="javascript: void(0);" onclick="open_img('<?php echo $document['file']; ?>');">
										<img src="<?php echo $document['file']; ?>" alt="" style="width: 100%;">
										<?php if ($document['title'] != "") { ?>
										<p class="p-title-doc" style="color:<?php echo $document['color']; ?>;">
											<?php echo htmlspecialchars($document['title']); ?>
										</p>
										<?php } ?>
									</a>												
								<?php } ?>
							</div> <!-- END Display Documents -->

							<!------------------------
							-	 UPDATE DOCUMENT	-- 
							-------------------------->
							<div class="div-item-form">

								<form method="post" accept-charset="utf-8" class="form-item">
									<div class="div-fields-item">
										<!-- Get document id -->
										<input type="hidden" name="document_id" value="<?php echo $document['id']; ?>">
										<!-- Get document position -->
										<input type="hidden" name="document_position" value="<?php echo $key_actual_doc_position; ?>">

										<!-- Order Document -->
										<div class="div-item-order" >
											<label>Ordre : </label>
											<select class="select-item-order" name="document_ordering" >
												<?php orderOption($documents_order, $document); ?>
											</select>
										</div>

										<?php
										if ($is_premium) { ?>
										<!-- Title Document -->
										<div>
											<label>Titre</label>
											<input type="text" name="title" class="input-text" value="<?php echo $document['title']; ?>" placeholder="title">	
										</div>

										<!-- Title Color Document -->
										<div>
											<label>Couleur du titre</label>	
											<input type="color" name="title_color" class="input-color" value="<?php echo $document['color']; ?>">	
										</div>
										
										<!-- Time to display -->
										<div>
											<label>Heure début</label>
											<input type="time" name="cn_from" class="input-time" value="<?php echo $document['cn_from']; ?>">	
										</div>
										<div>
											<label>Heure fin</label>
											<input type="time" name="cn_to" class="input-time" value="<?php echo $document['cn_to']; ?>">	
										</div>

										<?php } // if ($is_premium) ?>
									</div>
									
									<!----------------------------
									-	BUTTON SUBMIT DOCUMENT	--
									-----------------------------> 
									<div class="div-btn-item">
										<!-- BUTTON UPDATE -->
										<div class="div-btn-item-update">
											<button type="submit" class="cn_btn gif_load_onClick et_pb_button " name="btn_update_document">Mise à jour</button>	
										</div>
										<!-- BUTTON DELETE -->
										<div class="div-btn-item-delete">
											<input type="hidden" name="document_id" value="<?php echo $document['id']; ?>">
											<button type="submit" class="cn_btn gif_load_onClick et_pb_button" name="btn_delete_document">Effacer</button>
										</div>
									</div>
								</form>
							</div> <!-- END Upload Document -->

						</div>
				<?php
					} // foreach
				} // if ($list_user_documents)

				// if there isn't any documents uploaded
				else {
				?>
					<h3 class="text-center">Aucun document pour le moment</h3>

				<?php
				}
				?>

			</div> <!-- uploaded-doc -->

			<div class="mylod" >
					<img src="<?= $site_url."/wp-content/uploads/2020/06/loder.gif" ?>" style="width: 200px;position: fixed;top: 40%;left: 0px;right: 0px;margin: 0px auto;z-index: 9999999999;border-radius: 3px;">  
			</div>
		</div> <!-- user-registration -->


<!--*************************************************************************************************
*											STYLE CSS												*
**************************************************************************************************-->


		<style type="text/css" media="screen">
			@font-face {
				font-family: 'Arvo';
				font-style: normal;
				font-weight: 400;
				font-display: swap;
				src: url(https://fonts.gstatic.com/s/arvo/v14/tDbD2oWUg0MKqScQ7Q.woff2) format('woff2');
				unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
			}
			@font-face {
				font-family: 'Bebas Neue';
				font-style: normal;
				font-weight: 400;
				font-display: swap;
				src: url(https://fonts.gstatic.com/s/bebasneue/v2/JTUSjIg69CK48gW7PXoo9Wlhyw.woff2) format('woff2');
				unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
			}
			@font-face {
				font-family: 'Bodoni Moda';
				font-style: normal;
				font-weight: 400;
				font-display: swap;
				src: url(https://fonts.gstatic.com/s/bodonimoda/v7/aFT67PxzY382XsXX63LUYL6GYFcan6NJrKp-VPjfJMShrpsGFUt8oU7a8Il4tGjM.woff2) format('woff2');
				unicode-range: U+0100-024F, U+0259, U+1E00-1EFF, U+2020, U+20A0-20AB, U+20AD-20CF, U+2113, U+2C60-2C7F, U+A720-A7FF;
			}
			@font-face {
				font-family: 'Bree Serif';
				font-style: normal;
				font-weight: 400;
				font-display: swap;
				src: url(https://fonts.gstatic.com/s/breeserif/v10/4UaHrEJCrhhnVA3DgluA96rp5w.woff2) format('woff2');
				unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
			}
			@font-face {
				font-family: 'Crimson Text';
				font-style: normal;
				font-weight: 400;
				font-display: swap;
				src: url(https://fonts.gstatic.com/s/crimsontext/v11/wlp2gwHKFkZgtmSR3NB0oRJfbwhT.woff2) format('woff2');
				unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
			}
			@font-face {
				font-family: 'Indie Flower';
				font-style: normal;
				font-weight: 400;
				font-display: swap;
				src: url(https://fonts.gstatic.com/s/indieflower/v12/m8JVjfNVeKWVnh3QMuKkFcZVaUuH.woff2) format('woff2');
				unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
			}
			@font-face {
				font-family: 'Libre Baskerville';
				font-style: normal;
				font-weight: 400;
				font-display: swap;
				src: url(https://fonts.gstatic.com/s/librebaskerville/v9/kmKnZrc3Hgbbcjq75U4uslyuy4kn0qNZaxM.woff2) format('woff2');
				unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
			}
			@font-face {
				font-family: 'Oswald';
				font-style: normal;
				font-weight: 400;
				font-display: swap;
				src: url(https://fonts.gstatic.com/s/oswald/v36/TK3_WkUHHAIjg75cFRf3bXL8LICs1_FvsUZiZQ.woff2) format('woff2');
				unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
			}
			@font-face {
				font-family: 'Roboto';
				font-style: normal;
				font-weight: 400;
				font-display: swap;
				src: url(https://fonts.gstatic.com/s/roboto/v27/KFOmCnqEu92Fr1Mu4mxK.woff2) format('woff2');
				unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
			}

			.cn_btn{
				/* font-weight: bold; */
				background: #d6d6d6;
			}
			.umsg{
				background: #fff;
				text-align: center;
					padding: 20px;
				margin: 0 auto;
			}
			.uploaded-doc{
				background: #fff;
				padding: 1px 20px;
				margin: 15px auto;
				border-radius: 10px;
			}
			.cn_model{
				left:0!important;
				top: 0px!important;
				width: 100%!important;
				height: 100%!important;
				position: fixed!important;
				background: rgba(0, 0, 0, 0.5)!important;
				z-index: 999999 !important; 
				display: block;
				overflow-y: scroll; 
				display: none;
			}

			.cn_model_body{
				position: relative;
				width: 70%;
				top: 5%;
				left: 0px;
				right: 0px;
				margin: 0px auto;
				z-index: 999999!important; 
				border-radius: 3px;
			}
			.cn_close {
				cursor: pointer;
				font-weight: bold;
				color: #fff;
			}
			.input-text {
				width: 70%;
				padding: 10px 15px;
				height: 27px;
			}
			.mylod{
				left:0!important;
				top: 0px!important;
				width: 100%!important;
				height: 100%!important;
				position: fixed!important;
				background: rgba(0, 0, 0, 0.5)!important;
				z-index: 999999 !important; 
				display: none;
			}
			.cn_close_blck{
				color: #000!important;
			}
			.text-center {
				text-align: center;
			}
			#etablissement_title {
				font-family: <?php echo $name_style ?>;
				font-size: <?php echo $name_size."px" ?>;
				color: <?php echo $user_etablissement_color; ?>
			}
			.div-marg-10px {
				overflow: hidden; 
				margin-bottom: 10px;
			}
			
			.update-blocks	{
				width: 100%;
				background: #fff;
				padding: 20px;
				margin: 0 auto;
				border-radius: 10px;
			}
			.update-blocks label{
				font-size: 1.2em;
				color: black;
				display: inline-block;
				min-width: 338px;
				width: 60%;
			}
			.update-blocks label span{
				display: inline-block;
				width: max-content;
			}
			.input-text, 
			.input-number,
			.input-color,
			.select-style {
				display: inline-block;
				font-size: 1.1em;
				color: black;
				text-align: center;
				border: rgb(95, 95, 95) solid 2px !important;
				border-radius: 4px;
				height: 27px;
			}
			.update-name input{
				min-width: 200px;
				width: 30%;
			}
			.select-style {
				text-align-last: center;
				min-width: 200px;
				width: 30%;
			}
			.update-docs .input-text {
				min-width: 221px;
				width: 35%;
			}
			.update-blocks .input-file {
				font-size: 1.1em;
				color: black;
				display: inline-block;
				min-width: 221px;
				width: 35%;
			}
			.update-blocks button{
				margin-top: 25px;
				padding-top: 4px !important;
				padding-bottom: 4px !important;
			}
			.update-blocks button:hover{
				padding-top: 3px !important;
				padding-bottom: 3px !important;
			}
					
			.div-update-item {
				display: flex;
				justify-content: space-around;
				align-content: center;
				flex-wrap: wrap;
				margin: 10px 0;

			}
			.div-item-img-banner {
				display: table;
				width: 170px;
				min-height: 170px;
			}
			.div-item-img-doc {
				display: table;
				width: 170px;
				height: 266px;
			}
			.div-item-img img{
				box-shadow: 0px 0px 4px 1px black;
				margin-top: 4px;
			}
			.div-item-img a{
				display: table-cell;
				vertical-align: middle;
			}
			.div-item-form {
				width: 65%;
				display: flex;
				align-content: center;
			}
			.form-item {
				display: flex;
				justify-content: space-around;
				align-content: center;
				flex-wrap: wrap;
				width: 100%;
				margin: 10px 0;
			}
			.div-fields-item {
				display: flex;
				flex-direction: column;
				justify-content: center;
				width: 65%;
				min-width: 250px;
			}
			.div-fields-item label,
			.div-a-test-link {
				font-size: 1.2em;
				color: black;
			}
			.div-fields-item > div{
				display: flex;
				flex-wrap: wrap;
				justify-content: space-between;
				margin: 5px 0;
				overflow: hidden;
			}
			.div-fields-item input, .div-fields-item select {
				display: inline-block;
				transition: all .35s;
				border-radius: 4px;
			}
			.div-banner-link > label{
				display: inline-block;
				vertical-align: text-top;
			}
			.select-item-order,
			.input-color,
			.input-time {
				width: 80px;
				text-align-last: center;
				font-weight: 600;
				border: rgb(95, 95, 95) solid 2px;
				border-radius: 4px;
				height: 27px;
			}
			.select-item-order option{
				padding-left: 15px;
				text-align: center;
				text-align-last: center;
				text-indent: 5px;
			}

			.div-btn-item {
				display: flex;
				flex-direction: column;
				justify-content: center;
				margin: 10px 0 10px 3%;
			}
			.div-btn-item button{
				margin: 3px;
				width: max-content;
				padding-top: 4px !important;
				padding-bottom: 4px !important;
			}

			.div-btn-item button:hover{
				padding-top: 3px !important;
				padding-bottom: 3px !important;
			}
			.p-title-doc {
				text-align: center; 
				font-size: 17px;
			}
			.div-item-img-doc a {
				text-decoration: none;
			}


						/****************
						*   TABLETTE    *
						****************/
			@media(min-width: 780px) and (max-width: 980px){
				.div-item-form {
					width: 65%;
					display: flex;
					align-content: center;
					margin-left: 10px;
				}
				.div-fields-item {
					width: 100%;
				}
				
				.div-btn-item {
					display: flex;
					flex-direction: row;
					justify-content: space-around;
					margin: 10px 0;
				}
				.div-item-img {
					display: table;
					width: 170px;
					min-height: 188px;
				}
			}

						/****************
						*   SMARTPHONE  *
						****************/
			@media(max-width: 779px){
				.update-blocks label{
					display: flex !important;
					flex-wrap: wrap;
					min-width: min-content !important;
					width: 100% !important;
				}
				.update-blocks label span{
					display: inline-block;
					min-width: min-content;
					width: auto !important;
				}
				.update-name input,
				.select-style {
					min-width: min-content !important;
					width: 100% !important;
					max-width: 250px;
				}
				.update-docs .input-text {
					min-width: min-content !important;
					width: 100% !important;
					max-width: 310px;
				}
				.update-blocks .input-file {
					font-size: 1.1em;
					color: black;
					display: inline-block;
					min-width: min-content !important;
					width: 100% !important;
				}
				.div-btn-submit{
					text-align: center;
				}

				.div-item-form {
					width: 100%;
					max-width: 300px;
					display: flex;
					flex-direction: column;
					align-content: center;
				}
				.div-fields-item {
					width: 100%;
					min-width: min-content;
				}
				
				.div-btn-item {
					display: flex;
					flex-direction: row;
					flex-wrap: wrap;
					justify-content: space-around;
					width: 100%;
					margin: 10px 0;
				}
				.div-item-img {
					display: table;
					width: 100%;
					max-width: 300px;
					min-height: 188px;
				}
				.select-item-order,
				.input-color {
					width: 26%;
					min-width: 40px;
					text-align-last: center;
					font-weight: 600;
				}
				.input-time {
					width: 26%;
					min-width: 75px;
					text-align-last: center;
					font-weight: 600;
				}
			}
		</style>


<!--*************************************************************************************************
*											SCRIPT JS												*
**************************************************************************************************-->

		<script type="text/javascript">
			(function( $ ) {
				'use strict';
				$(document).ready(function() {
					
					// Show loading gif when submit add document/banner form
					$('.gif_load_onClick').click(function() {
						if ($('.add_banner').val() || $('.add_document').val()) {
							$(".mylod").show();
						}
					});
					
					$('.cn_close').click(function() {
						$(".cn_model").hide();
						jQuery('.cn_new_img').attr('src','');
						jQuery('.cn_new_PDF').attr('data','');
						jQuery('.cn_new_PDF').attr('width','');
						jQuery('.cn_new_PDF').attr('height','');
					});

					$('.open_cn_model').click(function() {
						$('.mylod').show();
						var open_cn_model_id = $(this).attr('data-target');
						$(open_cn_model_id).show();

						setTimeout(function() {
							$('.mylod').hide();
						}, 1000);
					});

					// Actualize the documents and banners's order on change of the value (submit the form)
					$(".select-item-order").change(function () {
						var selectName = $(this).attr('name');
						var closestForm = $(this).closest('form');

						// There are 2 submit button in each form so we must select one to submit the form
						if(selectName == "banner_ordering") {
							closestForm.find('button[name=btn_update_banner]').click();
						} else if (selectName == "document_ordering") {
							closestForm.find('button[name=btn_update_document]').click();
						}
					})
				})  // $(document).ready

			})( jQuery ); // function( $ )


			function open_img(img){
				jQuery('.cn_new_img').attr('src',img);
				jQuery(".cn_model").show();
			}

			function open_PDF(img){
				jQuery('.cn_new_PDF').attr('data',img);
				jQuery('.cn_new_PDF').attr('width','100%');
				jQuery('.cn_new_PDF').attr('height','500px');
				jQuery(".cn_model").show();
			}

		</script>

					
			
		<?php
				// }else{
				// 	echo '<h3 style="text-align: center;">This is not your page</h3>';
				// }
			// }else{
			// 	echo '<h3 style="text-align: center;">invalid user</h3>';
			// }
		// } else {
		// 	header("Location: https://qrmiam.fr/accueil/abonnement/");
		// 	die();
		} // END if($membership_product_id != 1000) => si user est Actif
		else {
			?>
			<br>
			<h2 style="text-align: center;">Vous n'êtes plus abonnés</h2>
			<h2 style="text-align: center; color: red"><a href="https://qrmiam.fr/accueil/abonnement/">Abonnez-vous ici</a></h2>
			<br>
			<?php
		}
	} // END if(get_current_user_id()) => Si user id exist (ligne 437)
	else {
		echo '<h3 style="text-align: center;">Vous devez vous authentifier pour accéder à la page</h3>';
	}

		$ReturnString = ob_get_contents();
		ob_end_clean();
		return $ReturnString;

} // function upload_document() (ligne 454)



