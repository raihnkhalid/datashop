<?php
try {
	$conn = new PDO('mysql:host=localhost;dbname=datashop', 'root', '', array(PDO::ATTR_PERSISTENT => TRUE));
} catch (PDOException $err) {
	echo $error->getMessage();
}

$func = new Auth($conn);

class Auth
{
	private $db;
	private $error;

	function __construct($dbconn)
	{
		$this->db = $dbconn;
		session_start();
		date_default_timezone_set('Asia/Jakarta');
	}


	public function getLastError()
	{
		return $this->error;
	}

	public function isLoggedIn()
	{
		if (isset($_SESSION['user_login'])) {
			return true;
		}
	}

	public function fetchEmail()
	{
		$qry = $this->db->prepare("SELECT * FROM users WHERE id = :id");
		$qry->bindParam(":id", $_SESSION['user_login']);
		$qry->execute();
		$fetch = $qry->fetch();

		return $fetch['email'];
	}

	public function logout_user()
	{
		session_destroy();
		unset($_SESSION['user_login']);

		return true;
	}


	public function register_user($email, $password)
	{
		try {
			$hashPw = password_hash($password, PASSWORD_DEFAULT);
			$qry = $this->db->prepare("INSERT INTO users (email, password) VALUES(:email, :password)");
			$qry->bindParam(":email", $email);
			$qry->bindParam(":password", $hashPw);
			$qry->execute();
			return true;
		} catch (PDOException $err) {

			if ($err->errorInfo[0] == 23000) {
				$this->error = "Email tersebut sudah terdaftar!";
				return false;
			} else {
				echo $err->getMessage();
				return false;
			}
		}
	}


	public function login_user($email, $password)
	{
		try {
			$qry = $this->db->prepare("SELECT * FROM users WHERE email = :email");
			$qry->bindParam(":email", $email);
			$qry->execute();
			$fetch = $qry->fetch();
			$cnt = $qry->rowCount();

			if (!$fetch) {
				$this->error = "Email atau Password salah!";
				return false;
			}

			if ($cnt > 0) {
				if (password_verify($password, $fetch['password'])) {
					$_SESSION['user_login'] = $fetch['id'];
					header("Location: home.php");
					return true;
				} else {
					$this->error = "Email atau Password salah!";
					return false;
				}
			}
		} catch (PDOException $err) {
			echo $err->getMessage();
			return false;
		}
	}
}