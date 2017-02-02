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
	<h2>Create Content</h2>
	
	<form action="create_content.php" method="post">
		<p>Menu name:
			<input type="text" name="menu_name" value="" />
		</p>
		<p>Position:
			<select name="position">
			<?php
				// call function find_all_contents (SQL query for selction all contents)
				$content_set = find_all_contents();
				// calculate number of row in dababase
				$content_count = mysqli_num_rows($content_set);
				for ($count=1; $count <= ($content_count + 1); $count++){
					echo "<option value=\"{$count}\">{$count}</option>";
				}
			?>
			</select>
		</p>
		<P>Visible:
			<input type="radio" name="visible" value="0" /> No
			<input type="radio" name="visible" value="1" /> Yes
		</p>
		<input type="submit" name="submit" value="Create Content" />
	</form>
	<br />
	<a href="manage_content.php">Cancel</a>
  </div>
</div>

<?php include("../includes/layouts/footer.php"); ?> 