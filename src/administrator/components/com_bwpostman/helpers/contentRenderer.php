<?php
/**
 * BwPostman Newsletter Component
 *
 * BwPostman Content Renderer Class.
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

require_once(JPATH_COMPONENT_ADMINISTRATOR . '/helpers/htmlContent.php');


/**
* Content Renderer Class
* Provides methods render the selected contents from which the newsletters shall be generated
* --> Referring to BwPostman 1.6 beta and Communicator 2.0.0rc1 (??)
*
* @package		BwPostman-Admin
* @subpackage	Newsletters
*
* @since       2.3.0 here (moved from newsletter model)
*/
class contentRenderer
{
	/**
	 * Method to get the menu item ID for the content item
	 *
	 * @access      public
	 *
	 * @param   string $row
	 *
	 * @return    int     $itemid     menu item ID
	 *
	 * @throws Exception
	 *
	 * @since       0.9.1
	 */
	public function getItemid($row)
	{
		$itemid = 0;
		try
		{
			$_db   = JFactory::getDbo();
			$query = $_db->getQuery(true);

			$query->select($_db->quoteName('id'));
			$query->from($_db->quoteName('#__menu'));
			$query->where($_db->quoteName('link') . ' = ' . $_db->quote('index.php?option=com_bwpostman&view=' . $row));
			$query->where($_db->quoteName('published') . ' = ' . (int) 1);

			$_db->setQuery($query);

			$itemid = $_db->loadResult();

			if (empty($itemid))
			{
				$query = $_db->getQuery(true);

				$query->select($_db->quoteName('id'));
				$query->from($_db->quoteName('#__menu'));
				$query->where($_db->quoteName('link') . ' = ' . $_db->quote('index.php?option=com_bwpostman&view=register'));
				$query->where($_db->quoteName('published') . ' = ' . (int) 1);

				$_db->setQuery($query);

				$itemid = $_db->loadResult();
			}
		}
		catch (RuntimeException $e)
		{
			JFactory::getApplication()->enqueueMessage($e->getMessage(), 'error');
		}

		return $itemid;
	}

	/**
	 * This is the main function to render the content from an ID to HTML
	 *
	 * @param array  $nl_content
	 * @param int    $template_id
	 * @param string $text_template_id
	 *
	 * @return array    content
	 *
	 * @throws Exception
	 *
	 * @since       0.9.1
	 */
	public function getContent($nl_content, $template_id, $text_template_id)
	{
		JPluginHelper::importPlugin('bwpostman');
		$dispatcher = JEventDispatcher::getInstance();

		$param = JComponentHelper::getParams('com_bwpostman');
		$content = array();

		$tpl      = self::getTemplate($template_id);
		$text_tpl = self::getTemplate($text_template_id);

		// add template assets only for user-made templates
		if ($tpl->tpl_id == '0')
		{
			$tpl_assets = self::getTemplateAssets($template_id);
			if (!empty($tpl_assets))
			{
				foreach ($tpl_assets as $key => $value)
				{
					$tpl->$key = $value;
				}
			}
		}

		// only for old templates
		if ($template_id < 1)
		{
			$content['html_version'] = '<div class="outer"><div class="header"><img class="logo" src="' .
				JRoute::_(JUri::root() . $param->get('logo')) .
				'" alt="" /></div><div class="content-outer"><div class="content"><div class="content-inner"><p class="nl-intro">&nbsp;</p>';
		}
		else
		{
			$content['html_version'] = '';
		}

		$content['text_version'] = '';

		$dispatcher->trigger('onBwpmBeforeRenderNewsletter', array(&$nl_content, &$tpl, &$text_tpl, &$content));

		if ($nl_content == null)
		{
			$content['html_version'] .= '';
			$content['text_version'] .= '';
		}
		else
		{
			foreach ($nl_content as $content_id)
			{
				$dispatcher->trigger('onBwpmBeforeRenderNewsletterArticle', array(&$nl_content, &$tpl, &$text_tpl, &$content));

				if ($tpl->tpl_id && $template_id > 0)
				{
					$content['html_version'] .= $this->replaceContentHtmlNew($content_id, $tpl);
					if (($tpl->article['divider'] == 1) && ($content_id != end($nl_content)))
					{
						$content['html_version'] = $content['html_version'] . $tpl->tpl_divider;
					}
				}
				else
				{
					$content['html_version'] .= $this->replaceContentHtml($content_id, $tpl);
				}

				if ($text_tpl->tpl_id && $text_tpl->tpl_id > '999')
				{
					$content['text_version'] .= $this->replaceContentTextNew($content_id, $text_tpl);
					if (($text_tpl->article['divider'] == 1) && ($content_id != end($nl_content)))
					{
						$content['text_version'] = $content['text_version'] . $text_tpl->tpl_divider . "\n\n";
					}
				}
				else
				{
					$content['text_version'] .= $this->replaceContentText($content_id, $text_tpl);
				}

				$dispatcher->trigger('onBwpmAfterRenderNewsletterArticle', array(&$nl_content, &$tpl, &$text_tpl, &$content));
			}
		}

		$dispatcher->trigger('onBwpmAfterRenderNewsletter', array(&$nl_content, &$tpl, &$text_tpl, &$content));

		// only for old templates
		if ($template_id < 1)
		{
			$content['html_version'] .= '</div></div></div></div>';
		}

		return $content;
	}

	/**
	 * Method to retrieve content
	 *
	 * @param int $id
	 *
	 * @return mixed
	 *
	 * @throws Exception
	 *
	 * @since       0.9.1
	 */
	public function retrieveContent($id)
	{
		$row   = new stdClass();
		$app   = JFactory::getApplication();
		$_db   = JFactory::getDbo();
		$query = $_db->getQuery(true);

		$query->select($_db->quoteName('a') . '.*');
		$query->select('ROUND(v.rating_sum/v.rating_count) AS ' . $_db->quoteName('rating'));
		$query->select($_db->quoteName('v') . '.' . $_db->quoteName('rating_count'));
		$query->select($_db->quoteName('u') . '.' . $_db->quoteName('name') . ' AS ' . $_db->quoteName('author'));
		$query->select($_db->quoteName('cc') . '.' . $_db->quoteName('title') . ' AS ' . $_db->quoteName('category'));
		$query->select($_db->quoteName('s') . '.' . $_db->quoteName('title') . ' AS ' . $_db->quoteName('section'));
		$query->select($_db->quoteName('g') . '.' . $_db->quoteName('title') . ' AS ' . $_db->quoteName('groups'));
		$query->select($_db->quoteName('s') . '.' . $_db->quoteName('published') . ' AS ' . $_db->quoteName('sec_pub'));
		$query->select($_db->quoteName('cc') . '.' . $_db->quoteName('published') . ' AS ' . $_db->quoteName('cat_pub'));
		$query->from($_db->quoteName('#__content') . ' AS ' . $_db->quoteName('a'));
		$query->join(
			'LEFT',
			$_db->quoteName('#__categories') .
			' AS ' . $_db->quoteName('cc') .
			' ON ' . $_db->quoteName('cc') . '.' . $_db->quoteName('id') . ' = ' . $_db->quoteName('a') . '.' . $_db->quoteName('catid')
		);
		$query->join(
			'LEFT',
			$_db->quoteName('#__categories') .
			' AS ' . $_db->quoteName('s') .
			' ON ' . $_db->quoteName('s') . '.' . $_db->quoteName('id') . ' = ' . $_db->quoteName('cc') . '.' . $_db->quoteName('parent_id') .
			' AND ' . $_db->quoteName('s') . '.' . $_db->quoteName('extension') . ' = ' . $_db->quote('com_content')
		);
		$query->join(
			'LEFT',
			$_db->quoteName('#__users') .
			' AS ' . $_db->quoteName('u') .
			' ON ' . $_db->quoteName('u') . '.' . $_db->quoteName('id') . ' = ' . $_db->quoteName('a') . '.' . $_db->quoteName('created_by')
		);
		$query->join(
			'LEFT',
			$_db->quoteName('#__content_rating') .
			' AS ' . $_db->quoteName('v') .
			' ON ' . $_db->quoteName('a') . '.' . $_db->quoteName('id') . ' = ' . $_db->quoteName('v') . '.' . $_db->quoteName('content_id')
		);
		$query->join(
			'LEFT',
			$_db->quoteName('#__usergroups') .
			' AS ' . $_db->quoteName('g') .
			' ON ' . $_db->quoteName('a') . '.' . $_db->quoteName('access') . ' = ' . $_db->quoteName('g') . '.' . $_db->quoteName('id')
		);
		$query->where($_db->quoteName('a') . '.' . $_db->quoteName('id') . ' = ' . (int) $id);

		$_db->setQuery($query);
		try
		{
			$row = $_db->loadObject();
		}
		catch (RuntimeException $e)
		{
			$app->enqueueMessage($e->getMessage(), 'error');
		}

		if ($row)
		{
			$params = new JRegistry();
			$params->loadString($row->attribs, 'JSON');

			$params->def('link_titles', $app->get('link_titles'));
			$params->def('author', $params->get('newsletter_show_author'));
			$params->def('createdate', $params->get('newsletter_show_createdate'));
			$params->def('modifydate', !$app->get('hideModifyDate'));
			$params->def('print', !$app->get('hidePrint'));
			$params->def('pdf', !$app->get('hidePdf'));
			$params->def('email', !$app->get('hideEmail'));
			$params->def('rating', $app->get('vote'));
			$params->def('icons', $app->get('icons'));
			$params->def('readmore', $app->get('readmore'));
			$params->def('item_title', 1);

			$params->set('intro_only', 1);
			$params->set('item_navigation', 0);

			$params->def('back_button', 0);
			$params->def('image', 1);

			$row->params = $params;
			$row->text   = $row->introtext;
		}

		return $row;
	}

	/**
	 * Method to replace HTML content
	 *
	 * @param int    $id
	 * @param object $tpl
	 *
	 * @return string
	 *
	 * @throws Exception
	 *
	 * @since       0.9.1
	 */
	public function replaceContentHtml($id, $tpl)
	{
		$lang = JFactory::getLanguage();
		$lang->load('com_bwpostman', JPATH_ADMINISTRATOR, 'en_GB', true);
		$lang->load('com_bwpostman', JPATH_ADMINISTRATOR, null, true);

		$content = '';

		if ($id != 0)
		{
			// Editor user type check
			$access          = new stdClass();
			$access->canEdit = $access->canEditOwn = $access->canPublish = 0;

			$row = $this->retrieveContent($id);

			if ($row)
			{
				$params  = $row->params;
				$lang    = self::getArticleLanguage($row->id);
				$_Itemid = ContentHelperRoute::getArticleRoute($row->id, 0, $lang);
				$link    = JRoute::_(JUri::base());
				if ($_Itemid)
				{
					$link .= $_Itemid;
				}

				$intro_text = $row->text;

				$html_content = new htmlContent();

				if (key_exists('show_title', $tpl->article) && $tpl->article['show_title'] != 0)
				{
					ob_start();
					// Displays Item Title
					$html_content->Title($row, $params);

					$content .= ob_get_contents();
					ob_end_clean();
				}

				$content .= '<div class="intro_text">';
				// Displays Category article info

				ob_start();

				if ($tpl->article['show_createdate'] != 0 || $tpl->article['show_author'] != 0)
				{
					$html_content->ArticleInfoBegin();
					// Displays Created Date
					if ($tpl->article['show_createdate'] != 0)
					{
						$html_content->CreateDate($row);
					}

					// Displays Author Name
					if ($tpl->article['show_author'] != 0)
					{
						$html_content->Author($row);
						$html_content->ArticleInfoEnd();
					}
				}

				// Displays Urls
				$content .= ob_get_contents();
				ob_end_clean();

				$content .= $intro_text //(function_exists('ampReplace') ? ampReplace($intro_text) : $intro_text). '</td>'
					. '</div>';

				if ($tpl->article['show_readon'] != 0)
				{
					$tag_readon = isset($tpl->tpl_tags_readon) && $tpl->tpl_tags_readon == 0 ?
						$tpl->tpl_tags_readon_advanced :
						BwPostmanTplHelper::getReadonTag();
					$link       = str_replace('administrator/', '', $link);

					// Trigger Plugin "substitutelinks"
					if (JFactory::getApplication()->getUserState('com_bwpostman.edit.newsletter.data.substitutelinks') == '1')
					{
						JPluginHelper::importPlugin('bwpostman');
						$dispatcher = JEventDispatcher::getInstance();
						$dispatcher->trigger('onBwPostmanSubstituteReadon', array(&$link));
					}

					$tag_readon = str_replace('[%readon_href%]', $link, $tag_readon);
					$content    .= str_replace('[%readon_text%]', JText::_('READ_MORE'), $tag_readon);
				}

				// Set special article html if defined at the template
				$tag_article_begin = isset($tpl->tpl_tags_article) && $tpl->tpl_tags_article == 0 ?
					$tpl->tpl_tags_article_advanced_b :
					BwPostmanTplHelper::getArticleTagBegin();
				$tag_article_end   = isset($tpl->tpl_tags_article) && $tpl->tpl_tags_article == 0 ?
					$tpl->tpl_tags_article_advanced_e :
					BwPostmanTplHelper::getArticleTagEnd();
				$content           = $tag_article_begin . $content . $tag_article_end;

				return stripslashes($content);
			}
		}

		return JText::sprintf('COM_BWPOSTMAN_NL_ERROR_RETRIEVING_CONTENT', $id);
	}

	/**
	 * Method to replace HTML content (new)
	 *
	 * @param $id
	 * @param $tpl
	 *
	 * @return string
	 *
	 * @throws Exception
	 *
	 * @since       1.1.0
	 */
	public function replaceContentHtmlNew($id, $tpl)
	{
		$lang = JFactory::getLanguage();
		$lang->load('com_bwpostman', JPATH_ADMINISTRATOR, 'en_GB', true);
		$lang->load('com_bwpostman', JPATH_ADMINISTRATOR, null, true);

		$content     = '';
		$create_date = '';

		if ($id != 0)
		{
			// Editor user type check
			$access          = new stdClass();
			$access->canEdit = $access->canEditOwn = $access->canPublish = 0;

			// $id = "-1" if no content is selected
			if ($id == '-1')
			{
				$content .= $tpl->tpl_article;
				$content = preg_replace("/<table id=\"readon\".*?<\/table>/is", "", $content);
				$content = isset($tpl->article['show_title']) && $tpl->article['show_title'] == 0 ?
					str_replace('[%content_title%]', '', $content) :
					str_replace('[%content_title%]', JText::_('COM_BWPOSTMAN_TPL_PLACEHOLDER_TITLE'), $content);
				$content = str_replace('[%content_text%]', JText::_('COM_BWPOSTMAN_TPL_PLACEHOLDER_CONTENT'), $content);

				return stripslashes($content);
			}

			$row = $this->retrieveContent($id);

			if ($row)
			{
				$lang    = self::getArticleLanguage($row->id);
				$_Itemid = ContentHelperRoute::getArticleRoute($row->id, 0, $lang);
				$link    = JRoute::_(JUri::base());
				if ($_Itemid)
				{
					$link .= $_Itemid;
				}

				$intro_text = $row->text;

				if (intval($row->created) != 0)
				{
					$create_date = JHtml::_('date', $row->created);
				}

				$link = str_replace('administrator/', '', $link);

				$content      .= $tpl->tpl_article;
				$content      = isset($tpl->article['show_title']) && $tpl->article['show_title'] == 0 ?
					str_replace('[%content_title%]', '', $content) :
					str_replace('[%content_title%]', $row->title, $content);
				$content_text = '';
				if (($tpl->article['show_createdate'] == 1) || ($tpl->article['show_author'] == 1))
				{
					$content_text .= '<p class="article-data">';
					if ($tpl->article['show_createdate'] == 1)
					{
						$content_text .= '<span class="createdate"><small>';
						$content_text .= JText::sprintf('COM_CONTENT_CREATED_DATE_ON', $create_date);
						$content_text .= '&nbsp;&nbsp;&nbsp;&nbsp;</small></span>';
					}

					if ($tpl->article['show_author'] == 1)
					{
						$content_text .= '<span class="created_by"><small>';
						$content_text .= JText::sprintf(
							'COM_CONTENT_WRITTEN_BY',
							($row->created_by_alias ? $row->created_by_alias : $row->author)
						);
						$content_text .= '</small></span>';
					}

					$content_text .= '</p>';
				}

				$content_text .= $intro_text;
				$content      = str_replace('[%content_text%]', $content_text, $content);

				// Trigger Plugin "substitutelinks"
				if (JFactory::getApplication()->getUserState('com_bwpostman.edit.newsletter.data.substitutelinks') == '1')
				{
					JPluginHelper::importPlugin('bwpostman');
					$dispatcher = JEventDispatcher::getInstance();
					$dispatcher->trigger('onBwPostmanSubstituteReadon', array(&$link));
				}

				$content = str_replace('[%readon_href%]', $link, $content);
				$content = str_replace('[%readon_text%]', JText::_('READ_MORE'), $content);

				return stripslashes($content);
			}
		}

		return JText::sprintf('COM_BWPOSTMAN_NL_ERROR_RETRIEVING_CONTENT', $id);
	}

	/**
	 * Method to replace text content
	 *
	 * @param int    $id
	 * @param object $text_tpl
	 *
	 * @return string
	 *
	 * @throws Exception
	 *
	 * @since       1.1.0
	 */
	public function replaceContentTextNew($id, $text_tpl)
	{
		$create_date = '';

		if ($id != 0)
		{
			$row = $this->retrieveContent($id);

			if ($row)
			{
				$lang    = self::getArticleLanguage($row->id);
				$_Itemid = ContentHelperRoute::getArticleRoute($row->id, 0, $lang);
				$link    = JRoute::_(JUri::base());
				if ($_Itemid)
				{
					$link .= $_Itemid;
				}

				$intro_text = $row->text;
				$intro_text = strip_tags($intro_text);

				$intro_text = $this->unHTMLSpecialCharsAll($intro_text);

				if (intval($row->created) != 0)
				{
					$create_date = JHtml::_('date', $row->created);
				}

				$link = str_replace('administrator/', '', $link);

				$content      = $text_tpl->tpl_article;
				$content      = isset($text_tpl->article['show_title']) && $text_tpl->article['show_title'] == 0 ?
					str_replace('[%content_title%]', '', $content) :
					str_replace('[%content_title%]', $row->title, $content);
				$content_text = "\n";
				if (($text_tpl->article['show_createdate'] == 1) || ($text_tpl->article['show_author'] == 1))
				{
					if ($text_tpl->article['show_createdate'] == 1)
					{
						$content_text .= JText::sprintf('COM_CONTENT_CREATED_DATE_ON', $create_date);
						$content_text .= '    ';
					}

					if ($text_tpl->article['show_author'] == 1)
					{
						$content_text .= JText::sprintf(
							'COM_CONTENT_WRITTEN_BY',
							($row->created_by_alias ? $row->created_by_alias : $row->author)
						);
					}

					$content_text .= "\n\n";
				}

				$content_text .= $intro_text;
				$content      = str_replace('[%content_text%]', $content_text . "\n", $content);

				// Trigger Plugin "substitutelinks"
				if (JFactory::getApplication()->getUserState('com_bwpostman.edit.newsletter.data.substitutelinks') == '1')
				{
					JPluginHelper::importPlugin('bwpostman');
					$dispatcher = JEventDispatcher::getInstance();
					$dispatcher->trigger('onBwPostmanSubstituteReadon', array(&$link));
				}

				$content = str_replace('[%readon_href%]', $link . "\n", $content);
				$content = str_replace('[%readon_text%]', JText::_('READ_MORE'), $content);

				return stripslashes($content);
			}
		}

		return '';
	}

	/**
	 * Method to replace text content
	 *
	 * @param int    $id
	 * @param object $text_tpl
	 *
	 * @return string
	 *
	 * @throws Exception
	 *
	 * @since       0.9.1
	 */
	public function replaceContentText($id, $text_tpl)
	{
		$lang = JFactory::getLanguage();
		$lang->load('com_bwpostman', JPATH_ADMINISTRATOR, 'en_GB', true);
		$lang->load('com_bwpostman', JPATH_ADMINISTRATOR, null, true);

		$create_date = '';

		if ($id != 0)
		{
			$row = $this->retrieveContent($id);

			if ($row)
			{
				$lang    = self::getArticleLanguage($row->id);
				$_Itemid = ContentHelperRoute::getArticleRoute($row->id, 0, $lang);
				$link    = JRoute::_(JUri::base());
				if ($_Itemid)
				{
					$link .= $_Itemid;
				}

				$intro_text = $row->text;
				$intro_text = strip_tags($intro_text);

				$intro_text = $this->unHTMLSpecialCharsAll($intro_text);

				if (intval($row->created) != 0)
				{
					$create_date = JHtml::_('date', $row->created);
				}

				$content = isset($text_tpl->article['show_title']) && $text_tpl->article['show_title'] == 0 ? "\n" : "\n" . $row->title;

				$content_text = "";
				if (($text_tpl->article['show_createdate'] == 1) || ($text_tpl->article['show_author'] == 1))
				{
					if ($text_tpl->article['show_createdate'] == 1)
					{
						$content_text .= JText::sprintf('COM_CONTENT_CREATED_DATE_ON', $create_date);
						$content_text .= '    ';
					}

					if ($text_tpl->article['show_author'] == 1)
					{
						$content_text .= JText::sprintf(
							'COM_CONTENT_WRITTEN_BY',
							($row->created_by_alias ? $row->created_by_alias : $row->author)
						);
					}

					$content_text .= "\n\n";
				}

				$intro_text = $content_text . $intro_text;

				$content .= "\n\n" . $intro_text . "\n\n";
				if ($text_tpl->article['show_readon'] == 1)
				{
					// Trigger Plugin "substitutelinks"
					if (JFactory::getApplication()->getUserState('com_bwpostman.edit.newsletter.data.substitutelinks') == '1')
					{
						JPluginHelper::importPlugin('bwpostman');
						$dispatcher = JEventDispatcher::getInstance();
						$dispatcher->trigger('onBwPostmanSubstituteReadon', array(&$link));
					}

					$content .= JText::_('READ_MORE') . ": \n" . str_replace('administrator/', '', $link) . "\n\n";
				}

				return stripslashes($content);
			}
		}

		return '';
	}

	/**
	 * Method to get the language of an article
	 *
	 * @access	public
	 *
	 * @param	int		$id     article ID
	 *
	 * @return 	mixed	language string or 0
	 *
	 * @throws Exception
	 *
	 * @since	2.3.0 here (moved from newsletter model, there since 1.0.7)
	 */
	private function getArticleLanguage($id)
	{
		if (JLanguageMultilang::isEnabled())
		{
			$result = '';
			$_db	= JFactory::getDbo();
			$query	= $_db->getQuery(true);

			$query->select($_db->quoteName('language'));
			$query->from($_db->quoteName('#__content'));
			$query->where($_db->quoteName('id') . ' = ' . (int) $id);

			$_db->setQuery($query);
			try
			{
				$result = $_db->loadResult();
			}
			catch (RuntimeException $e)
			{
				JFactory::getApplication()->enqueueMessage($e->getMessage(), 'error');
			}

			return $result;
		}
		else
		{
			return 0;
		}
	}

	/**
	 * Method to get the template settings which are used to compose a newsletter
	 *
	 * @access	public
	 *
	 * @param   int    $template_id     template id
	 *
	 * @return	object
	 *
	 * @throws Exception
	 *
	 * @since	2.3.0 here (moved from newsletter model, there since 1.1.0)
	 */
	public function getTemplate($template_id)
	{
		$tpl    = new stdClass();
		$params = JComponentHelper::getParams('com_bwpostman');

		if (is_null($template_id))
		{
			$template_id = '1';
		}

		$_db	= JFactory::getDbo();
		$query	= $_db->getQuery(true);
		$query->select($_db->quoteName('id'));
		$query->select($_db->quoteName('tpl_html'));
		$query->select($_db->quoteName('tpl_css'));
		$query->select($_db->quoteName('tpl_article'));
		$query->select($_db->quoteName('tpl_divider'));
		$query->select($_db->quoteName('tpl_id'));
		$query->select($_db->quoteName('basics'));
		$query->select($_db->quoteName('article'));
		$query->select($_db->quoteName('intro'));
		$query->from($_db->quoteName('#__bwpostman_templates'));
		$query->where($_db->quoteName('id') . ' = ' . $template_id);

		$_db->setQuery($query);
		try
		{
			$tpl = $_db->loadObject();
		}
		catch (RuntimeException $e)
		{
			JFactory::getApplication()->enqueueMessage($e->getMessage(), 'error');
		}

		if (is_string($tpl->basics))
		{
			$registry = new JRegistry;
			$registry->loadString($tpl->basics);
			$tpl->basics = $registry->toArray();
		}

		if (is_string($tpl->article))
		{
			$registry = new JRegistry;
			$registry->loadString($tpl->article);
			$tpl->article = $registry->toArray();
		}

		if (is_string($tpl->intro))
		{
			$registry = new JRegistry;
			$registry->loadString($tpl->intro);
			$tpl->intro = $registry->toArray();
		}

		// only for old templates
		if (empty($tpl->article))
		{
			$tpl->article['show_createdate'] = $params->get('newsletter_show_createdate');
			$tpl->article['show_author'] = $params->get('newsletter_show_author');
			$tpl->article['show_readon'] = 1;
		}

		return $tpl;
	}

	/**
	 * Method to get the template assets which are used to compose a newsletter
	 *
	 * @access	public
	 *
	 * @param   int    $template_id     template id
	 *
	 * @return	array
	 *
	 * @throws Exception
	 *
	 * @since	2.3.0 here (moved from newsletter model there since 2.0.0)
	 */
	public function getTemplateAssets($template_id)
	{
		$tpl_assets = array();

		$_db	= JFactory::getDbo();
		$query	= $_db->getQuery(true);
		$query->select('*');
		$query->from($_db->quoteName('#__bwpostman_templates_tags'));
		$query->where($_db->quoteName('templates_table_id') . ' = ' . (int) $template_id);
		$_db->setQuery($query);
		try
		{
			$tpl_assets = $_db->loadAssoc();
		}
		catch (RuntimeException $e)
		{
			JFactory::getApplication()->enqueueMessage($e->getMessage(), 'error');
		}

		return $tpl_assets;
	}

	/**
	 * Method to replace edit and unsubscribe link
	 *
	 * @access	private
	 *
	 * @param   string  $text
	 *
	 * @return 	boolean
	 *
	 * @since	2.3.0 here (moved from newsletter model)
	 */
	public function replaceTplLinks(&$text)
	{
		$lang = JFactory::getLanguage();
		$lang->load('com_bwpostman', JPATH_ADMINISTRATOR, 'en_GB', true);
		$lang->load('com_bwpostman', JPATH_ADMINISTRATOR, null, true);

		$params 			= JComponentHelper::getParams('com_bwpostman');
		$del_sub_1_click	= $params->get('del_sub_1_click');

		// replace edit and unsubscribe link
		if ($del_sub_1_click === '0')
		{
			$replace1	= '<a href="[EDIT_HREF]">' . JText::_('COM_BWPOSTMAN_TPL_UNSUBSCRIBE_LINK_TEXT') . '</a>';
		}
		else
		{
			$replace1	= '<a href="[UNSUBSCRIBE_HREF]">' . JText::_('COM_BWPOSTMAN_TPL_UNSUBSCRIBE_LINK_TEXT') . '</a>';
		}
		$text		= str_replace('[%unsubscribe_link%]', $replace1, $text);
		$replace2	= '<a href="[EDIT_HREF]">' . JText::_('COM_BWPOSTMAN_TPL_EDIT_LINK_TEXT') . '</a>';
		$text		= str_replace('[%edit_link%]', $replace2, $text);

		return true;
	}

	/**
	 * Method to add the HTML-Tags and the css to the HTML-Newsletter
	 *
	 * @access	private
	 *
	 * @param 	string  $text      HTML newsletter
	 * @param   int     $id
	 *
	 * @return 	boolean
	 *
	 * @throws Exception
	 *
	 * @since 2.3.0 here (moved from newsletter model)
	 */
	public function addHtmlTags(&$text, &$id)
	{
		$params = JComponentHelper::getParams('com_bwpostman');
		$tpl    = self::getTemplate($id);

		// add template assets only for user-made templates
		if ($tpl->tpl_id == '0')
		{
			$tpl_assets	= self::getTemplateAssets($id);
			if (!empty($tpl_assets))
			{
				foreach ($tpl_assets as $key => $value)
				{
					$tpl->$key	= $value;
				}
			}
		}

		$newtext  = isset($tpl->tpl_tags_head) && $tpl->tpl_tags_head == 0 ? $tpl->tpl_tags_head_advanced : BwPostmanTplHelper::getHeadTag();
		$newtext .= '   <style type="text/css">' . "\n";
		$newtext .= '   ' . $tpl->tpl_css . "\n";
		// only for old newsletters with template_id < 1
		if ($id < 1 && $params->get('use_css_for_html_newsletter') == 1)
		{
			$params	= JComponentHelper::getParams('com_bwpostman');
			$css	= $params->get('css_for_html_newsletter');
			$newtext .= '   ' . $css . "\n";
		}

		$dispatcher = JEventDispatcher::getInstance();
		JPluginHelper::importPlugin('bwpostman');
		$dispatcher->trigger('onBwPostmanBeforeCustomCss', array(&$newtext));

		if (isset($tpl->basics['custom_css']))
		{
			$newtext .= $tpl->basics['custom_css'] . "\n";
		}

		$newtext .= '   </style>' . "\n";
		$newtext .= ' </head>' . "\n";

		if (isset($tpl->basics['paper_bg']))
		{
			$newtext .= ' <body bgcolor="' . $tpl->basics['paper_bg'] .
				'" emb-default-bgcolor="' . $tpl->basics['paper_bg'] . '" style="background-color:' . $tpl->basics['paper_bg'] .
				';color:' . $tpl->basics['legal_color'] . ';">' . "\n";
		}
		else
		{
			$newtext .= isset($tpl->tpl_tags_body) && $tpl->tpl_tags_body == 0 ? $tpl->tpl_tags_body_advanced : BwPostmanTplHelper::getBodyTag();
		}

		$newtext .= $text . "\n";
		$newtext .= ' </body>' . "\n";
		$newtext .= '</html>' . "\n";

		$text = $newtext;

		return true;
	}

	/**
	 * Method to add the HTML-footer to the HTML-Newsletter
	 *
	 * @access	private
	 *
	 * @param 	string $text        HTML newsletter
	 * @param   integer $templateId template id
	 *
	 * @return 	boolean
	 *
	 * @throws Exception
	 *
	 * @since 2.3.0 here (moved from newsletter model)
	 */
	public function addHTMLFooter(&$text, &$templateId)
	{
		$lang = JFactory::getLanguage();
		$lang->load('com_bwpostman', JPATH_ADMINISTRATOR, 'en_GB', true);
		$lang->load('com_bwpostman', JPATH_ADMINISTRATOR, null, true);

		$uri  				= JUri::getInstance();
		$params 			= JComponentHelper::getParams('com_bwpostman');
		$del_sub_1_click	= $params->get('del_sub_1_click');
		$impressum			= JText::_($params->get('legal_information_text'));
		$impressum			= nl2br($impressum, true);
		$sitelink           = $uri->root();

		$dispatcher = JEventDispatcher::getInstance();
		JPluginHelper::importPlugin('bwpostman');
		$dispatcher->trigger('onBwPostmanBeforeObligatoryFooterHtml', array(&$text));

		// get template assets if exists
		$tpl_assets	= self::getTemplateAssets($templateId);

		if (strpos($text, '[%impressum%]') !== false)
		{
			$unsubscribelink	= '';
			$editlink			= '';

			// Trigger Plugin "substitutelinks"
			if(JFactory::getApplication()->getUserState('com_bwpostman.edit.newsletter.data.substitutelinks') == '1')
			{
				JPluginHelper::importPlugin('bwpostman');
				$dispatcher = JEventDispatcher::getInstance();
				$dispatcher->trigger('onBwPostmanSubstituteLinks', array(&$unsubscribelink, &$editlink, &$sitelink));
			}

			if ($del_sub_1_click === '0')
			{
				$replace = "<br /><br />" . JText::sprintf('COM_BWPOSTMAN_NL_FOOTER_HTML', $sitelink) . "<br /><br />" . $impressum;
			}
			else
			{
				$replace = "<br /><br />" . JText::sprintf('COM_BWPOSTMAN_NL_FOOTER_HTML_ONE_CLICK', $sitelink) . "<br /><br />" . $impressum;
			}

			$replace3  = isset($tpl_assets['tpl_tags_legal']) && $tpl_assets['tpl_tags_legal'] == 0 ?
				$tpl_assets['tpl_tags_legal_advanced_b'] :
				BwPostmanTplHelper::getLegalTagBegin();
			$replace3 .= $replace . "<br /><br />\n";
			$replace3 .= isset($tpl_assets['tpl_tags_legal']) && $tpl_assets['tpl_tags_legal'] == 0 ?
				$tpl_assets['tpl_tags_legal_advanced_e'] :
				BwPostmanTplHelper::getLegalTagEnd();

			$text = str_replace('[%impressum%]', $replace3, $text);
		}

		// only for old newsletters with template_id < 1
		if ($templateId < 1)
		{
			if ($del_sub_1_click === '0')
			{
				$replace = JText::_('COM_BWPOSTMAN_NL_FOOTER_HTML_LINE') . JText::sprintf('COM_BWPOSTMAN_NL_FOOTER_HTML', $sitelink) . $impressum;
			}
			else
			{
				$replace = JText::_('COM_BWPOSTMAN_NL_FOOTER_HTML_LINE') . JText::sprintf('COM_BWPOSTMAN_NL_FOOTER_HTML_ONE_CLICK', $sitelink) . $impressum;
			}
			$text = str_replace("[dummy]", "<div class=\"footer-outer\"><p class=\"footer-inner\">{$replace}</p></div>", $text);
		}

		$dispatcher->trigger('onBwPostmanAfterObligatoryFooter', array(&$text, &$templateId));

		return true;
	}

	/**
	 * Method to replace edit and unsubscribe link
	 *
	 * @access	private
	 *
	 * @param   string  $text
	 *
	 * @return 	boolean
	 *
	 * @throws Exception
	 *
	 * @since	2.3.0 here (moved from newsletter model, there since 1.1.0)
	 */
	public function replaceTextTplLinks(&$text)
	{
		$lang = JFactory::getLanguage();
		$lang->load('com_bwpostman', JPATH_ADMINISTRATOR, 'en_GB', true);
		$lang->load('com_bwpostman', JPATH_ADMINISTRATOR, null, true);

		$uri  				= JUri::getInstance();
		$itemid_edit		= $this->getItemid('edit');
		$itemid_unsubscribe	= $this->getItemid('register');
		$params 			= JComponentHelper::getParams('com_bwpostman');
		$del_sub_1_click	= $params->get('del_sub_1_click');

		if ($del_sub_1_click === '0')
		{
			$unsubscribelink	= $uri->root() . 'index.php?option=com_bwpostman&amp;Itemid=' . $itemid_edit .
				'&amp;view=edit&amp;task=unsub&amp;editlink=[EDITLINK]';
		}
		else
		{
			$unsubscribelink	= $uri->root() . 'index.php?option=com_bwpostman&amp;Itemid=' . $itemid_unsubscribe .
				'&amp;view=edit&amp;task=unsubscribe&amp;email=[UNSUBSCRIBE_EMAIL]&amp;code=[UNSUBSCRIBE_CODE]';
		}

		$editlink			= $uri->root() . 'index.php?option=com_bwpostman&amp;Itemid=' . $itemid_edit . '&amp;view=edit&amp;editlink=[EDITLINK]';
		$sitelink			= '';

		// Trigger Plugin "substitutelinks"
		if(JFactory::getApplication()->getUserState('com_bwpostman.edit.newsletter.data.substitutelinks') == '1')
		{
			JPluginHelper::importPlugin('bwpostman');
			$dispatcher = JEventDispatcher::getInstance();
			$dispatcher->trigger('onBwPostmanSubstituteLinks', array(&$unsubscribelink, &$editlink, &$sitelink));
		}

		// replace edit and unsubscribe link
		$replace1	= '+ ' . JText::_('COM_BWPOSTMAN_TPL_UNSUBSCRIBE_LINK_TEXT') . " +\n  " . $unsubscribelink;
		$text		= str_replace('[%unsubscribe_link%]', $replace1, $text);
		$replace2	= '+ ' . JText::_('COM_BWPOSTMAN_TPL_EDIT_LINK_TEXT') . " +\n  " . $editlink;
		$text		= str_replace('[%edit_link%]', $replace2, $text);

		return true;
	}

	/**
	 * Method to add the footer Text-Newsletter
	 *
	 * @access	private
	 *
	 * @param 	string  $text   Text newsletter
	 * @param   int     $id
	 *
	 * @return 	boolean
	 *
	 * @throws Exception
	 *
	 * @since 2.3.0 here (moved from newsletter model)
	 */
	public function addTextFooter(&$text, &$id)
	{
		$lang = JFactory::getLanguage();
		$lang->load('com_bwpostman', JPATH_ADMINISTRATOR, 'en_GB', true);
		$lang->load('com_bwpostman', JPATH_ADMINISTRATOR, null, true);

		$uri  				= JUri::getInstance();
		$itemid_unsubscribe	= $this->getItemid('register');
		$itemid_edit		= $this->getItemid('edit');
		$params 			= JComponentHelper::getParams('com_bwpostman');
		$del_sub_1_click	= $params->get('del_sub_1_click');
		$impressum			= "\n\n" . JText::_($params->get('legal_information_text')) . "\n\n";

		$unsubscribelink	= $uri->root() . 'index.php?option=com_bwpostman&amp;Itemid=' . $itemid_unsubscribe .
			'&amp;view=edit&amp;task=unsubscribe&amp;email=[UNSUBSCRIBE_EMAIL]&amp;code=[UNSUBSCRIBE_CODE]';
		$editlink			= $uri->root() . 'index.php?option=com_bwpostman&amp;Itemid=' . $itemid_edit . '&amp;view=edit&amp;editlink=[EDITLINK]';
		$sitelink			= $uri->root();

		$dispatcher = JEventDispatcher::getInstance();
		JPluginHelper::importPlugin('bwpostman');
		$dispatcher->trigger('onBwPostmanBeforeObligatoryFooterText', array(&$text));

		// Trigger Plugin "substitutelinks"
		if(JFactory::getApplication()->getUserState('com_bwpostman.edit.newsletter.data.substitutelinks') == '1')
		{
			JPluginHelper::importPlugin('bwpostman');
			$dispatcher = JEventDispatcher::getInstance();
			$dispatcher->trigger('onBwPostmanSubstituteLinks', array(&$unsubscribelink, &$editlink, &$sitelink));
		}

		if (strpos($text, '[%impressum%]') !== false)
		{
			// replace [%impressum%]
			if ($del_sub_1_click === '0')
			{
				$replace	= "\n\n" . JText::sprintf('COM_BWPOSTMAN_NL_FOOTER_TEXT', $sitelink, $editlink) . $impressum;
			}
			else
			{
				$replace	= "\n\n" . JText::sprintf('COM_BWPOSTMAN_NL_FOOTER_TEXT_ONE_CLICK', $sitelink, $unsubscribelink, $editlink) . $impressum;
			}
			$text		= str_replace('[%impressum%]', $replace, $text);
		}

		// only for old newsletters with template_id < 1
		if ($id < 1)
		{
			if ($del_sub_1_click === '0')
			{
				$replace	= JText::_('COM_BWPOSTMAN_NL_FOOTER_TEXT_LINE') .
					JText::sprintf('COM_BWPOSTMAN_NL_FOOTER_TEXT', $sitelink, $editlink) . $impressum;
			}
			else
			{
				$replace	= JText::_('COM_BWPOSTMAN_NL_FOOTER_TEXT_LINE') .
					JText::sprintf('COM_BWPOSTMAN_NL_FOOTER_TEXT_ONE_CLICK', $sitelink, $unsubscribelink, $editlink) . $impressum;
			}
			$text		= str_replace("[dummy]", $replace, $text);
		}

		return true;
	}

	/**
	 * Method to add the Template-Tags to the content
	 * Template tags are:
	 * - HTML doctype/header
	 * - HTML body
	 * - newsletter article div (concerns every single article)
	 * - newsletter read more div (concerns every single read mor button)
	 * - newsletter legal info (implemented as table by default)
	 *
	 * @access	private
	 *
	 * @param   string  $text
	 * @param   int     $id
	 *
	 * @return 	boolean
	 *
	 * @throws Exception
	 *
	 * @since	2.3.0 here (moved from newsletter model, there since 1.1.0)
	 */
	public function addTplTags(&$text, &$id)
	{
		$tpl = self::getTemplate($id);

		$newtext	= $tpl->tpl_html . "\n";

		// make sure that conditions be usable - some editors add space to conditions
		$text		= str_replace('[%content%]', str_replace('<!-- [if', '<!--[if', $text), $newtext);

		return true;
	}

	/**
	 * Method to add the TEXT to the TEXT-Newsletter
	 *
	 * @access	private
	 *
	 * @param 	string  $text   Text newsletter
	 * @param   int     $id     template id
	 *
	 * @return 	boolean
	 *
	 * @throws Exception
	 *
	 * @since	2.3.0 here (moved from newsletter model, there since 1.1.0)
	 */
	public function addTextTpl(&$text, &$id)
	{
		$tpl	= self::getTemplate($id);

		$text	= str_replace('[%content%]', "\n" . $text, $tpl->tpl_html);

		return true;
	}

	/**
	 * Method to process special characters
	 *
	 * @param $text
	 *
	 * @return mixed
	 *
	 * @since       0.9.1
	 */
	private function unHTMLSpecialCharsAll($text)
	{
		$text = $this->deHTMLEntities($text);

		return $text;
	}

	/**
	 * convert html special entities to literal characters
	 *
	 * @param string $text
	 *
	 * @return  string  $text
	 *
	 * @since       0.9.1
	 */
	private function deHTMLEntities($text)
	{
		$search  = array(
			"'&(quot|#34);'i",
			"'&(amp|#38);'i",
			"'&(lt|#60);'i",
			"'&(gt|#62);'i",
			"'&(nbsp|#160);'i",
			"'&(iexcl|#161);'i",
			"'&(cent|#162);'i",
			"'&(pound|#163);'i",
			"'&(curren|#164);'i",
			"'&(yen|#165);'i",
			"'&(brvbar|#166);'i",
			"'&(sect|#167);'i",
			"'&(uml|#168);'i",
			"'&(copy|#169);'i",
			"'&(ordf|#170);'i",
			"'&(laquo|#171);'i",
			"'&(not|#172);'i",
			"'&(shy|#173);'i",
			"'&(reg|#174);'i",
			"'&(macr|#175);'i",
			"'&(neg|#176);'i",
			"'&(plusmn|#177);'i",
			"'&(sup2|#178);'i",
			"'&(sup3|#179);'i",
			"'&(acute|#180);'i",
			"'&(micro|#181);'i",
			"'&(para|#182);'i",
			"'&(middot|#183);'i",
			"'&(cedil|#184);'i",
			"'&(supl|#185);'i",
			"'&(ordm|#186);'i",
			"'&(raquo|#187);'i",
			"'&(frac14|#188);'i",
			"'&(frac12|#189);'i",
			"'&(frac34|#190);'i",
			"'&(iquest|#191);'i",
			"'&(Agrave|#192);'",
			"'&(Aacute|#193);'",
			"'&(Acirc|#194);'",
			"'&(Atilde|#195);'",
			"'&(Auml|#196);'",
			"'&(Aring|#197);'",
			"'&(AElig|#198);'",
			"'&(Ccedil|#199);'",
			"'&(Egrave|#200);'",
			"'&(Eacute|#201);'",
			"'&(Ecirc|#202);'",
			"'&(Euml|#203);'",
			"'&(Igrave|#204);'",
			"'&(Iacute|#205);'",
			"'&(Icirc|#206);'",
			"'&(Iuml|#207);'",
			"'&(ETH|#208);'",
			"'&(Ntilde|#209);'",
			"'&(Ograve|#210);'",
			"'&(Oacute|#211);'",
			"'&(Ocirc|#212);'",
			"'&(Otilde|#213);'",
			"'&(Ouml|#214);'",
			"'&(times|#215);'i",
			"'&(Oslash|#216);'",
			"'&(Ugrave|#217);'",
			"'&(Uacute|#218);'",
			"'&(Ucirc|#219);'",
			"'&(Uuml|#220);'",
			"'&(Yacute|#221);'",
			"'&(THORN|#222);'",
			"'&(szlig|#223);'",
			"'&(agrave|#224);'",
			"'&(aacute|#225);'",
			"'&(acirc|#226);'",
			"'&(atilde|#227);'",
			"'&(auml|#228);'",
			"'&(aring|#229);'",
			"'&(aelig|#230);'",
			"'&(ccedil|#231);'",
			"'&(egrave|#232);'",
			"'&(eacute|#233);'",
			"'&(ecirc|#234);'",
			"'&(euml|#235);'",
			"'&(igrave|#236);'",
			"'&(iacute|#237);'",
			"'&(icirc|#238);'",
			"'&(iuml|#239);'",
			"'&(eth|#240);'",
			"'&(ntilde|#241);'",
			"'&(ograve|#242);'",
			"'&(oacute|#243);'",
			"'&(ocirc|#244);'",
			"'&(otilde|#245);'",
			"'&(ouml|#246);'",
			"'&(divide|#247);'i",
			"'&(oslash|#248);'",
			"'&(ugrave|#249);'",
			"'&(uacute|#250);'",
			"'&(ucirc|#251);'",
			"'&(uuml|#252);'",
			"'&(yacute|#253);'",
			"'&(thorn|#254);'",
			"'&(yuml|#255);'"
		);
		$replace = array(
			"\"",
			"&",
			"<",
			">",
			" ",
			chr(161),
			chr(162),
			chr(163),
			chr(164),
			chr(165),
			chr(166),
			chr(167),
			chr(168),
			chr(169),
			chr(170),
			chr(171),
			chr(172),
			chr(173),
			chr(174),
			chr(175),
			chr(176),
			chr(177),
			chr(178),
			chr(179),
			chr(180),
			chr(181),
			chr(182),
			chr(183),
			chr(184),
			chr(185),
			chr(186),
			chr(187),
			chr(188),
			chr(189),
			chr(190),
			chr(191),
			chr(192),
			chr(193),
			chr(194),
			chr(195),
			chr(196),
			chr(197),
			chr(198),
			chr(199),
			chr(200),
			chr(201),
			chr(202),
			chr(203),
			chr(204),
			chr(205),
			chr(206),
			chr(207),
			chr(208),
			chr(209),
			chr(210),
			chr(211),
			chr(212),
			chr(213),
			chr(214),
			chr(215),
			chr(216),
			chr(217),
			chr(218),
			chr(219),
			chr(220),
			chr(221),
			chr(222),
			chr(223),
			chr(224),
			chr(225),
			chr(226),
			chr(227),
			chr(228),
			chr(229),
			chr(230),
			chr(231),
			chr(232),
			chr(233),
			chr(234),
			chr(235),
			chr(236),
			chr(237),
			chr(238),
			chr(239),
			chr(240),
			chr(241),
			chr(242),
			chr(243),
			chr(244),
			chr(245),
			chr(246),
			chr(247),
			chr(248),
			chr(249),
			chr(250),
			chr(251),
			chr(252),
			chr(253),
			chr(254),
			chr(255)
		);

		return $text = preg_replace($search, $replace, $text);
	}
}
