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
			$p_id = $_GET["actor"];
			$sql = 'SELECT * FROM ACTOR NATURAL JOIN PERSON WHERE P_ID = '. $p_id;
						 
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
								Der Schauspieler wurde erfolgreich hinzugefügt
							</div>
						</div>
					  </div>';
			}
		?>
		<div class="row">
			<div class="col-xs-12">
				<h1>
					<?php echo $row['FIRST_NAME'] . " " . $row['LAST_NAME']; ?>
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
				<p>
					<?php
						if(isset($row['BIRTHDATE']))
							$date = date_format(date_create($row['BIRTHDATE']), 'd.m.y');
						else
							$date = "---";
						echo "Geboren am " . $date . " in " . $row['BIRTHPLACE'];
					?>
				</p>
				<p>
					<?php echo $row['DESCRIPTION']; ?>
				</p>
				<hr>
				<p>
					<h3>Movies with <?php echo $row['FIRST_NAME'] . " " . $row['LAST_NAME']; ?></h3>
					<?php
						$sql = 'SELECT * FROM MOVIE NATURAL JOIN ACTS WHERE P_ID = '. $p_id;
									 
						// execute sql statement
						$stmt = oci_parse($conn, $sql);
						oci_execute($stmt);

						// fetch rows
						while ($row = oci_fetch_assoc($stmt)) {
							echo "<a href='detail.php?movie=" . $row['M_ID'] . "'>";
							echo $row['TITLE'] . "<br>";
							echo "</a>";
						};
					?>
				</p>
			</div>
		</div>

	</div>
</body>
</html>
