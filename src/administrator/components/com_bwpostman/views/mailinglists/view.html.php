<?php
/**
 * BwPostman Newsletter Component
 *
 * BwPostman all mailinglists view for backend.
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
use Joomla\CMS\Toolbar\Toolbar;
use Joomla\CMS\Toolbar\ToolbarHelper;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Layout\LayoutHelper;

// Import VIEW object class
jimport('joomla.application.component.view');

// Require helper class
JLoader::register('BwPostmanHelper', JPATH_COMPONENT_ADMINISTRATOR . '/helpers/helper.php');
JLoader::register('BwPostmanHTMLHelper', JPATH_COMPONENT_ADMINISTRATOR . '/helpers/htmlhelper.php');

/**
 * BwPostman Lists View
 *
 * @package 	BwPostman-Admin
 *
 * @subpackage 	Mailinglists
 *
 * @since       0.9.1
 */
class BwPostmanViewMailinglists extends JViewLegacy
{
	/**
	 * property to hold selected items
	 *
	 * @var array   $items
	 *
	 * @since       0.9.1
	 */
	protected $items;

	/**
	 * property to hold pagination object
	 *
	 * @var object  $pagination
	 *
	 * @since       0.9.1
	 */
	protected $pagination;

	/**
	 * property to hold state
	 *
	 * @var array|object  $state
	 *
	 * @since       0.9.1
	 */
	protected $state;

	/**
	 * property to hold filter form
	 *
	 * @var object  $filterForm
	 *
	 * @since       0.9.1
	 */
	public $filterForm;

	/**
	 * property to hold active filters
	 *
	 * @var object  $activeFilters
	 *
	 * @since       0.9.1
	 */
	public $activeFilters;

	/**
	 * property to hold total value
	 *
	 * @var string $total
	 *
	 * @since       0.9.1
	 */
	public $total;

	/**
	 * property to hold permissions as array
	 *
	 * @var array $permissions
	 *
	 * @since       2.0.0
	 */
	public $permissions;

	/**
	 * property to hold sidebar
	 *
	 * @var object  $sidebar
	 *
	 * @since       0.9.1
	 */
	public $sidebar;

	/**
	 * Are we at Joomla 4?
	 *
	 * @var    string
	 *
	 * @since  2.4.0
	 */
	public $isJ4 = false;

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
		$app	= JFactory::getApplication();

		$this->permissions		= JFactory::getApplication()->getUserState('com_bwpm.permissions');

		if (!$this->permissions['view']['mailinglist'])
		{
			$app->enqueueMessage(JText::sprintf('COM_BWPOSTMAN_VIEW_NOT_ALLOWED', JText::_('COM_BWPOSTMAN_MLS')), 'error');
			$app->redirect('index.php?option=com_bwpostman');
		}

		// Get data from the model
		$this->state			= $this->get('State');
		$this->items			= $this->get('Items');
		$this->filterForm		= $this->get('FilterForm');
		$this->activeFilters	= $this->get('ActiveFilters');
		$this->pagination		= $this->get('Pagination');
		$this->total			= $this->get('total');

		if(version_compare(JVERSION, '3.99', 'ge'))
		{
			$this->isJ4 = true;
		}

		if(!$this->isJ4)
		{
			$this->addToolbarLegacy();
			BwPostmanHelper::addSubmenu('bwpostman');
		}
		else
		{
			$this->addToolbarLegacy();
		}

		$this->sidebar = JHtmlSidebar::render();

		// Call parent display
		parent::display($tpl);

		return $this;
	}


	/**
	 * Add the page title and toolbar for Joomla 3.
	 *
	 * @throws Exception
	 *
	 * @since       0.9.1
	 */
	protected function addToolbarLegacy()
	{
		// Get document object, set document title and add css
		$document = Factory::getDocument();
		$document->setTitle(JText::_('COM_BWPOSTMAN_MLS'));
		$document->addStyleSheet(JUri::root(true) . '/administrator/components/com_bwpostman/assets/css/bwpostman_backend.css');

		// Set toolbar title
		ToolbarHelper::title(JText::_('COM_BWPOSTMAN_MLS'), 'list');

		// Set toolbar items for the page
		if ($this->permissions['mailinglist']['create'])
		{
			ToolbarHelper::addNew('mailinglist.add');
		}

		if (BwPostmanHelper::canEdit('mailinglist'))
		{
			ToolbarHelper::editList('mailinglist.edit');
		}

		ToolbarHelper::divider();
		if (BwPostmanHelper::canEditState('mailinglist'))
		{
			ToolbarHelper::publishList('mailinglists.publish');
			ToolbarHelper::unpublishList('mailinglists.unpublish');
			ToolbarHelper::divider();
		}

		if (BwPostmanHelper::canArchive('mailinglist'))
		{
			ToolbarHelper::archiveList('mailinglist.archive');
			ToolbarHelper::divider();
			ToolbarHelper::spacer();
		}

		if (BwPostmanHelper::canEdit('mailinglist', 0) || BwPostmanHelper::canEditState('mailinglist', 0))
		{
			ToolbarHelper::checkin('mailinglists.checkin');
			ToolbarHelper::divider();
		}

		$bar = Toolbar::getInstance('toolbar');
		$bar->addButtonPath(JPATH_COMPONENT_ADMINISTRATOR . '/libraries/toolbar');

//		$manualLink = BwPostmanHTMLHelper::getManualLink('mailinglists');
//		$forumLink  = BwPostmanHTMLHelper::getForumLink();

//		$bar->appendButton('extlink', 'users', JText::_('COM_BWPOSTMAN_FORUM'), $forumLink);
//		$bar->appendButton('extlink', 'book', JText::_('COM_BWPOSTMAN_MANUAL'), $manualLink);

		ToolbarHelper::spacer();
	}

	/**
	 * Add the page title and toolbar for Joomla 4.
	 *
	 * @throws Exception
	 *
	 * @since       2.4.0
	 */
	protected function addToolbar()
	{
		// Get document object, set document title and add css
		$document = Factory::getDocument();
		$document->setTitle(JText::_('COM_BWPOSTMAN_MLS'));
		$document->addStyleSheet(JUri::root(true) . '/administrator/components/com_bwpostman/assets/css/bwpostman_backend.css');

		// Get the toolbar object instance
		$toolbar = Toolbar::getInstance('toolbar');

		// Set toolbar title
		ToolbarHelper::title(JText::_('COM_BWPOSTMAN_MLS'), 'list');

		// Set toolbar items for the page
		if ($this->permissions['mailinglist']['create'])
		{
			$toolbar->addNew('mailinglist.add');
		}

		if (BwPostmanHelper::canEdit('mailinglist'))
		{
			$toolbar->edit('mailinglist.edit');
		}

		if (BwPostmanHelper::canEditState('mailinglist'))
		{
			$dropdown = $toolbar->dropdownButton('status-group')
				->text('JTOOLBAR_CHANGE_STATUS')
				->toggleSplit(false)
				->icon('fa fa-globe')
				->buttonClass('btn btn-info')
				->listCheck(true);

			$childBar = $dropdown->getChildToolbar();
			$childBar->publish('mailinglists.publish')->listCheck(true);
			$childBar->unpublish('mailinglists.unpublish')->listCheck(true);

			if (BwPostmanHelper::canArchive('mailinglist'))
			{
				$childBar->archive('mailinglist.archive')->listCheck(true);
			}

			if (BwPostmanHelper::canEdit('mailinglist', 0) || BwPostmanHelper::canEditState('mailinglist', 0))
			{
				$childBar->checkin('mailinglists.checkin')->listCheck(true);
			}
		}

		$toolbar->addButtonPath(JPATH_COMPONENT_ADMINISTRATOR . '/libraries/toolbar');

//		$manualLink = BwPostmanHTMLHelper::getManualLink('mailinglists');
//		$forumLink  = BwPostmanHTMLHelper::getForumLink();

//		$toolbar->appendButton('ExtLink', 'users', JText::_('COM_BWPOSTMAN_FORUM'), $forumLink);
//		$toolbar->appendButton('ExtLink', 'book', JText::_('COM_BWPOSTMAN_MANUAL'), $manualLink);
	}
}
