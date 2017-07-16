{include file="header.tpl" navHome=" class='nav-active active'"}
	  {$errorMsg}
          <div class="header">
            <h2><strong>Home</strong> <small>Welcome to Burstcoin.biz</small></h2>
          </div>
          <div class="row">
            <div class="col-xlg-12">
	      <div class="panel">
		<div class="panel-header">
		  <h3><strong><i class="icon-layers"></i>Recent Blocks</strong> <small><a href="{$httpRoot}blocks">more</a></small></h3>
		</div>
		<div data-height="450" class="panel-content withScroll mCustomScrollbar p-t-0" style="height:450px;"><div style="position:relative; height:100%; overflow:hidden; max-width:100%;" id="mCSB_17" class="mCustomScrollBox mCS-light"><div style="position: relative; top: 0px;" class="mCSB_container">
		  <table class="table helper-margin">
		    <thead>
		      <tr>
			<th>Height</th>
			<th>Age</th>
			<th>Transactions</th>
			<th>Sent + Fee</th>
			<th>Size</th>
		      </tr>
		    </thead>
		    <tbody>
		      {foreach $blockdata AS $block}
		      <tr>
			<td><a href="{$httpUrl}block/{$block.block}">{$block.height}</a></td>
			<td>{$block.age}</td>
			<td>{$block.numberOfTransactions}</td>
			<td>
			  {math equation="x * y" assign="btc" x=$block.totalAmountNQT y=$globalStats.burstBTC}
			  {math equation="x * y" assign="btcUSD" x=$btc y=$globalStats.btcUSD}
			  {math equation="x * y" assign="btcEUR" x=$btc y=$globalStats.btcEUR}
			  <abbr data-rel="tooltip" data-placement="right" data-html="true" data-original-title="$ {$btcUSD|number_format:2} / € {$btcEUR|number_format:2}<br>{$btc|number_format:8} BTC">{$block.totalAmountNQT|number_format:2} Burst</abbr>
			  + {$block.totalFeeNQT|number_format:0} Burst
			</td>
			<td>{$block.payloadLength|number_format:2} KB</td>
		      </tr>
		      {/foreach}
		    </tbody>
		  </table>
		</div><div style="position: absolute; display: block; opacity: 0;" class="mCSB_scrollTools"><div class="mCSB_draggerContainer"><div oncontextmenu="return false;" style="position: absolute; height: 192px; top: 0px;" class="mCSB_dragger"><div style="position: relative; line-height: 192px;" class="mCSB_dragger_bar"></div></div><div class="mCSB_draggerRail"></div></div></div></div></div>
	      </div>
            </div>
          </div>
		    
{include file="Ads/leader_middle.tpl"}
		    
          <div class="row">
            <div class="col-xlg-12">
              <div class="row">
                <div class="col-sm-3 col-xs-6">
                  <a href="{$httpRoot}charts/mined-burstcoins" class="panel">
                    <div class="panel-content widget-small bg-primary">
                      <div class="title">
                        <h1>Total Supply</h1>
                        <span class="m-t-5">{$globalStatsTotalSupply|number_format:0} Burst</span>
			<p>&nbsp;</p>
                      </div>
                      <div class="content">
                        <div id="stock-supply-sm"></div>
                      </div>
                    </div>
                  </a>
                </div>
                <div class="col-sm-3 col-xs-6">
                  <a href="{$httpRoot}charts/number-of-transactions-per-day" class="panel">
                    <div class="panel-content widget-small bg-blue">
                      <div class="title">
                        <h1>Transactions</h1>
                        <span class="m-t-5">{$globalStatsTotalTransactions|number_format:0}</span>
			<p>&nbsp;</p>
                      </div>
                      <div class="content">
                        <div id="stock-transactions-sm"></div>
                      </div>
                    </div>
                  </a>
                </div>
                <div class="col-sm-3 col-xs-6">
                  <a href="{$httpRoot}charts/number-of-wallets" class="panel">
                    <div class="panel-content widget-small bg-green">
                      <div class="title">
                        <h1>Wallets</h1>
                        <span class="m-t-5">{$globalStatsTotalWallets|number_format:0}</span>
			<p>&nbsp;</p>
                      </div>
                      <div class="content">
                        <div id="stock-wallets-sm"></div>
                      </div>
                    </div>
                  </a>
                </div>
                <div class="col-sm-3 col-xs-6">
                  <a href="{$httpRoot}charts/estimated-network-size" class="panel">
                    <div class="panel-content widget-small bg-purple">
                      <div class="title">
                        <h1>Network Size</h1>
                        <span class="m-t-5">{$globalStatsNetworkSize|number_format:0} TB</span>
			<p>&nbsp;</p>
                      </div>
                      <div class="content">
                        <div id="stock-network-sm"></div>
                      </div>
                    </div>
                  </a>
                </div>
              </div>
            </div>
          </div>
{include file="footer.tpl" loadJSCharts="1" footerJS="home" updatePage="1"}