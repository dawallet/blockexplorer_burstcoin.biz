$(function() { 
  $('#chart').highcharts('StockChart', {
      title: {
	  text: 'Estimated Network Size'
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
	      text: 'Network Size (TB)'
	  },
	  min: 0
      },
      credits: {
	enabled: false
      },
      tooltip: { {literal}
	  pointFormat: '{point.y:,.0f} TB'{/literal}
      },
      series: [{
	  showInLegend: false,
	  data: [
	      {foreach $blockdata AS $block}
	      [Date.UTC({$block.blockyear}, {$block.blockmonth}, {$block.blockday}, {$block.blockhour}, {$block.blockmin}, 0), {$block.networksize} ],
	      {/foreach}
	  ]
      }]
  });
});