<?php
class Database
{
    
    private static $dbName = 'controlvehicular';
	private static $dbHost = 'localhost';
	private static $dbUserName = 'root';
	private static $dbPassword = 'DIFinformatica.03';

	private static $cont  = null;
	
	public function __construct() 
	{
		exit('Init function is not allowed');
	}

	public static function connect()
	{
       if ( null == self::$cont )
       {      
        try 
        {
          self::$cont =  new PDO( "mysql:host=".self::$dbHost.";"."dbname=".self::$dbName, self::$dbUserName, self::$dbPassword,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));  
        }
        catch(PDOException $e) 
        {
          die($e->getMessage());
        }
       } 
       return self::$cont;
	}
	
	public static function disconnect()
	{
		self::$cont = null;
	}
}
?>
