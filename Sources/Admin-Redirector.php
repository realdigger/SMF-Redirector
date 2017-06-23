<?php
/**
 * @package SMF Redirector
 * @file Admin-Redirector.php
 * @author digger <digger@mysmf.ru> <http://mysmf.ru>
 * @copyright Copyright (c) 2015-2017, digger
 * @license The MIT License (MIT) https://opensource.org/licenses/MIT
 * @version 1.2
 */

/**
 * Add mod admin area
 * @param $admin_areas
 */
function addRedirectorAdminArea(&$admin_areas)
{
    global $txt;
    loadLanguage('Redirector/Redirector');

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
 * Add mod settings area
 * @param bool $return_config
 * @return array
 */
function addRedirectorAdminSettings($return_config = false)
{
    global $txt, $scripturl, $context;
    loadLanguage('Redirector/Redirector');

    $context['page_title'] = $txt['redirector_admin_menu'];
    $context['post_url'] = $scripturl . '?action=admin;area=modsettings;save;sa=redirector';
    $context['settings_message'] = $txt['redirector_description'];

    $config_vars = array(
        array('title', 'redirector_admin_menu'),
        array('check', 'redirector_enabled'),
        array('check', 'redirector_guest_only'),

        array(
            'select',
            'redirector_mode',
            array(
                'immediate' => $txt['redirector_mode_immediate'],
                'delayed' => $txt['redirector_mode_delayed'],
            ),
        ),
        array('int', 'redirector_delay'),
        array('large_text', 'redirector_whitelist', 'subtext' => $txt['redirector_whitelist_sub']),
        array('title', 'redirector_hide_links_title'),
        array('check', 'redirector_hide_guest_links'),
        array('large_text', 'redirector_hide_guest_custom_message'),
    );

    if ($return_config) {
        return $config_vars;
    }

    if (isset($_GET['save'])) {
        checkSession();
        saveDBSettings($config_vars);
        redirectexit('action=admin;area=modsettings;sa=redirector');
    }

    prepareDBSettingContext($config_vars);
}
