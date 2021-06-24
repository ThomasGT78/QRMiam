<?php

// stats_page() - shortcode
// show_stats_view()

/****************************************************************************************************
*										FUNCTION basicStats()										*
****************************************************************************************************/

add_shortcode('user_basic_statistics', 'basicStats');

function basicStats() {
	$user_id = get_current_user_id();

	// Profil admin
	if(in_array($user_id, array(1, 40, 588, 589))){
		$admin = true;
	}
	if ($admin){
		$stat_nbre_vue_all_this_hour = get_stat_nbre_vue_all_users_this_hour();
		$stat_nbre_vue_all_today = get_stat_nbre_vue_all_users_today();
		
		$vue_all_this_hour = $stat_nbre_vue_all_this_hour[0]['somme'];
		$vue_all_today = $stat_nbre_vue_all_today[0]['somme'];
		?>
		<div class="basic-stats">
			<p>
				Les cartes et menus ont été vues 
				<strong><?= ($vue_all_this_hour == 0)? 0 : $vue_all_this_hour ?></strong> fois cette heure et 
				<strong><?= ($vue_all_today == 0)? 0 : $vue_all_today ?></strong> fois au aujourd'hui.
			</p>
		</div>
		<?php
	}
    $stat_nbre_vue = get_stat_nbre_vue_By_user_id($user_id);
    $stat_nbre_vue_today = get_stat_nbre_vue_By_user_id_today($user_id);

    $vue_today = $stat_nbre_vue_today[0]['somme'];
    $vue_total = $stat_nbre_vue[0]['somme'];

    ?>
    <div class="basic-stats">
        <p>
            Vos cartes et menus ont été vues 
            <strong><?= ($vue_today == 0)? 0 : $vue_today ?></strong> fois aujourd'hui et 
            <strong><?= ($vue_total == 0)? 0 : $vue_total ?></strong> fois au total.
        </p>
    </div>
    
    <style type="text/css" media="screen">
        .basic-stats{
            margin-bottom: 30px;
        }
    </style>
<?php 
}


/****************************************************************************************************
*										FUNCTION stats_page()										*
****************************************************************************************************/

// add_shortcode( 'statistiques' , 'advancedStats' );
add_shortcode( 'advanced_statistics' , 'advancedStats' );

function advancedStats() {
	?>
	<script
		src="https://code.jquery.com/jquery-3.6.0.min.js"
		integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
		crossorigin="anonymous">
	</script>
	<?php
	$user_id = get_current_user_id();
	
	// Profil admin
	if(in_array($user_id, array(1, 40, 588, 589))){
		$admin = true;
	}

	$membership_product_id = get_user_meta($user_id,'membership_product_id',true);
	
	if ($membership_product_id == 1107 || $membership_product_id == 236950 || $membership_product_id == 238608) {
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

	$Today_Object = new DateTime();
	$today = date("Y-m-d");
	$frenchToday = date("d/m/Y");
	$actualDay = date("d");
	$actualMonth = date("m");
	$actualYear = date("Y");

	$firstDayMonth = $actualYear . "-" . $actualMonth . "-01"; 
	

	function displayDate ($postedDate) {
		list($year, $month, $day) = explode('-', $postedDate);
		return $displayDate = $day . "/" . $month . "/" . $year;
	}

			/********************
			*	 FAST BUTTON	*
			********************/

	if (isset($_POST['btn_from_start'])){
		$postedBtnFromStart = $_POST['btn_from_start'];
		
		// Make today the End Date
		$postedEndDate = $today;

		// Get Registered Date of the user and make it the Start Date
		$user_registered = get_user_registered_By_Id($user_id);
		$user_registered_date_and_hour = new DateTime($user_registered[0]['user_registered']);
		$postedStartDate = $user_registered_date_and_hour->format('Y-m-d');
		
		// Display Date
		$startDate = displayDate ($postedStartDate);
		$endDate = $frenchToday;
	} // END $_POST['btn_from_start']

	
	if (isset($_POST['btn_last_30'])){
		$postedBtnLast30 = $_POST['btn_last_30'];

		// Make today the End Date
		$postedEndDate = $today;

		// Affect the date 1 month before today at the Start date
		$Month_Before_Object = $Today_Object->sub(new DateInterval('P1M'));
		$postedStartDate = $Month_Before_Object->format('Y-m-d');

		// Display Date
		$startDate = displayDate ($postedStartDate);
		$endDate = $frenchToday;
	} // END $_POST['btn_last_30']


	if (isset($_POST['btn_this_month'])){
		$postedBtnThisMonth = $_POST['btn_this_month'];

		// Make today the End Date
		$postedEndDate = $today;
		
		// Affect the 1st of the current month at the Start date
		$postedStartDate = $firstDayMonth;
		
		// Display Date
		$startDate = displayDate ($postedStartDate);
		$endDate = $frenchToday;
	} // END $_POST['btn_this_month']


	if (isset($_POST['endDate']) OR isset($postedBtnFromStart) OR isset($postedBtnThisMonth) OR isset($postedBtnLast30)){
		$period = true;
		$postedRadioPeriod = "period";
	} else {
		$period = false;
		$postedRadioPeriod = "specificDate";
	};

			/********************
			*	 SUBMIT DATE	*
			********************/

	if (isset($_POST['btn_submit_date'])){
		$canvas_type = $_POST['canvas_type'];

		if(isset($_POST['startDate'])) {
			$postedStartDate = $_POST['startDate'];
			$startDate = displayDate ($postedStartDate);
		}
		if(isset($_POST['endDate'])) {
			$postedEndDate = $_POST['endDate'];
			$endDate = displayDate ($postedEndDate);
		}
		if(isset($_POST['user_search_input'])){
			$username = $_POST['user_search_input'];
		}
		
	}  else {
		$canvas_type = "horloge";
	}

			/****************************************
			*	  HIDDEN INPUT (for Light Users)	*
			****************************************/
	if ($is_lite){
		if(isset($_POST['radio_choice'])) {
			$postedRadioPeriod = $_POST['radio_choice'];

			if ($postedRadioPeriod == 'specificDate') {
				$postedStartDate = $today;
				$startDate = displayDate ($postedStartDate);
			} else if ($postedRadioPeriod == 'period') {
				$postedStartDate = $firstDayMonth;
				$startDate = displayDate ($postedStartDate);

				$postedEndDate = $today;
				$endDate = displayDate ($postedEndDate);
			}
		}
	}
	/****************************************************************
	* 						FORM STATISTIQUES						*
	****************************************************************/
	?>
	
	<div id="advanced_stats">
		<h3>Statistiques Avancées <?php echo ($is_lite)? '<a style="color:red" href="https://qrmiam.fr/abonnement/">(passez à l’abonnement Premium pour visualiser les stats)</a>': "" ;?></h3>
		
		<div id="advanced_stats_settings">

			<div class="block-button-flex">
				<form action="" method="POST" >
					<button type="submit" name="btn_from_start" id="btn_from_start" class="btn fastButton">Depuis le début</button>
				</form>
				<form action="" method="POST" >
					<button type="submit" name="btn_last_30" id="btn_last_30" class="btn fastButton"> Les 30 derniers jours</button>
				</form>
				<form action="" method="POST" >
					<button type="submit" name="btn_this_month" id="btn_this_month" class="btn fastButton">Mois en cours</button>
				</form>
			</div>

			<form action="" method="POST" id="statsForm">
				<div class="choice-block">
					<div class="choice-block-label"><label>Visualiser les statististiques par: </label></div>
					
					<!-- Input Radio -->
					<div class="radio-block">
						<input type="radio" name="dateOrPeriod" value="specificDate" id="specificDate" class="statPeriod" <?php echo ($postedRadioPeriod == "specificDate")? "checked":""?> />
						<label for="specificDate">Date Spécifique</label>
					
						<input type="radio" name="dateOrPeriod" value="period" id="period" class="statPeriod" <?php echo ($postedRadioPeriod == "period")? "checked":""?> /> 
						<label for="period">Période</label>
					</div>
				</div>

				<!-- Input Date -->
				<div id="error_date" style="color: red;"></div>

				<div id=div_canvas_type>
						<input type=hidden id=canvas_type name=canvas_type value="<?php echo $canvas_type; ?>"/>
				</div>
				<div id=div_radio_choice>
						<input type=hidden id=radio_choice name=radio_choice value="<?php echo $postedRadioPeriod; ?>"/>
				</div>
				<div class="input-date-block ">
					<div id="input-start-date">
						<label>Du </label>
						<input type="date" id="startDate" name="startDate"
						value="<?php if ($postedStartDate) { echo $postedStartDate; } else { echo $today; }?>" min="2020-05-31" max="<?php echo $today; ?>">
					</div>
					
					<div id="input-end-date" >
					<?php if ($postedRadioPeriod == "period") { ?>
						<label>Au </label>
						<input type="date" id="endDate" name="endDate"
						value="<?php if ($postedEndDate) { echo $postedEndDate; } else { echo $today; }?>" min="2020-05-31" max="<?php echo $today; ?>">
					<?php } ?>
					</div>
				</div>


				<!-- INPUT DATALIST -->
				<?php 
				// Choisir un user pour voir ces stats (if admin)
				if($admin){
					$users_array = get_userID_and_username();
					//print_r($users_array);
					?>
					<div class="user-search-block">
						<div class="user-search-label"><label for="user_search_input">Rechercher un utilisateur: </label></div>

						<div class="user-search">
							<input list="users" name="user_search_input" id="user_search_input" value="<?php echo $username ?>"/>
							<datalist id="users">
								<?php
								for ($i=0; $i < sizeof($users_array) ; $i++) { 
									$optionName = $users_array[$i]['display_name'];
									echo '<option value="'.$optionName.'">';
								}
								?>
							</datalist>
							<button type="button" name="btn_clear" id="btn_clear" class="btn-clear">Clear</button>
						</div>
					</div>
					<?php 
				} 
				?>
				<br>

				<button type="submit" name="btn_submit_date" id="btn_submit_date" class="et_pb_button ">Afficher les Stats</button>

			</form>
		</div>
		<!----------------------------
		--  		STYLE			--
		----------------------------->
		<style>
		#advanced_stats_settings {
			background-color: #DFECF4;
			padding: 5px 3% 20px 3%;
			border-radius: 3px;
			box-shadow: 0px 0px 6px 1px black;
			margin: 20px 0;
		}
		.block-button-flex {
			display: flex;
			flex-wrap: wrap;
			justify-content: space-around;
			margin: 15px 0 25px 0;
		}
		.btn {
			font-size: 15px;
			font-weight: 600;
			border: rgb(248, 242, 231) solid 2px;
			border-radius: 13px;
			margin: 0;
			padding: 10px;
			width: 190px;
			background-color: #2f688d;
			color: white;
		}
		.btn:hover, .btn-clear:hover{
			background-color: #6FABD3;
		}

		#btn_submit_date {
			margin-left: 0;
		}
		.blur {
			filter: blur(5px);
		}
		.choice-block {
			font-size: 1em;
			display: flex;
			flex-wrap: wrap;
			margin: 30px 0 10px 0;
		}
		.choice-block-label {
			margin-right: 5%;
			font-weight: 600;
			min-width: max-content;
		}
		.radio-block {
			min-width: max-content;
		}
		.radio-block input {
			vertical-align: text-top;
			margin: 2px 2px 0 0;
		}
		.radio-block label {
			margin-right: 7px;
		}

		.input-date-block {
			display: flex;
			flex-wrap: wrap;
			justify-content: space-around;
			margin: 10px 0 25px 0;
		}
		.input-date-block label {
			margin-right: 10px;
		}
		.input-date-block input {
			min-width: 160px;
			width: 80%;
			border-radius: 5px;
			padding: 3px 0px 3px 27px;
			font-weight: 600;
			text-align: center;
			margin: 2px 0;
		}
		#input-start-date, #input-end-date {
			margin: 0 4% 0 4%;
			min-width: max-content;
			width: 40%;
		}

		.user-search-block {
			display: flex;
			flex-wrap: wrap;
			margin: 25px 0;
		}
		.user-search-block label {
			vertical-align: text-top;
		}
		.user-search-label {
			margin-right: 5%;
			font-weight: 600;
		}
		.user-search {
			min-width: max-content;
			width: 40%;
		}
		#user_search_input {
			min-width: 155px;
			width: 75%;
			border-radius: 5px;
			padding: 3px 0;
			font-weight: 600;
			text-align: center;
			margin: 2px 0;
		}
		.btn-clear {
			border: rgb(248, 242, 231) solid 2px;
			border-radius: 10px;
			margin: 0 0 0 10px;
			padding: 5px;
			width: max-content;
			background-color: #2f688d;
			color: white;
		}
		</style>


		<?php
		/****************************************************************
		 * 							DISPLAY STATISTICS					*
		 * *************************************************************/
		// IF FORM SUBMITED
		if(isset($_POST['btn_submit_date']) OR $period OR isset($_POST['radio_choice'])) {
			
			/**    IF POSTED USERNAME (for research by admin only)    **/
			if(isset($username)){

				if($username == null){ // Empty User Field
					if($period){		// «Période» selected
						echo "<br><h4>Statistiques du <strong>" . $startDate . "</strong> au <strong>" . $endDate . "</strong> :</h4>";
					} else {	// «Date Spécifique» selected
						echo "<br><h4>Statistiques du <strong>" . $startDate . "</strong> :</h4>";
					}
				} else { //User field filled
					$user_id = get_userID_By_username($username);
					if($period){		// «Période» selected
						echo "<br><h4>Statistiques de <strong>" . $username . "</strong> du <strong>" . $startDate . "</strong> au <strong>" . $endDate . "</strong> :</h4>";
					} else {	// «Date Spécifique» selected
						echo "<br><h4>Statistiques de <strong>" . $username . "</strong> du <strong>" . $startDate . "</strong> :</h4>";
					}
				}
			} // END POSTED USER

			else { // (all users except admin)
				if($postedRadioPeriod == 'period'){		// «Période» selected
					echo "<br><h4>Statistiques du <strong>" . $startDate . "</strong> au <strong>" . $endDate . "</strong> :</h4>";
				} else {	// «Date Spécifique» selected
					echo "<br><h4>Statistiques du <strong>" . $startDate . "</strong> :</h4>";
				}
			}

			show_stats_view($user_id,$admin, $username, $postedStartDate, $postedEndDate, $canvas_type);

		} // END If form submited
		else { // If nothing posted (arriving on the page or actualizing)
			echo "<br><h4>Statistiques du <strong>$frenchToday</strong>:</h4>";
			show_stats_view($user_id,$admin, $username, $today, $postedEndDate, $canvas_type);
		}
		?>
	</div>
	


<script type="text/javascript">
/****************************************************************************************************
*									SRIPT JavaScript et jQuery										*
****************************************************************************************************/

	(function( $ ) {
		// 'use strict';

		var isChecked = $("input[name=dateOrPeriod]").val();
		var firstDayMonth = "<?php echo $firstDayMonth; ?>";
		
		var membership_product_id = <?= $membership_product_id ?>;
		var isPremium = <?= $is_premium ?>;
		var isLite = <?= $is_lite ?>;
		var isActive = <?= $is_active ?>;

		// INPUT END DATE TO CREATE for the period search
		const inputEndDate = `<label>Au </label>
			<input type="date" id="endDate" name="endDate"
			value="<?php if ($postedEndDate) { echo $postedEndDate; } else { echo $today; }?>"
			min="2020-05-31"max="<?php echo $today; ?>">`;
		

		// DISPLAY INPUT END DATE if « period » selected
		$('.statPeriod').click(function() {
			var value = this.value;
			
			if(value == "period") {
				// if the div is empty, create an element, else don't create
				if (!$('#endDate').length){ 
					$("#input-end-date").append(inputEndDate);
					// USER LIGHT
					if (isLite) {
						$("#endDate" ).prop("disabled", true);
					}
					$("#startDate").val(firstDayMonth);
				}
			} else if (value == "specificDate") {
				$("#input-end-date").empty();
			}
			// $(this).closest('form').submit();
			if (isLite) {
				$("#radio_choice").val(value);
				$(this).closest('form').submit();
			}
		})


		// FAST BUTTON
		$('.fastButton').click(function(event) {
			if (!$('#endDate').length){ 
				$("#input-end-date").append(inputEndDate);
				if (isLite) {
					$("#endDate" ).prop("disabled", true);
					console.log("#input-end-date").val();
				}
				$("#startDate").val(firstDayMonth);
			}
		})


		// BUTTON CLEAR value #utilisateur datalist
		$('#btn_clear').click(function() {
			var value = this.value;
			$("#user_search_input").val('');
		})
		

		// MANAGE DATE ERROR (if end date inferior to start date)
		$("#btn_submit_date").click(function( event ) {
			// IF LIGHT ACCOUNT, Don't submit but redirect
			if (isLite) {
				event.preventDefault();
				// console.log("ok: "+membership_product_id);
				document.location.href="https://qrmiam.fr/abonnement/";
				// $("form").append('<a style="color:red" href="https://qrmiam.fr/abonnement/">(passez à l’abonnement Premium pour visualiser les stats)</a>');
			}

			// CHECK if startDate SMALLER than endDate
			else if ($('#endDate').length){
				var startDate = $("#startDate").val();
				var endDate = $("#endDate").val();
				if(startDate >= endDate) {
					event.preventDefault();
					$("#error_date").html("<p>La date de fin doit être supérieur à la date de début.</p>");
				}
			} else {
				$(this).closest('form').submit();
			}
		});
		
		// GO TO SPECIFIC DATE
		// $('.xChart').click(function() {
		// 	var value = this.value;
		// 	console.log("ça marche: "+value);
		// })


		// SHOW ADVANCED STATS ONLY TO PREMIUM
		if (isLite) {
			$('canvas').addClass('blur');
			$("#startDate" ).prop("disabled", true);
			$("#endDate" ).prop("disabled", true);
			$("#btn_from_start" ).prop("disabled", true);
			$("#btn_last_30" ).prop("disabled", true);
			$("#btn_this_month" ).prop("disabled", true);
		}

	})( jQuery );
</script>

<?php
} // END function advancedStats()



/****************************************************************************************************
*																									*
*									GRAPHIQUES DE STATISTIQUES										*
*																									*
****************************************************************************************************/

/****************************************************************************************************
*									FUNCTION show_stats_view()										*
****************************************************************************************************/

function show_stats_view($user_id, $admin, $username, $start_date, $end_date, $canvas_type) {
	if($admin && $username == null){ // Show data for all users
		$stat_nbre_vue = get_stat_nbre_vue_all_users_per_date_hourly($start_date, $end_date);
		
	}
	else { // show data for current user (or user choosen by admin)
		if($username){
			$user_id = get_userID_By_username($username);
		}
		$stat_nbre_vue = get_stat_nbre_vue_By_user_id_And_Date_hourly($user_id,$start_date, $end_date);
		
	}
	
	if($stat_nbre_vue[0]['nbre_vue'] != 0) {

		for ($i=0; $i < sizeof($stat_nbre_vue); $i++) {

			if(!$stat_nbre_vue[$i]['nbre_vue'] OR !$stat_nbre_vue[$i]['heure']) {
				//echo "0";
			}

			if($end_date) {
				$date = $stat_nbre_vue[$i]['date'];
				list($year, $month, $day) = explode('-', $date);
				$formatedDate = $day . "/" . $month . "/" . $year;
				// $formatedDate = "<span class=\"xChart\">".$day . "/" . $month . "/" . $year."</span>";
				$x[$i] = $formatedDate;
				$y[$i] = $stat_nbre_vue[$i]['nbre_vue'];
			} else {
				$x[$i] = $stat_nbre_vue[$i]['heure']."h";
				$y[$i] = $stat_nbre_vue[$i]['nbre_vue'];
			}
			
		}
	?>

		<canvas id="chartDate"></canvas>
		<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>

		<!---------------------------------------
		-	Show graphic for specific date (by hour)	-
		---------------------------------------->
		<?php if (!$end_date) { ?> 
			<script>
				var ctx = document.getElementById("chartDate");
				var chartDate = new Chart(ctx, {
					type: 'line',
					data: {
						labels: <?php echo json_encode($x); ?>,
						datasets: [{
							label: 'Vues par date',
							data: <?php echo json_encode($y); ?>,
							borderColor: 'rgba(255, 40, 40, 1)',
							lineTension: 0.05,
							backgroundColor: 'rgba(0, 0, 0, 0.05)'
						}]
					}
				});
			</script>
		
		<!-----------------------------------------------
		-	Show graphic for period (by day)	-
		------------------------------------------------>
		<?php } else { ?> 
			<script>
				var ctxChartDate = document.getElementById("chartDate");
				var chartDate = new Chart(ctxChartDate, {
					// type: 'line',
					type: 'bar',
					data: {
						labels: <?php echo json_encode($x); ?>,
						datasets: [{
							label: 'Vues par période',
							data: <?php echo json_encode($y); ?>,
							lineTension: 0.05,
							backgroundColor: '#EB8273'
						}]
					},
					options: {
						scales: {
							yAxes: [{
								display: true,
								ticks: {
									beginAtZero: true,
									// min: 0,
									// precision: 0,
									// stepSize: 2,
								},
							}]
						}
					},
				});
			</script>
		<?php
			show_average_per_hour($user_id, $admin, $username, $start_date, $end_date, $canvas_type);
		}
	} // if($stat_nbre_vue[0]['nbre_vue'] != 0)
	else {
		echo "Aucune vue ce jour là.";
	}
} // function show_stats_view()




/****************************************************************************************************
*									FUNCTION show_average_per_hour()								*
****************************************************************************************************/

function show_average_per_hour($user_id, $admin, $username, $start_date, $end_date, $canvas_type) { ?>
	<br><h3>Moyennes des vues par heures sur la période:</h3>
	<div class="block-button-flex">
		<div class="div-btn-avg-view">
			<button type="button" name="btn_avg_horloge" id="btn_avg_horloge" class="btn btn-avg-view" value="horloge" >Vue Horloge</button>
		</div>
		<div class="div-btn-avg-view">
			<button type="button" name="btn_avg_line" id="btn_avg_line" class="btn btn-avg-view" value="line" >Vue Courbe</button>
		</div>
	</div>

	<?php
	if($admin && $username == null){ // Show data for all users
		$stat_nbre_vue = get_average_vues_all_users_per_date_hourly($start_date, $end_date);
		
	}
	else { // show data for current user (or user choosen by admin)
		if($username){
			$user_id = get_userID_By_username($username);
		}
		$stat_nbre_vue = get_average_vues_By_user_id_And_Date_hourly($user_id, $start_date, $end_date);
	
	}
	
	if($stat_nbre_vue[0]['avgVues'] != 0) {

		for ($i=0; $i < sizeof($stat_nbre_vue); $i++) {
			$x[$i] = $stat_nbre_vue[$i]['heure']."h";
			$y[$i] = $stat_nbre_vue[$i]['avgVues'];
		}
	?>
	<canvas id="chartAvgHorloge"></canvas>
	<canvas id="chartAvgLine"></canvas>

	<script>
		var canvas_type = "<?php echo $canvas_type ?>";
		if (canvas_type == "horloge") {
			$("#chartAvgLine").addClass('avg-hide');
			$("#chartAvgHorloge").removeClass('avg-hide');
		} else {
			$("#chartAvgHorloge").addClass('avg-hide');
			$("#chartAvgLine").removeClass('avg-hide');
		} 

		$('.btn-avg-view').click(function() {
			var canvas_type = this.value;
			$("#canvas_type").val(canvas_type)

			if (canvas_type == "horloge") {
				$("#chartAvgLine").addClass('avg-hide');
				$("#chartAvgHorloge").removeClass('avg-hide');
			} else if (canvas_type == "line") {
				$("#chartAvgHorloge").addClass('avg-hide');
				$("#chartAvgLine").removeClass('avg-hide');
			} 
		})
		// Average hour per period
		
		/********************
		*	 RADAR CHART	*
		********************/
		var ctxChartAvgHorloge = document.getElementById("chartAvgHorloge");
		var chartAvgHorloge = new Chart(ctxChartAvgHorloge, {
			type: 'radar',
			data: {
				labels: <?php echo json_encode($x); ?>,
				datasets: [{
					label: 'Moyenne des nombres de vue',
					data: <?php echo json_encode($y); ?>,
					fill: true,
					backgroundColor: 'rgba(54, 162, 235, 0.2)',
					borderColor: 'rgb(54, 162, 235)',
					pointBackgroundColor: 'rgb(54, 162, 235)',
					pointBorderColor: '#fff',
					pointHoverBackgroundColor: '#fff',
					pointHoverBorderColor: 'rgb(54, 162, 235)'
				}]
			},
			options: {
				elements: {
					line: {
						borderWidth: 3
					}
				},
				scale: {
					ticks: {
						beginAtZero: true,
						// min: 0,
						// precision: 0,
						// stepSize: 2,
					},
				}
			},
		});

		/********************
		*	  LINE CHART	*
		********************/
		var ctxChartAvgLine = document.getElementById("chartAvgLine");
		var chartAvgLine = new Chart(ctxChartAvgLine, {
			type: 'line',
			data: {
				labels: <?php echo json_encode($x); ?>,
				datasets: [{
					label: 'Vues par date',
					data: <?php echo json_encode($y); ?>,
					borderColor: 'rgba(255, 40, 40, 1)',
					lineTension: 0.05,
					backgroundColor: 'rgba(0, 0, 0, 0.05)'
				}]
			},
			options: {
				scales: {
					yAxes: [{
						display: true,
						ticks: {
							beginAtZero: true,
							// min: 0,
							// precision: 0,
							// stepSize: 2,
						},
					}]
				}
			},
		});

	</script>

	<style>
		#chartAvgHorloge {
			/* width: 80%; */
			height: 400px;
		}
		.avg-hide {
			display: none !important;
		}
		.div-btn-avg-view {
			min-width: 200px ;
			width: 40%;
		}
		.btn-avg-view {
			width: 100% !important;
		}
	</style>
<?php
	} // if($stat_nbre_vue[0]['nbre_vue'] != 0)
	else {
		echo "Aucune vue ce jour là.";
	}
} // function get_vue_period()
?>
