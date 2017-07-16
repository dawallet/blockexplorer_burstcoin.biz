        
{include file="Ads/leader_footer.tpl"}
	<div class="footer">
<center><i>
<span>Burst is based on Bitcoins Blockchain Technology. Bitcoin today is seen as the digital gold standard,</span>
<br>
<span> but like gold, its mining process is environmentally unfriendly and exploiting resources. Burstcoins are mined very</span>
<br>
<span>efficiently with free disk space, and can be traded on many Forex Crypto Exchanges. Burst offers</span>
<br>
<span>a Marketplace, Asset Exchange, Crowdfunding and much more. By design Burst is a distributed, non-centralized Cryptocurrency</span>
</i></center>
	</div>
          <div class="footer">
            <div class="copyright">
              <p class="pull-left sm-pull-reset">

                <span>Copyright <span class="copyright">©</span> 2014 - 2017 Burstcoin.biz</span>
              </p>
              <p class="pull-right sm-pull-reset">
                <span><a href="{$httpRoot}contact" class="m-l-10">Contact</a></span>
              </p>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- BEGIN SEARCH
    <div id="morphsearch" class="morphsearch">
      <form class="morphsearch-form">
        <input class="morphsearch-input" type="search" placeholder="Search..."/>
        <button class="morphsearch-submit" type="submit">Search</button>
      </form>
      <div class="morphsearch-content withScroll">
        <div class="dummy-column user-column">
          <h2>Live Search coming soon!</h2>
        </div>
        <div class="dummy-column"></div>
        <div class="dummy-column"></div>
      </div>
      <span class="morphsearch-close"></span>
    </div>
    <!-- END SEARCH -->
    <div class="loader-overlay">
      <div class="spinner">
        <div class="bounce1"></div>
        <div class="bounce2"></div>
        <div class="bounce3"></div>
      </div>
    </div>
    <a href="#" class="scrollup"><i class="fa fa-angle-up"></i></a> 
    <script src="{$httpRoot}assets/plugins/jquery-validation/jquery.validate.js"></script>
    <script src="{$httpRoot}assets/plugins/jquery/jquery-1.11.1.min.js"></script>
    <script src="{$httpRoot}assets/plugins/jquery/jquery-migrate-1.2.1.min.js"></script>
    <script src="{$httpRoot}assets/plugins/jquery-ui/jquery-ui-1.11.2.min.js"></script>
//    <script src="{$httpRoot}assets/plugins/gsap/main-gsap.min.js"></script>
//    <script src="{$httpRoot}assets/plugins/bootstrap/js/bootstrap.min.js"></script>
    <script src="{$httpRoot}assets/plugins/jquery-cookies/jquery.cookies.min.js"></script>
    <script src="{$httpRoot}assets/plugins/jquery-block-ui/jquery.blockUI.min.js"></script>
//  <script src="{$httpRoot}assets/plugins/mcustom-scrollbar/jquery.mCustomScrollbar.concat.min.js"></script>
//  <script src="{$httpRoot}assets/plugins/bootstrap-dropdown/bootstrap-hover-dropdown.min.js"></script>
//  <script src="{$httpRoot}assets/plugins/charts-sparkline/sparkline.min.js"></script>
//  <script src="{$httpRoot}assets/plugins/retina/retina.min.js"></script>
//  <script src="{$httpRoot}assets/plugins/select2/select2.min.js"></script>
//  <script src="{$httpRoot}assets/plugins/icheck/icheck.min.js"></script>
//  <script src="{$httpRoot}assets/js/application.js"></script>
//  <script src="{$httpRoot}assets/js/plugins.js"></script>
//  <script src="{$httpRoot}assets/js/pages/search.js"></script>
    {if isset($loadJSCharts)}
    <script src="{$httpRoot}assets/plugins/charts-highstock/js/highstock.min.js"></script>
    <script src="{$httpRoot}assets/plugins/charts-highcharts/js/highcharts.js"></script>
    <script src="{$httpRoot}assets/plugins/charts-highstock/js/modules/exporting.min.js"></script>
    {/if}
    {if isset($loadJSForms)}
    <script src="{$httpRoot}assets/plugins/jquery-validation/jquery.validate.js"></script>
    <script src="{$httpRoot}assets/plugins/jquery-validation/additional-methods.min.js"></script>
    {/if}
    {if isset($loadJSCountUp)}
    <script src="{$httpRoot}assets/plugins/countup/countUp.min.js"></script>
    {/if}
    <script src="{$httpRoot}assets/js/layout.js"></script>
    
    <script>
      {if isset($footerJS)}{include file="JS/footer_$footerJS.tpl"}{/if}
      
      {if isset($updatePage)}setTimeout(function(){ window.location.href = document.URL }, 240000);{/if}
    </script>
  </body>
</html>
