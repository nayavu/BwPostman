<?php
/**
 * BwPostman Newsletter Component
 *
 * BwPostman archive newsletters template for backend.
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
use Joomla\CMS\Layout\LayoutHelper;

// Load the tooltip behavior for the notes
HTMLHelper::_('behavior.tooltip');

// Load the modal behavior for the newsletter preview
HTMLHelper::_('behavior.modal');
HTMLHelper::_('formbehavior.chosen', 'select');

$user		= Factory::getUser();
$userId		= $user->get('id');
$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));

$nullDate = Factory::getDbo()->getNullDate();
//Set context and layout state for filters
$this->context	= 'archive.newsletters';
$tab			= Factory::getApplication()->setUserState($this->context . '.tab', 'newsletters');
//
/**
 * BwPostman Archived Newsletters Layout
 *
 * @package 	BwPostman-Admin
 * @subpackage 	Archive
 */
?>

<div id="bwp_view_lists">
	<form action="<?php echo Route::_($this->request_url); ?>" method="post" name="adminForm" id="adminForm" class="form-inline">
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
			echo LayoutHelper::render(
				'default',
				array('view' => $this, 'tab' => $tab),
				$basePath = JPATH_ADMINISTRATOR . '/components/com_bwpostman/layouts/searchtools'
			);
		?>

			<div class="row-fluid">
					<table id="main-table" class="adminlist table table-striped">
						<tbody>
							<tr>
								<td valign="top" width="90%">
									<ul class="bwp_tabs">
										<?php
										if ($this->permissions['view']['archive'] && BwPostmanHelper::canArchive('newsletter', 1, 0))
										{
										?>
											<li class="open"><!-- We need to use the setAttribute-function because of the IE -->
												<button onclick="layout.setAttribute('value','newsletters');this.form.submit();"
														class="buttonAsLink_open"><?php echo Text::_('COM_BWPOSTMAN_ARC_NLS'); ?>
												</button>
											</li>
											<?php
										}

										if ($this->permissions['view']['archive'] && BwPostmanHelper::canArchive('subscriber', 1, 0))
										{
										?>
											<li class="closed">
												<button onclick="layout.setAttribute('value','subscribers');this.form.submit();"
														class="buttonAsLink"><?php echo Text::_('COM_BWPOSTMAN_ARC_SUBS'); ?>
												</button>
											</li>
											<?php
										}

										if ($this->permissions['view']['archive'] && BwPostmanHelper::canArchive('campaign', 1, 0))
										{
										?>
											<li class="closed">
												<button onclick="layout.setAttribute('value','campaigns');this.form.submit();"
														class="buttonAsLink"><?php echo Text::_('COM_BWPOSTMAN_ARC_CAMS'); ?>
												</button>
											</li>
											<?php
										}

										if ($this->permissions['view']['archive'] && BwPostmanHelper::canArchive('mailinglist', 1, 0))
										{
										?>
											<li class="closed">
												<button onclick="layout.setAttribute('value','mailinglists');this.form.submit();"
														class="buttonAsLink"><?php echo Text::_('COM_BWPOSTMAN_ARC_MLS'); ?>
												</button>
											</li>
											<?php
										}

										if ($this->permissions['view']['archive'] && BwPostmanHelper::canArchive('template', 1, 0))
										{
										?>
											<li class="closed">
												<button onclick="layout.setAttribute('value','templates');
													this.form.submit();" class="buttonAsLink"><?php echo Text::_('COM_BWPOSTMAN_ARC_TPLS'); ?>
												</button>
											</li>
											<?php
										}
										?>
									</ul>

									<div class="current">
										<table class="adminlist">
											<thead>
												<tr>
													<th width="30" nowrap="nowrap">
														<input type="checkbox" name="checkall-toggle" value=""
																title="<?php echo Text::_('JGLOBAL_CHECK_ALL'); ?>"
																onclick="Joomla.checkAll(this)" />
													</th>
													<th>
														<?php echo HTMLHelper::_('searchtools.sort',  'Subject', 'a.subject', $listDirn, $listOrder); ?>
													</th>
													<th nowrap="nowrap">
														<?php echo HTMLHelper::_(
															'searchtools.sort',
															'COM_BWPOSTMAN_NL_DESCRIPTION',
															'a.description',
															$listDirn,
															$listOrder
														); ?>
													</th>
													<th width="150" nowrap="nowrap">
														<?php echo HTMLHelper::_(
															'searchtools.sort',
															'COM_BWPOSTMAN_NL_MAILING_DATE',
															'a.mailing_date',
															$listDirn,
															$listOrder
														); ?>
													</th>
													<th width="100" nowrap="nowrap">
														<?php echo HTMLHelper::_('searchtools.sort',  'Author', 'author', $listDirn, $listOrder); ?>
													</th>
													<th width="100" nowrap="nowrap">
														<?php echo HTMLHelper::_(
															'searchtools.sort',
															'COM_BWPOSTMAN_CAM_NAME',
															'campaigns',
															$listDirn,
															$listOrder
														); ?>
													</th>
													<th nowrap="nowrap">
														<?php echo HTMLHelper::_(
															'searchtools.sort',
															'COM_BWPOSTMAN_PUBLISHED',
															'a.published',
															$listDirn,
															$listOrder
														); ?>
													</th>
													<th nowrap="nowrap">
														<?php echo HTMLHelper::_(
															'searchtools.sort',
															'COM_BWPOSTMAN_NL_PUBLISH_UP',
															'a.publish_up',
															$listDirn,
															$listOrder
														); ?>
														<br />
														<?php echo HTMLHelper::_(
															'searchtools.sort',
															'COM_BWPOSTMAN_NL_PUBLISH_DOWN',
															'a.publish_down',
															$listDirn,
															$listOrder
														); ?>
													</th>
													<th width="150">
														<?php echo HTMLHelper::_(
															'searchtools.sort',
															'COM_BWPOSTMAN_ARC_ARCHIVE_DATE',
															'a.archive_date',
															$listDirn,
															$listOrder
														); ?>
													</th>
													<th width="30" nowrap="nowrap">
														<?php echo HTMLHelper::_('searchtools.sort',  'NUM', 'a.id', $listDirn, $listOrder); ?>
													</th>
												</tr>
											</thead>
											<tfoot>
												<tr>
													<td colspan="10"><?php echo $this->pagination->getListFooter(); ?></td>
												</tr>
											</tfoot>
											<tbody>
											<?php
											if (count($this->items) > 0) {
												foreach ($this->items as $i => $item) :
													?>
													<tr class="row<?php echo $i % 2; ?>">
														<td align="center"><?php echo HTMLHelper::_('grid.id', $i, $item->id); ?></td>
														<td>
															<?php
															echo $item->subject;
															if ($item->mailing_date !== $nullDate)
															{ ?>&nbsp;&nbsp;
																<span class="cam_preview">
																	<span class="editlinktip hasTip"
																			title="<?php echo Text::_('COM_BWPOSTMAN_ARC_SHOW_NL');?>::
																			<?php echo $this->escape($item->subject); ?>">
																		<a class="modal" href="
																		<?php echo Route::_(
																			'index.php?option=com_bwpostman&view=newsletter&format=raw&layout=newsletter_html_modal&task=insideModal&nl_id='. $item->id
																		);
																		?>"
																				rel="{handler: 'iframe', size: {x: 650, y: 450}}">
																			<?php echo Text::_('COM_BWPOSTMAN_HTML_NL');?>
																		</a>&nbsp;
																	</span>
																	<span class="editlinktip hasTip" title="
																	<?php echo Text::_('COM_BWPOSTMAN_ARC_SHOW_NL');?>::
																	<?php echo $this->escape($item->subject); ?>">
																		<a class="modal" href="
																		<?php echo Route::_(
																			'index.php?option=com_bwpostman&view=newsletter&format=raw&layout=newsletter_text_modal&task=insideModal&nl_id='. $item->id
																		);?>"
																				rel="{handler: 'iframe', size: {x: 650, y: 450}}">
																			<?php echo Text::_('COM_BWPOSTMAN_TEXT_NL');?>
																		</a>&nbsp;
																	</span>
																</span>
															<?php } ?>
														</td>
														<td align="center"><?php echo $item->description; ?></td>
														<td align="center">
															<?php
															if ($item->mailing_date !== $nullDate)
															{
																echo HTMLHelper::date($item->mailing_date, Text::_('BW_DATE_FORMAT_LC5'));
															}
															?>&nbsp;
														</td>
														<td align="center">
															<?php echo $item->author; ?></td>
														<td align="center">
															<?php echo $item->campaigns;
															if ($item->campaign_archive_flag)
															{
																echo " (" . Text::_('ARCHIVED') . ")";
															}
															?>
														</td>
														<td align="center">
															<?php echo HTMLHelper::_(
																'jgrid.published',
																$item->published,
																$i,
																'newsletters.',
																BwPostmanHelper::canEditState('newsletter', (int) $item->id)
															); ?>
														</td>
														<td align="center">
															<p style="text-align: center;">
																<?php echo ($item->publish_up !== $nullDate)
																	? HTMLHelper::date($item->publish_up, Text::_('BW_DATE_FORMAT_LC5'))
																	: '-'; ?>
																<br /></p>
															<p style="text-align: center;">
																<?php echo ($item->publish_down !== $nullDate)
																	? HTMLHelper::date($item->publish_down, Text::_('BW_DATE_FORMAT_LC5'))
																	: '-'; ?>
															</p>
														</td>
														<td align="center">
															<?php echo HTMLHelper::date($item->archive_date, Text::_('BW_DATE_FORMAT_LC5')); ?>
														</td>
														<td align="center"><?php echo $item->id; ?></td>
													</tr>
												<?php endforeach;
											}
											else { ?>
												<tr class="row1">
													<td colspan="10"><strong><?php echo Text::_('COM_BWPOSTMAN_NO_DATA'); ?></strong></td>
												</tr><?php
											}
											?>
											</tbody>
										</table>
									</div>
								</td>
							</tr>
						</tbody>
					</table>
					<input type="hidden" name="task" value="" />
					<input type="hidden" name="boxchecked" value="0" />
					<input type="hidden" name="layout" value="newsletters" /><!-- value can change if one clicks on another tab -->
					<input type="hidden" name="tab" value="newsletters" /><!-- value never changes -->
					<input type="hidden" name="view" value="archive" />
					<?php echo HTMLHelper::_('form.token'); ?>
				</div>

			<p class="bwpm_copyright"><?php echo BwPostmanAdmin::footer(); ?></p>
		</div>
	</form>
</div>
