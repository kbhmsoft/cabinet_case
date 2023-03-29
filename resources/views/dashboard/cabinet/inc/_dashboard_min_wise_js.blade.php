<script type="text/javascript">
      // Create the chart
      Highcharts.chart('container', {
         chart: {
            type: 'column'
         },
         title: {
            text: 'মন্ত্রণালয় ভিত্তিক মামলা'
         },
         subtitle: {
            text: 'মামলা'
         },
         accessibility: {
            announceNewData: {
               enabled: true
            }
         },
         xAxis: {
            type: 'category'
         },
         yAxis: {
            title: {
               text: 'Number of Case'
            }

         },
         legend: {
            enabled: false
         },
         plotOptions: {
            series: {
               borderWidth: 0,
               dataLabels: {
                  enabled: true,
                  format: '{point.y}'
               }
            }
         },

         tooltip: {
            headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
            pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y}</b> of total<br/>'
         },

         series: [
         {
           name: "Division",
           colorByPoint: true,
           data: <?=json_encode($ministrydata);?>
         }
         ],

         drilldown: {
            series: <?=json_encode($department_data);?>
         }
    });
   </script>