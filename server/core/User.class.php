<?php

class User {

    public function get(){
        $user = get_current_user(); // Current PHP system user
        $uid  = getmyuid();         // User ID of the script owner
        $gid  = getmygid();         // Group ID of the script owner
        $pid  = getmypid();         // Process ID
        $inode = getmyinode();      // Inode of the script
        $php_user = getenv('USER') ?: getenv('USERNAME'); // System user from environment

        $details = [
            "current_user" => $user,
            "user_id" => $uid,
            "group_id" => $gid,
            "process_id" => $pid,
            "inode" => $inode,
            "php_ini_user" => $php_user,
        ];

        return $details;
    }
}

?>