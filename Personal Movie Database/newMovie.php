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
	<?php
		if(isset($_POST["titel"])) $titel = $_POST["titel"];
		if(isset($_POST["jahr"])) $jahr = $_POST["jahr"];
		if(isset($_POST["beschreibung"])) $beschreibung = $_POST["beschreibung"];
	?>
	<?php
		if(isset($titel) and isset($jahr)) {
			$sql = "INSERT INTO MOVIE(title, year, description) VALUES (q'{" . $titel . "}', q'{" . $jahr . "}', q'{" . $beschreibung . "}')";

			// execute sql statement
			$stmt = oci_parse($conn, $sql);

			//if INSERT successful
			if(oci_execute($stmt)) $success = 1;

			$sql = "SELECT * FROM movie WHERE title LIKE q'{" . $titel . "}'";

			// execute sql statement
			$stmt = oci_parse($conn, $sql);
			oci_execute($stmt);
			$row = oci_fetch_assoc($stmt);
			oci_free_statement($stmt);

			if($success == 1) {
				//redirect to movie-page
				header("Location: detail.php?movie=" . $row['M_ID'] . "&new=yes");
				die();
			} else {
				//redirect to new-movie-page
				header("Location: newMovie.php?new=no&movie=" . $row['M_ID']);
				die();
			}
		}
 	?>
	<div class="container" style="height: 100%; border-radius: 0 0 10px 10px;">
		<?php
			if(isset($_GET["new"])) echo '
				<div class="row col-sm-offset-2 col-sm-9">
					<div class="alert alert-warning">
						<span class="glyphicon glyphicon glyphicon-warning-sign"></span>
						Dieser Film existiert bereits <a href=\'detail.php?movie=' . $_GET["movie"] . '\'>hier</a>
					</div>
				</div>';
		?>
		<form action="newMovie.php" method="POST" class="form-horizontal">

			<div class="form-group">
				<label for="inputTitle" class="col-xs-2 control-label">Titel</label>
				<div class="col-xs-10 col-sm-9">
					<input type="text" name="titel" class="form-control" id="inputTitle" placeholder="Filmtitel..." value="<?php if(isset($titel)) echo $titel;?>" required>
				</div>
			</div>

			<div class="form-group">
				<label for="inputYear" class="col-xs-2 control-label">Jahr</label>
				<div class="col-xs-10 col-sm-9">
					<input type="number" min="1850" max="<?php echo date("Y")+10;?>" name="jahr" class="form-control" id="inputYear" placeholder="Jahr..." value="<?php if(isset($jahr)) echo $jahr;?>" required>
				</div>
			</div>
			
			<div class="form-group">
				<label for="inputDesc" class="col-xs-12 col-sm-2 control-label">Beschreibung</label>
				<div class="col-xs-12 col-sm-9">
					<textarea
						name="beschreibung"
						style="resize: vertical"
						class="form-control"
						id="inputDesc"
						rows="3"
						maxlength="500"
						placeholder="Beschreibungstext..."><?php if(isset($beschreibung)) echo $beschreibung; ?></textarea>
				</div>
			</div>

			<div class="form-group">
				<div class="col-xs-offset-2 col-xs-10">
					<button type="submit" class="btn btn-primary">Eintragen</button>
				</div>
			</div>
		</form>

	</div>
</body>
</html>
