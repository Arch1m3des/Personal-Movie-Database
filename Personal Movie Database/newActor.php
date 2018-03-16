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
	<?php
		if(isset($_POST["vorname"])) $vorname = $_POST["vorname"];
		if(isset($_POST["nachname"])) $nachname = $_POST["nachname"];
		if(isset($_POST["beschreibung"])) $beschreibung = $_POST["beschreibung"];
		if(isset($_POST["gebdat"])) $gebdat = $_POST["gebdat"];
		if(isset($_POST["gebort"])) $gebort = $_POST["gebort"];
	?>
	<?php
		if(isset($vorname) and isset($nachname)) {

			$sql = "INSERT INTO PERSON(first_name, last_name, birthdate) VALUES (q'{" . $vorname . "}', q'{" . $nachname . "}', TO_DATE(q'{" . $gebdat . "}', 'YYYY-MM-DD'))";

			// execute sql statement
			$stmt = oci_parse($conn, $sql);
			if(oci_execute($stmt)) {

				if(isset($gebdat) AND $gebdat != "")
					$sql = "SELECT p_id FROM PERSON WHERE first_name LIKE q'{" . $vorname . "}' AND last_name LIKE q'{" . $nachname . "}' AND birthdate = TO_DATE(q'{" . $gebdat . "}', 'YYYY-MM-DD')";
				else
					$sql = "SELECT p_id FROM PERSON WHERE first_name LIKE q'{" . $vorname . "}' AND last_name LIKE q'{" . $nachname . "}'";

				// execute sql statement
				$stmt = oci_parse($conn, $sql);
				oci_execute($stmt);
				$row = oci_fetch_assoc($stmt);
				$p_id = $row['P_ID'];

				$sql = "INSERT INTO ACTOR(p_id, birthplace, description) VALUES (q'{" . $p_id . "}', q'{" . $gebort . "}', q'{" . $beschreibung . "}')";

				// execute sql statement
				$stmt = oci_parse($conn, $sql);
				if(oci_execute($stmt)) {
					oci_free_statement($stmt);

					//redirect to actor-page
					header("Location: detail_actor.php?actor=" . $p_id . "&new=yes");
					die();
				}
			}
		}
	?>
	<div class="container" style="height: 100%; border-radius: 0 0 10px 10px;">
		<?php
			if(isset($_GET["new"])) echo '
				<div class="row col-sm-offset-2 col-sm-9">
					<div class="alert alert-warning">
						<span class="glyphicon glyphicon glyphicon-warning-sign"></span>
						Dieser Schauspieler existiert bereits <a href=\'detail_actor.php?actor=' . $_GET["actor"] . '\'>hier</a>
					</div>
				</div>';
		?>
		<form action="newActor.php" method="POST" class="form-horizontal">

			<div class="form-group">
				<label for="inputFirstName" class="col-sm-2 control-label">Vorname</label>
				<div class="col-sm-9">
					<input type="text" name="vorname" class="form-control" id="inputFirstName" placeholder="Vorname..." value="<?php if(isset($vorname)) echo $vorname;?>" required>
				</div>
			</div>

			<div class="form-group">
				<label for="inputLastName" class="col-sm-2 control-label">Nachname</label>
				<div class="col-sm-9">
					<input type="text" name="nachname" class="form-control" id="inputLastName" placeholder="Nachname..." value="<?php if(isset($nachname)) echo $nachname;?>" required>
				</div>
			</div>

			<div class="form-group">
				<label for="inputBirthdate" class="col-sm-2 control-label">Geboren am</label>
				<div class="col-sm-4">
					<input type="date" name="gebdat" class="form-control" id="inputBirthdate" placeholder="tt.mm.jjjj" value="<?php if(isset($gebdat)) echo $gebdat;?>">
				</div>
				<label for="inputBirthplace" class="col-sm-1 control-label">in</label>
				<div class="col-sm-4">
					<input type="text" name="gebort" class="form-control" id="inputBirthplace" placeholder="Geburtsort..." value="<?php if(isset($gebort)) echo $gebort;?>">
				</div>
			</div>

			<div class="form-group">
				<label for="inputDesc" class="col-sm-2 control-label">Beschreibung</label>
				<div class="col-sm-9">
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
				<div class="col-sm-offset-2 col-sm-2">
					<button type="submit" id="btn-responsive" class="btn btn-primary">Eintragen</button>
				</div>
			</div>
		</form>

	</div>
</body>
</html>