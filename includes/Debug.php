<?php

class Debug
{
	/**
	 * Outputs debugging information in a text file "debug.txt"
	 * {@link http://www.hemasolutions.com Hema Solutions}
	 * @access public
	 * @author Anastas Dolushanov <adolushanov@gmail.com>
	 * @copyright GPL 2010
	 * @package HSF core
	 * @version 3.0
	 */
	static function var_debug($item)
	{
		if (!is_writable (dirname(__FILE__) . '/debug.txt'))
		{
			@chmod (dirname(__FILE__) . '/debug.txt', 0777);
		}
		$fh = fopen (dirname(__FILE__) . '/debug.txt', 'ab');
		switch (true)
		{
			case is_object ($item) === true:
				fputs ($fh, '[' . date ('Y-m-d H:i:s') . '] ' . var_export ($item, true) . "\r\n");
				break;
			case is_array ($item) === true:
				fputs ($fh, '[' . date ('Y-m-d H:i:s') . '] ' . print_r ($item, true) . "\r\n");
				break;
			default:
				fputs ($fh, '[' . date ('Y-m-d H:i:s') . '] ' . $item . "\r\n");
				break;
		}
		fclose ($fh);
	}

	static function sql_debug($query, $binds)
	{
		foreach ($binds as $key => $value)
		{
			$query = str_replace($key, "'" . $value . "'", $query);
		}
		Debug::var_debug($query);
	}

	static function benchmark($message = null)
	{
		global $bm_start;
		global $bm_end;

		$start = explode (" ", $bm_start);
		$end = explode (" ", $bm_end);

		Debug::var_debug (($message == null ? "benchmark" : $message) . " : " . round (($end[1] - $start[1]) + ($end[0] - $start[0]), 6));
	}
}
?>
