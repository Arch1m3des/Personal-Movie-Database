<?php
	$user = 'a1400660';
	$pass = 'qwer1234';
	$database = 'lab';

	// establish database connection
	$conn = oci_connect($user, $pass, $database);
	if (!$conn) exit;

	$actor = $_GET["actor"];
	$movie = $_GET["movie"];

	$sql = "INSERT INTO ACTS(p_id, m_id) VALUES (q'{" . $actor . "}', q'{" . $movie . "}')";

	// execute sql statement
	$stmt = oci_parse($conn, $sql);
	oci_execute($stmt);

	oci_free_statement($stmt);

	//redirect to movie-page
	header("Location: detail.php?movie=" . $movie);
	die();
?>