#!/usr/bin/env php
<?php
include __DIR__ . '/common.php';
use Gaia\Test\Tap;
use Gaia\Job;
use Gaia\JobRunner;
use Gaia\Pheanstalk;

if( ! @fsockopen('127.0.0.1', '11300')) {
    Tap::plan('skip_all', 'Beanstalkd not running on localhost');
}

Tap::plan(5);

$tube = '__test__';

Job::attach( 
    function(){
        return array( new Pheanstalk('127.0.0.1', '11300' ) );
    }
);


for( $i = 0; $i < 2; $i++){
    $start = microtime(TRUE);
    $job = new Job('http://admin1.sv3.gaiaonline.com/');
    $job->queue = 'test';
    $id = $job->store();
    $elapsed = number_format( microtime(TRUE) - $start, 3);
    print "\nSTORE " . $id . ' ' . $elapsed . 's';
}

for( $i = 0; $i < 1000; $i++){
    $start = microtime(TRUE);
    $job = new Job('http://jloehrer.d.gaiaonline.com/test/dummy.php'); //'https://graph.facebook.com/cocacola');
    $job->queue = 'test';
    $id = $job->store();
    $elapsed = number_format( microtime(TRUE) - $start, 3);
    print "\nSTORE " . $id . ' ' . $elapsed . 's';
}

$start = microtime(TRUE);


Job::watch('test');
Job::config()->set('register_url', 'http://jloehrer.d.gaiaonline.com/test/dummy.php?register');
$runner = new JobRunner();
//$runner->setLimit(8);
$runner->setTimelimit(120);
$runner->enableDebug();
$runner->setDebugLevel(1);
$runner->setMax(10);

$runner->attach( function(){
    print "\nCALLBACK TRIGGERED\n";
    Job::attach( array( new Beanstalk('127.0.0.1', '11300' ) ) );
    Job::watch('test');

}, $timeout = 10 );


$runner->send();

print_r( $runner );

$elapsed = number_format( microtime(TRUE) - $start, 3);


print "\nDONE: $elapsed\n";