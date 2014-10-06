<?php
// AUTHOR : ALI NAWAZ HIRAJ
// COPYRIGHT (C) 2012 - DEMIXSOFT (R) 2014
// CREATED : 12-03-2012 [DD-MM-YYYY]
// UPDATED : 25-05-2013 [DD-MM-YYYY]

// UPDATED : VER. 2.0 --> 30-04-2014 [DD-MM-YYYY]


  class Database{
  
  // Global Variables
  public static $db_name = "gamegtx";
  public static $server = "localhost";
  public static $DBuser = "root";
  public static $DBpass = "";
  
  private static $connected = false;
  
  // Connection activation function
  public static function connect()
  {
  mysql_connect(Database::$server, Database::$DBuser, Database::$DBpass) or die(mysql_error());
  mysql_select_db(Database::$db_name) or die(mysql_error());
  Database::$connected=true;
  }
  
  // Connection Closing Function
  public static function close()
  {
      if(Database::$connected==true)
      {
        mysql_close();
        Database::$connected=false;
      }
  }
  
  // Change configs on run..
  public static function config($serverName,$db,$username,$password)
  {
       Database::$db_name = $db;
       Database::$DBpass = $password;
       Database::$DBuser = $username;
       Database::$server = $serverName;
       //echo $password;
  }
  
  //perfroming simple query
  public static function query($QueryText)
  {
      Database::connect();
      $result = mysql_query($QueryText) or die(mysql_error());
      //Database::close();
      return $result;
  }
  
  //end of class
  }
?>
