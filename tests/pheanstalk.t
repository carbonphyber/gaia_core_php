#!/usr/bin/env php
<?php
include __DIR__ . '/common.php';
use Gaia\Test\Tap;
use Gaia\Pheanstalk;

if( ! @fsockopen('127.0.0.1', '11300')) {
    Tap::plan('skip_all', 'Beanstalkd not running on localhost');
}

if( ! class_exists('Pheanstalk') ) {
    Tap::plan('skip_all', 'Pheanstalk class library not loaded. check vendors/pheanstalk.');
}

Tap::plan(4);

$tube = '__test__';



$client = new Pheanstalk('127.0.0.1', '11300');
$res = $client->useTube($tube);
$res = $client->put('hello', $pri = 1, $delay = 0, $ttr = 10);
Tap::ok( $res, 'put a hello message into beanstalkd');
Tap::like( $res, '/[1-9][0-9]?+/', 'got a valid id back');
$client->watch($tube);
$job = $client->reserve( 0 );
Tap::isa( $job, 'Pheanstalk_Job', 'got a pheanstalk_job object back');
$res = $client->delete( $job );
Tap::ok( $res, 'successfully deleted the message');
