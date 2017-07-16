{include file="header.tpl" siteTitle="Admin - Payouts" navbar="main"}
    <div class="page-header">
      <h1>Admin - Payouts</h1>
      <ol class="breadcrumb">
        <li><a href="{$httpUrl}"><span class="glyphicon glyphicon-home"></span> &nbsp;Home</a></li>
        <li><a href="{$httpUrl}admin/stats">Admin</a></li>
        <li class="active">Payouts</li>
      </ol>
    </div>
    <div class="container-fluid">
      <div class="row">
        <div class="col-xs-12">
          <div class="cbox animated fadeIn">
            <div class="cbox-title">
              <h2>Surfbar Payouts</h2>
            </div>
            <div class="cbox-content">
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th>Surfbar ID</th>
                    <th>Wallet</th>
                    <th>Balance (SP)</th>
                    <th>Balance (Burst)</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  {$totalSP = $totalAdminBalance}
                  {$totalBurst = $balanceBurst}
                  {foreach $accounts AS $account}
                  {if $account.totalBalance >= 2000}
                  <tr>
                    <td>{$account.surfbarid}</td>
                    <td>{$account.address}{if !empty($account.name)} <small>({$account.name})</small>{/if}</td>
                    <td>
                      {$account.totalBalance|number_format:2} SP
                      {$totalSP = $totalSP+$account.totalBalance}
                    </td>
                    <td>
                      {$balanceBurst = $account.totalBalance*$spvalue/$globalStats.ebesucherRate}
                      {$balanceBurst|number_format:8} Burst
                      {$totalBurst = $totalBurst+$balanceBurst}
                    </td>
                    <td>
                      <a href="{$httpUrl}admin/payouts/{$account.surfbarid}">Send payout</a>
                    </td>
                  </tr>
                  {/if}
                  {/foreach}
                </tbody>
                <tfoot>
                  <tr>
                    <th colspan="2" class="text-right">Total:</th>
                    <th>{$totalSP|number_format:2} SP</th>
                    <th>{$totalBurst|number_format:8} Burst</th>
                    <th></th>
                  </tr>
                </tfoot>
              </table>
              <br>
              <p class="no-margin">Aktuell stehen <strong>{$availableBalance|number_format:0} Burst</strong> für Auszahlungen zur Verfügung.</p>
            </div>
          </div>
          <div class="cbox animated fadeIn">
            <div class="cbox-title">
              <h2>Surfbar Earnings</h2>
            </div>
            <div class="cbox-content">
              <table class="table table-bordered">
                <tbody>
                  <tr>
                    <td>eBesucher Verdienst:</td>
                    <td>
                      {$surfpointsEuro = $totalSurfpoints/100000*2.1}
                      {$surfpointsEuro|number_format:2} €
                    </td>
                  </tr>
                  <tr>
                    <td>Auszahlungen in Burstcoins:</td>
                    <td>{$totalBurstPayouts = $totalPayouts.burstcoins+$totalPayouts.burstfee}{$totalBurstPayouts|number_format:0} Burst ({$totalPayouts.burstcoins|number_format:0} Payouts + {$totalPayouts.burstfee|number_format:0} Fee)</td>
                  </tr>
                  <tr>
                    <td>Wert der Auszahlungen in Euro:</td>
                    <td>
                      {$burstPayedRest = $totalBurstPayouts-$burstPayed}
                      {$burstEuroRest = $burstPayedRest*$burstEuroRate}
                      {$burstEuro = $burstEuro+$burstEuroRest}
                      {$burstEuro|number_format:2} €
                    </td>
                  </tr>
                  <tr>
                    <td>Nicht ausgezahltes Guthaben:</td>
                    <td>
                      {$unconfirmedPayouts = $totalSurfbarBalance*$spvalue}
                      {$unconfirmedPayouts|number_format:2} €
                    </td>
                  </tr>
                  <tr>
                    <td>burstcoin.biz Verdienst:</td>
                    <td>
                      {$burstIncome = $surfpointsEuro-$burstEuro-$unconfirmedPayouts}
                      {$burstIncome|number_format:2} €
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
          <div class="cbox animated fadeIn">
            <div class="cbox-title">
              <h2>Missing Payouts</h2>
            </div>
            <div class="cbox-content">
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th>Payout ID</th>
                    <th>Amount</th>
                    <th>Transaction ID</th>
                    <th>Address</th>
                    <th>Date</th>
                  </tr>
                </thead>
                <tbody>
                  {foreach $missingPayouts AS $payout}
                  <tr>
                    <td>{$payout.payoutid}</td>
                    <td>{$payout.amount|number_format:8:".":""}</td>
                    <td><a href="{$httpUrl}transaction/{$payout.transaction}" target="_blank">{$payout.transaction}</a></td>
                    <td>{$payout.address}</td>
                    <td>{$payout.ts_payed|date_format:"d.m.Y - H:i:s"}</td>
                  </tr>
                  {foreachelse}
                  <tr>
                    <td colspan="5"><em>Keine fehlerhaften Payouts gefunden.</em></td>
                  </tr>
                  {/foreach}
                </tbody>
              </table>
            </div>
          </div>
          <div class="cbox animated fadeIn">
            <div class="cbox-title">
              <h2>Stats</h2>
            </div>
            <div class="cbox-content">
              <table class="table table-bordered" style="width:40%;">
                <tbody>
                  <tr>
                    <td style="width:70%;">User gesamt:</td>
                    <td style="width:30%;">{$surfbarUserTotal|number_format:0}</td>
                  </tr>
                  <tr>
                    <td>Davon aktiv:</td>
                    <td>{$surfbarUserActive|number_format:0} {$surfbarUserPercent = $surfbarUserActive/$surfbarUserTotal*100} ({$surfbarUserPercent|number_format:2} %)</td>
                  </tr>
                  <tr>
                    <td>Davon inaktiv:</td>
                    <td>{$surfbarUserInactive|number_format:0} {$surfbarUserPercent = $surfbarUserInactive/$surfbarUserTotal*100} ({$surfbarUserPercent|number_format:2} %)</td>
                  </tr>
                  <tr>
                    <td>Neue User in den letzten 24h:</td>
                    <td>{$surfbarUserNew24|number_format:0}</td>
                  </tr>
                  <tr>
                    <td>Neue User in den letzten 48h:</td>
                    <td>{$surfbarUserNew48|number_format:0}</td>
                  </tr>
                  <tr>
                    <td>Eingeloggte User in den letzten 48h:</td>
                    <td>{$surfbarUserLogin48|number_format:0}</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
{include file="footer.tpl"}
