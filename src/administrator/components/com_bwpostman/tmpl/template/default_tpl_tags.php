<?php
/**
 * BwPostman Newsletter Component
 *
 * BwPostman edit template sub-template tpl_tags for backend.
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

// No direct access.
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;

defined('_JEXEC') or die;

echo HTMLHelper::_('uitab.startTabSet', 'tpl_tags', array('startOffset' => 0));
echo HTMLHelper::_('uitab.addTab', 'tpl_tags', 'tpl_tag1', Text::_('COM_BWPOSTMAN_TPL_TAGS_HEAD_LABEL'));

echo Text::_('COM_BWPOSTMAN_TPL_HEAD_DESC');
?>
<fieldset class="panelform">
	<div class="row">
		<div class="col-md-12">
			<div class="control-group">
				<div class="control-label">
					<?php echo $this->form->getLabel('tpl_tags_head'); ?>
				</div>
				<div class="controls">
					<?php echo $this->form->getInput('tpl_tags_head'); ?>
				</div>
			</div>

			<div class="control-group">
				<p>
					<label>
						<?php echo Text::_('COM_BWPOSTMAN_TPL_TAGS_STANDARD_LABEL'); ?>
					</label>
				</p>
				<div class="textarea inputbox"><?php echo nl2br(htmlentities($this->headTag)); ?></div>
			</div>

			<div class="control-group">
				<?php echo $this->form->getLabel('tpl_tags_head_advanced'); ?>
				<?php echo $this->form->getInput('tpl_tags_head_advanced'); ?>
			</div>
		</div>
	</div>
</fieldset>
<?php
echo HTMLHelper::_('uitab.endTab');

echo HTMLHelper::_('uitab.addTab', 'tpl_tags', 'tpl_tag2', Text::_('COM_BWPOSTMAN_TPL_TAGS_BODY_LABEL'));
echo Text::_('COM_BWPOSTMAN_TPL_HEAD_DESC');
?>
<fieldset class="panelform">
	<div class="row">
		<div class="col-md-12">
			<div class="control-group">
				<div class="control-label">
					<?php echo $this->form->getLabel('tpl_tags_body'); ?>
				</div>
				<div class="controls">
					<?php echo $this->form->getInput('tpl_tags_body'); ?>
				</div>
			</div>

			<div class="control-group">
				<p>
					<label>
						<?php echo Text::_('COM_BWPOSTMAN_TPL_TAGS_STANDARD_LABEL'); ?>
					</label>
				</p>
				<div class="textarea inputbox"><?php echo nl2br(htmlentities($this->bodyTag)); ?></div>
			</div>

			<div class="control-group">
				<?php echo $this->form->getLabel('tpl_tags_body_advanced'); ?>
				<?php echo $this->form->getInput('tpl_tags_body_advanced'); ?>
			</div>
		</div>
	</div>
</fieldset>
<?php
echo HTMLHelper::_('uitab.endTab');

echo HTMLHelper::_('uitab.addTab', 'tpl_tags', 'tpl_tag3', Text::_('COM_BWPOSTMAN_TPL_TAGS_ARTICLE_LABEL'));
echo Text::_('COM_BWPOSTMAN_TPL_ARTICLE_DESC');
?>
<fieldset class="panelform">
	<div class="row">
		<div class="col-md-12">
			<div class="control-group">
				<div class="control-label">
					<?php echo $this->form->getLabel('tpl_tags_article'); ?>
				</div>
				<div class="controls">
					<?php echo $this->form->getInput('tpl_tags_article'); ?>
				</div>
			</div>

			<div class="control-group">
				<p>
					<label>
						<?php echo Text::_('COM_BWPOSTMAN_TPL_TAGS_STANDARD_LABEL'); ?>
					</label>
				</p>
				<div class="textarea inputbox"><?php echo nl2br(htmlentities($this->articleTagBegin)); ?></div>
			</div>

			<div class="control-group">
				<p><?php echo Text::_('COM_BWPOSTMAN_TPL_TAGS_ARTICLE_INFO'); ?></p>
				<div class="textarea inputbox"><?php echo nl2br(htmlentities($this->articleTagEnd)); ?></div>
			</div>

			<div class="control-group">
				<?php echo $this->form->getLabel('tpl_tags_article_advanced_b'); ?>
				<?php echo $this->form->getInput('tpl_tags_article_advanced_b'); ?>
			</div>

			<div class="control-group">
				<p><?php echo Text::_('COM_BWPOSTMAN_TPL_TAGS_ARTICLE_INFO'); ?></p>
				<?php echo $this->form->getInput('tpl_tags_article_advanced_e'); ?>
			</div>
		</div>
	</div>
</fieldset>
<?php
echo HTMLHelper::_('uitab.endTab');

echo HTMLHelper::_('uitab.addTab', 'tpl_tags', 'tpl_tag4', Text::_('COM_BWPOSTMAN_TPL_TAGS_READON_LABEL'));
echo Text::_('COM_BWPOSTMAN_TPL_READON_DESC');
?>
<fieldset class="panelform">
	<div class="row">
		<div class="col-md-12">
			<div class="control-group">
				<div class="control-label">
					<?php echo $this->form->getLabel('tpl_tags_readon'); ?>
				</div>
				<div class="controls">
					<?php echo $this->form->getInput('tpl_tags_readon'); ?>
				</div>
			</div>

			<div class="control-group">
				<p>
					<label>
						<?php echo Text::_('COM_BWPOSTMAN_TPL_TAGS_STANDARD_LABEL'); ?>
					</label>
				</p>
				<div class="textarea inputbox"><?php echo nl2br(htmlentities($this->readonTag)); ?></div>
			</div>

			<div class="control-group">
				<?php echo $this->form->getLabel('tpl_tags_readon_advanced'); ?>
				<?php echo $this->form->getInput('tpl_tags_readon_advanced'); ?>
			</div>
		</div>
	</div>
</fieldset>
<?php
echo HTMLHelper::_('uitab.endTab');

echo HTMLHelper::_('uitab.addTab', 'tpl_tags', 'tpl_tag5', Text::_('COM_BWPOSTMAN_TPL_TAGS_LEGAL_LABEL'));
echo Text::_('COM_BWPOSTMAN_TPL_LEGAL_DESC');
?>
	<fieldset class="panelform">
		<div class="row">
			<div class="col-md-12">
				<div class="control-group">
					<div class="control-label">
						<?php echo $this->form->getLabel('tpl_tags_legal'); ?>
					</div>
					<div class="controls">
						<?php echo $this->form->getInput('tpl_tags_legal'); ?>
					</div>
				</div>

				<div class="control-group">
					<p>
						<label>
							<?php echo Text::_('COM_BWPOSTMAN_TPL_TAGS_STANDARD_LABEL'); ?>
						</label>
					</p>
					<div class="textarea inputbox"><?php echo nl2br(htmlentities($this->legalTagBegin)); ?></div>
				</div>

				<div class="control-group">
					<p><?php echo Text::_('COM_BWPOSTMAN_TPL_TAGS_LEGAL_INFO'); ?></p>
					<div class="textarea inputbox"><?php echo nl2br(htmlentities($this->legalTagEnd)); ?></div>
				</div>

				<div class="control-group">
					<?php echo $this->form->getLabel('tpl_tags_legal_advanced_b'); ?>
						<?php echo $this->form->getInput('tpl_tags_legal_advanced_b'); ?>
				</div>

				<div class="control-group">
					<p><?php echo Text::_('COM_BWPOSTMAN_TPL_TAGS_LEGAL_INFO'); ?></p>
					<?php echo $this->form->getInput('tpl_tags_legal_advanced_e'); ?>
				</div>
			</div>
		</div>
	</fieldset>

<?php
echo HTMLHelper::_('uitab.endTab');

echo HTMLHelper::_('uitab.endTabSet');


