<?php require_once("../includes/database_connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>

<!-- process form -->
<?php
	if (isset($_POST["submit"])){
		// process the form
		$menu_name = $_POST["menu_name"];
		$position = (int) $_POST["position"];
		$visible = (int) $_POST["visible"];
		
		// escapse all strings
		$menu_name = mysqli_real_escape_string($connection, $menu_name);
		
		// perform database insertion
		$query  = "INSERT INTO contents (";
		$query .= " menu_name, position, visible";
		$query .= ") VALUES (";
		$query .= " '{$menu_name}', '{$position}', '{$visible}'";
		$query .= ")";
		
		$result = mysqli_query($connection, $query);
		
		if ($result){
			// sucess
			redirect_to("manage_content.php");
		} else {
			// failure
			redirect_to("new_content.php");
		}
	} else {
		redirect_to("new_content.php");
	}
?>
<?php
  //Close database connection
  if (isset($connection)){
	  mysqli_close($connection);
  }
?>