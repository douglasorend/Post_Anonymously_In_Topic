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
if (!defined('SMF'))
	die('Hacking attempt...');

//=================================================================================
// Post Anonymously In Topic (PAIT) Hook functions
//=================================================================================
function PAIT_Init()
{
	global $smcFunc, $user_info, $modSettings, $topic;

	// Load our language strings for later:
	loadLanguage('PAIT');

	// If topic ID specified, look at who started the topic:
	if ($topic && !empty($modSettings['PAIT_TSSA']))
	{
		// Pull the topic's board number ONLY if user is topic starter in that topic:
		$result = $smcFunc['db_query']('', '
			SELECT t.id_board
			FROM {db_prefix}topics AS t
				LEFT JOIN {db_prefix}messages AS m ON (m.id_msg = t.id_first_msg)
			WHERE t.id_topic = {int:id_topic}
				AND (m.id_member = {int:id_member} OR m.anonymous = {int:id_member})
			LIMIT 1',
			array(
				'id_topic' => (int) $topic,
				'id_member' => (int) $user_info['id'],
			)
		);
		list($board) = $smcFunc['db_fetch_row']($result);
		$smcFunc['db_free_result']($result);

		// If user is topic starter AND has topic starter permission, then yes, they can see who posted anonymously:
		$see_mine = $see_all = !empty($board) && allowedTo('topic_starter_see_anonymous', $board);
	}

	// Let's find out if we are able to view ANY anonoymous posts:
	if (empty($see_mine))
		$see_mine = $modSettings['PAIT_mode'] >= 2;
	if (empty($see_all))
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
	$smcFunc['PAIT_ts'] = create_function('$tbl = false', '
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
	$permissionList['board']['topic_starter_see_anonymous'] = array(false, 'topic', 'moderate');

	// If no right to post anonymously OR see who posted anonymously, can't grant it to others:
	if (!allowedTo('post_anonymously'))
		$context['illegal_permissions'][] = 'post_anonymously';
	if (!allowedTo('see_who_posted_anonymously'))
		$context['illegal_permissions'][] = 'see_who_posted_anonymously';
	if (!allowedTo('topic_starter_see_anonymous'))
		$context['illegal_permissions'][] = 'topic_starter_see_anonymous';

	// Guests can't post things anonymously (already got it) OR see who posted things anonymously:
	$context['non_guest_permissions'][] = 'post_anonymously';
	$context['non_guest_permissions'][] = 'see_who_posted_anonymously';
	$context['non_guest_permissions'][] = 'topic_starter_see_anonymous';
}

function PAIT_Verify_User()
{
	// Skip this if we are trying to hide the user:
	if (isset($_GET['action']) && $_GET['action'] == 'pait_ajax' && isset($_GET['u']))
		return isset($_GET['u']) ? (int) $_GET['u'] : 0;
}

function PAIT_Actions(&$actions)
{
	$actions['pait_ajax'] = array('Subs-PAIT.php', 'PAIT_AJAX');
	$actions['pait_toggle'] = array('Subs-PAIT.php', 'PAIT_Toggle');
}

function PAIT_Exit($flag)
{
	global $context, $modSettings;
	if (!empty($modSettings['PAIT_Hide_User']) && (!empty($context['PAIT_hide_user']) || (isset($_GET['action']) && $_GET['action'] == 'post' && isset($_GET['anon']))))
		PAIT_AJAX(false);
}

function PAIT_Display(&$buttons)
{
	global $context, $scripturl, $board_info;
	if (allowedTo('post_anonymously') && !empty($board_info['post_anon']))
		$buttons['post_anon'] = array('test' => 'can_reply', 'text' => 'reply_anonymously', 'image' => 'reply.gif', 'lang' => true, 'url' => $scripturl . '?action=post;topic=' . $context['current_topic'] . '.' . $context['start'] . ';last_msg=' . $context['topic_last_message'] . ';anon');
}

function PAIT_Pre_BoardIndex(&$extraBoardColumns, &$extraBoardParameters)
{
	$extraBoardColumns[] = 'b.post_anon';
	$extraBoardColumns[] = 'b.child_level';
}

function PAIT_BoardTree_Board(&$row)
{
	global $boards;
	$boards[$row['id_board']]['post_anon'] = $row['post_anon'];
	$boards[$row['id_board']]['child_level'] = $row['child_level'];
}

//=================================================================================
// Post Anonymously In Topic (PAIT) support functions
//=================================================================================
function PAIT_AJAX($exit = true)
{
	global $smcFunc, $user_info, $txt;

	// Are we armed with enough information to do our "dirty" work?
	if (!allowedTo('post_anonymously') || !isset($_GET['u']))
	{
		echo $txt['pait_ajax_not_allowed'];
		exit;
	}

	// Guests use 0, members use their session ID.
	$session_id = $user_info['is_guest'] ? 'ip' . $user_info['ip'] : session_id();

	// Get the online log for this user from the database:
	$request = $smcFunc['db_query']('', '
		SELECT url
		FROM {db_prefix}log_online
		WHERE session = {string:session_id}',
		array(
			'session_id' => $session_id,
	));
	list ($url) = $smcFunc['db_fetch_row']($request);
	$smcFunc['db_free_result']($request);

	// Nothing found?  I guess we gotta tell the user that we somehow failed:
	if (empty($url))
	{
		echo $txt['pait_ajax_no_user_found'];
		exit;
	}

	// Change the "action" element to "Display" ONLY if it is "Post":
	$actions = safe_unserialize($url);
	if (isset($actions['action']) && $actions['action'] == 'post')
		$actions['action'] = 'display';

	// Update the online log in the database:
	$smcFunc['db_query']('', '
		UPDATE {db_prefix}log_online
		SET url = {string:url}
		WHERE session = {string:session_id}',
		array(
			'session_id' => $session_id,
			'url' => serialize($actions),
		)
	);

	// Exit the script, if it is requested:
	if ($exit)
	{
		echo 'OK';
		exit;
	}
}

function PAIT_Toggle($id = false, $anonymous = -1, $redirect = true)
{
	global $smcFunc, $user_info, $txt;

	// This function can't be called if the user can't post things anonymously:
	if (!allowedTo('post_anonymously'))
		fatal_lang_error('no_access', false);
	if (empty($id) && isset($_GET['anon']))
		$id = $_GET['anon'];
	$id = (int) $id;

	// Get some details about the post we're trying to work with:
	$request = $smcFunc['db_query']('', '
		SELECT
			msg.id_topic, mem.id_member, mem.member_name, mem.email_address, msg.anonymous
		FROM {db_prefix}messages AS msg
			LEFT JOIN {db_prefix}members AS mem ON (mem.id_member = IF(msg.anonymous = 0, msg.id_member, msg.anonymous))
		WHERE msg.id_msg = {int:id_msg}
			AND mem.id_member = {int:id_member}',
		array(
			'id_msg' => $id,
			'id_member' => (int) $user_info['id'],
		)
	);
	$row = $smcFunc['db_fetch_assoc']($request);
	$smcFunc['db_free_result']($request);

	// Does the post exist?  If not, abort with an error!
	if (empty($row))
		fatal_lang_error('no_access', false);
	if ($anonymous == -1)
		$anonymous = !$row['anonymous'];

	// Are we changing this post to an anonymous post?
	if (!empty($anonymous) && empty($row['anonymous']))
	{
		$smcFunc['db_query']('', '
			UPDATE {db_prefix}messages
			SET anonymous = id_member, id_member = 0, poster_name = {string:poster}, poster_email = {string:email}
			WHERE id_msg = {int:id_msg}',
			array(
				'id_msg' => $id,
				'poster' => $txt['anonymous'],
				'email' => '',
			)
		);
		$smcFunc['db_query']('', '
			UPDATE {db_prefix}topics
			SET id_member_started = IF(id_first_msg = {int:id_msg}, 0, id_member_started),
				id_member_updated = IF(id_last_msg = {int:id_msg}, 0, id_member_updated)
			WHERE id_topic = {int:id_topic}',
			array(
				'id_msg' => $id,
				'id_topic' => $row['id_topic'],
			)
		);
		updateMemberData($row['id_member'], array('posts' => '+', 'anon_posts' => '-'));
	}

	// Or are we changing this post from an anonymous post?
	elseif (empty($anonymous) && !empty($row['anonymous']))
	{
		$smcFunc['db_query']('', '
			UPDATE {db_prefix}messages
			SET id_member = anonymous, anonymous = 0, poster_name = {string:poster}, poster_email = {string:email}
			WHERE id_msg = {int:id_msg}',
			array(
				'id_msg' => $id,
				'poster' => $row['member_name'],
				'email' => $row['email_address'],
			)
		);
		$smcFunc['db_query']('', '
			UPDATE {db_prefix}topics
			SET id_member_started = IF(id_first_msg = {int:id_msg}, {int:anonymous}, id_member_started),
				id_member_updated = IF(id_first_msg = {int:id_msg}, {int:anonymous}, id_member_updated)
			WHERE id_topic = {int:id_topic}',
			array(
				'id_msg' => $id,
				'anonymous' => $row['anonymous'],
				'id_topic' => $row['id_topic'],
			)
		);
		updateMemberData($row['id_member'], array('posts' => '-', 'anon_posts' => '+'));
	}

	if ($redirect)
		redirectExit('topic=' . $row['id_topic'] . '.msg' . $id . '#msg' . $id);
}

//=================================================================================
// Hooks related to PAIT settings:
//=================================================================================
function PAIT_Admin(&$areas)
{
	global $txt;
	$areas['config']['areas']['modsettings']['subsections']['pait'] = array($txt['PAIT_Title']);
}

function PAIT_Area(&$areas)
{
	$areas['pait'] = 'PAIT_Config';
}

function PAIT_Config($return_config = false)
{
	global $smcFunc, $sourcedir, $txt, $scripturl, $context, $boards;

	// Set a few things up before doing the settings:
	isAllowedTo('admin_forum');
	loadLanguage('ManageMembers');
	require_once($sourcedir . '/Subs-Boards.php');
	getBoardTree();

	// The settings for this mod:
	$settings = array(
		array('callback', 'PAIT_boards'),
		array('check', 'PAIT_Hide_User'),
		'',
		array('select', 'PAIT_mode', array(
			0 => $txt['PAIT_mode0'], 	// 0: No One.  Also disables recording Member ID.
			1 => $txt['PAIT_mode1'], 	// 1: No One.
			2 => $txt['PAIT_mode2'], 	// 2: Only poster can see their anonymous post
			3 => $txt['PAIT_mode3']		// 3: Everyone with "See Who Posted Anonymously" permission.
		)),
		array('permissions', 'see_who_posted_anonymously', 0, $txt['PAIT_SWPA_MG']),
		'',
		array('check', 'PAIT_TSSA'),
		array('permissions', 'topic_starter_see_anonymous', 0, $txt['PAIT_TSSA_MG']),
	);
	if ($return_config)
		return $settings;

	// Saving these settings?
	if (isset($_GET['save']))
	{
		// Make sure we got valid stuff to work with:
		checkSession();
		if (empty($_POST['boardaccess']))
			$_POST['boardaccess'] = array(0);
		elseif (!is_array($_POST['boardaccess']))
			$_POST['boardaccess'] = array($_POST['boardaccess']);
		$non_anon = array_diff(array_keys($boards), $anon = $_POST['boardaccess']);

		// Mark the boards as anonymous or not:
		$smcFunc['db_query']('', '
			UPDATE {db_prefix}boards
			SET post_anon = 0
			WHERE id_board IN ({array_int:boards})',
			array(
				'boards' => $non_anon,
			)
		);
		$smcFunc['db_query']('', '
			UPDATE {db_prefix}boards
			SET post_anon = 1
			WHERE id_board IN ({array_int:boards})',
			array(
				'boards' => $anon,
			)
		);

		// Save the rest of the settings:
		saveDBSettings($settings);
		redirectexit('action=admin;area=modsettings;sa=pait');
	}

	// Get ready to display this settings page to the user:
	$context['post_url'] = $scripturl . '?action=admin;area=modsettings;save;sa=pait';
	$context['settings_title'] = $txt['PAIT_Settings'];
	prepareDBSettingContext($settings);
}

function template_callback_PAIT_boards()
{
	global $txt, $boards;

	if (!empty($boards))
	{
		echo '
						<dt>
							', $txt['PAIT_Boards'], ':
						</dt>
						<dd>
							<fieldset id="visible_boards" style="width: 95%;">
								<legend><a href="javascript:void(0);" onclick="document.getElementById(\'visible_boards\').style.display = \'none\';document.getElementById(\'visible_boards_link\').style.display = \'block\'; return false;">', $txt['PAIT_Enabled'], '</a></legend>';
		foreach ($boards as $board)
			echo '
								<div style="margin-left: ', $board['child_level'], 'em;"><input type="checkbox" name="boardaccess[]" id="boardaccess_', $board['id'], '" value="', $board['id'], '" ', $board['post_anon'] ? ' checked="checked"' : '', ' class="input_check" /> <label for="boardaccess_', $board['id'], '">', $board['name'], '</label></div>';

		echo '
								<br />
								<input type="checkbox" id="checkall_check" class="input_check" onclick="invertAll(this, this.form, \'boardaccess\');" /> <label for="checkall_check"><em>', $txt['check_all'], '</em></label>
							</fieldset>
							<a href="javascript:void(0);" onclick="document.getElementById(\'visible_boards\').style.display = \'block\'; document.getElementById(\'visible_boards_link\').style.display = \'none\'; return false;" id="visible_boards_link" style="display: none;">[ ', $txt['membergroups_select_visible_boards'], ' ]</a>
							<script type="text/javascript"><!-- // --><![CDATA[
								document.getElementById("visible_boards_link").style.display = "";
								document.getElementById("visible_boards").style.display = "none";
							// ]]></script>
						</dd>';
	}
}

?>