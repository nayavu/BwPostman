<?php
/**
 * BwPostman Overview Module
 *
 * BwPostman main part of module.
 *
 * @version %%version_number%%
 * @package BwPostman-Rchive-Module
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

defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Factory;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Helper\ModuleHelper;

// Include the syndicate functions only once
require_once __DIR__ . '/helper.php';

$app		= Factory::getApplication();
$document	= Factory::getDocument();

// Get document object, set document title and add css
$templateName	= $app->getTemplate();
$css_filename	= '/templates/' . $templateName . '/css/mod_bwpostman_overview.css';

$document->addStyleSheet(Uri::root(true) . '/media/mod_bwpostman_overview/css/bwpostman_overview.css');
if (file_exists(JPATH_BASE . $css_filename)) {
	$document->addStyleSheet(Uri::root(true) . $css_filename);
}

$moduleclass_sfx	= htmlspecialchars($params->get('moduleclass_sfx'));
$list				= modBwPostmanOverviewHelper::getList($params, $module->id);

require ModuleHelper::getLayoutPath('mod_bwpostman_overview', $params->get('layout', 'default'));
