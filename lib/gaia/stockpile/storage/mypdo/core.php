<?php
namespace Gaia\Stockpile\Storage\MyPDO;
use \Gaia\DB\Driver\PDO;
use \Gaia\Stockpile\Exception;


class Core {
    protected $db;
    protected $app;
    protected $user_id;
    public function __construct( PDO $db, $app, $user_id){
        $this->db = $db;
        $this->app = $app;
        $this->user_id = $user_id;
    }
    
    public function create(){
        $table = $this->table();
        $rs = $this->execute('SHOW TABLES LIKE %s', $this->table());
        $row = $rs->fetch(\PDO::FETCH_BOTH);
        if( ! $row ) return $this->execute($this->sql('CREATE'));
    }
    
    protected function table(){
        return $this->app . '_stockpile_' . constant(get_class( $this ) . '::TABLE' );
    }
    
    protected function sql( $name ){
        return $this->injectTableName( constant(get_class($this) . '::SQL_' . $name) );
    }
    
    protected function injectTableName( $query ){
        return str_replace('{TABLE}', $this->table(), $query );
    }
    
    protected function execute( $query /*, .... */ ){
        $args = func_get_args();
        array_shift( $args );
        $rs = $this->db->query( $qs = $this->db->format_query_args( $query, $args ) );
        if( ! $rs ) throw new Exception('database error', array('db'=> $this->db, 'query'=>$qs, 'error'=>$this->db->errorInfo()) );
        return $rs;
    }
}