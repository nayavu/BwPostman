<?php
/**
 * BwPostman Newsletter Component
 *
 * BwPostman all subscribers test-recipients template for backend.
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

use Joomla\CMS\Language\Text;

// Require helper class
require_once(JPATH_COMPONENT_ADMINISTRATOR . '/helpers/htmlhelper.php');

$user		= JFactory::getUser();
$userId		= $user->get('id');
$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));

$colNum = 7;
?>

<script type="text/javascript">
	/* <![CDATA[ */
	function changeTab(tab)
	{
		if (tab != 'default_testrecipients')
		{
			document.adminForm.tab.setAttribute('value',tab);
		}
	}

	function OnlyFiltered(onlyFiltered) // Get the selected value from modal box
	{
		if (onlyFiltered === '1') {
			document.getElementById('mlToExport').value = '<?php echo $this->filterMl; ?>';
		}

		Joomla.submitbutton('subscribers.exportSubscribers', document.adminForm);
	}

	Joomla.submitbutton = function (pressbutton)
	{
		if (pressbutton == 'subscriber.archive')
		{
			ConfirmArchive = confirm("<?php echo JText::_('COM_BWPOSTMAN_SUB_CONFIRM_ARCHIVE', true); ?>");
			if (ConfirmArchive == true)
			{
				submitform(pressbutton);
				return;
			}
		}
		else
		{
			submitform(pressbutton);
		}
	};
	/* ]]> */
</script>

<div id="bwp_view_lists">
	<form action="<?php echo JRoute::_('index.php?option=com_bwpostman&view=subscribers'); ?>"
			method="post" name="adminForm" id="adminForm">
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
				echo JLayoutHelper::render(
					'default',
					array('view' => $this, 'tab' => 'unconfirmed'),
					$basePath = JPATH_ADMINISTRATOR . '/components/com_bwpostman/layouts/searchtools'
				);
				?>

				<div class="row-fluid">
					<div class="form-horizontal">
						<ul class="bwp_tabs">
							<li class="closed">
								<button onclick="return changeTab('confirmed');" class="buttonAsLink" id="tab-confirmed">
									<?php echo JText::_('COM_BWPOSTMAN_SUB_CONFIRMED'); ?>
								</button>
							</li>
							<li class="closed">
								<button onclick="return changeTab('unconfirmed');" class="buttonAsLink" id="tab-unconfirmed">
									<?php echo JText::_('COM_BWPOSTMAN_SUB_UNCONFIRMED'); ?>
								</button>
							</li>
							<li class="open">
								<button onclick="return changeTab('testrecipients');" class="buttonAsLink_open" id="tab-testrecipients">
									<?php echo JText::_('COM_BWPOSTMAN_TEST'); ?>
								</button>
							</li>
						</ul>
					</div>
					<div class="clr clearfix"></div>

					<div class="current">
						<table id="main-table-bw-testrecipients" class="table bw-testrecipients">
							<thead>
							<tr>
								<th style="width: 1%;" class="text-center">
									<!-- <input type="checkbox" name="checkall-toggle" value=""
										 title="<?php echo Text::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" /> -->
								</th>
								<th class="d-none d-md-table-cell" style="min-width: 100px;" scope="col">
									<?php echo JHtml::_('searchtools.sort',  'COM_BWPOSTMAN_SUB_NAME', 'a.name', $listDirn, $listOrder); ?>
								</th>
								<th class="d-none d-md-table-cell" style="min-width: 80px;" scope="col">
									<?php echo JHtml::_('searchtools.sort',  'COM_BWPOSTMAN_SUB_FIRSTNAME', 'a.firstname', $listDirn, $listOrder); ?>
								</th>
								<?php if($this->params->get('show_gender')) { ?>
									<th class="d-none d-md-table-cell" style="width: 7%;" scope="col">
										<?php echo JHtml::_('searchtools.sort',  'COM_BWPOSTMAN_SUB_GENDER', 'a.gender', $listDirn, $listOrder); ?>
									</th>
								<?php } ?>
								<th class="d-none d-md-table-cell" style="min-width: 150px;" scope="col">
									<?php echo JHtml::_('searchtools.sort', 'COM_BWPOSTMAN_EMAIL', 'a.email', $listDirn, $listOrder); ?>
								</th>
								<th class="d-none d-md-table-cell" style="width: 7%;" scope="col">
									<?php echo JHtml::_('searchtools.sort',  'COM_BWPOSTMAN_EMAILFORMAT', 'a.emailformat', $listDirn, $listOrder); ?>
								</th>
								<th class="d-none d-md-table-cell" style="width: 7%;" scope="col">
									<?php echo JHtml::_('searchtools.sort',  'COM_BWPOSTMAN_JOOMLA_USERID', 'a.user_id', $listDirn, $listOrder); ?>
								</th>
								<th class="d-none d-md-table-cell" style="width: 3%;" scope="col">
									<?php echo JHtml::_('searchtools.sort',  'NUM', 'a.id', $listDirn, $listOrder); ?>
								</th>
							</tr>
							</thead>
							<tbody>
							<?php
							if (count($this->items))
							{
								foreach ($this->items as $i => $item) :
									$name		= ($item->name) ? $item->name : Text::_('COM_BWPOSTMAN_SUB_NONAME');
									?>
								<tr class="row<?php echo $i % 2; ?>">
									<td align="center"><?php echo JHtml::_('grid.id', $i, $item->id, 0, 'cid', 'tb'); ?></td>
									<td>
										<?php
										if ($item->checked_out)
										{
											echo JHtml::_(
												'jgrid.checkedout',
												$i,
												$item->editor,
												$item->checked_out_time,
												'subscribers.',
												BwPostmanHelper::canCheckin('subscriber', $item->checked_out)
											);
										}

										if (BwPostmanHelper::canEdit('subscriber', $item))
										{ ?>
											<a href="<?php echo JRoute::_('index.php?option=com_bwpostman&task=subscriber.edit&id=' . $item->id); ?>">
												<?php echo $this->escape($name); ?></a>
											<?php
										}
										else
										{
											echo $this->escape($name);
										} ?>
									</td>
									<td><?php echo $item->firstname; ?></td>
									<?php
									if($this->params->get('show_gender'))
									{
										$colNum = 8; ?>
										<td>
											<?php
											if ($item->gender === '1')
											{
												echo Text::_('COM_BWPOSTMAN_FEMALE');
											}
											elseif ($item->gender === '0')
											{
												echo Text::_('COM_BWPOSTMAN_MALE');
											}
											else
											{
												echo Text::_('COM_BWPOSTMAN_NO_GENDER');
											}
											?>
										</td>
										<?php
									} ?>
									<td><?php echo $item->email; ?></td>
									<td align="center"><?php echo ($item->emailformat) ? Text::_('COM_BWPOSTMAN_HTML') : Text::_('COM_BWPOSTMAN_TEXT')?></td>
									<td align="center"><?php echo ($item->user_id) ? $item->user_id : ''; ?></td>
									<td align="center"><?php echo $item->id; ?></td>
									</tr><?php
								endforeach;
							}
							else
							{
								// if no data ?>
								<tr class="row1">
								<td colspan="<?php echo $colNum; ?>"><strong><?php echo Text::_('COM_BWPOSTMAN_NO_DATA'); ?></strong></td>
								</tr><?php
							}
							?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="pagination"><?php echo $this->pagination->getListFooter(); ?></div>
			<p class="bwpm_copyright"><?php echo BwPostmanAdmin::footer(); ?></p>

			<?php //Load the batch processing form. ?>
			<?php echo $this->loadTemplate('batch'); ?>

			<input type="hidden" name="task" value="" />
			<input type="hidden" id="tab" name="tab" value="testrecipients" />
			<input type="hidden" name="tpl" value="testrecipients" />
			<input type="hidden" name="boxchecked" value="0" />
			<input type="hidden" id="mlToExport" name="mlToExport" value="" />

			<?php echo JHtml::_('form.token'); ?>
		</div>
	</form>
</div>

