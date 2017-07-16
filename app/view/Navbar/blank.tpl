    <nav class="navbar navbar-default" role="navigation">
      <div class="container-fluid">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse-header">
            <span class="sr-only">Navigation auf-/zuklappen</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          {if $hideHeaderLogo != "true"}
          <a class="navbar-brand" href="{$httpMain}"><img src="{$httpUrl}img/home/logo.png"></a>
          {elseif isset($logoUrl)}
          <img src="{$logoUrl}" height="40" class="margin-top" style="height:40px;">
          {/if}
        </div>
      </div>
    </nav>
    