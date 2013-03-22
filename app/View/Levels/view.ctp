<h1><?php echo $level['Level']['name'] ?> by <?php echo $level['User']['username'] ?></h1>
<span class='rating'>
<?php echo $level['Level']['rating'] ?>
  <span class='upvote'>
  <?php
    echo $this->Html->link('Vote Up', array('action' => 'rate', $level['Level']['id'], '1'))
  ?>
  </span>
  <span class='downvote'>
  <?php
    echo $this->Html->link('Vote Down', array('action' => 'rate', $level['Level']['id'], '-1'))
  ?>
  </span>
</span>
<?php
if($is_owner) {
  echo $this->Html->link('Edit', array(
    'action' => 'edit',
    $level['Level']['id']
  ));
}
?>
<pre>
<?php echo $level['Level']['content'] ?>
</pre>
