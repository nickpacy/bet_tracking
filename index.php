<?php 
    $pageTitle = "Homepage - Bet Tracking";
    include("includes/header.php"); ?>

  <link rel="stylesheet" href="css/index.css">

  <style>
  .navUser a:link{
    color: white;
  }
  .navUser a:hover {
    color: #551A8B;
}
  </style>

  <script>
  $(document)
    .ready(function() {

      
      $('.ui.rating')
	  .rating({
	    initialRating: <?php echo intval(GetSiteRating()); ?>,
	    maxRating: 10
	  }).rating('setting', 'onRate', function(value) {
     
        $.post("includes/index_ajax.php",
               {_UserRating: value},
               function(result){
                $("#userRating").html(value);
                $("#rating_success").html(result);
        });

    });


      //ajax for dynamic parts on page.
      $.getJSON("includes/index_ajax.php",
         {_LoadBetProfit: <?php print $user_id; ?>},
         function(result){
          $("#month_bet_ajax").removeClass("ui active inline loader");
          $("#month_bet_ajax").html(result.paragraph);
          $("#profitCard").html(result.htmlCard);
        });

      $.get("includes/index_ajax.php",
         {_LoadUpcomingTable: <?php print $user_id; ?>},
         function(result){
          $("#upcoming_game_ajax").html(result);
        });



      $.get("includes/index_ajax.php",
         {_LoadSlidesData: <?php print $user_id; ?>},
         function(result){
          $("#slidesAjax").removeClass("active inline loader");
          $("#slidesAjax").html(result);
        });


    })
  ;
  </script>
</head>
<body class="pushable">

<?php require("includes/incl_nav.php"); ?>




    <div class="ui text container">
      <h1 class="ui inverted header">
        Pacy Bet Tracking
      </h1>
      <h2>A place to store and track your sports betting habit.</h2>
      <button class="ui huge primary button" onclick="$('.ui.long.modal').modal('show');">Add Bet&nbsp;&nbsp;<i class="plus icon"></i></button>
    </div>

  </div>

  <div class="ui vertical stripe segment">
    <div class="ui middle aligned stackable grid container">
      <div class="row">
        <div class="eight wide column">
          <h3 class="ui header"><?php print getdate()[month]; ?>  Bet Profits</h3>
          <p class="ui active inline loader" id="month_bet_ajax"></p>
          <h3 class="ui header">Want more trends?</h3>
            <div class="ui text shape active large inline loader" id="slidesAjax">
              
            </div>
        </div>
        <div class="six wide right floated column" id="profitCard">
          <div class="ui active large inline loader"></div>
        </div>
      </div>
      <div class="row">
        <div class="center aligned column">
          <a class="ui huge button" href="stats">View More Stats</a>
        </div>
      </div>
    </div>
  </div>


  <div class="ui vertical stripe quote segment">
    <div class="ui equal width stackable internally celled grid">
      <div class="center aligned row">
        <div class="column">
          <div  class="ui  image">
            <img src="images/bovada.png">
          </div>
          <p>Trusted betting website</p>
        </div>
        <div class="column">

        <?php 
            $qry = "SELECT MAX(ID) AS MaxID FROM Team;";
            $q = mysqli_query($conn, $qry);
            $rs = mysqli_fetch_array($q);
            $maxTeam = $rs["MaxID"];  

            srand(floor(time() / (60*60*24))); 
            $rand1 = GetTeamLogoByID(rand (1, $maxTeam));
            srand(floor(time() / (60*60*24)) + 1); 
            $rand2 = GetTeamLogoByID(rand (1, $maxTeam));
            srand(floor(time() / (60*60*24)) + 2); 
            $rand3 = GetTeamLogoByID(rand (1, $maxTeam));

            ?>
            <div class="ui basic segment">
            <h4 class="ui header">Random Teams Of The Day<div class="sub header">Test your luck with these teams.</div></h4>
          <div class="ui fluid three item menu">
            <div class="item">
              <h5 class="ui header">
                <img class="ui tiny image" src="<?php echo $rand1[0]; ?>">
                <div class="sub header"><?php echo $rand1[1] . " " . $rand1[2]; ?></div>
              </h5>
            </div>
            <div class="item">
              <h5 class="ui header">
                <img class="ui tiny image" src="<?php echo $rand2[0]; ?>">
                <div class="sub header"><?php echo $rand2[1] . " " . $rand2[2]; ?></div>
              </h5>
            </div>
            <div class="item">
              <h5 class="ui header">
                <img class="ui tiny image" src="<?php echo $rand3[0]; ?>">
                <div class="sub header"><?php echo $rand3[1] . " " . $rand3[2]; ?></div>
              </h5>
            </div>
          </div>
          <p>
           <a class="ui button small blue" href="teams">View All Teams</a>
          </p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="ui vertical stripe segment">
    <div class="ui text container">
      <h3 class="ui header">See Upcoming Games</h3>
      <div id="upcoming_game_ajax" class="mb20">
        <div class="ui icon message">
	  <i class="notched circle loading icon"></i>
	  <div class="content">
	    <div class="header">
	      Just one second
	    </div>
	    <p>We're fetching that content for you.</p>
	  </div>
	</div>
      </div>
      <a class="ui large button" href="my_bets">See More</a>
      <h4 class="ui horizontal header divider">
        <p>Share Us</p>
      </h4>

      <?php $qry = "SELECT rating FROM UserTBL WHERE user_id = $user_id;";
            $q = mysqli_query($conn, $qry);
            $rs = mysqli_fetch_array($q);
            if ($rs["rating"] == "") { ?>
      <div id="rating_success" style="margin-bottom: 20px; ">      
        <h3 class="ui header">Rate us</h3>
        <div class="ui rating"></div>
        <p><span id="userRating"><?php echo intval(GetSiteRating()); ?></span>/10</p>
      </div>
            <?php } ?>
      <div class="ui fluid four item menu">
        <a class="item"><i class="large twitter blue icon"></i> Tweet</a>
        <a class="item"><i class="large facebook dark blue square icon"></i> Share</a>
        <a class="item"><i class="large google plus red square icon"></i> Google +</a>
        <a class="item" href="mailto:?Subject=Check%20Out%20This%20Bet%20Tracking%20Website" target="_top"><i class="large mail icon"></i> E-mail</a>
      </div>
    </div>
  </div>
</div>





<?php include("includes/footer.php"); ?>