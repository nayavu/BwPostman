<?php
/**
 * BwPostman Newsletter Component
 *
 * BwPostman templates form template for backend.
 *
 * @version %%version_number%%
 * @package BwPostman-Admin
 * @author Karl Klostermann
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
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\HTML\HTMLHelper;

// Load the tooltip behavior for the notes
HtmlHelper::_('bootstrap.tooltip');
HtmlHelper::_('behavior.keepalive');
HtmlHelper::_('formbehavior.chosen', 'select');

$image = '<i class="icon-info"></i>';

$options = array(
		'onActive' => 'function(title, description){
		description.setStyle("display", "block");
		title.addClass("open").removeClass("closed");
	}',
		'onBackground' => 'function(title, description){
		description.setStyle("display", "none");
		title.addClass("closed").removeClass("open");
	}',
	'startOffset' => 0,  // 0 starts on the first tab, 1 starts the second, etc...
	'useCookie' => true, // this must not be a string. Don't use quotes.
);

?>

<div id="bwp_view_lists">
	<?php
	if ($this->queueEntries)
	{
		Factory::getApplication()->enqueueMessage(Text::_('COM_BWPOSTMAN_ENTRIES_IN_QUEUE'), 'warning');
	}
	?>
	<form action="<?php echo Route::_('index.php?option=com_bwpostman&view=template&id=' . (int) $this->item->id); ?>"
			method="post" name="adminForm" id="adminForm" class="form-horizontal">
		<fieldset class="adminform">
			<legend>
				<?php
				$title = Text::_('COM_BWPOSTMAN_NEW_TPL_HTML');
				if ($this->item->id)
				{
					$title = Text::sprintf('COM_BWPOSTMAN_EDIT_TPL_HTML', $this->item->id);
				}

				echo $title
				?>
			</legend>
			<div class="well well-small">
				<div class="fltlft width-40 span5 control-group">
					<?php
						echo HtmlHelper::_('tabs.start', 'template_tabs', $options);
						echo HtmlHelper::_('tabs.panel', Text::_('COM_BWPOSTMAN_TPL_BASICS_LABEL'), 'panel1');
					?>
					<fieldset class="panelform">
						<legend><?php echo Text::_('COM_BWPOSTMAN_TPL_BASICS_LABEL'); ?></legend>
						<div class="well well-small">
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
									<?php echo $this->form->getLabel('thumbnail'); ?>
									<div class="controls"><?php echo $this->form->getInput('thumbnail'); ?></div>
								</li>
								<p><span class="required_description"><?php echo Text::_('COM_BWPOSTMAN_REQUIRED'); ?></span></p>
								<?php echo $this->loadTemplate('basics'); ?>
							</ul>
						</div>
					</fieldset>
					<?php
					echo HtmlHelper::_('tabs.panel', Text::_('COM_BWPOSTMAN_TPL_HEADER_LABEL'), 'panel2');
					echo $this->loadTemplate('header');
					echo HtmlHelper::_('tabs.panel', Text::_('COM_BWPOSTMAN_TPL_INTRO_LABEL'), 'panel3');
					echo $this->loadTemplate('intro');
					echo HtmlHelper::_('tabs.panel', Text::_('COM_BWPOSTMAN_TPL_ARTICLE_LABEL'), 'panel4');
					echo $this->loadTemplate('article');
					echo HtmlHelper::_('tabs.panel', Text::_('COM_BWPOSTMAN_TPL_FOOTER_LABEL'), 'panel5');
					echo $this->loadTemplate('footer');
					if ($this->permissions['com']['admin'] || $this->permissions['admin']['template'])
					{
						echo HtmlHelper::_('tabs.panel', Text::_('COM_BWPOSTMAN_TPL_FIELDSET_RULES'), 'panel6');
						?>
						<div class="well well-small">
							<fieldset class="adminform">
								<?php echo $this->form->getInput('rules'); ?>
							</fieldset>
						</div>
						<?php
					}

					echo HtmlHelper::_('tabs.end');
					?>
					<div class="clr clearfix"></div>
					<div class="well-note well-small"><?php echo Text::_('COM_BWPOSTMAN_TPL_USER_NOTE'); ?></div>
				</div>
				<div id="email_preview" class="fltlft span7">
					<p>
						<button class="btn btn-large btn-block btn-primary" type="submit">
							<?php echo Text::_('COM_BWPOSTMAN_TPL_REFRESH_PREVIEW'); ?>
						</button>
					</p>
					<iframe id="myIframe" name="myIframeHtml"
						src="index.php?option=com_bwpostman&amp;view=template&amp;layout=template_preview&amp;format=raw&amp;id=<?php echo $this->item->id; ?>"
						height="800" width="100%" style="border: 1px solid #c2c2c2;">
					</iframe>
				</div>
				<div class="clr clearfix"></div>
			</div>
		</fieldset>

		<input type="hidden" name="task" value="" />
		<input type="hidden" name="id" value="<?php echo $this->item->id; ?>" />
		<input type="hidden" name="created_by" value="<?php echo $this->item->created_by; ?>" />
		<?php echo $this->form->getInput('id'); ?>
		<?php echo $this->form->getInput('asset_id'); ?>
		<?php echo $this->form->getInput('tpl_id'); ?>
		<?php echo $this->form->getInput('checked_out'); ?>
		<?php echo $this->form->getInput('archive_flag'); ?>
		<?php echo $this->form->getInput('archive_time'); ?>
		<?php echo HtmlHelper::_('form.token'); ?>

		<input type="hidden" id="cancelText" value="<?php echo Text::_('COM_BWPOSTMAN_TPL_CONFIRM_CANCEL', true); ?>" />
		<input type="hidden" id="titleErrorText" value="<?php echo Text::_('COM_BWPOSTMAN_TPL_ERROR_TITLE', true); ?>" />
		<input type="hidden" id="descriptionErrorText" value="<?php echo Text::_('COM_BWPOSTMAN_TPL_ERROR_DESCRIPTION', true); ?>" />

		<p class="bwpm_copyright"><?php echo BwPostmanAdmin::footer(); ?></p>
	</form>
</div>

<?php
Factory::getDocument()->addScript(Uri::root(true) . '/administrator/components/com_bwpostman/assets/js/bwpm_template_checkValues.js');
Factory::getDocument()->addScript(Uri::root(true) . '/administrator/components/com_bwpostman/assets/js/bwpm_template.js');
Factory::getDocument()->addScript(Uri::root(true) . '/administrator/components/com_bwpostman/assets/js/bwpm_template_base.js');
