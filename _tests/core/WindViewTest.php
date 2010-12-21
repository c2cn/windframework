<?php
require_once 'core/WindView.php';

class WindViewTest extends BaseTestCase {
	private $templateConfig = 'default';
	
	function testCreateViewerResolver() {
		$windView = $this->createWindView();
		$viewResolver = $windView->createViewerResolver();
		$this->assertEquals('WindViewer', get_class($viewResolver), 'test create viewer resolver failed.');
	}
	
	public function testInitViewWithForward() {
		$windView = $this->createWindView();
		$this->assertEquals($windView->templateDefault, 'index');
		$this->assertEquals($windView->initViewWithForward($this->createWindForward())->templateDefault, 'testIndex');
	}
	
	public function testDoAction() {
		
	}
	
	public function testCreateWindView() {
		$this->templateConfig = 'default';
		C::init(WindViewData::getData1());
		$windView = $this->createWindView();
		$this->assertEquals($windView->templateDir, 'template');
		$this->assertEquals($windView->templateExt, 'htm');
		$this->assertEquals($windView->templateDefault, 'index');
		$this->assertEquals($windView->templateCacheDir, 'cache');
		$this->assertEquals($windView->templateCompileDir, 'compile');
	}
	
	public function testCreateWindView1() {
		$this->templateConfig = '';
		C::init(WindViewData::getData1());
		try {
			$windView = $this->createWindView();
		} catch (Exception $exception) {
			$this->assertEquals('WindException', get_class($exception));
		}
	}
	
	private function createWindForward() {
		$forward = new WindForward();
		$forward->setTemplateName('testIndex');
		return $forward;
	}
	
	private function createWindView() {
		return new WindView($this->templateConfig);
	}
	
	protected function setUp() {
		parent::setUp();
	}
	
	protected function tearDown() {
		parent::tearDown();
	}
}

class WindViewData {
	static public function getData2() {
		return array(
			'templates' => array(), 
			'viewerResolvers' => array(
				'default' => 'WIND:core.viewer.WindViewer'));
	}
	static public function getData1() {
		return array(
			'templates' => array(
				'default' => array(
					'dir' => 'template', 
					'default' => 'index', 
					'ext' => 'htm', 
					'resolver' => 'default', 
					'isCache' => '0', 
					'cacheDir' => 'cache', 
					'compileDir' => 'compile'), 
				'wind' => array(
					'dir' => 'template', 
					'default' => 'index', 
					'ext' => 'htm', 
					'resolver' => 'default', 
					'isCache' => '0', 
					'cacheDir' => 'cache', 
					'compileDir' => 'compile')), 
			'viewerResolvers' => array(
				'default' => 'WIND:core.viewer.WindViewer'));
	}
}
