<?php
	session_start();
if (isset($_POST['submit']))
{
	include 'dbh.inc.php';
	$uid = $_POST['uid'];
	$pwd = $_POST['pwd'];
	if (empty($uid) || empty($pwd))
	{
		header("Location: ../index.php?login=empty");
		exit();
	}
	else
	{
		try
		{
			$sql = "SELECT COUNT(*) FROM users WHERE user_uid=:username";
			$stmt = $conn->prepare($sql);
			$stmt->bindParam(":username", $uid);
			$stmt->execute();
			$resCheck = $stmt->fetchColumn();
		}
		catch (PDOException $var)
		{
			echo $var->getMessage();
		}
		if ($resCheck == 0)
		{
			header("Location: ../signup.php?login=UnkownUser");
			exit();
		}
		else
		{
			try
			{
				$sql = "SELECT * FROM users WHERE user_uid=:username";
				$check = $conn->prepare($sql);
				$check->bindParam(":username", $uid);
				$check->execute();
				$row = $check->fetch(PDO::FETCH_ASSOC);
			}
			catch (PDOException $var)
			{
				echo $var->getMessage();
			}
			if ($row)
			{
				$hashpwdcheck = password_verify($pwd, $row['user_pwd']);
				if ($hashpwdcheck == FALSE)
				{
					header("Location: ../index.php?login=invalid_pwd");
					exit();
				}
				else if ($hashpwdcheck == TRUE)
				{
					if ($row['user_verified'] == 0)
					{
						header("Location: ../index.php?login=account_unverified");
						exit();
					}
					$_SESSION['u_id'] = $row['user_id'];
					$_SESSION['u_first'] = $row['user_first'];
					$_SESSION['u_last'] = $row['user_last'];
					$_SESSION['u_email'] = $row['user_email'];
					$_SESSION['u_uid'] = $row['user_uid'];
					$_SESSION['u_verify'] = $row['user_verified'];
					$_SESSION['u_notif'] = $row['user_notif'];

					if ($row['user_add'] == 1)
					{
						header("Location: ../index.php?login=success");
						exit();
					}
					else
					{
						header("Location: ../additional-info.php");
						exit();
					}
				}
			}
		}
	}
}
else
{
	header("Location: ../index.php?login=post_not_set");
	exit();
}
?>