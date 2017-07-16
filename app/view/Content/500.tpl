{include file="header.tpl" siteTitle="Internal Error"}
	  <div class="row">
	    <div class="col-xlg-12">
	      <br>
	      <div class="panel" style="margin:0 auto;min-width:250px;width:400px;max-width:400px;">
		<div class="panel-content">
		  <h1 class="text-center"><strong>Internal Error</strong></h1>
		  <p>Sorry, there is probably a mistake and we will fix it soon. Try checking the URL for error or try to find something else on our site.</p>
		  <form method="post" action="{$httpUrl}search" class="text-center p-b-10" role="form">
		    <input type="text" name="search" class="form-control" placeholder="Search for Block / Address / Transaction" required="required">
		  </form>
		</div>
	      </div>
	    </div>
	  </div>
{include file="footer.tpl"}