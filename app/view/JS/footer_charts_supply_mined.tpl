$(function() { 
  $('#chart').highcharts({
      chart: {
	  type: 'pie',
	  plotBackgroundColor: null,
	  plotBorderWidth: null,
	  plotShadow: false
      },
      title: {
	  text: 'Total Mined Burstcoins'
      },
      subtitle: {
	  text: 'Source: burstcoin.biz'
      },
      tooltip: {
	  pointFormat: '{literal}Mining progress: <b>{point.percentage:.2f}%</b>{/literal}'
      },
      credits: {
	enabled: false
      },
      plotOptions: {
	  pie: {
	      allowPointSelect: true,
	      cursor: 'pointer',
	      dataLabels: {
		  enabled: true,                                  
		  format: '{literal}<b>{point.name}</b>: {point.percentage:.2f} % ({point.y:,.0f} Burst){/literal}',
		  style: {
		      color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
		  }
	      }
	  }
      },
      series: [{
      data: [
	['Mined', {$minedBurstcoins}],
	['Unmined', {$unminedBurstcoins}]
      ]
      }], 
  });
});