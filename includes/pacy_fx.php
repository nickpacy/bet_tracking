<?php 

include("scs_first.php");
include("password.php");
date_default_timezone_set('America/New_York');

function noInject($value) { 
	global $conn;
	$value = stripslashes( $value ); 		
	$value = mysqli_real_escape_string($conn, $value ); 
	$value = strip_tags($value);
	$value = htmlspecialchars($value);
	$value = trim($value);
	return $value; 
} 

function curPageURL() {
	 $pageURL = 'http';
	 if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
	 $pageURL .= "://";
	 if ($_SERVER["SERVER_PORT"] != "80") {
	  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	 } else {
	  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	 }
	 

	  //$pageURL = substr($pageURL, 20);
	  //echo $pageURL;
	  return $pageURL;
}

function CheckLoggedIn($pageURL){
	global $conn;

	if (!strpos($pageURL,'login.php')) {

		if (isset($_SESSION['user_id'])) {
			return $_SESSION['user_id'];
		}elseif (isset($_SESSION['user_name'])) {
								
			$qry = "SELECT * FROM bet_tracking.UserTBL WHERE user_name = '" . $_SESSION['user_name'] . "';";
			$q = mysqli_query($conn, $qry);
			$rowCount = $q->num_rows;

			if ($rowCount == 1) {

				while($rs = mysqli_fetch_array($q)) { 
						$user_id = $rs['user_id'];
					}

				$_SESSION["user_id"] = $user_id;
				return $user_id;


			}else{
				header( 'Location: login.php?action=noUser' );
			}


		}else{
			header( 'Location: login.php?action=noUser' );
		}
	}
}

function encryptString($string){

	$hash = password_hash($string, PASSWORD_BCRYPT);
	return $hash;
}

function generateRandomPassword($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}


function validLogin(){

	if ($_SESSION['loginName'] == "nickpacy") {
		return true;
	}else{
		return false;
	}
	
}

function GetResultNumber($result){
	global $conn;
	$qry = "SELECT count(ID) as TheCount FROM bet_tracking.Bet WHERE Result_ID = $result AND user_id = " . $_SESSION["user_id"];
	$q = mysqli_query($conn, $qry);
	$rs = mysqli_fetch_array($q);
	return $rs["TheCount"];
	
}

function getUserID(){
	global $conn;
	if (isset($_SESSION['user_name'])) {
		$qry = "SELECT * FROM bet_tracking.UserTBL WHERE user_name = '" . $_SESSION['user_name'] . "';";
		$q = mysqli_query($conn, $qry);
		$rowCount = $q->num_rows;

		if ($rowCount == 1) {

			while($rs = mysqli_fetch_array($q)) { 
					$user_id = $rs['user_id'];
				}

			$_SESSION["user_id"] = $user_id;
			return $user_id;


		}else{
			header( 'Location: login.php?action=noUser' );
		}


	}else{
		header( 'Location: login.php?action=noUser' );
		
	}
}


function GetProfitXMonth(){
	global $conn;
	global $user_id;
	$theVal = array();

	$qry = 		  " SELECT str_to_date( concat( year( start_time ) , '-', month( start_time ) , '-1' ) , '%Y-%m-%d' ) AS 'TheMonth', ";
	$qry = $qry . " (sum(CASE WHEN TheType = 'Wins' THEN Amount ElSE 0 END)-sum(CASE WHEN TheType = 'Losses' THEN Amount ElSE 0 END)) as 'Total' ";
	$qry = $qry . " FROM( ";
	$qry = $qry . " SELECT start_time, sum(to_win_amount) as 'Amount', 'Wins' as 'TheType' FROM Master WHERE user_id = $user_id AND result = 'Win' GROUP BY start_time ";
	$qry = $qry . " UNION ";
	$qry = $qry . " SELECT start_time, sum(bet_amount) as 'Amount', 'Losses' as 'TheType' FROM Master WHERE user_id = $user_id AND result = 'Loss' GROUP BY start_time ";
	$qry = $qry . "    ) as qry ";
	$qry = $qry . " GROUP BY str_to_date( concat( year( start_time ) , '-', month( start_time ) , '-1' ) , '%Y-%m-%d' ) ";
	$q = mysqli_query($conn, $qry);
	$rowCount = $q->num_rows;
	
	if ($rowCount != 0) {
		
		$monthArr = "[";
		$profitArr = "[";
		$pNum = 0;
		$count = 1;

		while($rs = mysqli_fetch_array($q)) {

			$monthNum  = date("m",strtotime($rs["TheMonth"]));
			$year  = date("Y",strtotime($rs["TheMonth"]));
			$dateObj   = DateTime::createFromFormat('!m', $monthNum);
			$monthName = $dateObj->format('F'); // March

			$profitArr = $profitArr . number_format(($rs["Total"] + $pNum), 2, '.', '');
			$monthArr = $monthArr . "'" .  $monthName ."-" . $year . "'";
			$pNum = $rs["Total"];
			if ($rowCount != $count) {	
				$profitArr = $profitArr . ", ";
				$monthArr = $monthArr . ", ";
			}

			$count = $count + 1;
		}

		$profitArr = $profitArr . "] ";
		$monthArr = $monthArr . "] ";

		$theVal[0] = $monthArr;
		$theVal[1] = $profitArr;

	}else{
		$theVal[0] = "[]";
		$theVal[1] = "[]";
	}

	return $theVal;
}
function GetProfitXSport(){
	global $conn;
	global $user_id;
	$theVal = array();

$qry = $qry . " 	SELECT sport,  ";
$qry = $qry . " (sum(CASE WHEN TheType = 'Wins' THEN Amount ElSE 0 END)-sum(CASE WHEN TheType = 'Losses' THEN Amount ElSE 0 END)) as 'Total'  ";
$qry = $qry . " FROM(  ";
$qry = $qry . "     SELECT sport, sum(to_win_amount) as 'Amount', 'Wins' as 'TheType'  ";
$qry = $qry . "    	FROM Master WHERE user_id = $user_id AND result = 'Win'  ";
$qry = $qry . "     GROUP BY sport ";
$qry = $qry . " UNION ";
$qry = $qry . "     SELECT sport, sum(bet_amount) as 'Amount', 'Losses' as 'TheType'  ";
$qry = $qry . "     FROM Master WHERE user_id = $user_id AND result = 'Loss'  ";
$qry = $qry . "     GROUP BY sport  ";
$qry = $qry . " ) as qry GROUP BY sport ORDER BY (sum(CASE WHEN TheType = 'Wins' THEN Amount ElSE 0 END)-sum(CASE WHEN TheType = 'Losses' THEN Amount ElSE 0 END)) DESC ";
	$q = mysqli_query($conn, $qry);
	$rowCount = $q->num_rows;
	
	if ($rowCount != 0) {
		
		$sportArr = "[";
		$profitArr = "[";
		$count = 0;

		while($rs = mysqli_fetch_array($q)) {

			$profitArr = $profitArr . number_format($rs["Total"], 2, '.', '');
			$sportArr = $sportArr . "'" .  $rs["sport"] . $year . "'";
			$pNum = $rs["Total"];
			if ($rowCount != $count+1) {	
				$sportArr = $sportArr . ", ";
				$profitArr = $profitArr . ", ";
			}

			$count = $count + 1;
		}

		$profitArr = $profitArr . "] ";
		$sportArr = $sportArr . "] ";
		$theVal[0] = $sportArr;
		$theVal[1] = $profitArr;

	}else{
		$theVal[0] = "[]";
		$theVal[1] = "[]";
	}

	return $theVal;
}


function dec2Frac($dec){
    $whole = floor ( $dec );
    $decimal = $dec - $whole;
    $leastCommonDenom = 48; // 16 * 3;
    $denominators = array (2, 3, 4, 8, 16, 24, 48 );
    $roundedDecimal = round ( $decimal * $leastCommonDenom ) / $leastCommonDenom;
    if ($roundedDecimal == 0)
	    return $whole;
    if ($roundedDecimal == 1)
	    return $whole + 1;
    foreach ( $denominators as $d ) {
	    if ($roundedDecimal * $d == floor ( $roundedDecimal * $d )) {
		    $denom = $d;
		    break;
	    }
    }
    if ($whole > 0 ) {
    	$whole = "+" . $whole;
    }
    //return ($whole == 0 ? '' : $whole) . " " . ($roundedDecimal * $denom) . "/" . $denom;
    $val = $dec;
    if ($dec > 0 ) {
    	$val = "+" . $val;
    }
    return $val;
}


function GetSiteRating(){
	global $conn;
	$qry = " SELECT AVG(rating) as 'AvgRating' FROM UserTBL ";
	$q = mysqli_query($conn, $qry);
	$rs = mysqli_fetch_array($q);
	return $rs["AvgRating"];
}

function GetTeamLogo($league, $ESPN_ID, $Abbr){

	if ($league == 'NCAA') {
  		$code = $ESPN_ID;
  	}else{
  		$code = $Abbr;
  	}

	$str = "http://a.espncdn.com/combiner/i?img=/i/teamlogos/". $league . "/500/". $code . ".png";
	return $str;

}


function GetTeamLogoByID($team_ID){
	global $conn;
	$qry = "SELECT Abbr, ESPN_ID, League_Type, City, Nickname FROM Team t INNER JOIN Sport s ON t.Sport_ID = s.ID WHERE t.ID = $team_ID;";
	$q = mysqli_query($conn, $qry);
    $rs = mysqli_fetch_array($q);


	if ($rs["League_Type"] == 'NCAA') {
  		$code = $rs["ESPN_ID"];
  	}else{
  		$code = $rs["Abbr"];
  	}

	$img = "http://a.espncdn.com/combiner/i?img=/i/teamlogos/". $rs["League_Type"] . "/500/". $code . ".png";

    $arr = array($img, $rs["City"], $rs["Nickname"]);

	return $arr;
}


function GetLeagueLogo($league){

	switch ($league) {
      case "NCAAF":
         $str = "http://sportsandentertainmentnashville.com/wp-content/uploads/2014/10/NCAA_Football_Logo.png";
          break;
      case "NCAAB":
        $str = "http://elitesportsadvisor.com/wp-content/uploads/2014/11/NCAA-Basketball-Logo-300x294.png";
          break;
      default:
         $str = "http://a.espncdn.com/combiner/i?img=/i/teamlogos/leagues/500/" . $league . ".png";

  }

	return $str;

}

















function convert_number_to_words($number) {
    
    $hyphen      = '-';
    $conjunction = ' and ';
    $separator   = ', ';
    $negative    = 'negative ';
    $decimal     = ' point ';
    $dictionary  = array(
        0                   => 'zero',
        1                   => 'one',
        2                   => 'two',
        3                   => 'three',
        4                   => 'four',
        5                   => 'five',
        6                   => 'six',
        7                   => 'seven',
        8                   => 'eight',
        9                   => 'nine',
        10                  => 'ten',
        11                  => 'eleven',
        12                  => 'twelve',
        13                  => 'thirteen',
        14                  => 'fourteen',
        15                  => 'fifteen',
        16                  => 'sixteen',
        17                  => 'seventeen',
        18                  => 'eighteen',
        19                  => 'nineteen',
        20                  => 'twenty',
        30                  => 'thirty',
        40                  => 'fourty',
        50                  => 'fifty',
        60                  => 'sixty',
        70                  => 'seventy',
        80                  => 'eighty',
        90                  => 'ninety',
        100                 => 'hundred',
        1000                => 'thousand',
        1000000             => 'million',
        1000000000          => 'billion',
        1000000000000       => 'trillion',
        1000000000000000    => 'quadrillion',
        1000000000000000000 => 'quintillion'
    );
    
    if (!is_numeric($number)) {
        return false;
    }
    
    if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
        // overflow
        trigger_error(
            'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
            E_USER_WARNING
        );
        return false;
    }

    if ($number < 0) {
        return $negative . convert_number_to_words(abs($number));
    }
    
    $string = $fraction = null;
    
    if (strpos($number, '.') !== false) {
        list($number, $fraction) = explode('.', $number);
    }
    
    switch (true) {
        case $number < 21:
            $string = $dictionary[$number];
            break;
        case $number < 100:
            $tens   = ((int) ($number / 10)) * 10;
            $units  = $number % 10;
            $string = $dictionary[$tens];
            if ($units) {
                $string .= $hyphen . $dictionary[$units];
            }
            break;
        case $number < 1000:
            $hundreds  = $number / 100;
            $remainder = $number % 100;
            $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
            if ($remainder) {
                $string .= $conjunction . convert_number_to_words($remainder);
            }
            break;
        default:
            $baseUnit = pow(1000, floor(log($number, 1000)));
            $numBaseUnits = (int) ($number / $baseUnit);
            $remainder = $number % $baseUnit;
            $string = convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
            if ($remainder) {
                $string .= $remainder < 100 ? $conjunction : $separator;
                $string .= convert_number_to_words($remainder);
            }
            break;
    }
    
    if (null !== $fraction && is_numeric($fraction)) {
        $string .= $decimal;
        $words = array();
        foreach (str_split((string) $fraction) as $number) {
            $words[] = $dictionary[$number];
        }
        $string .= implode(' ', $words);
    }
    
    return $string;
}


?>