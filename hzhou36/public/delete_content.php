<?php require_once("../includes/database_connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>

<?php
	if (isset($_GET["content"])){
		$selected_content_id = $_GET["content"];
		$selected_tea_id = null;
	} elseif (isset($_GET["teas"])) {
		$selected_tea_id = $_GET["teas"];
		$selected_content_id = null;
	} else {
		$selected_content_id = null;
		$selected_tea_id = null;
	}
?>

<?php $current_content = find_content_by_id($selected_content_id) ?>

<?php include("../includes/layouts/header.php"); ?>




<?php
	$current_content = find_content_by_id($selected_content_id);
	if (!$current_content){
		// content ID is missing or invalid or content could not be found in database
		redirect_to("manage_content.php");
	}
	
	$id = $current_content["id"];
	$query = "DELETE FROM contents WHERE id = {$id} LIMIT 1";
	$resutlt = mysqli_affected_rows($connection, $query);
	
	if ($result && mysqli_affected_rows($connection) == 1){
		// sucess
		redirect_to("manage_content.php");
	} else {
		// failure
		redirect_to("manage_content.php?content={$id}");
	}
?>