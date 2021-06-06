<?php
/****************************************************************************************************
*								FUNCTION ORDER OPTION FOR Upload									*
****************************************************************************************************/

// Displays select's <option> to choose the documents's order 
function orderOption($documentsOrder, $document) {
    foreach ($documentsOrder as $key => $itemId) {
        $selectedOption = '<option value='.$key.' selected>'.($key+1).'</option>';
        $notSelectedOption = '<option value='.$key.'>'.($key+1).'</option>';
        // var_dump($documentsOrder[$key]);
        echo ($itemId == $document['id'])? $selectedOption : $notSelectedOption;
    } 
}

// $documentsOrder must be global with & to be used in orderOption()
function actualizeOrder(&$documentsOrder, $optionSelected, $document_id, $key_actual_doc_position) {
    array_splice($documentsOrder, $key_actual_doc_position, 1);
    $sliced = array_slice($documentsOrder, $optionSelected);
    array_splice($documentsOrder, $optionSelected);
    array_push($documentsOrder, $document_id);
    foreach ($sliced as $item){
        array_push($documentsOrder, $item );
    }

    global $wpdb;
    $table_user_doce = $wpdb->prefix . 'cn_user_doce';

    foreach ($documentsOrder as $key => $id) {
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
            ?> <div class="umsg"><h2>Ordre des documents modifié avec succés</h2></div> <?php	
        } else {
            ?> <div class="umsg"><h2>Il y a eu un problème, modification de l'ordre des documents non effectuée!</h2></div> <?php
        }
    } elseif (isset($_POST['btn_update_banner'])) {
        if ($response_ordering->success == 'success') {
            ?> <div class="umsg"><h2>Ordre des bannières modifié avec succés</h2></div> <?php	
        } else {
            ?> <div class="umsg"><h2>Il y a eu un problème, modification de l'ordre des bannières non effectuée!</h2></div> <?php
        }
    }
} // actualizeOrder
