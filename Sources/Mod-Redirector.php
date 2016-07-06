<?php
/**
 * Project: SMF Redirector
 * Version: 1.0
 * Author: digger http://mysmf.ru
 * License: CC BY-NC-ND http://creativecommons.org/licenses/by-nc-nd/4.0/
 *
 * To run this install manually please make sure you place this
 * in the same place and SSI.php and index.php
 */

if (!defined('SMF')) {
    die('Hacking attempt...');
}

/**
 * Load all needed hooks
 */
function loadRedirectorHooks()
{
    global $modSettings;

    add_integration_function('integrate_admin_areas', 'addRedirectorAdminArea', false);
    add_integration_function('integrate_modify_modifications', 'addRedirectorAdminAction', false);
    add_integration_function('integrate_menu_buttons', 'addRedirectorCopyright', false);

    if (empty($modSettings['redirector_enabled'])) {
        return;
    }

    add_integration_function('integrate_actions', 'addRedirectorAction', false);
    add_integration_function('integrate_menu_buttons', 'addRedirectorForUsers', false);
    add_integration_function('integrate_bbc_codes', 'changeRedirectorUrlTag', false);
}


/**
 * Add mod admin area
 * @param $admin_areas
 */
function addRedirectorAdminArea(&$admin_areas)
{
    global $txt;
    loadLanguage('Redirector/');

    $admin_areas['config']['areas']['modsettings']['subsections']['redirector'] = array($txt['redirector_admin_menu']);
}


/**
 * Add mod admin action
 * @param $subActions
 */
function addRedirectorAdminAction(&$subActions)
{
    $subActions['redirector'] = 'addRedirectorAdminSettings';
}


/**
 * Add action for redirect page
 * @param array $actionArray
 */
function addRedirectorAction(&$actionArray = array())
{
    $actionArray['go'] = array('Mod-Redirector.php', 'showRedirectorPage');
}


/**
 * Add mod settings area
 * @param bool $return_config
 * @return array
 */
function addRedirectorAdminSettings($return_config = false)
{
    global $txt, $scripturl, $context;
    loadLanguage('Redirector/');

    $context['page_title'] = $context['settings_title'] = $txt['redirector_admin_menu'];
    $context['post_url'] = $scripturl . '?action=admin;area=modsettings;save;sa=redirector';

    $config_vars = array(
        array('check', 'redirector_enabled'),
        array('check', 'redirector_guest_only'),
        //array('check', 'redirector_check_referer'),
        /*
        array('select', 'redirector_mode',
            array(
                'immediate' => $txt['redirector_mode_immediate'],
                'delayed' => $txt['redirector_mode_delayed'],
            ),
        ),
        array('int', 'redirector_delay'),
        */
        array('large_text', 'redirector_whitelist', 'subtext' => $txt['redirector_whitelist_sub']),
    );

    if ($return_config) {
        return $config_vars;
    }

    if (isset($_GET['save'])) {
        checkSession();
        $save_vars = $config_vars;
        saveDBSettings($save_vars);
        redirectexit('action=admin;area=modsettings;sa=redirector');
    }

    prepareDBSettingContext($config_vars);
}


/**
 * Add redirection to member's website urls, messengers, etc...
 * @return bool
 */
function addRedirectorForUsers()
{
    global $modSettings, $user_profile;

    if (empty($modSettings['redirector_enabled']) || empty($user_profile)) {
        return false;
    }

    foreach (array_keys($user_profile) as $user_id) {
        if (!empty($user_profile[$user_id]['website_url'])) {
            $user_profile[$user_id]['website_url'] = getRedirectorUrl($user_profile[$user_id]['website_url']);
        }
        //$user_profile[$user_id]['icq']['href'] = getRedirectorUrl($user_profile[$user_id]['icq']['href']);
        //$user_profile[$user_id]['yum']['href'] = getRedirectorUrl($user_profile[$user_id]['yum']['href']);
        //$user_profile[$user_id]['msn']['href'] = getRedirectorUrl($user_profile[$user_id]['msn']['href']);
    }
}


/**
 * Get redirected url
 * @param string $url original url
 * @return string redirected url
 */
function getRedirectorUrl($url = '')
{
    global $scripturl, $modSettings, $context;

    $redirector_whitelist_adds = "\n" . 'link.tapatalk.com';

    if (!empty($modSettings['redirector_guest_only']) && empty($context['user']['is_guest'])) {
        return $url;
    }

    $whitelist = array_map('trim', explode("\n", $modSettings['redirector_whitelist'] . $redirector_whitelist_adds));

    $host = parse_url($url, PHP_URL_HOST);
    if (!empty($host) && is_array($whitelist) && in_array($host, $whitelist)) {
        return $url;
    }

    return $scripturl . '?action=go;url=' . (base64_encode($url));
}


/**
 * Add mod copyright to the forum credits page
 */
function addRedirectorCopyright()
{
    global $context;

    if ($context['current_action'] == 'credits') {
        $context['copyrights']['mods'][] = '<a href="http://mysmf.ru/mods/redirector" target="_blank">Redirector</a> &copy; 2015-2016, digger';
    }
}


/**
 * Show redirect page
 */
function showRedirectorPage()
{
    global $modSettings, $scripturl, $context, $txt, $boardurl;

    $link = ($_GET['url']);
    $link = (base64_decode($link));

    if (!empty($modSettings['redirector_check_referer'])) {
        header('Location: ' . $boardurl);
        exit;
    } elseif ($modSettings['redirector_mode'] == 'immediate') {
        header('Location: ' . $link);
        exit;
    } // if it is in settings - use automatic redirection after delay
    elseif ($modSettings['redirector_mode'] == 'delayed') {
        header('Refresh: ' . $modSettings['redirector_delay'] . '; url=' . $link);
        exit;
    }
}


/**
 * Change default url and iurl tags
 * @param array $codes default BB-codes array
 */
function changeRedirectorUrlTag(&$codes = array())
{
    foreach ($codes as $codeId => $code) {
        if ($code['tag'] == 'url' && $code['type'] == 'unparsed_content') {
            $codes[$codeId]['validate'] = create_function('&$tag, &$data, $disabled', '
					$data = strtr($data, array(\'<br />\' => \'\'));
					$link = $data;
					
					if (strpos($data, \'http://\') !== 0 && strpos($data, \'https://\') !== 0)				
						$data = \'http://\' . $data;
						$data = getRedirectorUrl($data);
						$tag[\'content\'] = \'<a href="\' . $data . \'" class="bbc_link" target="_blank">\' . $link . \'</a>\';						
				');
        } elseif ($code['tag'] == 'url' && $code['type'] == 'unparsed_equals') {
            $codes[$codeId]['validate'] = create_function('&$tag, &$data, $disabled', '
					if (strpos($data, \'http://\') !== 0 && strpos($data, \'https://\') !== 0)
						$data = \'http://\' . $data;
						
						$href = getRedirectorUrl($data);
						$tag[\'before\'] = \'<a href="\' . $href . \'" class="bbc_link" target="_blank">\';
						$tag[\'after\'] = \'</a>\';
				');
        } elseif ($code['tag'] == 'iurl' && $code['type'] == 'unparsed_content') {
            $codes[$codeId]['validate'] = create_function('&$tag, &$data, $disabled', '
					$data = strtr($data, array(\'<br />\' => \'\'));
					$link = $data;
					
					if (strpos($data, \'http://\') !== 0 && strpos($data, \'https://\') !== 0)				
						$data = \'http://\' . $data;
						$data = getRedirectorUrl($data);
						$tag[\'content\'] = \'<a href="\' . $data . \'" class="bbc_link" target="_blank">\' . $link . \'</a>\';						
				');
        } elseif ($code['tag'] == 'iurl' && $code['type'] == 'unparsed_equals') {
            $codes[$codeId]['validate'] = create_function('&$tag, &$data, $disabled', '
					if (strpos($data, \'http://\') !== 0 && strpos($data, \'https://\') !== 0)
						$data = \'http://\' . $data;
						
						$href = getRedirectorUrl($data);
						$tag[\'before\'] = \'<a href="\' . $href . \'" class="bbc_link" target="_blank">\';
						$tag[\'after\'] = \'</a>\';
				');
        }
    }
}
