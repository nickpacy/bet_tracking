<?php 
if ($_SERVER["SERVER_NAME"] == "localhost") {
	$user = 'root';
	$password = 'root';
	$database = 'nick_db';
	$host = 'localhost';
	$port = 8889;

	
}else{
	$database	= "bet_tracking";
	$host 		= "localhost";
	$user		= "webpacy";
	$password	= "Nichola$9";
	$port		= 3306;
}
	

	$conn = mysqli_connect ($host, $user, $password, $database) or die ("Error connecting to the database");

include("pacy_fx.php");

if ($_REQUEST["GetTeamProfits"] != ""){

	$user_id = $_REQUEST["GetTeamProfits"];
	$limit = $_REQUEST["limit"];

	$qry = "SELECT League, City, Nickname , SUM(Profit) AS Profit, Abbr, ESPN_ID, League_Type
		FROM (
		  SELECT at.Abbr, at.ESPN_ID, at.City, at.Nickname, s.League, s.League_Type,
		    (SUM(CASE WHEN Result_ID = 1 THEN To_Win_Amount ELSE 0 END)-
		    SUM(CASE WHEN Result_ID = 2 THEN Bet_Amount ELSE 0 END)) AS Profit
		  FROM Bet b
		    INNER JOIN Team at ON b.Away_Team_ID = at.ID
		    INNER JOIN Team ht ON b.Home_Team_ID = ht.ID
		    INNER JOIN Sport s ON b.Sport_ID = s.ID
		  WHERE user_id = $user_id AND Bet_Choice_ID = 2
		  GROUP BY at.Abbr, at.ESPN_ID, at.City, at.Nickname, s.League, s.League_Type

		  UNION ALL

		  SELECT ht.Abbr, ht.ESPN_ID, ht.City, ht.Nickname, s.League, s.League_Type,
		    (SUM(CASE WHEN Result_ID = 1 THEN To_Win_Amount ELSE 0 END)-
		    SUM(CASE WHEN Result_ID = 2 THEN Bet_Amount ELSE 0 END)) AS Profit
		  FROM Bet b
		    INNER JOIN Team at ON b.Away_Team_ID = at.ID
		    INNER JOIN Team ht ON b.Home_Team_ID = ht.ID
		    INNER JOIN Sport s ON b.Sport_ID = s.ID
		  WHERE user_id = $user_id AND Bet_Choice_ID = 1
		  GROUP BY ht.Abbr, ht.ESPN_ID, ht.City, ht.Nickname, s.League, s.League_Type
		) q
		GROUP BY League, City, Nickname, Abbr, ESPN_ID, League_Type
		ORDER BY Profit DESC ";

		if ($limit == "") {
			$qry = $qry . " LIMIT 5";
		}

		$q = mysqli_query($conn, $qry);

?>

<table class="ui very basic collapsing celled table">
    <thead>
      <tr>
        <th>Team</th>
        <th>Profit</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($rs = mysqli_fetch_array($q)) { ?>
      <tr>
        <td>
          <h4 class="ui image header">
            <img src="<?php echo GetTeamLogo($rs["League_Type"], $rs["ESPN_ID"], $rs["Abbr"]); ?>" class="ui mini rounded image">
            <div class="content">
              <?php echo $rs["City"]; ?>
              <div class="sub header"><?php echo $rs["Nickname"]; ?>&nbsp;<small>(<?php echo $rs["League"]; ?>)</small></div>
          </div>
        </h4></td>
        <td><?php echo money_format('$%i', ($rs["Profit"])); ?></td>
      </tr>
      <?php } ?>
    </tbody>
  </table>
  	<?php if ($limit == "") { ?>
 	 <a href="#" onclick="GetTeamProfits('1');$(this).hide();return false;">View All</a>
	<?php } ?>

<?php } 

if ($_REQUEST["GetMostBetOn"] != ""){

	$user_id = $_REQUEST["GetMostBetOn"];

	$qry = "SELECT League, City, Nickname , SUM(TheCount) AS NumBets
			FROM (
			  SELECT at.City, at.Nickname, s.League, COUNT(b.ID) AS TheCount
			  FROM Bet b
			    INNER JOIN Team at ON b.Away_Team_ID = at.ID
			    INNER JOIN Team ht ON b.Home_Team_ID = ht.ID
			    INNER JOIN Sport s ON b.Sport_ID = s.ID
			  WHERE user_id = $user_id AND Bet_Choice_ID = 2
			  GROUP BY at.City, at.Nickname, s.League

			  UNION ALL

			  SELECT ht.City, ht.Nickname, s.League, COUNT(b.ID) AS TheCount
			  FROM Bet b
			    INNER JOIN Team at ON b.Away_Team_ID = at.ID
			    INNER JOIN Team ht ON b.Home_Team_ID = ht.ID
			    INNER JOIN Sport s ON b.Sport_ID = s.ID
			  WHERE user_id = $user_id AND Bet_Choice_ID = 1
			  GROUP BY ht.City, ht.Nickname, s.League
			) q
			GROUP BY League, City, Nickname
			ORDER BY NumBets DESC LIMIT 1";

	$q = mysqli_query($conn, $qry);
	$rs = mysqli_fetch_array($q);
	echo $rs["City"] . "<br>" . $rs["Nickname"];

}

if ($_REQUEST["GetLeagueStats"] != ""){

	$user_id = $_REQUEST["GetLeagueStats"];

	$qry = "SELECT League,
				SUM(Wins) AS Wins,
				SUM(Losses) AS Losses,
				(SUM(Wins) - SUM(Losses)) AS Diff,
				SUM(Profit) AS Profit
			FROM(
			SELECT League, COUNT(b.ID) AS Wins, 0 AS Losses, SUM(To_Win_Amount) AS Profit
			FROM Bet b
			INNER JOIN Sport s ON b.Sport_ID = s.ID
			WHERE Result_ID = 1 AND User_ID = $user_id
			GROUP BY League

			UNION ALL

			SELECT League, 0 AS Wins, COUNT(b.ID) AS Losses, SUM(Bet_Amount * -1) AS Profit
			FROM Bet b
			INNER JOIN Sport s ON b.Sport_ID = s.ID
			WHERE Result_ID = 2 AND User_ID = $user_id
			GROUP BY League
			)q
			GROUP BY League
			ORDER BY Wins DESC";

	$q = mysqli_query($conn, $qry);

?>
<table class="ui very basic single line celled table">
    <thead>
      <tr>
        <th>League</th>
        <th>Wins</th>
        <th>Losses</th>
        <th>Diff</th>
        <th>Profit</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($rs = mysqli_fetch_array($q)) { ?>
      <tr>
        <td>
          <h4 class="ui image header">
            <img src="<?php echo GetLeagueLogo($rs["League"]); ?>" class="ui mini rounded image">
            <!-- <div class="content">
              <?php echo $rs["League"]; ?>
          </div> -->
        </h4></td>
        <td><?php echo $rs["Wins"]; ?></td>
        <td><?php echo $rs["Losses"]; ?></td>
        <td><?php 

        if ($rs["Diff"] > 0) {
        	echo "+";
        }

        echo $rs["Diff"]; ?></td>
        <td><?php echo money_format('$%i', ($rs["Profit"])); ?></td>
      </tr>
      <?php } ?>
    </tbody>
  </table>

<?php } 

if ($_REQUEST["GetOverallProfit"] != ""){

	$user_id = $_REQUEST["GetOverallProfit"];

		$qry = "SELECT
		    (SUM(CASE WHEN Result_ID = 1 THEN To_Win_Amount ELSE 0 END)-
		    SUM(CASE WHEN Result_ID = 2 THEN Bet_Amount ELSE 0 END)) AS Profit
		  FROM Bet b
		  WHERE user_id = $user_id";

		$q = mysqli_query($conn, $qry);
		$rs = mysqli_fetch_array($q);

		$Profit = $rs["Profit"];

		if ($Profit >= 0) {
			$color = "green";
			$sign = "+$";
		}else{
			$color = "red";
			$sign = "-$";
			$Profit = abs($Profit);
		}

?>

	<div class="ui card">
	  <div class="content center aligned">
	  <h1 class="ui header"><i class="dollar massive icon" style="font-size:70px;color:<?php echo $color; ?>"></i></h1>
	    <a class="header"><?php echo $sign . $Profit; ?></a>
	    <div class="description">
	      Net Income
	    </div>
	  </div>
	</div>

<?php } ?>