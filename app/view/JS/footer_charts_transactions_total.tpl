$(function() { 
  $('#chart').highcharts('StockChart', {
      title: {
	  text: 'Total Number Of Transactions'
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
	      text: 'Total Transactions'
	  },
	  min: 0
      },
      credits: {
	enabled: false
      },
      tooltip: { {literal}
	  pointFormat: '{point.y} Transactions'{/literal}
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