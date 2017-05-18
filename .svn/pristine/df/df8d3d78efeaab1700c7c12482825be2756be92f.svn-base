<?php
/**
 * Created by PhpStorm.
 * User: Jerry
 * Date: 2015/5/26
 * Time: 20:39
 */
namespace Onemla\Access;

use Onemla\OnemlaHelper;
use Onemla\UCenter\UCUser;

class OnemlaAccess{
    /**
     * @var array
     */
    protected static $assetRules = array();
    /**
     * @var array
     */
    protected static $groupsByUser = array();

    //保存当前操作的模块
    const ACCESS_MODULE_KEY = "access_module_key";

    //设置模块名
    public static function setModuleName($pModuleName)
    {
        OnemlaHelper::session(self::ACCESS_MODULE_KEY, $pModuleName);
    }

    //获取当前模块名
    public static function getModuleName()
    {
        return OnemlaHelper::session(self::ACCESS_MODULE_KEY);
    }

    /**
     * @param $asset
     * @return mixed
     */
    public static function getAssetRules($asset){
        $map = array();
        if(is_numeric($asset)){
            $map['id'] = (int)$asset;
        }else{
            $map['name'] = $asset;
        }
        $rules = M('assets')->where($map)->find();
        if($rules){
            $rules = json_decode($rules['rules'], true);
            self::$assetRules[$asset] = $rules;
        }

        return self::$assetRules[$asset];
    }

    /**
     * @param int $parent_id
     */
    public static function getAssetLevelRules($parent_id = 1){
        $rules = M('assets')->field("name, rules")
                            ->select();
        foreach($rules as $rule){
            self::$assetRules[$rule['name']] = json_decode($rule['rules'], true);
        }
    }

    /**
     * @param $userId
     * @param bool $recursive
     * @return mixed
     */
    public static function getGroupsByUser($userId, $recursive = true){
        $storeId = $userId . ':' . (int) $recursive;


        if (!isset(self::$groupsByUser[$storeId])){
            M('usergroup_map') -> alias('map') -> where("map.user_id = '%d'", $userId)
                               -> field($recursive ? 'b.group_id' : 'a.group_id');
            M('usergroup_map') -> join('__USERGROUP__ a ON a.group_id = map.group_id');


            if($recursive){
                M('usergroup_map') -> join('__USERGROUP__ b ON b.lft <= a.lft AND b.rgt >= a.rgt', 'LEFT');
            }

            $result = M('usergroup_map') -> select();
            $groupids = array();
            foreach($result as $group){
                $groupids[] = $group['group_id'];
            }
            self::$groupsByUser[$storeId] = $groupids;
        }

        return self::$groupsByUser[$storeId];
    }

    public static function check($userId, $actions){
        // Sanitise inputs.
        $userId = (int) $userId;
        $User = OnemlaHelper::getUser($userId);

        $action = strtolower(preg_replace('#[\s\-]+#', '.', trim($actions['action'])));
        $asset = strtolower(preg_replace('#[\s\-]+#', '.', trim($actions['group'])));
        // Get the rules for the asset recursively to root if not already retrieved.
        if (empty(self::$assetRules[$asset])) {
            self::$assetRules[$asset] = self::getAssetRules($asset);
        }

        if($User->group == null){
            UCUser::getInstance($userId)->set('group', self::getGroupsByUser($userId));
        }
        return self::allow(self::$assetRules[$asset][$action], $User->group);
    }

    /**
     * @param $actions
     * @return bool
     */
    public static function checkGroup($actions, $group=null){

        if(is_null($group)){
            $User = OnemlaHelper::getUser();
            if( $User -> isRoot ){
                return true;
            }
            $group = $User->group;
        }

//        $action = strtolower(preg_replace('#[\s\-]+#', '.', trim($actions['action'])));
        $action = $actions['action'];
        $asset = strtolower(preg_replace('#[\s\-]+#', '.', trim($actions['group'])));

        // Get the rules for the asset recursively to root if not already retrieved.
        if (empty(self::$assetRules[$asset])) {
            self::$assetRules[$asset] = self::getAssetRules($asset);
        }

        return self::allow(self::$assetRules[$asset][$action], $group);

        return true;
    }

    public static function checkGroupPermission($iGroupId, $pAction)
    {
        return self::checkGroup(array(
            'action' => $pAction,
            'group'  => $iGroupId,
        ));
    }

    /**
     * @param $assets
     * @param $groups
     * @return bool
     */
    public static function allow($assets, $groups){
        $iIsAllow = false;
        foreach($groups as $groupId){
            if(isset($assets[$groupId])){
                $iIsAllow = $iIsAllow ? $iIsAllow : $assets[$groupId] > 0;
            }
        }
        return $iIsAllow;
    }

    /**
     * @param $component
     * @param string $section
     * @return array|bool
     */
    public static function getActions($component, $section = 'component'){
         $actions = self::getActionsFromFile(
             APP_ADMIN_PATH . '/Components/' . $component . '/Conf/access.xml',
            "/access/section[@name='" . $section . "']/"
        );

        return empty($actions) ? array() : $actions;
    }

    /**
     * 解析所有后台管理权限配置
     */
    public static function parseAdminPermissionCfg($pConfig)
    {
        $pAssetsInserts = array();
        foreach ($pConfig['MainMenu'] as $key => $value) {
            $pComponentPermission = array();
            foreach ($value[PERMISSION_CFG_PER] as $k => $v) {
                $pComponentPermission[PERMISSION_ADMIN_PREFIX.".".$value['Module'].".".$k] = $v[PERMISSION_CFG_TITLE];
            }
            $pPermissionCfg[] = array(
                'component' => $value['Module'],
                'title' => $value['Title'],
                'type'  => 1,
                'permission' => json_encode($pComponentPermission),
            );
            $pAssetsInserts[] = array(
                'parent_id' => 0,
                'name' => $value['Module'],
                'title' => $value['Title'],
                'rules' => json_encode(array()),
            );
        }

        $pModel = M("cfg_assets");
        $pModel -> execute("truncate tb_cfg_assets");
        $pModel -> addAll($pPermissionCfg);

        $pUserGroupModel = OnemlaHelper::getModel("UserGroup/Assets");
        $pUserGroupModel -> duplicateInsert($pAssetsInserts);
    }

    /**
     * @param $file
     * @param string $xpath
     * @return array|bool
     */
    public static function getActionsFromFile($file, $xpath = "/access/section[@name='component']/"){
        if (!is_file($file) || !is_readable($file)) {
            // If unable to find the file return false.
            return false;
        } else {
            // Else return the actions from the xml.
            $xml = simplexml_load_file($file);
            return self::getActionsFromData($xml, $xpath);
        }
    }

    /**
     * @param $data
     * @param string $xpath
     * @return array|bool
     */
    public static function getActionsFromData($data, $xpath = "/access/section[@name='component']/"){
        // Initialise the actions array
        $actions = array();

        // Get the elements from the xpath
        $elements = $data->xpath($xpath . 'action[@name][@title]');

        if (!empty($elements)) {
            foreach ($elements as $action) {
                // Add the action to the actions array
                $actions[] = (object) array(
                    'name' => (string) $action['name'],
                    'title' => (string) $action['title'],
                );
            }
        }
        // Finally return the actions array
        return $actions;
    }

    /**
     * 获取后台管理权限名
     */
    public static function getAdminPermissionName($pComponent, $pName)
    {
        return PERMISSION_ADMIN_PREFIX.".".$pComponent.".".$pName;
    }

    /**
     * 检查后台管理权限
     * @param $pCompenent 组件名
     * @param $pName 权限名，读或者写
     * @return bool
     */
    public static function checkAdminPermission($pCompenent, $pName)
    {
        if ($pName == PERMISSION_SUPER) {
            return self::isRoot();
        }

        $pName = self::getAdminPermissionName($pCompenent, $pName);
        return self::checkGroup(array(
            'group'  => $pCompenent,
            'action' => $pName,
        ));
    }

    //检查权限
    public static function checkAdminAccess($pAction)
    {
        $pComponentName = self::getModuleName();
        return self::checkAdminPermission($pComponentName, $pAction);
    }

    //是否超级管理员
    public static function isRoot()
    {
        return OnemlaHelper::getUser() -> isRoot;
    }


}