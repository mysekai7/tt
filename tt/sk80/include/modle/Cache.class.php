<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Cache extends Record
{
    public static function getTotal()
    {
        $tablename = Record::tableFromClass('Article');
        $sql = "SELECT COUNT(id) AS total FROM $tablename WHERE type='post'";
        self::$__CONN__->query($sql);
        return self::$__CONN__->last_result[0]->total;
    }
}

?>
