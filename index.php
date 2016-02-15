<!DOCTYPE html>
<html>
<head>
	<?php 
	$conn = mysqli_connect("localhost", "mbooman_admin", "admin", "mbooman_todo");

	if(!$conn){
		die("INFO: Connection failed: " . mysqli_connect_error());
	}

	if(isset($_POST["remId"])){
		if($_POST["remId"] == "all") mysqli_query($conn, "DELETE FROM activiteiten");
		elseif($_POST["remId"] != "") mysqli_query($conn, "DELETE FROM activiteiten WHERE id='" . $_POST["remId"] . "'");
	}

	if(isset($_POST["change"]) && $_POST["change"] != "" && isset($_POST["cngId"]) && $_POST["cngId"] != "") mysqli_query($conn, "UPDATE activiteiten SET omschrijving='" . $_POST["change"] . "' WHERE id=" . $_POST["cngId"]);

	if(isset($_POST["todo"]) && $_POST["todo"] != "") mysqli_query($conn, "INSERT INTO activiteiten (omschrijving) VALUES ('" . $_POST["todo"] . "')");

	//echo "SQL INFO: Connected!";

	?>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>ToDo-List</title>
	<link rel="stylesheet" href="style.css">

	<script>
	function change(id){
		var name = document.getElementById("txt"+id).innerHTML;

		var input = document.getElementById("input");
		input.setAttribute("onsubmit", "return confirmForm()")
		input.innerHTML = "<input type='text' id='text' name='change' maxlength='140' autofocus> <input type='hidden' name='cngId' value='"+ id +"'> <input type='submit' id='submit' value='Change'>";
		
		var textbox = document.getElementById("text");
		textbox.setAttribute("value", name);
		textbox.setAttribute("placeholder", name);
	}

	function confirmForm(){
		return confirm("Are you sure?");
	}
	</script>
</head>
<body>
	<div id="frame">
		<form id="input" method="post">
			<input type="text" id="text" name="todo" maxlength="140" autofocus>
			<input type="submit" id="submit" value="Submit">
		</form>
		<div id="todo-list">
			<ul id="list">
				<?php
				$result = mysqli_query($conn, "SELECT id,omschrijving FROM activiteiten");

				if (mysqli_num_rows($result) > 0) {
				    while($row = mysqli_fetch_assoc($result)) {
				        echo "<li id='itm" . $row["id"] . "'><p id='txt". $row["id"] ."'>" . $row["omschrijving"] . "</p> 
				        <form method='post' onsubmit='return confirmForm()'><input type='hidden' name='remId' value='" . $row["id"] . "'><input type='button' class='cng' value='Change' onclick='change(".$row["id"].")'><input type='submit' class='del' value='Delete'></form></li>\n";
				    }
				} else {
				    echo "<li>Think of something you want to do</li>";
				}
				?>
			</ul>
			<ul id="options">
				<li><a href="/projects/todo-list/"><input id="refresh" type="button" value="Refresh"></a></li>
				<li><form id="delAll" method="post" onsubmit='return confirmForm()'><input type="hidden" name="remId" value="all"><input type="submit" value="Delete All"></form></li>
			</ul>
		</div>
	</div>
	<?php
	mysqli_close($conn);
	?>
</body>
</html>