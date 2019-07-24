<?php
/**
 * BwPostman Newsletter Component
 *
 * BwPostman all campaigns default template for backend.
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
JHtml::_('formbehavior.chosen', 'select');
JHtml::_('behavior.multiselect');

$user		= JFactory::getUser();
$userId		= $user->get('id');
$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));


/**
 * BwPostman Campaigns Layout
 *
 * @package 	BwPostman-Admin
 * @subpackage 	Campaigns
 */
?>

<script type="text/javascript">
/* <![CDATA[ */
	function confirmArchive(archive_value) // Get the selected value from modal box
	{
		document.adminForm.archive_nl.value = archive_value;
		Joomla.submitbutton('campaign.archive');
	}
/* ]]> */
</script>

<div id="bwp_view_lists">
	<form action="<?php echo JRoute::_('index.php?option=com_bwpostman&view=campaigns'); ?>"
			method="post" name="adminForm" id="adminForm">
		<div class="row">
			<div class="col-md-12">
				<div id="j-main-container" class="j-main-container">
					<?php
						// Search tools bar
						echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this));
					?>

					<table id="main-table" class="table">
						<thead>
							<tr>
								<th style="width: 1%;" class="text-center">
									<input type="checkbox" name="checkall-toggle" value=""
											title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
								</th>
								<th class="d-none d-md-table-cell" style="min-width: 150px;" scope="col">
									<?php echo JHtml::_('searchtools.sort',  'COM_BWPOSTMAN_CAM_TITLE', 'a.title', $listDirn, $listOrder); ?></th>
								<th class="d-none d-md-table-cell" style="min-width: 150px;" scope="col">
									<?php echo JHtml::_('searchtools.sort',  'COM_BWPOSTMAN_CAM_DESCRIPTION', 'a.description', $listDirn, $listOrder); ?>
								</th>
								<th class="d-none d-md-table-cell" style="width: 10%;" scope="col">
									<?php echo JHtml::_('searchtools.sort',  'COM_BWPOSTMAN_CAM_NL_NUM', 'newsletters', $listDirn, $listOrder); ?>
								</th>
								<th class="d-none d-md-table-cell" style="width: 3%;" scope="col" aria-sort="ascending">
									<?php echo JHtml::_('searchtools.sort',  'NUM', 'a.id', $listDirn, $listOrder); ?>
								</th>
							</tr>
						</thead>
						<tbody>
							<?php
							if (count($this->items) > 0)
							{
								foreach ($this->items as $i => $item)
								{
									?>
									<tr class="row<?php echo $i % 2; ?>">
										<td align="center"><?php echo JHtml::_('grid.id', $i, $item->id); ?></td>
										<td>
										<?php
										if ($item->checked_out)
										{ ?>
											<?php
											echo JHtml::_(
												'jgrid.checkedout',
												$i,
												$item->editor,
												$item->checked_out_time,
												'campaigns.',
												BwPostmanHelper::canCheckin('campaign', $item->checked_out)
											);
										} ?>
										<?php
										if (BwPostmanHelper::canEdit('campaign', $item))
										{ ?>
											<a href="<?php echo JRoute::_('index.php?option=com_bwpostman&task=campaign.edit&id=' . $item->id); ?>">
												<?php echo $this->escape($item->title); ?>
											</a> <?php
										}
										else
										{
											echo $this->escape($item->title);
										} ?>
										</td>
										<td><?php echo $item->description; ?></td>
										<td align="center"><?php echo $item->newsletters; ?></td>
										<td align="center"><?php echo $item->id; ?></td>
									</tr>
								<?php
								}
							}
							else
							{ ?>
								<tr class="row1">
									<td colspan="5"><strong><?php echo JText::_('COM_BWPOSTMAN_NO_DATA'); ?></strong></td>
								</tr><?php
							}
							?>
						</tbody>
					</table>
				</div>
			</div>
			<div class="pagination"><?php echo $this->pagination->getListFooter(); ?></div>
			<p class="bwpm_copyright"><?php echo BwPostmanAdmin::footer(); ?></p>

			<input type="hidden" name="task" value="" />
			<input type="hidden" name="boxchecked" value="0" />
			<input type="hidden" name="archive_nl" value="0" />
			<?php echo JHtml::_('form.token'); ?>

		</div>
	</form>
</div>