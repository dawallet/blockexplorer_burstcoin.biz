$(function() { 
  $('#chart').highcharts('StockChart', {
      title: {
	  text: 'Average Transactions Per Block'
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
	      text: 'Transactions Per Block'
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
	      {foreach $blockdata AS $block}
	      [Date.UTC({$block.blockyear}, {$block.blockmonth}, {$block.blockday}), {$block.transactions} ],
	      {/foreach}
	  ]
      }]
  });
});