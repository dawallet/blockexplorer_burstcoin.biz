$(function() { 
  $('#chart').highcharts({
      chart: {
	  type: 'column',
	  zoomType: 'x'
      },
      title: {
	  text: 'Balance Distribution'
      },
      subtitle: {
	  text: document.ontouchstart === undefined ?
		  'Source: burstcoin.biz | Click and drag in the chart area to zoom in' :
		  'Source: burstcoin.biz | Pinch the chart to zoom in'
      },
      xAxis: {
	  categories: [
	      '< 1 Burst',
	      '1 - 10 Burst',
	      '10 - 100 Burst',
	      '100 - 1k Burst',
	      '1k - 10k Burst',
	      '10k - 100k Burst',
	      '100k - 1M Burst',
	      '1M - 10M Burst',
	      '> 10M Burst'
	  ]
      },
      yAxis: {
	  min: 0,
	  title: {
	      text: 'Number Of Accounts'
	  }
      },
      credits: {
	enabled: false
      },
      tooltip: {
	  {literal}headerFormat: '<strong>{point.key}</strong><br>',
	  pointFormat: '{point.y} Accounts',
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
	  name: 'Accounts',
	  data: [{$blockData1}, {$blockData2}, {$blockData3}, {$blockData4}, {$blockData5}, {$blockData6}, {$blockData7}, {$blockData8}, {$blockData9}]

      }]
  });
});