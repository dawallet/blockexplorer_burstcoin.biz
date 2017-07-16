{include file="header.tpl" siteTitle="Surfbar" navEarn=" nav-active active" navSurfbar=" class='active'"}
          <div class="header">
            <h2><strong>Surfbar</strong></h2>
	    <div class="breadcrumb-wrapper">
              <ol class="breadcrumb">
                <li><a href="{$httpRoot}">Home</a></li>		
                <li class="active">Surfbar</li>
              </ol>
            </div>
          </div>
	  <div class="row">
	    <div class="col-xs-12 col-lg-6">
	      <div class="panel">
		<div class="panel-header">
		  <h3><strong>Run our surfbar and earn Burstcoins</strong></h3>
		</div>
		<div class="panel-content p-t-0">
		  <p>
		    Enter your Burst address and get your personal surflink.
		  </p>
		  <div id="surfbarResult"></div>
		  <form method="post" action="{$httpUrl}surfbar/login" role="form" id="surfbarForm">
		    <div class="form-group">
		      <label for="name">Your address</label>
		      <input type="text" name="address" class="form-control" placeholder="Enter your Burst address">
		    </div>
		    <button type="submit" class="btn btn-primary btn-lg btn-block" id="surfbarBtn">Send <span class="glyphicon glyphicon-chevron-right"></span></button>
		  </form>
		</div>
	      </div>
	      <div class="panel">
		<div class="panel-content widget-info">
		  <div class="row">
		    <div class="left">
		      <i class="fa fa-dollar bg-green"></i>
		    </div>
		    <div class="right">
		      <div class="clearfix">
			<p data-to="{$totalPayouts|number_format:0:"":""}" data-from="0" class="number countup pull-left">{$totalPayouts|number_format:0}</p>
			<p class="number pull-left m-l-5">Burst</p>
		      </div>
		      <p class="text">Total Surfbar Payouts</p>
		    </div>
		  </div>
		</div>
	      </div>
	      <div class="text-center">
		{include file="Ads/block_first.tpl"}
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
	    </div>
	  </div>
{include file="footer.tpl" loadJSCountUp="1" footerJS="surfbar"}