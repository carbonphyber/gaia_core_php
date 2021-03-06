<?php
namespace Gaia\NewID;

class TimeRandLock extends TimeRand implements Iface {
    
    protected $cache;
    
    public function __construct( \Gaia\Cache\Iface $cache ){
        $this->cache = $cache;
    }
    
    // use the cache to guarantee the id generated is unique in the system.
    public function id(){
        for( $i = 0; $i < 100; $i++){
            $id = parent::id();
            if( $this->cache->add( __CLASS__ . '/' . $id, 1, 3600) ) return $id;
        }
        throw new \Gaia\Exception('unable to allocate new id');
    }
}