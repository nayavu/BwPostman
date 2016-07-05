<?php
/**
 * BwPostman Newsletter Component
 *
 * BwPostman single mailinglists view for backend.
 *
 * @version 2.0.0 bwpm
 * @package BwPostman-Admin
 * @author Romana Boldt
 * @copyright (C) 2012-2016 Boldt Webservice <forum@boldt-webservice.de>
 * @support http://www.boldt-webservice.de/forum/bwpostman.html
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
defined ('_JEXEC') or die ('Restricted access');

// Require helper class
require_once (JPATH_COMPONENT_ADMINISTRATOR.'/helpers/helper.php');

// Import VIEW object class
jimport('joomla.application.component.view');

/**
 * BwPostman Mailinglist View
 *
 * @package 	BwPostman-Admin
 * @subpackage 	Mailinglists
 */
class BwPostmanViewMailinglist extends JViewLegacy
{
	/**
	 * property to hold form data
	 *
	 * @var array   $form
	 */
	protected $form;

	/**
	 * property to hold selected item
	 *
	 * @var object   $item
	 */
	protected $item;

	/**
	 * property to hold state
	 *
	 * @var array|object  $state
	 */
	protected $state;

	/**
	 * property to hold can do properties
	 *
	 * @var array $canDo
	 */
	public $canDo;

	/**
	 * property to hold queue entries property
	 *
	 * @var boolean $queueEntries
	 */
	public $queueEntries;

	/**
	 * property to hold request url
	 *
	 * @var object  $request_url
	 */
	protected $request_url;

	/**
	 * property to hold template
	 *
	 * @var object $template
	 */
	public $template;


	/**
	 * Execute and display a template script.
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  mixed  A string if successful, otherwise a JError object.
	 */
	public function display($tpl = null)
	{
		$app		= JFactory::getApplication();
		$template	= $app->getTemplate();
		$uri		= JUri::getInstance();
		$uri_string	= str_replace('&', '&amp;', $uri->toString());

		$app->setUserState('com_bwpostman.edit.mailinglist.id', JFactory::getApplication()->input->getInt('id', 0));

		//check for queue entries
		$this->queueEntries	= BwPostmanHelper::checkQueueEntries();

		$this->form		= $this->get('Form');
		$this->item		= $this->get('Item');
		$this->state	= $this->get('State');
		$this->canDo	= BwPostmanHelper::getActions($this->item->id, 'mailinglist');

		// Save a reference into view
		$this->request_url	= $uri_string;
		$this->template		= $template;

		$this->addToolbar();

		// Call parent display
		parent::display($tpl);
	}

	/**
	 * Add the page title, styles and toolbar.
	 *
	 */
	protected function addToolbar()
	{
		JFactory::getApplication()->input->set('hidemainmenu', true);
		$uri		= JUri::getInstance();
		$userId		= JFactory::getUser()->get('id');

		// Get document object, set document title and add css
		$document = JFactory::getDocument();
		$document->setTitle(JText::_('COM_BWPOSTMAN_ML_DETAILS'));
		$document->addStyleSheet(JUri::root(true) . '/administrator/components/com_bwpostman/assets/css/bwpostman_backend.css');

		// Get the user browser --> if the user has msie load the ie-css to show the tabs in the correct way
		jimport('joomla.environment.browser');
		$browser = JBrowser::getInstance();
		$user_browser = $browser->getBrowser();

		if ($user_browser == 'msie')
		{
			$document->addStyleSheet(JUri::root(true) . '/administrator/components/com_bwpostman/assets/css/bwpostman_backend_ie.css');
		}

		// Set toolbar title depending on the state of the item: Is it a new item? --> Create; Is it an existing record? --> Edit
		$isNew = ($this->item->id < 1);

		// Set toolbar title and items
        $canDo		= BwPostmanHelper::getActions($this->item->id, 'mailinglist');
        $checkedOut	= !($this->item->checked_out == 0 || $this->item->checked_out == $userId);

		// For new records, check the create permission.
		if ($isNew && $canDo->get('bwpm.create'))
		{
			JToolbarHelper::save('mailinglist.save');
			JToolbarHelper::apply('mailinglist.apply');
			JToolbarHelper::cancel('mailinglist.cancel');
			JToolbarHelper::title(JText::_('COM_BWPOSTMAN_ML_DETAILS').': <small>[ ' . JText::_('NEW').' ]</small>', 'plus');
		}
		else
		{
			// Can't save the record if it's checked out.
			if (!$checkedOut)
			{
				// Since it's an existing record, check the edit permission, or fall back to edit own if the owner.
				if ($canDo->get('bwpm.edit') || ($canDo->get('bwpm.edit.own') && $this->item->created_by == $userId))
				{
					JToolbarHelper::save('mailinglist.save');
					JToolbarHelper::apply('mailinglist.apply');
				}
			}
			// Rename the cancel button for existing items
			JToolbarHelper::cancel('mailinglist.cancel', 'JTOOLBAR_CLOSE');
			JToolbarHelper::title(JText::_('COM_BWPOSTMAN_ML_DETAILS').': <small>[ ' . JText::_('EDIT').' ]</small>', 'edit');
		}

		$backlink 	= $_SERVER['HTTP_REFERER'];
		$siteURL 	= $uri->base();

		// If we came from the cover page we will show a back-button
		if ($backlink == $siteURL.'index.php?option=com_bwpostman')
		{
			JToolbarHelper::spacer();
			JToolbarHelper::divider();
			JToolbarHelper::spacer();
			JToolbarHelper::back();
		}
		JToolbarHelper::divider();
		JToolbarHelper::spacer();
		JToolbarHelper::help(JText::_("COM_BWPOSTMAN_FORUM"), false, 'http://www.boldt-webservice.de/forum/bwpostman.html');
		JToolbarHelper::spacer();
	}
}
