<?php
	include_once 'header.php';
?>
<section class="header-container">
	<div class="header-bar">
		<h2>Forgot_Password?</h2>
	</div>
</section>
<form class="forgot-form" action="includes/forgot_pwd.inc.php" method="POST">
    <input type="text" name="uid" placeholder="Username">
	<input type="text" name="email" placeholder="E-mail">
	<button type="submit" name="submit">Send Email To Change Password</button>
</form>
<?php
	include_once 'footer.php';
?>