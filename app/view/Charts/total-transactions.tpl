{include file="header.tpl" siteTitle="Total Number Of Transactions" navCharts=" class='nav-active active'"}           
          <div class="header">
            <h2><strong>Charts</strong> <small>Total Number Of Transactions</small></h2>
	    <div class="breadcrumb-wrapper">
              <ol class="breadcrumb">
                <li><a href="{$httpRoot}">Home</a></li>	
		<li><a href="{$httpRoot}charts">Charts</a></li>
                <li class="active">Total Transactions</li>
              </ol>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-12">
	      <div class="panel">
		<div class="panel-content">
		  <div id="chart" style="width:100%;height:600px;"></div>
		</div>
	      </div>
            </div>
          </div>
{include file="footer.tpl" loadJSCharts="1" footerJS="charts_transactions_total"}