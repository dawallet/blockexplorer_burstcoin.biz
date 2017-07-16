{include file="header.tpl" siteTitle="Burstcoin Price and Mining Calculator" navTools=" nav-active active" navCalculator=" class='active'"}
          <div class="header">
            <h2><strong>Calculator</strong></h2>
	    <div class="breadcrumb-wrapper">
              <ol class="breadcrumb">
                <li><a href="{$httpRoot}">Home</a></li>		
                <li class="active">Calculator</li>
              </ol>
            </div>
          </div>
	  <div class="row">
	    <div class="col-xs-12 col-lg-6">
	      <div class="panel">
		<div class="panel-header">
		  <h3><strong><i class="fa fa-calculator"></i> Mining Calculator | Long-term average</strong></h3>
		</div>
		<div class="panel-content p-t-0">
		  <form class="form-horizontal" role="form">
		    <div class="form-group">
		      <label for="coinsPerBlock" class="col-sm-4 control-label">Coins Per Block</label>
		      <div class="col-sm-4">
			<input type="text" class="form-control" id="coinsPerBlock" value="{$blockReward}">
		      </div>
		    </div>
		    <div class="form-group">
		      <label for="baseTarget" class="col-sm-4 control-label">Base Target</label>
		      <div class="col-sm-7">
			<div class="input-group">
			  <input type="text" class="form-control" id="baseTarget" value="{$baseTarget}">
			  <div class="input-group-addon">(360 block average)</div>
			</div>
		      </div>
		    </div>
		    <div class="form-group">
		      <label for="valueCurrency" class="col-sm-4 col-xs-12 control-label">Total Plot Size</label>
		      <div class="col-sm-4 col-xs-8">
			<input type="text" class="form-control" id="plotSize" value="0">
		      </div>
		      <div class="col-sm-2 col-xs-4" style="padding-left:0px;">
			<select class="form-control" id="plotUnit">
			  <option value="GB">GB</option>
			  <option value="TB">TB</option>
			  <option value="PB">PB</option>
			</select>
		      </div>
		    </div>
		    <hr>
		    <div class="form-group">
		      <label class="col-sm-4 control-label">Burst Per Day</label>
		      <label class="col-sm-8 control-label" id="burstPerDay" style="text-align:left !important;font-weight:normal;"></label>
		    </div>
		    <div class="form-group">
		      <label class="col-sm-4 control-label">Burst Per Week</label>
		      <label class="col-sm-8 control-label" id="burstPerWeek" style="text-align:left !important;font-weight:normal;"></label>
		    </div>
		    <div class="form-group">
		      <label class="col-sm-4 control-label">Burst Per Month</label>
		      <label class="col-sm-8 control-label" id="burstPerMonth" style="text-align:left !important;font-weight:normal;"></label>
		    </div>
		    <div class="form-group">
		      <label class="col-sm-4 control-label">Network Size</label>
		      <label class="col-sm-8 control-label" style="text-align:left !important;font-weight:normal;"><a href="{$httpUrl}charts/estimated-network-size">{$networksize} TB</a></label>
		    </div>
		  </form>
		</div>
	      </div>
	    </div>
	    <div class="col-xs-12 col-lg-6">
	      <div class="panel">
		<div class="panel-header">
		  <h3><strong><i class="fa fa-calculator"></i> Price Calculator</strong></h3>
		</div>
		<div class="panel-content p-t-0">
		  <form class="form-horizontal p-b-10" role="form">
		    <div class="form-group">
		      <label for="amount" class="col-sm-2 col-xs-12">Amount</label>
		      <div class="col-sm-5 col-xs-8">
			<input type="text" class="form-control" id="amount" value="1000">
		      </div>
		      <div class="col-sm-3 col-xs-4" style="padding-left:0px;">
			<select class="form-control" id="cryptoCurrency">
			  <option value="Burst">Burst</option>
			  <option value="BTC">BTC</option>
			</select>
		      </div>
		    </div>
		    <div class="form-group">
		      <label for="rate" class="col-sm-2">Rate</label>
		      <div class="col-sm-8">
			<div class="input-group">
			  <input type="text" class="form-control" id="rate" value="{$btcBurst}">
			  <div class="input-group-addon">BTC / Burst</div>
			</div>
		      </div>
		    </div>
		    <div class="form-group">
		      <label for="valueCurrency" class="col-sm-2 col-xs-12">Currency</label>
		      <div class="col-sm-5 col-xs-8">
			<div class="input-group">
			  <input type="text" class="form-control" id="valueCurrency" value="{$btcUSD}">
			  <div class="input-group-addon input-currency">$ / BTC</div>
			</div>
		      </div>
		      <div class="col-sm-3 col-xs-4" style="padding-left:0px;">
			<select class="form-control" id="currency">
			  <option value="USD">USD</option>
			  <option value="EUR">EUR</option>
			</select>
		      </div>
		    </div>
		    <hr>
		    <div class="form-group" style="margin-bottom:0px;">
		      <div class="col-sm-10 col-sm-offset-2">
			<h2 style="margin:5px 0px 0px;"><strong><span id="currencySign"></span> <span id="currencyAmount"></span></strong> &nbsp; <small><span id="cryptoAmount"></span> <span id="crypto"></span></small></h2>
		      </div>
		    </div>
		  </form>
		</div>
	      </div>
	    </div>
	  </div>
{include file="footer.tpl" footerJS="calculator"}
