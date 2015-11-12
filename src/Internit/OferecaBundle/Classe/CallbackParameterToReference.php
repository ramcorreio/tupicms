<?php
namespace Internit\OferecaBundle\Classe;

class CallbackParameterToReference extends Callback {
	/**
	 * @param $reference
	 * @TODO implement $paramIndex;
	 * param index choose which callback param will be passed to reference
	 */
	public function __construct(&$reference){
		$this->callback =& $reference;
	}
}