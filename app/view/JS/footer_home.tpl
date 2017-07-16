$(function() { 
  function smallStockCharts(tabName, dataNumber) {
    if (tabName == 'supply') {
      var tooltipText = 'Burst Total Supply';
      var homeChartData = [{foreach $globalStatsNewBurstcoinsChart AS $block}[Date.UTC({$block.blockyear}, {$block.blockmonth}, {$block.blockday}), {$block.burstcoins} ]{if $block@last}{else},{/if}{/foreach}];
    } else if (tabName == 'transactions') {
      var tooltipText = 'Transactions';
      var homeChartData = [{foreach $globalStatsNewTransactionsChart AS $block}[Date.UTC({$block.transactionyear}, {$block.transactionmonth}, {$block.transactionday}), {$block.transactions} ]{if $block@last}{else},{/if}{/foreach}];
    } else if (tabName == 'wallets') {
      var tooltipText = 'Wallets';
      var homeChartData = [{foreach $globalStatsNewWalletsChart AS $block}[Date.UTC({$block.walletyear}, {$block.walletmonth}, {$block.walletday}), {$block.wallets} ]{if $block@last}{else},{/if}{/foreach}];
    } else if (tabName == 'network') {
      var tooltipText = 'TB';
      var homeChartData = [{foreach $globalStatsNetworkSizeChart AS $block}[Date.UTC({$block.blockyear}, {$block.blockmonth}, {$block.blockday}, {$block.blockhour}, {$block.blockmin}, 0), {$block.networksize} ]{if $block@last}{else},{/if}{/foreach}];
    }
    $('#stock-' + tabName + '-sm').highcharts('StockChart', {
	chart: {
	    height: 149,
	    plotBorderColor: '#C21414',
	    backgroundColor: 'transparent',
	    spacingRight: 0,
	    spacingLeft: 0,
	    spacingBottom: 0,
	    spacingTop: 0,
	    marginBottom: 0
	},
	credits: {
	    enabled: false
	},
	colors: ['rgba(0,0,0,0.3)', 'rgba(0,0,0,0.3)'],
	exporting: {
	    enabled: false
	},
	rangeSelector: {
	    selected: 0,
	    enabled: false
	},
	scrollbar: {
	    enabled: false
	},
	navigator: {
	    enabled: false
	},
	navigation: {
	    buttonOptions: {
		enabled: false
	    }
	},
	xAxis: {
	    gridLineColor: 'transparent',
	    gridLineColor: 'transparent',
	    lineColor: 'transparent',
	    tickColor: 'transparent',
	    minorGridLineWidth: 0,
	    labels: {
		enabled: false
	    }
	},
	yAxis: {
	    gridLineColor: 'transparent',
	    gridLineColor: 'transparent',
	    lineColor: 'transparent',
	    labels: {
		enabled: false
	    }
	},
	tooltip: { 
	    pointFormat: '{literal}{point.y:,.0f}{/literal} ' + tooltipText
	},
	series: [{
	    name: tabName,
	    data: homeChartData,
	    type: 'spline',
	    tooltip: {
		valueDecimals: 0
	    }
	}]
    });
  }
  smallStockCharts('supply', 1);
  smallStockCharts('transactions', 2);
  smallStockCharts('wallets', 3);
  smallStockCharts('network', 4);
});