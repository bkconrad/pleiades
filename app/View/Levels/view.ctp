<?php echo $this->Html->tag('h2', $level['Level']['name']  . " by " . $level['User']['username'], array('escape' => true)); ?>

<?php
$downloads = 'Downloaded ' . $level['Level']['downloads'] . ' times&nbsp;';
if ($level['Level']['downloads'] == 1) {
  $downloads = 'Downloaded once';
} else if ($level['Level']['downloads'] == 0) {
  $downloads = 'No downloads yet';
}
echo $this->Html->tag('div', $downloads);
?>

<?php
echo $this->element('rating', array(
  'id' => $level['Level']['id'],
  'rating' => $level['Level']['rating'],
  'current_rating' => $current_rating,
  'is_owner' => $is_owner,
  'logged_in' => $logged_in
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
  echo '&nbsp;';
  echo $this->Html->link('Delete', array(
    'action' => 'delete',
    $level['Level']['id']
  ), array('confirm' => 'Are you sure you wish to delete this level?'));
}
?>
</div>
<?php
if(isset($level['Level']['screenshot_filename']) && !empty($level['Level']['screenshot_filename'])) {
  echo '<img class="screenshot" src="' . $this->webroot . 'img/' . $level['Level']['screenshot_filename'] . '">';
}
?>

<?php
echo $this->Html->tag('div',
  'To download this map, run:' .
  $this->Html->tag('div',
    '/dlmap&nbsp;' . preg_replace('/\.level$/', '', $level['Level']['level_filename']),
    array(
      'class' => 'download-instructions'
    )
  ),
  array(
    'class' => 'download-instructions-container'
  )
);
?>
<?php echo $this->Html->tag('div', $level['Level']['description'], array('escape' => true, 'class' => 'level_description')) ?>
<?php echo $this->Html->tag('h1', $level['Level']['level_filename'], array('escape' => true)) ?>
<?php echo $this->Html->tag('pre', $level['Level']['content'], array('escape' => true, 'class' => 'submission')) ?>
<?php 
if (!empty($level['Level']['levelgen'])) {
  echo $this->Html->tag('h1', $level['Level']['levelgen_filename'], array('escape' => true));
  echo $this->Html->tag('pre', $level['Level']['levelgen'], array('escape' => true, 'class' => 'submission'));
}

if (!empty($level['Tag'])) {
  echo $this->Html->tag('h1', 'Tags');
  echo '<ul class="tags">';
  foreach($level['Tag'] as $k => $tag) {
    $link = $this->Html->link($tag['name'], '/levels/search/tags[0]:' . $tag['id'] , array('escape' => true));
    echo $this->Html->tag('li', $link, array('class' => 'tag'));
  }
  echo '</ul>';
}
?>
