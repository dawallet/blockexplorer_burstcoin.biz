$(function() {        
  $('#chart').highcharts('StockChart', {
      title: {
	  text: 'Total Burst Sent per day for: {$addressdata.accountRS}'
      },
      subtitle: {
	  text: 'Source: burstcoin.biz'
      },
      xAxis: {
	  type: 'datetime',
	  dateTimeLabelFormats: {
	      month: '%e. %b %y',
	      year: '%b'
	  }
      },
      yAxis: {
	  title: {
	      text: 'Burst Sent'
	  },
	  min: 0
      },
      credits: {
	enabled: false
      },
      tooltip: { {literal}
	  pointFormat: '{point.y} Burst'{/literal}
      },

      series: [{
	  showInLegend: false,
	  data: [
	      {foreach $transactiondata AS $transaction}
	      [Date.UTC({$transaction.transactionyear}, {$transaction.transactionmonth}, {$transaction.transactionday}), {$transaction.transactions} ],
	      {/foreach}
	  ]
      }]
  });
});