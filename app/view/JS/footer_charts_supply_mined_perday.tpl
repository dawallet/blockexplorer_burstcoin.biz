$(function() { 
  $('#chart').highcharts('StockChart', {
      title: {
	  text: 'Number Of Burstcoins Mined Per Day'
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
	      text: 'Burstcoins (Burst) Mined Per Day'
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
	      {foreach $blockdata AS $block}
	      [Date.UTC({$block.blockyear}, {$block.blockmonth}, {$block.blockday}), {$block.burstcoins} ],
	      {/foreach}
	  ]
      }]
  });
});