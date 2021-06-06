<?php

/**************************************************************************************************
*										FUNCTION cn_model()										  *
**************************************************************************************************/

add_action('wp_head', 'cn_model');

function cn_model(){
	?>
	<div class="cn_model" id="cn_model" style="display: none">
		<div class="cn_model_body">
			<div class="cn_card mb-4">
				<div class="cn_card-header">
					<i class="cn_close pull-right">X</i>
				</div>
				<div id="cn_model_body" class="cn_card-body" style="text-align: center;">
					<img class="cn_new_img" src="" alt="">
					<!-- <iframe class="cn_new_PDF" src=""></iframe> -->
					<object class="cn_new_PDF" data="" type="application/pdf"></object>
				</div>
			</div>
		</div>
	</div>
	<?php
}
