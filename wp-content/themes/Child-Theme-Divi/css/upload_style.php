<?php


add_shortcode( 'css_upload_style' , 'upload_style' );

function upload_style() { ?>

    <style type="text/css" media="screen">

        .div-etabl-name {
            color: red;
        }
        label {
            color: red;
        }
    </style>

<?php } ?>