<?php
/*
 * Joomla! component Emailscheduler
 *
 * @author Yireo (info@yireo.com)
 * @copyright Copyright 2015
 * @license GNU Public License
 * @link http://www.yireo.com
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

/**
 * Emailscheduler update helper
 */
class EmailschedulerUpdate
{
	/**
	 * Run update queries
	 */
	static public function runUpdateQueries()
	{
		$sqlfiles = array(
			JPATH_COMPONENT . '/sql/install.sql',
			JPATH_COMPONENT . '/sql/update.sql',);

		foreach ($sqlfiles as $sqlfile)
		{
			if (file_exists($sqlfile) && is_readable($sqlfile))
			{
				self::runUpdateQueriesFromFile($sqlfile);
			}
		}
	}

	/**
	 * Run update queries from file
	 */
	static public function runUpdateQueriesFromFile($sqlfile)
	{
		$db = JFactory::getDBO();
		$buffer = file_get_contents($sqlfile);

		if (method_exists('JDatabaseDriver', 'splitSql'))
		{
			$queries = JDatabaseDriver::splitSql($buffer);
		}
		elseif (method_exists('JDatabase', 'splitSql'))
		{
			$queries = JDatabase::splitSql($buffer);
		}
		else
		{
			return false;
		}

		foreach ($queries as $query)
		{
			$query = trim($query);

			if ($query != '' && $query{0} != '#')
			{
				$db->setQuery($query);
				$db->execute();
			}
		}
	}
}
