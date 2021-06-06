<?php

// cn_menu()
// cn_menu_content()

/****************************************************************************************************
*										FUNCTION cn_menu()											*
****************************************************************************************************/

add_action('admin_menu','cn_menu');

function cn_menu(){
	add_menu_page('Upload Banner', 'Upload Banner', 'manage_options', 'cn-menu', 'cn_menu_content','dashicons-text-page',5);
}


/****************************************************************************************************
*									FUNCTION cn_menu_content()										*
****************************************************************************************************/

function cn_menu_content() {
 	if (isset($_POST['cn_submit'])) {
 		update_option('cn_all_banner',$_POST['cn_all_banner']);
 		update_option('cn_all_banner_link',$_POST['cn_all_banner_link']);
 		echo '<h3 style="text-align: center;">Updated successfully</h3>';
 	}

 	$cn_all_banner=get_option('cn_all_banner');
 	$cn_all_banner_link=get_option('cn_all_banner_link');
 	?>

 	<form method="post" accept-charset="utf-8">
 		<br>
		<br>
		<br>
 		<label>Banner 1</label>
		<br>
  		<input type="text" name="cn_all_banner[]" style="width: 500px" value="<?php echo $cn_all_banner[0]; ?>" placeholder="">
  		<input type="text" name="cn_all_banner_link[]" style="width: 500px" value="<?php echo $cn_all_banner_link[0]; ?>" placeholder="link">
  		<br>
		<br>
  		<label>Banner 2</label>
		<br>
  		<input type="text" name="cn_all_banner[]" style="width: 500px" value="<?php echo $cn_all_banner[1]; ?>" placeholder="">
  		<input type="text" name="cn_all_banner_link[]" style="width: 500px" value="<?php echo $cn_all_banner_link[1]; ?>" placeholder="link">
  		<br>
		<br>
  		<label>Banner 3</label>
		<br>
  		<input type="text" name="cn_all_banner[]" style="width: 500px" value="<?php echo $cn_all_banner[2]; ?>" placeholder="">
  		<input type="text" name="cn_all_banner_link[]" style="width: 500px" value="<?php echo $cn_all_banner_link[2]; ?>" placeholder="link">
  		<br>
		<br>
  		<button type="submit" name="cn_submit" class="button">Submit</button>
 	</form>
<?php
} // function cn_menu_content()
