<?php
namespace Backend\Access;

use Page\Generals as Generals;
use Page\InstallationPage;
use Page\InstallUsersPage;
use Page\Login as LoginPage;

use Page\AccessPage as AccessPage;
use Page\InstallUsersPage as UsersPage;


/**
 * Class TestInstallUsersCest
 *
 * This class contains the installation and configuration of all users which are needed to test access at backend of BwPostman
 *
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
 * @since   2.1.0
 */
class TestInstallUsersCest
{
	/**
	 * Test method to login into backend
	 *
	 * @param   LoginPage            $loginPage
	 * @param   array                  $user
	 *
	 * @return  void
	 *
	 * @throws \Exception
	 *
	 * @since   2.0.0
	 */
	public function _login(LoginPage $loginPage, array $user)
	{
		$loginPage->logIntoBackend($user);
	}

	/**
	 * Test method to check for allowed/forbidden of list links at main view of BwPostman
	 *
	 * @param   \AcceptanceTester            $I
	 *
	 * @return  void
	 *
	 * @throws \Exception
	 *
	 * @since   2.0.0
	 */
	public function TestAccessRightsForListViewButtonsFromMainView(\AcceptanceTester $I)
	{
		$I->wantTo("check permissions for main view list buttons");
		$I->expectTo("see appropriate messages");

		$loginPage = new LoginPage($I);
		$this->_login($loginPage, Generals::$admin);

		foreach (AccessPage::$all_users as $user)
		{
			# Check for usergroup. If not exists, throw exception
			$groupId = $I->grabColumnFromDatabase(Generals::$db_prefix . 'id', 'id', array('title' => $user['user']));

			if (!$groupId)
			{
				$e = new \Exception();
				throwException($e);
			}

			# Check for user. If exists, skip
			$userId = $I->grabColumnFromDatabase(Generals::$db_prefix . 'users', 'id', array('name' => $user['user']));

			if ($userId)
			{
				continue;
			}

			# Switch to user page
			$I->amOnPage(InstallUsersPage::$user_management_url);

			$I->click(Generals::$toolbar['New']);
			$I->waitForElement(InstallUsersPage::$registerName);

			# Add user
			$I->fillField(InstallUsersPage::$registerName, $user['user']);
			$I->fillField(InstallUsersPage::$registerLoginName, $user['user']);
			$I->fillField(InstallUsersPage::$registerPassword1, $user['password']);
			$I->fillField(InstallUsersPage::$registerPassword2, $user['password']);
			$I->fillField(InstallUsersPage::$registerEmail, $user['user'] . "@tester-net.nil");

			$I->click(InstallUsersPage::$usergroupTab);
			$I->waitForElement(InstallUsersPage::$publicGroup);

			$checkbox = sprintf(InstallUsersPage::$usergroupCheckbox, $groupId);
			codecept_debug("Checkbox: $checkbox");
			$I->click($checkbox);

			$I->click(Generals::$toolbar['Save & Close']);
			$I->waitForElement(Generals::$alert_success, 10);
			$I->see(InstallUsersPage::$createSuccessMsg, Generals::$alert_success);
		}
		$this->_logout($I, $loginPage);
	}

	/**
	 * Test method to logout from backend
	 *
	 * @param   \AcceptanceTester     $I
	 * @param   LoginPage             $loginPage
	 * @param   boolean               $truncateSession
	 *
	 * @return  void
	 *
	 * @throws \Exception
	 *
	 * @since   2.0.0
	 */
	public function _logout(\AcceptanceTester $I, LoginPage $loginPage, $truncateSession = false)
	{
		$loginPage->logoutFromBackend($I, $truncateSession);
	}

}
