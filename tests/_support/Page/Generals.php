<?php
namespace Page;

/**
 * Class Generals
 *
 * Class to hold generally needed properties and methods
 *
 * @package Page
 * @copyright (C) 2018 Boldt Webservice <forum@boldt-webservice.de>
 * @support https://www.boldt-webservice.de/en/forum-en/forum/bwpostman.html
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
class Generals
{
	// include url of current page
	/**
	 * @var string
	 *
	 * @since 2.0.0
	 */
	public static $url          = '/administrator/index.php?option=com_bwpostman&view=bwpostman';

	/**
	 * @var string
	 *
	 * @since 2.0.0
	 */
	public static $archive_url  = '/administrator/index.php?option=com_bwpostman&view=archive&layout=newsletters';

	/**
	 * @var string
	 *
	 * @since 2.0.0
	 */
	public static $control_panel        = "Control Panel";

	/**
	 * @var string
	 *
	 * @since 2.0.0
	 */
	public static $login_txt            = "Log in";

	/**
	 * @var string
	 *
	 * @since 2.0.0
	 */
	public static $nav_user_menu        = "//*[@title='User Menu']";

	/**
	 * @var string
	 *
	 * @since 2.0.0
	 */
	public static $nav_user_menu_logout = "//*[@class='dropdown-item']/a[normalize-space() = 'Log out']";

	/**
	 * @var string
	 *
	 * @since 2.0.0
	 */
	public static $logout_txt           = "Log out";

	/**
	 * @var string
	 *
	 * @since 2.0.0
	 */
	public static $com_options;

	/**
	 * @var string
	 *
	 * @since 2.0.0
	 */
	public static $sys_message_container    = "//*[@id='system-message-container']";

	/**
	 * @var string
	 *
	 * @since 2.4.0
	 */
	public static $joomlaMenuCollapse    = "//*/a[@id='menu-collapse']";

	/**
	 * @var string
	 *
	 * @since 2.4.0
	 */
	public static $nlTabBar    = "//*[@class='tab-wrapper-bwp']";

	/**
	 * @var string
	 *
	 * @since 2.0.0
	 */
	public static $media_frame              = "Change Image";

	/**
	 * @var string
	 *
	 * @since 2.0.0
	 */
	public static $image_frame              = "imageframe";

	/**
	 * @var string
	 *
	 * @since 2.0.0
	 */
	public static $back_button  = "//*[@id='toolbar-back']/button";

	/**
	 * @var object  $tester AcceptanceTester
	 *
	 * @since   2.0.0
	 */
	protected $tester;

	// backend users
	/**
	 * @var array
	 *
	 * @since 2.0.0
	 */
	public static $admin        = array('user' => 'AdminTester', 'password' => 'BwPostmanTest', 'author' => 'AdminTester');

	/**
	 * @var string
	 *
	 * @since 2.0.0
	 */
	public static $extension            = "BwPostman";

	/**
	 * @var string
	 *
	 * @since 2.0.0
	 */
	public static $plugin_u2s           = "BwPostman Plugin User2Subscriber";

	/**
	 * @var string
	 *
	 * @since 2.0.0
	 */
	public static $first_list_entry     = "//*[@id='cb0']";

	/*
	 * Declare UI map for this page here. CSS or XPath allowed.
	 * public static $usernameField = '#username';
	 * public static $formSubmitButton = "#mainForm input[type=submit]";
	 */

	/**
	 * @var string
	 *
	 * @since 2.0.0
	 */
	public static $pageTitle        = 'h1.page-title';

	/**
	 * @var string
	 *
	 * @since 2.0.0
	 */
	public static $alert_header     = '//*/joomla-alert/h4';

	/**
	 * @var string
	 *
	 * @since 2.0.0
	 */
	public static $alert_heading    = "//*[@id='system-message-container']/div[1]/joomla-alert/h4";

	/**
	 * @var string
	 *
	 * @since 2.4.0
	 */
	public static $alert_heading4    = "//*[@id='system-message-container']/div[1]/joomla-alert/div[@class='alert-heading']";

	/**
	 * @var string
	 *
	 * @since 2.0.0
	 */
	public static $alert            = 'div.alert';

	/**
	 * @var string
	 *
	 * @since 2.0.0
	 */
	public static $alert_success    = '//*[@id="system-message"]/joomla-alert[@type="success"]/div/div';

	/**
	 * @var string
	 *
	 * @since 2.4.0
	 */
	public static $alert_success4    = '//*[@id="system-message"]/joomla-alert[@type="success"]/div/p';

	/**
	 * @var string
	 *
	 * @since 2.0.0
	 */
	public static $alert_msg        = '//*[@id="system-message"]/joomla-alert[@type="message"]';

	/**
	 * @var string
	 *
	 * @since 2.2.0
	 */
	public static $alert_info        = '//*[@id="system-message"]/joomla-alert[@type="info"]/div/div';

	/**
	 * @var string
	 *
	 * @since 2.0.0
	 */
	public static $alert_warn       = '//*[@id="system-message"]/joomla-alert[@type="warning"]/div/div';

	/**
	 * @var string
	 *
	 * @since 2.0.0
	 */
	public static $alert_nothing       = 'div.alert-';

	/**
	 * @var string
	 *
	 * @since 2.0.0
	 */
	public static $alert_error      = '//*[@id="system-message"]/joomla-alert[@type="danger"]/div/div';

	/**
	 * @var string
	 *
	 * @since 2.0.0
	 */
	public static $systemMessageClose      = "//*[@id='system-message']/joomla-alert/button";

	/**
	 * @var string
	 *
	 * @since 2.0.0
	 */
	public static $alert_success_txt    = 'Success';

	/**
	 * @var string
	 *
	 * @since 2.0.0
	 */
	public static $alert_msg_txt        = 'Message';

	/**
	 * @var string
	 *
	 * @since 2.4.0
	 */
	public static $alert_info_txt        = 'Info';

	/**
	 * @var string
	 *
	 * @since 2.0.0
	 */
	public static $alert_warn_txt       = 'Warning';

	/**
	 * @var string
	 *
	 * @since 2.0.0
	 */
	public static $alert_error_txt      = 'Error';

	/**
	 * @var string
	 *
	 * @since 2.0.0
	 */
	public static $archive_alert_success = 'div.alert-success > div.alert-message';

	/**
	 * @var string
	 *
	 * @since 2.0.0
	 */
	public static $archive_txt           = 'Archive';

	/**
	 * @var string
	 *
	 * @since 2.0.0
	 */
	public static $header           = '/html/body/div[2]/section/div/div/div[2]/form/div/fieldset/legend';

	/**
	 * @var string
	 *
	 * @since 2.0.0
	 */
	public static $check_all_button = "//*[@name='checkall-toggle']";

	/**
	 * @var    string
	 *
	 * @since  2.4.0
	 */
	public static $packageInstallerTab = 'a#tab-package';

	/**
	 * @var    string
	 *
	 * @since  2.4.0
	 */
	public static $packageInstallerText = 'Upload & Install Joomla Extension';

	/**
	 * @var    string
	 *
	 * @since  2.4.0
	 */
	public static $webInstallerText = 'Categories';

	/**
	 * Version to test
	 *
	 * @var    string
	 *
	 * @since  2.0.0
	 */
	public static $versionToTest = '2.0.0';

	/**
	 * Version to test
	 *
	 * @var    array
	 *
	 * @since  2.1.0
	 */
	public static $downloadFolder = array(
		'root' => '/root/Downloads/',
		'jenkins' => '/home/jenkins/Downloads/',
		);

	/**
	 * database prefix
	 *
	 * @var    string
	 *
	 * @since  2.0.0
	 */
	public static $db_prefix = 'jos_';

	/**
	 * Array of user groups
	 *
	 * @var    array
	 *
	 * @since  2.0.0
	 */
	public static $usergroups = array ('undefined', 'Public', 'Registered', 'Special', 'Guest', 'Super Users');

	/**
	 * Array of states
	 *
	 * @var    array
	 *
	 * @since  2.0.0
	 */
	public static $states = array ('unpublish', 'publish');

	/**
	 * Array of sorting order values
	 *
	 * @var    array
	 *
	 * @since  2.0.0
	 */
	public static $sort_orders = array ('ascending', 'descending');

	/**
	 * Array of list limit values
	 *
	 * @var    array
	 *
	 * @since  2.0.0
	 */
	public static $list_limits = array (5, 10, 20);

	/**
	 * Array of submenu xpath values for all pages
	 *
	 * @var    array
	 *
	 * @since  2.0.0
	 */
	public static $submenu = array (
		'BwPostman'     => "//*[@id='submenu']/li/a[contains(text(), 'BwPostman')]",
		'Newsletters'   => "//*[@id='submenu']/li/a[contains(text(), 'Newsletters')]",
		'Subscribers'   => "//*[@id='submenu']/li/a[contains(text(), 'Subscribers')]",
		'Campaigns'     => "//*[@id='submenu']/li/a[contains(text(), 'Campaigns')]",
		'Mailinglists'  => "//*[@id='submenu']/li/a[contains(text(), 'Mailinglists')]",
		'Templates'     => "//*[@id='submenu']/li/a[contains(text(), 'Templates')]",
		'Archive'       => "//*[@id='submenu']/li/a[contains(text(), 'Archive')]",
		'Maintenance'   => "//*[@id='submenu']/li/a[contains(text(), 'Maintenance')]",
	);

	/**
	 * @var string
	 *
	 * @since 2.0.0
	 */
	public static $submenu_toggle_button  = "//*[@id='j-toggle-sidebar-icon']";

	/**
	 * Array of toolbar id values for list page
	 *
	 * @var    array
	 *
	 * @since  2.0.0
	 */
	public static $toolbar = array (
		'New'                  => "//*[@id='toolbar-new']/button",
		'Edit'                 => "//*[@id='toolbar-edit']/button",
		'Publish'              => "//*[@id='toolbar-publish']/button",
		'Unpublish'            => "//*[@id='toolbar-unpublish']/button",
		'Archive'              => "//*[@id='toolbar-archive']/button",
		'Help'                 => "//*[@id='toolbar-help']/button",
		'Duplicate'            => "//*[@id='toolbar-copy']/button",
		'Send'                 => "//*[@id='toolbar-envelope']/button",
		'Add HTML-Template'    => "//*[@id='toolbar-calendar']/button",
		'Add Text-Template'    => "//*[@id='toolbar-new']/button",
		'Default'              => "//*[@id='toolbar-default']/button",
		'Check-In'             => "//*[@id='toolbar-checkin']/button",
		'Install-Template'     => "//*[@id='toolbar-custom']/a",
		'Options'              => "//*[@id='toolbar-options']/button",
		'Save'                 => "//*[@id='toolbar-apply']/button",
		'Save & Close'         => "//*[@id='toolbar-save']/button",
		'Save & New'           => "//*[@id='toolbar-save-new']/button",
		'Save as Copy'         => "//*[@id='toolbar-save-copy']/button",
		'Cancel'               => "//*[@id='toolbar-cancel']/button",
		'Back'                 => "//*[@id='toolbar-back']/button",
		'Delete'               => "//*[@id='toolbar-delete']/button",
		'Restore'              => "//*[@id='toolbar-unarchive']/button",
		'Enable'               => "//*[@id='toolbar-publish']/button",
		'Import'               => "//*[@id='toolbar-download']/button",
		'Export'               => "//*[@id='toolbar-upload']/button",
		'Export Popup'         => "//*[@id='toolbar-popup-upload']/button",
		'Batch'                => "//*[@id='toolbar-batch']/button",
		'Reset sending trials' => "//*[@id='toolbar-unpublish']/button",
		'Continue sending'     => "//*[@id='toolbar-popup-envelope']/button",
		'Clear queue'          => "//*[@id='toolbar-delete']/button",
		'Uninstall  '          => "//*[@id='toolbar-delete']/button",
		'BwPostman Manual'     => "//*[@id='toolbar-manual']/button",
		'BwPostman Forum'      => "//*[@id='toolbar-forum']/button",
	);

	/**
	 * Array of toolbar id values for list page
	 *
	 * @var    array
	 *
	 * @since  2.0.0
	 */
	public static $toolbar4 = array (
		'New'                  => "a#toolbar-new",
		'Edit'                 => "a#toolbar-edit",
		'Publish'              => "a#toolbar-publish",
		'Unpublish'            => "a#toolbar-unpublish",
		'Archive'              => "a#toolbar-archive",
		'Help'                 => "a#toolbar-help",
		'Duplicate'            => "a#toolbar-copy",
		'Send'                 => "a#toolbar-envelope",
		'Add HTML-Template'    => "a#toolbar-calendar",
		'Add Text-Template'    => "a#toolbar-new",
		'Default'              => "a#toolbar-default",
		'Check-In'             => "a#toolbar-checkin",
		'Install-Template'     => "a#toolbar-install-template",
		'Options'              => "a#toolbar-options",
		'Save'                 => "a#toolbar-apply",
		'Save & Close'         => "a#toolbar-save",
		'Save & New'           => "a#toolbar-save-new",
		'Save as Copy'         => "a#toolbar-save-copy",
		'Cancel'               => "a#toolbar-cancel",
		'Back'                 => "a#toolbar-back",
		'Delete'               => "a#toolbar-delete",
		'Restore'              => "a#toolbar-unarchive",
		'Enable'               => "a#toolbar-publish",
		'Import'               => "a#toolbar-download",
		'Export'               => "a#toolbar-upload",
		'Export Popup'         => "a#toolbar-popup-upload",
		'Batch'                => "a#toolbar-batch",
		'Reset sending trials' => "a#toolbar-unpublish",
		'Continue sending'     => "a#toolbar-popup-envelope",
		'Clear queue'          => "a#toolbar-delete",
		'Uninstall  '          => "a#toolbar-delete",
		'BwPostman Manual'     => "a#toolbar-book",
		'BwPostman Forum'      => "a#toolbar-users",
	);

	/**
	 * Array of arrows to sort
	 *
	 * @var array
	 *
	 * @since 2.0.0
	 */
	public static $sort_arrows = array(
		'up'    => 'icon-arrow-up-3',
		'down'  => 'icon-arrow-down-3'
	);

	/**
	 * Location of selected value in sort select list
	 *
	 * @var string
	 *
	 * @since   2.0.0
	 */
	public static $select_list_selected_location = "//*[@id='%s']/option[contains(text(), '%s')][@selected='selected']";

	/**
	 * @var string
	 *
	 * @since 2.0.0
	 */
	public static $select_list_open              = "//*[@id='%s']";

	/**
	 * Location of table column
	 *
	 * @var string
	 *
	 * @since   2.0.0
	 */
	public static $table_headcol_link_location = "//*[@id='main-table']/thead/tr/th/a/span[normalize-space(text()) = '%s']";

	/**
	 * Location of main table and arrow column
	 *
	 * @var string
	 *
	 * @since   2.0.0
	 */
	public static $main_table                   = "//*[@id='main-table']";

	/**
	 * @var string
	 *
	 * @since 2.0.0
	 */
	public static $table_headcol_arrow_location = "//*/table/thead/tr/th[%s]/a/span";

	/**
	 * @var string
	 *
	 * @since 2.0.0
	 */
	public static $search_list_id       = "filter_search_filter";

	/**
	 * @var string
	 *
	 * @since 2.0.0
	 */
	public static $search_field         = "//*[@id='filter_search']";

	/**
	 * @var string
	 *
	 * @since 2.0.0
	 */
	public static $search_list          = "//*[@id='filter_search_filter']";

	/**
	 * @var string
	 *
	 * @since 2.0.0
	 */
	public static $search_button        = "//*[@class='js-stools-container-bar']/div[1]/div[1]/div[1]/span/button";

	// Filter bar

	/**
	 * @var string
	 *
	 * @since 2.0.0
	 */
	public static $filterbar_button     = "//*[@id='j-main-container']/div[1]/div[1]/div[1]/div[2]/button";

	/**
	 * @var string
	 *
	 * @since 2.0.0
	 */
	public static $filter_bar_open      = "//*[@id='j-main-container']/div[1]/div[2]";

	/**
	 * @var string
	 *
	 * @since 2.0.0
	 */
	public static $clear_button         = "//*[@class='js-stools-container-bar']/div[1]/button";

	/**
	 * @var string
	 *
	 * @since 2.0.0
	 */
	public static $null_row             = "//*/table/tbody/tr/td";

	/**
	 * @var string
	 *
	 * @since 2.0.0
	 */
	public static $null_msg             = "There are no data available";

	// Filter status

	/**
	 * @var string
	 *
	 * @since 2.0.0
	 */
	public static $status_list_id       = "//*[@id='filter_published']";

	/**
	 * @var string
	 *
	 * @since 2.0.0
	 */
	public static $status_list          = "//*[@id='filter_published_chzn']/a";

	/**
	 * @var string
	 *
	 * @since 2.0.0
	 */
	public static $status_none          = "//*[@id='filter_published_chzn']/div/ul/li[text()='- Select Status -']";

	/**
	 * @var string
	 *
	 * @since 2.0.0
	 */
	public static $status_unpublished   = "unpublished";

	/**
	 * @var string
	 *
	 * @since 2.0.0
	 */
	public static $status_published     = "published";

	/**
	 * @var string
	 *
	 * @since 2.0.0
	 */
	public static $icon_unpublished     = "//*/a/span[contains(@class, 'icon-unpublish')]";

	/**
	 * @var string
	 *
	 * @since 2.0.0
	 */
	public static $icon_published       = "//*/a/span[contains(@class, 'icon-publish')]";

	// filter identifiers

	/**
	 * @var string
	 *
	 * @since 2.0.0
	 */
	public static $publish_row          = "//*[@id='main-table']/tbody/tr[%s]/td[%s]/a/span[contains(@class, 'icon-publish')]";

	/**
	 * @var string
	 *
	 * @since 2.0.0
	 */
	public static $unpublish_row        = "//*[@id='main-table']/tbody/tr[%s]/td[%s]/a/span[contains(@class, 'icon-unpublish')]";

	/**
	 * @var string
	 *
	 * @since 2.0.0
	 */
	public static $attachment_row       = "//*[@id='main-table']/tbody/tr[%s]/td[2]/span[contains(@class, 'icon_attachment')]";

	/**
	 * @var string
	 *
	 * @since 2.0.0
	 */
	public static $template_yes_row          = "//*[@id='main-table']/tbody/tr[%s]/td[%s]/button/span[contains(@class, 'icon-featured')]";

	/**
	 * @var string
	 *
	 * @since 2.0.0
	 */
	public static $template_no_row        = "//*[@id='main-table']/tbody/tr[%s]/td[%s]/button/span[contains(@class, 'icon-unfeatured')]";

	/**
	 * @var string
	 *
	 * @since 2.0.0
	 */
	public static $null_date            = '0000-00-00 00:00:00';

	/**
	 * @var string
	 *
	 * @since 2.0.0
	 */
	public static $table_header         = "//*/thead";

	/**
	 * @var string
	 *
	 * @since 2.0.0
	 */
	public static $pagination_bar       = "//*/div[contains(@class, 'pagination pagination-toolbar')]";

		// Filter access

	/**
	 * @var string
	 *
	 * @since 2.0.0
	 */
	public static $access_column        = "//*/td[5]";

	/**
	 * @var string
	 *
	 * @since 2.0.0
	 */
	public static $access_list_id       = "//*[@id='filter_access']";

	/**
	 * @var string
	 *
	 * @since 2.0.0
	 */
	public static $access_list          = "//*[@id='filter_access_chzn']/a";

	/**
	 * @var string
	 *
	 * @since 2.0.0
	 */
	public static $access_none          = "//*[@id='filter_access_chzn']/div/ul/li[text()='- Select Access -']";

	/**
	 * @var string
	 *
	 * @since 2.0.0
	 */
	public static $access_public        = "Public";

	/**
	 * @var string
	 *
	 * @since 2.0.0
	 */
	public static $access_guest         = "Guest";

	/**
	 * @var string
	 *
	 * @since 2.0.0
	 */
	public static $access_registered    = "Registered";

	/**
	 * @var string
	 *
	 * @since 2.0.0
	 */
	public static $access_special       = "Special";

	/**
	 * @var string
	 *
	 * @since 2.0.0
	 */
	public static $access_super         = "Super Users";

	/**
	 * @var string
	 *
	 * @since 2.4.0
	 */
	public static $filterOptionsSwitcher        = "//*[@class='js-stools-container-bar']/div/div/button[contains(@class,'js-stools-btn-filter')]";

	/**
	 * @var string
	 *
	 * @since 2.4.0
	 */
	public static $filterOptionsPopup        = "//*/div[contains(@class,'js-stools-container-filters')]";


	/**
	 * @var string
	 *
	 * @since 2.0.0
	 */
	public static $ordering_list        = "//*[@id='list_fullordering']";

	/**
	 * @var string
	 *
	 * @since 2.0.0
	 */
	public static $ordering_value       = "//*[@id='list_fullordering']/option[text()='";

	/**
	 * @var string
	 *
	 * @since 2.0.0
	 */
	public static $ordering_id          = "list_fullordering";

	// list limit

	/**
	 * @var string
	 *
	 * @since 2.0.0
	 */
	public static $limit_list_id        = "//*[@id='list_limit']";

	/**
	 * @var string
	 *
	 * @since 2.0.0
	 */
	public static $limit_list           = "//*[@class='js-stools-container-bar']/div[1]/div[1]/button";

	/**
	 * @var string
	 *
	 * @since 2.0.0
	 */
	public static $limit_5              = "5";

	/**
	 * @var string
	 *
	 * @since 2.0.0
	 */
	public static $limit_10             = "10";

	/**
	 * @var string
	 *
	 * @since 2.0.0
	 */
	public static $limit_15             = "15";

	/**
	 * @var string
	 *
	 * @since 2.0.0
	 */
	public static $limit_20             = "20";

	/**
	 * @var string
	 *
	 * @since 2.0.0
	 */
	public static $limit_25             = "25";

	/**
	 * @var string
	 *
	 * @since 2.0.0
	 */
	public static $limit_30             = "30";

	// Pagination
	/**
	 * @var string
	 *
	 * @since 2.0.0
	 */
	public static $first_page           = "//*/a[contains(@aria-label, 'Go to start page')]";

	/**
	 * @var string
	 *
	 * @since 2.0.0
	 */
	public static $prev_page            = "//*/a[contains(@aria-label, 'Go to previous page')]";

	/**
	 * @var string
	 *
	 * @since 2.0.0
	 */
	public static $next_page            = "//*/a[contains(@aria-label, 'Go to next page')]";

	/**
	 * @var string
	 *
	 * @since 2.0.0
	 */
	public static $last_page            = "//*/a[contains(@aria-label, 'Go to end page')]";

	/**
	 * @var string
	 *
	 * @since 2.0.0
	 */
	public static $page_1               = "//*/a[contains(@aria-label, 'Go to page 1')]";

	/**
	 * @var string
	 *
	 * @since 2.0.0
	 */
	public static $page_2               = "//*/a[contains(@aria-label, 'Go to page 2')]";

	/**
	 * @var string
	 *
	 * @since 2.0.0
	 */
	public static $page_3               = "//*/a[contains(@aria-label, 'Go to page 3')]";

	/**
	 * @var string
	 *
	 * @since 2.0.0
	 */
	public static $last_page_identifier = "//*/li[contains(@class, 'page-link') and contains(@class, 'current')]/span";


	// buttons
	/**
	 * @var string
	 *
	 * @since 2.0.0
	 */
	public static $button_red   = 'btn btn-outline-danger';

	/**
	 * @var string
	 *
	 * @since 2.0.0
	 */
	public static $button_green = 'btn btn-outline-success';

	/**
	 * @var string
	 *
	 * @since 2.0.0
	 */
	public static $button_grey  = 'btn';

	// General error messages
	/**
	 * @var string
	 *
	 * @since 2.0.0
	 */
	public static $msg_edit_no_permission   = "No permission to edit this item!";

	/**
	 * Variables for selecting mailinglists
	 * Hint: Use with sprintf <nbr> for wanted row
	 *
	 * @var    string
	 *
	 * @since  2.0.0
	 */
	public static $mls_accessible       = "//*[@id='jform_ml_available_%s']";

	/**
	 * @var string
	 *
	 * @since 2.0.0
	 */
	public static $mls_nonaccessible    = "//*[@id='jform_ml_unavailable_%s']";

	/**
	 * @var string
	 *
	 * @since 2.0.0
	 */
	public static $mls_internal         = "//*[@id='jform_ml_intern_%s']";

	/**
	 * @var string
	 *
	 * @since 2.0.0
	 */
	public static $mls_usergroup        = "//*[@id='1group_2']";

	/**
	 * General messages
	 * /

	/**
	 * @var string
	 *
	 * @since 2.0.0
	 */
	public static $invalidField        = "Field required: ";


	/**
	 * Plugin related
	 * /

	/**
	 * @var string
	 *
	 * @since 2.0.0
	 */
	public static $plugin_disabled_success  = 'Plugin successfully disabled';

	/**
	 * @var string
	 *
	 * @since 2.0.0
	 */
	public static $plugin_enabled_success   = 'Plugin successfully enabled';

	/**
	 * @var string
	 *
	 * @since 2.0.0
	 */
	public static $plugin_saved_success     = 'Plugin saved';

	/**
	 * @var string
	 *
	 * @since 2.0.0
	 */
	public static $plugin_page                      = "/administrator/index.php?option=com_plugins";

	/**
	 * @var string
	 *
	 * @since 2.0.0
	 */
	public static $view_plugin                      = "Plugins";



	/**
	 * Basic route example for your current URL
	 * You can append any additional parameter to URL
	 * and use it in tests like: Page\Edit::route('/123-post');
	 *
	 * @param   string  $param  page to route to
	 *
	 * @return  string  new url
	 *
	 * @since   2.0.0
	 */
	public static function route($param)
	{
		return static::$url . $param;
	}

	/**
	 * Method to get install file name
	 *
	 * @return     string
	 *
	 * @since  2.0.0
	 */
	public static function getInstallFileName()
	{
		return '/Support/Software/Joomla/BwPostman/' . self::$versionToTest . '/com_bwpostman/com_bwpostman.' . self::$versionToTest . '.zip';
	}

	/**
	 * Method to get all options of component from manifest
	 *
	 * @param       object      $options
	 *
	 * @since  2.0.0
	 */
	public static function setComponentOptions($options)
	{
		self::$com_options = $options;
	}

	/**
	 * @param \AcceptanceTester $I
	 *
	 * @throws \Exception
	 *
	 * @since version
	 */
	public static function dontSeeAnyWarning(\AcceptanceTester $I)
	{
		$I->waitForElement(self::$alert_header, 30);

		$I->dontSee(self::$alert_warn_txt, self::$alert);
		$I->dontSee(self::$alert_error_txt, self::$alert);

		$I->dontSeeElement(self::$alert_warn);
		$I->dontSeeElement(self::$alert_error);
	}
}
