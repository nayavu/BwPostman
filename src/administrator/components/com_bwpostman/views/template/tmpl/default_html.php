<?php
/**
 * BwPostman Newsletter Component
 *
 * BwPostman edit template sub-template html for backend.
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
use Joomla\CMS\Plugin\PluginHelper;

// Load the tooltip behavior for the notes
HtmlHelper::_('bootstrap.tooltip');
HtmlHelper::_('behavior.keepalive');

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
	<form action="<?php echo Route::_('index.php?option=com_bwpostman&view=template&layout=default_html&id=' . (int) $this->item->id); ?>"
			method="post" name="adminForm" id="adminForm" class="form-horizontal">
		<fieldset class="adminform">
			<legend>
				<?php echo Text::_(
					empty($this->item->id) ? Text::_('COM_BWPOSTMAN_NEW_TPL_HTML') : Text::sprintf('COM_BWPOSTMAN_EDIT_TPL_HTML', $this->item->id)
				); ?>
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
							</ul>
						</div>
					</fieldset>

					<fieldset class="panelform">
						<legend><?php echo Text::_('COM_BWPOSTMAN_TPL_ARTICLE_LABEL'); ?></legend>
						<div class="well well-small">
							<ul class="adminformlist unstyled">
								<?php
								foreach ($this->form->getFieldset('jarticle') as $field) :
									$show = array(
										"jform[article][show_title]",
										"jform[article][show_author]",
										"jform[article][show_createdate]",
										"jform[article][show_readon]"
									);
									if (in_array($field->name, $show)) : ?>
										<li><?php echo $field->label; ?>
											<div class="controls clearfix"><?php echo $field->input; ?></div>
										</li>
										<?php
									endif;
								endforeach; ?>
							</ul>
						</div>
					</fieldset>
					<p><span class="required_description"><?php echo Text::_('COM_BWPOSTMAN_REQUIRED'); ?></span></p>
					<?php
						echo HtmlHelper::_('tabs.panel', Text::_('COM_BWPOSTMAN_TPL_CSS_LABEL'), 'panel2');
					?>
					<fieldset class="panelform">
						<ul class="adminformlist unstyled">
							<li>
								<div><?php echo Text::_('COM_BWPOSTMAN_TPL_CSS_DESC'); ?></div>
								<div class="well well-small">
									<?php echo $this->form->getInput('tpl_css'); ?>
								</div>
							</li>
						</ul>
					</fieldset>
					<?php
						echo HtmlHelper::_('tabs.panel', Text::_('COM_BWPOSTMAN_TPL_HTML_LABEL'), 'panel3');
					?>
					<fieldset class="panelform">
						<ul class="adminformlist unstyled">
							<li>
								<div><?php echo Text::_('COM_BWPOSTMAN_TPL_HTML_DESC'); ?></div>
									<div class="well well-small">
										<?php echo $this->form->getInput('tpl_html'); ?>
										<?php
										$link = Uri::base() . '#';
										if(PluginHelper::isEnabled('bwpostman', 'personalize')) {
											$button_text = Text::_('COM_BWPOSTMAN_TPL_HTML_PERS_BUTTON');
											$linktexts = array(
												'PERS' => $button_text,
												'[FIRSTNAME]',
												'[LASTNAME]',
												'[FULLNAME]',
												'[%content%]',
												'[%unsubscribe_link%]',
												'[%edit_link%]',
												'[%impressum%]'
											);
										}
										else
										{
											$linktexts = array(
												'[FIRSTNAME]',
												'[LASTNAME]',
												'[FULLNAME]',
												'[%content%]',
												'[%unsubscribe_link%]',
												'[%edit_link%]',
												'[%impressum%]'
											);
										}

										foreach ($linktexts as $key => $linktext) {
											echo "                    <a class=\"btn btn-small pull-left\"
												onclick=\"buttonClick('" . $linktext . "','jform_tpl_html');
												return false;\" href=\"" . $link . "\">" . $linktext . "</a>";
											echo '                     <p>&nbsp;' . Text::_('COM_BWPOSTMAN_TPL_HTML_DESC' . $key) . '</p>';
										}

										if(PluginHelper::isEnabled('bwpostman', 'personalize'))
										{
											echo Text::_('COM_BWPOSTMAN_TPL_HTML_DESC_PERSONALIZE');
										}
										?>
									</div>
								<div class="clr clearfix"></div>
							</li>
						</ul>
					</fieldset>
					<?php
						echo HtmlHelper::_('tabs.panel', Text::_('COM_BWPOSTMAN_TPL_TAGS_LABEL'), 'panel4');
					?>
					<fieldset class="panelform">
						<legend><?php echo Text::_('COM_BWPOSTMAN_TPL_TAGS_LABEL'); ?></legend>
						<div><?php echo Text::_('COM_BWPOSTMAN_TPL_TAGS_DESC'); ?></div>
						<?php echo $this->loadTemplate('tpl_tags'); ?>
					</fieldset>
					<?php
					if ($this->permissions['com']['admin'] || $this->permissions['admin']['template'])
					{
						echo HtmlHelper::_('tabs.panel', Text::_('COM_BWPOSTMAN_TPL_FIELDSET_RULES'), 'panel5'); ?>
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
					<p><button class="btn btn-large btn-block btn-primary" type="submit">
						<?php echo Text::_('COM_BWPOSTMAN_TPL_REFRESH_PREVIEW'); ?></button>&nbsp;
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
		<input type="hidden" name="nl_method" value="default_html" />
		<input type="hidden" name="id" value="<?php echo $this->item->id; ?>" />
		<?php echo $this->form->getInput('id'); ?>
		<?php echo $this->form->getInput('asset_id'); ?>
		<?php echo $this->form->getInput('tpl_id', null, 0); ?>
		<?php echo $this->form->getInput('checked_out'); ?>
		<?php echo $this->form->getInput('archive_flag'); ?>
		<?php echo $this->form->getInput('archive_time'); ?>
		<?php echo $this->form->getInput('templates_table_id'); ?>
		<?php echo HtmlHelper::_('form.token'); ?>

		<input type="hidden" id="cancelText" value="<?php echo Text::_('COM_BWPOSTMAN_TPL_CONFIRM_CANCEL', true); ?>" />
		<input type="hidden" id="titleErrorText" value="<?php echo Text::_('COM_BWPOSTMAN_TPL_ERROR_TITLE', true); ?>" />
		<input type="hidden" id="descriptionErrorText" value="<?php echo Text::_('COM_BWPOSTMAN_TPL_ERROR_DESCRIPTION', true); ?>" />

		<p class="bwpm_copyright"><?php echo BwPostmanAdmin::footer(); ?></p>
	</form>

</div>

<?php
Factory::getDocument()->addScript(Uri::root(true) . '/administrator/components/com_bwpostman/assets/js/bwpm_template_html.js');
Factory::getDocument()->addScript(Uri::root(true) . '/administrator/components/com_bwpostman/assets/js/bwpm_template_base.js');
