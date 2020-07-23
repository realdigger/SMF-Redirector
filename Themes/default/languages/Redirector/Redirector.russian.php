<?php
/**
 * @package SMF Redirector
 * @file Redirector.russian.php
 * @author digger <digger@mysmf.ru> <http://mysmf.ru>
 * @copyright Copyright (c) 2015-2017, digger
 * @license The MIT License (MIT) https://opensource.org/licenses/MIT
 * @version 1.2
 */

$txt['redirector_description'] = 'Редирект и скрытие внешних ссылок в сообщениях на форуме. Рекомендуется, добавить в файл robots.txt строку<br>
Disallow: /*action=go;';
$txt['redirector_admin_menu'] = 'Перенаправление ссылок';
$txt['redirector_enabled'] = 'Включить перенаправление';
$txt['redirector_guest_only'] = 'Перенаправлять только для гостей';
$txt['redirector_check_referer'] = 'Перенаправлять на главную для чужих и пустых реферов';
$txt['redirector_mode'] = 'Режим перенаправления';
$txt['redirector_mode_immediate'] = 'Немедленно';
$txt['redirector_mode_delayed'] = 'C задержкой';
$txt['redirector_delay'] = 'Задержка в секундах';
$txt['redirector_whitelist'] = 'Белый список доменов';
$txt['redirector_whitelist_sub'] = 'По одному домену в строке';
$txt['redirector_page_title'] = 'Переход по внешней ссылке';
$txt['redirector_hide_links_title'] = 'Скрытие ссылок';
$txt['redirector_hide_guest_links'] = 'Скрывать ссылки (кроме белого списка) от гостей';
$txt['redirector_hide_guest_message'] = '[Войдите или зарегистрируйтесь]';
$txt['redirector_hide_guest_custom_message'] = 'Сообщение отображаемое вместо скрытой от гостя ссылки';
$txt['whoall_go'] = 'Переходит по внешней ссылке';
$txt['redirector_nofollow_links'] = 'Добавить к ссылкам rel="nofollow noopener noreferrer"';
$txt['redirector_page_text'] = '<div class="information"><p>Вы переходите по внешней ссылке не имеющей отношения к форуму. Переход произойдет через {TIME} сек.</p><p>{LINK}</p></div>';
$txt['redirector_page_settings_title'] = 'Страница перенаправления';
$txt['redirector_page_guests_text'] = 'Шаблон текста для гостей';
$txt['redirector_page_members_text'] = 'Шаблон текста для пользователей';
$txt['redirector_page_text_sub'] = 'Можно использовать HTML тэги, {LINK} для вывода ссылки и {TIME} для отображения секунд';
