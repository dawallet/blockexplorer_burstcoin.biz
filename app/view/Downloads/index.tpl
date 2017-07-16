{include file="header.tpl" siteTitle="Downloads" navDownloads=" class='nav-active active'"}
          <div class="header">
            <h2>
	      <strong>Downloads</strong>
	      <div class="btn-group">
		<button aria-expanded="false" type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown">
		  {if $smarty.get.p0 == "Miner"}Miner{elseif $smarty.get.p0 == "Plotter"}Plotter{elseif $smarty.get.p0 == "Wallet"}Wallet{else}all{/if} <span class="caret"></span>
		</button>
		<span class="dropdown-arrow"></span>
		<ul class="dropdown-menu" role="menu">
		  <li><a href="{$httpRoot}downloads/all">all</a></li>
		  <li><a href="{$httpRoot}downloads/Miner">Miner</a></li>
		  <li><a href="{$httpRoot}downloads/Plotter">Plotter</a></li>
		  <li><a href="{$httpRoot}downloads/Wallet">Wallet</a></li>
		</ul>
	      </div>	    
	    </h2>
	    <div class="breadcrumb-wrapper">
              <ol class="breadcrumb">
                <li><a href="{$httpRoot}">Home</a></li>
		<li class="active">Downloads</li>
              </ol>
            </div>
          </div>
	  <div class="row">
	    <div class="col-xlg-12">
	      <div class="panel">
		<div class="panel-content">
		  <table class="table table-striped">
		    <thead>
		      <tr>
			<th>Name</th>
			<th>Version</th>
			<th>Category</th>
			<th>Platform</th>
		      </tr>
		    </thead>
		    <tbody>
		      {foreach $downloaddata AS $download}
			<tr>
			  <td><a href="{$httpUrl}download/{$download.downloadid}-{$download.name|lower|replace:" ":"-"}">{$download.name}</a></td>
			  <td>{$download.version}</td>
			  <td>{$download.category}</td>
			  <td>{$download.platform}</td>
			</tr>
		      {/foreach}
		    </tbody>
		  </table>
		</div>
	      </div>
	    </div>
	  </div>
{include file="footer.tpl" footerJS="downloads" updatePage="1"}