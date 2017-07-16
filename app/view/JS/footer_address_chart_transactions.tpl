$(function() {        
  $('#chart').highcharts('StockChart', {
      title: {
	  text: 'No. of transactions per day for: {$addressdata.accountRS}'
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
	      text: 'Transactions Per Day'
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