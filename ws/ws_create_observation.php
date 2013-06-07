<?php
/**
 * Process the capture form
 *
 */

# Includes
require_once("inc/error.inc.php");
require_once("inc/database.inc.php");
require_once("inc/security.inc.php");
require_once("inc/json.pdo.inc.php");

# Set arguments for error email
$err_user_name = "Herve";
$err_email = "hs.enot@gmail.com";

# Retrieve URL arguments
try {
	$p_asset_id = $_REQUEST['field_asset'];
	$p_cells_id = $_REQUEST['field_selected_cells'];
	$p_option_radio = $_REQUEST['field_optionsRadios'];
	$p_email_address = $_REQUEST['field_email_address'];
	$p_comment = $_REQUEST['field_comment'];

}
catch (Exception $e) {
    trigger_error("Caught Exception: " . $e->getMessage(), E_USER_ERROR);
}

# Performs the query and returns XML or JSON
try {
	// Opening up DB connection
	$pgconn = pgConnection();

	// Inserting the observation
	$sql = "INSERT INTO observation(asset_id, status_id, user_id,email_address,comments) VALUES (".$p_asset_id.",".$p_option_radio.",1,'".$p_email_address."','".$p_comment."');";
	$sql = sanitizeSQL($sql);
	//echo $sql;
    $recordSet = $pgconn->prepare($sql);
    $recordSet->execute();

	// Another block to insert the observation coverage
	$sql2 = "INSERT INTO observation_coverage(cell_id,observation_id) SELECT c.id,currval('observation_gid_seq') FROM (SELECT id FROM cell WHERE id IN (".$p_cells_id.")) c;";
	$sql2 = sanitizeSQL($sql2);
	//echo $sql2;
    $recordSet2 = $pgconn->prepare($sql2);
    $recordSet2->execute();

	header("Content-Type: application/json");
	echo fs2json($recordSet2);
}
catch (Exception $e) {
	trigger_error("Caught Exception: " . $e->getMessage(), E_USER_ERROR);
}

?>