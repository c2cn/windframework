<?php

L::import('WIND:core.filter.WindHandlerInterceptor');
/**
 *
 * the last known user to change this file in the repository  <$LastChangedBy$>
 * @author Qiong Wu <papa0924@gmail.com>
 * @version $Id$
 * @package 
 */
class WindValidateListener extends WindHandlerInterceptor {

	/**
	 * @var WindHttpRequest
	 */
	private $request = null;

	private $validateRules = array();

	private $validatorClass = '';

	/**
	 * @param WindHttpRequest $request
	 * @param array $validateRules
	 * @param string $validatorClass
	 */
	public function __construct($request, $validateRules, $validatorClass) {
		$this->request = $request;
		$this->validateRules = (array) $validateRules;
		$this->validatorClass = $validatorClass;
	}

	/* (non-PHPdoc)
	 * @see WindHandlerInterceptor::preHandle()
	 */
	public function preHandle() {
		
		print_r($this->validateRules);
		echo $this->validatorClass;
	}

	/* (non-PHPdoc)
	 * @see WindHandlerInterceptor::postHandle()
	 */
	public function postHandle() {

	}

}

?>