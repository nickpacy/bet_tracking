<?php 
    $pageTitle = "Teams - Bet Tracking";
    include("includes/header.php"); ?>

    <link rel="stylesheet" type="text/css" href="css/header.css">
    <link rel="stylesheet" type="text/css" href="css/progress_bar.css">



  <script>
  $(document)
    .ready(function() {

    
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
        <div class="header"><a href="my_account.php"><i class="setting icon"></i>&nbsp;My Account</a></div>
      </div>
    </div>
    <div class="item">
      <div class="content">
        <div class="header"><a href="bet_action.php?logout=true"><i class="sign out icon"></i>&nbsp;Logout</a></div>
      </div>
    </div>
  </div>
</div><script>
$(document).ready(function(){
    $('.menu .item').tab({
      onFirstLoad : function(tabName) {
            LoadTeams(tabName);
            $('.ph').removeClass("ph");
          }
    });

});
</script>

<style>
/*  .bottom.attached.tab.segment{
    min-height: 400px;
  }
  .ui.container.tabs{
    min-height: 400px;
  }*/
  td{
    padding: .5em;
  }
  .tab.segment, .ph{
    min-height: 500px;
  }
</style>

  
 <div class="ui container tabs" style="padding: 20px 0">
  <h1 class="ui header" style="font-size:2em;">Teams By League</h1>

  <div class="ui top attached tabular menu">
    <a class="item" data-tab="NFL">
      <img class="leagueIcon" src="http://content.sportslogos.net/logos/7/1007/full/dwuw5lojnwsj12vfe0hfa6z47.gif" alt="NFL" />
    </a>
    <a class="item" data-tab="NHL">
      <img class="leagueIcon" src="http://content.sportslogos.net/logos/1/491/full/wkue6hfkzqs2bnl0efw8sihf7.png" alt="NHL" />
    </a>
    <a class="item" data-tab="MLB">
      <img class="leagueIcon" src="https://upload.wikimedia.org/wikipedia/en/thumb/2/2a/Major_League_Baseball.svg/1280px-Major_League_Baseball.svg.png" alt="MLB" />
    </a>
    <a class="item" data-tab="NBA">
      <img src="http://content.sportslogos.net/logos/6/982/full/2971.gif" alt="NBA" />
    </a>
    <a class="item" data-tab="NCAAF">
      <img src="http://sportsandentertainmentnashville.com/wp-content/uploads/2014/10/NCAA_Football_Logo.png" alt="NCAAF" />
    </a>
    <a class="item" data-tab="NCAAB">
      <img src="http://elitesportsadvisor.com/wp-content/uploads/2014/11/NCAA-Basketball-Logo-300x294.png" alt="NCAAB" />
    </a>
  </div>
  
  <div class="ui bottom attached tab segment" data-tab="NFL">

  </div>
  <div class="ui bottom attached tab segment" data-tab="NHL">
    
  </div>
  <div class="ui bottom attached tab segment" data-tab="MLB">
    
  </div>
  <div class="ui bottom attached tab segment" data-tab="NBA">
    
  </div>
  <div class="ui bottom attached tab segment" data-tab="NCAAF">
    
  </div>
  <div class="ui bottom attached tab segment" data-tab="NCAAB">
    
  </div>

  <div id="placeholder" class="ph">

</div>
            
         
</div>



<script>

$('.special.cards .image').dimmer({
  on: 'hover'
});
  
function LoadTeams(league){

  $.ajax({
        url: ajaxPage,
        type: 'POST',
        data: {_GetTeamsList: "1", league: league},
        async: false,
        cache: false,
        timeout: 30000,
        error: function(){
        },
        success: function(result){ 
          if (result != "") {
        $('.segment[data-tab=' + league + ']').html(result);
          }else{
              $('.segment[data-tab=' + league + ']').html("<div class='ui massive red message'><i class='calendar icon'></i>No Teams Availible</div>");
            } 
        }
    });

}

</script>




<?php include("includes/footer.php"); ?>