{include file="header.tpl" siteTitle="Blocks mined on: {$blockdate}" navExplorer=" class='nav-active active'"}
          <div class="header">
            <h2><strong>Block Explorer</strong></h2>
	    <div class="breadcrumb-wrapper">
              <ol class="breadcrumb">
                <li><a href="{$httpRoot}">Home</a></li>		
                <li class="active">Block Explorer</li>
              </ol>
            </div>
          </div>
          <div class="row">
            <div class="col-xlg-12">
	      <div class="panel">
		<div class="panel-header">
		  <h3 class="t-center">
		    {if isset($blockdatePrev)}<small><a href="{$httpUrl}blocks/{$blockdatePrev}">Previous</a></small> &nbsp;{/if}
		    <strong>Blocks mined on:</strong> {$blockdate}
		    {if isset($blockdateNext)} &nbsp;<small><a href="{$httpUrl}blocks/{$blockdateNext}">Next</a></small>{/if}
		  </h3>
		</div>
		<div data-height="700" class="panel-content withScroll mCustomScrollbar p-t-0" style="height:700px;"><div style="position:relative; height:100%; overflow:hidden; max-width:100%;" id="mCSB_17" class="mCustomScrollBox mCS-light"><div style="position: relative; top: 0px;" class="mCSB_container">
		  <table class="table helper-margin">
		    <thead>
		      <tr>
			<th>Height</th>
			<th>Date</th>
			<th>Transactions</th>
			<th>Sent</th>
			<th>Fee</th>
			<th>Block Reward</th>
			<th>Size</th>
		      </tr>
		    </thead>
		    <tbody>
		      {$totalTransactions = 0}
		      {$totalSent = 0}
		      {$totalFee = 0}
		      {$totalReward = 0}
		      {$totalSize = 0}
		      {foreach $blockdata AS $block}
		      <tr>
			<td><a href="{$httpUrl}block/{$block.block}">{$block.height}</a></td>
			<td>{$block.blockdate}</td>
			<td>{$block.numberOfTransactions|number_format:0}</td>{$totalTransactions = $totalTransactions+$block.numberOfTransactions}
			<td>
			  {math equation="x * y" assign="btc" x=$block.totalAmountNQT y=$globalStats.burstBTC}
			  {math equation="x * y" assign="btcUSD" x=$btc y=$globalStats.btcUSD}
			  {math equation="x * y" assign="btcEUR" x=$btc y=$globalStats.btcEUR}                  
			  <abbr data-rel="tooltip" data-placement="right" data-html="true" data-original-title="$ {$btcUSD|number_format:2} / € {$btcEUR|number_format:2}<br>{$btc|number_format:8} BTC">{$block.totalAmountNQT|number_format:2} Burst</abbr>
			</td>{$totalSent = $totalSent+$block.totalAmountNQT}
			<td>{$block.totalFeeNQT|number_format:0} Burst</td>{$totalFee = $totalFee+$block.totalFeeNQT}
			<td>{$block.blockReward|number_format:0} Burst</td>{$totalReward = $totalReward+$block.blockReward}
			<td>{$block.payloadLength|number_format:2} KB</td>{$totalSize = $totalSize+$block.payloadLength}
		      </tr>
		      {/foreach}
		    </tbody>
		    <tfoot>
		      <tr>
			<td colspan="2"><strong>Total:</strong></td>
			<td><strong>{$totalTransactions|number_format:0}</strong></td>
			<td>
			  {math equation="x * y" assign="btc" x=$totalSent y=$globalStats.burstBTC}
			  {math equation="x * y" assign="btcUSD" x=$btc y=$globalStats.btcUSD}
			  {math equation="x * y" assign="btcEUR" x=$btc y=$globalStats.btcEUR}                  
			  <abbr data-rel="tooltip" data-placement="right" data-html="true" data-original-title="$ {$btcUSD|number_format:2} / € {$btcEUR|number_format:2}<br>{$btc|number_format:8} BTC">{$totalSent|number_format:2} Burst</abbr>
			</td>
			<td><strong>{$totalFee|number_format:0} Burst</strong></td>
			<td><strong>{$totalReward|number_format:0} Burst</strong></td>
			<td><strong>{$totalSize|number_format:0} KB</strong></td>
		      </tr>
		    </tfoot>
		  </table>
		</div><div style="position: absolute; display: block; opacity: 0;" class="mCSB_scrollTools"><div class="mCSB_draggerContainer"><div oncontextmenu="return false;" style="position: absolute; height: 192px; top: 0px;" class="mCSB_dragger"><div style="position: relative; line-height: 192px;" class="mCSB_dragger_bar"></div></div><div class="mCSB_draggerRail"></div></div></div></div></div>
	      </div>
            </div>
          </div>		    
{include file="footer.tpl" updatePage="1"}
