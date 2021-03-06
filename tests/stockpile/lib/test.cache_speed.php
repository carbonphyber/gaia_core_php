<?php
namespace Gaia\Stockpile;
use Gaia\Test\Tap;

if( ! class_exists('\Memcache') && ! class_exists('\Memcached') ){
    Tap::plan('skip_all', 'no pecl-memcache or pecl-memcached extension installed');
}

if( ! @fsockopen('127.0.0.1', '11211')) {
    Tap::plan('skip_all', 'memcached not running on localhost');
}

// how many tests are we gonna run?
Tap::plan(5);

// utility function for instantiating the object 
function stockpile( $app, $user_id ){
    return new Cacher( new Tally( $app, $user_id), memcache() );
}

// wrap in try/catch so we can fail and print out debug.
try {
    include __DIR__ . '/speed_tests.php';
    
} catch( \Exception $e ){
    Tap::fail( 'unexpected exception thrown' );
    print $e;
}
