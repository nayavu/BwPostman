<?php
/**
 * BwPostman Newsletter Component
 *
 * BwPostman edit template sub-template header for backend.
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
defined('_JEXEC') or die('Restricted access');

$fieldSets = $this->form->getFieldsets('header');

foreach ($fieldSets as $name => $fieldSet) :
	?>
	<fieldset class="panelform options-grid-form options-grid-form-full">
		<div>
			<?php foreach ($this->form->getFieldset($name) as $field) : ?>
				<?php echo $field->renderField(); ?>
			<?php endforeach; ?>
		</div>
	</fieldset>
<?php endforeach;

