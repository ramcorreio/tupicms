<?php
namespace Internit\OferecaBundle\Classe;



class Callback implements ICallbackNamed {
	public $callback = null;
	public $params = null;
	protected $name;
	public function __construct($callback, $param1 = null, $param2 = null,
			$param3 = null) {
		$params = func_get_args();
		$params = array_slice($params, 1);
		if ($callback instanceof Callback) {
			// TODO implement recurention
		} else {
			$this->callback = $callback;
			$this->params = $params;
		}
	}
	public function getName() {
		return 'Callback: '.$this->name;
	}
	public function hasName() {
		return isset($this->name) && $this->name;
	}
	public function setName($name) {
		$this->name = $name;
		return $this;
	}
	// TODO test me
	//	public function addParams() {
	//		$params = func_get_args();
	//		return new Callback($this->callback, $this->params+$params);
	//	}
}
/**
 * Shorthand for new Callback(create_function(...), ...);
 *
 * @author Tobiasz Cudnik <tobiasz.cudnik/gmail.com>
 */