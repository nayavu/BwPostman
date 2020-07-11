<?php
/**
 * BwPostman Newsletter Component
 *
 * BwPostman edit model for frontend.
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
use Joomla\CMS\Table\Table;
use Joomla\CMS\Form\Form;
use Joomla\CMS\Language\Multilanguage;

// Import MODEL object class
jimport('joomla.application.component.modeladmin');

require_once(JPATH_COMPONENT_ADMINISTRATOR . '/helpers/subscriberhelper.php');


/**
 * Class BwPostmanModelEdit
 *
 * @since       0.9.1
 */
class BwPostmanModelEdit extends JModelAdmin
{

	/**
	 * Subscriber ID
	 *
	 * @var integer
	 *
	 * @since       0.9.1
	 */
	private $id;

	/**
	 * User ID in subscriber-table
	 *
	 * @var integer
	 *
	 * @since       0.9.1
	 */
	private $userid;

	/**
	 * Subscriber data
	 *
	 * @var array
	 *
	 * @since       0.9.1
	 */
	private $data;

	/**
	 * Constructor
	 * Builds object, determines the subscriber ID and the viewlevel
	 *
	 * @throws Exception
	 *
	 * @since       0.9.1
	 */
	public function __construct()
	{
		parent::__construct();

		$user		= Factory::getUser();
		$id			= 0;

		if ($user->guest)
		{
			// Subscriber is guest
			$session				= Factory::getSession();
			$session_subscriberid	= $session->get('session_subscriberid');

			if(isset($session_subscriberid) && is_array($session_subscriberid))
			{
				// Session contains subscriber ID
				$id	= $session_subscriberid['id'];
			}
		}
		else
		{
			// Subscriber is user
			// Get the subscriber ID from the subscribers-table
			$id	= $this->getTable()->getSubscriberIdByUserId($user->get('id'));
		}

		$this->setData($id);
	}

	/**
	 * Returns a Table object, always creating it.
	 *
	 * @param	string      $type       The table type to instantiate
	 * @param	string	    $prefix     A prefix for the table class name. Optional.
	 * @param	array	    $config     Configuration array for model. Optional.
	 *
	 * @return	Table	A database object
	 *
	 * @since  1.0.1
	 */
	public function getTable($type = 'Subscribers', $prefix = 'BwPostmanTable', $config = array())
	{
		return Table::getInstance($type, $prefix, $config);
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @throws Exception
	 *
	 * @since	1.0.1
	 */
	protected function populateState()
	{
		$jinput	= Factory::getApplication()->input;

		// Load state from the request.
		$pk = $jinput->getInt('id');
		$this->setState('subscriber.id', $pk);

		$offset = $jinput->getUint('limitstart');
		$this->setState('list.offset', $offset);

		// TODO: Tune these values based on other permissions.
		$user	= Factory::getUser();
		if ((!$user->authorise('bwpm.edit.state', 'com_bwpostman')) &&  (!$user->authorise('bwpm.edit', 'com_bwpostman')))
		{
			$this->setState('filter.published', 1);
			$this->setState('filter.archived', 2);
		}

		$this->setState('filter.language', Multilanguage::isEnabled());
	}

	/**
	 * Method to get the record form.
	 *
	 * @param	array	$data		Data for the form.
	 * @param	boolean	$loadData	True if the form is to load its own data (default case), false if not.
	 *
	 * @return	mixed	A JForm object on success, false on failure
	 *
	 * @throws Exception
	 *
	 * @since	1.0.1
	 */
	public function getForm($data = array(), $loadData = true)
	{
		Form::addFieldPath('JPATH_COMPONENT/models/fields');

		// Get the form.
		$form = $this->loadForm('com_bwpostman.subscriber', 'subscriber', array('control' => 'jform', 'load_data' => $loadData));

		if (empty($form))
		{
			return false;
		}

		$jinput	= Factory::getApplication()->input;
		$id		= $jinput->get('id', 0);
		$user	= Factory::getUser();

		// Check for existing subscriber.
		// Modify the form based on Edit State access controls.
		if ($id != 0 && (!$user->authorise('bwpm.subscriber.edit.state', 'com_bwpostman.subscriber.' . (int) $id))
			|| ($id == 0 && !$user->authorise('bwpm.edit.state', 'com_bwpostman')))
		{
			// Disable fields for display.
			$form->setFieldAttribute('status', 'disabled', 'true');

			// Disable fields while saving.
			// The controller has already verified this is an subscriber you can edit.
			$form->setFieldAttribute('state', 'filter', 'unset');
		}

		// Check for required name
		if (!$form->getValue('name_field_obligation'))
		{
			$form->setFieldAttribute('name', 'required', false);
		}

		// Check for required first name
		if ($form->getValue('firstname_field_obligation'))
		{
			$form->setFieldAttribute('firstname', 'required', true);
		}

		BwPostmanSubscriberHelper::customizeSubscriberDataFields($form);

		return $form;
	}

	/**
	 * Method to reset the subscriber ID, view level and the subscriber data
	 *
	 * @access	public
	 *
	 * @param	int $id     subscriber ID
	 *
	 * @since       0.9.1
	 */
	protected function setData($id)
	{
		$this->id   = $id;
		$this->data = null;
	}

	/**
	 * Method to get subscriber data.
	 *
	 * @param	int     $pk 	The id of the subscriber.
	 *
	 * @return	mixed	Menu item data object on success, false on failure.
	 *
	 * @throws Exception
	 *
	 * @since       0.9.1
	 */
	public function getItem($pk = null)
	{
		$app	        = Factory::getApplication();
		$list_id_values = null;
		$_db	        = $this->_db;
		$query	        = $_db->getQuery(true);

		// Initialise variables.
		$pk = (!empty($pk)) ? $pk : (int) $app->getUserState('subscriber.id');

		// Get subscriber data from subscribers table
		$query->select('*');
		$query->from($_db->quoteName('#__bwpostman_subscribers'));
		$query->where($_db->quoteName('id') . ' = ' . (int) $pk);

		try
		{
			$_db->setQuery($query);
			$this->data = $_db->loadObject();
		}
		catch (RuntimeException $e)
		{
			$app->enqueueMessage($e->getMessage(), 'error');
		}

		// if no data get, take default values
		if (!is_object($this->data))
		{
			$this->data = BwPostmanSubscriberHelper::fillVoidSubscriber();
		}

		// set id and mailinglists property
		$this->id                 = $pk;
		$this->data->mailinglists = $this->getTable('Subscribers_Mailinglists')->getMailinglistIdsOfSubscriber($pk);

		return $this->data;
	}

	/**
	 * Method to get the mail address of a subscriber from the subscribers-table depending on the subscriber ID
	 *
	 * @param 	int		$id     subscriber ID
	 *
	 * @return 	string	user ID
	 *
	 * @throws Exception
	 *
	 * @since       0.9.1
	 */
	public function getEmailaddress($id)
	{
		return $this->getTable()->getEmailaddress($id);
	}

	/**
	 * Checks if an editlink exists in the subscribers-table
	 *
	 * @param 	string  $editlink   to edit the subscriber data
	 *
	 * @return 	int subscriber ID
	 *
	 * @throws Exception
	 *
	 * @since       0.9.1
	 */
	public function checkEditlink($editlink)
	{
		if ($editlink === null)
		{
			return 0;
		}

		$id = $this->getTable()->checkEditlink($editlink);

		if (empty($id))
		{
			$id = 0;
		}

		return (int)$id;
	}

	/**
	 * Method to save the subscriber data
	 *
	 * @access    public
	 *
	 * @param array $data associative array of data to store
	 *
	 * @return    Boolean
	 *
	 * @throws Exception
	 *
	 * @since     1.0.1
	 */
	public function save($data)
	{
		parent::save($data);

		// Get the subscriber id
		$subscriber_id = $data['id'];

		// Delete all mailinglist entries for the subscriber_id from newsletters_mailinglists-table
		$subsMlTable = $this->getTable('Subscribers_Mailinglists');
		$subsMlTable->deleteMailinglistsOfSubscriber($subscriber_id);

		// Store subscribed mailinglists in newsletters_mailinglists-table
		if (isset($data['mailinglists']))
		{
			if (($data['mailinglists']) != '') {
				$subsMlTable = $this->getTable('Subscribers_Mailinglists');
				$subsMlTable->storeMailinglistsOfSubscriber($subscriber_id, $data['mailinglists']);
			}
		}

		return true;
	}
}
