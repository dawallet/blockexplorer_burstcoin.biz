{include file="header.tpl" siteTitle="Admin - Stats" navbar="main"}
    <div class="page-header">
      <h1>Admin - Stats</h1>
      <ol class="breadcrumb">
        <li><a href="{$httpUrl}"><span class="glyphicon glyphicon-home"></span> &nbsp;Home</a></li>
        <li class="active">Admin - Stats</li>
      </ol>
    </div>
    <div class="container-fluid">
      <div class="row">
        <div class="col-xs-12">
          <div class="cbox animated fadeIn">            
            <div class="cbox-title">
              <h2>Global Stats</h2>
            </div>
            <div class="cbox-content">
              <table class="table table-bordered margin-bottom">
                <thead>
                  <tr>
                    <th>Bezeichnung</th>
                    <th>Datenbank</th>
                    <th>Wallet</th>
                    <th>Differenz</th>
                    <th>Aktion</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>Blöcke</td>
                    <td>{$numberOfBlocks.blocks|number_format:0}</td>
                    <td>{$walletData.numberOfBlocks|number_format:0}</td>
                    <td>
                      {math equation="x - y" assign="blocks" x=$numberOfBlocks.blocks y=$walletData.numberOfBlocks}
                      <span class="{if $blocks == 0}text-success{else}text-danger{/if}">{$blocks}</span>
                    </td>
                    <td></td>
                  </tr>
                  <tr>
                    <td>Accounts</td>
                    <td>{$numberOfAccounts.accounts|number_format:0}</td>
                    <td>{$walletData.numberOfAccounts|number_format:0}</td>
                    <td>
                      {math equation="x - y" assign="accounts" x=$numberOfAccounts.accounts y=$walletData.numberOfAccounts}
                      <span class="{if $accounts == 0}text-success{else}text-danger{/if}">{$accounts}</span>
                    </td>
                    <td><a href="{$httpUrl}admin/stats/check/accounts">Check</a></td>             
                  </tr>
                  <tr>
                    <td>Transactions</td>
                    <td>{$numberOfTransactions.transactions|number_format:0}</td>
                    <td>{$walletData.numberOfTransactions|number_format:0}</td>
                    <td>
                      {math equation="x - y" assign="transactions" x=$numberOfTransactions.transactions y=$walletData.numberOfTransactions}
                      <span class="{if $transactions == 0}text-success{else}text-danger{/if}">{$transactions}</span>
                    </td>
                    <td><a href="{$httpUrl}admin/stats/check/transactions">Check</a></td>        
                  </tr>
                  <tr>
                    <td>Supply</td>
                    <td>{$totalSupply.supply|number_format:0}</td>
                    <td>{$walletData.totalEffectiveBalanceNXT|number_format:0}</td>
                    <td>
                      {math equation="x - y" assign="supply" x=$totalSupply.supply y=$walletData.totalEffectiveBalanceNXT}
                      <span class="{if $supply == 0}text-success{else}text-danger{/if}">{$supply|number_format:0}</span>
                    </td>
                    <td></td>
                  </tr>
                </tbody>
              </table>
              <p class="no-margin"><strong>{$unsyncedBlocks}</strong> Blöcke wurden noch nicht erneut synchronisiert. (fehlerfrei bis Block 52,000)</p>
            </div>
          </div>
          <div class="cbox animated fadeIn">
            <div class="cbox-title">
              <h2>Fehlende Blöcke</h2>
            </div>
            <div class="cbox-content">
              {foreach $missingBlocks AS $block}
                <p>{$block.gap_starts_at} - {$block.gap_ends_at}</p>
              {foreachelse}
                <p><em>Keine fehlenden Blöcke gefunden.</em></p>
              {/foreach}
            </div>
          </div>
          <div class="cbox animated fadeIn">
            <div class="cbox-title">
              <h2>Total Payouts</h2>
            </div>
            <div class="cbox-content">
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th></th>
                    <th>Last 7 days</th>
                    <th>Last 14 days</th>
                    <th>Last 28 days</th>
                    <th>Total</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td><a href="{$httpUrl}admin/payouts">Surfbar</a></td>
                    <td>{$surfbarPayoutL7D|number_format:0}</td>
                    <td>{$surfbarPayoutL14D|number_format:0}</td>
                    <td>{$surfbarPayoutL28D|number_format:0}</td>
                    <td>{$surfbarPayoutTotal|number_format:0}</td>
                  </tr>
                  <tr>
                    <td>Faucet</td>
                    <td>{$faucetPayoutL7D|number_format:0}</td>
                    <td>{$faucetPayoutL14D|number_format:0}</td>
                    <td>{$faucetPayoutL28D|number_format:0}</td>
                    <td>{$faucetPayoutTotal|number_format:0}</td>
                  </tr>
                </tbody>
                <tfoot>
                  <tr>
                    <td><strong>Total:</strong></td>
                    <td><strong>{$totalPayoutL7D = $surfbarPayoutL7D+$faucetPayoutL7D}{$totalPayoutL7D|number_format:0}</strong></td>
                    <td><strong>{$totalPayoutL14D = $surfbarPayoutL14D+$faucetPayoutL14D}{$totalPayoutL14D|number_format:0}</strong></td>
                    <td><strong>{$totalPayoutL28D = $surfbarPayoutL28D+$faucetPayoutL28D}{$totalPayoutL28D|number_format:0}</strong></td>
                    <td><strong>{$totalPayout = $surfbarPayoutTotal+$faucetPayoutTotal}{$totalPayout|number_format:0}</strong></td>
                  </tr>
                </tfoot>
              </table><br>
              <p class="no-margin">Enthaltene Transaktionskosten: <strong>{$totalPayoutFee|number_format:0}</strong></p>
            </div>
          </div>          
        </div>
      </div>
    </div>
{include file="footer.tpl"}
