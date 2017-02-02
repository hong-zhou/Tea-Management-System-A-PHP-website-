<?php require_once("../includes/database_connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php include("../includes/layouts/header.php"); ?>

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

<div id="main"> 
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
		<br />
		<br />
		<a href="index.php">HOME</a>
  </div>
  
  <div id="page">
	<?php if ($selected_content_id) { ?>
	<h2>Manage Content</h2>
		<?php $current_content = find_content_by_id($selected_content_id) ?>
		Menu name: <?php echo $current_content["menu_name"]; ?><br />
		<a href="update_content.php?content=<?php echo $current_content["id"]; ?>">Update Content</a>
	<?php } elseif ($selected_tea_id) { ?>
	<h2>Manage Tea</h2>
	
		<?php $current_tea = find_tea_by_id($selected_tea_id) ?>
		Menu name: <?php echo $current_tea["menu_name"]; ?><br />
		Position:<?php echo $current_tea["position"]; ?><br />
		Visible:<?php echo $current_tea["visible"] == 1 ? 'yes' : 'no'; ?><br />
		<br />
		Content:<br />
		<div class="view_content">
			<?php echo htmlentities($current_tea["content"]); ?>
		</div>
		<br />
		<br />
		<br />
		
	<?php } else { ?>
		Please select a page.
	<?php } ?>
  </div>
</div>

<?php include("../includes/layouts/footer.php"); ?> 

