<?php
/**
 * @package SMF Redirector
 * @file Redirector.english.php
 * @author digger <digger@mysmf.ru> <http://mysmf.ru>
 * @copyright Copyright (c) 2015-2017, digger
 * @license The MIT License (MIT) https://opensource.org/licenses/MIT
 * @version 1.2
 */

$txt['redirector_description']               = 'Redirect and hiding for external links. You should add to your robots.txt file<br> 
Disallow: /*action=go;';
$txt['redirector_admin_menu']                = 'Links Redirector';
$txt['redirector_enabled']                   = 'Enable redirect';
$txt['redirector_guest_only']                = 'For guests only';
$txt['redirector_check_referer']             = 'Redirect to main page for empty or foreign referrals';
$txt['redirector_mode']                      = 'Redirect mode';
$txt['redirector_mode_immediate']            = 'instant';
$txt['redirector_mode_delayed']              = 'delayed';
$txt['redirector_delay']                     = 'Delay for seconds';
$txt['redirector_whitelist']                 = 'Domains white list';
$txt['redirector_whitelist_sub']             = 'One domain on every row';
$txt['redirector_page_title']                = 'Redirect to external link';
$txt['redirector_hide_links_title']          = 'Hide links';
$txt['redirector_hide_guest_links']          = 'Hide links (except white list) for guests';
$txt['redirector_hide_guest_message']        = '[Login or Register]';
$txt['redirector_hide_guest_custom_message'] = 'Message to show when link is hided';
$txt['whoall_go']                            = 'Go to external link';
$txt['redirector_nofollow_links']            = 'Add rel="nofollow noopener noreferrer" for links';
$txt['redirector_page_text']                 = '<div class="information"><p>You go to the external link. You will be redirected in {TIME} sec.</p><p>{LINK}</p></div>';
$txt['redirector_page_settings_title']       = 'Redirect page';
$txt['redirector_page_guests_text']          = 'Template for guests';
$txt['redirector_page_members_text']         = 'Template for members';
$txt['redirector_page_text_sub']             = 'You can use HTML tags, {link} for link url and {TIME} for seconds';
$txt['redirector_nofollow_links_sub']        = 'If redirect not enabled and link not in whitelist.';
$txt['redirector_extra_title']               = 'Addititonal';
$txt['redirector_check_referrer']            = 'Check HTTP_REFERER for forum domain';
$txt['redirector_check_sub']                 = 'Will prevent use your forum as redirect gateway for other sites. May conflict with Tapatalk and SSI.';
$txt['redirector_check_session']             = 'Use session key foe extra links security';
$txt['redirector_protection_title']          = 'Links security';
$txt['redirector_action_name']               = 'Set own link action name';
$txt['redirector_action_name_sub']           = 'Wil be used in index.php?action=<strong>go</strong>;url=... links.';
