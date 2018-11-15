<?php
	include_once 'header.php';
?>

<section class="header-container">
	<div class="header-bar">
		<h2>Additional Information</h2>
	</div>
</section>

<p class="disclaimer">*Disclaimer: Please Fill out this Form, and Your Profile will not be Public Until You do so.</p>

<form action="includes/additional-info.inc.php" class="add-info" method="post">
<h1>Gender</h1>
		<label for="Female">Female</label>
		<input type="radio" name="gender" value="Female" id="Female">
		<label for="Male">Male</label>
		<input type="radio" name="gender" value="Male" id="Male">
		<label for="Other">Other</label>
		<input type="radio" name="gender" value="Other" id="Other">
	<h1>Sexual Preference</h1>
		<label for="Heterosexual">Heterosexual</label>
		<input type="radio" name="sex-pref" value="Heterosexual" id="Heterosexual">
		<label for="Homosexual">Homosexual</label>
		<input type="radio" name="sex-pref" value="Homosexual" id="Homosexual">
		<label for="Bisexual">Bisexual</label>
		<input type="radio" name="sex-pref" value="Other" id="Bisexual">
	<h1>Biography</h1>
		<textarea name="bio" id="bio" required maxlength="255" placeholder="Tell People about Yourself"></textarea>
	<button type="submit" value="OK" name="submit">Submit</button>
	<button type="reset" value="Reset">Reset</button>	
</form>

<?php
	include_once 'footer.php';
?>