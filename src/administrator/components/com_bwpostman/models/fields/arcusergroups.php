<?php

/**
 * BwPostman Newsletter Component
 *
 * BwPostman  form field mailinglists class.
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

defined('JPATH_BASE') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;

JFormHelper::loadFieldClass('list');

/**
 * Class JFormFieldArcUsergroups
 *
 * @since       1.2.0
 */
class JFormFieldArcUsergroups extends JFormFieldList
{
	/**
	 * property to hold archived user groups
	 *
	 * @var string  $type
	 *
	 * @since       1.2.0
	 */
	protected $type = 'ArcUsergroups';

	/**
	 * Method to get the field options.
	 *
	 * @return	array  The field option objects.
	 *
	 * @throws Exception
	 *
	 * @since	1.2.0
	 */
	protected function getOptions()
	{
		// Get a db connection.
		$_db	= Factory::getDbo();
		$query	= $_db->getQuery(true);

		// Get # of all published mailinglists
		$query->select('DISTINCT (nm.mailinglist_id) AS value');
		$query->select('u.title AS text');
		$query->from('#__bwpostman_newsletters_mailinglists AS nm');
		$query->where('nm.mailinglist_id < 0');
		$query->rightJoin('#__bwpostman_newsletters AS n ON n.id = nm.newsletter_id');
		$query->where('n.archive_flag = 1');
		$query->leftJoin('#__usergroups AS u ON CONCAT("-", u.id) = nm.mailinglist_id');
		$query->order('u.title');

		$_db->setQuery($query);

		try
		{
			$options = $_db->loadObjectList();
		}
		catch (RuntimeException $e)
		{
			Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
		}

		$parent = new stdClass;
		$parent->value = '';
		$parent->text = Text::_('COM_BWPOSTMAN_ARC_FILTER_USERGROUPS');
		array_unshift($options, $parent);

		// Merge any additional options in the XML definition.
		$options = array_merge(parent::getOptions(), $options);

		return $options;
	}
}
