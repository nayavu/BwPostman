<?php
/**
 * BwPostman Newsletter Component
 *
 * BwPostman main component for backend.
 *
 * @version %%version_number%%
 * @package BwPostman-Admin
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

// Miscellaneous
define('BWPOSTMAN_LOG_MEM', 0);

// import joomla controller library
jimport('joomla.application.component.controller');

// Require class
require_once(JPATH_COMPONENT_ADMINISTRATOR . '/classes/admin.class.php');
require_once(JPATH_COMPONENT_ADMINISTRATOR . '/helpers/helper.php');

try
{
	// Get the user object
	$user = JFactory::getUser();
	$app  = JFactory::getApplication();
	// Access check.
	if ((!$user->authorise('core.manage', 'com_bwpostman')))
	{
		$app->enqueueMessage(JText::_('JERROR_ALERTNOAUTHOR'), 'warning');

		return false;
	}

	// Preload user permissions
	BwPostmanHelper::setPermissionsState();

	// Get an instance of the controller
	$controller = JControllerLegacy::getInstance('BwPostman');

//	require_once(JPATH_ADMINISTRATOR . '/components/com_bwpostman/controller.php');
//	$controller2 = new BwPostmanController();

	// Perform the Request task
	$jinput = JFactory::getApplication()->input;
	$task = $jinput->getCmd('task');
	$controller->execute($task);

	// Redirect if set by the controller
	$controller->redirect();
}
catch (Exception $exception)
{
	JText::_('JERROR_AN_ERROR_HAS_OCCURRED');
	$app->enqueueMessage($exception->getMessage());
}
