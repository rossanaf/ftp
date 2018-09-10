<?php 
ob_start();
class loginClass 
{
	public static function checkLoginState($db) 
	{
		// if (!isset($_SESSION))
		// {
		// 	session_start();
		// }
		if (isset($_COOKIE['userid']) && isset($_COOKIE['token']) && isset($_COOKIE['serial']))
		{
			$query = "SELECT * FROM sessions WHERE session_userid = :userid AND session_token = :token AND session_serial = :serial;";

			$user_id = $_COOKIE['userid'];
			$token = $_COOKIE['token'];
			$serial = $_COOKIE['serial'];

			$stmt = $db->prepare("SELECT * FROM sessions WHERE session_userid = :userid AND session_token = :token AND session_serial = :serial;");
			$stmt->execute(array(':userid' => $user_id, ':token' => $token, ':serial' => $serial));

			$row = $stmt->fetch(PDO::FETCH_ASSOC);

			if ($row['session_userid'] > 0)
			{
				if (
					$row['session_userid'] == $_COOKIE['userid'] &&
					$row['session_token'] == $_COOKIE['token'] &&
					$row['session_serial'] == $_COOKIE['serial']
					)
				{
					if (
						$row['session_userid'] == $_SESSION['userid'] &&
						$row['session_token'] == $_SESSION['token'] &&
						$row['session_serial'] == $_SESSION['serial']
						)
					{
						return true;
					}
				} else {
					loginClass::createSession($_COOKIE['username'], $_COOKIE['userid'], $_COOKIE['token'], $_COOKIE['serial']);
					return true;
				}
			}
		}
	}

	public static function createRecord($db, $user_id, $user_name)
	{
		$stmt = $db->prepare("DELETE FROM sessions WHERE session_userid = :user_id;");
		$stmt->execute(array(':user_id' => $user_id));

		$token = loginClass::createString(32);
		$serial = loginClass::createString(32);
		$date = time();

		loginClass::createCookie($user_name, $user_id, $token, $serial);
		loginClass::createSession($user_name, $user_id, $token, $serial);

		$stmt = $db->prepare("INSERT INTO sessions (session_userid, session_token, session_serial, session_date) VALUES (:user_id, :token, :serial, :date)");
		$stmt->execute(array(':user_id' => $user_id, ':token' => $token, ':serial' => $serial, ':date' => $date));
	}

	public static function createCookie($user_name, $user_id, $token, $serial)
	{
		setcookie('userid', $user_id, time() + (86400)*2, "/");
		setcookie('username', $user_name, time() + (86400)*2, "/");
		setcookie('token', $token, time() + (86400)*2, "/");
		setcookie('serial', $serial, time() + (86400)*2, "/");
	}

	public static function deleteCookie()
	{
		setcookie('userid', '', time() - (86400)*2, "/");
		setcookie('username', '', time() - (86400)*2, "/");
		setcookie('token', '', time() - (86400)*2, "/");
		setcookie('serial', '', time() - (86400)*2, "/");
		session_destroy();
	}

	public static function createSession($user_name, $user_id, $token, $serial)
	{
		if (!isset($_SESSION))
		{
			session_start();
		}
		$_SESSION['userid'] = $user_id;
		$_SESSION['username'] = $user_name;
		$_SESSION['token'] = $token;
		$_SESSION['serial'] = $serial;
	}

	public static function createString($len)
	{
		$string = "aksAShbga8e4ut@nfijdsmR980+3WQ8402sdagtrs834987CQNBsdfA73W2Q2";
		
		return substr(str_shuffle($string), 0, 32);
	}
}

?>