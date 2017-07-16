{include file="header.tpl" siteTitle="Richlist" navCharts=" class='nav-active active'"}           
          <div class="header">
            <h2><strong>Wallets By Received Burst (Top 500)</strong></h2>
	    <div class="breadcrumb-wrapper">
              <ol class="breadcrumb">
                <li><a href="{$httpRoot}">Home</a></li>	
		<li><a href="{$httpRoot}charts">Charts</a></li>
                <li class="active">Wallets By Received Burst</li>
              </ol>
            </div>
          </div>
          <div class="row">
            <div class="col-xlg-12">
	      <div class="panel">
		<div data-height="700" class="panel-content withScroll mCustomScrollbar" style="height:700px;"><div style="position:relative; height:100%; overflow:hidden; max-width:100%;" id="mCSB_17" class="mCustomScrollBox mCS-light"><div style="position: relative; top: 0px;" class="mCSB_container">
		  <table class="table helper-margin">
		    <thead>
		      <tr>
			<th>Rank</th>
			<th>Address</th>
			<th>Transactions</th>
			<th class="t-right">Total received</th>
			<th class="t-right">Balance</th>
		      </tr>
		    </thead>
		    <tbody>
		      {foreach $addressdata AS $addr}
		      <tr>
			<td>{counter}</td>
			<td><a href="{$httpUrl}address/{$addr.account}">{$addr.accountRS}</a>{if $addr.account == 13383190289605706987} <small>&nbsp;(Exchange: <a href="https://bittrex.com/Market/?MarketName=BTC-BURST" target="_blank">Bittrex</a>)</small>{elseif $addr.account == 5810532812037266198} <small>&nbsp;(Exchange: <a href="https://poloniex.com/exchange#btc_burst" target="_blank">Poloniex</a>)</small>{elseif $addr.account == 11073283704811863744}<small>&nbsp;(Exchange: <a href="https://c-cex.com/?p=burst-btc" target="_blank">C-CEX</a>)</small>{elseif !empty($addr.name)} <small>&nbsp;({$addr.name|escape|truncate:30:"..."})</small>{/if}</td>
			<td>{$addr.transactions|number_format:0}</td>
			<td class="t-right">{$addr.totalreceived|number_format:2} Burst</td>
			<td class="t-right">
			  {math equation="x * y" assign="btc" x=$addr.unconfirmedBalanceNQT y=$globalStats.burstBTC}
			  {math equation="x * y" assign="btcUSD" x=$btc y=$globalStats.btcUSD}
			  {math equation="x * y" assign="btcEUR" x=$btc y=$globalStats.btcEUR}
			  <abbr data-rel="tooltip" data-placement="right" data-html="true" data-original-title="$ {$btcUSD|number_format:2} / € {$btcEUR|number_format:2}">{$addr.unconfirmedBalanceNQT|number_format:2} Burst</abbr> <small>({$btc|number_format:8} BTC)</small>
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