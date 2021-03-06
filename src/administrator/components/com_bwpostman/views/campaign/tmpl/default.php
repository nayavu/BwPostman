<?php
/**
 * BwPostman Newsletter Component
 *
 * BwPostman single campaigns form template for backend.
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

//Load tabs behavior for the Tabs
//jimport('joomla.html.html.tabs');
require_once(JPATH_COMPONENT_ADMINISTRATOR . '/helpers/bwtabs.php');

// Load the modal behavior for the newsletter preview
HtmlHelper::_('behavior.modal', 'a.popup');

$tab_options = array(
	'onActive' => 'function(title, description){
		description.setStyle("display", "block");
		title.addClass("open").removeClass("closed");
	}',
	'onBackground' => 'function(title, description){
		description.setStyle("display", "none");
		title.addClass("closed").removeClass("open");
	}',
	'useCookie' => 'true',  // note the quotes around true, since it must be a string.
							// But if you put false there, you must not use quotes otherwise JHtmlBwTabs will handle it as true
);

/**
 * BwPostman Single Campaign Layout
 *
 * @package 	BwPostman-Admin
 * @subpackage 	Campaigns
 */

?>

<div id="bwp_editform">
	<?php
	if ($this->queueEntries)
	{
		Factory::getApplication()->enqueueMessage(Text::_('COM_BWPOSTMAN_ENTRIES_IN_QUEUE'), 'warning');
	}
	?>
	<form action="<?php echo Route::_('index.php?option=com_bwpostman&layout=default&id=' . (int) $this->item->id); ?>"
			method="post" name="adminForm" id="adminForm">
		<?php
		echo JHtmlBwTabs::start('bwp-cam-nl-pane', $tab_options);

			// Start Tab basic
		$title = Text::_('COM_BWPOSTMAN_NEW_CAM');

		if ($this->item->id)
		{
			$title = Text::sprintf('COM_BWPOSTMAN_EDIT_CAM', $this->item->id);
		}

		echo JHtmlBwTabs::panel($title, 'basic', '');
		echo $this->loadTemplate('basic');

		// Start Tab assigned/unsent newsletters
		$text	= Text::_('COM_BWPOSTMAN_CAM_UNSENT_NLS');
		echo JHtmlBwTabs::panel($text, 'unsent', '');
		echo $this->loadTemplate('unsent');

		// Start Tab sent newsletters
		echo JHtmlBwTabs::panel(Text::_('COM_BWPOSTMAN_NL_SENT'), 'sent', '');
		echo $this->loadTemplate('sent');

		// Start Tab permissions
		if ($this->permissions['com']['admin'] || $this->permissions['admin']['campaign'])
		{
			echo JHtmlBwTabs::panel(Text::_('COM_BWPOSTMAN_CAM_FIELDSET_RULES'), 'rules', '');
			echo $this->loadTemplate('rules');
		}

		echo JHtmlBwTabs::end();
		?>

		<input type="hidden" name="task" value="" />

		<?php echo $this->form->getInput('id'); ?>
		<?php echo $this->form->getInput('asset_id'); ?>
		<?php echo $this->form->getInput('checked_out'); ?>
		<?php echo $this->form->getInput('archive_flag'); ?>
		<?php echo $this->form->getInput('archive_time'); ?>
		<?php echo HtmlHelper::_('form.token'); ?>

		<input type="hidden" id="alertTitle" value="<?php echo Text::_('COM_BWPOSTMAN_CAM_ERROR_TITLE', true); ?>" />
		<input type="hidden" id="alertRecipients" value="<?php echo Text::_('COM_BWPOSTMAN_CAM_ERROR_NO_RECIPIENTS_SELECTED'); ?>" />
	</form>

	<p class="bwpm_copyright"><?php echo BwPostmanAdmin::footer(); ?></p>
</div>
