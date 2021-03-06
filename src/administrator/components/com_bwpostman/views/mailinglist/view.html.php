<?php
/**
 * BwPostman Newsletter Component
 *
 * BwPostman single mailinglists view for backend.
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

use Joomla\CMS\Factory;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Toolbar\Toolbar;
use Joomla\CMS\Toolbar\ToolbarHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Environment\Browser;

// Require helper class
require_once(JPATH_COMPONENT_ADMINISTRATOR . '/helpers/helper.php');
require_once(JPATH_COMPONENT_ADMINISTRATOR . '/helpers/htmlhelper.php');

// Import VIEW object class
jimport('joomla.application.component.view');

/**
 * BwPostman Mailinglist View
 *
 * @package 	BwPostman-Admin
 *
 * @subpackage 	Mailinglists
 *
 * @since       0.9.1
 */
class BwPostmanViewMailinglist extends JViewLegacy
{
	/**
	 * property to hold form data
	 *
	 * @var array   $form
	 *
	 * @since       0.9.1
	 */
	protected $form;

	/**
	 * property to hold selected item
	 *
	 * @var object   $item
	 *
	 * @since       0.9.1
	 */
	protected $item;

	/**
	 * property to hold state
	 *
	 * @var array|object  $state
	 *
	 * @since       0.9.1
	 */
	protected $state;

	/**
	 * property to hold queue entries property
	 *
	 * @var boolean $queueEntries
	 *
	 * @since       0.9.1
	 */
	public $queueEntries;

	/**
	 * property to hold request url
	 *
	 * @var object  $request_url
	 *
	 * @since       0.9.1
	 */
	protected $request_url;

	/**
	 * property to hold template
	 *
	 * @var object $template
	 *
	 * @since       0.9.1
	 */
	public $template;


	/**
	 * property to hold permissions as array
	 *
	 * @var array $permissions
	 *
	 * @since       2.0.0
	 */
	public $permissions;

	/**
	 * Execute and display a template script.
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  mixed  A string if successful, otherwise a JError object.
	 *
	 * @throws Exception
	 *
	 * @since       0.9.1
	 */
	public function display($tpl = null)
	{
		$app		= Factory::getApplication();
		$template	= $app->getTemplate();
		$uri		= Uri::getInstance();
		$uri_string	= str_replace('&', '&amp;', $uri->toString());

		$this->permissions		= Factory::getApplication()->getUserState('com_bwpm.permissions');

		if (!$this->permissions['view']['mailinglist'])
		{
			$app->enqueueMessage(Text::sprintf('COM_BWPOSTMAN_VIEW_NOT_ALLOWED', Text::_('COM_BWPOSTMAN_MLS')), 'error');
			$app->redirect('index.php?option=com_bwpostman');
		}

		$app->setUserState('com_bwpostman.edit.mailinglist.id', Factory::getApplication()->input->getInt('id', 0));

		//check for queue entries
		$this->queueEntries	= BwPostmanHelper::checkQueueEntries();

		$this->form		= $this->get('Form');
		$this->item		= $this->get('Item');
		$this->state	= $this->get('State');

		// Save a reference into view
		$this->request_url	= $uri_string;
		$this->template		= $template;

		$this->addToolbar();

		// Call parent display
		parent::display($tpl);
		return $this;
	}

	/**
	 * Add the page title, styles and toolbar.
	 *
	 * @throws Exception
	 *
	 * @since       0.9.1
	 */
	protected function addToolbar()
	{
		Factory::getApplication()->input->set('hidemainmenu', true);
		$uri		= Uri::getInstance();
		$userId		= Factory::getUser()->get('id');

		// Get document object, set document title and add css
		$document = Factory::getDocument();
		$document->setTitle(Text::_('COM_BWPOSTMAN_ML_DETAILS'));
		$document->addStyleSheet(Uri::root(true) . '/administrator/components/com_bwpostman/assets/css/bwpostman_backend.css');
		$document->addScript(Uri::root(true) . '/administrator/components/com_bwpostman/assets/js/bwpm_mailinglist.js');

		// Get the user browser --> if the user has msie load the ie-css to show the tabs in the correct way
		jimport('joomla.environment.browser');
		$browser = Browser::getInstance();
		$user_browser = $browser->getBrowser();

		if ($user_browser == 'msie')
		{
			$document->addStyleSheet(Uri::root(true) . '/administrator/components/com_bwpostman/assets/css/bwpostman_backend_ie.css');
		}

		// Set toolbar title depending on the state of the item: Is it a new item? --> Create; Is it an existing record? --> Edit
		$isNew = ($this->item->id < 1);

		// Set toolbar title and items
		$checkedOut	= !($this->item->checked_out == 0 || $this->item->checked_out == $userId);

		// For new records, check the create permission.
		if ($isNew && BwPostmanHelper::canAdd('mailinglist'))
		{
			ToolbarHelper::save('mailinglist.save');
			ToolbarHelper::apply('mailinglist.apply');
			ToolbarHelper::save2new('mailinglist.save2new');
			ToolbarHelper::save2copy('mailinglist.save2copy');
			ToolbarHelper::cancel('mailinglist.cancel');
			ToolbarHelper::title(Text::_('COM_BWPOSTMAN_ML_DETAILS') . ': <small>[ ' . Text::_('NEW') . ' ]</small>', 'plus');
		}
		else
		{
			// Can't save the record if it's checked out.
			if (!$checkedOut)
			{
				// Since it's an existing record, check the edit permission, or fall back to edit own if the owner.
				if (BwPostmanHelper::canAdd('mailinglist'))
				{
					ToolbarHelper::save('mailinglist.save');
					ToolbarHelper::apply('mailinglist.apply');

					ToolbarHelper::save2copy('mailinglist.save2copy');
					ToolbarHelper::save2new('mailinglist.save2new');
				}
			}

			// Rename the cancel button for existing items
			ToolbarHelper::cancel('mailinglist.cancel', 'JTOOLBAR_CLOSE');
			ToolbarHelper::title(Text::_('COM_BWPOSTMAN_ML_DETAILS') . ': <small>[ ' . Text::_('EDIT') . ' ]</small>', 'edit');
		}

		$backlink   = '';
		if (key_exists('HTTP_REFERER', $_SERVER))
		{
			$backlink 	= Factory::getApplication()->input->server->get('HTTP_REFERER', '', '');
		}

		$siteURL 	= $uri->base() . 'index.php?option=com_bwpostman';

		// If we came from the cover page we will show a back-button
		if ($backlink == $siteURL)
		{
			ToolbarHelper::spacer();
			ToolbarHelper::divider();
			ToolbarHelper::spacer();
			ToolbarHelper::back();
		}

		ToolbarHelper::divider();
		ToolbarHelper::spacer();

		$bar = Toolbar::getInstance('toolbar');
		$bar->addButtonPath(JPATH_COMPONENT_ADMINISTRATOR . '/libraries/toolbar');

		$manualLink = BwPostmanHTMLHelper::getManualLink('mailinglist');
		$forumLink  = BwPostmanHTMLHelper::getForumLink();

		$bar->appendButton('Extlink', 'users', Text::_('COM_BWPOSTMAN_FORUM'), $forumLink);
		$bar->appendButton('Extlink', 'book', Text::_('COM_BWPOSTMAN_MANUAL'), $manualLink);
	}
}
