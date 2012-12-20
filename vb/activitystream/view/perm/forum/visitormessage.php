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

class vB_ActivityStream_View_Perm_Forum_VisitorMessage extends vB_ActivityStream_View_Perm_Base
{
	public function __construct(&$content)
	{
		return parent::__construct($content);
	}

	public function group($activity)
	{
		if (!$this->fetchCanViewMembers())
		{
			return false;
		}

		if (!$this->content['visitormessage'][$activity['contentid']])
		{
			$this->content['vmid'][$activity['contentid']] = 1;
		}
	}

	public function process()
	{
		if (!$this->content['vmid'])
		{
			return true;
		}

		$messages = vB::$db->query_read_slave("
			SELECT
				vm.userid, vm.postuserid, vm.dateline, vm.state, vm.title, vm.pagetext, vm.vmid, vm.postusername
			FROM " . TABLE_PREFIX . "visitormessage AS vm
			WHERE
				vm.vmid IN (" . implode(",", array_keys($this->content['vmid'])) . ")
					AND
				vm.state <> 'deleted'
		");
		while ($message = vB::$db->fetch_array($messages))
		{
			$this->content['visitormessage'][$message['vmid']] = $message;
			$this->content['userid'][$message['postuserid']] = 1;
			$this->content['userid'][$message['userid']] = 1;
		}

		$this->content['vmid'] = array();
	}

	public function fetchCanView($record)
	{
		$this->processUsers();
		return $this->fetchCanViewVisitorMessage($record['contentid']);
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
		$messageinfo =& $this->content['visitormessage'][$activity['contentid']];
		$activity['postdate'] = vbdate(vB::$vbulletin->options['dateformat'], $activity['dateline'], true);
		$activity['posttime'] = vbdate(vB::$vbulletin->options['timeformat'], $activity['dateline']);

		$messageinfo['preview'] = strip_quotes($messageinfo['pagetext']);
		$messageinfo['preview'] = htmlspecialchars_uni(fetch_censored_text(
			fetch_trimmed_title(strip_bbcode($messageinfo['preview'], false, true, true, true),
				vb::$vbulletin->options['as_snippet'])
		));
		$userinfo = $this->fetchUser($activity['userid'], $messageinfo['postusername']);

		$templater = vB_Template::create($templatename);
			$templater->register('userinfo', $userinfo);
			$templater->register('userinfo2', $this->content['user'][$messageinfo['userid']]);
			$templater->register('linkinfo', array('vmid' => $messageinfo['vmid']));
			$templater->register('linkinfo2', array('tab' => 'visitor_messaging'));
			$templater->register('activity', $activity);
			$templater->register('messageinfo', $messageinfo);
		return $templater->render();
	}
}

/*======================================================================*\
|| ####################################################################
|| # Downloaded: 08:44, Wed Dec 19th 2012
|| # CVS: $RCSfile$ - $Revision: 57655 $
|| ####################################################################
\*======================================================================*/