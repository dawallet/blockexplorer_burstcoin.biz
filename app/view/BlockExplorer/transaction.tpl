{include file="header.tpl" siteTitle="Transaction {$transactiondata.transaction}" navExplorer=" class='nav-active active'"}
          <div class="header">
            <h2><strong>Block Explorer</strong> <small>Transaction {$transactiondata.transaction}</small></h2>
	    <div class="breadcrumb-wrapper">
              <ol class="breadcrumb">
                <li><a href="{$httpRoot}">Home</a></li>
		<li><a href="{$httpUrl}blocks">Block Explorer</a></li>
		<li class="active">Transaction</li>
              </ol>
            </div>
          </div>
	  <div class="row">
	    <div class="col-xlg-12">
	      <div class="panel">
		<div class="panel-header">
		  <h3><strong>Transaction</strong> {$transactiondata.transaction}</h2>
		</div>
		<div class="panel-content p-t-0">
		  <div class="row">
		    <div class="col-xs-12 col-sm-12 col-md-6">
		      <table class="table table-striped">
			<tbody>
			  <tr>
			    <td>Sender</td>
			    <td><a href="{$httpUrl}address/{$transactiondata.sender}">{$transactiondata.senderRS}</a></td>
			  </tr>
			  <tr>
			    <td>Recipient</td>
			    <td>{if empty($transactiondata.recipient)}/{else}<a href="{$httpUrl}address/{$transactiondata.recipient}">{$transactiondata.recipientRS}</a>{/if}</td>
			  </tr>
			  <tr>
			    <td>Amount</td>
			    <td>
			      {math equation="x * y" assign="btc" x=$transactiondata.amountNQT y=$globalStats.burstBTC}
			      {math equation="x * y" assign="btcUSD" x=$btc y=$globalStats.btcUSD}
			      {math equation="x * y" assign="btcEUR" x=$btc y=$globalStats.btcEUR}
			      <abbr data-rel="tooltip" data-placement="right" data-html="true" data-original-title="$ {$btcUSD|number_format:2} / € {$btcEUR|number_format:2}">{$transactiondata.amountNQT|number_format:8} Burst</abbr> <small>({$btc|number_format:8} BTC)</small>
			    </td>
			  </tr>
			  <tr>
			    <td>Fee</td>
			    <td>{$transactiondata.feeNQT|number_format:0} Burst</td>
			  </tr>
			  <tr>
			    <td>Block</td>
			    <td><a href="{$httpUrl}block/{$transactiondata.block}">{$transactiondata.block}</a></td>
			  </tr>
			  <tr>
			    <td>Type</td>
			    <td>{$type}</td>
			  </tr>
			  {if isset($attachment)}
			    {foreach key=arrkey item=arrdata from=$attachment}                  
			    <tr>
			      <td>{$arrkey|escape|ucfirst}</td>
			      <td>{$arrdata|escape}</td>
			    </tr>
			    {/foreach}
			  {/if}
			  <tr>
			    <td>Confirmations</td>
			    <td>{$confirmations}</td>
			  </tr>
			  <tr>
			    <td>Timestamp</td>
			    <td>{$transactiondata.transactiondate}</td>
			  </tr>
			</tbody>
		      </table>
		    </div>
		    <div class="col-xs-12 col-sm-12 col-md-6 text-center">
{include file="Ads/block_first.tpl"}
		    </div>
		  </div>
		  <table class="table table-striped">
		    <tbody>
		      <tr>
			<td>Signature</td>
			<td>{$transactiondata.signature|wordwrap:40:"\n":true}</td>
		      </tr>
		      <tr>
			<td>Signature Hash</td>
			<td>{$transactiondata.signatureHash}</td>
		      </tr>
		      <tr>
			<td>Full Hash</td>
			<td>{$transactiondata.fullHash}</td>
		      </tr>
		    </tbody>
		  </table>
		</div>
	      </div>
	    </div>
	  </div>
{include file="footer.tpl" updatePage="1"}