{include file="header.tpl" siteTitle="Total Burst Received per day for: {$addressdata.accountRS}" navExplorer=" class='nav-active active'"}
          <div class="header">
            <h2><strong>Block Explorer</strong> <small>Total Burst Received per day</small></h2>
	    <div class="breadcrumb-wrapper">
              <ol class="breadcrumb">
                <li><a href="{$httpRoot}">Home</a></li>
		<li><a href="{$httpUrl}blocks">Block Explorer</a></li>
		<li><a href="{$httpUrl}address/{$addressdata.account}">{$addressdata.accountRS}</a></li>
		<li class="active">Chart</li>
              </ol>
            </div>
          </div>
	  <div class="row">
	    <div class="col-xlg-12">
	      <div class="panel">
		<div class="panel-content">
		  <div id="chart" style="width:100%;height:600px;"></div>
		</div>
	      </div>
	    </div>
	  </div>
{include file="footer.tpl" loadJSCharts="1" footerJS="address_chart_totalreceived" updatePage="1"}