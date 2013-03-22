<h1><?php echo $level['Level']['name'] ?> by <?php echo $level['User']['username'] ?></h1>
<span class='rating'>
<?php echo $level['Level']['rating'] ?>
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
