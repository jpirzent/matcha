<?php
	include_once 'header.php';
?>

<section class="header-container">
	<div class="header-bar">
		<h2>Sign-Up</h2>
	</div>
</section>

<form class="signup-form" action="includes/signup.inc.php" method="POST">
    <input type="text" name="first" placeholder="First Name">
    <input type="text" name="last" placeholder="Last Name">
    <input type="text" name="email" placeholder="E-mail">
    <input type="text" name="uid" placeholder="Username">
    <input type="password" name="pwd" placeholder="Password">
    <button type="submit" name="submit">Create Account</button>
</form>

<?php
	include_once 'footer.php';
?>