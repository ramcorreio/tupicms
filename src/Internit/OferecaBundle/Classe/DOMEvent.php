<?php
namespace Internit\OferecaBundle\Classe;
/**
 * phpQuery is a server-side, chainable, CSS3 selector driven
 * Document Object Model (DOM) API based on jQuery JavaScript Library.
 *
 * @version 0.9.5
 * @link http://code.google.com/p/phpquery/
 * @link http://phpquery-library.blogspot.com/
 * @link http://jquery.com/
 * @author Tobiasz Cudnik <tobiasz.cudnik/gmail.com>
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 * @package phpQuery
 */

// class names for instanceof
// TODO move them as class constants into phpQuery
define('DOMDOCUMENT', 'DOMDocument');
define('DOMELEMENT', 'DOMElement');
define('DOMNODELIST', 'DOMNodeList');
define('DOMNODE', 'DOMNode');

/**
 * DOMEvent class.
 *
 * Based on
 * @link http://developer.mozilla.org/En/DOM:event
 * @author Tobiasz Cudnik <tobiasz.cudnik/gmail.com>
 * @package phpQuery
 * @todo implement ArrayAccess ?
 */
class DOMEvent {
	/**
	 * Returns a boolean indicating whether the event bubbles up through the DOM or not.
	 *
	 * @var unknown_type
	 */
	public $bubbles = true;
	/**
	 * Returns a boolean indicating whether the event is cancelable.
	 *
	 * @var unknown_type
	 */
	public $cancelable = true;
	/**
	 * Returns a reference to the currently registered target for the event.
	 *
	 * @var unknown_type
	 */
	public $currentTarget;
	/**
	 * Returns detail about the event, depending on the type of event.
	 *
	 * @var unknown_type
	 * @link http://developer.mozilla.org/en/DOM/event.detail
	 */
	public $detail;	// ???
	/**
	 * Used to indicate which phase of the event flow is currently being evaluated.
	 *
	 * NOT IMPLEMENTED
	 *
	 * @var unknown_type
	 * @link http://developer.mozilla.org/en/DOM/event.eventPhase
	 */
	public $eventPhase;	// ???
	/**
	 * The explicit original target of the event (Mozilla-specific).
	 *
	 * NOT IMPLEMENTED
	 *
	 * @var unknown_type
	 */
	public $explicitOriginalTarget; // moz only
	/**
	 * The original target of the event, before any retargetings (Mozilla-specific).
	 *
	 * NOT IMPLEMENTED
	 *
	 * @var unknown_type
	 */
	public $originalTarget;	// moz only
	/**
	 * Identifies a secondary target for the event.
	 *
	 * @var unknown_type
	 */
	public $relatedTarget;
	/**
	 * Returns a reference to the target to which the event was originally dispatched.
	 *
	 * @var unknown_type
	 */
	public $target;
	/**
	 * Returns the time that the event was created.
	 *
	 * @var unknown_type
	 */
	public $timeStamp;
	/**
	 * Returns the name of the event (case-insensitive).
	 */
	public $type;
	public $runDefault = true;
	public $data = null;
	public function __construct($data) {
		foreach($data as $k => $v) {
			$this->$k = $v;
		}
		if (! $this->timeStamp)
			$this->timeStamp = time();
	}
	/**
	 * Cancels the event (if it is cancelable).
	 *
	 */
	public function preventDefault() {
		$this->runDefault = false;
	}
	/**
	 * Stops the propagation of events further along in the DOM.
	 *
	 */
	public function stopPropagation() {
		$this->bubbles = false;
	}
}


if (!function_exists('mb_internal_encoding'))
{
	function mb_internal_encoding($enc) {return true; }
}

/**
 *  mb_regex_encoding()
 *
 *  Included for mbstring pseudo-compatability.
 */
if (!function_exists('mb_regex_encoding'))
{
	function mb_regex_encoding($enc) {return true; }
}

/**
 *  mb_strlen()
 *
 *  Included for mbstring pseudo-compatability.
 */
if (!function_exists('mb_strlen'))
{
	function mb_strlen($str)
	{
		return strlen($str);
	}
}

/**
 *  mb_strpos()
 *
 *  Included for mbstring pseudo-compatability.
 */
if (!function_exists('mb_strpos'))
{
	function mb_strpos($haystack, $needle, $offset=0)
	{
		return strpos($haystack, $needle, $offset);
	}
}
/**
 *  mb_stripos()
 *
 *  Included for mbstring pseudo-compatability.
 */
if (!function_exists('mb_stripos'))
{
	function mb_stripos($haystack, $needle, $offset=0)
	{
		return stripos($haystack, $needle, $offset);
	}
}

/**
 *  mb_substr()
 *
 *  Included for mbstring pseudo-compatability.
 */
if (!function_exists('mb_substr'))
{
	function mb_substr($str, $start, $length=0)
	{
		return substr($str, $start, $length);
	}
}

/**
 *  mb_substr_count()
 *
 *  Included for mbstring pseudo-compatability.
 */
if (!function_exists('mb_substr_count'))
{
	function mb_substr_count($haystack, $needle)
	{
		return substr_count($haystack, $needle);
	}
}


function pq($arg1, $context = null) {
	$args = func_get_args();
	return call_user_func_array(
		array('phpQuery', 'pq'),
		$args
	);
}
// add plugins dir and Zend framework to include path
set_include_path(
	get_include_path()
		.PATH_SEPARATOR.dirname(__FILE__).'/phpQuery/'
		.PATH_SEPARATOR.dirname(__FILE__).'/phpQuery/plugins/'
);
// why ? no __call nor __get for statics in php...
// XXX __callStatic will be available in PHP 5.3
phpQuery::$plugins = new phpQueryPlugins();
// include bootstrap file (personal library config)
if (file_exists(dirname(__FILE__).'/phpQuery/bootstrap.php'))
	require_once dirname(__FILE__).'/phpQuery/bootstrap.php';