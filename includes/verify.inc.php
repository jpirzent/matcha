<?php

    include_once 'dbh.inc.php';
	if (isset($_GET['key']))
	{
		$key = $_GET['key'];
		
		try
		{
			$sql = "SELECT COUNT(*) FROM users WHERE user_key=:hkey";
			$stmt = $conn->prepare($sql);
			$stmt->bindParam(":hkey", $key);
			$stmt->execute();
			$resCheck = $stmt->fetchColumn();
		}
		catch(PDOException $e)
		{
			echo $sql . "<br>" . $e->getMessage();
		}
		if ($resCheck == 0)
		{
			header("Location: ../signup.php?login=error");
			exit();
		}
		else
		{
			try
			{
				$sql = "SELECT * FROM users WHERE user_key=:hkey";
				$check = $conn->prepare($sql);
				$check->bindParam(":hkey", $key);
				$check->execute();
				$row = $check->fetch(PDO::FETCH_ASSOC);
			}
			catch (PDOException $var)
			{
				echo $var->getMessage();
			}
			if ($row)
			{
				try
				{
					$sql = "UPDATE users SET user_verified='1' WHERE user_key=:hkey";
					$stmt = $conn->prepare($sql);
					$stmt->bindParam(":hkey", $key);
					$stmt->execute();
					header("Location: ../index.php?verification=success");
					exit();
				}
				catch(PDOException $e)
				{
					echo $sql . "<br>" . $e->getMessage();
				}
			}
		}
	}
	else
	{
		header("Location: ../index.php?login-error");
		exit();
	}
?>