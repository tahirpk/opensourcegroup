<?php if (!defined('VB_ENTRY')) die('Access denied.');
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

/**
 * Test Widget Item
 *
 * @package vBulletin
 * @author Edwin Brown, vBulletin Development Team
 * @version $Revision: 37230 $
 * @since $Date: 2010-05-28 11:50:59 -0700 (Fri, 28 May 2010) $
 * @copyright vBulletin Solutions Inc.
 */
class vBCms_Item_Widget_ExecPhp extends vBCms_Item_Widget
{
	/*Properties====================================================================*/

	/**
	 * A package identifier.
	 *
	 * @var string
	 */
	protected $package = 'vBCms';

	/**
	 * A class identifier.
	 *
	 * @var string
	 */
	protected $class = 'ExecPhp';

	/** The default configuration **/
	protected $config = array(
		'phpcode'       => "\$output = date(vB::\$vbulletin->options['dateformat']) . \"<br />\\n\";",
		'template_name' => 'vbcms_widget_execphp_page',
		'cache_ttl' => 5
	);

}

/*======================================================================*\
|| ####################################################################
|| # Downloaded: 08:44, Wed Dec 19th 2012
|| # SVN: $Revision: 37230 $
|| ####################################################################
\*======================================================================*/