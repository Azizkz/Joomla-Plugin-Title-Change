<?php
/**
 * System Plugin for Joomla! - Title change
 *
 * @author     Viviana Menzel <vivianamenzel@dr-menzel-it.de>
 * @copyright  Copyright 2015 Viviana Menzel
 * @license    GNU Public License version 3 or later
 * @link       http://www.dr-menzel-it.de/
 */

defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');

/**
 * Class PlgSystemTitlechange
 *
 * @since  April 2015
 */

class PlgSystemTitlechange extends JPlugin
{
	/**
	 * Constructor
	 *
	 * @param   object  &$subject  Instance of JEventDispatcher
	 * @param   array   $config    Configuration
	 */
	public function __construct(& $subject, $config)
	{
		parent::__construct($subject, $config);

		$this->loadLanguage();
	}

	/**
	 * Event method onAfterRender
	 *
	 * @return null
	 */
	public function onAfterRender()
	{
		$application = JFactory::getApplication();
		if ($application->isSite() == false)
		{
			return;
		}
		$body = $application->getBody();
		$body = $this->replaceTags($body);
		$application->setBody($body);
	}
	/**
	 * Method to replace tags in a text
	 * 
	 * @param   string  $text  Text to replace tags in
	 *
	 * @return mixed
	 */
	public function replaceTags($text)
	{
		$result = $text;
		while (preg_match_all('/<title>([^\{\}]*){titlechange:([^\s}]+)\ ([^\}]+)\}(.*?)<\/title>/', $text, $matches)) {
			foreach ($matches[2] as $matchIndex => $match)
			{
				$tag = $matches[0][$matchIndex];
				$group1 = $matches[1][$matchIndex];
				$group3 = $matches[3][$matchIndex];
				$group4 = $matches[4][$matchIndex];
				$text = str_replace($tag, "<title>" .$group1. $group3 .$group4. "</title>", $text);
			}
		}
		if (!preg_match_all('/{titlechange:([^\s}]+)\ ([^\}]+)\}/', $text, $matches))
		{
			return $text;
		} else {
			foreach ($matches[2] as $matchIndex => $match)
			{
				$tag = $matches[0][$matchIndex];
				$classname = $matches[1][$matchIndex];
				$text = str_replace($tag, "<span class=".$classname.">" .$match . "</span>", $text);
			}
				
			return $text;
		}
	}
}
