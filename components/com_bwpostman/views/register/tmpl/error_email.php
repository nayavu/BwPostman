<?php
/**
 * BwPostman Newsletter Component
 *
 * BwPostman register error email template for frontend.
 *
 * @version 1.3.2 bwpm
 * @package BwPostman-Site
 * @author Romana Boldt
 * @copyright (C) 2012-2015 Boldt Webservice <forum@boldt-webservice.de>
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

// Displays a error message and a mailto-link of the admin email address
// Will be shown if
// 1. the confirmation email could not be sent
// 2. the editlink email could not be sent
// 3. the activation code email could not be sent
?>

<div id="bwpostman">
	<div id="bwp_com_error_email">
		<?php if ($this->params->def('show_page_title', 1)) { ?>
		<h1 class="componentheading<?php echo $this->params->get('pageclass_sfx'); ?>"><?php echo $this->escape($this->params->get('page_title')); ?></h1>
		<?php }

		$admin_email = $this->params->def('default_from_email', $this->config->get('mailfrom'));

		echo '<p class="bwp-error">' . JText::_('COM_BWPOSTMAN_ERROR') . '</p>';

		if ($this->error->err_email) {
			// Case 1
			$msg = '<p class="error-message">' . JText::sprintf($this->error->err_msg, $this->error->err_email) . '</p>';
			echo $msg;
		}
		else {
			// Case 2
			// Case 3
			echo '<p class="error-message">' . JText::_($this->error->err_msg) . '</p>';
		}

		$msg1 = '<p class="contact-admin">' . JText::sprintf('COM_BWPOSTMAN_ERROR_CONTACTADMIN', $admin_email) . '</p>';
		echo JHTML::_('content.prepare', $msg1);
		?>
		<p class="bwpm_copyright"<?php if ($this->params->get('show_boldt_link') != 1) echo ' style="display:none;"'; ?>><?php echo BwPostman::footer(); ?></p>
	</div>
</div>
