<?php
/**
 * @package SMF Redirector
 * @file Redirector.russian-utf8.php
 * @author digger <digger@mysmf.ru> <http://mysmf.ru>
 * @copyright Copyright (c) 2015-2017, digger
 * @license The MIT License (MIT) https://opensource.org/licenses/MIT
 * @version 1.2
 */

$txt['redirector_description']               = 'Редирект и скрытие внешних ссылок в сообщениях на форуме. Рекомендуется, добавить в файл robots.txt строку<br>
Disallow: /*action={ACTION};';
$txt['redirector_admin_menu']                = 'Перенаправление ссылок';
$txt['redirector_enabled']                   = 'Включить перенаправление';
$txt['redirector_guest_only']                = 'Перенаправлять только для гостей';
$txt['redirector_check_referer']             = 'Перенаправлять на главную для чужих и пустых реферов';
$txt['redirector_mode']                      = 'Режим перенаправления';
$txt['redirector_mode_immediate']            = 'Немедленно';
$txt['redirector_mode_delayed']              = 'Через страницу с задержкой';
$txt['redirector_delay']                     = 'Задержка в секундах';
$txt['redirector_whitelist']                 = 'Белый список доменов, на которые не накладывается редирект';
$txt['redirector_whitelist_sub']             = 'По одному домену в строке. Для правильной работы модов автовставки нужно вписать домены сайтов с которых вставляются ссылки (например, www.youtube.com)';
$txt['redirector_page_title']                = 'Переход по внешней ссылке';
$txt['redirector_hide_links_title']          = 'Скрытие ссылок';
$txt['redirector_hide_guest_links']          = 'Скрывать ссылки (кроме белого списка) от гостей';
$txt['redirector_hide_guest_message']        = '[Войдите или зарегистрируйтесь]';
$txt['redirector_hide_guest_custom_message'] = 'Сообщение отображаемое вместо скрытой от гостя ссылки';
$txt['whoall_go']                            = 'Переходит по внешней ссылке';
$txt['redirector_nofollow_links']            = 'Добавить к ссылкам rel="nofollow noopener noreferrer"';
$txt['redirector_page_text']                 = '<div class="information"><p>Вы переходите по внешней ссылке не имеющей отношения к форуму. Переход произойдет через {TIME} сек.</p><p>{LINK}</p></div>';
$txt['redirector_page_settings_title']       = 'Страница перенаправления';
$txt['redirector_page_guests_text']          = 'Шаблон текста для гостей';
$txt['redirector_page_members_text']         = 'Шаблон текста для пользователей';
$txt['redirector_page_text_sub']             = 'Можно использовать HTML тэги, {LINK} для вывода ссылки и {TIME} для отображения секунд.';
$txt['redirector_nofollow_links_sub']        = 'Если не включено перенаправление и ссылка не из белого списка.';
$txt['redirector_extra_title']               = 'Дополнительно';
$txt['redirector_check_referrer']            = 'Проверять соответствие HTTP_REFERER домену форума';
$txt['redirector_check_sub']                 = 'Сделает невозможным использование сторонними сайтами редиректа вашего форума в своих целях. Но, может сделать нерабочими ссылки через редирект на вашем форуме в некоторых случаях. Например, для Tapatalk или SSI.';
$txt['redirector_check_session']             = 'Использовать ключ в сессии для дополнительной защиты ссылок';
$txt['redirector_protection_title']          = 'Защита ссылок';
$txt['redirector_action_name']               = 'Задать свое имя для ссылки перенаправления';
$txt['redirector_action_name_sub']           = 'Заменит go в ссылках index.php?action=<strong>go</strong>;url=...<br> Сделает нерабочими ссылки через ваш редирект, уже размещенные на сторонних сайтах.';
