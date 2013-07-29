<?php
/**
 * Returns the occurence + observation history of a (group of) cell(s)
 */

session_start();

# Includes
require_once("inc/error.inc.php");
require_once("inc/database.inc.php");
require_once("inc/security.inc.php");

# Checking if the session has a logged-in user
if (!$_SESSION['logged-in'])
{
	trigger_error("Caught Exception: This web service requires authentication.", E_USER_ERROR);
	exit;
}

# Set arguments for error email
$err_user_name = "Herve";
$err_email = "herve.senot@groundtruth.com.au";

# Performs the query and returns XML or JSON
try {
	$format = 'json';
	$p_asset_id = $_REQUEST['asset'];
	$p_cells_id = $_REQUEST['selected_cells'];
	$p_cells_list = $p_cells_id;

	$sql = <<<ENDSQL
select cell_id,source_type,status,stakeholder,time_mark,comments,photo from
((select 
oc.cell_id,
cast('Observation' as text) as source_type,
(select rs.label from nz.r_status rs where rs.id=o.status_id) as status,
cast('User contribution' as text)||' (by '||(select name from nz."user" u where o.user_id=u.id)||')' as stakeholder,
to_char(o.ts, 'YYYY/MM/DD HH12:MI:SS') as time_mark,
coalesce(o.comments,'') as comments,
coalesce(o.photo,'') as photo,
cast(extract(epoch from o.ts) as integer) as ord
from nz.observation o,nz.observation_coverage oc
where o.id=oc.observation_id and o.asset_id=$p_asset_id and oc.cell_id in ($p_cells_list))
union all
(
select 
bo.cell_id,
'Baseline data',
(select rs.label from nz.r_status rs where rs.id=bo.status_id),
(select rs.label from nz.r_source rs where rs.id=bo.source_id),
(select ry.label from nz.r_year ry   where ry.id=bo.year_id),
'',
'',
0
from nz.baseline_occurence bo where bo.cell_id in ($p_cells_list) and bo.asset_id=$p_asset_id)) t
order by cell_id,ord desc
ENDSQL;

	//echo $sql;
	$pgconn = pgConnection();

    /*** fetch into an PDOStatement object ***/
    $recordSet = $pgconn->prepare($sql);
    $recordSet->execute();

	if ($format == 'json') {
		require_once("inc/json.pdo.inc.php");
		// Required to cater for IE
		header("Content-Type: text/html");
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