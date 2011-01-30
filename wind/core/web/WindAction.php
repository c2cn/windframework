<?php
/**
 * @author Qiong Wu <papa0924@gmail.com> 2010-11-16
 * @link http://www.phpwind.com
 * @copyright Copyright &copy; 2003-2110 phpwind.com
 * @license 
 */

L::import('WIND:WindModule');
/**
 * the last known user to change this file in the repository  <$LastChangedBy$>
 * @author Qiong Wu <papa0924@gmail.com>
 * @version $Id$ 
 * @package 
 */
abstract class WindAction extends WindModule {

	protected $request;

	protected $response;

	/**
	 * @param WindHttpRequest $request
	 * @param WindHttpResponse $response
	 */
	public function __construct($request, $response) {
		$this->request = $request;
		$this->response = $response;
	}

	/**
	 * 根据路由信息重定向执行方法
	 * 
	 * @param AbstractWindRouter $handlerAdapter
	 */
	public function doAction($handlerAdapter) {
		$this->beforeAction();
		$this->run();
		$this->afterAction();
		return $this->forward();
	}

	public function beforeAction() {}

	abstract public function run();

	public function afterAction() {}

	/**
	 * 请求另一个操作处理
	 * 
	 * @param string $actionHandle
	 * @param string $path
	 */
	public function forwardAction($actionHandle = '', $path = '', $isRedirect = false) {
		$this->forward()->setAction($actionHandle, $path, $isRedirect);
	}

	/**
	 * 请求一个重定向Action
	 * 
	 * @param string $actionHandle
	 * @param string $path
	 * @param mixed string | array $args
	 */
	public function forwardRedirectAction($actionHandle = '', $path = '', $args = '') {
		$this->forward()->setAction($actionHandle, $path, true, $args);
	}

	/* 数据处理 */
	
	/**
	 * 设置模板数据
	 * @param string|array|object $data
	 * @param string $key
	 */
	public function setOutput($data, $key = '') {
		$this->forward()->setVars($data, $key);
	}

	/**
	 * 获得输入数据
	 * 如果输入了回调方法则返回数组:
	 * 第一个值：value
	 * 第二个值：验证结果
	 * @param string $name input name
	 * @param string $type input type (GET POST COOKIE)
	 * @param string $callback | validation for input
	 * @return array | string
	 */
	public function getInput($name, $type = '', $callback = null) {
		if (is_array($name))
			return $this->getInputWithArray($name, $type);
		else
			return $this->getInputWithString($name, $type, $callback);
	}

	/* 错误处理 */
	
	/**
	 * 添加错误信息
	 * 
	 * @param string $message
	 * @param string $key
	 */
	public function addError($message, $key = '') {
		$this->error->addError($message, $key);
	}

	/**
	 * @param string $message
	 * @param string $key
	 */
	public function sendError($message = '', $key = '', $errorAction = '') {
		$this->addError($message, $key);
		$this->error->setErrorAction($errorAction);
		$this->error->sendError();
	}

	/* 模板处理 */
	
	/**
	 * 设置页面模板
	 * @param string $template
	 */
	public function setTemplate($template = '') {
		if ($template) $this->forward()->setTemplateName($template);
	}

	/**
	 * 设置页面布局
	 * 
	 * @param WindLayout $layout
	 */
	public function setLayout($layout = '') {
		$this->forward()->setLayout($layout);
	}

	/**
	 * 设置模板配置--提供多套模板路径机制
	 * 
	 * @param string $templateConfigName
	 */
	public function setTemplateConfig($templateConfigName = '') {
		$this->forward()->setTemplateConfig($templateConfigName);
	}

	/**
	 * @return WindForward
	 */
	public function forward() {
		if (!isset($this->forward)) {
			L::import('WIND:core.WindForward');
			$this->forward = new WindForward();
		}
		return $this->forward;
	}

	/**
	 * Enter description here ...
	 * 
	 * @param unknown_type $name
	 * @param unknown_type $type
	 * @return Ambigous <multitype:unknown mixed , string, unknown, multitype:>
	 */
	private function getInputWithString($name, $type = '', $callback = array()) {
		$value = '';
		switch ($type) {
			case 'form':
				$value = $this->response->getData($name);
				break;
			case IWindRequest::INPUT_TYPE_GET:
				$value = $this->request->getGet($name);
				break;
			case IWindRequest::INPUT_TYPE_POST:
				$value = $this->request->getPost($name);
				break;
			case IWindRequest::INPUT_TYPE_COOKIE:
				$value = $this->request->getCookie($name);
				break;
			default:
				$value = $this->request->getAttribute($name);
		}
		return $callback ? array($value, call_user_func_array($callback, array($value))) : $value;
	}

	/**
	 * Enter description here ...
	 * 
	 * @param string $name
	 * @param string $type
	 * @return array
	 */
	private function getInputWithArray($name, $type = '') {
		$result = array();
		foreach ($name as $key => $value) {
			$result[(is_array($value) ? $key : $value)] = $this->getInput($value, $type);
		}
		return $result;
	}
}