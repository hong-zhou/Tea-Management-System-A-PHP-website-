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
	if (!$current_content){
		// content ID is missing or invalid or content could not be found in database
		redirect_to("manage_content.php");
	}
?>

<!-- updata database -->
<?php
	if (isset($_POST["submit"])){
		// process the form
		$id = $current_content["id"];
		$escaped_string = mysqli_real_escape_string($connection, $_POST["menu_name"]);
		$menu_name = $escaped_string;
		$position = (int) $_POST["position"];
		$visible = (int) $_POST["visible"];
		
		// escapse all strings
		//$menu_name = mysqli_real_escape_string($connection, $menu_name);
		
		// perform database insertion
		$query  = "UPDATE contents SET ";
		$query .= "menu_name = '{$menu_name}', ";
		$query .= "position = '{$position}', ";
		$query .= "visible = '{$visible}' ";
		$query .= "WHERE id = '{$id}' ";
		$query .= "LIMIT 1";
		
		$result = mysqli_query($connection, $query);
		
		if ($result && mysqli_affected_rows($connection) == 1){
			// sucess
			redirect_to("manage_content.php");
		} else {
			// failure
			redirect_to("new_content.php");
		}
	} else {
		//redirect_to("new_content.php");
	}
?>

<div id="main"> 
  <!-- navigation panel -->
  <div id="leftbox">
	<ul class="contents">
		<?php
			//Perform database query from MySQL contents table
			$query  = "SELECT * ";
			$query .= "FROM contents ";
			$query .= "WHERE visible = 1 ";
			$query .= "ORDER BY position ASC";
			$content_set = mysqli_query($connection, $query);
			// Test if there was a query error
			if (!$content_set) {
				die("Database query failed.");
			}
		?>
		<?php
			// 3. Use returned data (if any)
			while($content = mysqli_fetch_assoc($content_set)) {
				// output data from each row
		?>
			<li>
				<!--- provide link to different contents record in the contents table -->
				<a href="manage_content.php?content=<?php echo urlencode($content["id"]); ?>">
					<?php echo $content["menu_name"]; ?>
				</a>
				<?php
					//Perform database query from MySQL teas table
					$query  = "SELECT * ";
					$query .= "FROM teas ";
					$query .= "WHERE visible = 1 ";
					$query .= "AND content_id = {$content["id"]} ";
					$query .= "ORDER BY position ASC";
					$tea_set = mysqli_query($connection, $query);
					// Test if there was a query error
					if (!$tea_set) {
						die("Database query failed.");
					}
				?>
				<ul class="teas">
					<?php
						while($teas = mysqli_fetch_assoc($tea_set)) {
					?>
						<li>
							<!--- provide link to different tea record in the teas table -->
							<a href="manage_content.php?teas=<?php echo urlencode($teas["id"]); ?>">
								<?php echo $teas["menu_name"]; ?> 
							</a>
						</li>
					<?php
						}
					?>
				</ul>
			</li>
		<?php
			}
		?>
		<?php
			//Release returned data
			mysqli_free_result($content_set);
		?>
		</ul>
		<br />
		<a href="new_content.php">+ Add new content</a>
  </div>
  
  <!-- display content page -->
  <div id="page">
	<h2>Update Content:<?php echo $current_content["menu_name"]; ?></h2>
	
	<form action="update_content.php?content=<?php echo $current_content["id"]; ?>" method="post">
		<p>Menu name:
			<input type="text" name="menu_name" value="<?php echo $current_content["menu_name"]; ?>" />
		</p>
		<p>Position:
			<select name="position">
			<?php
				$content_set = find_all_contents();
				$content_count = mysqli_num_rows($content_set);
				for ($count=1; $count <= $content_count; $count++){
					echo "<option value=\"{$count}\"";
					if ($current_content["position"] == $count){
						echo " selected";
					}
					echo ">{$count}</option>";
				}
			?>
			</select>
		</p>
		<P>Visible:
			<input type="radio" name="visible" value="0" 
				<?php if ($current_content["visible"] == 0) {
					echo "checked";
				} ?> 
			/> No
			<input type="radio" name="visible" value="1"
				<?php if ($current_content["visible"] == 1) {
					echo "checked";
				} ?> 
			/> Yes
		</p>
		<input type="submit" name="submit" value="Update Content" />
	</form>
	<br />
	<a href="manage_content.php">Cancel</a>
	&nbsp; &nbsp;
	<a href="delete_content.php?content=<?php $current_content["id"] ?>" >Delete content</a>
  </div>
</div>

<?php include("../includes/layouts/footer.php"); ?> 