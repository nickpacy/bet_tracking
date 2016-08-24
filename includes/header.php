<?php include("scs_first.php"); ?>
<?php include("pacy_fx.php"); ?>

<!DOCTYPE HTML>
<html>
<head>
  <!-- Standard Meta -->
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
  <link rel="shortcut icon" href="../images/icon_016.png" type="image/x-icon">
  <link rel="icon" href="../images/icon_72.png" type="image/x-icon">


  <!-- Site Properties -->
  <title><?php print $pageTitle; ?></title>
  <link rel="stylesheet" type="text/css" href="../dist/semantic.min.css">

  <script src="/js/jquery_1_11_1.js"></script>
  <script src="/js/jquery.cookie.js"></script>
  <script src="../dist/semantic.min.js"></script>
  <script src="js/bet_tracking.js"></script>
  <script src="js/oddsConverter.js"></script>

<?php $pageURL = curPageURL();

	$domain = "bet_tracking/";

	$page = strpos($pageURL, $domain);//24
	
	$page = substr($pageURL, $page + strlen($domain));
	
  $user_id  = CheckLoggedIn($pageURL);
?>

<?php //if (!strpos($pageURL,'index.php') && !strpos($pageURL,'login.php') && !$page == "") { ?>

<!-- </head>
<body> -->

<? //} ?>

<?php include("add_bet.php"); ?>