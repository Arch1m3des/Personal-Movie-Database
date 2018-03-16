<?php
$user = 'a1407626';
$pass = 'qwer1234';
$database = 'lab';

  // establish database connection
$conn = oci_connect($user, $pass, $database);
if (!$conn) exit;

	//Anzahl an Filmen
	$sql = "SELECT COUNT(*) FROM MOVIE";
	$stmt = oci_parse($conn, $sql);
	oci_execute($stmt);
	$movies = oci_fetch_row($stmt);

	//Anzahl an Schauspielern
	$sql = "SELECT COUNT(*) FROM ACTOR";
	$stmt = oci_parse($conn, $sql);
	oci_execute($stmt);
	$actors = oci_fetch_row($stmt);

	//Durchschn. Alter der Filme
	$sql = "SELECT
				CAST(
					AVG(
						CAST(
							EXTRACT(YEAR FROM SYSDATE) - MOVIE.YEAR
						AS DECIMAL(10,2))
					)
				AS DECIMAL(10,2))
			FROM MOVIE";
	$stmt = oci_parse($conn, $sql);
	oci_execute($stmt);
	$movieAge = oci_fetch_row($stmt);

	//Filme pro Jahr
	$sql = "SELECT MOVIE.YEAR, COUNT(MOVIE.YEAR) AS NUM FROM MOVIE GROUP BY MOVIE.YEAR ORDER BY YEAR";
	$stmt = oci_parse($conn, $sql);
	oci_execute($stmt);

?>

<!DOCTYPE html>
<head>

	<title>&Uuml;ber MovieSequel</title>
	
	<!--Load the AJAX API-->
    <script type="text/javascript" src="https://www.google.com/jsapi" charset="UTF-8"></script>
    <script type="text/javascript" charset="UTF-8">
	    var data;
	    var chart;

		// Load the Visualization API and the piechart package.
		google.load('visualization', '1', {'packages':['corechart']});

		// Set a callback to run when the Google Visualization API is loaded.
		google.setOnLoadCallback(onLoad);

		function onLoad() {
			// Create our data table.
			data = new google.visualization.DataTable();
			data.addColumn('string', 'Jahr');
			data.addColumn('number', 'Filme');
			data.addRows([
				<?php
				while($moviesPerYear = oci_fetch_row($stmt)) {
					echo "['" . $moviesPerYear["0"] . "', " . $moviesPerYear["1"] . "],";
				}
				?>
				]);
			drawChart();
		}

		function drawChart() {

			// Set chart options
			var options = {
				'title':'Anzahl Filme nach Jahr',
				'titleTextStyle': {
					'fontSize': 20
				},
				'width':'100%',
				'height':300,
				'animation': {
					'startup':true,
					'duration':1500,
					'easing': 'inAndOut'
				},
				'hAxis': {
					'textStyle': {
						'fontSize':10
					},
					'showTextEvery':10
				},
				'vAxis': {'viewWindowMode': 'maximized'},
				'curveType': 'function',
				'lineWidth':1,
				'pointSize': 3,
				'legend': { position: "none" },
				'chartArea':{left:'5%',top:'10%',width:'90%',height:'80%'},
				'backgroundColor':'#f9f9f9'
			};

			// Instantiate and draw our chart, passing in some options.
			function resize () {
				chart = new google.visualization.LineChart(document.getElementById('chart_div'));
				google.visualization.events.addListener(chart, 'select', selectHandler);
				chart.draw(data, options);
			}

			window.onload = resize();
			window.onresize = resize;

		}

		function selectHandler() {
			var selectedItem = chart.getSelection()[0];
			var value = data.getValue(selectedItem.row, 0);
			alert('The user selected ' + value);
		}

    </script>

</head>
<body>
	<?php include 'include/header.php';?>
	<div class="container" style="height: 100%; border-radius: 0 0 10px 10px;">

		<h1 style="font-weight: 300;">&Uuml;ber MovieSequel</h1>

		<div class="row" style="margin-top: 30px; margin-bottom: 20px;">
			<div class="col-xs-12 col-sm-4">
				<div class="alert alert-info" style="text-align: center">
					<strong>
						Anzahl Filme: <?php echo $movies["0"]; ?>
					</strong>
				</div>
			</div>
			<div class="col-xs-12 col-sm-4">
				<div class="alert alert-info" style="text-align: center">
					<strong>
						Anzahl Schauspieler: <?php echo $actors["0"]; ?>
					</strong>
				</div>
			</div>
			<div class="col-xs-12 col-sm-4">
				<div class="alert alert-info" style="text-align: center">
					<strong>
						&#8960; Alter der Filme: <?php echo $movieAge["0"]; ?>
					</strong>
				</div>
			</div>
		</div>
		
		<div class="row">
			<div class="col-xs-12">
				<div id="chart_div" style="padding: auto; width:100%; height:300"></div>
			</div>
		</div>

		<div class="row">
			<div class="col-xs-12">
				<div id="chart_div" style="padding: auto; width:100%; height:300"></div>
			</div>
		</div>

		<h2>Wieso, weshalb, warum?</h2>
		<p class="lead" style="font-weight: 350">MovieSequel entstand im Zuge einer Projektarbeit f&uuml;r das Fach Datenbanksysteme.</p>
		<p><strong>Die Aufgabenstellung lautete wie folgt:</strong></p>
		<p>Ziel des Projektes dieses Studiensemesters ist es, ein Datenbankkonzept (vergleichbar mit einem Pflichtenheft f&uuml;r Softwareprojekte) f&uuml;r eine vorgegebene Problemdom&auml;ne zu entwickeln, dieses Konzept mittels Data Definition Language (DDL) zu realisieren, unter Verwendung der Structured Query Language (SQL) abzufragen und mittels Java/PHP auf die fertige Datenbank zuzugreifen.</p>
		<h2>&Uuml;ber die Webseite</h2>
		<dl class="dl-horizontal">
			<dt>Autor</dt>
			<dt>Datum</dt>
			<dd>J&auml;nner 2016</dd>
		</dl>
	</div>
	<?php
		oci_free_statement($stmt);
	?>
</body>
</html>
