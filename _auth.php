<?php

// Class for authentications for users/gamePlayers

include_once 'Database.php';

class _auth {

    private static $errLog = array();

    public static function isExists($username) {
        $db = new Database();
        $db->connect();
        if (_auth::validString($username)) {
            $result = $db->query("select * from auth_users where username='$username'");
            if (mysql_num_rows($result) > 0) {
                $db->close();
                return true;
            } else {
                $db->close();
                return false;
            }
        } else {
            _auth::pushError("Unsafe Character(s) in given Username!", "_auth->isExists");
            $db->close();
            return false;
        }
        $db->close();
    }
    
    
    public static function getUserID($username) {
        $db = new Database();
        $db->connect();
        if (_auth::validString($username)) {
            $result = $db->query("select id from auth_users where username='$username'");
            if ($data = mysql_fetch_array($result)) {
                $id = $data[0];
                $db->close();
                return $id;
            } else {
                $db->close();
                return false;
            }
        } else {
            _auth::pushError("Unsafe Character(s) in given Username!", "_auth->isExists");
            $db->close();
            return false;
        }
        $db->close();
    }

    public static function addUser($username, $displayName) {
        $db = new Database();
        $db->connect();
        if (_auth::validString($username) && !_auth::isExists($username)) {
            $db->query("insert into auth_users (username,display_name,status) "
                    . "values('$username','$displayName','active');");
            $id = mysql_insert_id();
            $db->close();
            return $id;
        } else {
            _auth::pushError("Unsafe Character(s) in given Username Or User already Exists!", "_auth->addUser");
            $db->close();
            return false;
        }
        $db->close();
    }

    public static function validString($string) {
        if (ctype_alnum($string))
            return true;
        return false;
    }

    private static function pushError($error, $function) {
        array_push(_auth::$errLog, "Function: " . $function . " - " . $error);
    }

    public static function getErrorLogArray() {
        return _auth::$errLog;
    }

}
