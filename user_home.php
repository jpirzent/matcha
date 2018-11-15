<?php
	include_once 'header.php';
?>

<section class="header-container">
	<div class="header-bar">
		<h2>Profile</h2>
	</div>
</section>


<?php
	if (isset($_SESSION['u_uid']))
	{

		$uid = $_SESSION['u_id'];
		include_once 'includes/dbh.inc.php';
		try
		{
			$sql = 'SELECT * FROM users WHERE user_id=:user';
			$pdo = $conn->prepare($sql);
			$pdo->bindParam(":user", $uid);
			$pdo->execute();
			$result = $pdo->fetch(PDO::FETCH_ASSOC);
		}
		catch(PDOException $var)
		{
			echo $var->getMessage();
		}
	}
?>

<details>
	<p>this is james</p>
</details>

<?php
	include_once 'footer.php';
?>