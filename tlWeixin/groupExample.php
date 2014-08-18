<?php
include_once 'conf/weixin.php';
include_once 'classes/Group.class.php';

$accessToke = ACCESS_TOKEN;
$group = new Group($accessToke);

// $resp = $group->addOneGroup('自定义');
// echo $resp."<br />";

$openId='oMIYit4vcSrbGnpKGWJVD8QqvFlA';
$groupId = $group->getGroupIdByOpenId($openId);
echo $groupId."<br />";

$resp = $group->modifyGroupName(103, 'no组');
echo $resp."<br />";

$resp = $group->moveUserToGroup($openId, 0);
echo $resp."<br />";

// $resp = $group->deleteGroup(103);
// echo $resp."<br />";

$existGroups = $group->getExistGroups();
var_dump($existGroups);