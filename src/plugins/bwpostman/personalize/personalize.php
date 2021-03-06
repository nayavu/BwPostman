<?php
/**
 * BwPostman Personalize Plugin
 *
 * BwPostman Personalize Plugin main file for BwPostman.
 *
 * @version %%version_number%%
 * @package BwPostman Personalize Plugin
 * @author Romana Boldt
 * @copyright (C) %%copyright_year%% Boldt Webservice <forum@boldt-webservice.de>
 * @support https://www.boldt-webservice.de/en/forum-en/forum/bwpostman.html
 * @license GNU/GPL v3, see LICENSE.txt
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

defined('_JEXEC') or die('Restricted access');

jimport('joomla.plugin.plugin');

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\Database\DatabaseDriver;
use Joomla\CMS\Component\ComponentHelper;

if (!ComponentHelper::isEnabled('com_bwpostman')) {
	Factory::getApplication()->enqueueMessage(
		Text::_('PLG_BWPOSTMAN_PLUGIN_PERSONALIZE_ERROR') . ', ' . Text::_('PLG_BWPOSTMAN_PLUGIN_PERSONALIZE_COMPONENT_NOT_INSTALLED'),
		'error'
	);
	return false;
}

/**
 * Class plgBwPostmanPersonalize
 *
 * @since       2.0.0
 */
class PlgBwPostmanPersonalize extends JPlugin
{
	/**
	 * Database object
	 *
	 * @var    DatabaseDriver
	 *
	 * @since       2.0.0
	 */
	protected $db;

	/**
	 * Application object
	 *
	 * @var    JApplication
	 *
	 * @since       2.0.0
	 */
	protected $app;

	/**
	 * Method to write enhanced personalization in the body of the newsletter
	 *
	 * Inserts male or female string depending on gender of subscriber. If no gender is available, male string is used.
	 * At incomplete plugin parameters an empty string is inserted. We don't want incomplete plugin characters in newsletter.
	 *
	 * @param string    $context    context of the newsletter to display
	 * @param string    $body       the body of the newsletter
	 * @param int       $id         subscriber ID or user ID, depends on the context
	 *
	 * @return bool
	 *
	 * @since       2.0.0
	 */
	public function onBwPostmanPersonalize($context= 'com_bwpostman.view', &$body = '', $id = 0)
	{
		$gender = null;
		// get gender
		if ($context == 'com_bwpostman.send') {
			$gender = $this->getGenderFromSubscriberId($id);
		}
		elseif ($context == 'com_bwpostman.view') {
			if ($id > 0)
			{
				$gender = $this->getGenderFromUserId($id);
			}
		}

		// Start Plugin
		$regex_one		= '/(\[bwpostman_personalize\s*)(.*?)(\])/is';
		$regex_all		= '/\[bwpostman_personalize\s*.*?\]/si';
		$matches 		= array();
		$count_matches	= preg_match_all($regex_all, $body, $matches, PREG_OFFSET_CAPTURE | PREG_PATTERN_ORDER);

		for($j = 0; $j < $count_matches; $j++)
		{
			// Get plugin parameters
			$bwpm_personalize	= $matches[0][$j][0];
			preg_match($regex_one, $bwpm_personalize, $bwpm_personalize_parts);

			$gender_strings = $this->extractGenderStrings($bwpm_personalize_parts);

			// if gender not set replace with last parameter
			if ($gender === null)
			{
				$gender = 2;
			}

			// set replace value depending on gender
			$replace_value = $gender_strings[$gender];

			// modify newsletter body
			$body = preg_replace($regex_all, $replace_value, $body, 1);
		}

		return true;
	}

	/**
	 * Method to get the gender of the subscriber by subscriber_id
	 *
	 * @param int   $id     subscriber ID
	 *
	 * @return int  $gender gender of subscriber
	 *
	 * @since       2.0.0
	 */
	protected function getGenderFromSubscriberId($id)
	{
		$_db 	= $this->db;
		$query  = $this->db->getQuery(true);

		$query->select($this->db->quoteName('gender'));
		$query->from('#__bwpostman_subscribers');
		$query->where($this->db->quoteName('id') . ' = ' . (int) $id);
		$_db->setQuery($query);

		$gender = $this->db->loadResult();

		return $gender;
	}

	/**
	 * Method to get the gender of the subscriber by user_id
	 *
	 * @param int   $id     user ID
	 *
	 * @return int  $gender gender of subscriber
	 *
	 * @since       2.0.0
	 */
	protected function getGenderFromUserId($id)
	{
		$_db   = $this->db;
		$query = $this->db->getQuery(true);

		$query->select($this->db->quoteName('gender'));
		$query->from('#__bwpostman_subscribers');
		$query->where($this->db->quoteName('user_id') . ' = ' . (int) $id);
		$_db->setQuery($query);

		$gender = $this->db->loadResult();

		return $gender;
	}

	/**
	 * Method to extract the gender related strings form the plugin string
	 *
	 * @param array $bwpm_personalize_parts
	 *
	 * @return array    $parts
	 *
	 * @since       2.0.0
	 */
	protected function extractGenderStrings($bwpm_personalize_parts)
	{
		$parts = explode("|", $bwpm_personalize_parts[2]);
		array_shift($parts);
		$gender_string  = array();

		foreach ($parts as $part) {
			// extract sting between double quote
			$start  = strpos($part, '"');
			$end    = strrpos($part, '"');
			if (($start !== false) && ($end !== false))
			{
				$gender_string[] = substr($part, $start + 1, $end - $start - 1);
			}
			// if there are not two double quotes set string to original string (do nothing)
			else
			{
				$gender_string[] = $bwpm_personalize_parts[0];
			}
		}

		// if personalization parameters are incomplete, fill with original string (do nothing)
		while (count($gender_string) < 3)
		{
			$gender_string[] = $bwpm_personalize_parts[0];
		}

		return $gender_string;
	}
}
