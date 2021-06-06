<?php

// custom_remove_user()
// delete_directory()

/**************************************************************************************************
*									FUNCTION custom_remove_user()								  *
**************************************************************************************************/

add_action( 'delete_user', 'custom_remove_user', 10 );

function custom_remove_user( $user_id ) {
	$cn_user = get_user_by( 'id', $user_id ); 
	$cn_user->user_login;
	$upload = wp_upload_dir();
	$upload_dir = $upload['basedir'];
	$upload_dir = $upload_dir . '/'.$cn_user->user_login;
    
	delete_directory($upload_dir);
}



/**************************************************************************************************
*									FUNCTION delete_directory()									  *
**************************************************************************************************/

function delete_directory($dirname) {

	if (is_dir($dirname))
		$dir_handle = opendir($dirname);

		if (!$dir_handle)
			return false;
		while($file = readdir($dir_handle)) {
			if ($file != "." && $file != "..") {
				if (!is_dir($dirname."/".$file))
						unlink($dirname."/".$file);
				else
						delete_directory($dirname.'/'.$file);
			}
		}
		closedir($dir_handle);
		rmdir($dirname);

		return true;
}