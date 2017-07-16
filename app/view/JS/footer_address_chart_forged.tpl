$(function() {        
  $('#chart').highcharts('StockChart', {
      title: {
	  text: 'Total Forged Blocks per day to: {$addressdata.accountRS}'
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
	      text: 'Forged Blocks'
	  },
	  min: 0
      },
      credits: {
	enabled: false
      },
      tooltip: { {literal}
	  pointFormat: '{point.y} Blocks'{/literal}
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