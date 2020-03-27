<?php
/**
 * @package SMF Redirector
 * @file Mod-Redirector.php
 * @author digger <digger@mysmf.ru> <http://mysmf.ru>
 * @copyright Copyright (c) 2015-2017, digger
 * @license The MIT License (MIT) https://opensource.org/licenses/MIT
 * @version 1.2
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

    // Admin area
    add_integration_function('integrate_admin_include', '$sourcedir/Admin-Redirector.php', false);
    add_integration_function('integrate_admin_areas', 'addRedirectorAdminArea', false);
    add_integration_function('integrate_modify_modifications', 'addRedirectorAdminAction', false);

    if (empty($modSettings['redirector_enabled']) && empty($modSettings['redirector_hide_guest_links']) && empty($modSettings['redirector_nofollow_links'])) {
        return;
    }

    add_integration_function('integrate_actions', 'addRedirectorAction', false);
    add_integration_function('integrate_menu_buttons', 'addRedirectorForUsers', false);
    add_integration_function('integrate_bbc_codes', 'changeRedirectorUrlTag', false);
    add_integration_function('integrate_menu_buttons', 'addRedirectorCopyright', false);
    add_integration_function('integrate_buffer', 'fixRedirectorUnparsedEquals', false);
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
 * Add redirection to member's website urls, messengers, etc...
 */
function addRedirectorForUsers()
{
    global $modSettings, $user_profile;

    if (empty($modSettings['redirector_enabled']) || empty($user_profile)) {
        return;
    }

    foreach (array_keys($user_profile) as $user_id) {
        if (!empty($user_profile[$user_id]['website_url'])) {
            $user_profile[$user_id]['website_url'] = getRedirectorUrl($user_profile[$user_id]['website_url']);
        }
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

    if (!empty($modSettings['redirector_guest_only']) && empty($context['user']['is_guest'])) {
        return $url;
    }

    if (checkWhiteList($url)) {
        return $url;
    }

    if (!empty($modSettings['redirector_hide_guest_links']) && !empty($context['user']['is_guest'])) {
        return $scripturl . '?action=login';
    }

    if (!empty($modSettings['redirector_enabled'])) {
        return $scripturl . '?action=go;url=' . (base64_encode($url));
    } else {
        return $url;
    }

}

/**
 * Check url for whitelist
 * @param string $url
 * @return bool true if whitelisted, false if no
 */
function checkWhiteList($url = '')
{
    global $modSettings;

    $whitelist = array_map('trim', explode("\n", $modSettings['redirector_whitelist']));
    $host = parse_url($url, PHP_URL_HOST);

    if (!empty($host) && is_array($whitelist) && in_array($host, $whitelist)) {
        return true;
    }

    return false;
}

/**
 * Add mod copyright to the forum credits page
 */
function addRedirectorCopyright()
{
    global $context;

    if ($context['current_action'] == 'credits') {
        $context['copyrights']['mods'][] = '<a href="https://mysmf.net/mods/redirector" target="_blank">Redirector</a> &copy; 2015-2019, digger';
    }
}

/**
 * Show redirect page
 */
function showRedirectorPage()
{
    global $modSettings, $sourcedir, $context, $txt;
    loadLanguage('Redirector/Redirector');

    $link = ($_GET['url']);
    $link = str_replace('&amp;', '&', base64_decode($link));

    if ($modSettings['redirector_mode'] == 'immediate') {
        header('Location: ' . $link);
        exit;
    } // if it is in settings - use automatic redirection after delay
    elseif ($modSettings['redirector_mode'] == 'delayed') {
        require_once ($sourcedir . '/Subs-Post.php');
        header('Refresh: ' . $modSettings['redirector_delay'] . '; url=' . $link);
        exit;
/*
        $context['page_title'] = $txt['redirector_page_title'];

        $context['linktree'][] = array(
            'url' => $_SERVER['PHP_SELF'],
            'name' => $txt['redirector_page_title'],
        );
*/
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
            $codes[$codeId]['validate'] = create_function('&$tag, &$data',
                'changeUrlUnparsedContentCode($tag, $data);');
        } elseif ($code['tag'] == 'url' && $code['type'] == 'unparsed_equals') {
            $codes[$codeId]['validate'] = create_function('&$tag, &$data',
                'changeUrlUnparsedEqualsCode($tag, $data);');
        } elseif ($code['tag'] == 'iurl' && $code['type'] == 'unparsed_content') {
            $codes[$codeId]['validate'] = create_function('&$tag, &$data',
                'changeUrlUnparsedContentCode($tag, $data);');
        } elseif ($code['tag'] == 'iurl' && $code['type'] == 'unparsed_equals') {
            $codes[$codeId]['validate'] = create_function('&$tag, &$data',
                'changeUrlUnparsedEqualsCode($tag, $data);');
        }
    }
}

/**
 * Parse unparsed content tag
 * @param $tag
 * @param $data
 */
function changeUrlUnparsedContentCode(&$tag, &$data)
{
    global $txt, $modSettings, $context;
    loadLanguage('Redirector/Redirector');

    $data = strtr($data, array('<br />' => ''));
    $link_text = $data;

    // Skip local urls with #
    if (strpos($data, '#') === 0) {
        return;
    } else {
        if (strpos($data, 'http://') !== 0 && strpos($data, 'https://') !== 0) {
            $data = 'http://' . $data;
        }
    }

    // Hide links from guests
    if (!empty($modSettings['redirector_hide_guest_links']) && !empty($context['user']['is_guest']) && !checkWhiteList($data)) {
        $link_text = !empty($modSettings['redirector_hide_guest_custom_message']) ? $modSettings['redirector_hide_guest_custom_message'] : $txt['redirector_hide_guest_message'];
    }

    $data = getRedirectorUrl($data);

    $tag['content'] = '<a href="' . $data . '" class="bbc_link" ' . ((!empty($modSettings['redirector_nofollow_links']) && !checkWhiteList($data)) ? 'rel="nofollow noopener" ' : '') . ($tag['tag'] == 'url' ? 'target="_blank"' : '') . ' >' . $link_text . '</a>';
}

/**
 * Parse unparsed equals tag
 * @param $tag
 * @param $data
 */
function changeUrlUnparsedEqualsCode(&$tag, &$data)
{
    global $txt, $modSettings, $context;
    loadLanguage('Redirector/Redirector');

    // Anchor tag
    if (substr($data, 0, 1) == '#') {
        $data = '#post_' . substr($data, 1);
        return;
    } else {
        if (strpos($data, 'http://') !== 0 && strpos($data, 'https://') !== 0) {
            $data = 'http://' . $data;
        }
    }

    $href = getRedirectorUrl($data);

    $tag['before'] = '<a href="' . $href . '" class="bbc_link" ' . ((!empty($modSettings['redirector_nofollow_links']) && !checkWhiteList($data)) ? 'rel="nofollow noopener" ' : '') . ($tag['tag'] == 'url' ? 'target="_blank"' : '') . ' >';
    $tag['after'] = '</a>';

    // Hide links from guests
    if (!empty($modSettings['redirector_hide_guest_links']) && !empty($context['user']['is_guest']) && !checkWhiteList($data)) {
        $tag['before'] = $tag['before'] . (!empty($modSettings['redirector_hide_guest_custom_message']) ? $modSettings['redirector_hide_guest_custom_message'] : $txt['redirector_hide_guest_message']) . '[url-disabled]';
        $tag['after'] = '[/url-disabled]' . $tag['after'];
    }
}

/**
 * Fix link in unparsed equals tag
 * @param $buffer
 * @return mixed
 */
function fixRedirectorUnparsedEquals($buffer)
{
    return preg_replace('#\[url-disabled].*\[/url-disabled]#U', '', $buffer);
}
