<?php
/**
 * BwPostman Newsletter Component
 *
 * BwPostman subscribers table for backend.
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

use Joomla\CMS\Table\Table;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Mail\MailHelper;
use Joomla\CMS\Uri\Uri;
use Joomla\Registry\Registry;
use Joomla\Filter\InputFilter;
use Joomla\CMS\Application\ApplicationHelper;
use Joomla\CMS\User\UserHelper;

// needed for plugin support!!!!
require_once(JPATH_ADMINISTRATOR . '/components/com_bwpostman/helpers/helper.php');

/**
 * #__bwpostman_subscribers table handler
 * Table for storing the subscriber data
 *
 * @package		BwPostman-Admin
 * @subpackage	Subscribers
 *
 * @since       0.9.1
 */
class BwPostmanTableSubscribers extends JTable
{
	/**
	 * @var int Primary Key
	 *
	 * @since       0.9.1
	 */
	public $id = 0;

	/**
	 * @var int asset_id
	 *
	 * @since       1.0.1
	 */
	public $asset_id = null;

	/**
	 * @var int User-ID --> 0 = subscriber is not registered for the website, another ID = Subscriber is registered for
	 *      the website (ID comes from users-table)
	 *
	 * @since       0.9.1
	 */
	public $user_id = null;

	/**
	 * @var string Name
	 *
	 * @since       0.9.1
	 */
	public $name = null;

	/**
	 * @var string Firstname
	 *
	 * @since       0.9.1
	 */
	public $firstname = null;

	/**
	 * @var string Email
	 *
	 * @since       0.9.1
	 */
	public $email = null;

	/**
	 * @var int newsletter format --> 0 = text, 1 = html
	 *
	 * @since       0.9.1
	 */
	public $emailformat = null;

	/**
	 * @var int gender --> 0 = male, 1 = female NULL = unknown
	 *
	 * @since       0.9.1
	 */
	public $gender = null;

	/**
	 * @var string special field
	 *
	 * @since       0.9.1
	 */
	public $special = null;

	/**
	 * @var int Subscriber status --> 0 = not confirmed, 1 = confirmed, 9 = test-recipient
	 *
	 * @since       0.9.1
	 */
	public $status = 0;

	/**
	 * @var string Activation code for the subscription
	 *
	 * @since       0.9.1
	 */
	public $activation = null;

	/**
	 * @var string Code for editing the subscription in the frontend
	 *
	 * @since       0.9.1
	 */
	public $editlink = null;

	/**
	 * @var int access level/view level --> 1 = Public, 2 = Registered, 3 = Special, >3 = user defined viewlevels
	 *
	 * @since       0.9.1
	 */
	public $access = 1;

	/**
	 * @var datetime Registration date
	 *
	 * @since       0.9.1
	 */
	public $registration_date = null;

	/**
	 * @var int ID --> 0 = subscriber registered himself, another ID = administrator from users-table
	 *
	 * @since       0.9.1
	 */
	public $registered_by = null;

	/**
	 * @var string Registration IP
	 *
	 * @since       0.9.1
	 */
	public $registration_ip = null;

	/**
	 * @var datetime Confirmation date of the subscription
	 *
	 * @since       0.9.1
	 */
	public $confirmation_date = null;

	/**
	 * @var int ID --> -1 = account is not confirmed, 0 = subscriber confirmed the subscription by himself, another ID
	 *      = administrator from users-table
	 *
	 * @since       0.9.1
	 */
	public $confirmed_by = null;

	/**
	 * @var string Confirmation IP
	 *
	 * @since       0.9.1
	 */
	public $confirmation_ip = null;

	/**
	 * @var datetime last modification date of the subscriber
	 *
	 * @since       0.9.1
	 */
	public $modified_time = '0000-00-00 00:00:00';

	/**
	 * @var int user ID
	 *
	 * @since       0.9.1
	 */
	public $modified_by = 0;

	/**
	 * @var int Checked-out owner
	 *
	 * @since       0.9.1
	 */
	public $checked_out = 0;

	/**
	 * @var datetime Checked-out time
	 *
	 * @since       0.9.1
	 */
	public $checked_out_time = '0000-00-00 00:00:00';

	/**
	 * @var int Archive-flag --> 0 = not archived, 1 = archived
	 *
	 * @since       0.9.1
	 */
	public $archive_flag = 0;

	/**
	 * @var datetime Archive-date
	 *
	 * @since       0.9.1
	 */
	public $archive_date = null;

	/**
	 * @var int ID --> -1 = account is not archived, 0 = account is archived by the subscriber himself, another ID =
	 *      account is archived by an administrator
	 *
	 * @since       0.9.1
	 */
	public $archived_by = -1;

	/**
	 * Constructor
	 *
	 * @param 	JDatabaseDriver  $db Database object
	 *
	 * @since       0.9.1
	 */
	public function __construct(& $db)
	{
		parent::__construct('#__bwpostman_subscribers', 'id', $db);
	}

	/**
	 * Alias function
	 *
	 * @return  string
	 *
	 * @since   1.0.1
	 */
	public function getAssetName()
	{
		return self::_getAssetName();
	}

	/**
	 * Alias function
	 *
	 * @return  string
	 *
	 * @since   1.0.1
	 */
	public function getAssetTitle()
	{
		return self::_getAssetTitle();
	}

	/**
	 * Alias function
	 *
	 * @return  string
	 *
	 * @throws Exception
	 *
	 * @since   1.0.1
	 */
	public function getAssetParentId()
	{
		return self::_getAssetParentId();
	}

	/**
	 * Method to compute the default name of the asset.
	 * The default name is in the form table_name.id
	 * where id is the value of the primary key of the table.
	 *
	 * @return  string
	 *
	 * @since   1.0.1
	 */
	protected function _getAssetName()
	{
		$k = $this->_tbl_key;
		return 'com_bwpostman.subscriber.' . (int) $this->$k;
	}

	/**
	 * Method to return the title to use for the asset table.
	 *
	 * @return  string
	 *
	 * @since   1.0.1
	 */
	protected function _getAssetTitle()
	{
		return $this->name;
	}

	/**
	 * Method to get the parent asset id for the record
	 *
	 * @param   Table   $table  A Table object (optional) for the asset parent
	 * @param   integer  $id     The id (optional) of the content.
	 *
	 * @return  integer
	 *
	 * @since   11.1
	 */
	protected function _getAssetParentId(Table $table = null, $id = null)
	{
		$asset = Table::getInstance('Asset');
		$asset->loadByName('com_bwpostman.subscriber');
		return $asset->id;
	}

	/**
	 * Overloaded bind function
	 *
	 * @access public
	 *
	 * @param array|object  $data       Named array
	 * @param string        $ignore     Space separated list of fields not to bind
	 *
	 * @throws  BwException
	 *
	 * @return boolean
	 *
	 * @since       0.9.1
	 */
	public function bind($data, $ignore='')
	{
		// Bind the rules.
		if (is_object($data))
		{
			if (property_exists($data, 'rules') && is_array($data->rules))
			{
				$rules = new JAccessRules($data->rules);
				$this->setRules($rules);
			}
		}
		elseif (is_array($data))
		{
			if (array_key_exists('rules', $data) && is_array($data['rules']))
			{
				$rules = new JAccessRules($data['rules']);
				$this->setRules($rules);
			}
		}
		else
		{
			throw new BwException(Text::sprintf('JLIB_DATABASE_ERROR_BIND_FAILED_INVALID_SOURCE_ARGUMENT', get_class($this)));
		}

		// Cast properties
		$this->id = (int) $this->id;

		return parent::bind($data, $ignore);
	}

	/**
	 * Overloaded check method to ensure data integrity of a subscriber
	 *
	 * @access public

	 * @return boolean True on success
	 *
	 * @throws Exception
	 *
	 * @since       0.9.1
	 */
	public function check()
	{
		//Initialize
		jimport('joomla.mail.helper');

		$params = ComponentHelper::getParams('com_bwpostman');
		$app    = Factory::getApplication();
		$import = $app->getUserState('com_bwpostman.subscriber.import', false);
		$xtest  = $app->getUserState('com_bwpostman.subscriber.new_test', $this->status);
		$data   = $app->getUserState('com_bwpostman.subscriber.register.data', array());

		if ($app->isClient('site') && !empty($data['mod_id']))
		{
			// if data from module, we need module params
			// we can't use JoomlaModuleHelper, because module isn't shown on frontend
			$params = BwPostmanSubscriberHelper::getModParams((int)$data['mod_id']);
			$module_params  = new Registry($params->params);

			if ($module_params->get('com_params') == 0)
			{
				$params = $module_params;
			}
		}

		$session = Factory::getSession();
		$err     = $session->get('session_error');
		$fault   = false;

		$isTester   = false;
		$format_txt	= array(0 => 'Text', 1 => 'HTML');

		if ($xtest == '9')
		{
			$isTester = true;
		}

		if ($import && $this->status == '9')
		{
			$isTester = true;
		}

		// Cleanup all vulnerable values
		$filter = new InputFilter(array(), array(), 0, 0);

		$this->id                = $filter->clean($this->id, 'UINT');
		$this->asset_id          = $filter->clean($this->asset_id, 'UINT');
		$this->name              = $filter->clean($this->name);
		$this->firstname         = $filter->clean($this->firstname);
		$this->email             = $filter->clean($this->email);
		$this->emailformat       = $filter->clean($this->emailformat, 'BOOLEAN');
		$this->gender            = $filter->clean($this->gender, 'UINT');
		$this->special           = $filter->clean($this->special);
		$this->status            = $filter->clean($this->status, 'UINT');
		$this->activation        = $filter->clean($this->activation);
		$this->editlink          = $filter->clean($this->editlink);
		$this->access            = $filter->clean($this->access, 'UINT');
		$this->registration_date = $filter->clean($this->registration_date);
		$this->registered_by     = $filter->clean($this->registered_by, 'INT');
		$this->registration_ip   = $filter->clean($this->registration_ip);
		$this->confirmation_date = $filter->clean($this->confirmation_date);
		$this->confirmed_by      = $filter->clean($this->confirmed_by, 'INT');
		$this->modified_by       = $filter->clean($this->modified_by, 'INT');
		$this->confirmation_ip   = $filter->clean($this->confirmation_ip);
		$this->modified_time     = $filter->clean($this->modified_time);
		$this->checked_out       = $filter->clean($this->checked_out, 'INT');
		$this->checked_out_time  = $filter->clean($this->checked_out_time);
		$this->archive_flag      = $filter->clean($this->archive_flag, 'UINT');
		$this->archive_date      = $filter->clean($this->archive_date);
		$this->archived_by       = $filter->clean($this->archived_by, 'INT');

		if (!$import)
		{
			// Check for valid first name
			if ($params->get('firstname_field_obligation'))
			{
				if (trim($this->firstname) === '')
				{
					$app->enqueueMessage(Text::_('COM_BWPOSTMAN_SUB_ERROR_FIRSTNAME'), 'error');
					$fault	= true;
				}
			}

			// Check for valid name
			if ($params->get('name_field_obligation'))
			{
				if (trim($this->name) === '')
				{
					$app->enqueueMessage(Text::_('COM_BWPOSTMAN_SUB_ERROR_NAME'), 'error');
					$fault	= true;
				}
			}

			// Check for valid additional field
			if ($params->get('special_field_obligation'))
			{
				if (trim($this->special) === '')
				{
					$app->enqueueMessage(Text::sprintf('COM_BWPOSTMAN_SUB_ERROR_SPECIAL', $params->get('special_label') != '' ? Text::_($params->get('special_label')) : Text::_('COM_BWPOSTMAN_SPECIAL')), 'error');
					$fault	= true;
				}
			}
		}

		// Check for valid email address
		if (trim($this->email) === '')
		{
			$app->enqueueMessage(Text::_('COM_BWPOSTMAN_SUB_ERROR_EMAIL'), 'error');
			$fault	= true;
		}
		// If there is a email address check if the address is valid
		elseif (!MailHelper::isEmailAddress(trim($this->email)))
		{
			$app->enqueueMessage(Text::sprintf('COM_BWPOSTMAN_SUB_ERROR_EMAIL_INVALID', $this->email), 'error');
			$fault	= true;
		}

		if ($app->isClient('site') && !$this->id)
		{
			// Check if any mailinglist is checked
			if(!$data['mailinglists'])
			{
				$app->enqueueMessage(Text::_('COM_BWPOSTMAN_ERROR_LISTCHECK'), 'error');
				$fault	= true;
			}

			// agreecheck
			if ($params->get('disclaimer') == 1)
			{
				if(!isset($data['agreecheck']) || (isset($data['mod_id']) && $fault))
				{
					$app->enqueueMessage(Text::_('COM_BWPOSTMAN_ERROR_AGREECHECK'), 'error');
					$fault	= true;
				}
			}

			// Spamcheck 1
			// Set error message if a not visible (top: -5000px) input field is not empty
			if($data['falle'] !== '')
			{
				// input wrong - set error
				$app->enqueueMessage(Text::_('COM_BWPOSTMAN_ERROR_SPAMCHECK'), 'error');
				$fault	= true;
			}

			// Spamcheck 2
			// Set error message if check of a dynamic time variable failed
			if(!isset($data['bwp-' . BwPostmanHelper::getCaptcha(1)]) && !isset($data['bwp-' . BwPostmanHelper::getCaptcha(2)]))
			{
				// input wrong - set error
				$app->enqueueMessage(Text::_('COM_BWPOSTMAN_ERROR_SPAMCHECK2'), 'error');
				$fault	= true;
			}

			// Captcha check 1
			// Set error message if captcha test failed
			if ($params->get('use_captcha') == 1)
			{
				// start check
				if(trim($data['stringQuestion']) !== trim($params->get('security_answer')) || (isset($data['mod_id']) && $fault))
				{
					// input wrong - set error
					$app->enqueueMessage(Text::_('COM_BWPOSTMAN_ERROR_CAPTCHA'), 'error');
					$fault	= true;
				}
			}

			// Captcha check 2
			if ($params->get('use_captcha') == 2)
			{
				// Temp folder of captcha-images
				$captchaDir = JPATH_COMPONENT_SITE . '/assets/capimgdir/';
				// del old images after ? minutes
				$delFile = 10;
				// start check
				$resultCaptcha = BwPostmanHelper::CheckCaptcha($data['codeCaptcha'], $data['stringCaptcha'], $captchaDir, $delFile);
				if(!$resultCaptcha || (isset($data['mod_id']) && $fault))
				{
					// input wrong - set error
					$app->enqueueMessage(Text::_('COM_BWPOSTMAN_ERROR_CAPTCHA'), 'error');
					$fault	= true;
				}
			}
		}

		if ($fault)
		{
			$app->setUserState('com_bwpostman.edit.subscriber.data', $data);
			$session->set('session_error', $err);
			return false;
		}

		// Check for existing email
		$xids = $this->getSubscriberIdByEmail($this->email, $isTester);

		//Test-recipient may have multiple entries, but may not be archived
		if ($isTester)
		{
			foreach ($xids AS $xid)
			{
				$xid = intval($xid);

				if ($xid && $xid !== intval($this->id))
				{
					$testrecipient = $this->getSubscriberNewsletterData($xid);

					// Account with this emailformat already exists
					if (($testrecipient->archive_flag === 0) && ($testrecipient->emailformat === $this->emailformat))
					{
						$app->enqueueMessage(
							Text::sprintf(
								'COM_BWPOSTMAN_TEST_ERROR_ACCOUNTEXISTS',
								$this->email,
								$format_txt[$this->emailformat],
								$testrecipient->id
							),
							'error'
						);
						$err['err_code'] = 409;
						$err['err_msg'] = Text::sprintf(
							'COM_BWPOSTMAN_TEST_ERROR_ACCOUNTEXISTS',
							$this->email,
							$format_txt[$this->emailformat],
							$testrecipient->id
						);
						$err['err_id'] = $xid;
						$app->setUserState('com_bwpostman.subscriber.register.error', $err);
						$app->enqueueMessage(
							Text::sprintf(
								'COM_BWPOSTMAN_TEST_ERROR_ACCOUNTEXISTS',
								$this->email,
								$format_txt[$this->emailformat],
								$testrecipient->id
							),
							'error'
						);
						$session->set('session_error', $err);
						return false;
					}

					// Account is archived
					if (($testrecipient->archive_flag === 1) && ($testrecipient->emailformat === $this->emailformat))
					{
						$app->enqueueMessage(
							Text::sprintf(
								'COM_BWPOSTMAN_TEST_ERROR_ACCOUNTARCHIVED',
								$this->email,
								$format_txt[$this->emailformat],
								$testrecipient->id
							),
							'error'
						);
						$err['err_code'] = 410;
						$err['err_msg'] = Text::sprintf(
							'COM_BWPOSTMAN_TEST_ERROR_ACCOUNTARCHIVED',
							$this->email,
							$format_txt[$this->emailformat],
							$testrecipient->id
						);
						$err['err_id'] = $xid;
						$app->setUserState('com_bwpostman.subscriber.register.error', $err);
						$app->enqueueMessage(
							Text::sprintf(
								'COM_BWPOSTMAN_TEST_ERROR_ACCOUNTARCHIVED',
								$this->email,
								$format_txt[$this->emailformat],
								$testrecipient->id
							),
							'error'
						);
						$session->set('session_error', $err);
						return false;
					}
				}
			}
		}
		//Subscriber may only have one subscription, that ist not archived of blocked
		else
		{
			if ($xids && $xids !== intval($this->id))
			{
				$subscriber = $this->getSubscriberNewsletterData($xids);

				// Account is blocked by system/administrator
				if (($subscriber->archive_flag === 1) && ($subscriber->archived_by > 0))
				{
					$err['err_code']	= 405;
					$err['err_msg']		= Text::sprintf('COM_BWPOSTMAN_SUB_ERROR_DB_ACCOUNTBLOCKED_BY_SYSTEM', $this->email, $xids);
					$err['err_id']		= $xids;
					$err['err_email']	= $this->email;
					$app->setUserState('com_bwpostman.subscriber.register.error', $err);
					$session->set('session_error', $err);
					return false;
				}

				// Account is not activated
				if ($subscriber->status == 0)
				{
					$err['err_code'] = 406;
					$err['err_msg']	= Text::sprintf('COM_BWPOSTMAN_SUB_ERROR_DB_ACCOUNTNOTACTIVATED', $this->email, $xids);
					$err['err_id'] = $xids;
					$err['err_email']	= $this->email;

					$app->setUserState('com_bwpostman.subscriber.register.error', $err);
					$session->set('session_error', $err);

					return false;
				}

				// Account already exists
				if (($subscriber->status == 1) && ($subscriber->archive_flag != 1))
				{
					$link = Uri::base() . 'index.php?option=com_bwpostman&view=edit';
					//@ToDo: With the following routing with SEO activated don't work
					$err['err_code'] = 407;
					$err['err_msg'] = Text::sprintf(
						'COM_BWPOSTMAN_SUB_ERROR_DB_ACCOUNTEXISTS',
						$this->email,
						$link
					);
					$err['err_id'] = $xids;
					$err['err_email']	= $this->email;
					$app->setUserState('com_bwpostman.subscriber.register.error', $err);
					$session->set('session_error', $err);
					$this->setError(Text::sprintf('COM_BWPOSTMAN_SUB_ERROR_DB_ACCOUNTEXISTS', $this->email,  $xids));
					return false;
				}
			}
		}

		return true;
	}

	/**
	 * Method to check if test-recipients exists
	 *
	 * @return 	boolean
	 *
	 * @throws Exception
	 *
	 * @since
	 */
	public function checkForTestrecipients()
	{
		$testrecipients = null;

		$db	   = $this->_db;
		$query = $db->getQuery(true);

		$query->select('COUNT(' . $db->quoteName('id') . ')');
		$query->from($db->quoteName($this->_tbl));
		$query->where($db->quoteName('status') . ' = ' . 9);
		$query->where($db->quoteName('archive_flag') . ' = ' . 0);

		$db->setQuery($query);

		try
		{
			$testrecipients = $db->loadResult();
		}
		catch (RuntimeException $e)
		{
			Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
		}

		if (!count($testrecipients))
		{
			return false;
		}

		return true;
	}

	/**
	 * Overloaded load method to get all test-recipients when a newsletter shall be sent to them
	 *
	 * @access	public

	 * @return 	array
	 *
	 * @throws Exception
	 *
	 * @since
	 */
	public function loadTestrecipients()
	{
		$result = array();
		$this->reset();
		$db	= $this->_db;
		$query	= $db->getQuery(true);

		$query->select('*');
		$query->from($db->quoteName($this->_tbl));
		$query->where($db->quoteName('status') . ' = ' . 9);
		$query->where($db->quoteName('archive_flag') . ' = ' . 0);

		$db->setQuery($query);

		try
		{
			$result = $db->loadObjectList();
		}
		catch (RuntimeException $e)
		{
			Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
		}

		return $result;
	}

	/**
	 * Overridden Table::store to set created/modified and user id.
	 *
	 * @param   boolean  $updateNulls  True to update fields even if they are null.
	 *
	 * @return  boolean  True on success.
	 *
	 * @throws Exception
	 *
	 * @since   1.0.1
	 */
	public function store($updateNulls = false)
	{
		$app  = Factory::getApplication();

		if ($this->gender == '')
		{
			$this->gender = 2;
		}

		if ($this->id)
		{
			// Existing subscriber
			$this->modified_time = Factory::getDate()->toSql();
			$this->modified_by   = Factory::getUser()->get('id');
		}

		if ($this->confirmation_date == 0)
		{
			$this->confirmation_date = $this->_db->getNullDate();
		}

		$res = parent::store($updateNulls);

		if ($res !== true)
		{
			$app->enqueueMessage($this->getError());
		}

		$app->setUserState('com_bwpostman.subscriber.id', $this->id);
		$app->setUserState('subscriber.id', $this->id);

		return $res;
	}

	/**
	 * Overridden Table::delete
	 *
	 * @param	mixed	$pk     An optional primary key value to delete.  If not set the
	 *				        	instance property value is used.
	 *
	 * @return	boolean	True on success.
	 *
	 * @since   1.0.1
	 */
	public function delete($pk = null)
	{
		return parent::delete($pk);
	}

	/**
	 * Returns the identity (primary key) value of this record
	 *
	 * @return  mixed
	 *
	 * @since  3.0.0
	 */
	public function getId()
	{
		$key = $this->getKeyName();

		return $this->$key;
	}

	/**
	 * Check if the record has a property (applying a column alias if it exists)
	 *
	 * @param string $key key to be checked
	 *
	 * @return  boolean
	 *
	 * @since   3.0.0
	 */
	public function hasField($key)
	{
		$key = $this->getColumnAlias($key);

		return property_exists($this, $key);
	}

	/**
	 * Method to check if a subscriber is archived
	 *
	 * @param integer $subsId   ID of the subscriber to check
	 *
	 * @return 	object|false
	 *
	 * @throws Exception
	 *
	 * @since 3.0.0
	 */
	public function getSubscriberNewsletterData($subsId)
	{
		$result = false;
		$this->reset();

		$db    = $this->_db;
		$query = $db->getQuery(true);

		$query->select($db->quoteName('id'));
		$query->select($db->quoteName('editlink'));
		$query->select($db->quoteName('archive_flag'));
		$query->select($db->quoteName('archived_by'));
		$query->select($db->quoteName('status'));
		$query->select($db->quoteName('emailformat'));
		$query->from($this->_tbl);
		$query->where($db->quoteName('id') . ' = ' . $db->quote((int) $subsId));

		$db->setQuery($query);

		try
		{
			$result = $db->loadObject();
		}
		catch (RuntimeException $e)
		{
			Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
		}

		return $result;
	}

	/**
	 * Method to check by user ID if a user has a newsletter account (user = no guest)
	 *
	 * Returns 0 if user has no newsletter subscription
	 *
	 * @param    int $uid user ID
	 *
	 * @return    int $id     subscriber ID
	 *
	 * @throws Exception
	 *
	 * @since       3.0.0 (here, before since 2.0.0 at subscriber helper)
	 */
	public function getSubscriberIdByUserId($uid)
	{
		$db    = $this->_db;
		$query = $db->getQuery(true);

		$query->select($db->quoteName('id'));
		$query->from($db->quoteName($this->_tbl));
		$query->where($db->quoteName('user_id') . ' = ' . (int) $uid);
		$query->where($db->quoteName('status') . ' != ' . 9);

		try
		{
			$db->setQuery($query);
			$id = $db->loadResult();
		}
		catch (RuntimeException $e)
		{
			Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
		}

		if (empty($id))
		{
			$id = 0;
		}

		return (int)$id;
	}

	/**
	 * Method to check if an email address exists in the subscribers-table
	 *
	 * @param string  $email    subscriber email
	 * @param boolean $isTester is tester?
	 *
	 * @return 	int|array     $id     subscriber ID
	 *
	 * @throws Exception
	 *
	 * @since       0.9.1
	 */
	public function getSubscriberIdByEmail($email, $isTester = false)
	{
		$db    = $this->_db;
		$query = $db->getQuery(true);
		$id    = 0;

		// Sanitize values
		$email = $db->escape($email);

		$query->select($db->quoteName('id'));
		$query->from($db->quoteName($this->_tbl));
		$query->where($db->quoteName('email') . ' = ' . $db->quote($email));
		if (!$isTester)
		{
			$query->where($db->quoteName('status') . ' != ' . 9);
		}
		$db->setQuery($query);

		try
		{
			if (!$isTester)
			{
				$id = intval($this->_db->loadResult());
			}
			else
			{
				$id = $this->_db->loadColumn();
			}
		}
		catch (RuntimeException $e)
		{
			Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
		}

		return (int)$id;
	}

	/**
	 * Method to get the data of a subscriber who has a newsletter account from the subscribers-table
	 * because we need to know if his account is okay or archived or not activated (user = no guest)
	 *
	 * @access    public
	 *
	 * @param    int $id subscriber ID
	 *
	 * @return    object  $subscriber subscriber object
	 *
	 * @throws Exception
	 *
	 * @since       3.0.0 (here, before since 2.0.0 at subscriber helper)
	 */
	public function getSubscriberState($id)
	{
		$subscriber = null;
		$db         = $this->_db;
		$query      = $db->getQuery(true);

		$query->select('*');
		$query->from($db->quoteName($this->_tbl));
		$query->where($db->quoteName('id') . ' = ' . (int) $id);
		$query->where($db->quoteName('status') . ' != ' . 9);

		try
		{
			$db->setQuery($query);
			$subscriber = $db->loadObject();
		}
		catch (RuntimeException $e)
		{
			Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
		}

		return $subscriber;
	}

	/**
	 * Method to get the user ID of a subscriber from the subscribers-table depending on the subscriber ID
	 * --> is needed for the constructor
	 *
	 * @param 	int     $id     subscriber ID
	 *
	 * @return 	int user ID
	 *
	 * @throws Exception
	 *
	 * @since       3.0.0 (here, before since 2.0.0 at subscriber helper)
	 */
	public function getUserIdOfSubscriber($id)
	{
		$user_id    = null;
		$db	    = $this->_db;
		$query	    = $db->getQuery(true);

		$query->select($db->quoteName('user_id'));
		$query->from($db->quoteName($this->_tbl));
		$query->where($db->quoteName('id') . ' = ' . (int) $id);
		$query->where($db->quoteName('status') . ' != ' . 9);

		try
		{
			$db->setQuery($query);
			$user_id = $db->loadResult();
		}
		catch (RuntimeException $e)
		{
			Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
		}

		if (is_null($user_id))
		{
			$user_id = 0;
		}

		return $user_id;
	}

	/**
	 * Method to get the number of subscribers depending on provided sending and archive state
	 *
	 * @param boolean $tester
	 * @param boolean $archived
	 *
	 * @return 	integer|boolean number of subscribers or false
	 *
	 * @throws Exception
	 *
	 * @since       3.0.0 (here, before since 2.3.0 at subscriber helper)
	 */
	public function getNbrOfSubscribers($tester, $archived)
	{
		$archiveFlag    = 0;
		$statusOperator = "!=";

		if ($tester)
		{
			$statusOperator = "=";
		}

		if ($archived)
		{
			$archiveFlag = 1;
		}

		$db    = $this->_db;
		$query = $db->getQuery(true);

		$query->select('COUNT(*)');
		$query->from($db->quoteName($this->_tbl));

		if (!$archived)
		{
			$query->where($db->quoteName('status') . $statusOperator . 9);
		}

		$query->where($db->quoteName('archive_flag') . ' = ' . $archiveFlag);

		$db->setQuery($query);

		try
		{
			return $db->loadResult();
		}
		catch (RuntimeException $e)
		{
			Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
		}
		return false;
	}

	/**
	 * Method to create the editlink and check if the string does not exist twice or more
	 *
	 * @return string   $editlink
	 *
	 * @throws Exception
	 *
	 * @since 3.0.0 here
	 */
	public function getEditlink()
	{
		$db              = $this->_db;
		$newEditlink     = "";
		$editlinkMatches = true;

		while ($editlinkMatches)
		{
			$newEditlink = ApplicationHelper::getHash(UserHelper::genRandomPassword());

			$query = $db->getQuery(true);

			$query->select($db->quoteName('editlink'));
			$query->from($db->quoteName($this->_tbl));
			$query->where($db->quoteName('editlink') . ' = ' . $db->quote($newEditlink));

			$db->setQuery($query);

			try
			{
				$editlink = $db->loadResult();
			}
			catch (RuntimeException $e)
			{
				Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
				return false;
			}
			if ($editlink !== $newEditlink)
			{
				$editlinkMatches = false;
			}
		}

		return $newEditlink;
	}

	/**
	 * Method to create the activation and check if the string does not exist twice or more
	 *
	 * @return string   $activation
	 *
	 * @throws Exception
	 *
	 * @since 3.0.0 here
	 */
	public function createActivation()
	{
		$db    = $this->_db;
		$query = $db->getQuery(true);

		$newActivation     = "";
		$activationMatches = true;

		while ($activationMatches)
		{
			$newActivation = ApplicationHelper::getHash(UserHelper::genRandomPassword());

			$query->select($db->quoteName('activation'));
			$query->from($db->quoteName($this->_tbl));
			$query->where($db->quoteName('activation') . ' = ' . $db->quote($newActivation));

			$db->setQuery($query);

			try
			{
				$activation = $db->loadResult();
			}
			catch (RuntimeException $e)
			{
				Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
				return false;
			}

			if ($activation !== $newActivation)
			{
				$activationMatches = false;
			}
		}

		return $newActivation;
	}

	/**
	 * Method to get the complete subscriber data by email
	 *
	 * @param   integer      $id
	 *
	 * @return  object
	 *
	 * @throws Exception
	 *
	 * @since   3.0.0
	 */
	public function getSingleSubscriberData($id)
	{
		$subscriber = null;

		$db    = $this->_db;
		$query = $db->getQuery(true);

		$query->select('*');
		$query->from($db->quoteName($this->_tbl));
		$query->where($db->quoteName('id') . ' = ' . (int) $id);

		$db->setQuery($query);

		try
		{
			$db->setQuery($query);
			$subscriber = $db->loadObject();
		}
		catch (RuntimeException $e)
		{
			Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
		}

		return $subscriber;
	}

	/**
	 * Method to get the complete subscriber data by email
	 *
	 * @param   array      $values
	 *
	 * @return  object
	 *
	 * @since   3.0.0 (here)
	 */
	public function getSubscriberDataByEmail($values)
	{
		$db    = $this->_db;
		$query = $db->getQuery(true);

		// Sanitize values
		$email = $db->escape($values['email']);

		$query->select('*');
		$query->from($db->quoteName($this->_tbl));
		$query->where($db->quoteName('email') . ' = ' . $db->quote($email));

		if ((int)$values['status'] === 9)
		{
			$query->where($db->quoteName('emailformat') . ' = ' . $db->quote((int)$values['emailformat']));
			$query->where($db->quoteName('status') . ' = ' . 9);
		}
		else
		{
			$query->where($db->quoteName('status') . ' IN (0, 1)');
		}

		$db->setQuery($query);

		return $db->loadObject();
	}

	/**
	 * Method to get the subscriber activation data
	 *
	 * @param 	string  $activation     activation code for the newsletter account
	 *
	 * @return  object
	 *
	 * @throws Exception
	 *
	 * @since   3.0.0
	 */
	public function getSubscriberActivationData($activation)
	{
		$subscriber = null;

		$db    = $this->_db;
		$query = $db->getQuery(true);

		$query->select($db->quoteName('id'));
		$query->select($db->quoteName('email'));
		$query->select($db->quoteName('editlink'));
		$query->from($db->quoteName($this->_tbl));
		$query->where($db->quoteName('activation') . ' = ' . $db->quote($activation));
		$query->where($db->quoteName('status') . ' = ' . (int) 0);
		$query->where($db->quoteName('confirmation_date') . ' = ' . $db->quote($db->getNullDate()));
		$query->where($db->quoteName('confirmed_by') . ' = ' . (int) -1);
		$query->where($db->quoteName('archive_flag') . ' = ' . (int) 0);
		$query->where($db->quoteName('archived_by') . ' = ' . (int) -1);
		$db->setQuery($query);

		try
		{
			$subscriber = $db->loadObject();
		}
		catch (RuntimeException $e)
		{
			Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
		}

		return $subscriber;
	}

	/**
	 * Method to store the subscriber activation
	 *
	 * @param integer  $id              id of the subscriber to store activation
	 * @param string   $activation_ip   IP used for activation
	 *
	 * @return  boolean true on success
	 *
	 * @throws Exception
	 *
	 * @since   3.0.0
	 */
	public function storeSubscriberActivation($id, $activation_ip)
	{
		$date = Factory::getDate();
		$time = $date->toSql();

		$db    = $this->_db;
		$query = $db->getQuery(true);

		$query->update($db->quoteName($this->_tbl));
		$query->set($db->quoteName('status') . ' = ' . 1);
		$query->set($db->quoteName('activation') . ' = ' . $db->quote(''));
		$query->set($db->quoteName('confirmation_date') . ' = ' . $db->quote($time, false));
		$query->set($db->quoteName('confirmed_by') . ' = ' . 0);
		$query->set($db->quoteName('confirmation_ip') . ' = ' . $db->quote($activation_ip));
		$query->where($db->quoteName('id') . ' = ' . (int) $id);
		$db->setQuery($query);

		try
		{
			$db->execute();
		}
		catch (RuntimeException $e)
		{
			Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
			return false;
		}

		return true;
	}

	/**
	 * Method to validate edit link, if exists return subscriber id
	 *
	 * @param integer  $email      email of the demanded unsubscription
	 * @param string   $editlink   editlink provided by the unsubscription
	 *
	 * @return  integer|boolean false on failure
	 *
	 * @throws Exception
	 *
	 * @since   3.0.0
	 */
	public function validateSubscriberEditlink($email, $editlink)
	{
		$db    = $this->_db;
		$query = $db->getQuery(true);

		// Sanitize values
		$email    = $db->escape($email);
		$editlink = $db->escape($editlink);

		$query->select($db->quoteName('id'));
		$query->from($db->quoteName($this->_tbl));
		$query->where($db->quoteName('email') . ' = ' . $db->quote($email));
		$query->where($db->quoteName('editlink') . ' = ' . $db->quote($editlink));
		$query->where($db->quoteName('status') . ' != ' . 9);
		$db->setQuery($query);

		try
		{
			$id = $db->loadResult();
		}
		catch (RuntimeException $e)
		{
			Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
			return false;
		}

		return $id;
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
	 * @since  3.0.0
	 */
	public function getEmailaddress($id)
	{
		$emailaddress = null;

		$db	   = $this->_db;
		$query = $db->getQuery(true);

		$query->select($db->quoteName('email'));
		$query->from($db->quoteName($this->_tbl));
		$query->where($db->quoteName('id') . ' = ' . (int) $id);

		try
		{
			$db->setQuery($query);
			$emailaddress = $db->loadResult();
		}
		catch (RuntimeException $e)
		{
			Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
		}

		return $emailaddress;
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
	 * @since  3.0.0
	 */
	public function checkEditlink($editlink)
	{
		$db    = $this->_db;
		$query = $db->getQuery(true);

		$editlink = $db->escape($editlink);

		$query->select($db->quoteName('id'));
		$query->from($db->quoteName($this->_tbl));
		$query->where($db->quoteName('editlink') . ' = ' . $db->quote($editlink));
		$query->where($db->quoteName('status') . ' != ' . 9);

		try
		{
			$db->setQuery($query);
			$id = $db->loadResult();
		}
		catch (RuntimeException $e)
		{
			Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
		}

		return $id;
	}

	/**
	 * Method to get the subscribers data for push to sendmailqueue
	 *
	 * @param int    $content_id     Content ID --> from the sendmailcontent-Table
	 * @param string $status         Status --> 0 = unconfirmed, 1 = confirmed
	 * @param array  $subscribers    array of subscriber ids to get the data for
	 *
	 * @return 	array
	 *
	 * @throws Exception
	 *
	 * @since  3.0.0
	 */
	public function getSubscriberDataForSendmailqueue($content_id, $status, $subscribers)
	{
		$data  = array();
		$db    = $this->_db;
		$query = $db->getQuery(true);

		$query->select($db->quote($content_id) . ' AS content_id');
		$query->select($db->quoteName('email') . ' AS ' . $db->quoteName('recipient'));
		$query->select($db->quoteName('emailformat') . ' AS ' . $db->quoteName('mode'));
		$query->select($db->quoteName('name') . ' AS ' . $db->quoteName('name'));
		$query->select($db->quoteName('firstname') . ' AS ' . $db->quoteName('firstname'));
		$query->select($db->quoteName('id') . ' AS ' . $db->quoteName('subscriber_id'));
		$query->from($db->quoteName($this->_tbl));
		$query->where($db->quoteName('status') . ' IN (' . $status . ')');
		$query->where($db->quoteName('archive_flag') . ' = ' . 0);

		if (count($subscribers))
		{
			$query->where($db->quoteName('id') . ' IN (' . implode(',', $subscribers) . ')');
		}

		$db->setQuery($query);

		try
		{
			$data = $db->loadRowList();
		}
		catch (RuntimeException $e)
		{
			Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
		}

		return $data;
	}
}
