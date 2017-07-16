<?php
// Transaktionstypen
$conf['transactiontypes'] = 
        array(
        0 => 
            array(
                0 => 'Ordinary Payment'
                ), 
        1 => 
            array(
                0 => 'Arbitrary Message',
                1 => 'Alias Assignment', 
                2 => 'Poll Creation', 
                3 => 'Vote Casting', 
                4 => 'Hub Announcements', 
                5 => 'Account Info', 
                6 => 'Alias Transfer/Sale', 
                7 => 'Alias Buy'
                ),
        2 =>
            array(
                0 => 'Asset Issuance',
                1 => 'Asset Transfer',
                2 => 'Ask Order Placement',
                3 => 'Bid Order Placement',
                4 => 'Ask Order Cancellation',
                5 => 'Bid Order Cancellation'
            ),
        3 =>
            array(
                0 => 'Marketplace Listing',
                1 => 'Marketplace Removal',
                2 => 'Marketplace Price Change',
                3 => 'Marketplace Quantity Change',
                4 => 'Marketplace Purchase',
                5 => 'Marketplace Delivery',
                6 => 'Marketplace Feedback',
                7 => 'Marketplace Refund'
            ),
        4 => array(
                array(0 => 'balance_leasing')
            ),
        20 => array(
                0 => 'Reward Recipient Assignment'
            ),
        21 => array(
                0 => 'Escrow Creation',
                1 => 'Escrow Signing',
                2 => 'Escrow Result',
                3 => 'Subscription Subscribe',
                4 => 'Subscription Cancel',
                5 => 'Subscription Payment'
            ),
        22 => array(
                0 => 'AT Creation',
                1 => 'AT Payment'
            )
        );

// Pools
$conf['pools'] = array(
        0 => array('name' => 'DevPool v2', 'addr' => '21869187791279079', 'url' => 'http://178.62.39.204:8121/'),
        1 => array('name' => 'burst.ninja', 'addr' => '12468105956737329840', 'url' => 'http://burst.ninja/'),
        2 => array('name' => 'Burst Team Pool', 'addr' => '11894018496043975481', 'url' => 'http://pool.burst-team.us/'),
        3 => array('name' => 'tompool.org', 'addr' => '392861956774712841', 'url' => 'http://www.tompool.org/'),
        4 => array('name' => 'Burstcoin.biz Pool', 'addr' => '14789046051569562492', 'url' => 'http://pool.burstcoin.biz/'),
        5 => array('name' => 'European Pool', 'addr' => '18401070918313114651', 'url' => 'http://pool.burstcoin.eu'),
	6 => array('name' => 'Burstcoin.de Pool', 'addr' => '15291186589713514299', 'url' => 'http://pool.burstcoin.de/'),
	7 => array('name' => 'Burst4All Pool', 'addr' => '16550913052977077387', 'url' => 'http://pool.burst4all.com/'),
	8 => array('name' => 'BurstCoin.ml Pool', 'addr' => '13210932244776097704', 'url' => 'http://pool.burstcoin.ml:8020/'),
	9 => array('name' => 'burst.lexitoshi.uk', 'addr' => '16732464642587527083', 'url' => 'http://burst.lexitoshi.uk'),
	10 => array('name' => 'tross BurstCoin Pool', 'addr' => '3195398293854632251', 'url' => 'http://burstpool.ddns.net/'),
);


// Surfbar
$conf['surfbar'] = array('burstPayed' => 0, 'burstEuro' => 0, 'burstEuroRate' => 0.000210);
