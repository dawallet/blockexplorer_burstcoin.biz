{include file="header.tpl" siteTitle="Contact"}     
          <div class="header">
            <h2><strong>Contact</strong></h2>
	    <div class="breadcrumb-wrapper">
              <ol class="breadcrumb">
                <li><a href="{$httpRoot}">Home</a></li>		
                <li class="active">Contact</li>
              </ol>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="panel panel-default">
                <div class="panel-header bg-dark">
                  <h2 class="panel-title">Submit your <strong>Comment</strong></h2>
                </div>
                <div class="panel-content bg-dark">
                  <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                      <p>Got new ideas for burstcoin.biz or found a bug?<br>Share your feedback with us.</p>
		      {$errorMsg}
		      <br>		      
                      <form action="{$httpUrl}contact/check" method="post" role="form" class="form-horizontal form-validation">
                        <div class="form-group">
                          <label class="col-sm-3 control-label">Name
                          </label>
                          <div class="col-sm-9 prepend-icon">
                            <input type="text" name="name" class="form-control" value="{if !isset($messageSend)}{$smarty.post.name|escape}{/if}" placeholder="Minimum 3 characters..." minlength="3" required>
                            <i class="icon-user"></i>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-3 control-label">Email
                          </label>
                          <div class="col-sm-9 prepend-icon">
                            <input type="email" name="email" class="form-control" value="{if !isset($messageSend)}{$smarty.post.email|escape}{/if}" placeholder="Enter your email address..." required>
                            <i class="icon-envelope"></i>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-3 control-label">Comment
                          </label>
                          <div class="col-sm-9">
                            <textarea name="message" rows="8" class="form-control" placeholder="Write your comment... (minimum 30 characters)" minlength="30" required>{if !isset($messageSend)}{$smarty.post.message|escape}{/if}</textarea>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-sm-9 col-sm-offset-3">
                            <div class="pull-right">
                              <button type="submit" class="btn btn-embossed btn-primary m-r-20">Send my Comment</button>
                              <button type="reset" class="cancel btn btn-embossed btn-default m-b-10 m-r-0">Cancel</button>
                            </div>
                          </div>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
            </div>
	    <div class="col-md-6 text-center">
	      <br><br>
	      {include file="Ads/block_first.tpl"}
	    </div>
	  </div>
{include file="footer.tpl" loadJSForms="1"}
