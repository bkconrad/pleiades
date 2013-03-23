<?php echo $this->Html->tag('h1', $level['Level']['name']  . " by " . $level['User']['username'], array('escape' => true)); ?>

<?php
echo $this->element('rating', array(
  'id' => $level['Level']['id'],
  'rating' => $level['Level']['rating'],
  'current_rating' => $current_rating
));
?>

<?php
if($is_owner) {
  echo $this->Html->link('Edit', array(
    'action' => 'edit',
    $level['Level']['id']
  ));
}
?>
<?php echo $this->Html->tag('pre', $level['Level']['content'], array('escape' => true)) ?>
<?php 
if (!empty($level['Level']['levelgen'])) {
  echo $this->Html->tag('pre', $level['Level']['levelgen'], array('escape' => true));
}
?>
