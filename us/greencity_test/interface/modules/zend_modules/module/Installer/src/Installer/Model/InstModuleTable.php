<?php
/* +-----------------------------------------------------------------------------+
*    OpenEMR - Open Source Electronic Medical Record
*    Copyright (C) 2013 Z&H Consultancy Services Private Limited <sam@zhservices.com>
*
*    This program is free software: you can redistribute it and/or modify
*    it under the terms of the GNU Affero General Public License as
*    published by the Free Software Foundation, either version 3 of the
*    License, or (at your option) any later version.
*
*    This program is distributed in the hope that it will be useful,
*    but WITHOUT ANY WARRANTY; without even the implied warranty of
*    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*    GNU Affero General Public License for more details.
*
*    You should have received a copy of the GNU Affero General Public License
*    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*    @author  Jacob T.Paul  <jacob@zhservices.com>
*    @author  Vipin Kumar   <vipink@zhservices.com>
*    @author  Remesh Babu S <remesh@zhservices.com>
* +------------------------------------------------------------------------------+
*/

namespace Installer\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Config\Reader\Ini;
use Zend\Db\ResultSet\ResultSet;
use \Application\Model\ApplicationTable;

class InstModuleTable
{
  protected $tableGateway;
  protected $applicationTable;
  public function __construct(TableGateway $tableGateway){
    $this->tableGateway = $tableGateway;
    $adapter = \Zend\Db\TableGateway\Feature\GlobalAdapterFeature::getStaticAdapter();
    $this->adapter              = $adapter;
    $this->resultSetPrototype   = new ResultSet();
    $this->applicationTable	    = new ApplicationTable;
  }
  
  /**
   * Get All Modules Configuration Settings
   * 
   * @return type
   */
  public function getConfigSettings($id)
  {
    $sql    = "SELECT * FROM module_configuration
                        WHERE module_id =?";
    $params = array($id);
    $result = $this->applicationTable->zQuery($sql, $params);
    return $result;
  }
  
  /**
   * 
   * @param type $dir
   * @return boolean
   */
  public function installSQL($dir)
  {
    $sqltext = $dir . "/table.sql";
    if ($sqlarray = @file($sqltext)) {
      $sql = implode("", $sqlarray);
      $sqla = split(";", $sql);
      $this->getInstallerTable()->testingDir($dir);
      foreach ($sqla as $sqlq) {
        if (strlen($sqlq) > 5) {
          $query    = rtrim("$sqlq");
          $result = $this->applicationTable->zQuery($query);
        }
      }		    
      return true;
    } else {
      return true;
    }
  }
  
  /**
   * Save Configuration Settings
   *
   */
  public function saveSettings($fieldName, $fieldValue, $moduleId)
  {
    /** Check the field exist */
    $sql = "SELECT * FROM module_configuration
                      WHERE field_name = ?
                      AND module_id = ?";
    $params = array(
      $fieldName,
      $moduleId,
    );
    $result = $this->applicationTable->zQuery($sql, $params);
    if ($result->count() > 0) {
      $sql = "UPDATE module_configuration SET field_value = ?
                                          WHERE module_id = ?
                                          AND field_name = ?";
      $params = array(
        $fieldValue,
        $moduleId,
        $fieldName,
      );
      $result = $this->applicationTable->zQuery($sql, $params);
    } else {
      $sql = "INSERT INTO module_configuration SET field_name = ?, field_value = ?, module_id = ?";
      $params = array(
        $fieldName,
        $fieldValue,
        $moduleId,
      );
      $result = $this->applicationTable->zQuery($sql, $params);
    }
  }

  /**
   * this will be used to register a module 
   * @param unknown_type $directory
   * @param unknown_type $rel_path
   * @param unknown_type $state
   * @param unknown_type $base
   * @return boolean
   */
  public function register($directory,$rel_path,$state=0, $base = "custom_modules" )
  {
    $sql = "SELECT mod_active FROM modules WHERE mod_directory = ?";
    $params = array(
               $directory,
            );
    $check = $this->applicationTable->zQuery($sql, $params);

    if ($check->count() == 0) {
      $added = "";
      $typeSet = "";

      $lines = @file($GLOBALS['srcdir']."/../interface/modules/$base/$added$directory/info.txt");
      if ($lines){
        $name = $lines[0];
      }	else {
        $name = $directory;
      }
      $uiname = ucwords(strtolower($directory));
      $section_id = 0;
      $sec_count = "SELECT count(*) as total FROM module_acl_sections";
      $sec_result = $this->applicationTable->zQuery($sec_count);
      $arr = $sec_result->current();
      if($arr['total'] > 0){
        $sql_max_id = "SELECT MAX(section_id) as max_id FROM module_acl_sections";
        $sec_result = $this->applicationTable->zQuery($sql_max_id);
        $arr = $sec_result->current();
        $section_id = $arr['max_id'];
      }
      $section_id++;
      if($base != "custom_modules"){
        $added = "module/";

        $sql = "INSERT INTO modules SET mod_id = ?,  mod_name = ?,
                                      mod_active = ?, 
                                      mod_ui_name = ?, 
                                      mod_relative_link = ?,
                                      type=1,  
                                      mod_directory = ?, 
                                      date=NOW()
                                      ";
      } else {
        $sql = "INSERT INTO modules SET mod_id = ?,  mod_name = ?,
                                      mod_active = ?, 
                                      mod_ui_name = ?, 
                                      mod_relative_link = ?,
                                      mod_directory = ?, 
                                      date=NOW()
                                      ";
      }

      $params = array(
         $section_id, 
         $name,
         $state,
         $uiname,
         strtolower($rel_path),
         $directory,
      );

      $result = $this->applicationTable->zQuery($sql, $params);
      $moduleInsertId = $result->getGeneratedValue();
      
      $sql = "INSERT INTO module_acl_sections VALUES (?,?,0,?,?)";
      $params = array($moduleInsertId,$name,strtolower($directory),$moduleInsertId);
      $result = $this->applicationTable->zQuery($sql, $params);
      return $moduleInsertId;
    }
    return false;
    
  }
  
  /**
   * get the list of all modules
   * @return multitype:
   */
  public function allModules(){
    $sql    = "SELECT * FROM modules ORDER BY mod_ui_order ASC";
    $params = array();
    $result = $this->applicationTable->zQuery($sql, $params);
    return $result;
  }
  /**
   * get the list of all modules
   * @return multitype:
   */
  public function getInstalledModules()
  {
    $all = array();
    $sql = "select * from modules where mod_active = 1 order by mod_ui_order asc";
    $res =  $this->applicationTable->zQuery($sql);
     
    if(count($res) > 0){
      foreach($res as $row) {
        $mod = new InstModule();
        $mod -> exchangeArray($row);
        array_push($all,$mod);
      }
    }	
    return $all;    
  }
  
  /**
   * @param int $id
   * @param string $cols
   * @return Ambigous <boolean, unknown>
   */
  function getRegistryEntry( $id, $cols = "" )
  {
    $sql = "SELECT mod_directory FROM modules WHERE mod_id = ?";
    $results   = $this->applicationTable->zQuery($sql, array($id));
    
    $resultSet 	= new ResultSet();
    $resultSet->initialize($results);	
    $resArr		= $resultSet->toArray();
    $rslt 		= $resArr[0];

    $mod = new InstModule();
    $mod -> exchangeArray($rslt);   

    return $mod;
  }

  /**
   * Function to enable/disable a module
   * @param int 		$id		Module PK
   * @param string 	$mod	Status
   */
  public function updateRegistered ( $id, $mod = '', $values = '' ) 
  {
    if($mod == "mod_active=1"){
      $resp	= $this->checkDependencyOnEnable($id);
      if($resp['status'] == 'success' && $resp['code'] == '1') {
        $sql = "UPDATE modules SET mod_active = 1, 
                                    date = ? 
                               WHERE mod_id = ?";
        $params = array(
          date('Y-m-d H:i:s'),
          $id,
        );
        $results   = $this->applicationTable->zQuery($sql, $params);
      }
    } else if($mod == "mod_active=0"){
      $resp	= $this->checkDependencyOnDisable($id);	    
      if($resp['status'] == 'success' && $resp['code'] == '1') {
        $sql = "UPDATE modules SET mod_active = 0, 
                                    date = ? 
                               WHERE mod_id = ?";
        $params = array(
          date('Y-m-d H:i:s'),
          $id,
        );
        $results   = $this->applicationTable->zQuery($sql, $params);                
      }	 
    } else {
      $sql = "UPDATE modules SET sql_run=1, mod_nick_name=?, mod_enc_menu=?, 
                                 date=NOW() 
                             WHERE mod_id = ?";
      $params = array(
        $values[0],
        $values[1],  
        $id,
      );
      $resp   = $this->applicationTable->zQuery($sql, $params);
    }
    return $resp;
  }
  
  /**
   * Function to get ACL objects for module
   * @param int 		$mod_id		Module PK
   */
  public function getSettings($type,$mod_id)
  {
    if($type=='ACL')
      $type = 1;
    elseif($type=='Hooks')
      $type = 3;
    else
      $type = 2;
    $all = array();
    $sql = "SELECT ms.*,mod_directory 
                            FROM modules_settings AS ms 
                            LEFT OUTER JOIN modules AS m 
                            ON ms.mod_id=m.mod_id 
                            WHERE m.mod_id=? AND fld_type=?";
    $res = $this->applicationTable->zQuery($sql, array($mod_id, $type));
    if($res){
      foreach($res as $key => $m) {
      $mod = new InstModule();
      $mod -> exchangeArray($m);
      array_push($all, $mod);
      }
    }
    return $all;
  }
  
  /**
   * Function to get Oemr User Group
   */
  public function getOemrUserGroup()
  {
    $all = array();
    $sql = "SELECT * FROM gacl_aro_groups AS gag 
                        LEFT OUTER JOIN gacl_groups_aro_map AS ggam 
                        ON gag.id=ggam.group_id
                        WHERE parent_id<>0 
                        AND group_id IS NOT NULL 
                        GROUP BY id ";
    $res = $this->applicationTable->zQuery($sql);
    if($res){
      foreach($res as $key => $m) {
        $mod = new InstModule();
        $mod -> exchangeArray($m);
        array_push($all,$mod);
      }
    }
    return $all;
  }
  /**
   * Function to get Oemr User Group and Aro Map
   */
  public function getOemrUserGroupAroMap()
  {
    $all = array();
    $sql = "SELECT group_id,u.id AS id,CONCAT_WS(' ',CONCAT_WS(',',u.lname,u.fname),u.mname) AS user,u.username 
                    FROM gacl_aro_groups gag
                    LEFT OUTER JOIN gacl_groups_aro_map AS ggam 
                    ON gag.id=ggam.group_id 
                    LEFT OUTER JOIN gacl_aro AS ga 
                    ON ggam.aro_id=ga.id
                    LEFT OUTER JOIN users AS u 
                    ON u.username=ga.value 
                    WHERE group_id IS NOT NULL 
                    ORDER BY gag.id";
    $res = $this->applicationTable->zQuery($sql);
    if($res){
      foreach($res as $key => $m) {
        $all[$m['group_id']][$m['id']] = $m['user'];
      }
    }
    return $all;
  }
  
  /**
   * Function to get Active Users
   */
  public function getActiveUsers()
  {
    $all = array();
    $sql = "SELECT id,username,CONCAT_WS(' ',fname,mname,lname) AS USER 
                    FROM users 
                    WHERE active=1 
                    AND username IS NOT NULL 
                    AND username<>''";
    $res = $this->applicationTable->zQuery($sql);
    if($res){
      foreach($res as $key => $m) {
        $all[$m['username']] = $m['USER'];
      }
    }
    return $all;
  }
  
  public function getTabSettings($mod_id)
  {
    $all = array();
    $sql = "SELECT fld_type,COUNT(*) AS cnt  
                  FROM modules_settings 
                  WHERE mod_id=? 
                  GROUP BY fld_type 
                  ORDER BY fld_type ";
    $res = $this->applicationTable->zQuery($sql,array($mod_id));
    if($res){
      foreach($res as $key => $m) {
        $all[$m['fld_type']] = $m['cnt'];
      }
    }
    return $all;
  }
  /**
   *Function To Get Active ACL for this Module
   */
  public function getActiveACL($mod_id)
  {
    $arr = array();

    $sql = "SELECT mod_directory FROM modules WHERE mod_id=?";
    $result = $this->applicationTable->zQuery($sql, array($mod_id));
    $Section = $result->current();
    $aco = "modules_" . $Section['mod_directory'];
    
    $sql = "SELECT * FROM gacl_aco_map WHERE section_value=?";
    $MapRes = $this->applicationTable->zQuery($sql, array($aco));
    foreach ($MapRes as $key => $MapRow) {
      $sqlSelect = "SELECT acl_id,value,CONCAT_WS(' ',fname,mname,lname) AS user 
                            FROM gacl_aro_map 
                            LEFT OUTER JOIN users 
                            ON value=username 
                            WHERE active=1 AND acl_id=?";
      $aroRes = $this->applicationTable->zQuery($sqlSelect, array($MapRow['acl_id']));
      $i=0;
      foreach ($aroRes as $k => $aroRow) {
        $arr[$MapRow['value']][$i]['acl_id']  = $aroRow['acl_id'];
        $arr[$MapRow['value']][$i]['value']   = $aroRow['value'];
        $arr[$MapRow['value']][$i]['user']    = $aroRow['user'];
        $i++;
      }
    }
    return $arr;
  }
  
  /**
   *Function To Get Saved Hooks For this Module
   */
  public function getActiveHooks($mod_id)
  {
    $all = array();
    $sql		= "SELECT msh.*,ms.menu_name FROM modules_hooks_settings AS msh LEFT OUTER JOIN modules_settings AS ms ON
                obj_name=enabled_hooks AND ms.mod_id=msh.mod_id LEFT OUTER JOIN modules AS m ON msh.mod_id=m.mod_id 
                WHERE fld_type = '3' AND mod_active = 1 AND msh.mod_id = ? ";
    $res		= $this->applicationTable->zQuery($sql,array($mod_id));
    foreach($res as $row) {
      $mod = new InstModule();
      $mod -> exchangeArray($row);
      array_push($all,$mod);        
    }
    return $all;
  }
  
  /**
   * Function to get Status of a Hook
   */
  public function getHookStatus($modId,$hookId,$hangerId)
  {
    if($modId && $hookId && $hangerId){	
      $sql = "select * FROM modules_hooks_settings 
                        WHERE mod_id = ? 
                        AND enabled_hooks = ? 
                        AND attached_to = ? ";
      $res	= $this->applicationTable->zQuery($sql, array($modId, $hookId, $hangerId));
      foreach($res as $row){
        $modArr	= $row;
      }

      if($modArr['mod_id'] <> ""){
        return "1";
      } else {
        return "0";
      }
    }
  }
  
  /**
   * Function to Delete Hooks
   */
  public function saveHooks($modId,$hookId,$hangerId)
  {
    if($modId){
      $sql = "INSERT INTO modules_hooks_settings(mod_id, enabled_hooks, attached_to) VALUES (?,?,?) ";
      $this->applicationTable->zQuery($sql, array($modId, $hookId, $hangerId));			
    }
  }
  
  /**
   * Save Module Hook settings
   */
  public function saveModuleHookSettings($modId,$hook)
  {
    $sql = "INSERT INTO modules_settings SET mod_id = ?,
                                              fld_type = 3,
                                              obj_name = ?,
                                              menu_name = ?,
                                              path = ?";
    $params = array(
      $modId,
      $hook['name'],
      $hook['title'],
      $hook['path'],
    );
    $this->applicationTable->zQuery($sql, $params);
  }

  /**
   * Function to Delete Hooks
   */
  public function DeleteHooks($post)
  {
    if($post['hooksID']){						
      $this->applicationTable->zQuery("DELETE FROM modules_hooks_settings WHERE id = ? ",array($post['hooksID']));			
    }
  }
  
  /**
   * Function to Delete Module Hooks
   */
  public function deleteModuleHooks($modId)
  {
    if($modId){
      //DELETE MODULE HOOKS							
      $this->applicationTable->zQuery("DELETE FROM modules_hooks_settings WHERE mod_id = ? ",array($modId));
    }
  }
  
  public function checkDependencyOnEnable($mod_id)
  {
    $retArray	= array();
    $modDirectory	= $this->getModuleDirectory($mod_id);
    if($modDirectory){
      //GET DEPENDED MODULES OF A MODULE HOOKS FROM A FUNCTION IN ITS MODEL CONFIGURATION CLASS
      $depModules	= $this->getDependedModulesByDirectoryName($modDirectory);
      $requiredModules	= array();
      if(count($depModules) > 0){
        foreach($depModules as $depModule){
          if($depModule <> ""){																						
            $res	= $this->getModuleStatusByDirectoryName($depModule);																								
            if($res <> "Enabled"){
              $requiredModules[]	= $depModule;
            }	
          }						
        }			
      }
  
      if(count($requiredModules) > 0) {
        $retArray['status']	= "failure";
        $retArray['code']   = "200";
        $retArray['value']	= $requiredModules;
      } else {
        $retArray['status']	= "success";
        $retArray['code']   = "1";
        $retArray['value']	= "";
      }
    } else {
      $retArray['status']	= "failure";
      $retArray['code']   = "400";
      $retArray['value']	= \Application\Listener\Listener::z_xlt("Module Directory not found");
    }
    return $retArray;
  }
  
  
  public function checkDependencyOnDisable($mod_id)
  {
    $retArray	= array();
    $depFlag	= "0";
    $modArray	= $this->getInstalledModules();

    //GET MODULE DIRECTORY OF DISABLING MODULE
    $modDirectory	= $this->getModuleDirectory($mod_id);
    $usedModArr	= array();
    if(count($modArray) > 0){
      //LOOP THROUGH INSTALLED MODULES
      foreach($modArray as $module) {
        if($module->modId <> ""){
          //GET MODULE DEPENDED MODULES
          $InstalledmodDirectory	= $this->getModuleDirectory($module->modId);
          $depModArr	= $this->getDependencyModulesDir($module->modId);
          if(count($depModArr) > 0){
            //LOOP THROUGH DEPENDENCY MODULES
            //CHECK IF THE DISABLING MODULE IS BEING DEPENDED BY OTHER INSTALLED MODULES
            foreach($depModArr as $depModule) {
              if($modDirectory == $depModule){
                $depFlag	= "1";
                //break(2);
                $usedModArr[] = $InstalledmodDirectory;
              }
            }		
          }
        }
      }
    }
    if($depFlag == "0"){
        $retArray['status']	= "success";
        $retArray['code']   = "1";
        $retArray['value']	= "";
    } else {
      $usedModArr	= array_unique($usedModArr);
      $multiple   = "module";
      if(count($usedModArr) > 1) {
        $multiple	= "modules";
      }
      $usedModules	= implode(",",$usedModArr);
      $retArray['status']	= "failure";
      $retArray['code']   = "200";
      $retArray['value']	= \Application\Listener\Listener::z_xlt("Dependency Problem") . ': ' . \Application\Listener\Listener::z_xlt("This module is being used by ") . $usedModules ." " . \Application\Listener\Listener::z_xlt($multiple);
    }
    return $retArray;
  }
  
  public function getDependencyModules($mod_id)
  {
    $reader = new Ini();
    $modDirname	= $this->getModuleDirectory($mod_id);
    if($modDirname <> ""){			
      $depModuleStatusArr	= array();
      //GET DEPENDED MODULES OF A MODULE HOOKS FROM A FUNCTION IN ITS MODEL CONFIGURATION CLASS
      $depModulesArr	= $this->getDependedModulesByDirectoryName($modDirname);
      $ret_str = "";
      if(count($depModulesArr)>0){
        $count = 0;
        foreach($depModulesArr as $modDir){
          if($count > 0){
            $ret_str.= ", ";
          }
          $ret_str.= trim($modDir)."(".$this->getModuleStatusByDirectoryName($modDir).")";
          $count++;
        }			
      }		
    }		
    return $ret_str;		
  }
  
  public function getDependencyModulesDir($mod_id)
  {
    $depModulesArr	= array();
    $modDirectory 	= $this->getModuleDirectory($mod_id);
    if($modDirectory){			
      //GET DEPENDED MODULES OF A MODULE HOOKS FROM A FUNCTION IN ITS MODEL CONFIGURATION CLASS
      $depModulesArr	= $this->getDependedModulesByDirectoryName($modDirectory);							 
    }		
    return $depModulesArr;		
  }
  
  public function getModuleStatusByDirectoryName($moduleDir)
  {
    $sql = "SELECT mod_active,mod_directory FROM modules WHERE mod_directory = ? ";
    $res	= $this->applicationTable->zQuery($sql, array(trim($moduleDir)));
      foreach($res as $row) {
        $check	= $row;
      }

    if((count($check) > 0)&& is_array($check)){
      if($check['mod_active'] == "1"){
        return "Enabled";
      } else {
        return "Disabled";
      }		
    } else {
      return "Missing";
    }
  }
  
  public function getHangers()
  {
    return array(
      'reports'       => "Reports",
      'encounter'     => "Encounter",
      'demographics'  => "Demographics",
    );
  }
  
  public function getModuleDirectory($mod_id)
  {
    $moduleName	= "";
    if($mod_id <> ""){	
      $res	= $this->applicationTable->zQuery("SELECT mod_directory FROM modules WHERE mod_id = ? ",array($mod_id));
      foreach($res as $row) {
        $modArr	= $row;
      }
      if($modArr['mod_directory'] <> ""){			
        $moduleName = $modArr['mod_directory'];
      }		
      return $moduleName;
    }
  }
  
  public function checkModuleHookExists($mod_id,$hookId)
  {  
    $sql = "SELECT obj_name FROM modules_settings WHERE mod_id = ? AND fld_type = '3' AND obj_name = ? ";
    $res	= $this->applicationTable->zQuery($sql, array($mod_id, $hookId));
    foreach($res as $row){
      $modArr	= $row;
    }			
    if($modArr['obj_name'] <> ""){
      return "1";
    } else {
      return "0";
    }
  }
  
  //GET MODULE HOOKS FROM A FUNCTION IN CONFIGURATION MODEL CLASS
  public function getModuleHooks($moduleDirectory)
  {	
    $objHooks = $this->getObject($moduleDirectory, $option = 'Controller');
    $hooksArr	= array();
    if($objHooks){
      $hooksArr	= $objHooks->getHookConfig();
    }
    return $hooksArr;
  }
  
  
  //GET MODULE ACL SECTIONS FROM A FUNCTION IN CONFIGURATION MODEL CLASS
  public function getModuleAclSections($moduleDirectory)
  {	
    $objHooks = $this->getObject($moduleDirectory, $option = 'Controller');
    $aclArray	= array();
    if($objHooks){
      $aclArray	= $objHooks->getAclConfig();
    }
    return $aclArray;
  }
  
  public function insertAclSections($acl_data,$mod_dir,$module_id)
  {
    $obj    = new ApplicationTable;
    foreach($acl_data as $acl){
      $identifier = $acl['section_id'];
      $name				= $acl['section_name'];
      $parent			= $acl['parent_section'];

      $sql_parent = "SELECT section_id FROM module_acl_sections WHERE section_identifier =?";
      $result = $obj->zQuery($sql_parent,array($parent));
      $parent_id = 0;
      foreach($result as $row){
        $parent_id = $row['section_id'];
      }
      $sql_max_id = "SELECT MAX(section_id) as max_id FROM module_acl_sections";
      $result = $obj->zQuery($sql_max_id);
      $section_id = 0;
      foreach($result as $row){
        $section_id = $row['max_id'];
      }
      $section_id++;
      $sql_if_exists = "SELECT COUNT(*) as count FROM module_acl_sections WHERE section_identifier = ? AND parent_section =?";
      $result = $obj->zQuery($sql_if_exists,array($identifier,$parent_id));
      $exists = 0;
      foreach($result as $row){
        if($row['count'] > 0) $exists =1;
      }
      if($exists) continue;
      $sql_insert = "INSERT INTO module_acl_sections (`section_id`,`section_name`,`parent_section`,`section_identifier`,`module_id`) VALUES(?,?,?,?,?)";
      $obj->zQuery($sql_insert,array($section_id,$name,$parent_id,$identifier,$module_id));
    }

    $sql = "SELECT COUNT(mod_id) AS count FROM modules_settings WHERE mod_id = ? AND fld_type = 1";
    $result = $obj->zQuery($sql,array($module_id));
    $exists = 0;
    foreach($result as $row){
      if($row['count'] > 0) $exists =1;
    }
    if(!$exists){
      $sql = "INSERT INTO modules_settings(`mod_id`,`fld_type`,`obj_name`,`menu_name`) VALUES(?,'1',?,?)";
      $result = $obj->zQuery($sql,array($module_id,$mod_dir,$mod_dir));
    }
  }
  
  public function deleteACLSections($module_id)
  {
    $obj    = new ApplicationTable;
    $sql 		= "DELETE FROM module_acl_sections WHERE module_id =? AND parent_section <> 0";
    $obj->zQuery($sql, array($module_id));

    $sqsl		= "DELETE FROM modules_settings WHERE mod_id =? AND fld_type = 1";
    $obj->zQuery($sql, array($module_id));
  }
  
  //GET DEPENDED MODULES OF A MODULE FROM A FUNCTION IN CONFIGURATION MODEL CLASS
  public function getDependedModulesByDirectoryName($moduleDirectory)
  {	
    $objHooks = $this->getObject($moduleDirectory, $option = 'Controller');
    $retArr	= array();
    if($objHooks){
      $retArr	= $objHooks->getDependedModulesConfig();
    }
    return $retArr;
  }
  
  /**
   * Function to Save Module Hooks
   */
  public function saveModuleHooks($modId,$hookId,$hookTitle,$hookPath)
  {				
    if($modId){
      $sql = "INSERT INTO modules_settings(mod_id, fld_type, obj_name, menu_name, path) VALUES (?,'3',?,?,?) ";
      $this->applicationTable->zQuery($sql, array($modId, $hookId, $hookTitle, $hookPath));			
    }
  }
  
  /**
   * Function to Save Module Hooks
   * 
   */
  public function deleteModuleHookSettings($modId)
  {
    if($modId){
      $sql = "DELETE FROM modules_settings WHERE mod_id = ? AND fld_type = '3'";
      $this->applicationTable->zQuery($sql, array($modId));			
    }
  }
  
  /**
   * Function getObject
   * Dynamically create Module Controller / Form / Setup Object 
   *  
   * @param string $moduleDirectory Module Directory Name
   * @param string $option Controller / Form / Setup to create an Object
   * @param type $adapter 
   * @return type
   */
  public function getObject($moduleDirectory, $option = 'Controller', $adapter = '')
  {
    if ($option == 'Form' && ($moduleDirectory != 'Installer' || $option != 'Model')) {
      $phpObjCode 	= str_replace('[module_name]', $moduleDirectory, '$obj  = new \[module_name]\\' . $option . '\Moduleconfig' . $option . '($adapter);');
      $className		= str_replace('[module_name]', $moduleDirectory, '\[module_name]\\' . $option  . '\Moduleconfig' . $option . '');
    } elseif ($option == 'Setup') {
      $phpObjCode 	= str_replace('[module_name]', $moduleDirectory, '$obj  = new \[module_name]\Controller\SetupController;');
      $setupClass = str_replace('[module_name]', $moduleDirectory, '\[module_name]\Controller\SetupController');
      $setup = array();
      if (class_exists($setupClass)) {
        eval($phpObjCode);
        $setupTile = $obj->getTitle();
        $setup['module_dir']  = strtolower($moduleDirectory);
        $setup['title']       = $setupTile;
      }
      return $setup;
    } else {
      $phpObjCode 	= str_replace('[module_name]', $moduleDirectory, '$obj  = new \[module_name]\\' . $option . '\Moduleconfig' . $option . '();');
      $className		= str_replace('[module_name]', $moduleDirectory, '\[module_name]\\' . $option  . '\Moduleconfig' . $option . '');
    }
    
    if(class_exists($className)){
      eval($phpObjCode);
    }
    return $obj;
  }
  
  /**
   * validateNickName
   * @param String $name nickname
   * @return boolean Nickname available or not.
   * 
   **/ 
  public function validateNickName($name)
  {
    $sql 		= "SELECT * FROM `modules` WHERE mod_nick_name = ? ";
    $result = $this->applicationTable->zQuery($sql,array($name));
    $count  = $result->count();
    return $count;
  }
  
}
?>
