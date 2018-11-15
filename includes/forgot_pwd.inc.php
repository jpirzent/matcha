<?php
	if(isset($_POST['submit']))
	{
		include_once 'dbh.inc.php';
		
		$email = $_POST['email'];
		$uid = $_POST['uid'];
		if (empty($email) || empty($uid))
		{
			header("Location: ../forgot_pwd.php?change=emptyfields");
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
				header("Location: ../forgot_pwd.php?change=wrong_username");
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
					if (strcmp($uid, $row['user_uid']) == 0 && strcmp($email, $row['user_email']) == 0)
					{
						$pwd = mt_rand(10000000, 99999999);
						$subject = "Change Password";
						$msg = "
						<html>
						<head>
						<title>Matcha Change Password</title>
						</head>
						<body>
						<p>Please use ".$pwd." to login to your Account</p><br />
						</body>
						</html>
						";
						$head = "MIME-Version: 1.0" . "\r\n";
						$head .= "Content-type:text/html;charset=UTF-8" . "\r\n";
						mail($email, $subject, $msg, $head);
						$hashpwd = password_hash($pwd, PASSWORD_DEFAULT);
						try
						{
							$sql = "UPDATE users SET user_pwd=:hashpwd WHERE user_uid=:username";
							$stmt = $conn->prepare($sql);
							$stmt->bindParam(":hashpwd", $hashpwd);
							$stmt->bindParam(":username", $uid);
							$stmt->execute();
						}
						catch (PDOException $var)
						{
							echo $var->getMessage();
						}	
						header("Location: ../index.php?email-sent");
						exit();
					}
				}
				else
				{
					header("Location: ../index.php?error");
					exit();	
				}
			}
		}
	}
?>