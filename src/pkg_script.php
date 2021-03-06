<?php
/**
 * BwPostman Newsletter Package
 *
 * BwPostman package installer.
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
use Joomla\CMS\Session\Session;

/**
 * Class Pkg_BwPostmanInstallerScript
 *
 * @since       2.2.1
 */
class Pkg_BwPostmanInstallerScript
{
	/**
	 * @var string release
	 *
	 * @since       2.2.1
	 */
	private $release = null;

  /**
	 * Called on installation
  *
  * @return void
   *
	 * @since       2.2.1
  */

	public function install($parent)
  {
		sleep(5);

		$session = Factory::getSession();
		$session->set('update', false, 'bwpostman');

		// Get component manifest file version
		$manifest      = $parent->getManifest();
		$this->release = $manifest->version;

		$this->showFinished(false);
  }

  /**
	 * Called on update
  *
  * @return void
  *
	 * @since   2.2.1
  */

	public function update($parent)
  {
		$session = Factory::getSession();
		$session->set('update', true, 'bwpostman');

		// Get component manifest file version
		$manifest      = $parent->getManifest();
		$this->release = $manifest->version;

		$this->showFinished(true);
  }

	/**
	 * Called after any type of action
	 *
	 * @param   string  			$type		Which action is happening (install|uninstall|discover_install)
	 *
	 * @return  boolean  True on success
	 *
	 * @throws Exception
	 *
	 * @since       2.2.1
	 */

	public function postflight($type)
	{
	if ($type == 'update')
	{
			$oldRelease	= Factory::getApplication()->getUserState('com_bwpostman.update.oldRelease', '');

			if (version_compare($oldRelease, '2.2.1', 'lt'))
			{
				// rebuild update servers
				require_once(JPATH_ADMINISTRATOR . '/components/com_installer/models/updatesites.php');
				$installerModel = new InstallerModelUpdatesites();
				$installerModel->rebuild();
		}
	}
	return true;
  }

	/**
	 * shows the HTML after installation/update
	 *
	 * @param   boolean $update
	 *
	 * @return  void
	 *
	 * @since
	 */
	public function showFinished($update)
		{
		$lang = Factory::getLanguage();
		//Load first english files
		$lang->load('com_bwpostman.sys', JPATH_ADMINISTRATOR, 'en_GB', true);
		$lang->load('com_bwpostman', JPATH_ADMINISTRATOR, 'en_GB', true);

		//load specific language
		$lang->load('com_bwpostman.sys', JPATH_ADMINISTRATOR, null, true);
		$lang->load('com_bwpostman', JPATH_ADMINISTRATOR, null, true);

		$show_update = false;
		$show_right  = false;
		$lang_ver    = substr($lang->getTag(), 0, 2);

		if ($lang_ver != 'de')
		{
			$forum  = "https://www.boldt-webservice.de/en/forum-en/forum/bwpostman.html";
			$manual = "https://www.boldt-webservice.de/en/forum-en/manuals/bwpostman-manual.html";
		}
		else
		{
			$forum  = "https://www.boldt-webservice.de/de/forum/forum/bwpostman.html";
			$manual = "https://www.boldt-webservice.de/index.php/de/forum/handb%C3%BCcher/handbuch-zu-bwpostman.html";
		}


		if ($update)
		{
			$string_special = Text::_('COM_BWPOSTMAN_INSTALLATION_UPDATE_SPECIAL_NOTE_DESC');
		}
		else
		{
			$string_special = Text::_('COM_BWPOSTMAN_INSTALLATION_INSTALL_SPECIAL_NOTE_DESC');
		}

		$string_new         = Text::_('COM_BWPOSTMAN_INSTALLATION_UPDATE_NEW_DESC');
		$string_improvement = Text::_('COM_BWPOSTMAN_INSTALLATION_UPDATE_IMPROVEMENT_DESC');
		$string_bugfix      = Text::_('COM_BWPOSTMAN_INSTALLATION_UPDATE_BUGFIX_DESC');

		if (($string_bugfix != '' || $string_improvement != '' || $string_new != '') && $update)
		{
			$show_update = true;
		}
		if ($show_update || $string_special != '')
		{
			$show_right = true;
		}

		$asset_path = 'components/com_bwpostman/assets';
		?>

		<link rel="stylesheet" href="<?php echo Route::_($asset_path . '/css/install.css'); ?>" type="text/css" />

		<div id="com_bwp_install_header">
			<a href="https://www.boldt-webservice.de" target="_blank">
				<img border="0" align="center" src="<?php echo Route::_($asset_path . '/images/bw_header.png'); ?>" alt="Boldt Webservice" />
			</a>
		</div>
		<div class="top_line"></div>

		<div id="com_bwp_install_outer">
			<h1><?php echo Text::_('COM_BWPOSTMAN_INSTALLATION_WELCOME') ?></h1>
			<div id="com_bwp_install_left">
				<div class="com_bwp_install_welcome">
					<p><?php echo Text::_('COM_BWPOSTMAN_DESCRIPTION') ?></p>
				</div>
				<div class="com_bwp_install_finished">
					<h2>
						<?php
						if ($update)
						{
							echo Text::sprintf('COM_BWPOSTMAN_UPGRADE_SUCCESSFUL', $this->release);
						}
						else
						{
							echo Text::sprintf('COM_BWPOSTMAN_INSTALLATION_SUCCESSFUL', $this->release);
						}
						?>
					</h2>
				</div>
				<?php if ($show_right) { ?>
					<div class="cpanel">
						<div class="icon">
							<a href="<?php echo Route::_('index.php?option=com_bwpostman'); ?>">
								<?php echo HTMLHelper::_(
									'image',
									'administrator/components/com_bwpostman/assets/images/icon-48-bwpostman.png',
									Text::_('COM_BWPOSTMAN_INSTALL_GO_BWPOSTMAN')
								); ?>
								<span><?php echo Text::_('COM_BWPOSTMAN_INSTALL_GO_BWPOSTMAN'); ?></span>
							</a>
						</div>
						<div class="icon">
							<a href="<?php echo $manual; ?>" target="_blank">
								<?php echo HTMLHelper::_(
									'image',
									'administrator/components/com_bwpostman/assets/images/icon-48-manual.png',
									Text::_('COM_BWPOSTMAN_INSTALL_MANUAL')
								); ?>
								<span><?php echo Text::_('COM_BWPOSTMAN_INSTALL_MANUAL'); ?></span>
							</a>
						</div>
						<div class="icon">
							<a href="<?php echo $forum; ?>" target="_blank">
								<?php echo HTMLHelper::_(
									'image',
									'administrator/components/com_bwpostman/assets/images/icon-48-forum.png',
									Text::_('COM_BWPOSTMAN_INSTALL_FORUM')
								); ?>
								<span><?php echo Text::_('COM_BWPOSTMAN_INSTALL_FORUM'); ?></span>
							</a>
						</div>
					</div>
					<?php
				} ?>
			</div>

			<div id="com_bwp_install_right">
				<?php
				if ($show_right)
				{
					if ($string_special != '')
					{ ?>
						<div class="com_bwp_install_specialnote">
							<h2><?php echo Text::_('COM_BWPOSTMAN_INSTALLATION_SPECIAL_NOTE_LBL') ?></h2>
							<p class="urgent"><?php echo $string_special; ?></p>
						</div>
						<?php
					} ?>

					<?php if ($show_update)
				{ ?>
					<div class="com_bwp_install_updateinfo">
						<h2><?php echo Text::_('COM_BWPOSTMAN_INSTALLATION_UPDATEINFO') ?></h2>
						<?php echo Text::_('COM_BWPOSTMAN_INSTALLATION_CHANGELOG_INFO'); ?>
						<?php if ($string_new != '') { ?>
							<h3><?php echo Text::_('COM_BWPOSTMAN_INSTALLATION_UPDATE_NEW_LBL') ?></h3>
							<p><?php echo $string_new; ?></p>
							<?php
						} ?>
						<?php if ($string_improvement != '')
						{ ?>
							<h3><?php echo Text::_('COM_BWPOSTMAN_INSTALLATION_UPDATE_IMPROVEMENT_LBL') ?></h3>
							<p><?php echo $string_improvement; ?></p>
							<?php
						} ?>
						<?php if ($string_bugfix != '')
						{ ?>
							<h3><?php echo Text::_('COM_BWPOSTMAN_INSTALLATION_UPDATE_BUGFIX_LBL') ?></h3>
							<p><?php echo $string_bugfix; ?></p>
							<?php
						} ?>
					</div>
					<?php
				}
				}
				else
				{ ?>
					<div class="cpanel">
						<div class="icon">
							<a href="<?php echo Route::_('index.php?option=com_bwpostman&token=' . Session::getFormToken()); ?>">
								<?php echo HTMLHelper::_(
									'image',
									'administrator/components/com_bwpostman/assets/images/icon-48-bwpostman.png',
									Text::_('COM_BWPOSTMAN_INSTALL_GO_BWPOSTMAN')
								); ?>
								<span><?php echo Text::_('COM_BWPOSTMAN_INSTALL_GO_BWPOSTMAN'); ?></span>
							</a>
						</div>
						<div class="icon">
							<a href="<?php echo $manual; ?>" target="_blank">
								<?php echo HTMLHelper::_(
									'image',
									'administrator/components/com_bwpostman/assets/images/icon-48-bwpostman.png',
									Text::_('COM_BWPOSTMAN_INSTALL_MANUAL')
								); ?>
								<span><?php echo Text::_('COM_BWPOSTMAN_INSTALL_MANUAL'); ?></span>
							</a>
						</div>
						<div class="icon">
							<a href="<?php echo $forum; ?>" target="_blank">
								<?php echo HTMLHelper::_(
									'image',
									'administrator/components/com_bwpostman/assets/images/icon-48-bwpostman.png',
									Text::_('COM_BWPOSTMAN_INSTALL_FORUM')
								); ?>
								<span><?php echo Text::_('COM_BWPOSTMAN_INSTALL_FORUM'); ?></span>
							</a>
						</div>
					</div>
					<?php
				} ?>
			</div>
			<div class="clr"></div>

			<div class="com_bwp_install_footer">
				<p class="small">
					<?php echo Text::_('&copy; 2012-');
					echo date(" Y") ?> by
					<a href="https://www.boldt-webservice.de" target="_blank">Boldt Webservice</a>
				</p>
			</div>
		</div>

		<?php
	}
}
