<?php
/**
 * BwPostman Newsletter Component
 *
 * BwPostman single newsletter edit html template for backend.
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

JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.keepalive');
JHtml::_('formbehavior.chosen', 'select');

$image = JHtml::_('image', 'administrator/templates/' . $this->template . '/images/menu/icon-16-info.png', JText::_('COM_BWPOSTMAN_NOTES'));
$currentTab = 'edit_html';
?>

<div id="bwp_view_single">
	<form action="<?php echo JRoute::_('index.php?option=com_bwpostman&id=' . (int) $this->item->id); ?>"
			method="post" name="adminForm" id="adminForm">
		<?php
		if ($this->item->is_template)
		{
			JFactory::$application->enqueueMessage(JText::_("COM_BWPOSTMAN_NL_IS_TEMPLATE_INFO"), "Notice");
		}
		?>
		<div class="form-horizontal">
			<ul class="bwp_tabs">
				<li class="closed">
					<button onclick="return changeTab('edit_html', '<?php echo $currentTab; ?>');" class="buttonAsLink_open">
						<?php echo JText::_('COM_BWPOSTMAN_NL_STP1'); ?>
					</button>
				</li>
				<li class="open">
					<button onclick="return changeTab('edit_html', '<?php echo $currentTab; ?>');" class="buttonAsLink">
						<?php echo JText::_('COM_BWPOSTMAN_NL_STP2'); ?>
					</button>
				</li>
				<li class="closed">
					<button onclick="return changeTab('edit_text', '<?php echo $currentTab; ?>');" class="buttonAsLink">
						<?php echo JText::_('COM_BWPOSTMAN_NL_STP3'); ?>
					</button>
				</li>
				<li class="closed">
					<button onclick="return changeTab('edit_preview');" class="buttonAsLink">
						<?php echo JText::_('COM_BWPOSTMAN_NL_STP4'); ?>
					</button>
				</li>
				<?php if (BwPostmanHelper::canSend((int) $this->item->id) && !$this->item->is_template) { ?>
					<li class="closed">
						<button onclick="return changeTab('edit_preview', '<?php echo $currentTab; ?>');" class="buttonAsLink">
							<?php echo JText::_('COM_BWPOSTMAN_NL_STP5'); ?>
						</button>
					</li>
				<?php } ?>
			</ul>
		</div>
		<div class="clr clearfix"></div>

		<div class="tab-wrapper-bwp">
			<fieldset class="adminform form-horizontal">
				<legend><?php echo JText::_('COM_BWPOSTMAN_NL_HTML'); ?></legend>
				<div class="well well-small">
					<ul class="unstyled">
						<?php
						foreach($this->form->getFieldset('html_version') as $field):
							// if old template - show no intro fields
							if (empty($this->item->intro_headline) && empty($this->item->intro_text))
							{
								$show = array("jform[html_version]");
							}
							else
							{
								$show = array("jform[html_version]", "jform[intro_headline]", "jform[intro_text]");
							}

							if (in_array($field->name, $show)) :
								if ($field->hidden):
									echo $field->input;
								else:
									if ($field->name == 'jform[html_version]')
									{ ?>
									<li <?php echo 'class="' . $field->name . '"'; ?>>
										<div class="row-fluid clearfix">
											<div class="width-20 fltlft span2">
											<?php echo $field->label; ?>
											<?php echo '</div><div class="width-80 fltlft span10">'
												. JText::_('COM_BWPOSTMAN_NL_PERSONALISATION_NOTE'); ?>
											</div>
										</div>
										<div class="row-fluid clearfix"><?php echo $field->input; ?></div>
										<div class="row-fluid clearfix">
											<?php
											$link = JUri::base() . '#';
											if(JPluginHelper::isEnabled('bwpostman', 'personalize')) {
												$button_text = JText::_('COM_BWPOSTMAN_TPL_HTML_PERS_BUTTON');
												$linktexts = array('PERS' => $button_text, '[FIRSTNAME]', '[LASTNAME]', '[FULLNAME]');
											}
											else
											{
												$linktexts = array('[FIRSTNAME]', '[LASTNAME]', '[FULLNAME]');
											}

											foreach ($linktexts as $key => $linktext)
											{
												echo "                    <a class=\"btn btn-small pull-left\" 
												onclick=\"buttonClick('" . $linktext . "','jform_html_version');return false;\" 
												href=\"" . $link . "\">" . $linktext . "</a>";
												echo '                     <p>&nbsp;' . JText::_('COM_BWPOSTMAN_TPL_HTML_DESC' . $key) . '</p>';
											}

											if(JPluginHelper::isEnabled('bwpostman', 'personalize'))
											{
												echo JText::_('COM_BWPOSTMAN_TPL_HTML_DESC_PERSONALIZE');
											}
											?>
										</div>
									</li>
									<?php
									}
									else
									{ ?>
									<li><?php echo $field->label; ?>
										<div class="row-fluid controls"><?php echo $field->input; ?></div>
									</li>
									<?php
									}
								endif;
							endif;
						endforeach; ?>
					</ul>
					<p><span class="required_description"><?php echo JText::_('COM_BWPOSTMAN_REQUIRED'); ?></span></p>
				</div>
				<div class="clr clearfix"></div>

				<?php
				foreach($this->form->getFieldset('basic_1_hidden') as $field)
				{
					echo $field->input;
				}

				foreach($this->form->getFieldset('basic_2_hidden') as $field)
				{
					echo $field->input;
				}

				foreach($this->form->getFieldset('text_version_hidden') as $field)
				{
					echo $field->input;
				}

				foreach($this->form->getFieldset('templates_hidden') as $field)
				{
					echo $field->input;
				}

				foreach($this->form->getFieldset('selected_content_hidden') as $field)
				{
					echo $field->input;
				}

				foreach($this->form->getFieldset('available_content_hidden') as $field)
				{
					echo $field->input;
				}

				foreach($this->form->getFieldset('publish_hidden') as $field)
				{
					echo $field->input;
				}
				?>

			</fieldset>
		</div>
		<p class="bwpm_copyright"><?php echo BwPostmanAdmin::footer(); ?></p>

		<input type="hidden" name="id" value="<?php echo $this->item->id; ?>" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" id="layout" name="layout" value="edit_html" /><!-- value never changes -->
		<input type="hidden" name="tab" value="edit_html" /><!-- value can change if one clicks on another tab -->
		<input type="hidden" id="template_id_old" name="template_id_old" value="<?php echo $this->template_id_old; ?>" />
		<input type="hidden" id="text_template_id_old" name="text_template_id_old" value="<?php echo $this->text_template_id_old; ?>" />
		<input type="hidden" name="add_content" value="" />
		<input type="hidden" id="selected_content_old" name="selected_content_old" value="<?php echo $this->selected_content_old; ?>" />
		<input type="hidden" id="content_exists" name="content_exists" value="<?php echo $this->content_exists; ?>" />
		<?php echo JHtml::_('form.token'); ?>
	</form>
</div>
