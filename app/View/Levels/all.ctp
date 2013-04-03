<?php
foreach ($levels as $k => $level) {
  echo $this->element('level', array(
    'level' => $level
  ));
}
