<?php
$user = 'a1407626';
$pass = 'qwer1234';
$database = 'lab';

  // establish database connection
$conn = oci_connect($user, $pass, $database);
if (!$conn) exit;
?>

<!DOCTYPE html>
<head>
	
	<title>MovieSequel</title>
	
</head>
<body>
	<?php include 'include/header.php';?>
	<div class="container" style="height: 100%; border-radius: 0 0 10px 10px;">
		<div class="row">
			<div class="col-xs-12">
				<div class="jumbotron">
				  <h1 style="font-weight: 300;">Hallo!</h1>
				  <p>Willkommen bei MovieSequel</p>
				  <p><a class="btn btn-primary btn-lg" href="allMovies.php" role="button">Filme entdecken</a></p>
				</div>
			</div>
		</div>
		<?php
			$sql = "SELECT * FROM movie SAMPLE(3) WHERE rownum <= 3";
			 
			// execute sql statement
			$stmt = oci_parse($conn, $sql);
			oci_execute($stmt);
		?>
		<div class="row">
			<?php
				  // fetch rows of the executed sql query
				  while ($row = oci_fetch_assoc($stmt)) {
				    echo '<div class="col-xs-12 col-sm-4">
							<div class="thumbnail clearfix">
								<img src="img/popcorn.jpg" alt="Bild von Popcorn">
								<div class="caption" style="min-height: 200px;
															max-height: 200px;
															overflow: hidden;
								">';
				    echo "<h3>" . $row['TITLE'] . " <small>(" . $row['YEAR'] . ")</small></h3>";
				    echo "<p>" . $row['DESCRIPTION'] . "</p>
				    			</div>
				    			<div class='caption'>";
				    echo '			<p>
				    					<form class="pull-right form-group" action="detail.php" method="get">
				    						<input type="hidden" name="movie" value="' . $row['M_ID'] . '">
				    						<button type="submit" class="btn btn-primary">
				    							Mehr Info
				    						</button>
				    					</form>
				    				</p>
								</div>
							</div>
						</div>';
				  }
				?>
		</div>

	</div>
	<?php
		oci_free_statement($stmt);
	?>
</body>
</html>
