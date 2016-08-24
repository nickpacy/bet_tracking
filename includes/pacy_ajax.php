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

	if ($_REQUEST['_GetTeams'] != "") {
		$Sport_ID = $_REQUEST['_GetTeams'];
		$qry = "SELECT *, t.ID AS TeamID FROM Team t INNER JOIN Sport s ON t.Sport_ID = s.ID WHERE s.ID = '$Sport_ID' ORDER BY City";
		$q = mysqli_query($conn, $qry);
		$rowCount = $q->num_rows;
			if ($rowCount != 0) { ?>
		<div class="field">
			<label class="betLbl" for="away_team">Away Team:</label>
			<select class="ui dropdown" name="away_team" id="away_team" onchange="ChangeHomeAway('Away',$(this).val(),$(this).find('option:selected').text());">
			<option value="">-- Select Away Team --</option>
			<?php  
				while($rs = mysqli_fetch_array($q)) { ?>
				<option value="<?php print $rs["TeamID"]; ?>"><?php print $rs["City"] . " " . $rs["Nickname"]; ?></option>
			<?php } ?>
			</select>
		</div>
		<p align="center">
		<span id="Away_thumbnail"></span>
		vs.
		<span id="Home_thumbnail"></span>
		</p>
		<div class="field">
			<label class="betLbl" for="home_team">Home Team:</label>
			<select class="ui dropdown" name="home_team" id="home_team" onchange="ChangeHomeAway('Home',$(this).val(),$(this).find('option:selected').text());">
			<option value="">-- Select Home Team--</option>
			<?php 
			$q = mysqli_query($conn, $qry);
			 while($rs = mysqli_fetch_array($q)) { ?>
				<option value="<?php print $rs["TeamID"]; ?>"><?php print $rs["City"] . " " . $rs["Nickname"]; ?></option>
			<?php } ?>
			</select>
		</div>		

		<script>
			$('select.dropdown').dropdown();
		</script>




	<?php }else{ ?>

		<div class="form-group form-inline">
			<label class="betLbl" for="away_team">Away Team:</label>
			<input type="text" class="form-control" name="away_team" id="away_team" onchange="ChangeHomeAway('Away',$(this).val());" style="width:60%;" />
		</div>
		<p class="text-center">vs.</p>
	<div class="form-group form-inline">
			<label class="betLbl" for="home_team">Home Team:</label>
			<input type="text" class="form-control" name="home_team" id="home_team" onchange="ChangeHomeAway('Home',$(this).val());" style="width:60%;" />
		</div>



<?php	} //end if rowCount
} //end function

if ($_REQUEST['_GetThumbnail'] != "") {
		$team_id = $_REQUEST['_GetThumbnail'];
		$qry = "SELECT ESPN_ID, Abbr, League_type FROM Team t INNER JOIN Sport s ON t.Sport_ID = s.ID WHERE t.ID = '$team_id' LIMIT 1";
		$q = mysqli_query($conn, $qry);
		$rowCount = $q->num_rows;
			if ($rowCount != 0) { 

				$rs = mysqli_fetch_array($q); 

					if ($rs["League_type"] == 'NCAA') {
				  		$code = $rs["ESPN_ID"];
				  	}else{
				  		$code = $rs["Abbr"];
				  	}

				
			echo 'http://a.espncdn.com/combiner/i?img=/i/teamlogos/'. $rs["League_type"] . '/500/'. $code . '.png';
	} 
}



if ($_REQUEST['_CheckEmail'] != "") {
		$user_email = $_REQUEST['_CheckEmail'];

		$qry = "SELECT user_email FROM UserTBL WHERE user_email = '$user_email'";
		
		$q = mysqli_query($conn, $qry);
		$rowCount = $q->num_rows;
			if ($rowCount == 0) { 
				$check = "";
				$alertClass = "has-success";
				$ralertClass = "has-error";
			}else{
				$check = "There is already an account associated with this email address.";
				$alertClass = "has-error";
				$ralertClass = "has-success";
			} //end if rowCount
		echo json_encode(array("check" => $check, "alertClass" => $alertClass, "ralertClass" => $ralertClass));
} //end function


if ($_REQUEST['_CheckLogin'] != "") {
		$user_name = $_REQUEST['_CheckLogin'];

		$qry = "SELECT user_name FROM UserTBL WHERE user_name = '$user_name'";
		
		$q = mysqli_query($conn, $qry);
		$rowCount = $q->num_rows;
			if ($rowCount == 0) { 
				$check = "Username Availible";
			}else{
				$check = "Username Taken";
			} //end if rowCount
		echo json_encode(array("check" => $check));
} //end function


if ($_REQUEST['_CheckFriendList'] != "") {
		$user_name = $_REQUEST['_CheckFriendList'];

		$qry = "SELECT user_id, user_name FROM UserTBL WHERE user_name = '$user_name' OR user_email = '$user_name'";
		
		$q = mysqli_query($conn, $qry);
		$rowCount = $q->num_rows;
			if ($rowCount == 0) { 
				$check = "No User";
			}else{
				$rs = mysqli_fetch_array($q); 
				$check = $rs["user_id"];
			} //end if rowCount
		echo json_encode(array("check" => $check));
} //end function


if ($_REQUEST['_UpdateBet'] != "") {
		$id 	= $_REQUEST['_UpdateBet'];

		$sport				= $_POST['sport'];
		$home_team			= $_POST['home_team'];
		$away_team			= $_POST['away_team'];
		$bet_amount			= $_POST['bet_amount'];
		$bet_spread			= $_POST['bet_spread'];
		$odds				= $_POST['odds'];
		$to_win_amount		= $_POST['to_win_amount'];
		$result				= $_POST['result'];
		$start_time			= $_POST['start_time'];

		$start_time = date("Y-m-d H:i:s", strtotime($start_time));

		$bet_amount = trim($bet_amount, '$');
		$to_win_amount = trim($to_win_amount, '$');

		$qry = "UPDATE Master SET ";

		if (isset($sport)) { $qry = $qry . " sport = '$sport', ";	}
		if (isset($home_team)) { $qry = $qry . " home_team = '$home_team', ";	}
		if (isset($away_team)) { $qry = $qry . " away_team = '$away_team', ";	}
		if (isset($bet_amount)) { $qry = $qry . " bet_amount = $bet_amount, ";	}
		if (isset($bet_spread)) { $qry = $qry . " bet_spread = $bet_spread, ";	}
		if (isset($odds)) { $qry = $qry . " odds = $odds, ";	}
		if (isset($to_win_amount)) { $qry = $qry . " to_win_amount = $to_win_amount, ";	}
		if (isset($result)) { $qry = $qry . " result = '$result', ";	}
		if (isset($start_time)) { $qry = $qry . " start_time = '$start_time', ";	}

		$qry = rtrim($qry,', ');

		$qry = $qry . " WHERE id = $id ";

		
		if (mysqli_query($conn, $qry)) {
			echo "success";
		} else {
			echo "danger";
		}


} //end function




if ($_REQUEST['_GetBodavaData'] != "") {
		$str = $_REQUEST['_GetBodavaData'];
		$league = $_REQUEST['sport'];
		$last_published = $_REQUEST['last_published'];
		$header_league = $league;
		switch ($league) {
		    case "NFL":
		        $sport = 'football';
		        $league = "nfl-playoffs";
		        $defaultIMG = '';
		        break;
		    case "NHL":
		        $sport = 'hockey';
		        $league = "nhl-playoffs";
		        $defaultIMG = '';
		        break;
		    case "NBA":
		        $sport = 'basketball';
		        $league = "nba-playoffs";
		        $defaultIMG = 'http://content.sportslogos.net/logos/6/982/thumbs/2971.gif';
		        break;
		    case "MLB":
		        $sport = 'baseball';
		        $league = "mlb"; 
		        $defaultIMG = '';
		        break;
		    case "NCF":
		        $sport = 'football';
		        $league = "college";
		        $defaultIMG = 'http://sportsandentertainmentnashville.com/wp-content/uploads/2014/10/NCAA_Football_Logo.png';
		        break;
		    case "NCB":
		        $sport = 'basketball';
		        $league = "college-basketball";
		        $defaultIMG = 'http://elitesportsadvisor.com/wp-content/uploads/2014/11/NCAA-Basketball-Logo-300x294.png';
		        break;
		}

		$events = explode("###", $str);
		$lastDate = "";
		?>

		<div class="page-header">
			<h2><?php echo $header_league; ?> Games</h2>
			<p class="right">Last Updated <?php echo $last_published; ?></p>
			<div class="clear"></div>
		</div>
 		<div class="row">
<?php


		foreach ($events as $event) {
		    $data = explode("$$", $event);




			$game_date 				= $data[0];
			$teams 					= $data[1];
			$home_team_id 			= $data[2];
			$home_team_name 		= $data[3];
			$home_pointspread 		= $data[4];
			$home_moneyline 		= $data[5];
			$away_team_id 			= $data[6];
			$away_team_name 		= $data[7];
			$away_pointspread 		= $data[8];
			$away_moneyline 		= $data[9];
			$over 					= $data[10];
			$under 					= $data[11];
			$game_time 				= $data[12];


		    $qry = "SELECT * FROM Team t INNER JOIN Sport s ON t.Sport_ID = s.ID WHERE Bovada_ID = $home_team_id LIMIT 1";
		    $q = mysqli_query($conn, $qry);
		    $rowCount = $q->num_rows;
		    if ($rowCount != 0) {
		    	$rs = mysqli_fetch_array($q);
		    	$home_abbr = $rs["Abbr"];
		    	$home_thumbnail = GetTeamLogo($rs["League_Type"], $rs["ESPN_ID"], $rs["Abbr"]);
		    }else{
		    	$home_thumbnail = $defaultIMG;		    	
		    }

		    $qry = "SELECT * FROM Team t INNER JOIN Sport s ON t.Sport_ID = s.ID WHERE Bovada_ID = $away_team_id LIMIT 1";
		    $q = mysqli_query($conn, $qry);
		    $rowCount = $q->num_rows;
		    if ($rowCount != 0) {
		    	$rs = mysqli_fetch_array($q);
		    	$away_abbr = $rs["Abbr"];
		    	$away_thumbnail = GetTeamLogo($rs["League_Type"], $rs["ESPN_ID"], $rs["Abbr"]);
		    }else{
		    	$away_thumbnail = $defaultIMG;		    	
		    }

		    if ($home_pointspread == "undefined") {$home_pointspread = "Not Set";}
		    if ($away_pointspread == "undefined") {$away_pointspread = "Not Set";}
		    if ($home_moneyline == "undefined") {$home_moneyline = "Not Set";}
		    if ($away_moneyline == "undefined") {$away_moneyline = "Not Set";}
		    if ($over == "") {$over = "Not Set";}
		    if ($under == "") {$under = "Not Set";}else{$under = ("-" . trim($under,"+"));}



		    $bet_link = strtolower(str_replace(" ", "-", $away_team_name) . "-" . str_replace(" ", "-", $home_team_name) . "-" . date("Ymd", strtotime($game_date)));
		   
		   	 

		    ?>

<div class="ui items">

<?php 
	
	if ($lastDate != $game_date) { ?>

		<h3 class="ui horizontal divider header"><i class="calendar icon"></i>Games for <?php echo $game_date; ?></h3><br>

<?php

	}
	
 ?>
 			
 		<div class="item">
 			<div class="content">

				<table width="99%;">
					<tr class="mb20">
						<td colspan="4" align="center"><h3><?php print $game_date . " @ " . $game_time; ?></h3></td>
					</tr>
					<tr>
						<td></td>
						<td colspan="2" align="center"><img src="<?php print $away_thumbnail; ?>" alt="<?php print $away_abbr; ?>" width="75px;" style="padding:5px;" /></td>
						<td colspan="2" align="center"><img src="<?php print $home_thumbnail; ?>" alt="<?php print $home_abbr; ?>" width="75px;" style="padding:5px;" /></td>
					</tr>
					<tr>
						<td></td>
						<td colspan="2" align="center"><h4><?php print $away_team_name; ?></h4></td>
						<td colspan="2" align="center"><h4><?php print $home_team_name; ?></h4></td>
					</tr>
					<tr>
						<td>Pointspread</td>
						<td colspan="2" align="center"><p><?php print $away_pointspread; ?></p></td>
						<td colspan="2" align="center"><p><?php print $home_pointspread; ?></p></td>
					</tr>
					<tr>
						<td>Moneyline</td>
						<td colspan="2" align="center"><p><?php print $away_moneyline; ?></p></td>
						<td colspan="2" align="center"><p><?php print $home_moneyline; ?></p></td>
					</tr>
					<?php //if ($over != "" or $under != "" ) { ?>
						
					<tr>
						<td>Over/Under</td>
						<td colspan="2" align="center"><p><?php print $over; ?></p></td>
						<td colspan="2" align="center"><p><?php print $under; ?></p></td>
					</tr>
					<?php //} ?>
					<tr>
						<td colspan="4" align="center"><a href="https://sports.bovada.lv/<?php print $sport; ?>/<?php print strtolower($league); ?>/<?php print $bet_link; ?>" class="ui button orange">Bet This Game</a></td>
					</tr>
				</table>
			</div>
		</div>

		<div class="ui divider"></div>



		    <?php

		    $lastDate = $game_date;

		}

		?>
    </div>

		<?php

} 

if ($_REQUEST["_AddFriend"] != ""){

	$friend_id = $_POST["_AddFriend"];
	$user_id = $_POST["user_id"];

	$qry  = "INSERT INTO Friends (user_id, friend_id) VALUES ($user_id, $friend_id);";

		if (mysqli_query($conn, $qry)) {
			$alert = "green";
			$message = "Friend Added";
		} else {
			$alert = "red";
			$message = "Something went wrong.";
		}

		 $qry = "SELECT friend_id, user_name, user_email, count(Bet.user_id) AS 'NumBets' FROM `Friends` ";
        $qry = $qry . " INNER JOIN UserTBL ON UserTBL.user_id = Friends.friend_id ";
        $qry = $qry . " LEFT JOIN Bet ON Friends.friend_id = Bet.user_id";
        $qry = $qry . " WHERE Friends.user_id = $user_id";
        $qry = $qry . " GROUP BY friend_id, user_name, user_email";
        $qry = $qry . " ORDER BY count(Bet.user_id) DESC;";
		$q = mysqli_query($conn, $qry);
		$rowCount = $q->num_rows;

		if ($rowCount != 0) { ?>
			<div class="ui secondary inverted segment <?php echo $alert; ?>"><?php echo $message; ?></div>
			    <table class="ui single line table">
		          <thead>
		            <tr>
		              <th>Friend</th>
		              <th>Number of Bets</th>
		              <th></th>
		            </tr>
		          </thead>
		          <tbody>
		          <? while($rs = mysqli_fetch_array($q)) { 
		            ?>
		            <tr>
		              <td><?php print $rs["user_name"]; ?></td>
		              <td><?php print $rs["NumBets"]; ?></td>
		              <td><a href="#" onclick="GetFriendResults('<?php print $rs["friend_id"]; ?>'); return false;">View Bets&nbsp;<i class="book icon"></i></a></td>
		            </tr>
		          <?php } ?>
		          </tbody>
		        </table>

			<? }  

}

if ($_REQUEST["_GetFriendResults"] != "") {
			$friend_id = $_REQUEST["_GetFriendResults"];

			$qry = "SELECT * FROM UserTBL WHERE user_id = $friend_id;";
			$q = mysqli_query($conn, $qry);
			$rs = mysqli_fetch_array($q);
			$user_name = $rs["user_name"];

			$qry = "SELECT * FROM vBetHeader WHERE user_id = $friend_id ORDER BY Game_Date DESC";
			$q = mysqli_query($conn, $qry);
			$rowCount = $q->num_rows;

			?>
			<h2 class="ui header">Results for <?php print $user_name; ?></h2>
				<div class="ui stacked segment" style="background-image: url('images/subtle.png'); ">
			<div style="height: 400px; overflow: scroll;">
					
				<table class="ui small celled table compact selectable">
	            <thead>
	              <tr>
	                <th><a href="my_bets.php?sort=Result&asc=<?php echo $asc; ?>">Result</a></th>
	                <th><a href="my_bets.php?sort=sport&asc=<?php echo $asc; ?>">Sport</a></th>
	                <th><a href="my_bets.php?sort=away_team&asc=<?php echo $asc; ?>">Away Team</a></th>
	                <th><a href="my_bets.php?sort=home_team&asc=<?php echo $asc; ?>">Home Team</a></th>
	                <th><a href="my_bets.php?sort=bet_spread&asc=<?php echo $asc; ?>">Spread</a></th>
	                <th><a href="my_bets.php?sort=Odds&asc=<?php echo $asc; ?>">Odds</a></th>
	                <th><a href="my_bets.php?sort=Game_Date&asc=<?php echo $asc; ?>">Date</a></th>
	                <th>Edit</th>
	              </tr>
	            </thead>
	            <tbody>
	              <? while($rs = mysqli_fetch_array($q)) { 
	              ?>
	              <tr id="row_<?php echo $rs["ID"]; ?>" 
	              <?php if ($rs["Result"] == "Win") {
	                echo "class='positive'";
	              }elseif ($rs["Result"] == "Loss") {
	                echo "class='negative'";
	              }
	              ?>
	              >
	                <td id="Result-<?php echo $rs["ID"]; ?>"><?php echo $rs["Result"]; ?></td>
	                <td id="sport-<?php echo $rs["ID"]; ?>"><?php echo $rs["League"]; ?></td>
	                <td colspan="2" id="away_team-<?php echo $rs["ID"]; ?>">

	                <? if ($rs["Bet_Choice"] == "Away") {
	                  echo "<strong>" . $rs["Away_Team"] . "</strong>  @ " . $rs["Home_Team"];
	                }elseif ($rs["Bet_Choice"] == "Home") {
	                  echo $rs["Away_Team"] . " @ <strong>" . $rs["Home_Team"] . "</strong>";
	                }elseif ($rs["Bet_Choice"] == "Over") {
	                  echo $rs["Away_Team"] . " @ " . $rs["Home_Team"] . "<strong> (Over)</strong>";
	                }elseif ($rs["Bet_Choice"] == "Under") {
	                  echo $rs["Away_Team"] . " @ " . $rs["Home_Team"] . "<strong> (Under)</strong>";
	                }


	             ?>

	                </td>
	                <td class="editText" id="Spread-<?php echo $rs["ID"]; ?>"><?php 
	                if ($rs["Spread"] > 0 ) {
	                  echo "+" . ($rs["Spread"] + 0);
	                }else{
	                  echo $rs["Spread"] + 0; 
	                }?></td>
	                <td id="Odds-<?php echo $rs["ID"]; ?>"><?php 
	                if ($rs["Odds"] > 0 ) {
	                  echo "+" . ($rs["Odds"] + 0);
	                }else{
	                  echo $rs["Odds"] + 0; 
	                }?></td>
	                <td id="Game_Date-<?php echo $rs["ID"]; ?>"><?php echo date('m/d/y',strtotime($rs["Game_Date"])); ?></td>
	                <td id="td_edit-<?php echo $rs["ID"]; ?>"><i id="edit_<?php echo $rs["ID"]; ?>" onclick="window.location.href = 'edit_bet?bid=<?php echo $rs["ID"]; ?>';" class="editRow edit icon" style="cursor:pointer;color:red;"></i></td>
	              </tr>
	            <?php } ?>
	            </tbody>
	          </table>
				</div>
			</div>
<?php }

if ($_REQUEST["_EditData"] != ""){

	$id = $_REQUEST["_EditData"];


	echo json_encode(array("sport" => "NHL"));
}


if ($_POST["_GetTeamsList"] != ""){

	$league = $_POST["league"];

  
  $qry = "SELECT * FROM bet_tracking.Team INNER JOIN bet_tracking.Sport ON Team.Sport_ID = Sport.ID WHERE Sport.league = '$league' ORDER BY City";
  $q = mysqli_query($conn, $qry);
  $rowCount = $q->num_rows; 
  ?>

<div class="ui six stackable cards">
  <? 

  	$x = 0;

  	while($rs = mysqli_fetch_array($q)) {


	  	if ($rs["League_Type"] == 'NCAA') {
	  		$code = $rs["ESPN_ID"];
	  	}else{
	  		$code = $rs["Abbr"];
	  	}


	if($x %6 == 0 && $x != 0) { ?>
</div>
<div class="ui six stackable special cards">
<?php } ?>


    <div class="ui fluid card">
      <div class="image">
      	<img style="background-color: white;" src="<?php echo GetTeamLogo($rs["League_Type"], $rs["ESPN_ID"], $rs["Abbr"]); ?>" >
        <!-- <img src="http://a.espncdn.com/combiner/i?img=/i/teamlogos/<?php echo $rs["League_Type"]; ?>/500/<?php echo $code; ?>.png"> -->
      </div>
      <div class="content center aligned">
	      <div class="header"><h4><?php echo $rs["City"]; ?></h4></div>
	      <div class="header"><h3><?php echo $rs["Nickname"]; ?></h3></div>
  	  </div>
    </div>


    <?php
$x = $x + 1;

     } ?>

</div>

<?php
}

?>
