<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Logout</title>
		<link rel="stylesheet" type="text/css" 
		  href="css/styles.css?<?php echo time(); ?>">
</head>
<body class="logout">

	<?php
		session_destroy(); //destroy all sessions

		unset($_SESSION['Role']); //role session only

		        
	?>
	
			        <script>
						alert('Logout Successful!');
						window.location.href='signin.php';
					</script>";
</body>
</html>