<?php
/**
 * BwPostman Newsletter Component
 *
 * BwPostman mailinglist helper class for backend.
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

defined ('_JEXEC') or die ();

use Joomla\CMS\Factory;
use Joomla\Utilities\ArrayHelper;

/**
 * Class BwPostmanMailinglistHelper
 *
 * @since 2.2.0
 */
abstract class BwPostmanMailinglistHelper {
	/**
	 * Method to get the mailinglist ids for a single campaign
	 *
	 * @param array $mailinglists      list of mailinglists
	 *
	 * @return array
	 *
	 * @since 3.0.0
	 */
	public static function extractAssociatedUsergroups($mailinglists = array())
	{
		$usergroups	= array();

		foreach ($mailinglists as $mailinglist)
		{
			if ((int) $mailinglist < 0)
			{
				$usergroups[]	= -(int) $mailinglist;
			}
		}

		return $usergroups;
	}

	/**
	 * Method to merge the parts of the mailinglists (available, unavailable, internal, usergroups)
	 *
	 * @param array $data      list of mailinglists
	 *
	 * @since 3.0.0
	 */
	public static function mergeMailinglists(&$data = array())
	{
		if (isset($data['ml_available']))
		{
			foreach ($data['ml_available'] as $key => $value)
			{
				$data['mailinglists'][] 	= $value;
			}
		}

		if (isset($data['ml_unavailable']))
		{
			foreach ($data['ml_unavailable'] as $key => $value)
			{
				$data['mailinglists'][] 	= $value;
			}
		}

		if (isset($data['ml_intern']))
		{
			foreach ($data['ml_intern'] as $key => $value)
			{
				$data['mailinglists'][] 	= $value;
			}
		}

		// merge usergroups into mailinglists, single array may not exist, therefore array_merge would not give a result
		if (isset($data['usergroups']) && !empty($data['usergroups']))
		{
			foreach ($data['usergroups'] as $key => $value)
			{
				$data['mailinglists'][] = '-' . $value;
			}
		}
	}

	/**
	 * Method to merge the parts of the mailinglists (available, unavailable, internal) to one single array
	 *
	 * @param array $data      list of mailinglists
	 *
	 * @return array
	 *
	 * @since 3.0.0
	 */
	public static function mergeMailinglistsOnly($data = array())
	{
		$mailinglists = array();

		if (isset($data['ml_available']))
		{
			foreach ($data['ml_available'] as $key => $value)
			{
				$mailinglists[] 	= $value;
			}
		}

		if (isset($data['ml_unavailable']))
		{
			foreach ($data['ml_unavailable'] as $key => $value)
			{
				$mailinglists[] 	= $value;
			}
		}

		if (isset($data['ml_intern']))
		{
			foreach ($data['ml_intern'] as $key => $value)
			{
				$mailinglists[] 	= $value;
			}
		}

		return ArrayHelper::toInteger($mailinglists);
	}

	/**
	 * Method to get the data of a single Mailinglist for raw view
	 *
	 * @param 	int $ml_id      Mailinglist ID
	 *
	 * @return 	object Mailinglist
	 *
	 * @throws Exception
	 *
	 * @since 3.0.0 here
	 */
	public static function getSingleMailinglist($ml_id = null)
	{
		$db    = Factory::getDbo();
		$query = $db->getQuery(true);

		$query->select($db->quoteName('a') . '.*');
		$query->from($db->quoteName('#__bwpostman_mailinglists') . ' AS ' . $db->quoteName('a'));
		$query->where($db->quoteName('a') . '.' . $db->quoteName('id') . ' = ' . (int) $ml_id);
		// Join over the asset groups.
		$query->select($db->quoteName('ag') . '.' . $db->quoteName('title') . ' AS ' . $db->quoteName('access_level'));
		$query->join(
			'LEFT',
			$db->quoteName('#__viewlevels') . ' AS ' . $db->quoteName('ag') . ' ON ' .
			$db->quoteName('ag') . '.' . $db->quoteName('id') . ' = ' . $db->quoteName('a') . '.' . $db->quoteName('access')
		);

		$db->setQuery($query);
		try
		{
			$mailinglist = $db->loadObject();
		}
		catch (RuntimeException $e)
		{
			Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
		}

		return $mailinglist;
	}

	/**
	 * Method to get the options for the form fields comcam and comcam_noarc
	 *
	 * @param boolean $archiveMatters
	 *
	 * @return 	object
	 *
	 * @throws Exception
	 *
	 * @since 3.0.0
	 */
	public static function getMailinglistsFieldlistOptions($archiveMatters = false)
	{
		$options   = null;
		$db        = Factory::getDbo();
		$query     = $db->getQuery(true);

		$query->select($db->quoteName('a') . '.' . $db->quoteName('id')  . ' AS value');
		$query->select($db->quoteName('a') . '.' . $db->quoteName('title')  . ' AS text');
		$query->select($db->quoteName('a') . '.' . $db->quoteName('description'));
		$query->select($db->quoteName('a') . '.' . $db->quoteName('access'));
		$query->select($db->quoteName('a') . '.' . $db->quoteName('published'));
		$query->select($db->quoteName('a') . '.' . $db->quoteName('archive_flag')  . ' AS archived');
		$query->from($db->quoteName('#__bwpostman_mailinglists') . ' AS ' . $db->quoteName('a'));

		if ($archiveMatters)
		{
			$query->where($db->quoteName('a') . '.' . $db->quoteName('archive_flag') . ' = ' . 0);
		}

		// Join over the asset groups.
		$query->select($db->quoteName('ag') . '.' . $db->quoteName('title')  . ' AS access_level');
		$query->join(
			'LEFT',
			$db->quoteName('#__viewlevels') .
			' AS ' . $db->quoteName('ag') .
			' ON ' . $db->quoteName('ag') . '.' . $db->quoteName('id') . ' = ' . $db->quoteName('a') . '.' . $db->quoteName('access')
		);
		$query->order($db->quoteName('text') . 'ASC');

		try
		{
			$db->setQuery($query);
			$options = $db->loadObjectList();
		}
		catch (RuntimeException $e)
		{
			Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
		}

		return $options;
	}
}
