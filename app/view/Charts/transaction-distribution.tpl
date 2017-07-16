{include file="header.tpl" siteTitle="Transaction Amount Distribution" navbar="main" navCharts=" class='active'"}           
    <div class="page-header">
      <h1>Charts <small>Transaction Amount Distribution</small></h1>
      <ol class="breadcrumb">
        <li><a href="{$httpUrl}"><span class="glyphicon glyphicon-home"></span> &nbsp;Home</a></li>
        <li><a href="{$httpUrl}charts">Charts</a></li>
        <li class="active">Transaction Amount Distribution</li>
      </ol>
    </div>
    <div class="container-fluid">
      <div class="row">
        <div class="col-xs-12">
          <div class="cbox animated fadeIn">
            <div class="cbox-content cbox-without-title">
              <div id="chart" style="width:100%;height:600px;"></div>
            </div>
          </div>
          <script>
            $(function () {
                $('#chart').highcharts({
                    chart: {
                        type: 'column',
                        zoomType: 'x'
                    },
                    title: {
                        text: 'Transaction Amount Distribution'
                    },
                    subtitle: {
                        text: document.ontouchstart === undefined ?
                                'Source: burstcoin.biz | Click and drag in the chart area to zoom in' :
                                'Source: burstcoin.biz | Pinch the chart to zoom in'
                    },
                    xAxis: {
                        categories: [
                            '< 1 Burst',
                            '1 - 10 Burst',
                            '10 - 100 Burst',
                            '100 - 1k Burst',
                            '1k - 10k Burst',
                            '10k - 100k Burst',
                            '100k - 1M Burst',
                            '1M - 10M Burst',
                            '> 10M Burst'
                        ]
                    },
                    yAxis: {
                        min: 0,
                        title: {
                            text: 'Number Of Transactions'
                        }
                    },
                    credits: {
                      enabled: false
                    },
                    tooltip: {
                        {literal}headerFormat: '<strong>{point.key}</strong><br>',
                        pointFormat: '{point.y} Transactions',
                        shared: true,
                        useHTML: true{/literal}
                    },
                    plotOptions: {
                        column: {
                            pointPadding: 0.2,
                            borderWidth: 0
                        }
                    },
                    series: [{
                        showInLegend: false,
                        name: 'Transactions',
                        data: [{$blockData1}, {$blockData2}, {$blockData3}, {$blockData4}, {$blockData5}, {$blockData6}, {$blockData7}, {$blockData8}, {$blockData9}]

                    }]
                });
            });
          </script>
        </div>
      </div>
    </div>
{include file="footer.tpl"}
