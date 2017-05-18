<?php
/**
 * Created by PhpStorm.
 * User: Jerry
 * Date: 2015/4/16
 * Time: 17:08
 */
namespace Onemla;
use Think\Model;

class OnemlaModel extends Model{

    public function duplicateInsert($values)
    {
        $sql_keys      = '';
        $sql_values    = array();
        $sql_duplicate = array();

        foreach($values as $value){
            if (is_array($value)){
                if(empty($sql_keys)){
                    $sql_keys = '`'.join('`,`',array_keys($value)).'`';
                    $sql_duplicate = array_keys($value);
                }
                $sql_values[] = '(\''.join('\',\'',$value).'\')';
            }else{
                $sql_keys = '`'.join('`,`',array_keys($values)).'`';
                $sql_values = '\''.join('\',\'',$values).'\'';
                $sql_duplicate = array_keys($values);
                break;
            }
        }

        $sql_updates = array();
        foreach ($sql_duplicate as $dupKey)
        {
            $sql_updates[] = '`'.$dupKey.'` = VALUES(`'.$dupKey.'`)';
        }

        if (is_string($sql_values)) {
            $sql = 'INSERT INTO `'.$this -> getTableName().'` ('.$sql_keys.') VALUES('.$sql_values.')';
        }else{
            $sql = 'INSERT INTO `'.$this -> getTableName().'` ('.$sql_keys.') VALUES'.join(' , ',$sql_values);
        }

        $sql .= ' ON DUPLICATE KEY UPDATE '.join(',', $sql_updates);

        return $this -> execute($sql);
    }
}