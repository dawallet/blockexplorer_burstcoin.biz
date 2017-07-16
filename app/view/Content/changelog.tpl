{include file="header.tpl" siteTitle="Changelog" navbar="main"}     
    <div class="page-header">
      <h1>Changelog</h1>
      <ol class="breadcrumb">
        <li><a href="{$httpUrl}"><span class="glyphicon glyphicon-home"></span> &nbsp;Home</a></li>
        <li class="active">Changelog</li>
      </ol>
    </div>
    <div class="container-fluid">
      <div class="row">
        <div class="col-xs-12">
          <div class="cbox animated fadeIn">
            <div class="cbox-title">
              <h2>Changelog</h2>
            </div>
            <div class="cbox-content">
              {foreach $changelog AS $log}
                {if $log@first}{else}<hr>{/if}
                <h2>v{$log.version} <small>{$log.releasedate}</small></h2>
                <p>{$log.description|replace:"[url]":{$httpUrl}}</p>
              {/foreach}
            </div>
          </div>
        </div>
      </div>
    </div>
{include file="footer.tpl"}
