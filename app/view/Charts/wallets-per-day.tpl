{include file="header.tpl" siteTitle="New Wallets Per Day" navCharts=" class='nav-active active'"}           
          <div class="header">
            <h2><strong>Charts</strong> <small>New Wallets Per Day</small></h2>
	    <div class="breadcrumb-wrapper">
              <ol class="breadcrumb">
                <li><a href="{$httpRoot}">Home</a></li>	
		<li><a href="{$httpRoot}charts">Charts</a></li>
                <li class="active">New Wallets Per Day</li>
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
{include file="Ads/leader_middle.tpl"}
	      <div class="panel">
		<div class="panel-header">
		  <h3 class="panel-title"><strong>New Wallets Per Month</strong></h3>
		</div>
		<div class="panel-content p-t-0">
		  <table class="table table-striped">
		    <thead>
		      <tr>
			<th>Month/Year</th>
			<th>Number Of Wallets</th>
			<th>Average Per Day</th>
		      </tr>
		    </thead>
		    <tbody>
		      {$totalWallets = 0}
		      {$totalDays = 0}
		      {foreach $walletmonth AS $wallet}
		      <tr>
			<td>{$wallet.walletmonth}/{$wallet.walletyear}</td>
			<td>{$wallet.wallets|number_format:0}{$totalWallets = $totalWallets+$wallet.wallets}</td>
			<td>{$avgPerDay = round($wallet.wallets/$wallet.monthdays)}{$avgPerDay}{$totalDays = $totalDays+$wallet.monthdays}</td>
		      </tr>
		      {/foreach}
		    </tbody>
		    <tfoot>
		      <tr>
			<td><strong>Total:</strong></td>
			<td><strong>{$totalWallets|number_format:0}</strong></td>
			<td><strong>{$avgTotal = round($totalWallets/$totalDays)}{$avgTotal|number_format:0}</strong></td>
		      </tr>
		    </tfoot>
		  </table>
		</div>
	      </div>
            </div>
          </div>
{include file="footer.tpl" loadJSCharts="1" footerJS="charts_wallets_perday"}