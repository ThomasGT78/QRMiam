<?php

/**************************************************************************************************
*									FUNCTION delete_documents()         						  *
*   Delete banners unused anymore by Lite users if any exists when they become lite				  *
**************************************************************************************************/

add_action('woocommerce_payment_complete','delete_banners_lite');

function delete_banners_lite(){

    global $wpdb;
    $table_cn_user_doce = $wpdb->prefix . 'cn_user_doce';
    $user_id = get_current_user_id();

    if ($user_id == 40) {
        $is_admin = true;
    } else {
        $is_admin = false;
    }


    $membership_product_id = get_user_meta($user_id,'membership_product_id',true);
    if ($membership_product_id == 1105 || $membership_product_id == 236951) {
        $is_lite = true;
    }
    
    if(!$is_admin){ // Doesn't work for admin profile in case of change of status while testing

        if ($is_lite){
            $current_user = wp_get_current_user();
            $user_login = $current_user->user_login;

            $list_user_banners = get_user_banner_By_Id($user_id);
            $list_user_documents = get_user_documents_By_Id($user_id);

            // DELETE BANNER WHEN BECOME LITE
            if ($list_user_banners){
                foreach ($list_user_banners as $banner){
                    $banner_id = $banner['id'];
                    $file_path_raw = get_docs_path_By_docs_id($banner_id);
                    $file_absolute_path = $file_path_raw[0]['file'];
                    $file_relative_path = str_replace("https://qrmiam.fr", ".", $file_absolute_path);
                    
                    // Delete BANNER from database
                    $delete_sql = "DELETE FROM `".$table_cn_user_doce."` WHERE `id`= $banner_id";
                    iQuery($delete_sql,$rs);

                    // Delete BANNER file
                    if (file_exists($file_relative_path)) {
                        unlink($file_relative_path);
                    } 

                } //  END foreach
            } // END if($list_user_banners)

            // DELETE PDF WHEN BECOME LITE
            if ($list_user_documents){
                foreach ($list_user_documents as $document){
                    $ext = ".pdf";
    
                    $document_id = $document['id'];
                    $file_path_raw = get_docs_path_By_docs_id($document_id);
                    $file_absolute_path = $file_path_raw[0]['file'];
                    $file_relative_path = str_replace("https://qrmiam.fr", ".", $file_absolute_path);
                    
                    $extTest = substr($file_relative_path, -4);
    
                    if($extTest === $ext){ // if PDF
                        // Delete PDF from database
                        $delete_sql = "DELETE FROM `".$table_cn_user_doce."` WHERE `id`= $document_id";
                        iQuery($delete_sql,$rs);
    
                        // Delete PDF file
                        if (file_exists($file_relative_path)) {
                            unlink($file_relative_path);
                        } 
                    } // END if PDF
    
                } // END foreach
            } // END if($list_user_documents)

        } // END if($is_lite)
    }
} // END delete_banners_lite()



/****************************************************************************************************
*									FUNCTION delete_pdf_if_is_lite()     						    *
*   Delete banners and PDF unused anymore by Lite or non-actif users if any exists                  *
*   when there profile are checked                                                                  *
****************************************************************************************************/

function delete_banner_and_pdf_if_is_lite($user_id){

    global $wpdb;
    $table_cn_user_doce = $wpdb->prefix . 'cn_user_doce';

    $membership_product_id = get_user_meta($user_id,'membership_product_id',true);
    if ($membership_product_id == 1105 || $membership_product_id == 236951) {
        $is_lite = 1;
    }

    if ($is_lite){

        // DELETE BANNER
        $list_user_banners = get_user_banner_By_Id($user_id);

        if ($list_user_banners){
            foreach ($list_user_banners as $banner){
                $banner_id = $banner['id'];
                $file_path_raw = get_docs_path_By_docs_id($banner_id);
                $file_absolute_path = $file_path_raw[0]['file'];
                $file_relative_path = str_replace("https://qrmiam.fr", ".", $file_absolute_path);
                
                // Delete BANNER from database
                $delete_sql = "DELETE FROM `".$table_cn_user_doce."` WHERE `id`= $banner_id";
                iQuery($delete_sql,$rs);

                // Delete BANNER file
                if (file_exists($file_relative_path)) {
                    unlink($file_relative_path);
                } 
            }
        } // END if($list_user_banners)

        // DELETE PDF 
        $list_user_documents = get_user_documents_By_Id($user_id);

        if ($list_user_documents){
            foreach ($list_user_documents as $document){
                $ext = ".pdf";

                $document_id = $document['id'];
                $file_path_raw = get_docs_path_By_docs_id($document_id);
                $file_absolute_path = $file_path_raw[0]['file'];
                $file_relative_path = str_replace("https://qrmiam.fr", ".", $file_absolute_path);
                
                $extTest = substr($file_relative_path, -4);

                if($extTest === $ext){ // if PDF

                    // Delete PDF from database
                    $delete_sql = "DELETE FROM `".$table_cn_user_doce."` WHERE `id`= $document_id";
                    iQuery($delete_sql,$rs);

                    // Delete PDF file
                    if (file_exists($file_relative_path)) {
                        unlink($file_relative_path);
                    } 
                } // END if PDF

            } // END foreach
        } // END if($list_user_documents)

    } // END if($is_lite)
} // END delete_pdf_if_is_lite()


/****************************************************************************************************
*								FUNCTION delete_all_docs_if_not_actif() 						    *
*   Delete all docs from non-active users if any exists                                             *
*   when there profile are cheked                                                                   *
****************************************************************************************************/

function delete_all_docs_if_not_actif(){

    global $wpdb;
    $table_cn_user_doce = $wpdb->prefix . 'cn_user_doce';
    $user_id = get_current_user_id();

    $membership_product_id = get_user_meta($user_id,'membership_product_id',true);
    if ($membership_product_id == 1000 || $membership_product_id == 2000) {
        $is_non_actif = 1;
    }

    if($user_id != 40){ // Doesn't work for admin profile in case of change of status while testing

        if ($is_non_actif){
            $list_user_docs = get_all_user_docs_By_Id($user_id);
            if ($list_user_docs){
                foreach ($list_user_docs as $doc){
                    $doc_id = $doc['id'];
                    $file_path_raw = get_docs_path_By_docs_id($doc_id);
                    $file_absolute_path = $file_path_raw[0]['file'];
                    $file_relative_path = str_replace("https://qrmiam.fr", ".", $file_absolute_path);
                    
                    $delete_sql = "DELETE FROM `".$table_cn_user_doce."` WHERE `id`= $doc_id";

                    if (file_exists($file_relative_path)) {
                        unlink($file_relative_path);
                        iQuery($delete_sql,$rs);
                    } 
                }
            }
        } // END if($is_lite || $is_non_actif)
    }
} // END delete_banners_if_not_premium()





/**************************************************************************************************
*									FUNCTION delete_banners_unused()        					  *
*   Delete banners unused anymore by Lite or Non-Actif users if any exists  					  *
**************************************************************************************************/
// ONLY FOR CLEANING FROM DEVELOPPER

function delete_banners_unused(){

    global $wpdb;
    $table_cn_user_doce = $wpdb->prefix . 'cn_user_doce';

    $get_all_banners_SQL = "SELECT * FROM `" . $table_cn_user_doce . "` WHERE `banner`='yes' ORDER BY `user_id` ASC";

    $cn_user_doce_banners = iWhileFetch($get_all_banners_SQL);
    // print_r($cn_user_doce_banners);

    foreach($cn_user_doce_banners as $banner){
        $user_id = $banner['user_id'];

        $membership_product_id = get_user_meta($user_id,'membership_product_id',true);
        if ($membership_product_id == 1105 || $membership_product_id == 236951) {
            $is_lite = 1;
        }

        if ($is_lite){
            $banner_id = $banner['id'];
            $file_path_raw = get_docs_path_By_docs_id($banner_id);
            $file_absolute_path = $file_path_raw[0]['file'];
            $file_relative_path = str_replace("https://qrmiam.fr", ".", $file_absolute_path);
            
            $delete_sql = "DELETE FROM `".$table_cn_user_doce."` WHERE `id`= $banner_id";

            if (file_exists($file_relative_path)) {
                unlink($file_relative_path);
                iQuery($delete_sql,$rs);
            } 
        } // END if ($is_lite)
    } // END foreach
} // END delete_banners_unused()


// Delete all documents unused by any user among the documents placed by mistake directly in the « uploads » folder
// ONLY FOR CLEANING FROM DEVELOPPER

function delete_docs_unused(){

    global $wpdb;
    $table_cn_user_doce = $wpdb->prefix . 'cn_user_doce';
    $base_directory = "./wp-content/uploads//";
    $list_file = scandir($base_directory);
    // print_r($list_file);
    $full_path = "https://qrmiam.fr/wp-content/uploads//";

    $val1 = ".png";
    $val2 = ".jpg";
    $val3 = "jpeg";
    $val4 = ".pdf";
    $nb = 4;
    

    foreach($list_file as $sub_list_file){
        $valTest = substr($sub_list_file, -4);
        echo $valTest."<br>";

        if($valTest === $val1 || $valTest === $val2 || $valTest === $val3 || $valTest === $val4){
            $path = $full_path.$sub_list_file;
            $file_relative_path = $base_directory.$sub_list_file;
            // echo $file_relative_path."<br>";
            echo $path."<br>";
            $get_doc_SQL = "SELECT * FROM `3VQ4Io6_cn_user_doce` WHERE `file`='$path';";
            $response = iWhileFetch($get_doc_SQL);
            print_r($response);
            if($response){
                echo "exist<br>";
            } else {
                echo "DONT exist<br>";
                // unlink($file_relative_path);
            }
        }
    } // END foreach

} // END delete_banners_unused()



function delete_docs_users_unused(){

    global $wpdb;
    $table_users = $wpdb->prefix . 'users';

    $get_all_user_login_SQL = "SELECT ID, user_login FROM `" . $table_users . "` ORDER BY `ID` ASC";
    $users_list = iWhileFetch($get_all_user_login_SQL);
    // print_r($users_list);
    foreach($users_list as $user){
        $user_login = $user['user_login'];
        $base_directory = "./wp-content/uploads/$user_login";
        $list_file = scandir($base_directory);
        echo "<br><br>$user_login<br>";
        // print_r($list_file);

        $full_path = "https://qrmiam.fr/wp-content/uploads/$user_login/";

        $val1 = ".png";
        $val2 = ".jpg";
        $val3 = "jpeg";
        $val4 = ".pdf";
        $val5 = "webp";
        $nb = 4;
        
    
        foreach($list_file as $sub_list_file){
            $valTest = substr($sub_list_file, -4);
            // echo $valTest."<br>";
    
            if($valTest === $val1 || $valTest === $val2 || $valTest === $val3 || $valTest === $val4){
                $path = $full_path.$sub_list_file;
                echo $path."<br>";
                $file_relative_path = $base_directory."/".$sub_list_file;

                $get_doc_SQL = "SELECT * FROM `3VQ4Io6_cn_user_doce` WHERE `file`='$path';";
                $response = iWhileFetch($get_doc_SQL);
                // print_r($response);
                if($response){
                    echo "exist<br>";
                } else {
                    echo "DONT exist<br>";
                    echo $file_relative_path."<br>";
                    // unlink($file_relative_path);
                }
            }

            if($valTest === $val5){
                // $count = strlen($sub_list_file);
                $sub_list_file_cut = substr($sub_list_file, 0, -5);
                $path = $full_path.$sub_list_file_cut;
                echo $path."<br>";
                $file_relative_path = $base_directory."/".$sub_list_file;

                $get_doc_SQL = "SELECT * FROM `3VQ4Io6_cn_user_doce` WHERE `file`='$path';";
                // $get_doc_SQL = "SELECT * FROM `3VQ4Io6_cn_user_doce` WHERE `file` LIKE '$path%';";
                $response = iWhileFetch($get_doc_SQL);
                // print_r($response);
                if($response){
                    echo "exist<br>";
                } else {
                    echo "DONT exist<br>";
                    echo $file_relative_path."<br>";
                    // unlink($file_relative_path);
                }
            }
        } // END foreach
    } // END foreach

} // END delete_banners_unused()