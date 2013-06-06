<?php
/**
 * Returns a list of assets from the database as JSON file
 */

# Includes
require_once("../inc/error.inc.php");
require_once("../inc/database.inc.php");
require_once("../inc/security.inc.php");

# Set arguments for error email
$err_user_name = "Herve";
$err_email = "herve.senot@groundtruth.com.au";

# Performs the query and returns XML or JSON
try {
	$format = 'json';
	$sql = sanitizeSQL("SELECT id,label FROM asset");
	$pgconn = pgConnection();

    /*** fetch into an PDOStatement object ***/
    $recordSet = $pgconn->prepare($sql);
    $recordSet->execute();

	if ($format == 'json') {
		require_once("../inc/json.pdo.inc.php");
		header("Content-Type: application/json");
		echo rs2json($recordSet);
	}
	elseif ($format == "text") {
		header("Content-Type: application/text");
		while($line = $recordSet->fetch(PDO::FETCH_ASSOC))
		{
			foreach ($line as $col_key => $col_val)
			{
				echo $col_val . "\n";
			}
		}
	}
	else {
		trigger_error("Caught Exception: format must be json or text.", E_USER_ERROR);
	}
}
catch (Exception $e) {
	trigger_error("Caught Exception: " . $e->getMessage(), E_USER_ERROR);
}

?>