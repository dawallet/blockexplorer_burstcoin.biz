{include file="header.tpl" siteTitle="Top Block Forger" navCharts=" class='nav-active active'"}           
          <div class="header">
	    <h2>
	      <strong>Top Block Forger</strong> 
	      <small>({if $statsPeriod == "day"}Last 24 hours{elseif $statsPeriod == "week"}Last 7 days{elseif $statsPeriod == "month"}Last 30 days{else}All Time{/if})</small> 
	      <div class="btn-group">
		<button data-toggle="dropdown" class="btn btn-primary btn-sm dropdown-toggle" type="button" aria-expanded="false">Change <span class="caret"></span></button>
		<span class="dropdown-arrow"></span>
		<ul role="menu" class="dropdown-menu">
		  <li><a href="{$httpUrl}charts/addresses-by-forged-blocks/day">24 hours</a></li>
		  <li><a href="{$httpUrl}charts/addresses-by-forged-blocks/week">7 days</a></li>
		  <li><a href="{$httpUrl}charts/addresses-by-forged-blocks/month">30 days</a></li>
		  <li><a href="{$httpUrl}charts/addresses-by-forged-blocks">All Time</a></li>
		</ul>
	      </div>	
	    </h2>
	    <div class="breadcrumb-wrapper">
              <ol class="breadcrumb">
                <li><a href="{$httpRoot}">Home</a></li>	
		<li><a href="{$httpRoot}charts">Charts</a></li>
                <li class="active">Top Block Forger</li>
              </ol>
            </div>
          </div>
          <div class="row">
            <div class="col-xlg-12">
	      <div class="panel">
		<div data-height="700" class="panel-content withScroll mCustomScrollbar" style="height:700px;"><div style="position:relative; height:100%; overflow:hidden; max-width:100%;" id="mCSB_17" class="mCustomScrollBox mCS-light"><div style="position: relative; top: 0px;" class="mCSB_container">
		  <table class="table table-striped">
		    <thead>
		      <tr>
			<th>Rank</th>
			<th>Address</th>
			<th>Forged Blocks</th>
			<th>Share Ratio</th>
			<th>Balance</th>
		      </tr>
		    </thead>
		    <tbody>
		      {foreach $addressdata AS $addr}
		      <tr>
			<td>{counter}</td>
			<td><a href="{$httpUrl}address/{$addr.account}">{$addr.accountRS}</a>{if $addr.account == 13383190289605706987} <small>&nbsp;(Exchange: <a href="https://bittrex.com/Market/?MarketName=BTC-BURST" target="_blank">Bittrex</a>)</small>{elseif $addr.account == 5810532812037266198} <small>&nbsp;(Exchange: <a href="https://poloniex.com/exchange#btc_burst" target="_blank">Poloniex</a>)</small>{elseif $addr.account == 11073283704811863744}<small>&nbsp;(Exchange: <a href="https://c-cex.com/?p=burst-btc" target="_blank">C-CEX</a>)</small>{elseif !empty($addr.name)} <small>&nbsp;({$addr.name|escape|truncate:30:"..."})</small>{/if}</td>
			<td>{$addr.blocks|number_format:0}</td>
			<td>
			  {math equation="x / y * 100" assign="shareRatio" x=$addr.blocks y=$blockdata.blocks}
			  {$shareRatio|number_format:2} %
			</td>
			<td>
			  {math equation="x * y" assign="btc" x=$addr.unconfirmedBalanceNQT y=$globalStats.burstBTC}
			  {math equation="x * y" assign="btcUSD" x=$btc y=$globalStats.btcUSD}
			  {math equation="x * y" assign="btcEUR" x=$btc y=$globalStats.btcEUR}
			  <abbr data-rel="tooltip" data-placement="left" data-html="true" data-original-title="$ {$btcUSD|number_format:2} / € {$btcEUR|number_format:2}">{$addr.unconfirmedBalanceNQT|number_format:2} Burst</abbr>
			</td>
		      </tr>
		      {/foreach}
		    </tbody>
		  </table>
		</div><div style="position: absolute; display: block; opacity: 0;" class="mCSB_scrollTools"><div class="mCSB_draggerContainer"><div oncontextmenu="return false;" style="position: absolute; height: 192px; top: 0px;" class="mCSB_dragger"><div style="position: relative; line-height: 192px;" class="mCSB_dragger_bar"></div></div><div class="mCSB_draggerRail"></div></div></div></div></div>
	      </div>
            </div>
          </div>
{include file="footer.tpl"}