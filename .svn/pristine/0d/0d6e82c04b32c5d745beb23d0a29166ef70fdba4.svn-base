<?php
/**
 * Created by PhpStorm.
 * User: Jerry
 * Date: 2015/5/27
 * Time: 14:32
 */
namespace Onemla;

class OnemlaBTreeInfinite extends OnemlaModel{
    /**
     * @var string
     */
    protected $_tbl_key = 'id';
    /**
     * @var string
     */
    protected $_parent = 'parent_id';
    /**
     * @var string
     */
    protected $_left = 'lft';
    /**
     * @var string
     */
    protected $_right = 'rgt';

    public function __construct(){
        parent::__construct();
    }

    public function rebuild($parent_id = 0, $left = 0){
        // Get all children of this node
        $childrens = $this -> where("%s='%d'", $this->_parent, $parent_id)
                           -> field(array($this->_tbl_key))
                           -> select();
        // The right value of this node is the left value + 1
        $right = $left + 1;

        foreach ($childrens as $children) {
            // $right is the current right value, which is incremented on recursion return
            $right = $this->rebuild($children[$this->_tbl_key], $right);
        }

        $data[$this->_left] = $left;
        $data[$this->_right] = $right;

        // We've got the left value, and now that we've processed
        // the children of this node we also know the right value
        $this->where("%s='%d'", $this->_tbl_key, $parent_id)->save($data);

        // Return the right value of this node + 1
        return $right + 1;
    }

    /**
     * @param $data
     * @return bool|int
     */
    public function store($data){
        $allowEdit = isset($data[$this->_tbl_key]) && (int)$data[$this->_tbl_key] ? true : false;
        if($allowEdit){
            $id = (int)$data[$this->_tbl_key];
            unset($data[$this->_tbl_key]);
            $result = $this->where("%s='%d'", $this->_tbl_key, $id)->save($data);
        }else{
            $result = $this->add($data);
        }

        if($result){
            return $this->rebuild();
        }
        return false;
    }

    /**
     * @param $oid
     * @return array|bool
     */
    public function remove($oid){
        $data = $this->where("%s='%d'", $this->_tbl_key, $oid)
                     ->field(array($this->_tbl_key, $this->_parent, $this->_left, $this->_right))
                     ->find();

        if(!$data)return false;

        $map[$this->_left] = array('EGT', $data[$this->_left]);
        $map[$this->_right] = array('ELT', $data[$this->_right]);

        //获取当前节点下的所有子节点ID
        $results = $this->where($map)->field($this->_tbl_key)->select();
        if(!$results)return false;
        $ids = array();
        foreach($results as $result){
            $ids[] = $result[$this->_tbl_key];
        }

        //删除所有ids节点信息
        $where[$this->_tbl_key] = array('IN', $ids);
        if($this->where($where)->delete()){
            return $ids;
        }
        return false;
    }
}