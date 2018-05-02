<?php
/**
 * BwPostman Newsletter Component
 *
 * BwPostman newsletter single raw view for frontend.
 *
 * @version 2.0.2 bwpm
 * @package BwPostman-Site
 * @author Romana Boldt
 * @copyright (C) 2012-2018 Boldt Webservice <forum@boldt-webservice.de>
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

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

// Import VIEW object class
jimport('joomla.application.component.view');

/**
 * Class BwPostmanViewNewsletter
 *
 * @since       0.9.1
 */
class BwPostmanViewNewsletter extends JViewLegacy
{
	/**
	 * Property to hold newsletter content
	 *
	 * @var object
	 *
	 * @since       0.9.1
	 */
	protected $newsletter;

	/**
	 * Execute and display a template script.
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  mixed  A string if successful, otherwise a JError object.
	 *
	 * @since   1.2.0
	 */
	public function display($tpl = null)
	{
		$this->newsletter = $this->get('Content');

		// Call parent display
		parent::display($tpl);

		return $this;
	}
}
