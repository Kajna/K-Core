<?php 
namespace Core\Util;

/**
 * Utility class.
 *
 * @author <milos@caenazzo.com>
 */
class Util
{
	/**
	 * Variable for caching base() call, since often it is called multiple times.
	 *
	 * @var string
	 */
	protected static $base = null;

    /*
     * @var string
     */
    public static $publicPath = '';

    /**
     * Get site base url.
     *
     * @param string $path
     * @return string
     */
    public static function base($path = '')
    {
        // Check for cached version of base path
        if (null !== self::$base) {
            return self::$base.$path;
        }

        if (isset($_SERVER['HTTP_HOST'])) {
            $base_url = isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off' ? 'https' : 'http';
            $base_url .= '://'. $_SERVER['HTTP_HOST'];
            $base_url .= str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);
            self::$base = $base_url;
            return $base_url.$path;
        }
        return '';
    }

	/**
	 * Get CSS file path.
	 *
	 * @param  string $css
	 * @return string
	 */
	public static function css($css)
	{
		return self::$base.self::$publicPath.'/css/'.$css;
	}	

	/**
	 * Get JavaScript file path.
	 *
	 * @param  string $js
	 * @return string
	 */
	public static function js($js)
	{
		return self::$base.self::$publicPath.'/js/'.$js;
	}

	/**
	 * Get image file path.
	 *
	 * @param  string $img
	 * @return string
	 */
	public static function img($img)
	{
		return self::$base.self::$publicPath.'/images/'.$img;
	}

    /**
     * Redirect helper function.
     *
     * @var string $url
     * @var int $statusCode
     */
    public static function redirect($url = '', $statusCode = 303)
    {
        header('Location: '.self::base($url), true, $statusCode);
        die();
    }

    /**
     * Redirect helper function.
     *
     * @var string $url
     * @var int $statusCode
     */
    public static function redirectToUrl($url = '', $statusCode = 303)
    {
        header('Location: '.$url, true, $statusCode);
        die();
    }
}