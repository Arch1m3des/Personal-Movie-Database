<?php

$user = 'a1400660';
$pass = 'qwer1234';
$database = 'lab';

  // establish database connection
$conn = oci_connect($user, $pass, $database);
if (!$conn) exit;

?>

<!DOCTYPE html>
<html>
<head>

	<title>Movies - MovieSequel</title>

</head>
<body>
	<?php
		include 'include/header.php';

		// check if search view of list view
		if (isset($_GET['search']) AND $_GET['search']!= "") {
			$keyword = $_GET['search'];
			$sql = "SELECT * FROM MOVIE WHERE UPPER(TITLE) LIKE UPPER(q'{%" . $keyword . "%}') ORDER BY TITLE";
		} else {
			$sql = "SELECT * FROM movie SAMPLE(10) WHERE rownum <= 10";
		}

		// execute sql statement
		$stmt = oci_parse($conn, $sql);
		oci_execute($stmt);
	?>
	<div class="container">
		<div class="row" style="padding-bottom: 15px">
			<div class="col-xs-12 col-sm-6">
				<a href="newMovie.php">
					<button class="btn btn-default" id="btn-responsive" type="button">Neuen Film eintragen</button>
				</a>
			</div>
			<div class="col-xs-12 col-sm-6">
				<form class="form-inline" method='get'>
					<div class="form-group" id="responsive-pull">
						<input type="text" class="form-control" id="btn-responsive" name="search" placeholder="Suchbegriff..." value="<?php if(isset($keyword))echo $keyword; ?>">
						<button type="submit" class="btn btn-default" id="btn-responsive">Los</button>
					</div>
				</form>
			</div>
		</div>
		<table class="table table-hover table-responsive">
		  <thead>
		    <tr>
		      <th>Titel</th>
		      <th>Jahr</th>
		      <th>Beschreibung</th>
		    </tr>
		  </thead>
		  <tbody>
		  	<?php
			  // fetch rows of the executed sql query
			  while ($row = oci_fetch_assoc($stmt)) {
			    echo "<tr>";
			    echo "<td>" . $row['TITLE'] . "</td>";
			    echo "<td>" . $row['YEAR'] . "</td>";
			    echo "<td>" . $row['DESCRIPTION'] . "</td>";
			    echo '<td>
			    		<form action="detail.php" method="get">
				    		<input type="hidden" name="movie" value="' . $row['M_ID'] . '">
				    		<button type="submit" class="btn btn-primary">
				    			Mehr Info
				    		</button>
				    	</form>
				      </td>';
			    echo "</tr>";
			  }
			?>
		  </tbody>
		</table>
		<div class="row">
			<div class="col-xs-12">
				<?php
					//output number of rows
					if (isset($keyword)) {
						echo "<h2>" . ocirowcount($stmt) . " Filme gefunden!</h2>";
					} else {
						echo "<h2>" . ocirowcount($stmt) . " zufällige Filme!</h2>";
					}
				?>
			</div>
		</div>
		<?php
			oci_free_statement($stmt);
		?>
	</div>
</body>
</html>