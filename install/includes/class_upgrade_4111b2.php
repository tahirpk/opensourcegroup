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
/*
if (!isset($GLOBALS['vbulletin']->db))
{
	exit;
}
*/

class vB_Upgrade_4111b2 extends vB_Upgrade_Version
{
	/*Constants=====================================================================*/

	/*Properties====================================================================*/

	/**
	* The short version of the script
	*
	* @var	string
	*/
	public $SHORT_VERSION = '4111b2';

	/**
	* The long version of the script
	*
	* @var	string
	*/
	public $LONG_VERSION  = '4.1.11 Beta 2';

	/**
	* Versions that can upgrade to this script
	*
	* @var	string
	*/
	public $PREV_VERSION = '4.1.11 Beta 1';

	/**
	* Beginning version compatibility
	*
	* @var	string
	*/
	public $VERSION_COMPAT_STARTS = '';

	/**
	* Ending version compatibility
	*
	* @var	string
	*/
	public $VERSION_COMPAT_ENDS   = '';
	
	/**
	* Step #1
	*
	*/
	function step_1()
	{
		$this->run_query(
			$this->phrase['version']['380a2']['updating_usergroup_permissions'],
			"UPDATE " . TABLE_PREFIX . "usergroup SET
				forumpermissions = forumpermissions | " . $this->registry->bf_ugp_forumpermissions['canattachmentcss'] . "
			 WHERE usergroupid IN (5,6)"
		);
	}	
}

/*======================================================================*\
|| ####################################################################
|| # Downloaded: 08:44, Wed Dec 19th 2012
|| # CVS: $RCSfile$ - $Revision: 35750 $
|| ####################################################################
\*======================================================================*/