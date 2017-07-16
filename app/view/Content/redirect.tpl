{include file="header.tpl" siteTitle="You Are Being Redirected to An External Site"}
          <div class="row">
            <div class="col-lg-12">
	      <div class="panel">
                <div class="panel-content">
                  <h1><strong>You Are Being Redirected to An External Site</strong></h1>
		  <div class="alert alert-danger" role="alert">You are being redirected to a page not part of burstcoin.biz which may contain insecure content. Never enter your login details on an external site and always check the browser URL carefully to ensure you are on the correct domain.</div>
		  <p class="no-margin">If this page appears for more than a few seconds <a href="{$url}">click here</a></p>
		  <script>
		    window.setTimeout(function(){
		      window.location.href = "{$url}";
		    }, 10000);
		  </script>
                </div>
              </div>
	    </div>
	  </div>
{include file="footer.tpl"}