$(function() { 
  $('#chart').highcharts('StockChart', {
      title: {
	  text: 'Total Burstcoins In Circulation'
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
	      text: 'Total Burstcoins (Burst)'
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
	  name: 'Total Burstcoins',
	  data: [
	      {foreach $blockdata AS $block}
	      [Date.UTC({$block.blockyear}, {$block.blockmonth}, {$block.blockday}), {$block.burstcoins} ],
	      {/foreach}
	  ]
      }]
  });
});