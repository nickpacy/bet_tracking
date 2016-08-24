<?php 

require "includes/pacy_fx.php";

if ($_REQUEST['logout'] == "true") {
			session_unset();
			setcookie('login_name', $login_name, time() - (86400), "/"); // 86400 = 1 day
			header( 'Location: index' ) ;

}


if ($_POST["_addbet"] == "1") {


	if (!isset($_SESSION['user_id'])) {
		$user_id = getUserID();
	}else{
		$user_id = $_SESSION['user_id'];
	}

	if (!isset($user_id)) {
		header( 'Location: index?action=error' ) ;
	}
	
	$sport 			= $_POST["sport"];
	$home_team 		= $_POST["home_team"];
	$away_team 		= $_POST["away_team"];
	$bet_amount 	= $_POST["bet"];
	$odds 			= $_POST["us_odds"];
	$result 		= $_POST["result"];
	$bet_choice 	= $_POST["bet_choice"];
	$bet_spread 	= $_POST["bet_spread"];
	$to_win_amount 	= $_POST["payout"];
	$start_time 	= $_POST["start_time"];
	$notes 			= $_POST["notes"];

	$start_time = date("Y-m-d H:i:s", strtotime($start_time));

	$qry = " INSERT INTO bet_tracking.Bet (User_ID, Sport_ID, Away_Team_ID, Home_Team_ID,  Bet_Amount, Odds, To_Win_Amount, Spread, Bet_Choice_ID, Result_ID, Game_Date, Notes) VALUES ($user_id, '$sport', '$away_team', '$home_team', $bet_amount, $odds,  $to_win_amount, $bet_spread, '$bet_choice', '$result', '$start_time', '$notes')";


		if (mysqli_query($conn, $qry)) {
			$referer = $_SERVER['HTTP_REFERER'];
			header("Location: $referer");
		} else {
			echo $qry;

			//header( 'Location: index?action=error' ) ;
		}
}

if ($_POST["_createAccount"] == "1") {
	
	$user_name 			= $_POST["user_name"];
	$user_email 		= $_POST["user_email"];
	$password 			= $_POST["password"];
	$hash 				= encryptString($password);

	$Cqry = " SELECT * FROM bet_tracking.UserTBL WHERE user_name = '$user_name' OR user_email = '$user_email';";
	$Cq = mysqli_query($conn, $Cqry);
	$rowCount = $Cq->num_rows;
	if ($rowCount == 0) {
		


	$qry = " INSERT INTO bet_tracking.UserTBL (user_name, user_email, password) VALUES ('$user_name', '$user_email', '$hash')";


		if (mysqli_query($conn, $qry)) {

      		$_SESSION['user_id'] = mysql_insert_id($conn);
      		$_SESSION['user_name'] = strtolower($user_name);
			setcookie('user_name', $user_name, time() + (86400 * 30), "/"); // 86400 = 1 day
			header( 'Location: index?action=login' ) ;
		} else {
			header( 'Location: index?action=error' ) ;
		}

	}else{
		header( 'Location: login?action=userExists' ) ;
	} //ROWCOUNT ***Duplicate User
}

if ($_POST['_CheckLogin'] != "") {

		if ($_POST['_CheckLogin'] == 2) {
			$user_name 			= noInject($_POST['login_user_name']);
			$password 			= noInject($_POST['login_password']);
		}else{	
			$user_name 			= noInject($_POST['nav_user_name']);
			$password 			= noInject($_POST['nav_password']);
		}


		$hash 				= encryptString($password);

		$qry = "SELECT * FROM UserTBL WHERE user_name = '$user_name' OR user_email = '$user_name' LIMIT 1";
		$q = mysqli_query($conn, $qry);
		$rowCount = $q->num_rows;

		if ($rowCount == 1) {

			while($rs = mysqli_fetch_array($q)) { 
					$checkPass = $rs['password'];
					$cur_user_ID = $rs['user_id'];
					$user_name = $rs['user_name'];
				}


				if (password_verify($password, $checkPass)) {
      			$_SESSION['user_name'] = strtolower($user_name);
      			$_SESSION['user_id'] = $cur_user_ID;
      			setcookie('user_name', $user_name, time() + (86400 * 30), "/"); // 86400 = 1 day
   				header( 'Location: index' ) ;
			}else{
				header( 'Location: login?action=error' ) ;
			}

				
		}else{
			header( 'Location: login?action=doesntexist' ) ;
		}
}


?>

test
