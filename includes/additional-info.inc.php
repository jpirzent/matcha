<?php

	session_start();

	if (isset($_POST['submit']) && $_SESSION['u_id'])
	{
		$bio = $_POST['bio'];
		$gender = $_POST['gender'];
		$pref = $_POST['sex-pref'];

		if (empty($bio) || empty($gender) || empty($pref))
		{
			header("Location: ../additional-info.php?error");
			exit();	
		}
		else
		{
			$bio = htmlentities($bio);
			include_once 'dbh.inc.php';
			$uid = $_SESSION['u_id'];

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
			if (!$result)
			{
				header("Location: ../additional-info.php?error");
				exit();
			}
			else
			{
				try
				{
					$sql = "UPDATE users SET user_gender = :gender, user_pref = :pref, user_bio = :bio, user_add = '1' WHERE user_id=:user";
					$pdo = $conn->prepare($sql);
					$pdo->bindParam(":gender", $gender);
					$pdo->bindParam(":pref", $pref);
					$pdo->bindParam(":bio", $bio);
					$pdo->bindParam(":user", $uid);
					$pdo->execute();
					header("Location: ../index.php?var=change-successfull");
					exit();
				}
				catch(PDOException $var)
				{
					echo $var->getMessage();
				}	
			}
		}
	}
	else
	{
		header("Location: ../additional-info.php?error");
		exit();
	}

?>