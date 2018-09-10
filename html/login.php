<?php

    include_once ($_SERVER['DOCUMENT_ROOT']."/html/header.php");

    if (!loginClass::checkLoginState($db))
	{
		if (isset($_POST['username']) && isset($_POST['password']))
		{
			$query = "SELECT * FROM users WHERE user_name = :username AND user_password = :password";

			$username = $_POST['username'];
			$password = $_POST['password'];

			$stmt = $db->prepare($query);
			$stmt->execute(array(':username' => $username, ':password' => $password));

			$row = $stmt->fetch(PDO::FETCH_ASSOC);

			if ($row['user_id'] > 0)
			{
				loginClass::createRecord($db, $row['user_id'], $row['user_name']);
				header("location:nav.php");				
			}

		} else {
			echo '
			<div class="loginPage">
				<div id="loginContainer">
					<h1><img src="/images/ftp_login.png" width="60%" /></h1>
			        <h1 id="loginTitle">FTP Scoring</h1>
			        <br>
					<form action="login.php" method="post">
						<fieldset class="form-group">
							<input class="form-control" type="text" name="username" placeholder="Utilizador">
						</fieldset>
						<fieldset class="form-group">
							<input class="form-control" type="password" name="password" placeholder="Password">
						</fieldset>
						<fieldset class="form-group">
							<input class="btn btn-success" type="submit" name="submit" value="Login">
						</fieldset>
					</form>
					<p>If you do not have a user account<br>And/or got here by mistake<br>Please<br>Follow the Link to<br> 
					<a href="/"><button type="button" class="btn btn-warning btn-sm">HOME PAGE</button></p>
				</div>
			</di>
			';
		}
	} else {
		header("location:index.php");
	}
	
	include($_SERVER['DOCUMENT_ROOT']."/html/footer.php");
?>