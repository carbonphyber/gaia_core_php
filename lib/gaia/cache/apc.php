<?php
namespace Gaia\Cache;
if( ! function_exists('apc_fetch') ) require __DIR__ . '/apc.stub.php';

/**
* make APC conform to our cache interface. Works pretty well except for the replace call, since apc
* doesn't exactly support that. I can fake it though.
*/
class Apc Implements Iface {

    function get( $request){
        $res = apc_fetch( $request );
        if( is_array( $request ) && ! is_array( $res ) ) $res = array();
        return $res;
    }
    
    function set($k, $v, $expires = 0 ){
        return apc_store( $k, $v, $expires );
    }
    
    function add( $k, $v, $expires = 0 ){
        return apc_add( $k, $v, $expires );
    }
    
    function replace( $k, $v, $expires = 0 ){
        if( ! $this->get( $k ) ) return FALSE;
        return $this->set( $k, $v, $expires );
    }
    
    function increment( $k, $step = 1){
        return apc_inc($k, $step );
    }
    
    function decrement( $k, $step = 1){
        return apc_dec( $k, $step );
    }
    
    function delete( $k ){
        return apc_delete( $k );
    }
}