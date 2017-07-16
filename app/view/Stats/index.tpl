{include file="header.tpl" siteTitle="Burstcoin Stats" navStats=" class='nav-active active'"}           
          <div class="header">
	    <h2><strong>Stats</strong></h2>
	    <div class="breadcrumb-wrapper">
              <ol class="breadcrumb">
                <li><a href="{$httpRoot}">Home</a></li>	
                <li class="active">Stats</li>
              </ol>
            </div>
          </div>
          <div class="row">
	    <div class="col-xlg-4 col-lg-4 col-md-6 col-sm-6 col-xs-12">
	      <div class="panel">
		<div class="panel-content widget-info">
		  <div class="row">
		    <div class="left">
		      <i class="fa fa-cube bg-yellow"></i>
		    </div>
		    <div class="right">
		      <p data-to="{$lastBlock|number_format:0:"":""}" data-from="0" class="number countup">{$lastBlock|number_format:0}</p>
		      <p class="text">Last Block</p>
		    </div>
		  </div>
		</div>
	      </div>
	    </div>
	    <div class="col-xlg-4 col-lg-4 col-md-6 col-sm-6 col-xs-12">
	      <div class="panel">
		<div class="panel-content widget-info">
		  <div class="row">
		    <div class="left">
		      <i class="fa fa-cubes bg-yellow" style="font-size:19px;"></i>
		    </div>
		    <div class="right">
		      <p data-to="{$blocksMined|number_format:0:"":""}" data-from="0" class="number countup">{$blocksMined|number_format:0}</p>
		      <p class="text">Blocks Mined (Last 24h)</p>
		    </div>
		  </div>
		</div>
	      </div>
	    </div>
	    <div class="col-xlg-4 col-lg-4 col-md-6 col-sm-6 col-xs-12">
	      <div class="panel">
		<div class="panel-content widget-info">
		  <div class="row">
		    <div class="left">
		      <a href="{$httpUrl}charts/burstcoins-mined-per-day">
			<i class="fa fa-cubes bg-yellow" style="font-size:19px;"></i>
		      </a>
		    </div>
		    <div class="right">
		      <div class="clearfix">
			<p data-to="{$blockReward|number_format:0:"":""}" data-from="0" class="number countup pull-left">{$blockReward|number_format:0}</p>
			<p class="number pull-left m-l-5">Burst</p>
		      </div>
		      <p class="text">Coins Mined (Last 24h)</p>
		    </div>
		  </div>
		</div>
	      </div>
	    </div>
	    <div class="col-xlg-4 col-lg-4 col-md-6 col-sm-6 col-xs-12">
	      <div class="panel">
		<div class="panel-content widget-info">
		  <div class="row">
		    <div class="left">
		      <a href="{$httpUrl}charts/total-burstcoins">
			<i class="icon-layers bg-blue"></i>
		      </a>
		    </div>
		    <div class="right">
		      <div class="clearfix">
			<p data-to="{$totalMinedCoins|number_format:0:"":""}" data-from="0" class="number countup pull-left">{$totalMinedCoins|number_format:0}</p>
			<p class="number pull-left m-l-5">Burst</p>
		      </div>
		      <div class="clearfix">
		        <p class="text pull-left">Available Supply</p>
			<p class="text pull-left m-l-5" style="text-transform:none;">
			  <i class="icon-info" data-rel="tooltip" data-placement="bottom" data-html="true" data-original-title="Total Supply: 2,158,812,800 Burst ({$totalMinedCoinsPercent}% Mined / {$totalUnminedCoinsPercent}% Unmined)"></i>			  
			</p>
		      </div>
		    </div>
		  </div>
		</div>
	      </div>
	    </div>
	    <div class="col-xlg-4 col-lg-4 col-md-6 col-sm-6 col-xs-12">
	      <div class="panel">
		<div class="panel-content widget-info">
		  <div class="row">
		    <div class="left">
		      <a href="{$httpUrl}charts/number-of-transactions-per-day">
			<i class="glyphicon glyphicon-transfer bg-blue" style="font-size:20px;"></i>
		      </a>
		    </div>
		    <div class="right">
		      <p data-to="{$newTransactions|number_format:0:"":""}" data-from="0" class="number countup">{$transactions|number_format:0}</p>
		      <p class="text">New Transactions (Last 24h)</p>
		    </div>
		  </div>
		</div>
	      </div>
	    </div>
	    <div class="col-xlg-4 col-lg-4 col-md-6 col-sm-6 col-xs-12">
	      <div class="panel">
		<div class="panel-content widget-info">
		  <div class="row">
		    <div class="left">
		      <a href="{$httpUrl}charts/new-wallets-per-day">
			<i class="icon-wallet bg-blue"></i>
		      </a>
		    </div>
		    <div class="right">
		      <p data-to="{$newWallets|number_format:0:"":""}" data-from="0" class="number countup">{$newWallets|number_format:0}</p>
		      <p class="text">New Wallets (Last 24h)</p>
		    </div>
		  </div>
		</div>
	      </div>
	    </div>
	  </div>
{include file="Ads/leader_middle.tpl"}
	  <div class="row">
	    <div class="col-xlg-4 col-lg-4 col-md-6 col-sm-6 col-xs-12">
	      <div class="panel">
		<div class="panel-content widget-info">
		  <div class="row">
		    <div class="left">
		      <i class="fa fa-bitcoin bg-green"></i>
		    </div>
		    <div class="right">
		      <p class="number">{$globalStats.burstBTC}</p>
		      <p class="text">Market Price (weighted)</p>
		    </div>
		  </div>
		</div>
	      </div>
	    </div>
	    <div class="col-xlg-4 col-lg-4 col-md-6 col-sm-6 col-xs-12">
	      <div class="panel">
		<div class="panel-content widget-info">
		  <div class="row">
		    <div class="left">
		      <i class="fa fa-dollar bg-green"></i>
		    </div>
		    <div class="right">
		      <div class="clearfix">
			{math equation="x * y" assign="btc" x=$totalMinedCoins y=$globalStats.burstBTC}
			<p class="number pull-left">$</p>
			<p data-to="{$marketCapUSD|number_format:0:"":""}" data-from="0" class="number countup pull-left m-l-5">{$marketCapUSD|number_format:0}</p>
		      </div>
		      <div class="clearfix">
		        <p class="text pull-left">Market Cap</p>
			<p class="text pull-left m-l-5" style="text-transform:none;">
			  <i class="icon-info" data-rel="tooltip" data-placement="bottom" data-html="true" data-original-title="{$btc|number_format:0} BTC"></i>			  
			</p>
		      </div>
		    </div>
		  </div>
		</div>
	      </div>
	    </div>
	    <div class="col-xlg-4 col-lg-4 col-md-6 col-sm-6 col-xs-12">
	      <div class="panel">
		<div class="panel-content widget-info">
		  <div class="row">
		    <div class="left">
		      <a href="{$httpUrl}charts/estimated-network-size">
			<i class="fa fa-bolt bg-green"></i>
		      </a>
		    </div>
		    <div class="right">
		      <div class="clearfix">
			<p data-to="{$networksize|number_format:0:"":""}" data-from="0" class="number countup pull-left">{$networksize} TB</p>
			<p class="number pull-left m-l-5">TB</p>
		      </div>
		      <p class="text">Estimated Network Size</p>
		    </div>
		  </div>
		</div>
	      </div>
	    </div>
	  </div>
{include file="footer.tpl" loadJSCountUp="1" updatePage="1"}