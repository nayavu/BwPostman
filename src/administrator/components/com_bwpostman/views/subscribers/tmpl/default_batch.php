<?php
/**
 * BwPostman Newsletter Component
 *
 * BwPostman all subscribers confirmed template for backend.
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

// no direct access
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\HTML\HTMLHelper;

// Get some states
$filter_id	= $this->escape($this->state->get('filter.mailinglist'));
$published	= $this->escape($this->state->get('filter.published'));

// Set session filter state for moving, needed in model
Factory::getSession()->set('com_bwpostman.subscriber.batch_filter_mailinglist', $filter_id);

// Create the subscribe/unsubscribe/move options.
$options = array(
	HtmlHelper::_('select.option', 's', Text::_('COM_BWPOSTMAN_SUB_HTML_BATCH_SUBSCRIBE')),
	HtmlHelper::_('select.option', 'u', Text::_('COM_BWPOSTMAN_SUB_HTML_BATCH_UNSUBSCRIBE'))
);

if ($filter_id)
{
	$options[]	= HtmlHelper::_('select.option', 'm', Text::_('COM_BWPOSTMAN_SUB_HTML_BATCH_MOVE'));
}

// Create the batch selector to change select the mailinglist by which to add or remove.
$batch_lists = '<label id="batch-choose-action-lbl" for="batch-choose-action">' . Text::_('COM_BWPOSTMAN_SUB_BATCH_MENU_LABEL') . '</label>'
				. '<div class="clr"></div>'
				. '<div id="batch-choose-action" class="control-group">'
				. '<select name="batch[mailinglist_id]" class="inputbox" id="batch-mailinglist-id">'
				. HtmlHelper::_('select.options', $this->mailinglists, 'value', 'text', '', '', '')
				. '</select>'
				. '</div>'
				. '<div id="batch-task" class="control-group radio">'
				. HtmlHelper::_('select.radiolist', $options, 'batch[batch-task]', '', 'value', 'text', 's')
				. '</div>';
?>

<div class="modal hide fade" id="collapseModal">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&#215;</button>
		<h3><?php echo Text::_('COM_BWPOSTMAN_SUB_BATCH_OPTIONS'); ?></h3>
	</div>
	<div class="modal-body modal-batch">
		<p><?php echo Text::_('COM_BWPOSTMAN_SUB_BATCH_TIP'); ?></p>
		<div class="row-fluid">
			<?php if ($published >= 0) : ?>
				<div class="control-group">
					<div class="controls">
						<?php echo $batch_lists; ?>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</div>
	<div class="modal-footer">
		<button class="btn" type="button" onclick="document.getElementById('batch-mailinglist-id').value='';" data-dismiss="modal">
			<?php echo Text::_('JCANCEL'); ?>
		</button>
		<button class="btn btn-primary" type="submit" onclick="Joomla.submitbutton('subscriber.batch');">
			<?php echo Text::_('JGLOBAL_BATCH_PROCESS'); ?>
		</button>

	</div>
</div>
