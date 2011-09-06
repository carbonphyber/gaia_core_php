#!/usr/bin/env php
<?php
namespace Gaia\Stockpile;

// include default setup.
require __DIR__ . '/lib/setup.php';

use Gaia\Test\Tap;


// how many tests are we gonna run?
Tap::plan(94);

// utility function for instantiating the object 
function stockpile( $app, $user_id, $tran = NULL ){
    return new Hybrid( $app, $user_id, $tran );
}

// wrap in try/catch so we can fail and print out debug.
try {
    include __DIR__ . '/lib/common_tests.php';
    include __DIR__ . '/lib/transaction_extended_tests.php';
    include __DIR__ . '/lib/trade_tests.php';
    include __DIR__ . '/lib/hybrid_tests.php';

} catch( \Exception $e ){
    Tap::fail( 'unexpected exception thrown' );
    print $e;
}