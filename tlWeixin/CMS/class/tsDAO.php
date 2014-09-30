<?php
/*
 * tsDAO 操作数据库方法
 * */
class  tsDAO{

    public $dbLink;

    function __construct($dbHost,$dbUser,$dbPass,$dbName,$dbPort=3306) {
        $link = mysql_connect("$dbHost:$dbPort",$dbUser,$dbPass);
        if ($link) {
            mysql_select_db($dbName);
            mysql_query("set names 'utf8'",$link);
            $this->dbLink = $link;
        } else return false;
    }

    //查询一个字段的结果
    public function getColumn($sql){
        try{
            $res=mysql_query($sql,$this->dbLink);
            $row = mysql_fetch_row($res);
            return $row[0];
        }catch (Exception $e){
            echo $sql;
            throw $e;
        }
    }
    /**查询所有数据
     * MYSQL_ASSOC 返回以键值对的形式
     * MYSQL_NUM 返回以数字索引形式
     * MYSQL_BOTH 两种数组形式都有，这是默认的
     */
    public function fetchAllData($sql,$fetch_style=MYSQL_BOTH){
        try{
            $res = mysql_query($sql,$this->dbLink);
            $ret = array();
            while (($row=mysql_fetch_array($res,$fetch_style))!=false) {
                $ret[]=$row;
            }
            return $ret;
        }catch (Exception $e){
            echo $sql;
            throw $e;
        }
    }

/**通过密码查询后台管理员表返回用户id和level
 * $username   用户名
 * $password    密码
**/
	public function getAdminIdAndLevel($username,$password ){
		$qryAdmin = "SELECT `id`,`level` FROM `admin` WHERE adminName='".$username."' AND adminPass=MD5('".$password."')";
		$resAdmin = mysql_query($qryAdmin,$this->dbLink);
		if ($resAdmin) {
		    $rowAdmin = mysql_fetch_row($resAdmin);
		    return $rowAdmin;
		} else return false;
	}
/**
 * 通过adminId获取菜单
 * @param unknown $adminId
 * @return multitype:multitype:
 */
	public function getAccess($adminId){
	    $sql = "SELECT `menuId` FROM `accessControl` WHERE `adminId`='$adminId'";
	    $res = mysql_query($sql,$this->dbLink);
	    $result = array();
	    while (($row = mysql_fetch_array($res,MYSQL_NUM))!=false) {
	        $result[]=$row;
	    }
	    return $result;
	}

	/**
	 *提取后台一级菜单
	 */
	public function getAmenu(){
	    $sql="select * from menu where parent_id=0";
	    $res = mysql_query($sql,$this->dbLink);
	    $result = array();
	    while (($row = mysql_fetch_array($res,MYSQL_ASSOC))!=false) {
	        $result[]=$row;
	    }
	    return $result;
	}
	 /**
	 * 提取菜单项目的子菜单记录集
	 * $parent_id  父级栏目id
	 */
	public function getSubmenu($parent_id){
		$sql="select * from menu where parent_id=".$parent_id;
		$res = mysql_query($sql,$this->dbLink);
		$result = array();
		while (($row = mysql_fetch_array($res,MYSQL_ASSOC))!=false) {
		    $result[]=$row;
		}
		return $result;
	}
	/**
	 * updateOper 修改数据
	 * $data_array  需要修改的表字段数据（是以数组的形式）
	 * $conditions_array  条件（是以一维数组的形式）
	 */
	public function updateOper($tableName,$data_array,$conditions_array){
	    foreach ($data_array AS $field => $value){
	        $fieldValueStr .=  "`".$field."`='".$value."',";
	    }
	    $fieldValueStr = substr($fieldValueStr,0,-1);
	    foreach ($conditions_array AS $field => $value){
	        $conditionStr .= $field."='".$value."' AND ";
	    }
	    $conditionStr = substr($conditionStr,0,-5);
	    $update = "UPDATE $tableName SET ".$fieldValueStr." WHERE ".$conditionStr."";
	    if (mysql_query($update,$this->dbLink)) {
	        return true;
	    } else return false;
	}
	/**
	 * 根据admin表里的id获取管理员级别
	 * @param unknown $db
	 * @param unknown $ESId
	 * @param unknown $user_id
	 */
	public function getLevelById($user_id) {
	    $sql = " SELECT `level` FROM admin WHERE id=$user_id";
	    $res = mysql_query($sql,$this->dbLink);
		if ($res) {
		    $result = mysql_fetch_row($res);
		    return $result[0];
		} else return false;
	}

	/**
	 * deleteOper 删除数据
	 * $conditions_array  条件（是以一维数组的形式）
	 */
	public function deleteOper($table,$condition){
		try{
		    //DELETE FROM Persons WHERE LastName='Griffin'
		    foreach ($condition AS $field => $value){
		        $conditionStr .= $field."='".$value."' AND ";
		    }
		    $conditionStr = substr($conditionStr,0,-5);
		    $delete = "DELETE FROM ".$table." WHERE ".$conditionStr."";
		    return mysql_query($delete,$this->dbLink);
		}catch(Exception  $e)
		{
		    echo $e -> getMessage();
		    throw $e;
		}
	}
	/**
	 *  通过$username获取管理员表数据
	 *  $username 用户名
	 */
	public function getAdministratorByUsername($username){
	    $sql="SELECT * FROM admin WHERE adminName='$username'";
	    $res = mysql_query($sql,$this->dbLink);
		$result = array();
		while (($row = mysql_fetch_array($res,MYSQL_ASSOC))!=false) {
		    $result[]=$row;
		}
		return $result;
	}
	/**
	 * insertOper 修改数据
	 * $table 表名
	 * $data_array  需要添加的表字段数据（是以数组的形式）
	 */
	public function insertOper($tableName,$data_array){
        foreach ($data_array AS $field => $value){
            $fieldStr .= $field.",";
            $valueStr .= "'".$value."',";
        }
        $fieldStr = substr($fieldStr,0,-1);
        $valueStr = substr($valueStr,0,-1);
        $insert = "insert into $tableName (".$fieldStr.") values (".$valueStr.")";
        mysql_query($insert,$this->dbLink);
        return mysql_insert_id($this->dbLink);
	}

	/**
	 *  获取管理员表数据
	 *
	 */
	public function getAdministrator(){
	    $sql="SELECT * FROM admin ORDER BY `adminName`";
	    $res = mysql_query($sql,$this->dbLink);
	    $result = array();
	    while (($row = mysql_fetch_array($res,MYSQL_ASSOC))!=false) {
	        $result[]=$row;
	    }
	    return $result;
	}
	/** 获得permissionInfo总数
	 * @param unknown $adminId
	 */
	public function getPermissionInfoCount($adminId){
	    $sql = "SELECT COUNT(*) AS `count` FROM `v_permissionInfoView`
	    WHERE parent_id<>0 AND adminId=$adminId";
	    $res = mysql_query($sql,$this->dbLink);
		if ($res) {
		    $result = mysql_fetch_row($res);
		    return $result[0];
		} else return false;
	}

	/** 获得详细权限分配列表内容
	 * @param unknown $adminId
	 * @param unknown $sidx
	 * @param unknown $sord
	 * @param unknown $start
	 * @param unknown $limit
	 */
	public function getPermissionInfo($adminId,$sidx,$sord,$start,$limit){
	    $sql = "SELECT v_listMenus.`id`,v_listMenus.`name`,v_listMenus.`parName`, accessControl.`adminId` FROM v_listMenus
	    LEFT JOIN accessControl ON accessControl.`menuId`=v_listMenus.`id` AND adminId=$adminId";
	    $res = mysql_query($sql,$this->dbLink);
	    $result = array();
	    while (($row = mysql_fetch_array($res,MYSQL_NUM))!=false) {
	        $result[]=$row;
	    }
	    return $result;
	}
	/** 权限分配操作
	 * @param 被分配权限的管理员ID $adminId
	 * @param 被分配的权限 $saveParam
	 */
	public function permissionSave($adminId,$saveParam){
	    $table = "accessControl";
//先删除之前的权限
	    $delqry = "DELETE FROM accessControl WHERE adminId=$adminId";
	    mysql_query($delqry,$this->dbLink);
	    $result = true;
	    for($i=0;$i<count($saveParam);$i++){
	        try{
	            $resultInsert[$i] = mysql_query("INSERT INTO accessControl(adminId,menuId) VALUES($adminId,".$saveParam[$i].")",$this->dbLink);
	        }catch(Exception $e){
	            $resultInsert[$i] = 0;
	        }
	        $result = $result&&$resultInsert[$i];
	    }
	    return $result;
	}
	/**
	 * 获取代理数量
	 * @return Ambigous <>|boolean
	 */
	public function getAgentCount(){
	    $sql="SELECT COUNT(*) FROM agent;";
	    $res = mysql_query($sql,$this->dbLink);
	    if ($res) {
	        $row=mysql_fetch_row($res);
	        return $row[0];
	    } else return false;
	}
	/**
	 *获取代理数据
	 */
	public function getAgent($sidx,$sord,$start,$limit){
	    $sql="SELECT * FROM agent";
	    if ($sidx) {
	        $sql=$sql." ORDER BY $sidx $sord";
	    }
	    if ($start!='') {
	        $sql=$sql." LIMIT $start,$limit";
	    }
	    $res = mysql_query($sql,$this->dbLink);
	    if ($res) {
	        $ret = array();
	        while (($row=mysql_fetch_array($res,MYSQL_NUM))!=false) {
	            $ret[] = $row;
	        }
	        return $ret;
	    } else return false;
	}
	/**
	 *获取员工数据总数
	 */
	public function getCountStaff(){
	    $sql = "SELECT COUNT(*) as count FROM `staff`";
	    return $this -> getColumn($sql);
	}

	public function getoutletsCount() {
	    $sql = "SELECT COUNT(*) as count FROM `outlets`";
	    return $this -> getColumn($sql);;
	}


	/**
	 *获取员工数据
	 */
	public function getStaff($sidx,$sord,$start,$limit){
	    $sql="SELECT openId,staffName,gender,idCard,mobileNo,agentName,outletName,address,province,city,county,staffId,outletId,agentId,countyId,active
FROM v_userDetail";
	    if ($sidx) $sql=$sql." ORDER BY $sidx $sord";
	    if ($limit) $sql=$sql."  LIMIT $start,$limit";

	    return $this->fetchAllData($sql,MYSQL_NUM);
	}

	public function getCitisList($provinceId) {
	    $sql = "SELECT id,city FROM cityInfo WHERE provinceId=$provinceId";
	    return $this->fetchAllData($sql,MYSQL_ASSOC);
	}

	public function getOutletsList($agentId) {
	    $sql = "SELECT id,outletName FROM outlets WHERE agentId=$agentId";
	    return $this->fetchAllData($sql,MYSQL_ASSOC);
	}


	public function getCountyList($cityId) {
	    $sql = "SELECT id,county FROM countyInfo WHERE cityId=$cityId";
	    //echo $sql;
	    return $this->fetchAllData($sql,MYSQL_ASSOC);
	}

	public function getProvinceList(){
	    $sql = "SELECT id,province FROM provinceInfo";
	    return $this->fetchAllData($sql,MYSQL_ASSOC);
	}

	public function getAgentList(){
	    $sql = "SELECT id,agentName FROM agent";
	    return $this->fetchAllData($sql,MYSQL_ASSOC);
	}

	/** 执行员工信息编辑增删改操作
	 * @param unknown $db
	 * @param unknown $oper
	 * @param unknown $userInfo
	 */
	public function staffEdit($postArray){
	    $staffId = $_POST['id'];
	    try{
	        switch ($postArray['oper']){
	            case "add":
	                unset($postArray['outletName']);
	                unset($postArray['agentName']);
	                unset($postArray['address']);
	                unset($postArray['staffId']);
	                unset($postArray['oper']);
	                unset($postArray['id']);
	                if ($this->insertOper('staff', $postArray)) {
	                    return true;
	                } else return false;
	                break;
	            case "edit":
	                unset($postArray['outletName']);
	                unset($postArray['agentName']);
	                unset($postArray['address']);
	                unset($postArray['staffId']);
	                unset($postArray['oper']);
	                unset($postArray['id']);
	                $con=array("id"=>$staffId);
	                return $this->updateOper('staff', $postArray, $con);
	                break;
	            case "del":
	                $con=array("id"=>$staffId);
	                $dataArray = array("active"=>0);
	                return $this->updateOper('staff', $dataArray, $con);
	                break;
	        }
	    }catch(Exception $e){
	        echo "Failed:  ".$e->getMessage();
	    }
	}

	/** 执行员工信息编辑增删改操作
	 * @param unknown $db
	 * @param unknown $oper
	 * @param unknown $userInfo
	 */
	public function agentEdit($postArray){
	    $agentId = $_POST['id'];
	    try{
	        switch ($postArray['oper']){
	            case "add":
	                unset($postArray['id']);
	                unset($postArray['oper']);
	                if ($this->insertOper('agent', $postArray)) {
	                    return true;
	                } else return false;
	                break;
	            case "edit":
	                unset($postArray['oper']);
	                $con=array("id"=>$agentId);
	                return $this->updateOper('agent', $postArray, $con);
	                break;
	            case "del":
	                return $this->deleteOper('agent',array('id'=>$agentId));
	                break;
	        }
	    }catch(Exception $e){
	        echo "Failed:  ".$e->getMessage();
	    }
	}

	public function getoutlets($sidx,$sord,$start,$limit){
	    $sql="SELECT  id,outletName,provinceId,province,cityId,city,countyId,county,address,agentId,agentName FROM v_outletsDetail";
	    if ($sidx) {
	        $sql=$sql." ORDER BY $sidx $sord";
	    }
	    if ($start!='') {
	        $sql=$sql." LIMIT $start,$limit";
	    }
	    $res = mysql_query($sql,$this->dbLink);
	    if ($res) {
	        $ret = array();
	        while (($row=mysql_fetch_array($res,MYSQL_NUM))!=false) {
	            $ret[] = $row;
	        }
	        return $ret;
	    } else return false;
	}

	public function outletsEdit($postArray) {
	   $outletId = $_POST['id'];
	    try{
	        switch ($postArray['oper']){
	            case "add":
	                $postArray['agentId'] = $postArray['agentName'];
	                $postArray['countyId'] = $postArray['county'];
	                unset($postArray['province']);
	                unset($postArray['city']);
	                unset($postArray['county']);
	                unset($postArray['agentName']);
	                unset($postArray['oper']);
	                unset($postArray['id']);
	                if ($this->insertOper('outlets', $postArray)) {
	                    return true;
	                } else return false;
	                break;
	            case "edit":
	                if ($postArray['county']) {
	                    $postArray['countyId']=$postArray['county'];
	                } else unset($postArray['county']);

	                $postArray['agentId']=$postArray['agentName'];
	                unset($postArray['province']);
	                unset($postArray['city']);
	                unset($postArray['county']);
	                unset($postArray['agentName']);
	                unset($postArray['oper']);
	                unset($postArray['id']);
	                $con=array("id"=>$outletId);
	                return $this->updateOper('outlets', $postArray, $con);
	                break;
	            case "del":
	                $con=array("id"=>$outletId);
	                return $this->deleteOper('outlets',  $con);
	                break;
	        }
	    }catch(Exception $e){
	        echo "Failed:  ".$e->getMessage();
	    };
	}

	public function getnumOffCount() {
	    $sql="SELECT COUNT(*) FROM dailyCountOff;";
	    $res = mysql_query($sql,$this->dbLink);
	    if ($res) {
	        $row=mysql_fetch_row($res);
	        return $row[0];
	    } else return false;;
	}

	public function getnumOff($sidx,$sord,$start,$limit) {
	    $sql="SELECT staff.id,countOffDate,staffId,staff.`staffName`,outlets.`outletName`,agent.`agentName` FROM dailyCountOff
LEFT JOIN staff ON staff.`id`=staffId
LEFT JOIN outlets ON outlets.`id` = staff.`outletId`
LEFT JOIN agent ON agent.`id`=outlets.`agentId`";
	    if ($sidx) {
	        $sql=$sql." ORDER BY $sidx $sord";
	    }
	    if ($start!='') {
	        $sql=$sql." LIMIT $start,$limit";
	    }
	    $res = mysql_query($sql,$this->dbLink);
	    if ($res) {
	        $ret = array();
	        while (($row=mysql_fetch_array($res,MYSQL_NUM))!=false) {
	            $ret[] = $row;
	        }
	        return $ret;
	    } else return false;;
	}

	public function getValues($sql,$sidx=NULL,$sord=NULL,$start='',$limit=NULL) {
	    if ($sidx) {
	        $sql=$sql." ORDER BY $sidx $sord";
	    }
	    if ($start!='') {
	        $sql=$sql." LIMIT $start,$limit";
	    }
	    $res = mysql_query($sql,$this->dbLink);
	    if ($res) {
	        $ret = array();
	        while (($row=mysql_fetch_array($res,MYSQL_NUM))!=false) {
	            $ret[] = $row;
	        }
	        return $ret;
	    } else return false;;
	}

}
