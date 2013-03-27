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

  public function testTagInput() {
    $result = $this->Tag->tagInput(0xFF);
    foreach ($this->Tag->allTags() as $bit => $name) {
      $this->assertRegExp("/$name/i", $result);
    }
  }

  public function testInt() {
    $data = array_flip($this->Tag->allTags());
    $expected = pow(2, count(array_values($data))) - 1;
    $result = $this->Tag->int($data);
    $this->assertEqual($expected, $result);

    $data = array();
    $result = $this->Tag->int($data);
    $this->assertEqual(0, $result);
  }

}
