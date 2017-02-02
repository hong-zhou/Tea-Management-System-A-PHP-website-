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
  </div>
  
  <div id="page">
	<?php if ($selected_content_id) { ?>
	<h2>Manage Content</h2>
		<?php $current_content = find_content_by_id($selected_content_id) ?>
		Menu name: <?php echo $current_content["menu_name"]; ?><br />
		
	<?php } elseif ($selected_tea_id) { ?>
	
			<?php echo htmlentities($current_tea["content"]); ?>
		</div>
		<br />
		<br />
		<br />
		
	<?php } else { ?>
		<h1>Welcome to Hong's Tea House.</h1>
		<img src="tea.jpg" alt="Green Tea View" style="width:304px;height:228px;">
	<?php } ?>
  </div>
</div>

<?php include("../includes/layouts/footer.php"); ?> 
