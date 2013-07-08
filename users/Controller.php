<?php
function __autoload($className){
	include_once("models/$className.php");	
}

$users=new User();

if(!isset($_POST['action'])) {
	print json_encode(0);
	return;
}

// Almost spoiled my day 11th May 2013.
$POST_user='';
if (isset($_POST['user']))
{
  $POST_user=$_POST['user'];
}
if(get_magic_quotes_gpc()){
    $userParams = stripslashes($POST_user);
} else {
    $userParams = $POST_user;
}

$POST_action='';
if (isset($_POST['action']))
{
  $POST_action = $_POST['action'];
}
switch($POST_action) {
	case 'get_users':
		print $users->getUsers();
	break;
	
	case 'add_user':
		$user = new stdClass;
		$user = json_decode($userParams );
		print $users->add($user);		
	break;
	
	case 'delete_user':
		$user = new stdClass;
		$user = json_decode($userParams );
		print $users->delete($user);		
	break;
	
	case 'update_field_data':
		$user = new stdClass;
		$user = json_decode($userParams );
		print $users->updateValue($user);				
	break;
}

exit();
