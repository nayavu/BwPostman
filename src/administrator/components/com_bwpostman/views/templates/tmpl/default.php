<?php
/**
 * BwPostman Newsletter Component
 *
 * BwPostman all templates default template for backend.
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
use Joomla\CMS\Layout\LayoutHelper;

HtmlHelper::_('bootstrap.tooltip');
HtmlHelper::_('formbehavior.chosen', 'select');
HtmlHelper::_('behavior.multiselect');

$user		= Factory::getUser();
$userId		= $user->get('id');
$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));

?>

<div id="bwp_view_lists">
	<form action="<?php echo Route::_('index.php?option=com_bwpostman&view=templates'); ?>"
			method="post" name="adminForm" id="adminForm" class="form-inline">
		<?php if (property_exists($this, 'sidebar')) : ?>
			<div id="j-sidebar-container" class="span2">
				<?php echo $this->sidebar; ?>
			</div>
			<div id="j-main-container" class="span10">
		<?php else :  ?>
			<div id="j-main-container">
		<?php endif; ?>
		<?php
			// Search tools bar
			echo LayoutHelper::render('joomla.searchtools.default', array('view' => $this));
		?>

			<div class="row-fluid">
				<table id="main-table" class="adminlist table table-striped">
					<thead>
						<tr>
							<th width="30" nowrap="nowrap">
								<input type="checkbox" name="checkall-toggle" value=""
										title="<?php echo Text::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
							</th>
							<th width="250" nowrap="nowrap">
								<?php echo HtmlHelper::_('searchtools.sort',  'COM_BWPOSTMAN_TPL_TITLE', 'a.title', $listDirn, $listOrder); ?>
							</th>
							<th class="center" align="center" width="110" nowrap="nowrap">
								<?php echo Text::_('COM_BWPOSTMAN_TPL_THUMBNAIL'); ?>
							</th>
							<th class="center" align="center" width="100" nowrap="nowrap">
								<?php echo HtmlHelper::_('searchtools.sort',  'COM_BWPOSTMAN_TPL_FORMAT', 'a.tpl_id', $listDirn, $listOrder); ?>
								</th>
							<th class="center" align="center" width="60" nowrap="nowrap">
								<?php echo Text::_('COM_BWPOSTMAN_TPL_SET_DEFAULT'); ?>
							</th>
							<th class="center" align="center" width="100" nowrap="nowrap">
								<?php echo HtmlHelper::_('searchtools.sort',  'PUBLISHED', 'a.published', $listDirn, $listOrder); ?>
							</th>
							<th nowrap="nowrap">
								<?php echo HtmlHelper::_('searchtools.sort',  'COM_BWPOSTMAN_TPL_DESCRIPTION', 'a.description', $listDirn, $listOrder); ?>
							</th>
							<th width="30" nowrap="nowrap">
								<?php echo HtmlHelper::_('searchtools.sort',  'NUM', 'a.id', $listDirn, $listOrder); ?>
							</th>
						</tr>
					</thead>
					<tbody>
						<?php
						if (count($this->items) > 0)
						{
							foreach ($this->items as $i => $item) :
								?>
								<tr class="row<?php echo $i % 2; ?>">
									<td align="center"><?php echo HtmlHelper::_('grid.id', $i, $item->id); ?></td>
									<td>
									<?php
									if ($item->checked_out)
									{
										echo HtmlHelper::_(
											'jgrid.checkedout',
											$i,
											$item->editor,
											$item->checked_out_time,
											'templates.',
											BwPostmanHelper::canCheckin('template', $item->checked_out)
										);
									}

									if (BwPostmanHelper::canEdit('template', $item))
									{ ?>
										<a href="<?php echo Route::_('index.php?option=com_bwpostman&task=template.edit&id=' . $item->id); ?>">
											<?php echo $this->escape($item->title); ?>
										</a><?php
									}
									else
									{
										echo $this->escape($item->title);
									} ?>
									</td>
									<td class="center" align="center" >
										<?php
										if ($item->thumbnail)
											{
											if (BwPostmanHelper::canEdit('template', $item))
											{ ?>
												<a href="<?php
												echo Route::_('index.php?option=com_bwpostman&task=template.edit&id=' . $item->id);
												?>">
												<img src="<?php echo Uri::root(true) . '/' . $item->thumbnail; ?>" style="width: 100px;" />
												</a><?php
											}
											else
											{ ?>
												<img src="<?php echo Uri::root(true) . '/' . $item->thumbnail; ?>" style="width: 100px;" /><?php
											}
										} ?>
									</td>
									<td class="center" align="center">
										<?php
										if (($item->tpl_id == 998) || ($item->tpl_id > 999))
										{
											echo 'TEXT';
										}
										else
										{
											echo 'HTML';
										}?>
									</td>
									<td class="center" align="center">
										<?php
										echo HtmlHelper::_(
											'jgrid.isdefault',
											($item->standard != '0' && !empty($item->standard)),
											$i,
											'template.',
											BwPostmanHelper::canEditState('template', (int) $item->id) && $item->standard != '1');
										?>
									</td>
									<td class="center" align="center">
										<?php
										echo HtmlHelper::_(
											'jgrid.published',
											$item->published,
											$i,
											'templates.',
											BwPostmanHelper::canEditState('template', (int) $item->id),
											'cb'
										);
										?>
									<td>
									<?php echo nl2br($item->description); ?>
								</td>
									<td align="center"><?php echo $item->id; ?></td>
								</tr><?php
							endforeach;
						}
						else { ?>
							<tr class="row1">
								<td colspan="8"><strong><?php echo Text::_('COM_BWPOSTMAN_NO_DATA'); ?></strong>
								</td>
							</tr><?php
						}
						?>
					</tbody>
				</table>
			</div>
			<div class="pagination"><?php echo $this->pagination->getListFooter(); ?></div>
			<p class="bwpm_copyright"><?php echo BwPostmanAdmin::footer(); ?></p>

			<input type="hidden" name="task" value="" />
			<input type="hidden" name="boxchecked" value="0" />
			<?php echo HtmlHelper::_('form.token'); ?>

			<input type="hidden" id="archiveText" value="<?php echo Text::_('COM_BWPOSTMAN_TPL_CONFIRM_ARCHIVE', true); ?>" />
		</div>
	</form>
</div>
