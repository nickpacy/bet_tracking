<?php 
    $pageTitle = "My Bets - Bet Tracking";
    include("includes/header.php"); ?>

    <link rel="stylesheet" type="text/css" href="css/header.css">

<?php
 
        $bet_id = $_REQUEST["bid"];

        if ($bet_id == "") {
          header("Location: /bet_tracking/my_bets");
          exit();
        }



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
        <div class="header"><a href="bet_action?logout=true"><i class="sign out icon"></i>&nbsp;Logout</a></div>
      </div>
    </div>
  </div>
</div>
<?php 
  //$qry = "SELECT * FROM bet_tracking.Master WHERE user_id = $user_id AND id = $bet_id INNER JOIN Teams LIMIT 1 ";
  $qry = "SELECT * FROM vBetHeader WHERE User_ID = $user_id AND ID = $bet_id LIMIT 1";
  $q = mysqli_query($conn, $qry);
  $rowCount = $q->num_rows; 
  $rs = mysqli_fetch_array($q);
  ?>
<div class="ui raised very padded text container segment">
        

      <?php
        $qry = "SELECT * FROM vBetHeader WHERE User_ID = $user_id AND ID < $bet_id ORDER BY ID DESC LIMIT 1";
        $q = mysqli_query($conn, $qry);
        $rowCount = $q->num_rows; 
        $lastRs = mysqli_fetch_array($q);
      if ($rowCount > 0) { ?>
      <div class="ui left rail">
      <a href="edit_bet?bid=<?php echo $lastRs["ID"]; ?>">
        <div class="ui raised segment">
      <div class="ui red ribbon label">Prev Bet</div>
          <img class="ui avatar image" src="<?php echo GetTeamLogo($lastRs["League_Type"], $lastRs["Away_ESPN_ID"], $lastRs["Away_Abbr"]); ?>">
          &nbsp;vs&nbsp;
          <img class="ui avatar image" src="<?php echo GetTeamLogo($lastRs["League_Type"], $lastRs["Home_ESPN_ID"], $lastRs["Home_Abbr"]); ?>">
          <div class="ui basic label"><?php echo date('m/j/y', strtotime($lastRs["Game_Date"])); ?></div>
        </div>
      </a>
      </div>
            <?php
          }
        $qry = "SELECT * FROM vBetHeader WHERE User_ID = $user_id AND ID > $bet_id ORDER BY ID ASC LIMIT 1";
        $q = mysqli_query($conn, $qry);
        $rowCount = $q->num_rows; 
        $lastRs = mysqli_fetch_array($q);
          if ($rowCount > 0) { ?>
      <div class="ui right rail">
      <a href="edit_bet?bid=<?php echo $lastRs["ID"]; ?>">
        <div class="ui raised segment">
          <div class="ui red right ribbon label">Next Bet</div>
          <div style="float: left;">
            <div class="ui basic label"><?php echo date('m/j/y', strtotime($lastRs["Game_Date"])); ?></div>
            <img class="ui avatar image" src="<?php echo GetTeamLogo($lastRs["League_Type"], $lastRs["Away_ESPN_ID"], $lastRs["Away_Abbr"]); ?>">
            &nbsp;vs&nbsp;
            <img class="ui avatar image" src="<?php echo GetTeamLogo($lastRs["League_Type"], $lastRs["Home_ESPN_ID"], $lastRs["Home_Abbr"]); ?>" >
          </div>
        </div>
      </a>
      </div>
      <?php } ?>
 
  <div class="ui two column middle aligned very relaxed stackable grid">

    <div class="column center aligned">
      <div class="ui card">
        <div class="extra content">
          <?php 
            if ($rs["Bet_Choice"] == "Away") { ?>
              <i class="pointing down icon"></i>Your Pick&nbsp;
                <?php if ($rs["Result"] == "Win") { ?>
                  <i class="green large checkmark icon"></i>
                <?php } elseif ($rs["Result"] == "Loss") { ?>
                  <i class="red large remove icon"></i>
                <?php } elseif ($rs["Result"] == "Push") { ?>
                  <i class="grey large exchange icon"></i>
                <?php } elseif ($rs["Result"] == "Upcoming") { ?>
                  <i class="blue large wait icon"></i>
                <?php } ?>


           <?php } else { ?>
            <br>
           <?php } ?>
        </div>
        <div class="image" style="background-image: url('images/subtle.png')">
        <!-- style="background-color: green;" -->
          <img src="<?php echo GetTeamLogo($rs["League_Type"], $rs["Away_ESPN_ID"], $rs["Away_Abbr"]); ?>">
        </div>
        <div class="content">
          <a class="header"><?php echo $rs["Away_Team"]; ?></a>
        </div>
      </div>
    </div>
    <div class="ui vertical divider">
      VS
    </div>
    <div class="column center aligned">
      <div class="ui card">
        <div class="extra content">
<?php 
            if ($rs["Bet_Choice"] == "Home") { ?>
              <i class="pointing down icon"></i>Your Pick&nbsp;
                <?php if ($rs["Result"] == "Win") { ?>
                  <i class="green large checkmark icon"></i>
                <?php } elseif ($rs["Result"] == "Loss") { ?>
                  <i class="red large remove icon"></i>
                <?php } elseif ($rs["Result"] == "Push") { ?>
                  <i class="grey large exchange icon"></i>
                <?php } elseif ($rs["Result"] == "Upcoming") { ?>
                  <i class="blue large wait icon"></i>
                <?php } ?>


           <?php } else { ?>
            <br>
           <?php } ?>
        </div>
        <div class="image" style="background-image: url('images/subtle.png')">
          <img src="<?php echo GetTeamLogo($rs["League_Type"], $rs["Home_ESPN_ID"], $rs["Home_Abbr"]); ?>">
        </div>
        <div class="content">
          <a class="header"><?php echo $rs["Home_Team"]; ?></a>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="ui  text container segment">
      <div class="ui basic segment">
        <table class="ui single line table">
          <thead>
            <tr>
              <th>Result</th>
              <th>Bet Amount</th>
              <th>Profit</th>
              <th>Result</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td><?php echo $rs["Result"]; ?></td>
              <td><?php echo money_format('$%i', ($rs["Bet_Amount"])); ?></td>
              <td><?php 
                if ($rs["Result"] == "Win") {
                    echo money_format('$%i', ($rs["To_Win_Amount"]));
                } elseif ($rs["Result"] == "Loss") {
                    echo money_format('$%i', ($rs["Bet_Amount"] * -1));
                } else {
                  echo "";
                } ?></td>
              <td><?php echo $rs["Away_Team"]; ?></td>
            </tr>
          </tbody>
        </table>
      </div>
</div>



</div>


<script>

$('.special.cards .image').dimmer({
  on: 'hover'
});
  
function EditRow(id){

  $.getJSON(ajaxPage,
        {_EditData: id},
          function(result){
      $('.ui.long.modal').modal('show');
      $("#sport").val(result.sport);
      $("#sport").change();

    });

}

</script>




<?php include("includes/footer.php"); ?>