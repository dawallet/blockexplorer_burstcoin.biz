$(function() { 
  $('#chart').highcharts({
      chart: {
	  type: 'column',
	  zoomType: 'x'
      },
      title: {
	  text: 'Average Block Generation Time'
      },
      subtitle: {
	  text: document.ontouchstart === undefined ?
		  'Source: burstcoin.biz | Click and drag in the chart area to zoom in' :
		  'Source: burstcoin.biz | Pinch the chart to zoom in'
      },
      xAxis: {
	  categories: [
	      '< 60s',
	      '60 - 119s',
	      '120 - 239s',
	      '240 - 359s',
	      '360 - 599s',
	      '600 - 899s',
	      '> 900s'
	  ]
      },
      yAxis: {
	  min: 0,
	  title: {
	      text: 'Amount Of Blocks'
	  }
      },
      credits: {
	enabled: false
      },
      tooltip: {
	  {literal}headerFormat: '<strong>{point.key}</strong><br>',
	  pointFormat: '{point.y} Blocks',
	  shared: true,
	  useHTML: true{/literal}
      },
      plotOptions: {
	  column: {
	      pointPadding: 0.2,
	      borderWidth: 0
	  }
      },
      series: [{
	  showInLegend: false,
	  name: 'Blocks',
	  data: [{$blockDuration1}, {$blockDuration2}, {$blockDuration3}, {$blockDuration4}, {$blockDuration5}, {$blockDuration6}, {$blockDuration7}]

      }]
  });
});