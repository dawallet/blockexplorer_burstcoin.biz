{include file="header.tpl" siteTitle="Surfbar" navEarn=" nav-active active" navSurfbar=" class='active'"}
          <div class="header">
            <h2><strong>Surfbar</strong></h2>
	    <div class="breadcrumb-wrapper">
              <ol class="breadcrumb">
                <li><a href="{$httpRoot}">Home</a></li>
                <li><a href="{$httpRoot}surfbar">Surfbar</a></li>	
                <li class="active">My Account</li>
              </ol>
            </div>
          </div>
	  <div class="row">
	    <div class="col-xs-12 col-lg-6">
	      <div class="panel">
		<div class="panel-header">
		  <h3><strong>My Account</strong></h3>
		</div>
		<div class="panel-content p-t-0">
		  <p>Your surfbar link is:<br><strong><a href="{$httpUrl}surfbar/start/{$account.surfbarid}" target="_blank">{$httpUrl}surfbar/start/{$account.surfbarid}</a></strong></p>
		  <p>Your refferal link is:<br><strong>{$httpUrl}surfbar?ref={$account.surfbarid}</strong></p>
		  <br>
		  <table class="table table-striped">
		    <tbody>
		      <tr>
			<td style="width:50%;"><strong>Earned surf points:</strong></td>
			<td>{$account.surfpoints|number_format:2} SP</td>
		      </tr>
		      <tr>
			<td><strong>Referral earnings:</strong></td>
			<td>
			  {$surfpointsRef = $surfpointsRef/100*5}
			  {$surfpointsRef|number_format:2} SP
			</td>
		      </tr>
		      <tr>
			<td><strong>Converted surf points:</strong></td>
			<td class="text-danger">-{$account.surfpoints_converted|number_format:2} SP</td>
		      </tr>
		      <tr>
			<td><strong>Balance:</strong></td>
			<td class="text-success">
			  {$balance = $account.surfpoints+$surfpointsRef-$account.surfpoints_converted}
			  <strong>{$balance|number_format:2} SP</strong>
			</td>
		      </tr>
		    </tbody>
		  </table><br>
		  <p class="no-margin"><em><strong>Please note:</strong> The statistics are delayed by up to 120 minutes.</em></p>
		</div>
	      </div>
	      <div class="panel">
		<div class="panel-header">
		  <h3><strong>Recent Payouts</strong> <small>Total payouts: {$totalPaid|number_format:8} Burst</small></h3>
		</div>
		<div class="panel-content p-t-0">
		  {if $totalPaid > 0}
		  <table class="table table-striped">
		    <thead>
		      <tr>
			<th>Transaction</th>
			<th>Amount</th>
			<th>Timestamp</th>
		      </tr>
		    </thead>
		    <tbody>
		      {foreach $payoutdata AS $payout}
		      <tr>
			<td><a href="{$httpUrl}transaction/{$payout.transaction}">{$payout.transaction}</a></td>
			<td>{$payout.amount|number_format:8} Burst</td>
			<td>{$payout.ts_payed|date_format:"%D %H:%M:%S"}</td>
		      </tr>
		      {/foreach}
		    </tbody>
		  </table>
		  <br>
		  {/if}
		  <p class="no-margin">Your payout address: {if isset($numericAddress)}<a href="{$httpUrl}address/{$numericAddress}">{/if}{$account.address}{if isset($numericAddress)}</a>{/if}</p>
		</div>
	      </div>
	      <div class="panel">
		<div class="panel-header">
		  <h3><strong>Your Refferals</strong> <small>Total earnings: {$surfpointsRef|number_format:2} SP</small></h3>
		</div>
		<div class="panel-content p-t-0">
		  <p class="no-margin">You currently have a total of <strong>{$referrals}</strong> referrals.</p>
		  {if $referrals > 0}
		  <br>
		  <table class="table table-striped no-margin">
		    <thead>
		      <tr>
			<th>Address</th>
			<th>Earned SP</th>
		      </tr>
		    </thead>
		    <tbody>
		      {foreach $refdata AS $ref}
		      <tr>
			<td>{if $ref.accountID > 0 AND !empty($ref.accountID)}<a href="{$httpUrl}address/{$ref.accountID}">{/if}{$ref.address}{if $ref.accountID > 0 AND !empty($ref.accountID)}</a>{/if}</td>
			<td>
			  {$refpoints = $ref.surfpoints/100*5}
			  {$refpoints|number_format:2} SP
			</td>
		      </tr>
		      {/foreach}
		    </tbody>
		    <tfoot>
		      <tr>
			<td><strong>Total:</strong></td>
			<td><strong>{$surfpointsRef|number_format:2} SP</strong></td>
		      </tr>
		    </tfoot>
		  </table>
		  <br>
		  {/if}
		</div>
	      </div>
	    </div>
	    <div class="col-xs-12 col-lg-6 text-center">
	      <div class="panel">
		<div class="panel-header">
		  <h3><strong>FAQ</strong></h3>
		</div>
		<div class="panel-content p-t-0">
		  <h4 class="m-t-0">How can I earn Burstcoins?</h4>
		  <p>
		    By actively running the surfbar in your browser and by referring new members, you will be rewarded with surf points (SP). Those points will be automatically converted into Burstcoins once per day if your account balance reach 2,000 surf points. In this case your Burstcoins will automatically paid to your Burst address.
		  </p>
		  <h4>Can I run 2 or more surfbars simultaneously?</h4>
		  <p>
		    Yes, it is possible to run the surfbar simultaneously on multiple devices! The surflink can be used simultaneously on the living room PC as well as on a tablet or any other device, which allows you to considerably increase your earnings. The only condition is that each device (e.g. PC, notebook or tablet) has to be connected to the Internet through a unique global IPv4 address.
		  </p>
		  <h4>How much surf points can I earn for referring friends?</h4>
		  <p>
		    If somebody signs himself up through your referral link, he will be registered as your referral and we pay you 5% on top of the points he generates when using our surfbar.
		  </p>
		</div>
	      </div>
	      <div class="text-center">
		{include file="Ads/block_first.tpl"}
	      </div>
	    </div>
	  </div>
{include file="footer.tpl" footerJS="surfbar"}