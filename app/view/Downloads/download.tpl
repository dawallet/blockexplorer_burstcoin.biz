{include file="header.tpl" siteTitle="{$downloaddata.name} Download" navDownloads=" class='nav-active active'"}
          <div class="header">
            <h2><strong>Download</strong> <small>{$downloaddata.name}</small></h2>
	    <div class="breadcrumb-wrapper">
              <ol class="breadcrumb">
                <li><a href="{$httpRoot}">Home</a></li>
		<li><a href="{$httpUrl}downloads">Downloads</a></li>
		<li class="active">{$downloaddata.name}</li>
              </ol>
            </div>
          </div>
	  <div class="row">
	    <div class="col-xs-12 col-lg-6">
	      <div class="panel">
		<div class="panel-header">
		  <h3><strong>{$downloaddata.name}</strong></h2>
		</div>
		<div class="panel-content p-t-0">
		  <table class="table table-striped">
		    <tbody>
		      <tr style="width:30%;">
			<td>Version</td>
			<td>{$downloaddata.version}</td>
		      </tr>
		      <tr>
			<td>Size</td>
			<td>{$downloaddata.filesize}</td>
		      </tr>
		      <tr>
			<td>Downloads</td>
			<td>{$downloaddata.downloads}</td>
		      </tr>
		      <tr>
			<td>Platform</td>
			<td>{$downloaddata.platform}</td>
		      </tr>
		      <tr>
			<td>Category</td>
			<td>{$downloaddata.category}</td>
		      </tr>
		      <tr>
			<td>Links</td>
			<td><a href="{$httpUrl}r/{$source}" target="_blank">Source</a><br><a href="{$httpUrl}r/{$support}" target="_blank">Support</a></td>
		      </tr>
		    </tbody>
		  </table>
		  <a class="btn btn-primary btn-block" href="{$httpUrl}download/{$downloaddata.downloadid}-start-{$downloaddata.name|lower|replace:" ":"-"}">Download {$downloaddata.filename}</a>
		</div>
	      </div>
             {if isset($downloadhistory)}
	      <div class="panel">
		<div class="panel-header">
		  <h3><strong>Older version</strong></h2>
		</div>
		<div class="panel-content p-t-0">		  
		  <table class="table table-striped">
		    <thead>
		      <tr>
			<th style="width:30%;">Version</th>
			<th>Download</th>
		      </tr>
		    </thead>
		    <tbody>
		      {foreach $downloadhistory AS $history}
		      <tr>
			<td>{$history.version}</td>
			<td><a href="{$httpUrl}download/{$downloaddata.downloadid}-start-old-{$history.historyid}-{$downloaddata.name|lower|replace:" ":"-"}">{$history.filename}</a></td>
		      </tr>
		      {/foreach}
		    </tbody>
		  </table>
		</div>
	      </div>
	      {/if}
	    </div>
	    <div class="col-xs-12 col-lg-6 text-center">
	      <br><br>
	      <a class="btn btn-primary" href="{$httpUrl}download/{$downloaddata.downloadid}-start-{$downloaddata.name|lower|replace:" ":"-"}">Download {$downloaddata.filename}</a>
	      <br><br>
    {include file="Ads/block_first.tpl"}
	    </div>
	  </div>
{include file="footer.tpl" updatePage="1"}