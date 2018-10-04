<?php
namespace Page;

use Page\MainviewPage as MainView;

/**
 * Class MaintenancePage
 *
 * @package Page
 * @copyright (C) 2018 Boldt Webservice <forum@boldt-webservice.de>
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
class MaintenancePage
{
	// include url of current page

	/**
	 * @var string
	 *
	 * @since 2.0.0
	 */
	public static $url          = "/administrator/index.php?option=com_bwpostman&view=maintenance";

	/**
	 * @var string
	 *
	 * @since 2.0.0
	 */
	public static $forum_url    = "https://www.boldt-webservice.de/de/forum/bwpostman.html";

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
	public static $checkTablesButton    = ".//*[@id='cpanel']/div[1]/div/a";

	/**
	 * @var string
	 *
	 * @since 2.0.0
	 */
	public static $saveTablesButton     = ".//*[@id='cpanel']/div[2]/div/a/img";

	/**
	 * @var string
	 *
	 * @since 2.0.0
	 */
	public static $restoreTablesButton  = ".//*[@id='cpanel']/div[3]/div/a";

	/**
	 * @var string
	 *
	 * @since 2.0.0
	 */
	public static $settingsButton       = ".//*[@id='cpanel']/div[4]/div/a";

	/**
	 * @var string
	 *
	 * @since 2.0.0
	 */
	public static $forumButton          = ".//*[@id='cpanel']/div[5]/div/a";

	/**
	 * @var string
	 *
	 * @since 2.0.0
	 */
	public static $checkBackButton      = ".//*[@id='toolbar-arrow-left']/button";

	/**
	 * @var string
	 *
	 * @since 2.0.0
	 */
	public static $cancelSettingsButton = ".//*[@id='toolbar-cancel']/button";


	/**
	 * @var string
	 *
	 * @since 2.0.0
	 */
	public static $heading              = "Maintenance";

	/**
	 * @var string
	 *
	 * @since 2.0.0
	 */
	public static $checkHeading         = "Check tables";

	/**
	 * @var string
	 *
	 * @since 2.0.0
	 */
	public static $headingRestoreFile   = ".//*[@id='adminForm']/fieldset/legend";

	/**
	 * @var string
	 *
	 * @since 2.0.0
	 */
	public static $headingSettings      = "BwPostman Configuration";


	/**
	 * @var string
	 *
	 * @since 2.0.0
	 */
	public static $step1Field           = ".//*[@id='step1'][contains(@class, 'alert-success')]";

	/**
	 * @var string
	 *
	 * @since 2.0.0
	 */
	public static $step2Field           = ".//*[@id='step2'][contains(@class, 'alert-success')]";

	/**
	 * @var string
	 *
	 * @since 2.0.0
	 */
	public static $step3Field           = ".//*[@id='step3'][contains(@class, 'alert-success')]";

	/**
	 * @var string
	 *
	 * @since 2.0.0
	 */
	public static $step4Field           = ".//*[@id='step4'][contains(@class, 'alert-success')]";

	/**
	 * @var string
	 *
	 * @since 2.0.0
	 */
	public static $step5Field           = ".//*[@id='step5'][contains(@class, 'alert-success')]";

	/**
	 * @var string
	 *
	 * @since 2.0.0
	 */
	public static $step6Field           = ".//*[@id='step6'][contains(@class, 'alert-success')]";

	/**
	 * @var string
	 *
	 * @since 2.0.0
	 */
	public static $step7Field           = ".//*[@id='step7'][contains(@class, 'alert-success')]";

	/**
	 * @var string
	 *
	 * @since 2.0.0
	 */
	public static $step8Field           = ".//*[@id='step8'][contains(@class, 'alert-success')]";

	/**
	 * @var string
	 *
	 * @since 2.0.0
	 */
	public static $step9Field           = ".//*[@id='step9'][contains(@class, 'alert-success')]";

	/**
	 * @var string
	 *
	 * @since 2.0.0
	 */
	public static $step10Field          = ".//*[@id='step10'][contains(@class, 'alert-success')]";

	/**
	 * @var string
	 *
	 * @since 2.0.0
	 */
	public static $step11Field          = ".//*[@id='step11'][contains(@class, 'alert-success')]";


	/**
	 * @var string
	 *
	 * @since 2.0.0
	 */
	public static $step5SuccessClass    = ".//*[@id='step5'][contains(@class, 'alert-success')]";

	/**
	 * @var string
	 *
	 * @since 2.0.0
	 */
	public static $step11SuccessClass   = ".//*[@id='step11'][contains(@class, 'alert-success')]";

	/**
	 * @var string
	 *
	 * @since 2.0.0
	 */
	public static $step5SuccessMsg      = "Check asset-id's and user-id's..";

	/**
	 * @var string
	 *
	 * @since 2.0.0
	 */
	public static $step11SuccessMsg     = "Check: Check asset ids and user ids...";

	/**
	 * Test method to restore tables
	 *
	 * @param   \AcceptanceTester                $I
	 *
	 * @before  _login
	 *
	 * @after   _logout
	 *
	 * @return  void
	 *
	 * @throws \Exception
	 *
	 * @since   2.0.0
	 */
	public static function restoreTables(\AcceptanceTester $I)
	{
		$I->wantTo("Restore tables");
		$I->expectTo("see 'Result check okay'");
		$I->amOnPage(MainView::$url);
		$I->click(MainView::$maintenanceButton);

		$I->waitForElement(Generals::$pageTitle, 30);
		$I->see(self::$heading);

		$I->click(self::$restoreTablesButton);
		$I->waitForElement(self::$headingRestoreFile, 30);

		$I->attachFile(".//*[@id='restorefile']", "BwPostman_2_1_0_Tables.xml");

		$I->click(".//*[@id='adminForm']/fieldset/div[2]/div/table/tbody/tr[2]/td/input");
		$I->dontSeeElement(Generals::$alert_error);

		$I->waitForElementVisible(self::$step1Field, 30);
		$I->waitForElementVisible(self::$step2Field, 30);
		$I->waitForElementVisible(self::$step3Field, 30);
		$I->waitForElementVisible(self::$step4Field, 150);
		$I->waitForElementVisible(self::$step5Field, 30);
		$I->waitForElementVisible(self::$step6Field, 300);
		$I->waitForElementVisible(self::$step7Field, 30);
		$I->waitForElementVisible(self::$step8Field, 30);
		$I->waitForElementVisible(self::$step9Field, 30);
		$I->waitForElementVisible(self::$step10Field, 30);
		$I->waitForElementVisible(self::$step11Field, 30);
		$I->waitForElementVisible(self::$step11SuccessClass, 30);
		$I->see(self::$step11SuccessMsg, self::$step11SuccessClass);
		$I->click(self::$checkBackButton);
		$I->waitForElement(Generals::$pageTitle, 30);
		$I->see(self::$heading, Generals::$pageTitle);
	}
}
