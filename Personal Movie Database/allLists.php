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
	
	<title>MovieSequel</title>

</style>
</head>
<body>
	<div id="wrapper">
		<nav>NAVIGATION BAR GOES HERE</nav>
		<header>
			<hgroup>
				<h1>MovieSequel - all Lists</h1>
			</hgroup>
		</header>
		<section id="content">

			<?php
			  $sql = "SELECT L_ID, P_ID, Name, Private FROM list ORDER BY private";
			 
			  // execute sql statement
			  $stmt = oci_parse($conn, $sql);
			  oci_execute($stmt);
			?>

			<table border cellpadding='5px'>
			  <thead>
			    <tr>
			      <th>L_ID</th>
			      <th>P_ID</th>
			      <th>Name</th>
			      <th>Private?</th>
			    </tr>
			  </thead>
			  <tbody>
			  	<?php
				  // fetch rows of the executed sql query
				  while ($row = oci_fetch_assoc($stmt)) {
				    echo "<tr>";
				    echo "<td>" . $row['L_ID'] . "</td>";
				    echo "<td>" . $row['P_ID'] . "</td>";
				    echo "<td>" . $row['NAME'] . "</td>";
				    echo "<td>" . $row['PRIVATE'] . "</td>";
				    echo "</tr>";
				  }
				?>
			  </tbody>
			</table>
			<div>Insgesamt <?php echo oci_num_rows($stmt); ?> Listen gefunden!</div>

			<?php  oci_free_statement($stmt); ?>

		</section>
		<footer> COPYRIGHT AND STUFF GOES HERE </footer>
	</div>
</body>
</html>
