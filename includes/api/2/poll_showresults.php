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
if (!VB_API) die;

$VB_API_WHITELIST = array(
	'response' => array(
		'pollresults' => array(
			'pollbits' => array(
				'*' => array(
					'names' => array(
						'*' => array(
							'loggedin' => array(
								'userid', 'username', 'invisiblemark', 'buddymark'
							)
						)
					),
					'option' => array('question', 'votes', 'percentraw'),
					'show' => array('pollvoters')
				)
			),
			'pollinfo' => array(
				'question', 'timeout', 'posttime', 'public', 'closed'
			),
			'pollendtime', 'pollstatus'
		),
		'threadinfo' => $VB_API_WHITELIST_COMMON['threadinfo'],
	),
	'show' => array(
		'pollvoters', 'multiple', 'editpoll', 'pollenddate'
	)
);

function api_result_prerender_2($t, &$r)
{
	switch ($t)
	{
		case 'pollresults_table':
			$r['pollinfo']['posttime'] = $r['pollinfo']['dateline'];
			$r['pollendtime'] = $r['pollinfo']['dateline'] + ($r['pollinfo']['timeout'] * 86400);
			break;
	}
}

vB_APICallback::instance()->add('result_prerender', 'api_result_prerender_2', 2);

/*======================================================================*\
|| ####################################################################
|| # Downloaded: 08:44, Wed Dec 19th 2012
|| # CVS: $RCSfile$ - $Revision: 35584 $
|| ####################################################################
\*======================================================================*/