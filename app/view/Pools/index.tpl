{include file="header.tpl" siteTitle="Pool Comparison: {$blockdate}"  navTools=" nav-active active" navPools=" class='active'"}
          <div class="header">
            <h2><strong>Pool Comparison</strong></h2>
	    <div class="breadcrumb-wrapper">
              <ol class="breadcrumb">
                <li><a href="{$httpRoot}">Home</a></li>		
                <li class="active">Pool Comparison</li>
              </ol>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-12">
	      <div class="panel">
		<div class="panel-header">
		  <h3 class="t-center">
                {if isset($blockdatePrev)}<small><a href="{$httpUrl}pools/{$blockdatePrev}">Previous</a></small> &nbsp;{/if}
                <strong>Pool comparison:</strong> {$blockdate}
                {if isset($blockdateNext)} &nbsp;<small><a href="{$httpUrl}pools/{$blockdateNext}">Next</a></small>{/if}
		  </h3>
		</div>
		<div class="panel-content p-t-0">
		  <table class="table table-striped">
		    <thead>
		      <tr>
			<th>Pool</th>
			<th>Active Miner</th>
			<th>Total Payouts</th>
			<th>Avg. Payout/Miner</th>
			<th>Balance</th>
		      </tr>
		    </thead>
		    <tbody>
		      {foreach $pooldata AS $pool}
		      <tr>
			<td><a href="{$httpUrl}r/{$pool.url}">{$pool.name}</a></td>
			<td>{$pool.miner}</td>
			<td>                  
			  {math equation="x * y" assign="btc" x=$pool.payout y=$globalStats.burstBTC}
			  {math equation="x * y" assign="btcUSD" x=$btc y=$globalStats.btcUSD}
			  {math equation="x * y" assign="btcEUR" x=$btc y=$globalStats.btcEUR}                  
			  <abbr data-rel="tooltip" data-placement="right" data-html="true" data-original-title="$ {$btcUSD|number_format:2} / € {$btcEUR|number_format:2}<br>{$btc|number_format:8} BTC">{$pool.payout|number_format:2} Burst</abbr>
			<td>
			  {math equation="x / y" assign="avgpayout" x=$pool.payout y=$pool.miner}
			  {math equation="x * y" assign="btc" x=$avgpayout y=$globalStats.burstBTC}
			  {math equation="x * y" assign="btcUSD" x=$btc y=$globalStats.btcUSD}
			  {math equation="x * y" assign="btcEUR" x=$btc y=$globalStats.btcEUR}                  
			  <abbr data-rel="tooltip" data-placement="right" data-html="true" data-original-title="$ {$btcUSD|number_format:2} / € {$btcEUR|number_format:2}<br>{$btc|number_format:8} BTC">{$avgpayout|number_format:2} Burst</abbr>
			</td>
			<td><a href="{$httpUrl}address/{$pool.addr}">{$pool.balance|number_format:0} Burst</a></td>
		      </tr>
		      {/foreach}
		    </tbody>
		  </table>
		</div>
	      </div>
	    </div>
	  </div>
{include file="footer.tpl"}
