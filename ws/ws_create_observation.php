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
	$p_option_radio = $_REQUEST['field_options_radios'];
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

    // Getting the observation number just attributed (somehow curr_val does not always work)
	$sql = "SELECT last_value from observation_gid_seq;";
	$sql = sanitizeSQL($sql);
	//echo $sql;
    $recordSet = $pgconn->prepare($sql);
    $recordSet->execute();

	while ($row  = $recordSet->fetch())
	{
		$obs_id = $row[0];
	}

	// Another block to insert the observation coverage
	$sql2 = "INSERT INTO observation_coverage(cell_id,observation_id) SELECT c.id,".$obs_id." FROM (SELECT id FROM cell WHERE id IN (".$p_cells_id.")) c;";
	$sql2 = sanitizeSQL($sql2);
	//echo $sql2;
    $recordSet2 = $pgconn->prepare($sql2);
    $recordSet2->execute();

	header("Content-Type: application/json");
	$uploaded_img_webpath = "NONE";

    // Now processing the uploaded file
    if (!empty($_FILES['field_file']['name']))
    {
		if ($_FILES['field_file']['error'] > 0)
	    {
		    exit('{"success":"false","error":{"code":"'.$_FILES["file"]["error"].'"}');
	    }
	    else
	    {
			// Moving the file to the uploads directory
			$upload_path = "../uploads/";
			$ftyp = $_FILES["field_file"]["type"];
			if ($ftyp == "image/gif")
			{$fext = "gif";}
			elseif (($ftyp == "image/jpeg") || ($ftyp == "image/jpg")|| ($ftyp == "image/pjpeg"))
			{$fext = "jpg";}
			elseif (($ftyp == "image/x-png") || ($ftyp == "image/png"))
			{$fext = "png";}
			else
			{exit('{"success":"false","error":{"code":"U","message":"Unknown mime type:'.$ftyp.'"}');}

			// Building the filename out of the observation number and the mime type
			$target_path = $upload_path . $obs_id .".". $fext;

			if(move_uploaded_file($_FILES['field_file']['tmp_name'], $target_path)) {
				// Image web path:
				$uploaded_img_webpath = "http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/".$target_path;
				// Inserting the observation
				$sql = "UPDATE observation SET photo='".$uploaded_img_webpath."' WHERE id=".$obs_id;
				$sql = sanitizeSQL($sql);
				//echo $sql;
			    $recordSet = $pgconn->prepare($sql);
			    $recordSet->execute();
		    } else {
				exit('{"success":"false","error":{"code":"U","message":"The file upload to '.$target_path.' did not complete succeessfully"}}');
			}
	    }
	}
	exit('{"success":"true","observation_id":"'.$obs_id.'","uploaded_img":"'.$uploaded_img_webpath.'"}');
}
catch (Exception $e) {
	trigger_error("Caught Exception: " . $e->getMessage(), E_USER_ERROR);
}

?>