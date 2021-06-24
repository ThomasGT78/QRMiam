<?php

// $site_url = 'https://qrmiam.fr';
// $site_url = 'http://localhost:8081';
// $site_url = array('site_url' => 'https://qrmiam.fr');
// set_query_var( 'site_url', $site_url );

// instanciation de la classe wpdb
 global $wpdb;

// Définit le décalage horaire par défaut de toutes les fonctions date/heure
date_default_timezone_set('Europe/Paris');


// get_template_part( 'modules/queries_initial' );


// get_template_part( 'modules/queries' );

get_template_part( 'modules/queries_basic' );
// iQuery()
// iMainQuery()
// iWhileFetch()
// iInsert()
// iUpdateArray()
// iUpdateArrayInt()
// iUpdateArrayMultiConds()


get_template_part( 'modules/queries_user_and_data' );
// get_users_data_forMonday_By_userLogin($user_login)
// get_usermeta_data_forMonday_by_ID($userID)
// get_userID_By_username()
// get_userID_and_username()
// get_user_display_name_By_Id()
// get_user_registered_By_Id()
// get_etablissement_color_By_Id()
// get_etablissement_color_By_username()
// get_user_banner_By_Id()
// get_user_documents_By_Id()
// get_docs_path_By_docs_id()
// insert_user_doc_By_Id()

get_template_part( 'modules/queries_stats_nbre_vue' );
// insert_stat_nbre_vue()
// get_nbre_vue_By_Id()
// get_stat_nbre_vue_By_user_id()
// get_stat_nbre_vue_By_user_id_today()
// get_stat_nbre_vue_By_user_id_And_Date()
// get_stat_nbre_vue_By_user_id_And_Date_hourly()
// get_stat_nbre_vue_By_user_id_now()


// page		https://qrmiam.fr/abonnement
get_template_part( 'modules/membership_product' );
// membership_product()


// page		https://qrmiam.fr/profile/?username=t.garot
// page		https://qrmiam.fr/profile/?username=test
get_template_part( 'modules/public_page' );
// cn_public_page()


// page		https://qrmiam.fr/qrcode/
get_template_part( 'modules/qr_code' );
// gen_qrcode()


// page		https://qrmiam.fr/upload/
get_template_part( 'modules/upload_document' );
// upload_document()


get_template_part( 'modules/admin_menu' );
// cn_menu()
// cn_menu_content()


get_template_part( 'modules/user_register' );
// create_user_directory()
// set_membership_and_date()


get_template_part( 'modules/head_model' );
// cn_model()


get_template_part( 'modules/meta' );
// product_start_date_meta_box()
// product_start_date_meta_box_content()
// product_start_date_meta_box_save()
// my_custom_checkout_field_update_order_meta()


get_template_part( 'modules/remove_user' );
// custom_remove_user()
// delete_directory()


get_template_part( 'modules/statistics_advanced' );
// get_vue_hourly()
// stats_page()


get_template_part( 'modules/public_banner' );
// user_banner()


get_template_part( 'modules/user_id_column' );
// pippin_add_user_id_column()
// pippin_show_user_id_column_content()


get_template_part( 'modules/user_profile_fields' );
// cn_custom_user_profile_fields()
// wk_save_custom_user_profile_fields()


get_template_part( 'modules/pop_up' );


get_template_part( 'modules/delete_documents' );


                /********************
                *    MONDAY SYNC    *
                ********************/

get_template_part( 'modules/upload_monday_functions' );
// upload_profil_user()
// upload_user_registered()

get_template_part( 'modules/Monday_queries/monday_functions' );

get_template_part( 'modules/Monday_queries/monday_test_functions' );

// get_template_part( 'modules/function-mon-compte' );
