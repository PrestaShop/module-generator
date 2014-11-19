<?php
/**
* 2007-2014 PrestaShop
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
* @author    PrestaShop SA <contact@prestashop.com>
* @copyright 2007-2014 PrestaShop SA
* @license   http://addons.prestashop.com/en/content/12-terms-and-conditions-of-use
* International Registered Trademark & Property of PrestaShop SA
* -------------------------------------------------------------------
*
* Description :
*   This is a PHP class for generating cache file.
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

	/**
	* Get TTL
	*
	* @param string $name
	* @param int $ttl
	* @return sting
	*/
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
	* @param int $ttl
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
	* @param string $name
	* @param mixed $data
	* @return bool
	*/
	public static function setCache($name, $data)
	{
		return file_put_contents(self::$path.$name, self::compressObject($data));
	}

	/**
	* Delete all cache
	*/
	public static function clearAllCache()
	{
		$is_dot = array ('.', '..');
		if (is_dir(self::$path))
		{
			if (version_compare(phpversion(), '5.3', '<'))
			{
				$iterator = new RecursiveIteratorIterator(
					new RecursiveDirectoryIterator(self::$path),
					RecursiveIteratorIterator::SELF_FIRST
				);
			}
			else
			{
				$iterator = new RecursiveIteratorIterator(
					new RecursiveDirectoryIterator(self::$path, RecursiveDirectoryIterator::SKIP_DOTS),
					RecursiveIteratorIterator::CHILD_FIRST
				);
			}

			foreach ($iterator as $file)
			{
				if (version_compare(phpversion(), '5.2.17', '<='))
				{
					if (in_array($file->getBasename(), $is_dot))
						continue;
				}
				elseif (version_compare(phpversion(), '5.3', '<'))
				{
					if ($file->isDot())
						continue;
				}
				if ($file->getBasename() !== 'index.php')
					unlink($file->getPathname());
			}
			unset($iterator, $file);
		}
	}

	/**
	* Delete a data in cache
	*
	* @param string $name
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
	* @param mixed $string_array
	* @return mixed
	*/
	public static function compressObject($string_array)
	{
		$base_encode = 'base64_encode';
		return (strtr($base_encode(addslashes(gzcompress(serialize($string_array), 9))), '+/=', '-_,'));
	}

	/**
	* Uncompress a data in cache
	*
	* @param mixed $string_array
	* @return mixed
	*/
	public static function uncompressObject($string_array)
	{
		$base_decode = 'base64_decode';
		$strip_slashes = 'stripslashes';
		return (unserialize(gzuncompress($strip_slashes($base_decode(strtr($string_array, '-_,', '+/='))))));
	}
}