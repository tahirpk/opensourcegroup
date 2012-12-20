<?php
/*======================================================================*\
|| #################################################################### ||
|| # vBulletin Blog 4.2.0 Patch Level 3 - Licence Number VBS433AA8E
|| # ---------------------------------------------------------------- # ||
|| # Copyright �2000-2012 vBulletin Solutions Inc. All Rights Reserved. ||
|| # This file may not be redistributed in whole or significant part. # ||
|| # ---------------- VBULLETIN IS NOT FREE SOFTWARE ---------------- # ||
|| # http://www.vbulletin.com | http://www.vbulletin.com/license.html # ||
|| #################################################################### ||
\*======================================================================*/

// ######################## SET PHP ENVIRONMENT ###########################
error_reporting(E_ALL & ~E_NOTICE);
if (!is_object($vbulletin->db))
{
	exit;
}

$nodes = $vbulletin->db->query_read("
	SELECT nodeid
	FROM " . TABLE_PREFIX . "cms_node
	WHERE 
		new = 1
			AND
		lastupdated < " . (TIMENOW - 3600). "
");
while ($node = $vbulletin->db->fetch_array($nodes))
{
	$nodeitem = new vBCms_Item_Content($node['nodeid']);
	$class  = vB_Types::instance()->getContentClassFromId($nodeitem->getContentTypeID());
	$classname = $class['package']. '_Item_Content_' . $class['class'];

	if (class_exists($classname))
	{
		$nodeclass = new $classname($node['nodeid']);
	}
	else
	{
		$nodeclass = new vBCms_Item_Content($node['nodeid']);
	}

	$nodedm = $nodeclass->getDM();
	$nodedm->delete();	
}

log_cron_action('', $nextitem, 1);

/*======================================================================*\
|| ####################################################################
|| # Downloaded: 08:44, Wed Dec 19th 2012
|| # CVS: $Revision: 25612 $
|| ####################################################################
\*======================================================================*/
