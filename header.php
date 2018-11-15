<?php
	 session_start();
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<title>Matcha</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body>
	
<header>
	<nav>
		<div class="header-bar">
			<ul>
				<li><a href="index.php">_Home_</a></li>
			</ul>
			<div class="login-div">
				<?php
					if (isset($_SESSION['u_id']))
					{
						echo '<form action="includes/logout.inc.php" method="POST">
								<button type="submit" name="submit" class="logout-button">Logout</button>
								<a href="user_home.php" class="user_a">Profile</a>
							</form>';
					}
					else
					{
						echo '<form action="includes/login.inc.php" method="POST">
								<input type="text" name="uid" placeholder="username">
								<input type="password" name="pwd" placeholder="password">
								<button type="submit" name="submit">Login</button>
								</form>
								<a href="forgot_pwd.php">Forgot_Password?</a>
								<a href="signup.php">Sign Up</a>';
					}
				?>
			</div>
		</div>
	</nav>
</header>