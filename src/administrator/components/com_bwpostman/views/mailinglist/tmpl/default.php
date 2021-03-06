<?php
/**
 * BwPostman Newsletter Component
 *
 * BwPostman single mailinglist form template for backend.
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

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\CMS\HTML\HTMLHelper;

// Load the tooltip behavior for the notes
HtmlHelper::_('behavior.tooltip');
HtmlHelper::_('behavior.keepalive');
HtmlHelper::_('formbehavior.chosen', 'select');

?>

<div id="bwp_editform">
	<?php
	if ($this->queueEntries)
	{
		Factory::getApplication()->enqueueMessage(Text::_('COM_BWPOSTMAN_ENTRIES_IN_QUEUE'), 'warning');
	}
	?>
	<form action="<?php echo Route::_('index.php?option=com_bwpostman&task=edit.save'); ?>"
			method="post" name="adminForm" id="adminForm" class="form-horizontal">
		<div class="tab-wrapper-bwp">
			<?php echo HtmlHelper::_('bootstrap.startTabSet', 'myTab', array('active' => 'details')); ?>
			<?php echo HtmlHelper::_(
				'bootstrap.addTab',
				'myTab',
				'details',
				empty($this->item->id) ? Text::_('COM_BWPOSTMAN_NEW_ML') : Text::sprintf('COM_BWPOSTMAN_EDIT_ML', $this->item->id)
			); ?>
			<fieldset class="adminform">
				<legend>
					<?php
					echo empty($this->item->id) ? Text::_('COM_BWPOSTMAN_NEW_ML') : Text::sprintf('COM_BWPOSTMAN_EDIT_ML', $this->item->id);
					?>
				</legend>
				<div class="well well-small">
					<div class="width-60 fltlft span8 control-group">
						<ul class="adminformlist unstyled">
							<li>
								<?php echo $this->form->getLabel('title'); ?>
								<div class="controls"><?php echo $this->form->getInput('title'); ?></div>
							</li>

							<li>
								<?php echo $this->form->getLabel('description'); ?>
								<div class="controls"><?php echo $this->form->getInput('description'); ?></div>
							</li>

							<li>
								<?php echo $this->form->getLabel('access'); ?>
								<div class="controls"><?php echo $this->form->getInput('access'); ?></div>
							</li>

							<li>
								<?php echo $this->form->getLabel('published'); ?>
								<div class="controls"><?php echo $this->form->getInput('published'); ?></div>
							</li>
						</ul>
					</div>

					<div class="width-40 fltrt span4 control-group">
						<ul class="adminformlist width_50 unstyled">
							<li>
								<?php echo $this->form->getLabel('campaign_id'); ?>
								<div class="controls"><?php echo $this->form->getInput('campaign_id'); ?></div>
							</li>

							<li>
								<?php echo $this->form->getLabel('created_by'); ?>
								<div class="controls"><?php echo $this->form->getInput('created_by'); ?></div>
							</li>

							<li>
								<?php echo $this->form->getLabel('created_date'); ?>
								<div class="controls"><?php echo $this->form->getInput('created_date'); ?></div>
							</li>

							<li>
								<?php echo $this->form->getLabel('modified_by'); ?>
								<div class="controls"><?php echo $this->form->getInput('modified_by'); ?></div>
							</li>

							<li>
								<?php echo $this->form->getLabel('modified_time'); ?>
								<div class="controls"><?php echo $this->form->getInput('modified_time'); ?></div>
							</li>
						</ul>
					</div>
					<div class="clearfix"></div>
					<p><span class="required_description"><?php echo Text::_('COM_BWPOSTMAN_REQUIRED'); ?></span></p>
				</div>
			</fieldset>
			<?php echo HtmlHelper::_('bootstrap.endTab'); ?>

			<?php
			if ($this->permissions['com']['admin'] || $this->permissions['admin']['mailinglist']): ?>
				<?php echo HtmlHelper::_('bootstrap.addTab', 'myTab', 'permissions', Text::_('COM_BWPOSTMAN_ML_FIELDSET_RULES')); ?>
				<div class="well well-small">
						<fieldset class="adminform">
							<?php echo $this->form->getInput('rules'); ?>
						</fieldset>
				</div>
			<?php endif;
				echo HtmlHelper::_('bootstrap.endTab');
			?>
			<div class="clearfix"></div>
			<?php echo HtmlHelper::_('bootstrap.endTabSet'); ?>
		</div>
		<p class="bwpm_copyright"><?php echo BwPostmanAdmin::footer(); ?></p>

		<input type="hidden" name="task" value="" />
		<input type="hidden" name="id" value="<?php echo $this->item->id; ?>" />

		<?php echo $this->form->getInput('id'); ?>
		<?php echo $this->form->getInput('asset_id'); ?>
		<?php echo $this->form->getInput('checked_out'); ?>
		<?php echo $this->form->getInput('archive_flag'); ?>
		<?php echo $this->form->getInput('archive_time'); ?>
		<?php echo HtmlHelper::_('form.token'); ?>

		<input type="hidden" id="alertTitle" value="<?php echo Text::_('COM_BWPOSTMAN_ML_ERROR_TITLE', true); ?>" />
		<input type="hidden" id="alertDescription" value="<?php echo Text::_('COM_BWPOSTMAN_ML_ERROR_DESCRIPTION'); ?>" />
	</form>
</div>
