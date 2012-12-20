<?php

/* ======================================================================*\
  || #################################################################### ||
  || # vBulletin 4.2.0 Patch Level 3 - Licence Number VBS433AA8E
  || # ---------------------------------------------------------------- # ||
  || # Copyright �2000-2012 vBulletin Solutions Inc. All Rights Reserved. ||
  || # This file may not be redistributed in whole or significant part. # ||
  || # ---------------- VBULLETIN IS NOT FREE SOFTWARE ---------------- # ||
  || # http://www.vbulletin.com | http://www.vbulletin.com/license.html # ||
  || #################################################################### ||
  \*====================================================================== */

class vB_ActivityStream_View_Perm_Socialgroup_Group extends vB_ActivityStream_View_Perm_Socialgroup_Base
{
	public function __construct(&$content)
	{
		$this->requireFirst['vB_ActivityStream_View_Perm_Socialgroup_Groupmessage'] = 1;
		$this->requireFirst['vB_ActivityStream_View_Perm_Socialgroup_Discussion'] = 1;
		$this->requireFirst['vB_ActivityStream_View_Perm_Socialgroup_Photo'] = 1;
		$this->requireFirst['vB_ActivityStream_View_Perm_Socialgroup_Photocomment'] = 1;
		return parent::__construct($content);
	}

	public function group($activity)
	{
		if (!$this->fetchCanUseGroups())
		{
			return;
		}

		if (!$this->content['socialgroup'][$activity['contentid']])
		{
			$this->content['groupid'][$activity['contentid']] = 1;
		}
	}

	public function process()
	{
		if (!$this->content['groupid'])
		{
			return true;
		}

		$groups = vB::$db->query_read_slave("
			SELECT sg.options, sg.groupid, sg.name, sg.creatoruserid, sg.creatoruserid AS userid, sg.dateline, sg.type
				" . (vB::$vbulletin->userinfo['userid'] ? ", sgm.type AS membertype" : "") . "
			FROM " . TABLE_PREFIX . "socialgroup AS sg
			" . (vB::$vbulletin->userinfo['userid'] ? "LEFT JOIN " . TABLE_PREFIX . "socialgroupmember AS sgm ON (sgm.userid = " . vB::$vbulletin->userinfo['userid'] . " AND sgm.groupid = sg.groupid)" : "") . "
			WHERE sg.groupid IN (" . implode(",", array_keys($this->content['groupid'])) . ")
		");
		while ($group = vB::$db->fetch_array($groups))
		{
			$group['is_owner'] = ($group['creatoruserid'] == vB::$vbulletin->userinfo['userid']);
			$this->content['socialgroup'][$group['groupid']] = $group;
			$this->content['userid'][$group['creatoruserid']] = 1;
		}

		$this->content['groupid'] = array();
	}

	public function fetchCanView($group)
	{
		$this->processUsers();
		return $this->fetchCanUseGroups();
	}

	/*
	 * Register Template
	 *
	 * @param	string	Template Name
	 * @param	array	Activity Record
	 *
	 * @return	string	Template
	 */
	public function fetchTemplate($templatename, $activity)
	{
		$groupinfo =& $this->content['socialgroup'][$activity['contentid']];

		$activity['postdate'] = vbdate(vB::$vbulletin->options['dateformat'], $activity['dateline'], true);
		$activity['posttime'] = vbdate(vB::$vbulletin->options['timeformat'], $activity['dateline']);

		$templater = vB_Template::create($templatename);
			$templater->register('userinfo', $this->content['user'][$activity['userid']]);
			$templater->register('activity', $activity);
			$templater->register('groupinfo', $groupinfo);
		return $templater->render();
	}
}

/*======================================================================*\
|| ####################################################################
|| # Downloaded: 08:44, Wed Dec 19th 2012
|| # CVS: $RCSfile$ - $Revision: 57655 $
|| ####################################################################
\*======================================================================*/