<?php
/**********************************************************************************
* Subs-PAIT.php                                                                   *
***********************************************************************************
* This mod is licensed under the 2-clause BSD License, which can be found here:   *
*	http://opensource.org/licenses/BSD-2-Clause                                   *
***********************************************************************************
* This program is distributed in the hope that it is and will be useful, but	  *
* WITHOUT ANY WARRANTIES; without even any implied warranty of MERCHANTABILITY	  *
* or FITNESS FOR A PARTICULAR PURPOSE.											  *
**********************************************************************************/

function PAIT_Load()
{
	global $smcFunc, $user_info, $modSettings;

	// Decide how to construct our replacement query parts:
	loadLanguage('PAIT');
	$see_mine = $modSettings['PAIT_mode'] >= 2;
	$see_all = $modSettings['PAIT_mode'] == 3 && allowedTo('see_who_posted_anonymously');

	// Precompile our MySQL decisions into functions in $smcFunc:
	$smcFunc['PAIT_id'] = create_function('$tbl = false', '
		$tbl = !empty($tbl) ? $tbl . (!strpos($tbl, ".") ? "." : "") : "";
		return "' . (empty($user_info['id']) ? '" . $tbl . "id_member' : ($see_mine ? 'IF(" . $tbl . "anonymous ' . ($see_all ? '> 0' : '= ' . ((int) $user_info['id'])) . ', " . $tbl . "anonymous, " . $tbl . "id_member)' : '" . $tbl . "id_member')) . '";
	');
	$smcFunc['PAIT_an'] = create_function('$tbl = false', '
		$tbl = !empty($tbl) ? $tbl . (!strpos($tbl, ".") ? "." : "") : "";
		return "' . (empty($user_info['id']) ? '0' : ($see_mine ? 'IF(" . $tbl . "anonymous ' . ($see_all ? '> 0' : '= ' . ((int) $user_info['id'])) . ', 1, 0)' : '0')) . '";
	');
}

function PAIT_Permissions(&$permissionGroups, &$permissionList, &$leftPermissionGroups, &$hiddenPermissions, &$relabelPermissions)
{
	global $context;

	// Add our new permissions here:
	$permissionList['membergroup']['post_anonymously'] = array(false, 'general', 'view_basic_info');
	$permissionList['membergroup']['see_who_posted_anonymously'] = array(false, 'general', 'view_basic_info');

	// If no right to post anonymously OR see who posted anonymously, can't grant it to others:
	if (!allowedTo('post_anonymously'))
		$context['illegal_permissions'][] = 'post_anonymously';
	if (!allowedTo('see_who_posted_anonymously'))
		$context['illegal_permissions'][] = 'see_who_posted_anonymously';

	// Guests can't post things anonymously (already got it) OR see who posted things anonymously:
	$context['non_guest_permissions'][] = 'post_anonymously';
	$context['non_guest_permissions'][] = 'see_who_posted_anonymously';
}

function PAIT_Settings(&$settings)
{
	global $txt;
	$settings[] = array('select', 'PAIT_mode', array($txt['PAIT_mode0'], $txt['PAIT_mode1'], $txt['PAIT_mode2'], $txt['PAIT_mode3']));
}

?>