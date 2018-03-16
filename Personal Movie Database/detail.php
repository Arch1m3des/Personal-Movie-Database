<?php
$user = 'a1400660';
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
		<?php
			$m_id = $_GET["movie"];
			$sql = "SELECT * FROM movie WHERE M_ID = q'{" . $m_id . "}'";
						 
			// execute sql statement
			$stmt = oci_parse($conn, $sql);
			oci_execute($stmt);

			// fetch row
			$row = oci_fetch_assoc($stmt)
		?>
		<?php
			if(isset($_GET["new"])) {
				echo '<div class="row">
						<div class="col-xs-12">
							<div class="alert alert-success">
								<span class="glyphicon glyphicon glyphicon-ok"></span>
								Der Film wurde erfolgreich hinzugefügt
							</div>
						</div>
					  </div>';
			}
		?>
		<div class="row">
			<div class="col-xs-12">
				<h1>
					<?php echo $row['TITLE']; ?>
					<small>
						(<?php echo $row['YEAR']; ?>)
					</small>
				</h1>
				<hr>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-4 col-xs-offset-1">
				<img class="img-responsive img-thumbnail" src="img/popcorn-rotated.jpg">
			</div>	
			<div class="col-xs-6">
				<h3>Beschreibung</h3>
				<?php
					echo "<p>" . $row['DESCRIPTION'] . "</p>";
					echo "<p><a href='http://www.imdb.com/find?q=" . $row['TITLE'] . "'>More Info at the IMDb</a></p>";
				?>
				<hr>
				<h3>Schauspieler</h3>
				<?php
					$sql = 'SELECT * FROM ACTS NATURAL JOIN PERSON WHERE M_ID = '. $m_id;
					
					// execute sql statement
					$stmt = oci_parse($conn, $sql);
					oci_execute($stmt);

					// fetch row
					while($row = oci_fetch_assoc($stmt)){
						/*echo "<form action='detail_actor.php' method='get'>
								<input type='hidden' name='actor' value='" . $row['P_ID'] . "'>
								<button class='btn btn-link' type='submit'>" . $row['FIRST_NAME'] . " " . $row['LAST_NAME'] . "</button><br>
							</form>";*/
						echo "<a href='detail_actor.php?actor=" . $row['P_ID'] . "'>" . $row['FIRST_NAME'] . " " . $row['LAST_NAME'] . "</a><br>";
					}
				?>
				<a href='allActors.php?addTo=<?php echo $m_id; ?>'>
					<span class="glyphicon glyphicon-plus"></span>
				</a>
			</div>
		</div>	

	</div>
</body>
</html>
