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

if ($_REQUEST['_LoadBetProfit'] != "") {

	$user_id = 		$_REQUEST['_LoadBetProfit'];


	$theDate = date("Y") . "-" . date("m") . "-1";
	//$theDate = "2015-11-1";

	$dteSQL = "str_to_date( concat( year( Game_Date ) , '-', month( Game_Date ) , '-1' ) , '%Y-%m-%d' )";


	$qry = 		  " SELECT (sum(CASE WHEN TheType = 'Wins' THEN Amount ElSE 0 END)-sum(CASE WHEN TheType = 'Losses' THEN Amount ElSE 0 END)) as 'Total' ";
	$qry = $qry . " FROM( ";
	$qry = $qry . " SELECT sum(To_Win_amount) as 'Amount', 'Wins' as 'TheType' FROM Bet WHERE user_id = $user_id AND result_id = 1 AND " . $dteSQL . " = '$theDate'";
	$qry = $qry . " UNION ";
	$qry = $qry . " SELECT sum(Bet_Amount) as 'Amount', 'Losses' as 'TheType' FROM Bet WHERE user_id = $user_id AND result_id = 2 AND " . $dteSQL . " = '$theDate'";
	$qry = $qry . "    ) as qry";
	

	$q = mysqli_query($conn, $qry);
	$rowCount = $q->num_rows;
	if ($rowCount != 0) {
		$rs = mysqli_fetch_array($q);
		$totalProfit = $rs["Total"];
		if ($totalProfit == 0) {
			$color = "black";
			$sign = "$";
			$term = "Monthly Profit";
			$paragraph = "Congratulations. You have not won or lost any money this month.";
		}elseif ($totalProfit > 0) {
			$color = "green";
			$totalProfit = abs($totalProfit);
			$term = "Monthly Profit";
			$sign = "+$";
			$paragraph = "Woah. You are up this month.";
		}else{
			$color = "red";
			$totalProfit = abs($totalProfit);
			$term = "Monthly Loss";
			$sign = "-$";
			$paragraph = "Ooh! Looks like you need to bet some more.";
		}



		  $htmlCard = ' <div class="ui card">';
          $htmlCard = $htmlCard . '   <div class="content">';
          $htmlCard = $htmlCard . '     <p class="header" align="center"><i class="massive ' . $color . ' dollar icon"></i></p>';
          $htmlCard = $htmlCard . '     <div class="description" align="center">' . getdate()[month] . ' ' . $term ;
          $htmlCard = $htmlCard . '<h3 class="ui header">' . $sign . $totalProfit . '</h3></div>';
          $htmlCard = $htmlCard . '   </div>';
          $htmlCard = $htmlCard . ' </div>';



		echo json_encode(array("paragraph" => $paragraph, "htmlCard" => $htmlCard));

	} //end if rowCount
} //end function


if ($_REQUEST['_LoadUpcomingTable'] != "") {

	$user_id = 		$_REQUEST['_LoadUpcomingTable'];

	$qry = "SELECT * FROM vBetHeader WHERE Result = 'Upcoming' AND user_id = $user_id  ORDER BY Game_Date ASC LIMIT 5;"; //AND Game_Date >= CURDATE()
	$q = mysqli_query($conn, $qry);
	$rowCount = $q->num_rows;
	
				?>
				<table class="ui striped table">
					<thead>
						<tr>
							<th>Date</th>
							<th>Game</th>
							<th>Spread</th>
							<th>Bet</th>
							<th>Change</th>
						</tr>
					</thead>
					<tbody>
					<?php if ($rowCount != 0) {
						while ($rs = mysqli_fetch_array($q)) { ?>
							<tr>
								<td align="left"><?php print date('m/d/y',strtotime($rs["Game_Date"])); ?></td>
								<td align="left">
								<?php 	if ($rs['Bet_Choice'] == "Home") { 
											echo $rs["Away_Abbr"] . " vs. <b>" . $rs["Home_Abbr"] . "</b>"; 
										}else{
											echo "<b>" . $rs["Away_Abbr"] . "</b> vs. " . $rs["Home_Abbr"]; 
										}?>
								</td>
								<td align="left">
								<?php if ($rs["Spread"] == 0) {
										print "Pick";
								}else{
									print $rs["Spread"];
									} ?>
								</td>
								<td align="left">$<?php print $rs["Bet_Amount"]; ?></td>
								<td id="newResult_<?php print $rs["ID"]; ?>"><i class="edit icon quickUpdate" style="cursor:pointer;" onclick="$('#quick_result').val('<?php print $rs["ID"]; ?>');"></i></td>
							</tr>
							<?php
						}
					}else{
						echo "<tr><td colspan='5'><div class='ui red message'>No Upcoming Games</div></td></tr>";
					}
					?>
					</tbody>
					
				</table> 


<div id="quickResult" class="ui flowing popup top left transition hidden">
  <div class="ui three column divided center aligned grid">
    <div class="column">
      <button class="ui mini button green" onclick="UpdateGameResult('1');">Win</button>
    </div>
    <div class="column">
      <button class="ui mini button red" onclick="UpdateGameResult('2');">Loss</button>
    </div>
    <div class="column">
      <button class="ui mini button blue" onclick="UpdateGameResult('4');">Push</button>
    </div>
  	<input type="hidden" name="quick_result" id="quick_result" />
  </div>
</div>

<script>
$('.quickUpdate').popup({
    popup : $('#quickResult'),
    position : 'right center',
    on    : 'click'
  });

function UpdateGameResult(result){

	var bet_id = $("#quick_result").val();

	if (bet_id != "") {

		$.post("includes/index_ajax.php",
               {_UpdateGameResult: bet_id, result: result},
               function(value){
               	$("#newResult_" + bet_id).html(result);
               	$('.quickUpdate').popup('hide');
        });
	};

}

</script>


<?php }



if ($_REQUEST['_UpdateGameResult'] != "") {

	$bet_id = $_REQUEST['_UpdateGameResult'];
	$result = $_REQUEST['result'];

	$qry = "UPDATE Bet SET Result_ID = $result WHERE ID = $bet_id;";
	if (mysqli_query($conn, $qry)) {
			
			if ($result == "1") {
				echo "Win";
			}elseif ($result == "2") {
				echo "Loss";
			}elseif ($result == "4") {
				echo "Push";
			}else{
				echo "Error";
			}
	} else {
		echo $qry;
	}


}


if ($_REQUEST['_UserRating'] != "") {

	$rating = $_REQUEST['_UserRating'];
	$user_id = $_SESSION['user_id'];

	$qry = "UPDATE UserTBL SET rating = $rating WHERE user_id = $user_id;";
	if (mysqli_query($conn, $qry)) {
			echo "<p>Thank you for your feedback</p>";
	} else {
		echo $qry;
	}


}


if ($_REQUEST['_LoadSlidesData'] != "") {

	$user_id = $_SESSION['user_id'];

	$qry = "SELECT * FROM vBetHeader WHERE Result = 'Win' AND User_ID = $user_id ORDER BY Game_Date DESC";
	
	$q = mysqli_query($conn, $qry);
	$rs = mysqli_fetch_array($q);

	$s1 = "Last Win: ";
	if ($rs["Bet_Choice"] == "Home") {
		$s1 = $s1 . $rs["Away_Abbr"] . " vs <b>" . $rs["Home_Abbr"] . "</b>";
	}elseif ($rs["Bet_Choice"] == "Away") {
		$s1 = $s1 . "<b>" . $rs["Away_Abbr"] . "</b> vs " . $rs["Home_Abbr"];
	}elseif ($rs["Bet_Choice"] == "Over") {
		$s1 = $s1 . $rs["Away_Abbr"] . " vs " . $rs["Home_Abbr"] . " (Over)";
	}elseif ($rs["Bet_Choice"] == "Under") {
		$s1 = $s1 . $rs["Away_Abbr"] . " vs " . $rs["Home_Abbr"] . " (Under)";
	}
	$s1 = $s1 . "  " .  money_format('$%i', ($rs["To_Win_Amount"]));

?>

<div class="sides">
	<div class="ui header side slider" id="slider1"><?php echo $s1; ?></div>
	<div class="ui header side slider" id="slider2"><a href="graphs">View Graph Trends</a></div>
	<div class="ui header side slider" id="slider3">This is the last side</div>
</div>

<script>
	
$(document).ready(function() {

	// hide all quotes except the first
	$('.slider').hide().eq(0).show();
	var pause = 10000;

	var sliders= $('.slider');
	var count = sliders.length;
	var i = 0;

	setTimeout(transition,pause);


	function transition(){
	    sliders.eq(i).transition('fade'); // goes out opposite

	    if(++i>=count){
	        i=0;
	    }

	    sliders.eq(i).transition('fade'); //comes in
	    
	    setTimeout(transition, pause);
	}
});





</script>

<?php } ?>








