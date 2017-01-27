<?php
namespace Page;

/**
 * Class RegisterSubscribePage
 *
 * @package Register Subscribe Plugin
 * @copyright (C) 2016-2017 Boldt Webservice <forum@boldt-webservice.de>
 * @support https://www.boldt-webservice.de/en/forum-en/bwpostman.html
 * @license GNU/GPL, see LICENSE.txt
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @since   2.0.0
 */
class User2SubscriberPage
{
	/*
	 * Declare UI map for this page here. CSS or XPath allowed.
	 */

	// Frontend stuff
	// used urls and links
    public static $register_url         = '/index.php?option=com_users&view=registration';
	public static $user_activation_url  = '/index.php?option=com_users&task=registration.activate&token=';

	//view identifier
	public static $view_register        = ".//*[@id='member-registration']/fieldset[1]/legend";

	// login field identifiers
	public static $login_identifier_name            = ".//*[@id='jform_name']";
	public static $login_identifier_username        = ".//*[@id='jform_username']";
	public static $login_identifier_password1       = ".//*[@id='jform_password1']";
	public static $login_identifier_password2       = ".//*[@id='jform_password2']";
	public static $login_identifier_email1          = ".//*[@id='jform_email1']";
	public static $login_identifier_email2          = ".//*[@id='jform_email2']";
	public static $login_identifier_register        = ".//*[@id='member-registration']/div/div/button";

	// login field values
	public static $login_value_name         = "Sam Sample";
	public static $login_value_username     = "Sam";
	public static $login_value_password     = "!08Sam15";
	public static $login_value_email        = "dummy-1@bwtest.nil";

	public static $change_value_email       = "dummy-2@bwtest.nil";

	// subscriber field identifiers
	public static $subs_identifier_subscribe_no     = ".//*[@id='jform_bwpm_user2subscriber_bwpm_user2subscriber']/label[1]";
	public static $subs_identifier_subscribe_yes    = ".//*[@id='jform_bwpm_user2subscriber_bwpm_user2subscriber']/label[2]";
	public static $subs_identifier_female           = ".//*[@id='jform_bwpm_user2subscriber_gender']/label[1]";
	public static $subs_identifier_male             = ".//*[@id='jform_bwpm_user2subscriber_gender']/label[2]";
	public static $subs_identifier_name             = ".//*[@id='jform_bwpm_user2subscriber_name']";
	public static $subs_identifier_firstname        = ".//*[@id='jform_bwpm_user2subscriber_firstname']";
	public static $subs_identifier_special          = ".//*[@id='jform_bwpm_user2subscriber_special']";
	public static $subs_identifier_format_text      = ".//*[@id='jform_bwpm_user2subscriber_emailformat']/label[1]";
	public static $subs_identifier_format_html      = ".//*[@id='jform_bwpm_user2subscriber_emailformat']/label[2]";

	// subscriber field values
	public static $subs_value_name         = "Sample";
	public static $subs_value_firstname    = "Sam";
	public static $subs_value_special      = "0815";

	// success message identifiers
	public static $success_heading_identifier   = ".//*[@id='system-message']/div/h4";
	public static $success_message_identifier   = "div.alert-success > div > div.alert-message";
	public static $activation_completed_text    = "Your Account has been successfully activated. You can now log in using the username and password you chose during the registration.";
	public static $activation_complete          = ".//*[@id='system-message']/div/div/div";

	// error message identifiers
	public static $error_message_name               = "Invalid field:  Last Name";
	public static $error_message_firstname          = "Invalid field:  First Name";
	public static $error_message_special            = "Invalid field:  %s";

	// backend stuff
	public static $user_management_url              = 'administrator/index.php?option=com_users&view=users';
	public static $subscribers_url                  = "/administrator/index.php?option=com_bwpostman&view=subscribers";
	public static $plugin_page                      = "/administrator/index.php?option=com_plugins";

	// com_users related
	public static $user_edit_identifier             = ".//*[@id='userList']/tbody/*/td[2]/div[1]/a";
	public static $toolbar_apply_button             = ".//*[@id='toolbar-apply']/button";
	public static $toolbar_save_button              = ".//*[@id='toolbar-save']/button";
	public static $toolbar_delete_button            = ".//*[@id='toolbar-delete']/button";

	// com_bwpostman related
	public static $tab_confirmed                    = ".//*[@id='bwpostman_subscribers_tabs']/dt[2]";
	public static $tab_unconfirmed                  = ".//*[@id='bwpostman_subscribers_tabs']/dt[3]";
	public static $subscriber_email_col_identifier  = ".//*[@id='j-main-container']/div[2]/div/dd[%s]/table/tbody/*/td[5]";
	public static $subscriber_format_col_identifier = ".//*[@id='j-main-container']/div[2]/div/dd[%s]/table/tbody/*/td[6]";
	public static $subscriber_filter_col_identifier = ".//*[@id='j-main-container']/div[2]/div/dd[%s]/table/tbody/*/td[2]";
	public static $subscriber_edit_link             = ".//*[@id='j-main-container']/div[2]/div/dd[%s]/table/tbody/*/td[2]/a";

	public static $subslist_identifier_name         = ".//*[@id='j-main-container']/div[2]/div/dd[%s]/table/tbody/*/td[2]";
	public static $subslist_identifier_firstname    = ".//*[@id='j-main-container']/div[2]/div/dd[%s]/table/tbody/*/td[3]";
	public static $subslist_identifier_gender       = ".//*[@id='j-main-container']/div[2]/div/dd[%s]/table/tbody/*/td[4]";
	public static $subslist_identifier_special      = ".//*[@id='jform_special']";

	// mailinglist check
	public static $mailinglist1_checked             = ".//*[@id='jform_ml_available_1']";
	public static $mailinglist2_checked             = ".//*[@id='jform_ml_available_4']";
	public static $mailinglist_fieldset_identifier  = "//*[@id='adminForm']/div[1]/div[1]/fieldset/div[1]/div/fieldset/legend/span[2]";
	public static $subscriber_details_close         = ".//*[@id='toolbar-cancel']/button";

	// search subscriber
	public static $search_tool_button               = ".//*[@id='j-main-container']/div[1]/div[1]/div[1]/div[2]/button";
	public static $search_for_list                  = ".//*[@id='filter_search_filter_chzn']";
	public static $search_for_value                 = ".//*[@id='filter_search_filter_chzn']/div/ul/li[contains(text(), 'Name')]";

	// check for selected options
	public static $user_id_identifier               = ".//*[@id='j-main-container']/div[2]/div/dd[1]/table/tbody/tr/td[7]";
	public static $email_identifier                 = ".//*[@id='userList']/tbody/tr[1]/td[7]";
	public static $mail_field_identifier            = ".//*[@id='jform_email']";

	// com_plugin related
	public static $view_plugin                      = "Plugins";
	public static $plugin_name                      = "BwPostman Plugin User2Subscriber";
	public static $icon_published_identifier        = ".//*[@id='pluginList']/tbody/tr/td[3]/a/span";
	public static $plugin_edit_identifier           = ".//*[@id='pluginList']/tbody/tr/td[4]/a";

	// plugin edit tab options
	public static $plugin_tab_options               = ".//*[@id='myTabTabs']/li[2]/a";
	public static $plugin_message_identifier        = ".//*[@id='jform_params_register_message_option']";
	public static $plugin_show_format_yes           = ".//*[@id='jform_params_show_format_selection_option']/label[2]";
	public static $plugin_show_format_no            = ".//*[@id='jform_params_show_format_selection_option']/label[1]";
	public static $plugin_format_html               = ".//*[@id='jform_params_predefined_mailformat_option']/label[2]";
	public static $plugin_format_text               = ".//*[@id='jform_params_predefined_mailformat_option']/label[1]";
	public static $plugin_auto_update_yes           = ".//*[@id='jform_params_auto_update_email_option']/label[2]";
	public static $plugin_auto_update_no            = ".//*[@id='jform_params_auto_update_email_option']/label[1]";
	public static $plugin_auto_delete_yes           = ".//*[@id='jform_params_register_subscribe_option']/label[2]";
	public static $plugin_auto_delete_no            = ".//*[@id='jform_params_register_subscribe_option']/label[1]";

	public static $plugin_message_old               = 'Test text for newsletter message text';
	public static $plugin_message_new               = 'New Newsletter message text';

	// plugin edit tab mailinglists
	public static $plugin_tab_mailinglists          = ".//*[@id='myTabTabs']/li[3]/a";
	public static $plugin_checkbox_mailinglist      = ".//*[@id='mb%s']";

	//messages
	public static $delete_confirm           = "Are you sure you want to delete? Confirming will permanently delete the selected item(s)!";
	public static $delete_success           = "1 user successfully deleted.";
	public static $register_success         = "Your account has been created and an activation link has been sent";

	public static $plugin_disabled_success  = 'Plugin successfully disabled';
	public static $plugin_enabled_success   = 'Plugin successfully enabled';
	public static $plugin_saved_success     = 'Plugin successfully saved';
}
