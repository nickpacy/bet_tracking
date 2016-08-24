<?php 
    $pageTitle = "My Bets - Bet Tracking";
    include("includes/header.php"); ?>

  <?php

  if (isset($_SESSION["user_id"])) {
    $user_id = $_SESSION["user_id"];
  }else{
    $user_id = getUserID();
  }

    $numWins = GetResultNumber('1');
    $numLosses = GetResultNumber('2');
    $numUpcoming = GetResultNumber('3');
    $numPush = GetResultNumber('4');


    $profitArrMonth = GetProfitXMonth();
    $profitMonths = $profitArrMonth[0];
    $profitxMonth = $profitArrMonth[1];

    $profitArrSport = GetProfitXSport();
    $profitSports = $profitArrSport[0];
    $profitxSport = $profitArrSport[1];


 ?>

    <link rel="stylesheet" type="text/css" href="css/header.css">
    <link rel="stylesheet" type="text/css" href="css/progress_bar.css">

  <script src="http://code.highcharts.com/highcharts.js"></script>
  <script src="http://code.highcharts.com/themes/sand-signika.js"></script>

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



<div class="ui grid" style="padding: 0 5% 0 5%;">
  <div class="row ui grid">
    <div class="row ui grid">
      <div class="sixteen wide mobile sixteen wide tablet sixteen wide small monitor eight wide computer eight wide large screen eight wide widescreen column">
        <div id="container" style="min-width: 310px; height: 400px; margin: 20px auto"></div>
      </div>
      <div class="sixteen wide mobile sixteen wide tablet sixteen wide small monitor eight wide computer eight wide large screen eight wide widescreen column">
        <div id="container2" style="min-width: 310px; height: 400px; margin: 20px auto"></div>
      </div>
    </div>
    <div class="row ui grid">
      <div class="sixteen wide mobile sixteen wide tablet sixteen wide small monitor eight wide computer eight wide large screen eight wide widescreen column">
        <div id="container3" style="min-width: 310px; height: 400px; margin: 20px auto"></div>
      </div>
      <div class="sixteen wide mobile sixteen wide tablet sixteen wide small monitor eight wide computer eight wide large screen eight wide widescreen column">
        <div id="container4" style="min-width: 310px; height: 400px; margin: 20px auto"></div>
      </div>
    </div>
  </div>
</div>



<script>
$(function () {
    $('#container').highcharts({
        colors: ["#55BF3B", "#f45b5b", "#8085e9", "#7798BF"],
      chart: {
            type: 'pie'
        },
        title: {
            text: 'Betting Results',
            x: -20 //center
        },
        credits: {enabled: false},
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    format: '<b>{point.name}</b>: {point.y}',
                    style: {
                        color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                    }
                }
            }
        },
        series: [{
            name: "Result",
            colorByPoint: true,
            data: [{
                name: "Wins",
                y: <?php print $numWins; ?>
            }, {
                name: "Losses",
                y: <?php print $numLosses; ?>
            }, {
                name: "Upcoming",
                y: <?php print $numUpcoming; ?>
            }, {
                name: "Push",
                y: <?php print $numPush; ?>
            }]
        }]
    });
    $('#container2').highcharts({
        title: {
            text: 'Cumulative Profit',
        },
        xAxis: {
            categories: <?php print $profitMonths; ?>
        },
        yAxis: {
            title: {
                text: 'Profit ($)'
            },
            labels: {
                format: '${value}'
            }
        },
        credits: {enabled: false},
        legend: {
            enabled: false
        },
        tooltip: {
            pointFormat: '<span style="color:{point.color}">\u25CF</span> {series.name}: <b>${point.y:.2f}</b><br/>'
        },
        series: [{
            name: 'Profit',
            color: '#55BF3B',
            data: <?php print $profitxMonth; ?>
        }]
    });
    $('#container3').highcharts({
        chart: {
            type: 'column'
        },
        title: {
            text: 'Profit By Sport',
        },
        xAxis: {
            categories: <?php print $profitSports; ?>
        },
        yAxis: {
            title: {
                text: 'Profit ($)'
            },
            labels: {
                format: '${value}'
            }
        },
        credits: {enabled: false},
        legend: {
            enabled: false
        },
        tooltip: {
            pointFormat: '<span style="color:{point.color}">\u25CF</span> {series.name}: <b>${point.y:.2f}</b><br/>'
        },
        series: [{
            name: 'Profit',
            color: '#55BF3B',
            negativeColor: '#f45b5b',
            data: <?php print $profitxSport; ?>
        }]
    });
    $('#container4').highcharts({
        title: {
            text: 'Monthly Average Temperature',
            x: -20 //center
        },
        xAxis: {
            categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
                'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
        },
        yAxis: {
            title: {
                text: 'Temperature (°C)'
            }
        },
        credits: {enabled: false},
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle',
            borderWidth: 0
        },
        series: [{
            name: 'Tokyo',
            data: [7.0, 6.9, 9.5, 14.5, 18.2, 21.5, 25.2, 26.5, 23.3, 18.3, 13.9, 9.6]
        }, {
            name: 'New York',
            data: [-0.2, 0.8, 5.7, 11.3, 17.0, 22.0, 24.8, 24.1, 20.1, 14.1, 8.6, 2.5]
        }]
    });
});
        </script>




<?php
require('includes/footer.php');
?>