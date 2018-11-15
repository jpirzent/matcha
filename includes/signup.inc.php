<?php
	if (isset($_POST['submit'])) 
	{
		include_once 'dbh.inc.php';
		$first = $_POST['first'];
		$last = $_POST['last'];
		$email = $_POST['email'];
		$uid = $_POST['uid'];
		$pwd = $_POST['pwd'];
		
		if (empty($first) || empty($last) || empty($email) || empty($uid) || empty($pwd))
		{
			header("Location: ../signup.php?signup=empty");
			exit();
		}
		else
		{
			if (!preg_match("/^[a-zA-Z]*$/", $first) || !preg_match("/^[a-zA-Z]*$/", $last))
			{
				header("Location: ../signup.php?signup=invalid-names");
				exit();
			}
			else
			{
				if (!filter_var($email, FILTER_VALIDATE_EMAIL))
				{
					header("Location: ../signup.php?signup=invalid-email");
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
					if ($resCheck > 0)
					{
						header("Location: ../signup.php?signup=invalid-username");
						exit();
					}
					else
					{
						if (preg_match( '~[A-Z]~', $pwd) && preg_match( '~[a-z]~', $pwd) && preg_match( '~\d~', $pwd) && (strlen( $pwd) > 8))
						{
							$hashpwd = password_hash($pwd, PASSWORD_DEFAULT);
							$key = mt_rand(1000,9999);
							$key .= $uid;
							$hkey = hash('whirlpool', $key);
							$sql = "INSERT INTO users (user_first, user_last, user_email, user_uid, user_pwd, user_key) VALUES (:firstn, :lastn, :email, :username, :hashpwd, :hkey);";
							try
							{
								$stmt = $conn->prepare($sql);
								$stmt->bindParam(":firstn", $first);
								$stmt->bindParam(":lastn", $last);
								$stmt->bindParam(":email", $email);
								$stmt->bindParam(":username", $uid);
								$stmt->bindParam(":hashpwd", $hashpwd);
								$stmt->bindParam(":hkey", $hkey);
								$stmt->execute();
								echo "new record created successfully";
								$link = $_SERVER['HTTP_HOST'];
								$link .= str_replace("signup", "verify", $_SERVER['SCRIPT_NAME']);
								$subject = "Account Verification";
								$msg = "
								<html>
								<head>
								<title>Matcha Account Verification</title>
								</head>
								<body>
								<p>Please follow the link bellow to Verify your Account</p><br />
								<a href='http://".$link."?key=".$hkey."'>Verify Account</a>
								</body>
								</html>
								";
								$head = "MIME-Version: 1.0" . "\r\n";
								$head .= "Content-type:text/html;charset=UTF-8" . "\r\n";
								mail($email, $subject, $msg, $head);
								header("Location: ../index.php?signup=success");
								exit();
							}
							catch(PDOException $e)
							{
								echo $sql . "<br>" . $e->getMessage();
							}
							
						}
						else
						{
							header("Location: ../signup.php?signup=invalidpassword");
							exit();
						}
					}
				}
			}
		}
	}
	else
	{
		header("Location: ../index.php");
		exit(); 
	} 