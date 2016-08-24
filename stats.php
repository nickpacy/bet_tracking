<?php 
    $pageTitle = "My Bets - Bet Tracking";
    include("includes/header.php"); ?>

    <link rel="stylesheet" type="text/css" href="css/header.css">
    <link rel="stylesheet" type="text/css" href="css/progress_bar.css">


  <script>
  $(document)
    .ready(function() {

  


        $.get("includes/stats_ajax.php",
           {GetMostBetOn: <?php print $user_id; ?>},
           function(result){
            //$("#MostBetOnAjax").removeClass("ui active inline loader");
            $("#MostBetOnAjax").html(result);
          });
        $.get("includes/stats_ajax.php",
           {GetLeagueStats: <?php print $user_id; ?>},
           function(result){
            //$("#MostBetOnAjax").removeClass("ui active inline loader");
            $("#LeagueStatsAjax").removeClass("ui active inline loader");
            $("#LeagueStatsAjax").html(result);
          });

        $.get("includes/stats_ajax.php",
           {GetOverallProfit: <?php print $user_id; ?>},
           function(result){
            //$("#MostBetOnAjax").removeClass("ui active inline loader");
            $("#OverallProfitAjax").removeClass("ui active inline loader");
            $("#OverallProfitAjax").html(result);
          });

       GetTeamProfits("");


    });


      function GetTeamProfits($limit){
        $.get("includes/stats_ajax.php",
           {GetTeamProfits: <?php print $user_id; ?>, limit: $limit},
           function(result){
            $("#profitByTeamAjax").removeClass("ui active inline loader");
            $("#profitByTeamAjax").html(result);
          });
      }
  </script>
</head>
<body class="pushable">

<?php require("includes/incl_nav.php"); ?>

    <div class="ui text container">
    </div>

  </div>





	

<div class="ui text">
  <div class="ui vertical stripe segment">
    <div class="ui stackable grid container">
      <div class="row">
        <div class="ui four wide column">
              <div class="ui statistic">
                <div class="value">
                  <?php
                    $qry = "SELECT AVG(Bet_Amount) AS TheCount FROM Bet WHERE User_ID = $user_id";
                    $q = mysqli_query($conn, $qry);
                    $rs = mysqli_fetch_array($q);
                    echo money_format('$%i', ($rs["TheCount"])); ?>
                </div>
                <div class="label">
                  Average Bet Amount
                </div>
              </div>
        </div>
        <div class="ui four wide column">
              <div class="ui statistic">
                <div class="label">
                  Most Bet On Team
                </div>
                <div class="text value" id="MostBetOnAjax">
                </div>
              </div>
        </div>
        <div class="ui four wide column">
              <div class="ui statistic">
              <?php $qry = "SELECT COUNT(ID) AS TheCount FROM Bet WHERE User_ID = $user_id";
                    $q = mysqli_query($conn, $qry);
                    $rs = mysqli_fetch_array($q); ?>
                <div class="value">
                  <i class="book icon"></i> <?php echo $rs["TheCount"]; ?>
                </div>
                <div class="label">
                  Bets Made
                </div>
              </div>
        </div>
        <div class="ui four wide column">
             <div class="ui statistic">
              <?php $qry = "SELECT *
                            FROM vBetHeader
                            WHERE Result = 'Win' AND User_ID = $user_id
                            ORDER BY To_Win_Amount DESC, Game_Date ASC LIMIT 1";
                    $q = mysqli_query($conn, $qry);
                    $rs = mysqli_fetch_array($q); ?>
                <div class="label">
                  Biggest Game Payout
                </div>
                <div class="text value">
                  
                  <?php 

                  if ($rs["Bet_Choice"] == "Home") {
                    echo "<small>" . $rs["Home_City"] . "</small><br>" . $rs["Home_Nickname"];
                  }elseif ($rs["Bet_Choice"] == "Away") {
                    echo "<small>" . $rs["Away_City"] . "</small><br>" . $rs["Away_Nickname"];
                  }elseif ($rs["Bet_Choice"] == "Over") {
                    echo "<small>" . $rs["Away_Abbr"] . " vs " . $rs["Home_Abbr"] . "</small><br>(Over)";
                  }elseif ($rs["Bet_Choice"] == "Under") {
                    echo "<small>" . $rs["Away_Abbr"] . " vs " . $rs["Home_Abbr"] . "</small><br>(Under)";
                  }
                  echo "<br>" .  money_format('$%i', ($rs["To_Win_Amount"])); ?>
                </div>
              </div>
        </div>
      </div>
      <div class="row">
        <div class="ui four wide column">
          <h3 class="ui header">Profit By Team</h3>
          <div id="profitByTeamAjax" class="ui active inline loader"></div>
        </div>
        <div class="ui four wide column">
          <h3 class="ui header">By League Statistics</h3>
          <div id="LeagueStatsAjax" class="ui active inline loader"></div>
          
        </div>
        <div class="ui four wide column">
          <div id="OverallProfitAjax" class="ui active inline loader"></div>
        </div>
        <div class="ui four wide column">
        </div>
      </div>
    </div>
  </div>
</div>





<!--
<div class="container">
	<div class="row">
		<div class="col-xs-12">
			<div class="jumbotron">
				<h2 style="color:#fff;">My Account</h2>
			</div>
		</div>
	<div class="row">
		<div class="col-sm-12 col-md-6">
			<?php
				$qry = "SELECT friend_id, user_name, user_email, count(Master.user_id) AS 'NumBets' FROM `Friends` ";
				$qry = $qry . " INNER JOIN UserTBL ON UserTBL.user_id = Friends.friend_id ";
				$qry = $qry . " LEFT JOIN Master ON Friends.friend_id = Master.user_id";
				$qry = $qry . " WHERE Friends.user_id = $user_id";
				$qry = $qry . " GROUP BY friend_id, user_name, user_email";
				$qry = $qry . " ORDER BY count(Master.user_id) DESC;";
				$q = mysqli_query($conn, $qry);
				$rowCount = $q->num_rows; ?>
				<h2 class="page-header">Friend List</h2>
				<div class="form-group form-inline">
					<input type="text" class="form-control" id="addFriend" placeholder="Add Friend" onkeyup="CheckFriendTable($(this).val());;" />
					<input type="hidden" id="addFriendID" />
					<button class="btn btn-success" onclick="AddFriend();" id="addFriendbtn" disabled><span class="glyphicon glyphicon-plus"></span></button>
				</div>
				<div id="friendTbl">
				<?php if ($rowCount != 0) { ?>
			<table class="table table-striped">
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
						<td><a href="#" onclick="GetFriendResults('<?php print $rs["friend_id"]; ?>'); return false;">View Bets</a></td>
					</tr>
				<?php } ?>
				</tbody>
			</table>

			<? } ?>
			</div>
		</div>
		<div class="col-sm-12 col-md-6">
			<div id="friend_info" ></div>
		</div>
	</div>
	</div>
</div>
-->
<script>
	function CheckFriendTable(str){

	      if (str.length > 1) {
	          
	          $.getJSON(ajaxPage,
	             {_CheckFriendList: str},
	             function(result){
	              
	             	if (result.check == "No User") {
	             		$("#addFriendID").val('');
	             		$("#addFriendbtn").attr("disabled", true);
	             	}else{
	             		var friend_id = result.check;
	             		$("#addFriendID").val(friend_id);
	             		$("#addFriendbtn").attr("disabled", false);
	             	};

	             });
	      }

	}

	function AddFriend(){
		if ((!$("#addFriendbtn").attr("disabled")) && ($("#addFriendID").val() != "")) {
			var fid = $("#addFriendID").val();
			 $.post(ajaxPage,
			        {_AddFriend: fid, user_id: <?php echo $user_id ?>},
			          function(result){
			      $("#friendTbl").html(result);
			  });
			
		};
	}


	function GetFriendResults(friend_id){

		$.get(ajaxPage,
	             {_GetFriendResults: friend_id},
	             function(result){
	             	$("#friend_info").html(result);
	     });

	}

</script>

<?php
require('includes/footer.php');
?>