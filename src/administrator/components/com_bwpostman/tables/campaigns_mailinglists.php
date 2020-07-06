<?php
/**
 * BwPostman Newsletter Component
 *
 * BwPostman campaigns mailing lists cross table for backend.
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

use Joomla\Database\DatabaseDriver;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

/**
 * #__bwpostman_campaigns_mailinglists table handler
 * Table for storing the mailinglists to which a campaign shall be send
 *
 * @package		BwPostman-Admin
 *
 * @subpackage	Campaigns
 *
 * @since
 */
class BwPostmanTableCampaigns_Mailinglists extends JTable
{
	/**
	 * @var int Primary Key Campaign-ID
	 *
	 * @since
	 */
	public $campaign_id = null;

	/**
	 * @var int Primary Key Mailinglist-ID
	 *
	 * @since
	 */
	public $mailinglist_id = null;

	/**
	 * Constructor
	 *
	 * @param 	JDatabaseDriver  $db Database object
	 *
	 * @since
	 */
	public function __construct(& $db)
	{
		parent::__construct('#__bwpostman_campaigns_mailinglists', 'campaign_id', $db);
	}

	/**
	 * Method to copy the entries of this table for one or more campaigns
	 *
	 * @access	public
	 *
	 * @param 	int $oldid      ID of the existing campaign
	 * @param 	int $newid      ID of the copied campaign
	 *
	 * @throws Exception
	 *
	 * @return 	boolean
	 *
	 * @since
	 */
	public function copyLists($oldid, $newid)
	{
		$lists      = array();
		$_db		= $this->_db;
		$query		= $_db->getQuery(true);
		$subQuery	= $_db->getQuery(true);

		$subQuery->select($_db->quote((integer)$newid) . ' AS ' . $_db->quoteName('campaign_id'));
		$subQuery->select($_db->quoteName('mailinglist_id'));
		$subQuery->from($_db->quoteName($this->_tbl));
		$subQuery->where($_db->quoteName('campaign_id') . ' = ' . (int) $oldid);
		$_db->setQuery($subQuery);

		try
		{
			$lists		= $_db->loadAssocList();
		}
		catch (RuntimeException $e)
		{
			Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
		}

		foreach ($lists as $list)
		{
			$query->clear();
			$query->insert($_db->quoteName($this->_tbl));
			$query->columns(
				array(
				$_db->quoteName('campaign_id'),
				$_db->quoteName('mailinglist_id')
				)
			);
			$query->values(
				(int) $list['campaign_id'] . ',' .
					(int) $list['mailinglist_id']
			);
			$_db->setQuery($query);

			try
			{
				$_db->execute();
			}
			catch (RuntimeException $e)
			{
				Factory::getApplication()->enqueueMessage(Text::_('COM_BWPOSTMAN_CAM_COPY_MAILINGLISTS_FAILED'), 'error');
			}
		}

		return true;
	}

	/**
	 * Method to get associated mailing lists by campaign
	 *
	 * @param  integer   $id   newsletter id
	 *
	 * @return array
	 *
	 * @throws Exception
	 *
	 * @since 2.4.0 (here, before since 2.3.0 at BE newsletter model)
	 */
	public function getAssociatedMailinglistsByCampaign($id)
	{
		$db	= $this->_db;
		$mailinglists = array();

		$query = $db->getQuery(true);
		$query->select($db->quoteName('mailinglist_id'));
		$query->from($db->quoteName($this->_tbl));
		$query->where($db->quoteName('campaign_id') . ' = ' . (int) $id);

		$db->setQuery($query);

		try
		{
			$mailinglists = $db->loadColumn();
		}
		catch (RuntimeException $e)
		{
			Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
		}

		return $mailinglists;
	}

	/**
	 * Method to get the mailinglist ids for a single campaign
	 *
	 * @param int $cam_id Campaign ID
	 *
	 * @return array
	 *
	 * @throws Exception
	 *
	 * @since 2.4.0 here
	 */
	public function getCampaignMailinglists($cam_id = null)
	{
		$mailinglists = array();
		$db	= $this->_db;
		$query = $db->getQuery(true);

		$query->select($db->quoteName('mailinglist_id'));
		$query->from($db->quoteName($this->_tbl));
		$query->where($db->quoteName('campaign_id') . ' = ' . (int) $cam_id);
		$db->setQuery($query);
		try
		{
			$mailinglists = $db->loadColumn();
		}
		catch (RuntimeException $e)
		{
			Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
		}

		return $mailinglists;
	}

	/**
	 * Returns the identity (primary key) value of this record
	 *
	 * @return  mixed
	 *
	 * @since  2.4.0
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
	 * @since   2.4.0
	 */
	public function hasField($key)
	{
		$key = $this->getColumnAlias($key);

		return property_exists($this, $key);
	}
}
