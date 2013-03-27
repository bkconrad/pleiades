<?php
App::uses('View', 'View');
App::uses('Helper', 'View');
App::uses('TagHelper', 'View/Helper');
class TagHelperTest extends CakeTestCase {
	public function setUp() {
		parent::setUp();
		$View = new View();
		$this->Tag = new TagHelper($View);
	}
	public function tearDown() {
		unset($this->Tag);

		parent::tearDown();
	}

	public function testNoTags() {
    $result = $this->Tag->tags(0x00);
    $this->assertEqual(array(), $result);
	}

	public function testAllTags() {
    $result = $this->Tag->tags(0xFF);
    $this->assertEqual($this->Tag->allTags(), $result);
	}

}
