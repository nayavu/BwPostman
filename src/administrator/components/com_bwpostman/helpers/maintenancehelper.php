<?php
/**
 * BwPostman Newsletter Component
 *
 * BwPostman helper class for maintenance.
 *
 * @version %%version_number%%
 * @package BwPostman-Admin
 * @author Romana Boldt
 * @copyright (C) %%copyright_year%% Boldt Webservice <forum@boldt-webservice.de>
 * @support https://www.boldt-webservice.de/en/forum-en/bwpostman.html
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

use Joomla\Filesystem\File;

/**
 * Class BwPostmanMaintenanceHelper
 *
 * @since 2.1.0
 */
abstract class BwPostmanMaintenanceHelper
{

	/**
	 * Base compress method
	 *
	 * @param    string    $fileName    name of the file to compress
	 *
	 * @return   string    $compressedFile
	 *
	 * @throws \Exception
	 *
	 * @since    2.0.0
	 */
	public static function compressBackupFile($fileName)
	{
		$params = JComponentHelper::getParams('com_bwpostman');
dump($fileName, 'Filename');

		$compressMethod = $params->get('compress_method', 'zip');
		$compressedFile = $fileName . '.' . $compressMethod;
		$returnFile     = $compressedFile;
		$onlyFileName  = basename($fileName);

		$fh = fopen($fileName, 'r');
		$fileData = fread($fh, filesize($fileName));

		switch ($compressMethod)
		{
			case 'zip':
			default:
				$compressResult = self::compressByZip($compressedFile, $onlyFileName, $fileData);
				break;
		}

		if (!$compressResult)
		{
			$returnFile = $fileName;
		}

		return $returnFile;
	}

	/**
	 * Base compress method
	 *
	 * @param    string    $compressedFile   name of the compressed file
	 * @param    string    $fileName         name of the file to compress
	 * @param    string    $fileData         data to compress
	 *
	 * @return   boolean  success or not
	 *
	 * @throws \Exception
	 *
	 * @since    2.0.0
	 */
	public static function compressByZip($compressedFile, $fileName, $fileData)
	{
		$files = array(
			'track' => array(
				'name' => $fileName,
				'data' => $fileData,
				'time' => time()
			)
		);

		$packager = new Joomla\Archive\Zip();

		// Run the packager
		if (!$packager->create($compressedFile, $files))
		{
			JFactory::getApplication()->enqueueMessage(JText::_('COM_BWPOSTMAN_MAINTENANCE_SAVE_TABLES_ERROR_ZIP_CREATE'));

			return false;
		}

		return true;
	}

	/**
	 * Method to decompress backup file
	 *
	 * @param    string    $srcFileName    name of the file to decompress
	 * @param    string    $packName       name of the packed file
	 *
	 * @return   string    $decompressedFile
	 *
	 * @throws \Exception
	 *
	 * @since    2.0.0
	 */
	public static function decompressBackupFile($srcFileName, $packName)
	{
		$destFileName = str_replace('.zip', '.xml', $srcFileName);

		$destPath	= JFactory::getConfig()->get('tmp_path');

		$packager = new Joomla\Archive\Zip();

		// Run the packager
		if (!$packager->extract($srcFileName, $destPath))
		{
			JFactory::getApplication()->enqueueMessage(JText::_('COM_BWPOSTMAN_MAINTENANCE_RESTORE_ERROR_ZIP_EXTRACT'));

			return $srcFileName;
		}
		else
		{
			$unpackedSourceName = File::stripExt($packName);
			File::delete($srcFileName);
			File::move($destPath . '/' . $unpackedSourceName, $destPath . '/tmp_bwpostman_tablesav.xml');
		}

		return $destFileName;
	}
}
