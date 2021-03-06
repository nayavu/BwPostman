<?php
/**
 * BwPostman Newsletter Component
 *
 * BwPostman main controller for frontend.
 *
 * @version %%version_number%%
 * @package BwPostman-Site
 * @author Romana Boldt
 * @copyright (C) %%copyright_year%% Boldt Webservice <forum@boldt-webservice.de>
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
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\Registry\Registry;
use Joomla\CMS\Plugin\PluginHelper;

// Import CONTROLLER object class
jimport('joomla.application.component.controller');


/**
 * Class BwPostmanController
 *
 * @since       0.9.1
 */
class BwPostmanController extends JControllerLegacy
{
	/**
	 * Constructor
	 * Checks the session variables and deletes them if necessary
	 * Sets the userid and subscriberid
	 * Checks if something is wrong with the subscriber-data (not activated/blocked)
	 *
	 * @since       0.9.1
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Display
	 *
	 * @param	boolean		$cachable	If true, the view output will be cached
	 * @param	boolean		$urlparams	An array of safe url parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
	 *
	 * @return object       $this
	 *
	 * @since       0.9.1
	 *
	 * @throws Exception
	 */
	public function display($cachable = false, $urlparams = false)
	{
		parent::display($cachable, $urlparams);

		return $this;
	}

	/**
	 * Method to do the cron loop
	 *
	 * @return boolean
	 *
	 * @throws  Exception
	 *
	 * @since       2.3.0
	 */
	public function doCron()
	{
		$plugin = PluginHelper::getPlugin('bwpostman', 'bwtimecontrol');
		$pluginParams = new Registry();
		$pluginParams->loadString($plugin->params);
		$pluginPw   = (string) $pluginParams->get('bwtimecontrol_passwd');
		$pluginUser = (string) $pluginParams->get('bwtimecontrol_username');

		if ($pluginUser === "" || $pluginPw === "")
		{
			Factory::getApplication()->enqueueMessage(Text::_('COM_BWPOSTMAN_ERROR_TC_NO_CREDENTIALS'), 'error');
		}

		require_once(JPATH_PLUGINS . '/bwpostman/bwtimecontrol/helpers/phpcron.php');

		$bwpostmancron = new BwPostmanPhpCron();

		$bwpostmancron->doCronJob();

		return true;
	}
}
