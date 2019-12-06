<?php

class user {
	
	private $_link;

	public function __construct() {

		$this->_link = (new database())->__construct();
	
	}

	public function signed_in() {

		if(isset($_SESSION['token'])) {

			$token = JWT::decode($_SESSION['token'], 'ssecrt');

			if(!empty($token)) {
				return true;
			}

		}
		return false;

	}

	public function sign_in() {

		$email = trim($_POST['email']);
		$password = trim($_POST['password']);

		if(!empty($email) && !empty($password)) {

			$query = $this->_link->prepare('SELECT * FROM `users` WHERE `email` = :email');
			$query->bindParam(':email', $email, PDO::PARAM_STR);
			$query->execute();

			$result = $query->fetch(PDO::FETCH_ASSOC);

			if(password_verify($password, $result['password'])) {

				$token = array();
				$token['id'] = $result['id'];

				$_SESSION['token'] = JWT::encode($token, 'ssecrt');
				$_SESSION['email'] = $email;

				header('Location: /');
				die();

			} else {

				echo '<p class="c-red" style="color:#e55454 !important; text-align:center;"><i class="icmn-warning p-r-10"></i> Feil epost og/eller passord</p>';

			}

		} else {

			echo 'Please fill in all fields';

		}

	}

	public function sign_up() {

		header('Content-Type:text/html; charset=UTF-8');
		
		$checked 		= false;
		
		$username 				= trim($_POST['username']);
		$email 					= trim($_POST['email']);
		$password 				= trim($_POST['password']);
		$passwordCheck 			= trim($_POST['password2']);
		$firstname 				= trim($_POST['firstname']);
		$lastname 				= trim($_POST['lastname']);
		$birthday 				= trim($_POST['birthday']);
		$checked  				= trim($_POST['terms']);

		$newUsername 			= filter_var($username, FILTER_SANITIZE_STRING);
		$newEmail 				= filter_var($email, FILTER_SANITIZE_STRING);
		$newPassword 			= filter_var($password, FILTER_SANITIZE_STRING);
		$newPasswordCheck 		= filter_var($passwordCheck, FILTER_SANITIZE_STRING);
		$newFirstname 			= filter_var($firstname, FILTER_SANITIZE_STRING);
		$newLastname 			= filter_var($lastname, FILTER_SANITIZE_STRING);
		$newBirthday 			= filter_var($birthday, FILTER_SANITIZE_STRING);



		if(!empty($newUsername) && !empty($newEmail) && !empty($newPassword) && !empty($newPasswordCheck) && !empty($newFirstname) && !empty($newLastname) && !empty($newBirthday) && !$checked = false) {
			if($password == $passwordCheck) {
			if(filter_var($email, FILTER_VALIDATE_EMAIL)) {

				$query = $this->_link->prepare('SELECT * FROM `users` WHERE `email` = :email');
				$query->bindParam(':email', $newEmail, PDO::PARAM_STR);
				$query->execute();

				if($query->rowCount() == 0) {

					$query = $this->_link->prepare('SELECT * FROM `users` WHERE `username` = :username');
					$query->bindParam(':username', $newUsername, PDO::PARAM_STR);
					$query->execute();

					if($query->rowCount() == 0) {

						$options = [
							'cost' => 12
						];

						$newPassword = password_hash($newPassword, PASSWORD_BCRYPT, $options);
						// DEFAULT VALUES (DO NOT CHANGE)
						$null = NULL;
						$defualtScore = 100;
						$defualtRank  = 0;
						$t=time();
						$timestamp = (date("d/m/20y",$t));

						$query = $this->_link->prepare('INSERT INTO `users`() VALUES(NULL, :email, :username, :password, :firstname, :lastname, :birthday, :score, :rank, :signed_up)');
						
						$query->bindParam(':email', $newEmail, PDO::PARAM_STR);
						$query->bindParam(':username', $newUsername, PDO::PARAM_STR);
						$query->bindParam(':password', $newPassword, PDO::PARAM_STR);
						$query->bindParam(':firstname', $newFirstname, PDO::PARAM_STR);
						$query->bindParam(':lastname', $newLastname, PDO::PARAM_STR);
						$query->bindParam(':birthday', $newBirthday, PDO::PARAM_STR);
						$query->bindParam(':score', $defualtScore, PDO::PARAM_STR);
						$query->bindParam(':rank', $defualtRank, PDO::PARAM_STR);
						$query->bindParam(':signed_up', $timestamp, PDO::PARAM_STR);
						$query->execute();
						
						// Reserve ID in UsersAdv

			            $servername = "localhost";$username = "root";$password = "worktroll911";$dbname = "trundle";

						// Create connection
						$conn = new mysqli($servername, $username, $password, $dbname);
						// Check connection
						if ($conn->connect_error) {
						    die("Connection failed: " . $conn->connect_error);
						} 

						$sql = "INSERT INTO usersAdv (username) VALUES ('$newUsername');";

						if ($conn->query($sql) === TRUE) {
						    echo "New record created successfully";
						} else {
						    echo "Error: " . $sql . "<br>" . $conn->error;
						}

						$conn->close();

						// -- Reserve ID in UsersAdv

						// Reserve ID in UsersSettings

			            $servername = "localhost";$username = "root";$password = "worktroll911";$dbname = "trundle";

						// Create connection
						$conn = new mysqli($servername, $username, $password, $dbname);
						// Check connection
						if ($conn->connect_error) {
						    die("Connection failed: " . $conn->connect_error);
						} 

						$sql = "INSERT INTO usersSettings (username, hideEmail, hideLastname) VALUES ('$newUsername','1','1')";

						if ($conn->query($sql) === TRUE) {
						    echo "New record created successfully";
						} else {
						    echo "Error: " . $sql . "<br>" . $conn->error;
						}

						$conn->close();

						// -- Reserve ID in UsersSettings


						header('Location: index.php');
					} else {

						echo '<p class="c-red" style="color:#e55454 !important; text-align:center;"><i class="icmn-warning p-r-10"></i> Brukernavn allerede i bruk.</p>';

					}

				} else {

					echo '<p class="c-red" style="color:#e55454 !important; text-align:center;"><i class="icmn-warning p-r-10"></i> Email allerede i bruk.</p>';

				}

	
			} else {

				echo '<p class="c-red" style="color:#e55454 !important; text-align:center;"><i class="icmn-warning p-r-10"></i> Ikke riktig Email.</p>';

			}

			} else {
				
				echo '<p class="c-red" style="color:#e55454 !important; text-align:center;"><i class="icmn-warning p-r-10"></i> Passord er ulike.</p>';

			}
			
		} else {

			echo '<p class="c-red" style="color:#e55454 !important; text-align:center;"><i class="icmn-warning p-r-10"></i> Fyll inn alle felter.</p>';

		}

		

	}

	public function user_info($user, $field) {

		$query = $this->_link->prepare('SELECT * FROM `users` WHERE `id` = :id');
		$query->bindParam(':id', $user, PDO::PARAM_INT);
		$query->execute();

		return $query->fetch(PDO::FETCH_ASSOC)[$field];

	}

}

?>