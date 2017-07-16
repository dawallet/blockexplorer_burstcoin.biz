<?php
$routing = array(
    // Main
    '/' => array('Home', '', ROUTING_MAIN),
    '/search' => array('Home', '', ROUTING_MAIN),
    '/language/(*)' => array('Home', 'language', ROUTING_MAIN),
    // Block-Explorer
    '/blocks' => array('BlockExplorer', 'blocks', ROUTING_MAIN),
    '/blocks/(*)' => array('BlockExplorer', 'blocks', ROUTING_MAIN),
    '/block/(*)' => array('BlockExplorer', 'block', ROUTING_MAIN),
    '/address/(*)' => array('BlockExplorer', 'address', ROUTING_MAIN),
    '/address/chart/transactions/(*)' => array('BlockExplorer', 'addressChartTransactions', ROUTING_MAIN),
    '/address/chart/total-received/(*)' => array('BlockExplorer', 'addressChartTotalReceived', ROUTING_MAIN),
    '/address/chart/total-sent/(*)' => array('BlockExplorer', 'addressChartTotalSent', ROUTING_MAIN),
    '/address/chart/forged-blocks/(*)' => array('BlockExplorer', 'addressChartForgedBlocks', ROUTING_MAIN),
    '/address/chart/pool-mined/(*)' => array('BlockExplorer', 'addressChartPoolMined', ROUTING_MAIN),
    '/address/export/forged-blocks/(*)' => array('BlockExplorer', 'addressExportForgedBlocks', ROUTING_MAIN),
    '/address/export/transactions/(*)' => array('BlockExplorer', 'addressExportTransactions', ROUTING_MAIN),
    '/transaction/(*)' => array('BlockExplorer', 'transaction', ROUTING_MAIN),
    // Assets
    '/assets' => array('Assets', '', ROUTING_MAIN),
    // Charts
    '/charts' => array('Charts', '', ROUTING_MAIN),
    '/charts/total-burstcoins' => array('Charts', 'totalBurstcoins', ROUTING_MAIN),
    '/charts/burstcoins-mined-per-day' => array('Charts', 'minedPerDay', ROUTING_MAIN),
    '/charts/mined-burstcoins' => array('Charts', 'minedBurstcoins', ROUTING_MAIN),
    '/charts/blockchain-size' => array('Charts', 'blockchainSize', ROUTING_MAIN),
    '/charts/number-of-transactions-per-day' => array('Charts', 'transactionsPerDay', ROUTING_MAIN),
    '/charts/total-number-of-transactions' => array('Charts', 'totalTransactions', ROUTING_MAIN),
    '/charts/amount-of-transactions-per-day' => array('Charts', 'transactionsAmountPerDay', ROUTING_MAIN),
    '/charts/transaction-amount-distribution' => array('Charts', 'transactionDistribution', ROUTING_MAIN),
    '/charts/average-number-of-transactions-per-block' => array('Charts', 'transactionsAveragePerBlock', ROUTING_MAIN),
    '/charts/number-of-wallets' => array('Charts', 'totalWallets', ROUTING_MAIN),
    '/charts/new-wallets-per-day' => array('Charts', 'walletsPerDay', ROUTING_MAIN),
    '/charts/addresses-by-balance' => array('Charts', 'addressesBalance', ROUTING_MAIN),
    '/charts/addresses-by-balance/(*)' => array('Charts', 'addressesBalance', ROUTING_MAIN),
    '/charts/addresses-by-total-received-burstcoins' => array('Charts', 'addressesReceived', ROUTING_MAIN),
    '/charts/addresses-by-forged-blocks' => array('Charts', 'addressesForged', ROUTING_MAIN),
    '/charts/addresses-by-forged-blocks/(*)' => array('Charts', 'addressesForged', ROUTING_MAIN),
    '/charts/account-balance-distribution' => array('Charts', 'balanceDistribution', ROUTING_MAIN),
    '/charts/average-block-generation-time' => array('Charts', 'averageBlockTime', ROUTING_MAIN),
    '/charts/estimated-network-size' => array('Charts', 'estimatedNetworkSize', ROUTING_MAIN),
    // Stats
    '/stats' => array('Stats', '', ROUTING_MAIN),
    '/stats/(*)' => array('Stats', '', ROUTING_MAIN),
    // Calculator
    '/calculator' => array('Calculator', '', ROUTING_MAIN),
    '/calculator/api' => array('Calculator', 'api', ROUTING_MAIN),
    // Downloads
    '/downloads' => array('Downloads', '', ROUTING_MAIN),
    '/downloads/(*)' => array('Downloads', '', ROUTING_MAIN),
    '/download/(*)' => array('Downloads', 'download', ROUTING_MAIN),
    // Pools
    '/pools' => array('Pools', '', ROUTING_MAIN),
    '/pools/(*)' => array('Pools', '', ROUTING_MAIN),
    // Faucet
    '/faucet' => array('Faucet', '', ROUTING_MAIN),
    '/faucet/check' => array('Faucet', 'check', ROUTING_MAIN),
    // Surfbar
    '/surfbar' => array('Surfbar', '', ROUTING_MAIN),
    '/surfbar/login' => array('Surfbar', 'login', ROUTING_MAIN),
    '/surfbar/account' => array('Surfbar', 'account', ROUTING_MAIN),
    '/surfbar/start/(*)' => array('Surfbar', 'start', ROUTING_MAIN),
    // Price
    '/price' => array('Price', '', ROUTING_MAIN),
    '/price/chart' => array('Price', 'chart', ROUTING_MAIN),
    '/price/chart/(*)' => array('Price', 'chart', ROUTING_MAIN),
    '/price/market-depth' => array('Price', 'marketDepth', ROUTING_MAIN),
    '/price/market-cap' => array('Price', 'marketCap', ROUTING_MAIN),
    // Content
    '/changelog' => array('Content', 'changelog', ROUTING_MAIN),
    '/contact' => array('Content', 'contact', ROUTING_MAIN),
    '/contact/check' => array('Content', 'contactCheck', ROUTING_MAIN),
    '/chat' => array('Content', 'chat', ROUTING_MAIN),
    '/r/(*)' => array('Content', 'redirect', ROUTING_MAIN),
    '/fix' => array('Content', 'fix', ROUTING_MAIN),
    '/access-denied' => array('Content', 'error401'),
    '/page-not-found' => array('Content', 'error404'),
    '/error' => array('Content', 'error500'),
    // API
    '/account/(*)' => array('API', 'account', ROUTING_API),    
    // Admin
    '/admin/stats' => array('Admin', 'stats', ROUTING_MAIN),
    '/admin/stats/check/(*)' => array('Admin', 'check', ROUTING_MAIN),
    '/admin/payouts' => array('Admin', 'payouts', ROUTING_MAIN),
    '/admin/payouts/(*)' => array('Admin', 'payouts', ROUTING_MAIN),
    '/admin/smaxer' => array('Admin', 'smaxer', ROUTING_MAIN),
    // Login
    '/login' => array('Login', '', ROUTING_MAIN),
    '/login/check' => array('Login', 'check', ROUTING_MAIN),
    '/login/logout' => array('Login', 'logout', ROUTING_MAIN),
    '/login/resend-password' => array('Login', 'password', ROUTING_MAIN),
    '/login/resend-password/check' => array('Login', 'passwordCheck', ROUTING_MAIN),
    '/login/reset-password/(*)' => array('Login', 'passwordReset', ROUTING_MAIN)
);