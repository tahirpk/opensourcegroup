<?php
/*======================================================================*\
|| #################################################################### ||
|| # vBulletin 4.2.0 Patch Level 3 - Licence Number VBS433AA8E
|| # ---------------------------------------------------------------- # ||
|| # Copyright �2000-2012 vBulletin Solutions Inc. All Rights Reserved. ||
|| # This file may not be redistributed in whole or significant part. # ||
|| # ---------------- VBULLETIN IS NOT FREE SOFTWARE ---------------- # ||
|| # http://www.vbulletin.com | http://www.vbulletin.com/license.html # ||
|| #################################################################### ||
\*======================================================================*/
if (!VB_API) die;

loadCommonWhiteList();

$VB_API_WHITELIST = array(
	'response' => array(
		'HTML' => array(
			'emailselected', 'folderbits',
			'foruminfo' => $VB_API_WHITELIST_COMMON['foruminfo'],
			'id',
			'threadinfo' => $VB_API_WHITELIST_COMMON['threadinfo'],
			'type'
		)
	),
	'show' => array(
		'folders', 'subscribetothread'
	)
);

/*======================================================================*\
|| ####################################################################
|| # Downloaded: 08:44, Wed Dec 19th 2012
|| # CVS: $RCSfile$ - $Revision: 35584 $
|| ####################################################################
\*======================================================================*/