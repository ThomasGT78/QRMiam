<?php

/****************************************************************************************************
*										FUNCTION gen_qrcode()	/qrcode								*
****************************************************************************************************/

add_shortcode( 'gen-qrcode' , 'gen_qrcode' );

function gen_qrcode() {
	$site_url = 'https://qrmiam.fr';

    $user = wp_get_current_user();
	
	if (get_current_user_id()) {
    	$qrcode_url = $site_url."/Profile/?username=" . $user->user_login;
	?>
	<div class="qrBlock">
		<h5 style="text-align: center;">Voici votre QRCode personnel à télécharger</h5>

		<?php
		$html = do_shortcode( '[kaya_qrcode_dynamic ecclevel="L" align="alignnone"]' . $qrcode_url . '[/kaya_qrcode_dynamic]' );
		echo $html;
		?>

		<button type="button" name="btnDownloadQR" class="et_pb_button btn btn-validate" onclick="wpkqcg_qrcode_preview_download()">Télécharger</button>
		<button type="button" name="btnDownloadQR" class="et_pb_button btn btn-validate" onclick="go_to_my_page()" >Visualiser votre page</button>
	</div>

<script>
	function go_to_my_page(){
		window.open("<?php echo $qrcode_url ?>", "<?php echo $user->user_login ?>");
	}
	
	function wpkqcg_qrcode_preview_download() {
		var qrcode = document.getElementsByClassName('wpkqcg_qrcode');
		if (qrcode.length !== 0) {
			for (var i = 0; i < qrcode.length; ++i) {
				// get qrcode data
				var qrcodeB64Data	= qrcode[i].src.replace('data:image/png;base64,', '');
				var qrcodeBlob		= wpkqcg_qrcode_b64toBlob(qrcodeB64Data, 'image/png');
				var qrcodeFilename	= 'kaya-qr-code.png';
				
				// download qrcode image as a file
				if (window.navigator && window.navigator.msSaveOrOpenBlob) {
					window.navigator.msSaveOrOpenBlob(qrcodeBlob, qrcodeFilename); // for microsoft IE
				}
				else {
					// for other browsers
					var qrcodeBlobUrl = URL.createObjectURL(qrcodeBlob);
					var downloadLink = document.createElement('a');
					downloadLink.style.display = 'none';
					downloadLink.setAttribute('href', qrcodeBlobUrl);
					downloadLink.setAttribute('target', '_blank');
					downloadLink.setAttribute('rel', 'noopener noreferrer');
					downloadLink.setAttribute('download', qrcodeFilename);
					document.body.appendChild(downloadLink);
					downloadLink.click();
					document.body.removeChild(downloadLink);
					delete downloadLink;
				}
			}
		}
	}

	/**
	 * Convert a base64 data to Blob.
	 *
	 * @since 1.2.0
	 */
	function wpkqcg_qrcode_b64toBlob(b64Data, contentType, sliceSize)
	{
		contentType = contentType || '';
		sliceSize = sliceSize || 512;

		var byteCharacters = atob(b64Data);
		var byteArrays = [];

		for (var offset = 0; offset < byteCharacters.length; offset += sliceSize)
		{
			var slice = byteCharacters.slice(offset, offset + sliceSize);
			var byteNumbers = new Array(slice.length);
			for (var i = 0; i < slice.length; i++)
			{
				byteNumbers[i] = slice.charCodeAt(i);
			}
			var byteArray = new Uint8Array(byteNumbers);

			byteArrays.push(byteArray);
		}
		var blob = new Blob(byteArrays, {type: contentType});
		
		return blob;
	}
</script>


<style type="text/css" media="screen">

.btn {
    border: rgb(248, 242, 231) solid 2px;
    border-radius: 10px;
    margin: 15px;
    padding: 7px;
}
.btn-validate:hover {
	background-color: #6FD878;
}

.btn-validate {
    background-color: #25C533;
    color: white;
}

.qrBlock {
	text-align: center;
	margin-top: 20px;
}
</style>
	
	<?php
    } // if (get_current_user_id())
	else {
		echo '<h3 style="text-align: center;">Vous devez vous authentifier pour accéder à la page</h3>';
	}
} // function gen_qrcode()
?>

