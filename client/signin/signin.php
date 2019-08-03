<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function redirectMainPage(){
	header("Location: http://".$_SERVER['HTTP_HOST']."/Web/client/browse/main.php");
}
function redirect(){
	header("Location: http://".$_SERVER['HTTP_HOST']."/Web/client/signin/signin.php");
}
include_once("../handler/SessionManager.php");

checkOldUser();
?>
<!DOCTYPE HTML>
<html lang="en_us">
<head>
	<!--include these lines in every index pages-->
	<meta charset="UTF-8">
	<meta name="author"content="Sudip Ghimire">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="icon" href="../images/favicon.png" type="image/png">
	<link rel="stylesheet" type="text/css" href="../menu/menu.css" />
	<!-----change these lines per index page---->
	<link rel="stylesheet" type="text/css" href="signin.css">
	<title>Sudip Signin</title>
</head>
<body>
	<div id="showMe"></div>
	<div id="reserveMenu">
		<?php
			$menuFile=file_get_contents("../menu/menu.php");
			echo $menuFile;
		?>
	</div>
	<form id="FormArea"method="POST"action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
		<div id="Greet">
			<div id="firstLine" class="lines">
				Sign into your Chat ID
			</div>
			<div id="secondLine" class="lines">
				If you don't have chat ID, please <a href="../signup/signup.php">create one</a>.
			</div>
		</div>
		<div id="Input">
			<div id="ErrorLogger"name="ErrorLogger">Fill up the Field Below</div>

			<div id="EmailFld" class="fields">
				<span id="label">Email</span><br />
				<input type="text" name="Email" id="Email"placeholder='Your Email..' />
			</div>
			<div id="PasswordFld" class="fields">
				<span id="label">Password</span><br />
				<input type="password" name="Password" id="Password"placeholder='Your Password..' />
			</div>
		</div>
		<div id="Extra">
			<div id="forgetPassword">
				<span class="label">
					<a href="#">?Forget Password</a>
				</span>
			</div>
			<div id="UsernameIssue">
				<span class="label">
					<a href="#">?Can't find confirmation Email</a>
				</span>
			</div>
		</div>
		<div id="Final">
			<span id="btnContainer">
				<input id="login" type="submit" name="submit" value="Get in" />
			</span>
		</div>
	</form>
	<center id="seperator">
		<span id="label">Try to Get</span>
	</center>
	<center id="DesktopApp">
		<button id="downloadBtn">Get Desktop App</button>
	</center>

	<!------------------------------------------->
	<div id="Toast">Some text Message Here..</div>
	<!-------------------------------------------->

</body>
<script type="text/javascript"src="../menu/menu.js"></script>
<script type="text/javascript" src="signin.js"></script>
</html>
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$Email = $Password = $UserName = $FullName = $conn = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$GLOBALS['conn'] = new mysqli('localhost', 'sudip', 'sudip123', 'chatbox');
	$GLOBALS['Email'] = mysqli_real_escape_string($conn, $_POST['Email']);
	$GLOBALS['Password'] = mysqli_real_escape_string($conn, $_POST['Password']);
	
	function trueInput(){
		$conn = $GLOBALS['conn'];
		function processPassword($conn){
			$Password = $GLOBALS['Password'];
			$Email = $GLOBALS['Email'];
      $res = $conn->query("SELECT * FROM main WHERE Email='$Email' LIMIT 1;");
      $conn->close();
			if(mysqli_num_rows($res) > 0){
				$row = mysqli_fetch_array($res);
				$userPass = $row['Password'];
				$userId = base64_encode($row['UserName']);
        $timeTrigger = base64_encode($row['lastChange']);
        $myPass = gzcompress(base64_encode($row['Password']), 6);
				if (password_verify($Password, $userPass)) {
					deleteOldLogin();
					$cookie1 = base64_encode('userId');
          $cookie2 = base64_encode("userIdentification");
          $cookie3 = base64_encode("myHash");
					setcookie($cookie1, $userId, time() + (86400*5),'/');
          setcookie($cookie2, $timeTrigger, time() + (86400*5),'/');
          setcookie($cookie3, $myPass, time() + (86400*5),'/');
          setcookie('LastTalkWith', 'ChatBox', time() + (86400*1),'/');
					redirect();
				}
				else{
					echo "<script>Invalid()</script>";
				}
			}
			else {
				echo "<script>Invalid()</script>";
			}
		}
		function processEmail($conn){
			$Email = $GLOBALS['Email'];
			$res = $conn->query("SELECT * FROM main WHERE Email='$Email' LIMIT 1;");
			$res2 = $conn->query("SELECT * FROM temp WHERE Email = '$Email' LIMIT 1;");
			if(mysqli_num_rows($res2) > 0){
				echo "<script>Invalid();document.getElementById('ErrorLogger').textContent='This email is not verified yet..';</script>";
			}
			else if(mysqli_num_rows($res) > 0){
				processPassword($conn);
			}
			else{
				echo "<script>Invalid();</script>";
			}
		}
		processEmail($conn);
	}
	function checkEmail($Email){
		$sub = explode("@", $Email);
		if(!preg_match("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$^",$Email)){
			echo "<script>Invalid()</script>";
			return false;
		}
		else if(preg_match('/[\'^£$%&*()}{@#~.`?><>,"!|=_+¬-]/', $sub[0])){
			echo "<script>Invalid();</script>";
			return false;
		}
		else{
			return true;
		}
	}
	function checkPassword($Password){
		if(strlen($Password) < 8){
			echo "<script>Invalid();</script>";
			return false;
		}
		else if(!preg_match('/[0-9]/',$Password)){
			echo "<script>Invalid();</script>";
			return false;
		}
		else if(!preg_match("/[a-z]/i", $Password)){
			echo "<script>Invalid();</script>";
			return false;
		}
		else if(preg_match('/\s/',$Password)){
			echo "<script>Invalid();</script>";
			return false;
		}
		else{
			return true;
		}
	}
	$trueEmail = checkEmail($Email);
	$truePassword  = false;
	if($trueEmail == true){
		$truePassword = checkPassword($Password);
	}
	if($truePassword == true){
		trueInput();
	}
	exit;
}
exit;
?>