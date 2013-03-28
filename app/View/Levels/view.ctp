<?php echo $this->Html->tag('h2', $level['Level']['name']  . " by " . $level['User']['username'], array('escape' => true)); ?>

<?php
echo $this->element('rating', array(
  'id' => $level['Level']['id'],
  'rating' => $level['Level']['rating'],
  'current_rating' => $current_rating
));
?>

<div class="actions">
<?php
echo $this->Html->link('Download', array(
  'action' => 'download',
  $level['Level']['id']
));
if($is_owner) {
  echo '&nbsp;';
  echo $this->Html->link('Edit', array(
    'action' => 'edit',
    $level['Level']['id']
  ));
}
?>
</div>
<?php echo $this->Html->tag('div', $level['Level']['description'], array('escape' => true, 'class' => 'level_description')) ?>
<?php echo $this->Html->tag('h1', $level_file_name, array('escape' => true)) ?>
<?php echo $this->Html->tag('pre', $level['Level']['content'], array('escape' => true, 'class' => 'submission')) ?>
<?php 
if (!empty($level['Level']['levelgen'])) {
  echo $this->Html->tag('h1', $level['Level']['levelgen_filename'], array('escape' => true));
  echo $this->Html->tag('pre', $level['Level']['levelgen'], array('escape' => true, 'class' => 'submission'));
}

if (!empty($level['Tag'])) {
  echo '<ul>';
  foreach($level['Tag'] as $k => $tag) {
    echo $this->Html->tag('li', $tag['name'], array('escape' => true));
  }
  echo '</ul>';
}
?>
