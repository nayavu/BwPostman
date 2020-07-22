<?php
/**
 * BwPostman Newsletter Component
 *
 * BwPostman ajax controller for newsletter sending process.
 *
 * @version %%version_number%%
 * @package BwPostman-Admin
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

use Joomla\CMS\Factory;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Session\Session;

// Import CONTROLLER object class
jimport('joomla.application.component.controller');

require_once(JPATH_ADMINISTRATOR . '/components/com_bwpostman/models/newsletter.php');
require_once(JPATH_COMPONENT_ADMINISTRATOR . '/libraries/webapp/BwWebApp.php');

/**
 * BwPostman Newsletter Controller
 *
 * @package		BwPostman-Admin
 * @subpackage	Newsletter
 *
 * @since       2.4.0
 */
class BwPostmanControllerNewsletter extends JControllerLegacy
{

	/**
	 * Method to call newsletter sending process via ajax
	 *
	 * @return 	void
	 *
	 * @since   2.4.0
	 */
	public function startsending()
	{
		try
		{
			// Check for request forgeries
			if (!Session::checkToken('get')) {
				throw new Exception((Text::_('COM_BWPOSTMAN_JINVALID_TOKEN')));
			}

			$app    = Factory::getApplication();
			$jinput = $app->input;

			// Get the params
			$params         = ComponentHelper::getParams('com_bwpostman');
			$mails_per_step = (int)$app->getUserState('com_bwpostman.newsletters.mails_per_pageload', $params->get('default_mails_per_pageload'));
			$sendandpublish = (int)$app->getUserState('com_bwpostman.newsletters.sendmailandpublish', 0);
			$id             = (int)$app->getUserState('com_bwpostman.newsletters.publish_id', 0);

			$defaultPublish	= (int)$app->getUserState('com_bwpostman.newsletters.publish_nl_by_default', $params->get('publish_nl_by_default'));

			if ($defaultPublish)
			{
				$sendandpublish = 1;
			}

			$model = $this->getModel('newsletter');
			$nl_id = $jinput->getInt('nl_id');
			$app->setUserState('com_bwpostman.viewraw.newsletter.id', $nl_id);

			// Access check
			if (!BwPostmanHelper::canSend($nl_id))
			{
				echo Text::_('COM_BWPOSTMAN_ERROR_SENDING_NO_PERMISSION');
				header('HTTP/1.1 400 ' . Text::_('COM_BWPOSTMAN_ERROR_MSG'));
				exit;
			}

			// set defaults
			$mailsThisStepDone  = $jinput->getInt('mailsDone', 0);
			$nl_to_send_message = "";

			$percent     = 0;
			$sending     = "secondary";
			$delay_msg   = "secondary";
			$complete    = "secondary";
			$published   = "secondary";
			$nopublished = "secondary";
			$ready       = "0";
			$error       = "secondary";

			// start output buffer
			ob_start();

			// set number of queue entries before start sending
			$sumentries	= is_null($app->getUserState('com_bwpostman.newsletters.entries', null))
				? $app->setUserState('com_bwpostman.newsletters.entries', $model->checkTrials(2, 1))
				: $app->getUserState('com_bwpostman.newsletters.entries', null);

			if ($model->checkTrials(2))
			{
				// start sending process
				$ret = $model->sendMailsFromQueue($mails_per_step, true, $mailsThisStepDone);
				// number of queue entries during sending
				$entries = $model->checkTrials(2, 1);
				// progressbar
				$percent = empty($sumentries) ? 100 : floor(100 / $sumentries * ($sumentries - $entries));
				$nl_to_send_message = Text::sprintf('COM_BWPOSTMAN_NL_SENT_MESSAGE', $entries, $sumentries);
				$sending = 'success';

				if ($ret > 2)
				{   // nl package size for ajax call is 10
					$mailsThisStepDone = $ret;
				}

				if ($ret === 1)
				{   // There are more mails in the queue.
					$mailsThisStepDone = 0;
					$sending           = 'secondary';
					$delay_msg         = 'success';
				}

				if ($ret === 0)
				{   // No more mails to send.
					// reset number of queue entries before start sending
					$app->setUserState('com_bwpostman.newsletters.entries', null);
					$sending  = 'secondary';
					$complete = 'success';

					if ($sendandpublish === 1)
					{
						if ($model->publish($id, 1) === true)
						{
							$published = 'success';
						}
						else
						{
							$nopublished = 'error';
						}
					}

					// clear data
					$app->setUserState('com_bwpostman.newsletters.sendmailandpublish', null);
					$app->setUserState('com_bwpostman.newsletters.publish_id', null);

					$ready = "1";
				}

				if ($ret === 2)
				{   // There are fatal errors.
					echo "<br /><span id='nl_modal_to_send_message_error' style='color: #ff0000;'>" . Text::sprintf('COM_BWPOSTMAN_NL_ERROR_SENDING_TECHNICAL_REASON', '10') . "</span>";
					$sending = "secondary";
					$error   = 'error';
					$ready   = "1";
				}
			}
			else
			{
				// reset number of queue entries before start sending
				$app->setUserState('com_bwpostman.newsletters.entries', null);
				echo Text::_('COM_BWPOSTMAN_NL_SENDING_NO_QUEUE_ENTRIES_TO_SEND');
				$error = 'error';
				$ready = "1";
			}

			// return the contents of the output buffer
			$result = ob_get_contents();

			// clean the output buffer and turn off output buffering
			ob_end_clean();

			// set json response
			$res = array(
				"mailsDone"		=> $mailsThisStepDone,
				"nl2sendmsg"	=> $nl_to_send_message,
				"percent"		=> $percent,
				"sending"		=> $sending,
				"delay_msg"		=> $delay_msg,
				"complete"		=> $complete,
				"published"		=> $published,
				"nopublished"	=> $nopublished,
				"ready"			=> $ready,
				"result"		=> $result,
				"error"			=> $error
			);

			// ajax response
			$appWeb = new BwWebApp();
			$appWeb->setHeader('Content-Type', 'application/json', true);
			echo json_encode($res);
			$app->close();
		}
		catch (RuntimeException $e)
		{
			echo Text::sprintf('COM_BWPOSTMAN_NL_ERROR_SENDING_TECHNICAL_REASON', '11') . '<br />';
			echo $e->getMessage();
			header('HTTP/1.1 400 ' . Text::_('COM_BWPOSTMAN_ERROR_MSG'));
			exit;
		}

		catch (Exception $e)
		{
			echo Text::sprintf('COM_BWPOSTMAN_NL_ERROR_SENDING_TECHNICAL_REASON', '12') . '<br />';
			echo $e->getMessage();
			header('HTTP/1.1 400 ' . Text::_('COM_BWPOSTMAN_ERROR_MSG'));
			exit;
		}
	}
}

