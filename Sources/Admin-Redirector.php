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

    $admin_areas['config']['areas']['modsettings']['subsections']['redirector'] = [$txt['redirector_admin_menu']];
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
    global $txt, $scripturl, $context, $modSettings, $smcFunc;
    loadLanguage('Redirector/Redirector');

    $context['page_title']       = $txt['redirector_admin_menu'];
    $context['post_url']         = $scripturl . '?action=admin;area=modsettings;save;sa=redirector';
    $context['settings_message'] = str_replace(
        '{ACTION}',
        (!empty($modSettings['redirector_action_name']) ? $smcFunc['htmlspecialchars'](
            trim($modSettings['redirector_action_name'])
        ) : 'go'),
        $txt['redirector_description']
    );

    $config_vars = [
        ['title', 'redirector_admin_menu'],
        ['check', 'redirector_enabled'],
        ['check', 'redirector_guest_only'],
        [
            'select',
            'redirector_mode',
            [
                'immediate' => $txt['redirector_mode_immediate'],
                'delayed'   => $txt['redirector_mode_delayed'],
            ],
        ],
        ['int', 'redirector_delay'],
        ['large_text', 'redirector_whitelist', 'subtext' => $txt['redirector_whitelist_sub']],

        ['title', 'redirector_protection_title'],
        ['check', 'redirector_check_referrer', 'subtext' => $txt['redirector_check_sub']],
        ['check', 'redirector_check_session', 'subtext' => $txt['redirector_check_sub']],
        ['text', 'redirector_action_name', 'subtext' => $txt['redirector_action_name_sub']],

        ['title', 'redirector_page_settings_title'],
        ['large_text', 'redirector_page_members_text', 'subtext' => $txt['redirector_page_text_sub']],
        ['large_text', 'redirector_page_guests_text', 'subtext' => $txt['redirector_page_text_sub']],

        ['title', 'redirector_hide_links_title'],
        ['check', 'redirector_hide_guest_links'],
        ['large_text', 'redirector_hide_guest_custom_message'],

        ['title', 'redirector_extra_title'],
        ['check', 'redirector_nofollow_links', 'subtext' => $txt['redirector_nofollow_links_sub']],
    ];

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
