{include file="header.tpl" siteTitle="Address {$addressdata.accountRS}" navExplorer=" class='nav-active active'"}
          <div class="header">
            <h2><strong>Block Explorer</strong> <small>{$addressdata.accountRS}</small></h2>
	    <div class="breadcrumb-wrapper">
              <ol class="breadcrumb">
                <li><a href="{$httpRoot}">Home</a></li>	
		<li><a href="{$httpUrl}blocks">Block Explorer</a></li>
                <li class="active">Address</li>
              </ol>
            </div>
          </div>
	  <div class="row">
	    <div class="col-xlg-12">
	      <div class="panel">
		<div class="panel-content">
		  <table class="table table-striped">
		    <tbody>
		      <tr>
			<td>Address</td>
			<td>{$addressdata.accountRS}</td>
			<td class="border-left border-right text-center" style="border-color:#ddd;" rowspan="3"><img src="{$httpRoot}media/qr/{$addressdata.accountRS}.png" alt="{$addressdata.accountRS}"></td>
		      </tr>
		      <tr>
			<td>Numeric Account ID</td>
			<td>{$addressdata.account}</td>
		      </tr>
		      <tr>
			<td>Public Key</td>
			<td>{$addressdata.publicKey|escape}</td>
		      </tr>
		      {if !empty($addressdata.name)}
		      <tr>
			<td>Name</td>
			<td colspan="2">{$addressdata.name|escape}</td>
		      </tr>
		      {/if}
		      <tr>
			<td>First transaction</td>
			<td colspan="2">{$addressdata.accountFirstseen} &nbsp; <small><em>({$firstseenAge})</em></small></td>
		      </tr>
		      <tr>
			<td>Last transaction</td>
			<td colspan="2">{$addressdata.lastActivity} &nbsp; <small><em>({$lastActivityAge})</em></small></td>
		      </tr>
		      <tr>
			<td>No. Transactions</td>
			<td colspan="2"><a href="{$httpUrl}address/chart/transactions/{$addressdata.account}">{$totalTransactions|number_format:0}</a></td>
		      </tr>
		      <tr>
			<td>Total Received</td>
			<td colspan="2"><a href="{$httpUrl}address/chart/total-received/{$addressdata.account}">{$totalReceived|number_format:2} Burst</a></td>
		      </tr>
		      <tr>
			<td>Total Sent</td>
			<td colspan="2"><a href="{$httpUrl}address/chart/total-sent/{$addressdata.account}">{$totalSent|number_format:2} Burst</a></td>
		      </tr>
		      <tr>
			<td>Balance</td>
			<td colspan="2">
			  {math equation="x * y" assign="btc" x=$addressdata.unconfirmedBalanceNQT y=$globalStats.burstBTC}
			  {math equation="x * y" assign="btcUSD" x=$btc y=$globalStats.btcUSD}
			  {math equation="x * y" assign="btcEUR" x=$btc y=$globalStats.btcEUR}
			  <strong>{if $addressdata.unconfirmedBalanceNQT > 0}<span class="text-success">{/if}<abbr data-rel="tooltip" data-placement="right" data-html="true" data-original-title="$ {$btcUSD|number_format:2} / € {$btcEUR|number_format:2}">{$addressdata.unconfirmedBalanceNQT|number_format:2} Burst</abbr>{if $addressdata.unconfirmedBalanceNQT > 0}</span>{/if} <small>({$btc|number_format:8} BTC)</small></strong>
			</td>
		      </tr>                 
		      <tr>
			<td>Mined Blocks</td>
			<td colspan="2"><a href="{$httpUrl}address/chart/forged-blocks/{$addressdata.account}">{$forgedBlocks|number_format:0}</a> &nbsp; <small><em>({$totalMinedBurst|number_format:0} Burst)</em></small></td>
		      </tr>
		      <tr>
			<td>Pool Mined Balance</td>
			<td colspan="2"><a href="{$httpUrl}address/chart/pool-mined/{$addressdata.account}">{$poolMinedBalance|number_format:0} Burst</a></td>
		      </tr>
		      <tr>
			<td>Solo Mined Balance</td>
			<td colspan="2">{$addressdata.forgedBalanceNQT|number_format:0} Burst</td>
		      </tr>
		      <tr>
			<td>Rich List Rank</td>
			{$richListPage = ceil($richListRank/100)}
			<td colspan="2"><a href="{$httpUrl}charts/addresses-by-balance/{$richListPage}-{$richListRank}">{$richListRank|number_format:0}</a></td>
		      </tr> 
		    </tbody>
		  </table>
		</div>
	      </div>
	    </div>
{include file="Ads/leader_middle.tpl"} 
	    <div class="row" id="history">
	      <div class="col-xlg-12">
		{if $forgedBlocks > 0}
		<div class="col-xs-12 col-lg-3">
		  <div class="panel">
		    <div class="panel-header">
		      <h3><strong>Forged Blocks</strong> &nbsp;<a href="{$httpUrl}address/export/forged-blocks/{$addressdata.account}"><i class="fa fa-file-excel-o"></i></a> &nbsp;{if $blockPages > 1}<small>{if $pageBlocks < $blockPages}<a href="{$httpRoot}address/{$addressdata.account}-blocks-{math equation="x + 1" x=$pageBlocks}#history"><span class="glyphicon glyphicon-chevron-left"></span></a>{/if} {if $pageBlocks > 1}<a href="{$httpRoot}address/{$addressdata.account}-blocks-{math equation="x - 1" x=$pageBlocks}#history"><span class="glyphicon glyphicon-chevron-right"></span></a>{/if}</small>{/if}</h3>
		    </div>
		    <div class="panel-content p-t-0">
		      <table class="table tabel-striped">
			<tbody>
			  {foreach $blockdata AS $block}
			  <tr>
			    <td>
			      <h3 class="m-0"><strong>Block <a href="{$httpUrl}block/{$block.block}">#{$block.height}</a></strong></h3>
			      Reward: {$block.blockReward|number_format:0} Burst<br>
			      Fee: {$block.totalFeeNQT|number_format:0} Burst<br>
			      <em>{$block.blockdate} ago</em>
			    </td>
			  </tr>
			  {/foreach}
			</tbody>
		      </table>
		    </div>
		  </div>
		</div>
		{/if}
		<div class="col-xs-12 {if $forgedBlocks > 0}col-lg-9{else}col-lg-12{/if}">
		  <div class="panel">
		    <div class="panel-header">
		      <h3><strong>Transactions</strong> &nbsp;<a href="{$httpUrl}address/export/transactions/{$addressdata.account}"><i class="fa fa-file-excel-o"></i></a> &nbsp;{if $transactionsPages > 1}<small>{if $pageTransactions < $transactionsPages}<a href="{$httpRoot}address/{$addressdata.account}-transactions-{math equation="x + 1" x=$pageTransactions}#history"><span class="glyphicon glyphicon-chevron-left"></span></a>{/if} {if $pageTransactions > 1}<a href="{$httpRoot}address/{$addressdata.account}-transactions-{math equation="x - 1" x=$pageTransactions}#history"><span class="glyphicon glyphicon-chevron-right"></span></a>{/if}</small>{/if}</h2>
		    </div>
		    <div class="panel-content p-t-0">
		      <table class="table table-striped">
			<tbody>
			  {foreach $transactiondata AS $transaction}
			  <tr>
			    <td><a href="{$httpUrl}transaction/{$transaction.transaction}">{$transaction.transaction}</a></td>
			    <td>
			      {math equation="x * y" assign="btc" x=$transaction.amountNQT y=$globalStats.burstBTC}
			      {math equation="x * y" assign="btcUSD" x=$btc y=$globalStats.btcUSD}
			      {math equation="x * y" assign="btcEUR" x=$btc y=$globalStats.btcEUR}
			      {if $transaction.recipient == $addressdata.account}<span class="text-success"><span class="glyphicon glyphicon-chevron-right"></span> <abbr data-rel="tooltip" data-placement="right" data-html="true" data-original-title="{$transaction.amountNQT|number_format:8} Burst + {$transaction.feeNQT|number_format:0} Burst Fee<br>$ {$btcUSD|number_format:2} / € {$btcEUR|number_format:2}<br>{$btc|number_format:8} BTC">{else}<span class="text-danger"><span class="glyphicon glyphicon-chevron-left"></span> <abbr data-rel="tooltip" data-placement="right" data-html="true" data-original-title="{$transaction.amountNQT|number_format:8} Burst + {$transaction.feeNQT|number_format:0} Burst Fee<br>$ {$btcUSD|number_format:2} / € {$btcEUR|number_format:2}<br>{$btc|number_format:8} BTC">{/if}{$transaction.amountNQT|number_format:2} Burst</abbr></span></td>
			    <td>{if $transaction.recipient == $addressdata.account}<a href="{$httpUrl}address/{$transaction.sender}">{$transaction.senderRS}</a>{else}{if empty($transaction.recipient)}/{else}<a href="{$httpUrl}address/{$transaction.recipient}">{$transaction.recipientRS}</a>{/if}{/if}</td>
			    <td>{$transaction.transactiondate}</td>
			  </tr>
			  {/foreach}
			</tbody>
		      </table>
		    </div>
		  </div>
		</div>
	      </div>
	    </div>
	  </div>
{include file="footer.tpl" updatePage="1"}