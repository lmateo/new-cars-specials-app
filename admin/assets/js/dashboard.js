 function getVisitStats() {
     $.ajax({
         type: 'GET',
         url: ADMINURL + "/helper.php?getMainVisitStats=1",
         dataType: 'json'
     }).done(function(json) {
         var option = {
             series: {
                 lines: {
                     show: false,
                     fill: true,
                     lineWidth: 2
                 },
                 splines: {
                     show: true,
                     tension: 0.5,
                     lineWidth: 2,
                     fill: 0
                 },
                 shadowSize: 0
             },
             points: {
                 show: true,
                 lineWidth: 2,
                 radius: 5,
                 symbol: "circle",
                 fill: true,
                 fillColor: "#ffffff"
             },
             colors: ["#00b5ad"],
             grid: {
                 hoverable: true,
                 clickable: true,
                 borderColor: "#fff",
                 borderWidth: 1,
                 labelMargin: 16,
                 backgroundColor: '#fff'
             },
             yaxis: {
                 color: "rgba(0,0,0,0.0)",
                 ticks: false,
                 tickLength: 0,
                 font: {
                     color: "#939599"
                 }
             },
             xaxis: {
                 color: "rgba(0,0,0,0.1)",
                 ticks: json.xaxis,
                 tickDecimals: 0,
                 font: {
                     color: "#939599"
                 }
             },
             legend: {
                 backgroundColor: "#fff",
                 labelBoxBorderColor: "rgba(0,0,0,0.1)",
                 backgroundOpacity: .75,
                 noColumns: 1,
             }
         }
         plot = $.plot($('#chart2'), [json.visits], option);
     });

 }
 getVisitStats();


 function getTopFive() {
     $.ajax({
         type: 'GET',
         url: ADMINURL + "/helper.php?getTopFive=1",
         dataType: 'json'
     }).done(function(json) {
         $("#chart4").html(json.html);
         $(".wojo.progress").simpleprogress();
         $("#tvisits small").html(json.sum);
         var option = {
             series: {
                 pie: {
                     radius: 1,
                     show: true,
                     label: {
                         show: true,
                         radius: 2 / 3,
                         formatter: function(label, series) {
                             return '<div style="font-size:.75em;text-align:center;color:white;">' + series.data[0][1] + '%</div>';

                         },
                         threshold: 0.1
                     },
                     stroke: {
                         width: 0.1
                     }
                 }
             },
             legend: {

             },
             grid: {
                 hoverable: true
             },

             colors: ["#1CA8DD", "#9ACA40", "#d25b5b", "#d9499a", "#00b5ad"],
             opacity: .5
         };
         plot = $.plot($('#chart3'), json.visits, option);
     });

 }
 getTopFive();

 function getStats(range) {
     $.ajax({
         type: 'GET',
         url: ADMINURL + "/helper.php?getMainStats=1&timerange=" + range,
         dataType: 'json'
     }).done(function(json) {
         var option = {
             series: {
                 bars: {
                     show: true,
                     barWidth: 0.15,
                     lineWidth: 1,
                     order: 1,
                 }
             },
             points: {
                 show: true,
             },
             grid: {
                 hoverable: true,
                 clickable: true,
                 borderColor: "rgba(0,0,0,0.1)",
                 borderWidth: 1,
                 labelMargin: 16,
                 backgroundColor: '#fff'
             },
             yaxis: {
                 color: "rgba(0,0,0,0.1)",
                 font: {
                     color: "#939599"
                 }
             },
             xaxis: {
                 mode: "categories",
                 color: "rgba(0,0,0,0.1)",
                 ticks: json.xaxis,
                 font: {
                     color: "#939599"
                 }
             },
             legend: {
                 backgroundColor: "#fff",
                 labelBoxBorderColor: "rgba(0,0,0,0.1)",
                 backgroundOpacity: .75,
                 noColumns: 1,
             }
         }

         plot = $.plot($('#chart'), [json.gross, json.net, json.total, json.tax, json.coupon], option);
     });

 }
 getStats('all');

 function showTooltip(x, y, contents) {
     $('<div class="charts_tooltip">' + contents + '</div>').css({
         position: 'absolute',
         display: 'none',
         top: y + 5,
         left: x + 5
     }).appendTo("body").fadeIn(200);
 }
 var previousPoint = null;

 $("#chart, #chart2").on("plothover", function(event, pos, item) {
     if (item) {
         if (previousPoint != item.dataIndex) {
             previousPoint = item.dataIndex;
             $(".charts_tooltip").fadeOut("fast").promise().done(function() {
                 $(this).remove();
             });
             var x = item.datapoint[0].toFixed(2),
                 y = item.datapoint[1].toFixed(2);
             i = item.series.xaxis.options.ticks[item.dataIndex][1]
             showTooltip(item.pageX, item.pageY, item.series.label + " for " + i + " = " + y);
         }
     } else {
         $(".charts_tooltip").fadeOut("fast").promise().done(function() {
             $(this).remove();
         });
         previousPoint = null;
     }
 });