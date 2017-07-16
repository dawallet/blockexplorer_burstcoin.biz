{include file="header.tpl" siteTitle="Burstcoin Block #{$blockdata.height}" navExplorer=" class='nav-active active'"}
          <div class="header">
            <h2><strong>Block Explorer</strong> <small>Block #{$blockdata.height}</small></h2>
	    <div class="breadcrumb-wrapper">
              <ol class="breadcrumb">
                <li><a href="{$httpRoot}">Home</a></li>
		<li><a href="{$httpUrl}blocks">Block Explorer</a></li>
		<li class="active">Block #{$blockdata.height}</li>
              </ol>
            </div>
          </div>
	  <div class="row">
	    <div class="col-xlg-12">
	      <div class="panel">
		<div class="panel-content">
		  <div class="row">
		    <div class="col-xs-12 col-md-6">
		      <table class="table table-striped">
			<tbody>
			  <tr>
			    <td>Height</td>
			    <td>{$blockdata.height}</td>
			  </tr>
			  <tr>
			    <td>Number Of Transactions</td>
			    <td>{$blockdata.numberOfTransactions|number_format:0}</td>
			  </tr>
			  <tr>
			    <td>Total Amount</td>
			    <td>
			      {math equation="x * y" assign="btc" x=$blockdata.totalAmountNQT y=$globalStats.burstBTC}
			      {math equation="x * y" assign="btcUSD" x=$btc y=$globalStats.btcUSD}
			      {math equation="x * y" assign="btcEUR" x=$btc y=$globalStats.btcEUR}
			      <abbr data-rel="tooltip" data-placement="right" data-html="true" data-original-title="$ {$btcUSD|number_format:2} / € {$btcEUR|number_format:2}">{$blockdata.totalAmountNQT|number_format:8} Burst</abbr> <small>({$btc|number_format:8} BTC)</small>
			    </td>
			  </tr>
			  <tr>
			    <td>Transaction Fees</td>
			    <td>{$blockdata.totalFeeNQT|number_format:0} Burst</td>
			  </tr>
			  <tr>
			    <td>Timestamp</td>
			    <td>{$blockdata.blockdate}</td>
			  </tr>
			  <tr>
			    <td>Generator</td>
			    <td><a href="{$httpUrl}address/{$blockdata.generator}">{$blockdata.accountRS}</a></td>
			  </tr>
			  <tr>
			    <td>Block Generation Time</td>
			    <td>{$duration|number_format:2} minutes</td>
			  </tr>
			  <tr>
			    <td>Base Target</td>
			    <td>{$blockdata.baseTarget}</td>
			  </tr>
			  <tr>
			    <td>Size</td>
			    <td>{$payloadLength|number_format:2} KB</td>
			  </tr>
			  <tr>
			    <td>Version</td>
			    <td>{$blockdata.version}</td>
			  </tr>
			  <tr>
			    <td>Nonce</td>
			    <td>{$blockdata.nonce}</td>
			  </tr>
			  <tr>
			    <td>Block Reward</td>
			    <td>{$blockdata.blockReward|number_format:2} Burst</td>
			  </tr>
			</tbody>
		      </table>
		    </div>
		    <div class="col-xs-12 col-md-6 text-center">
		      <br>
{include file="Ads/block_first.tpl"} 
		    </div>
		  </div>
		  <div class="row">
		    <div class="col-xlg-12">
		      <table class="table table-striped">
			<tbody>
			  <tr>
			    <td style="min-width:150px;">Block Signature</td>
			    <td>{$blockdata.blockSignature|wordwrap:50:"\n":true}</td>
			  </tr>
			  <tr>
			    <td>Previous Block</td>
			    <td>{if isset($previousblock)}<a href="{$httpUrl}block/{$previousblock.block}">{$previousblock.block}</a>{/if}</td>
			  </tr>
			  <tr>
			    <td>Next Block</td>
			    <td>{if isset($nextblock)}<a href="{$httpUrl}block/{$nextblock.block}">{$nextblock.block}</a>{/if}</td>
			  </tr>
			</tbody>
		      </table>
		    </div>
		  </div>
		</div>
	      </div>
	      {if $blockdata.numberOfTransactions > 0}
{include file="Ads/leader_middle.tpl"} 
	      <div class="row">
		<div class="col-xlg-12">
		  <br>
		  <div class="panel">
		    <div class="panel-header">
		      <h3><strong>Transactions</strong> <small>Transactions contained within this block</small></h3>
		    </div>
		    <div class="panel-content p-t-0">
		      <table class="table table-striped">
			<thead>
			  <tr>
			    <th>Transaction ID</th>
			    <th>Sender</th>
			    <th>Recipient</th>
			    <th>Amount</th>
			    <th>Fee</th>
			    <th>Transaction Date</th>
			  </tr>
			</thead>
			<tbody>
			{foreach $transactiondata AS $transaction}
			  <tr>
			    <td><a href="{$httpUrl}transaction/{$transaction.transaction}">{$transaction.transaction}</a></td>
			    <td><a href="{$httpUrl}address/{$transaction.sender}">{$transaction.senderRS}</a></td>
			    <td>{if empty($transaction.recipient)}/{else}<a href="{$httpUrl}address/{$transaction.recipient}">{$transaction.recipientRS}</a>{/if}</td>
			    <td>
			      {math equation="x * y" assign="btc" x=$transaction.amountNQT y=$globalStats.burstBTC}
			      {math equation="x * y" assign="btcUSD" x=$btc y=$globalStats.btcUSD}
			      {math equation="x * y" assign="btcEUR" x=$btc y=$globalStats.btcEUR}
			      <abbr data-rel="tooltip" data-placement="right" data-html="true" data-original-title="$ {$btcUSD|number_format:2} / € {$btcEUR|number_format:2}<br>{$btc|number_format:8} BTC">{$transaction.amountNQT} Burst</abbr>
			    </td>
			    <td>{$transaction.feeNQT|number_format:0} Burst</td>
			    <td>{$transaction.transactiondate}</td>
			  </tr>
			{/foreach}
			</tbody>
		      </table>
		    </div>
		  </div>
		</div>
	      </div>
	      {/if}
	    </div>
	  </div>
{include file="footer.tpl" updatePage="1"}