$(function() { 
  $('#chart').highcharts('StockChart', {
      title: {
	  text: 'Total Wallets'
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
	      text: 'Total Wallets'
	  },
	  min: 0
      },
      credits: {
	enabled: false
      },
      tooltip: { {literal}
	  pointFormat: '{point.y} Wallets'{/literal}
      },
      series: [{
	  showInLegend: false,
	  data: [
	      {foreach $walletdata AS $wallet}
	      [Date.UTC({$wallet.walletyear}, {$wallet.walletmonth}, {$wallet.walletday}), {$wallet.wallets} ],
	      {/foreach}
	  ]
      }]
  });
});