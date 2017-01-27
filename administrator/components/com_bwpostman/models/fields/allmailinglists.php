<?php

/**
 * BwPostman Newsletter Component
 *
 * BwPostman  form field mailinglists class.
 *
 * @version 2.0.0 bwpm
 * @package BwPostman-Admin
 * @author Romana Boldt
 * @copyright (C) 2012-2017 Boldt Webservice <forum@boldt-webservice.de>
 * @support https://www.boldt-webservice.de/en/forum-en/bwpostman.html
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

JFormHelper::loadFieldClass('list');

/**
 * Class JFormFieldAllMailinglists
 *
 * @since
 */
class JFormFieldAllMailinglists extends JFormFieldList {

	/**
	 * property to hold all mailing lists
	 *
	 * @var string  $type
	 *
	 * @since
	 */
	protected $type = 'AllMailinglists';

	/**
	 * Method to get the field options.
	 *
	 * @return  array  The field option objects.
	 *
	 * @since   1.0.8
	 */
	protected function getOptions()
	{
		// Get a db connection.
		$_db	= JFactory::getDbo();
		$query	= $_db->getQuery(true);

		// Get # of all published mailinglists
		$query->select($_db->quoteName('id') . ' AS value');
		$query->select($_db->quoteName('title') . ' AS text');
		$query->from($_db->quoteName('#__bwpostman_mailinglists'));
		$query->where($_db->quoteName('archive_flag') . ' = ' . (int) 0);

		$_db->setQuery ($query);

		try
		{
			$options = $_db->loadObjectList();
		}
		catch (RuntimeException $e)
		{
			JFactory::getApplication()->enqueueMessage($e->getMessage(), 'error');
		}

		$parent = new stdClass;
		$parent->value = '';
		$parent->text = JText::_('COM_BWPOSTMAN_SUB_FILTER_MAILINGLISTS');
		array_unshift($options, $parent);

		// Merge any additional options in the XML definition.
		$options = array_merge(parent::getOptions(), $options);

		return $options;
	}
}
