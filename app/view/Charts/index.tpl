{include file="header.tpl" siteTitle="Burstcoin Charts" navCharts=" class='nav-active active'"}
          <div class="header">
            <h2><strong>Charts</strong></h2>
	    <div class="breadcrumb-wrapper">
              <ol class="breadcrumb">
                <li><a href="{$httpRoot}">Home</a></li>		
                <li class="active">Charts</li>
              </ol>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-12">
	      <div class="row">
		<div class="col-xs-12 col-md-6 col-lg-4">
		  <div class="panel">
		    <div class="panel-header bg-primary">
		      <h3><strong>Supply</strong></h3>
		    </div>
		    <div class="panel-content">
		      <p><a href="{$httpUrl}charts/total-burstcoins" class="btn btn-primary btn-block t-left btn-square btn-embossed btn-chart"><i class="fa fa-line-chart"></i>Burstcoins In Circulation</a></p>
		      <p><a href="{$httpUrl}charts/mined-burstcoins" class="btn btn-primary btn-block t-left btn-square btn-embossed btn-chart"><i class="fa fa-pie-chart"></i>Total Mined Burstcoins</a></p>
		      <p><a href="{$httpUrl}charts/burstcoins-mined-per-day" class="btn btn-primary btn-block t-left btn-square btn-embossed btn-chart"><i class="fa fa-line-chart"></i>Burstcoins Mined Per Day</a></p>
		      <p><a href="{$httpUrl}charts/estimated-network-size" class="btn btn-primary btn-block t-left btn-square btn-embossed btn-chart"><i class="fa fa-line-chart"></i>Estimated Network Size</a></p>
		      <p><a href="{$httpUrl}charts/average-block-generation-time" class="btn btn-primary btn-block t-left btn-square btn-embossed btn-chart"><i class="fa fa-bar-chart"></i>Avg. Block Generation Time</a></p>
		      <p><a href="{$httpUrl}charts/blockchain-size" class="btn btn-primary btn-block t-left btn-square btn-embossed btn-chart"><i class="fa fa-line-chart"></i>Blockchain Size</a></p>
		    </div>
		  </div>
		</div>
		<div class="col-xs-12 col-md-6 col-lg-4">
		  <div class="panel">
		    <div class="panel-header bg-blue">
		      <h3><strong>Transactions</strong></h3>
		    </div>
		    <div class="panel-content">
		      <p><a href="{$httpUrl}charts/total-number-of-transactions" class="btn btn-blue btn-block t-left btn-square btn-embossed btn-chart"><i class="fa fa-line-chart"></i>Total Transactions</a></p>
                      <p><a href="{$httpUrl}charts/number-of-transactions-per-day" class="btn btn-blue btn-block t-left btn-square btn-embossed btn-chart"><i class="fa fa-line-chart"></i>Transactions Per Day</a></p>
		      <p><a href="{$httpUrl}charts/amount-of-transactions-per-day" class="btn btn-blue btn-block t-left btn-square btn-embossed btn-chart"><i class="fa fa-line-chart"></i>Amount Sent Per Day</a></p>
		      <p><a href="{$httpUrl}charts/average-number-of-transactions-per-block" class="btn btn-blue btn-block t-left btn-square btn-embossed btn-chart"><i class="fa fa-line-chart"></i>Avg. Transactions Per Block</a></p>
		    </div>
		  </div>
		</div>
		<div class="col-xs-12 col-md-6 col-lg-4">
		  <div class="panel">
		    <div class="panel-header bg-green">
		      <h3><strong>Wallets</strong></h3>
		    </div>
		    <div class="panel-content">
                      <p><a href="{$httpUrl}charts/number-of-wallets" class="btn btn-success btn-block t-left btn-square btn-embossed btn-chart"><i class="fa fa-line-chart"></i>Total Wallets</a></p>
		      <p><a href="{$httpUrl}charts/new-wallets-per-day" class="btn btn-success btn-block t-left btn-square btn-embossed btn-chart"><i class="fa fa-line-chart"></i>New Wallets Per Day</a></p>
		      <p><a href="{$httpUrl}charts/addresses-by-balance" class="btn btn-success btn-block t-left btn-square btn-embossed btn-chart"><i class="fa fa-list"></i>Richlist</a></p>
		      <p><a href="{$httpUrl}charts/addresses-by-forged-blocks" class="btn btn-success btn-block t-left btn-square btn-embossed btn-chart"><i class="fa fa-list"></i>Top Block Forger</a></p>
		      <p><a href="{$httpUrl}charts/account-balance-distribution" class="btn btn-success btn-block t-left btn-square btn-embossed btn-chart"><i class="fa fa-bar-chart"></i>Balance Distribution</a></p>
		    </div>
		  </div>
		</div>
	      </div>
            </div>
          </div>
{include file="footer.tpl"}