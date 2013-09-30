<?php
$this->Js->get('.submission-link')
  ->event('click', 'submissionClickHandler(this)')
;

$byline =
    $this->Html->tag('span', $level['Level']['name'], array('class' => 'name', 'escape' => true))
  . '&nbsp;by&nbsp;'
  . $this->Html->tag('span', $level['User']['username'], array('class' => 'author', 'escape' => true))
;

echo $this->Html->tag('h2', $byline, array('class' => 'byline'));

echo $this->element('rating', array(
  'id' => $level['Level']['id'],
  'rating' => $level['Level']['rating'],
  'current_user_rating' => $current_user_rating,
  'is_owner' => $is_owner,
  'logged_in' => $logged_in
));

$downloads = 'Downloaded ' . $level['Level']['downloads'] . ' times&nbsp;';
if ($level['Level']['downloads'] == 1) {
  $downloads = 'Downloaded once';
} else if ($level['Level']['downloads'] == 0) {
  $downloads = 'No downloads yet';
}
echo $this->Html->tag('div', $downloads, array('class' => 'download-count'));

echo '<span class="level_info">';
echo '<span class="team_count">' . $level['Level']['team_count'] . '&nbsp;Team&nbsp;</span>';
echo '<span class="game_type">' . $level['Level']['game_type'] . '</span>';
echo '</span>';
?>

<div class="actions">
<?php
echo $this->Html->link('Download', array(
  'action' => 'download',
  $level['Level']['id']
));
if($is_owner || $isAdmin) {
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
echo '<span class="screenshot-wrapper">';
echo '<img class="screenshot" src="' . $this->webroot . 'img/' . $level['Level']['screenshot_filename'] . '">';
echo '</span>';
?>

<?php
echo $this->Html->tag('div',
  'To download this map while playing Bitfighter, type:' .
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
<?php if(!empty($level['Level']['description'])): ?>
  <h1>Description</h1>
  <?php echo $this->Html->tag('div', $level['Level']['description'], array('escape' => true, 'class' => 'level_description')) ?>
<?php endif; ?>

<?php
if (!empty($level['Tag'])) {
  echo $this->Html->tag('h1', 'Tags');
  echo '<ul class="tags">';
  foreach($level['Tag'] as $k => $tag) {
    $link = $this->Html->link($tag['name'], '/levels/search/tags[0]:' . $tag['id'] , array('escape' => true));
    echo $this->Html->tag('li', $link, array('class' => 'tag'));
  }
  echo '</ul>';
}

echo '<h1>Code</h1>';

echo '<div class="submission-wrapper">';
echo '<h2>';
echo $this->Html->link($level['Level']['level_filename'], array('action' => 'raw', $level['Level']['id'], 'content', true), array('class' => 'submission-link'));
echo '</h2>';
echo $this->Html->tag('pre', '',  array('escape' => true, 'id' => 'level-code', 'class' => 'submission levelcode'));
echo '</div>';

if (!empty($level['Level']['levelgen'])) {
  echo '<div class="submission-wrapper">';
  echo '<h2>';
  echo $this->Html->link($level['Level']['levelgen_filename'], array('action' => 'raw', $level['Level']['id'], 'levelgen'), array('class' => 'submission-link'));
  echo '</h2>';
  echo $this->Html->tag('pre', '', array('escape' => true, 'class' => 'submission levelgen', 'data-language' => 'lua'));
  echo '</div>';
}

if (!empty($comments)) {
    echo '<h1>Comments</h1>';
    foreach ($comments as $k => $comment) {
        echo $this->element('comment', array(
            'comment' => $comment
        ));
    }
}

if ($logged_in) {
    echo '<h1>Add Comment</h1>';
    echo $this->Form->create('Comment', array('url' => '/comments/add'));
    echo $this->Form->input('text');
    echo $this->Form->hidden('level_id', array('default' => $level['Level']['id']));
    echo $this->Form->end('Submit Comment');
}
?>
