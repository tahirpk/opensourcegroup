<?php
/*======================================================================*\
|| #################################################################### ||
|| # vBulletin 4.2.0 Patch Level 3 - Licence Number VBS433AA8E
|| # ---------------------------------------------------------------- # ||
|| # Copyright ©2000-2012 vBulletin Solutions Inc. All Rights Reserved. ||
|| # This file may not be redistributed in whole or significant part. # ||
|| # ---------------- VBULLETIN IS NOT FREE SOFTWARE ---------------- # ||
|| # http://www.vbulletin.com | http://www.vbulletin.com/license.html # ||
|| #################################################################### ||
\*======================================================================*/

function parse_video_bbcode($pagetext)
{
	global $vbulletin;

	($hook = vBulletinHook::fetch_hook('data_parse_bbcode_video')) ? eval($hook) : false;

	if (stripos($pagetext, '[video]') !== false)
	{
		require_once(DIR . '/includes/class_bbcode_alt.php');
		$parser = new vB_BbCodeParser_Video_PreParse($vbulletin, array());
		$pagetext = $parser->parse($pagetext);
	}

	return $pagetext;
}

/*======================================================================*\
|| ####################################################################
|| # Downloaded: 08:44, Wed Dec 19th 2012
|| # CVS: $RCSfile$ - $Revision: 27207 $
|| ####################################################################
\*======================================================================*/
?>
