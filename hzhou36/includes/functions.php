<?php
	
	function redirect_to($new_page){
		header("Location: " . $new_page);
		exit;
	}
	
	function find_all_contents($visible=true) {
		global $connection;
		
		$query  = "SELECT * ";
		$query .= "FROM contents ";
		if ($visible) {
			$query .= "WHERE visible = 1 ";
		}
		$query .= "ORDER BY position ASC";
		$subject_set = mysqli_query($connection, $query);
		return $subject_set;
	}

	function find_content_by_id($content_id){
		global $connection;
		//prevent SQL injection
		$safe_content_id = mysqli_real_escape_string ($connection, $content_id);
		
		$query  = "SELECT * ";
		$query .= "FROM contents ";
		$query .= "WHERE id = {$safe_content_id} ";
		$query .= "LIMIT 1";
		$content_set = mysqli_query($connection, $query);
		// Test if there was a query error
		if (!$content_set) {
			die("Database query failed.");
		}
		
		if ($content = mysqli_fetch_assoc($content_set)){
			return $content;
		} else {
			return null;
		}
	}

	function find_tea_by_id($teas_id){
		global $connection;
		//prevent SQL injection
		$safe_tea_id = mysqli_real_escape_string ($connection, $teas_id);
		
		$query  = "SELECT * ";
		$query .= "FROM teas ";
		$query .= "WHERE id = {$safe_tea_id} ";
		$query .= "LIMIT 1";
		$tea_set = mysqli_query($connection, $query);
		// Test if there was a query error
		if (!$tea_set) {
			die("Database query failed.");
		}
		
		if ($tea = mysqli_fetch_assoc($tea_set)){
			return $tea;
		} else {
			return null;
		}
	}
	
	

?>