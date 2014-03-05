<?php
/**
* 2007-2014 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
* @author    PrestaShop SA <contact@prestashop.com>
* @copyright 2007-2014 PrestaShop SA
* @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
* International Registered Trademark & Property of PrestaShop SA
*/

class TinyCache
{
	protected static $path;

	/** Set time to live in hour */
	const SEO_TIMEEXPIRE_LANG = 24;

	/**
	* Set cache path
	*
	* @param string $path
	* @return void
	*/
	public static function setPath($path)
	{
		self::$path = $path;
	}

	/**
	* Get cache path
	*
	* @return sting
	*/
	public static function getPath()
	{
		return self::$path;
	}

	public static function getTTL($name, $ttl = 0)
	{
		if ($ttl == 0) $ttl = self::SEO_TIMEEXPIRE_LANG;
		$d = strtotime('+ '.$ttl.' hours', filemtime(self::$path.$name));
		return ($name.' '.date('j/m/Y H:m:s', $d));
	}

	/**
	* Retrieve a data from cache
	*
	* @param string $name
	* @return array
	*/
	public static function getCache($name, $ttl = 0)
	{
		if ($ttl == 0) $ttl = self::SEO_TIMEEXPIRE_LANG;

		if (file_exists(self::$path.$name))
		{
			$d = strtotime('+ '.$ttl.' hours', filemtime(self::$path.$name));
			if (time() > $d)
			{
				self::clearCache($name);
				return false;
			}
			return (self::uncompressObject(Tools::file_get_contents(self::$path.$name)));
		}
		return false;
	}

	/**
	* Store a data in cache
	*
	* @param mixed $value
	* @return bool
	*/
	public static function setCache($name, $data)
	{
		return file_put_contents(self::$path.$name, self::compressObject($data));
	}

	/**
	* Delete a data in cache
	*
	* @param mixed $value
	* @return bool
	*/
	public static function clearCache($name)
	{
		if (file_exists(self::$path.$name))
			return unlink(self::$path.$name);
	}

	/**
	* Compress a data in cache
	*
	* @param mixed $value
	* @return bool
	*/
	public static function compressObject($string_array)
	{
		return (strtr(base64_encode(addslashes(gzcompress(serialize($string_array), 9))), '+/=', '-_,'));
	}

	/**
	* Uncompress a data in cache
	*
	* @param mixed $value
	* @return bool
	*/
	public static function uncompressObject($string_array)
	{
		return (unserialize(gzuncompress(stripslashes(base64_decode(strtr($string_array, '-_,', '+/='))))));
	}
}