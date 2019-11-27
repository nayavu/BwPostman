<?php
/**
 * BwPostman Newsletter Module
 *
 * BwPostman default template for module.
 *
 * @version %%version_number%%
 * @package BwPostman-Module
 * @author Romana Boldt, Karl Klostermann
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

JLoader::register('ContentHelperRoute', JPATH_SITE . '/components/com_content/helpers/route.php');

JHtml::_('behavior.tooltip');
JHtml::_('behavior.keepalive');
JHtml::_('behavior.formvalidator');
JHtml::_('formbehavior.chosen', 'select');

// Depends on jQuery UI
JHtml::_('jquery.ui', array('core'));

$n	= count($mailinglists);

$remote_ip  = JFactory::getApplication()->input->server->get('REMOTE_ADDR', '', '');
?>

<?php
// We cannot use the same form name and name for the disclaimer checkbox
// because this will not work if the module and the component will be displayed
// on the same page

?>

<script type="text/javascript">
/* <![CDATA[ */
function checkModRegisterForm()
{
	var form = document.bwp_mod_form;
	var errStr = "";
	var arrCB = document.getElementsByName("mailinglists[]");
	var n =	arrCB.length;
	var check = 0;

	// Validate input fields
	// firstname
	if (document.bwp_mod_form.a_firstname)
	{
		if ((!document.getElementById("a_firstname").value) && (document.getElementById("firstname_field_obligation_mod").value === '1'))
		{
			errStr += "<?php echo JText::_('MOD_BWPOSTMANERROR_FIRSTNAME', true); ?>\n";
		}
	}

	// name
	if (document.bwp_mod_form.a_name)
	{
		if ((!document.getElementById("a_name").value) && (document.getElementById("name_field_obligation_mod").value === '1'))
		{
			errStr += "<?php echo JText::_('MOD_BWPOSTMANERROR_NAME', true); ?>\n";
		}
	}

	// additional field
	if (document.bwp_mod_form.a_special)
	{
		if ((!document.getElementById("a_special").value) && (document.getElementById("special_field_obligation_mod").value === '1'))
		{
			errStr += "<?php echo JText::sprintf('MOD_BWPOSTMAN_SUB_ERROR_SPECIAL', JText::_($paramsComponent->get('special_label') != '' ? JText::_($paramsComponent->get('special_label')) : JText::_('MOD_BWPOSTMAN_SPECIAL'))); ?>\n";
		}
	}

	// email
	var email = document.getElementById("a_email").value;

	if (email === "")
	{
		errStr += "<?php echo JText::_('MOD_BWPOSTMANERROR_EMAIL', true); ?>\n";
	}
	else
	{
	var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,14})+$/;
		if (!filter.test(email))
		{
			errStr += "<?php echo JText::_('MOD_BWPOSTMANERROR_EMAIL_INVALID', true); ?>\n";
			email.focus;
		}
	}
	// mailinglist

	if (n > 1)
	{
		for (i = 0; i < n; i++)
		{
			if (arrCB[i].checked === true)
			{
				check++;
			}
		}
	}
	else
	{
		check++;
	}

	if (check === 0)
	{
		errStr += "<?php echo JText::_('MOD_BWPOSTMANERROR_NL_CHECK'); ?>\n";
	}

	// disclaimer
	if (document.bwp_mod_form.agreecheck_mod)
	{
		if (document.bwp_mod_form.agreecheck_mod.checked === false)
		{
			errStr += "<?php echo JText::_('MOD_BWPOSTMANERROR_DISCLAIMER_CHECK'); ?>\n";
		}
	}

	// captcha
	if (document.bwp_mod_form.stringCaptcha)
	{
		if (document.bwp_mod_form.stringCaptcha.value === '')
		{
			errStr += "<?php echo JText::_('MOD_BWPOSTMANERROR_CAPTCHA_CHECK'); ?>\n";
		}
	}

	// question
	if (document.bwp_mod_form.stringQuestion)
	{
		if (document.bwp_mod_form.stringQuestion.value === '')
		{
			errStr += "<?php echo JText::_('MOD_BWPOSTMANERROR_CAPTCHA_CHECK'); ?>\n";
		}
	}

	if ( errStr !== "" )
	{
		alert( errStr );
		return false;
	}
	else
	{
		form.submit();
	}
}
/* ]]> */
</script>

<noscript>
	<div id="system-message">
		<div class="alert alert-warning">
			<h4 class="alert-heading"><?php echo JText::_('WARNING'); ?></h4>
			<div>
				<p><?php echo JText::_('MOD_BWPOSTMAN_JAVASCRIPTWARNING'); ?></p>
			</div>
		</div>
	</div>
</noscript>

<div id="mod_bwpostman">
	<?php
	if ($n == 0)
	{
		// Don't show registration form if no mailinglist is selectable ?>
		<p class="bwp_mod_error_no_mailinglists"><?php echo addslashes(JText::_('MOD_BWPOSTMANERROR_NO_MAILINGLIST_AVAILABLE')); ?></p> <?php
	}
	else
	{
		// Show registration form only if a mailinglist is selectable ?>

	<form action="<?php echo JRoute::_('index.php?option=com_bwpostman&task=register'); ?>" method="post" id="bwp_mod_form"
			name="bwp_mod_form" class="form-validate form-inline" onsubmit="return checkModRegisterForm();">

		<?php // Spamcheck 1 - Input-field: class="user_hightlight" style="position: absolute; top: -5000px;"
		?>
		<p class="user_hightlight">
			<label for="a_falle"><strong><?php echo addslashes(JText::_('MOD_BWPOSTMANSPAMCHECK')); ?></strong></label>
			<input type="text" name="falle" id="a_falle" size="20"
					title="<?php echo addslashes(JText::_('MOD_BWPOSTMANSPAMCHECK')); ?>" maxlength="50" />
		</p>
		<?php // End Spamcheck
		if ($paramsComponent->get('pretext'))
		{ // Show pretext only if set in basic parameters
			$preText = JText::_($paramsComponent->get('pretext'));
			?>
			<p id="bwp_mod_form_pretext"><?php echo $preText; ?></p>
			<?php
		} // End: Show pretext only if set in basic parameters

		if ($paramsComponent->get('show_gender') == 1)
		{
			// Show formfield gender only if enabled in basic parameters
			?>
			<p id="bwp_mod_form_genderformat">
				<label id="gendermsg_mod">
					<?php echo JText::_('MOD_BWPOSTMANGENDER'); ?>:
				</label>
			</p>
			<div id="bwp_mod_form_genderfield">
				<?php echo $lists['gender']; ?>
			</div>
			<?php
		} // End show gender

		if ($paramsComponent->get('show_firstname_field') OR $paramsComponent->get('firstname_field_obligation'))
		{ // Show firstname-field only if set in basic parameters
			?>
			<p id="bwp_mod_form_firstnamefield"
					class="input<?php echo ($paramsComponent->get('firstname_field_obligation')) ? '-append' : '-xx' ?>">
				<?php
				// Is filling out the firstname field obligating
				isset($subscriber->firstname) ? $sub_firstname = $subscriber->firstname : $sub_firstname = '';
				($paramsComponent->get('firstname_field_obligation'))
					? $required = '<span class="append-area"><i class="icon-star"></i></span>'
					: $required = '';
				?>
				<input type="text" name="a_firstname" id="a_firstname"
						placeholder="<?php echo addslashes(JText::_('MOD_BWPOSTMANFIRSTNAME')); ?>"
						value="<?php echo $sub_firstname; ?>" class="inputbox input-small" maxlength="50" />
				<?php echo $required; ?>
			</p>
			<?php
		}

		if ($paramsComponent->get('show_name_field') OR $paramsComponent->get('name_field_obligation'))
		{
			// Show name-field only if set in basic parameters
			?>
			<p id="bwp_mod_form_namefield" class="input-append">
				<?php // Is filling out the name field obligating
				isset($subscriber->name) ? $sub_name = $subscriber->name : $sub_name = '';
				($paramsComponent->get('name_field_obligation'))
					? $required = '<span class="append-area"><i class="icon-star"></i></span>'
					: $required = ''; ?>
				<input type="text" name="a_name" id="a_name" placeholder="<?php echo addslashes(JText::_('MOD_BWPOSTMANNAME')); ?>"
						value="<?php echo $sub_name; ?>" class="inputbox input-small" maxlength="50" />
				<?php echo $required; ?>
			</p>
			<?php
		} // End: Show name field only if set in basic parameters
		?>

		<?php
		if ($paramsComponent->get('show_special') OR $paramsComponent->get('special_field_obligation'))
		{
			// Show additional field only if set in basic parameters
			?>
			<p id="bwp_mod_form_specialfield" class="input<?php echo ($paramsComponent->get('special_field_obligation')) ? '-append' : '' ?>">
				<?php // Is filling out the additional field obligating
				isset($subscriber->special) ? $sub_special = $subscriber->special : $sub_special = '';
				($paramsComponent->get('special_field_obligation'))
					? $required = '<span class="append-area"><i class="icon-star"></i></span>'
					: $required = ''; ?>
				<input type="text" name="a_special" id="a_special"
						placeholder="<?php echo addslashes(JText::_($paramsComponent->get('special_label') != '' ? JText::_($paramsComponent->get('special_label')) : JText::_('MOD_BWPOSTMAN_SPECIAL'))); ?>"
						value="<?php echo $sub_special; ?>" class="inputbox input-small" maxlength="50" />
				<?php echo $required; ?>
			</p>
			<?php
		} // End: Show additional field only if set in basic parameters
		?>

		<?php isset($subscriber->email) ? $sub_email = $subscriber->email : $sub_email = ''; ?>
		<p id="bwp_mod_form_emailfield" class="input-append">
			<input type="text" id="a_email" name="email" placeholder="<?php echo addslashes(JText::_('MOD_BWPOSTMANEMAIL')); ?>"
					value="<?php echo $sub_email; ?>" class="inputbox input-small" maxlength="100" />
			<span class="append-area"><i class="icon-star"></i></span>
		</p>
		<?php
		if ($paramsComponent->get('show_emailformat') == 1)
		{
			// Show formfield emailformat only if enabled in basic parameters
			?>
			<p id="bwp_mod_form_emailformat">
				<label id="emailformatmsg_mod">
					<?php echo JText::_('MOD_BWPOSTMANEMAILFORMAT'); ?>:
				</label>
			</p>
			<div id="bwp_mod_form_emailformatfield">
				<?php echo $lists['emailformat']; ?>
			</div>
			<?php
		}
		else
		{
			// hidden field with the default emailformat
			?>
			<input type="hidden" name="emailformat" value="<?php echo $paramsComponent->get('default_emailformat'); ?>" />
			<?php
		} // End emailformat
		?>

		<?php // Show available mailinglists
		$n = count($mailinglists);

		$descLength = $params->get('desc_length');

		if (($mailinglists) && ($n > 0))
		{
			if ($n == 1)
			{ ?>
				<input type="checkbox" style="display: none;" id="a_<?php echo "mailinglists0"; ?>" name="<?php echo "mailinglists[]"; ?>"
				title="<?php echo "mailinglists[]"; ?>" value="<?php echo $mailinglists[0]->id; ?>" checked="checked" />
				<?php
				if ($params->get('show_desc') == 1)
				{ ?>
					<p class="mailinglist-description-single"><?php
						echo substr(JText::_($mailinglists[0]->description), 0, $descLength);

						if (strlen(JText::_($mailinglists[0]->description)) > $descLength)
						{
							echo '... ';
							echo JHTML::tooltip(JText::_($mailinglists[0]->description), $mailinglists[0]->title, 'tooltip.png', '', '');
						} ?>
					</p>
					<?php
				}
			}
			else
			{ ?>
				<p id="bwp_mod_form_lists" class="required">
					<?php echo JText::_('MOD_BWPOSTMANLISTS') . ' <i class="icon-star"></i>'; ?>
				</p>
				<div id="bwp_mod_form_listsfield">
				<?php
				foreach ($mailinglists AS $i => $mailinglist)
				{ ?>
					<p class="a_mailinglist_item_<?php echo $i; ?>">
						<input type="checkbox" id="a_<?php echo "mailinglists$i"; ?>" name="<?php echo "mailinglists[]"; ?>"
								title="<?php echo "mailinglists[]"; ?>" value="<?php echo $mailinglist->id; ?>" />
						<span class="mailinglist-title">
							<?php
							echo $mailinglist->title;
							if ($params->get('show_desc') == 1)
							{
							?>:
								</span><br />
								<span class="mailinglist-description">
									<?php
									echo substr(JText::_($mailinglist->description), 0, $descLength);
									if (strlen(JText::_($mailinglist->description)) > $descLength)
									{
										echo '... ';
										echo JHTML::tooltip(JText::_($mailinglist->description), $mailinglist->title, 'tooltip.png', '', '');
									} ?>
								</span>
						<?php
							}
							else
							{
								echo '</span>';
							} ?>
					</p>
					<?php
				}
				?>
				</div><?php
			}
		} // End Mailinglists

		if ($paramsComponent->get('disclaimer'))
		{
			// Show Disclaimer only if enabled in basic parameters
			?>
			<p id="bwp_mod_form_disclaimer">
				<input type="checkbox" id="agreecheck_mod" name="agreecheck_mod" title="agreecheck_mod" />
				<?php
				// Extends the disclaimer link with '&tmpl=component' to see only the content
				$tpl_com = $paramsComponent->get('showinmodal') == 1 ? '&amp;tmpl=component' : '';
				if ($paramsComponent->get('disclaimer_selection') == 1 && $paramsComponent->get('article_id') > 0)
				{
					// Disclaimer article and target_blank or not
					$disclaimer_link = JRoute::_(ContentHelperRoute::getArticleRoute($paramsComponent->get('article_id'))) . $tpl_com;
				}
				elseif ($paramsComponent->get('disclaimer_selection') == 2 && $paramsComponent->get('disclaimer_menuitem') > 0)
				{
					// Disclaimer menu item and target_blank or not
					$disclaimer_link = JRoute::_('index.php?Itemid=' . $paramsComponent->get('disclaimer_menuitem')) . $tpl_com;
				}
				else
				{
					// Disclaimer url and target_blank or not
					$disclaimer_link = $paramsComponent->get('disclaimer_link');
				} ?>
				<span>
					<?php
					// Show inside modalbox
					if ($paramsComponent->get('showinmodal') == 1)
					{
						echo '<a id="bwp_mod_open"';
					}
					// Show not in modalbox
					else
					{
						echo '<a href="' . $disclaimer_link . '"';
						if ($paramsComponent->get('disclaimer_target') == 0)
						{
							echo ' target="_blank"';
						};
					}
					echo '>' . JText::_('MOD_BWPOSTMAN_DISCLAIMER') . '</a> <i class="icon-star"></i>'; ?>
				</span>
			</p>
			<?php
		} // Show disclaimer
		?>

		<?php // Question
		if ($paramsComponent->get('use_captcha') == 1)
		{ ?>
			<div class="question">
				<p class="security_question_entry"><?php echo JText::_('MOD_BWPOSTMANCAPTCHA'); ?></p>
				<p class="security_question_lbl"><?php echo JText::_($paramsComponent->get('security_question')); ?></p>
				<p class="question input-append">
					<input type="text" name="stringQuestion" id="a_stringQuestion"
							placeholder="<?php echo addslashes(JText::_('MOD_BWPOSTMANCAPTCHA_LABEL')); ?>" maxlength="50" class="input-small" />
					<span class="append-area"><i class="icon-star"></i></span>
				</p>
			</div>
			<?php
		} // End question
		?>

		<?php // Captcha
		if ($paramsComponent->get('use_captcha') == 2)
		{
			$codeCaptcha = md5(microtime()); ?>
			<div class="captcha">
				<p class="security_question_entry"><?php echo JText::_('MOD_BWPOSTMANCAPTCHA'); ?></p>
				<p class="security_question_lbl">
					<img src="<?php echo JUri::base(); ?>index.php?option=com_bwpostman&amp;view=register&amp;task=showCaptcha&amp;format=raw&amp;codeCaptcha=<?php echo $codeCaptcha; ?>" alt="captcha" />
				</p>
				<p class="captcha input-append">
					<input type="text" name="stringCaptcha" id="a_stringCaptcha"
							placeholder="<?php echo addslashes(JText::_('MOD_BWPOSTMANCAPTCHA_LABEL')); ?>"
							maxlength="50" class="input-small" />
					<span class="append-area"><i class="icon-star"></i></span>
				</p>
			</div>
			<input type="hidden" name="codeCaptcha" value="<?php echo $codeCaptcha; ?>" />
			<?php
		} // End captcha
		?>
		<?php // End Spamcheck 2 ?>

		<p class="mod-button-register text-right">
			<button class="button validate btn" type="submit"><?php echo JText::_('MOD_BWPOSTMANBUTTON_REGISTER'); ?>
			</button>
		</p>

		<input type="hidden" name="option" value="com_bwpostman" />
		<input type="hidden" name="task" value="save" />
		<input type="hidden" name="view" value="register" />
		<input type="hidden" name="bwp-<?php echo $captcha; ?>" value="1" />

		<?php // TODO: Has subscriber->id to be here or may this remain empty? ?>
		<!-- <input type="hidden" name="id" value="<?php echo isset($subscriber->id); ?>" /> -->
		<input type="hidden" name="registration_ip" value="<?php echo $remote_ip; ?>" />
		<input type="hidden" name="name_field_obligation_mod" id="name_field_obligation_mod"
				value="<?php echo $paramsComponent->get('name_field_obligation'); ?>" />
		<input type="hidden" name="firstname_field_obligation_mod" id="firstname_field_obligation_mod"
				value="<?php echo $paramsComponent->get('firstname_field_obligation'); ?>" />
		<input type="hidden" name="special_field_obligation_mod" id="special_field_obligation_mod"
				value="<?php echo $paramsComponent->get('special_field_obligation'); ?>" />
		<input type="hidden" name="show_name_field_mod" id="show_name_field_mod"
				value="<?php echo $paramsComponent->get('show_name_field'); ?>" />
		<input type="hidden" name="show_firstname_field_mod" id="show_firstname_field_mod"
				value="<?php echo $paramsComponent->get('show_firstname_field'); ?>" />
		<input type="hidden" name="show_special_mod" id="show_special_mod" value="<?php echo $paramsComponent->get('show_special'); ?>" />
		<input type="hidden" name="mod_id" id="mod_id" value="<?php echo $module_id; ?>" />
		<?php echo JHtml::_('form.token'); ?>
	</form>

		<p id="bwp_mod_form_required">(<i class="icon-star"></i>) <?php echo JText::_('MOD_BWPOSTMANREQUIRED'); ?></p>
		<p id="bwp_mod_form_editlink" class="text-right">
			<button class="button btn" onclick="location.href='<?php
				echo JRoute::_('index.php?option=com_bwpostman&amp;view=edit&amp;Itemid=' . $itemid);
				?>'">
				<?php echo JText::_('MOD_BWPOSTMANLINK_TO_EDITLINKFORM'); ?>
			</button>
		</p>
	<?php
	}; // End: Show registration form ?>
	<!-- The Modal -->
	<div id="bwp_mod_Modal" class="bwp_mod_modal">
		<div id="bwp_mod_modal-content">
			<span class="bwp_mod_close">&times;</span>
			<div id="bwp_mod_wrapper"></div>
		</div>
	</div>
</div>

<script type="text/javascript">
	jQuery(document).ready(function()
	{
		// Turn radios into btn-group
		jQuery('.radio.btn-group label').addClass('btn');
		jQuery(".btn-group label:not(.active)").click(function()
		{
			var label = jQuery(this);
			var input = jQuery('#' + label.attr('for'));

			if (!input.prop('checked'))
			{
				label.closest('.btn-group').find("label").removeClass('active btn-success btn-danger btn-primary');
				if (input.val() === '')
				{
					label.addClass('active btn-primary');
				}
				else if (input.val() === 0)
				{
					label.addClass('active btn-danger');
				}
				else
				{
					label.addClass('active btn-success');
				}
				input.prop('checked', true);
			}
		});
		jQuery(".btn-group input[checked=checked]").each(function()
		{
			if (jQuery(this).val() === '')
			{
				jQuery("label[for=" + jQuery(this).attr('id') + "]").addClass('active btn-primary');
			}
			else if (jQuery(this).val() === 0)
			{
				jQuery("label[for=" + jQuery(this).attr('id') + "]").addClass('active btn-danger');
			}
			else
			{
				jQuery("label[for=" + jQuery(this).attr('id') + "]").addClass('active btn-success');
			}
		});
		<?php
		if ($paramsComponent->get('disclaimer') == 1 && $paramsComponent->get('showinmodal') == 1)
		{
		?>
		function setModModal() {
			// Set the modal height and width 90%
			if (typeof window.innerWidth != 'undefined')
			{
				viewportwidth = window.innerWidth,
					viewportheight = window.innerHeight
			}
			else if (typeof document.documentElement != 'undefined'
				&& typeof document.documentElement.clientWidth !=
				'undefined' && document.documentElement.clientWidth != 0)
			{
				viewportwidth = document.documentElement.clientWidth,
					viewportheight = document.documentElement.clientHeight
			}
			else
			{
				viewportwidth = document.getElementsByTagName('body')[0].clientWidth,
					viewportheight = document.getElementsByTagName('body')[0].clientHeight
			}
			var modalcontent = document.getElementById('bwp_mod_modal-content');
			modalcontent.style.height = viewportheight-(viewportheight*0.10)+'px';
			modalcontent.style.width = viewportwidth-(viewportwidth*0.10)+'px';

			// Get the modal
			var modal = document.getElementById('bwp_mod_Modal');

			// Get the Iframe-Wrapper and set Iframe
			var wrapper = document.getElementById('bwp_mod_wrapper');
			var html = '<iframe id="iFrame" name="iFrame" src="<?php echo $disclaimer_link ?>" frameborder="0" style="width:100%; height:100%;"></iframe>';

			// Get the button that opens the modal
			var btnopen = document.getElementById("bwp_mod_open");

			// Get the <span> element that closes the modal
			var btnclose = document.getElementsByClassName("bwp_mod_close")[0];

			// When the user clicks the button, open the modal
			btnopen.onclick = function() {
				wrapper.innerHTML = html;
				modal.style.display = "block";
			}

			// When the user clicks on <span> (x), close the modal
			btnclose.onclick = function() {
				modal.style.display = "none";
			}

			// When the user clicks anywhere outside of the modal, close it
			window.onclick = function(event) {
				if (event.target == modal) {
					modal.style.display = "none";
				}
			}
		}
		setModModal();
		<?php
		}
		?>
	})
</script>
