<?php
/**
 * BwPostman Newsletter Component
 *
 * BwPostman backend element to select a single newsletter for a view in frontend.
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
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Session\Session;
use Joomla\CMS\Language\Text;

defined('_JEXEC') or die('Restricted access');

/**
 * Renders a newsletter element
 *
 * @version %%version_number%%
 * @package BwPostman-Admin
 *
 * @since       1.0.8
 */

class JFormFieldsinglenews extends JFormField
{
	/**
	 * Element name
	 *
	 * @access	protected
	 * @var		string
	 *
	 * @since       1.0.8
	*/
	var	$_name = 'Subject';

	/**
	 * Method to get form input field
	 *
	 * @return string
	 *
	 * @since       1.0.8
	 */
	protected function getinput()
	{
		$doc 		= JFactory::getDocument();
		$fieldName	= $this->name;

		JTable::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_bwpostman/tables');

		$newsletter = JTable::getInstance('newsletters', 'BwPostmanTable');
		if ($this->value) {
			$newsletter->load($this->value);
		}
		else {
			$newsletter->subject = JText::_('COM_BWPOSTMAN_SELECT_NEWSLETTER');
		}

		// The active newsletter id field.
		if ((int)$this->value > 0)
		{
			$value = (int)$this->value;
		}
		else
		{
			$value = '';
		}

		$link = 'index.php?option=com_bwpostman&amp;view=newsletterelement&amp;tmpl=component';

		if (version_compare(JVERSION, '3.999.999', 'le'))
		{
			$link = 'index.php?option=com_bwpostman&amp;view=newsletterelement&amp;tmpl=component';
			JHtml::_('behavior.modal', 'a.modal');

			$js = "
				function SelectNewsletter(id, subject) {
					document.getElementById('a_id').value = id;
					document.getElementById('a_name').value = subject;
					var btn = window.parent.document.getElementById('sbox-btn-close');
					btn.fireEvent('click');
				};";


			// class='required' for client side validation
			$class = '';
			if ($this->required) {
				$class = ' class="required modal-value"';
			}

			$html  = '<span class="input-append">';
			$html .= '<input type="text" class="input-medium" id="a_name" value="' . $newsletter->subject . '" disabled="disabled" size="35" />';
			$html .= '<a class="modal btn hasTooltip" title="' . JHtml::tooltipText('COM_BWPOSTMAN_SELECT_NEWSLETTER') . '" href="' . $link . '" rel="{handler: \'iframe\', size: {x: 800, y: 450}, iframeOptions: {id: \'nlsFrame\'}}"><i class="icon-file"></i> ' . JText::_('JSELECT') . '</a>';
			$html .= "\n<input type=\"hidden\" id=\"a_id\" $class name=\"$fieldName\" value=\"$value\" />";
		}
		else
		{
			// Create the modal id.
			$modalId = 'Newsletter_' . $this->id;
			$modalTitle = Text::_('COM_BWPOSTMAN_SELECT_NEWSLETTER');

			// Add the modal field script to the document head.
			HTMLHelper::_('script', 'system/fields/modal-fields.min.js', array('version' => 'auto', 'relative' => true));

			$link = 'index.php?option=com_bwpostman&amp;view=newsletterelement&amp;tmpl=component&amp' . Session::getFormToken() . '=1';
			$urlSelect = $link . '&amp;function=jSelectNewsletter_' . $this->id;

			$js = "
				window.SelectNewsletter = function (id, subject) {
				window.processModalSelect('Newsletter', '" . $this->id . "', id, subject, '', '', '', '')
				};
				";

			$title = empty($newsletter->subject) ? Text::_('COM_CONTENT_SELECT_AN_ARTICLE') : htmlspecialchars($newsletter->subject, ENT_QUOTES, 'UTF-8');

			$html = '';
			$html .= '<span class="input-group">';
			$html .= '<input class="form-control" id="' . $this->id . '_name" type="text" value="' . $title . '" readonly size="35">';
			$html  .= '<span class="input-group-append">';

			// Select newsletter button
			$html .= '<button'
				. ' class="btn btn-primary"'
				. ' id="' . $this->id . '_select"'
				. ' data-toggle="modal"'
				. ' type="button"'
				. ' data-target="#ModalSelect' . $modalId . '">'
				. '<span class="icon-file" aria-hidden="true"></span> ' . Text::_('JSELECT')
				. '</button>';

			$html .= '</span></span>';

			// Select article modal
			$html .= HTMLHelper::_(
				'bootstrap.renderModal',
				'ModalSelect' . $modalId,
				array(
					'title'       => $modalTitle,
					'url'         => $urlSelect,
					'height'      => '400px',
					'width'       => '800px',
					'bodyHeight'  => 70,
					'modalWidth'  => 80,
					'footer'      => '<button type="button" class="btn btn-secondary" data-dismiss="modal">'
						. Text::_('JLIB_HTML_BEHAVIOR_CLOSE') . '</button>',
				)
			);

		// Note: class='required' for client side validation.
		$class = $this->required ? ' class="required modal-value"' : '';

		$html .= '<input type="hidden" id="' . $this->id . '_id" ' . $class . ' data-required="' . (int) $this->required . '" name="' . $this->name
			. '" data-text="' . htmlspecialchars(Text::_('COM_BWPOSTMAN_SELECT_NEWSLETTER', true), ENT_COMPAT, 'UTF-8') . '" value="' . $value . '">';
		}

		$doc->addScriptDeclaration($js);
		return $html;
	}
}

