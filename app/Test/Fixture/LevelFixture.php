<?php
class LevelFixture extends CakeTestFixture {
  public $import = 'Level';
  public $records = array(
    array(
      'name' => 'level',
      'content' => 'Script test.levelgen',
      'levelgen' => 'some levelgen code',
      'description' => 'descriptive',
      'user_id' => 1,
      'id' => 1
    ),
    array(
      'name' => 'level',
      'content' => 'empty (more or less)',
      'levelgen' => '',
      'description' => 'descriptive',
      'user_id' => 1,
      'id' => 2
    ),
    array(
      'name' => "bob's level",
      'content' => 'content',
      'levelgen' => '',
      'description' => 'uploaded by bob',
      'user_id' => 2,
      'id' => 3
    ),
  );
}
