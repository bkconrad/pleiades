<?php

echo $this->element('paging');

foreach ($levels as $k => $level) {
  echo $this->element('level', array(
    'level' => $level
  ));
}

echo $this->element('paging');
