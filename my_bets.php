<?php 
    $pageTitle = "My Bets - Bet Tracking";
    include("includes/header.php"); ?>

    <link rel="stylesheet" type="text/css" href="css/header.css">
    <link rel="stylesheet" type="text/css" href="css/progress_bar.css">

<?php
  $wins = GetResultNumber(1);
  $losses = GetResultNumber(2);
  $upcomings = GetResultNumber(3);
  $pushes = GetResultNumber(4);
  $total = $wins + $losses + $upcomings + $pushes;
        ?>


  <script>
  $(document)
    .ready(function() {

      $('.upcomingPopup').popup({
        position : 'top center',
        html : '<i class="horizontally flipped reply icon"></i><?php echo $upcomings; ?> Upcoming'
      });

      $('.pushPopup').popup({
        position : 'top center',
        html  : '<i class="exchange icon"></i><?php echo $pushes; ?> Push'
      });

    });
  </script>
</head>
<body class="pushable">
<?php require("includes/incl_nav.php"); ?>

    <div class="ui text container">
    </div>

  </div>

<div class="ui custom flowing popup transition hidden" style="min-width:170px;">
  <div class="ui middle aligned animated list">
    <div class="item">
      <div class="content">
        <div class="header"><a href="my_account"><i class="setting icon"></i>&nbsp;My Account</a></div>
      </div>
    </div>
    <div class="item">
      <div class="content">
        <div class="header"><a href="bet_action.php?logout=true"><i class="sign out icon"></i>&nbsp;Logout</a></div>
      </div>
    </div>
  </div>
</div>
<?php 

  $sort = $_REQUEST['sort'];
  $asc = $_REQUEST['asc'];
  if (!isset($asc)) {
    $asc = "asc";
  }
  if ($asc == "desc") {
    $asc = "asc";
  }else{
    $asc = "desc";
  }

  $qry = "SELECT * FROM vBetHeader WHERE user_id = $user_id ORDER BY ";

  switch ($sort) {
      case "sport":
          $qry = $qry . " League $asc";
          break;
      case "home_team":
        $qry = $qry . " Home_Team $asc";
          break;
      case "away_team":
          $qry = $qry . " Away_Team $asc";
          break;
      case "Bet_Amount":
          $qry = $qry . " Bet_Amount $asc";
          break;
      case "bet_spread":
          $qry = $qry . " Spread $asc";
          break;
      case "Odds":
          $qry = $qry . " Odds $asc";
          break;
      case "To_Win_Amount":
          $qry = $qry . " To_Win_Amount $asc";
          break;
      case "Result":
          $qry = $qry . " Result $asc";
          break;
      case "Game_Date":
          $qry = $qry . " Game_Date $asc";
          break;
      default:
          $qry = $qry . " Game_Date $asc";

  }


  $q = mysqli_query($conn, $qry);
  $rowCount = $q->num_rows; ?>





  <div class="ui vertical stripe segment">
    <div class="ui middle aligned stackable grid container">
      <div class="row">
        <div class="column">
          <h3 class="ui header"><i class="history icon"></i>Bet History for <?php print $_SESSION["user_name"]; ?></h3>
        </div>
      </div>
      <div class="row">
        <div class="column">
          <div class="ui massive green label"><?php echo $rowCount; ?> Bets</div>
        </div>
      </div>
      <div class="row">
        <div class="center aligned column">


          <div class="progress">
            <div class="progress-bar progress-bar-success " style="width: <?php echo ($wins/$total)*100; ?>%">
              <span><i class="trophy icon"></i><?php echo $wins ?> Wins</span>
            </div>
            <div class="progress-bar progress-bar-primary progress-bar-striped upcomingPopup" style="width: <?php echo ($upcomings/$total)*100; ?>%">
              <span><?php echo $upcomings ?> Upcoming</span>
            </div>
            <div class="progress-bar progress-bar-info progress-bar-striped pushPopup" style="width: <?php echo ($pushes/$total)*100; ?>%">
              <span><?php echo $pushes ?> Push</span>
            </div>
            <div class="progress-bar progress-bar-danger" style="width: <?php echo ($losses/$total)*100; ?>%">
              <span><i class="flag outline icon"></i><?php echo $losses ?> Losses</span>
            </div>
          </div>


          <table class="ui celled table compact selectable">
            <thead>
              <tr>
                <th><a href="my_bets.php?sort=Result&asc=<?php echo $asc; ?>">Result</a></th>
                <th><a href="my_bets.php?sort=sport&asc=<?php echo $asc; ?>">Sport</a></th>
                <th><a href="my_bets.php?sort=away_team&asc=<?php echo $asc; ?>">Away Team</a></th>
                <th><a href="my_bets.php?sort=home_team&asc=<?php echo $asc; ?>">Home Team</a></th>
                <th><a href="my_bets.php?sort=Bet_Amount&asc=<?php echo $asc; ?>">Bet Amount</a></th>
                <th><a href="my_bets.php?sort=bet_spread&asc=<?php echo $asc; ?>">Spread</a></th>
                <th><a href="my_bets.php?sort=Odds&asc=<?php echo $asc; ?>">Odds</a></th>
                <th><a href="my_bets.php?sort=To_Win_Amount&asc=<?php echo $asc; ?>">Payout</a></th>
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
                <td id="Bet_Amount-<?php echo $rs["ID"]; ?>"><?php echo money_format('$%i', ($rs["Bet_Amount"])); ?></td>
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
                <td id="To_Win_Amount-<?php echo $rs["ID"]; ?>"><?php echo money_format('$%i', ($rs["To_Win_Amount"])); ?></td>
                <td id="Game_Date-<?php echo $rs["ID"]; ?>"><?php echo date('m/d/y',strtotime($rs["Game_Date"])); ?></td>
                <td id="td_edit-<?php echo $rs["ID"]; ?>"><i id="edit_<?php echo $rs["ID"]; ?>" onclick="window.location.href = 'edit_bet?bid=<?php echo $rs["ID"]; ?>';" class="editRow edit icon" style="cursor:pointer;color:red;"></i></td>
              </tr>
            <?php } ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

</div>

<?php include("includes/footer.php"); ?>