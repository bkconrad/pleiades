<div class="rating">
<?php
$up_value = 1;
$down_value = -1;
if(!empty($current_rating)) { 
  if($current_rating['Rating']['value'] > 0) {
    $up_value = 0;
  } else if($current_rating['Rating']['value'] < 0) {
    $down_value = 0;
  }
}
?>
<span class='rating'>
  <span class='total'>
    <?php echo $rating ?>
  </span>
  <?php if($logged_in && !$is_owner): ?>
  <span class='upvote'>
  <?php
    $link_opts = array('action' => 'rate', $id, $up_value);
    $link_attr = array('escape' => false);
    $link_attr['title'] = 'Vote&nbsp;Up';
    if($up_value == 0) {
      $link_attr['class'] = 'active';
      $link_attr['title'] = 'Remove&nbsp;Vote';
    }
    echo $this->Html->link("&nbsp;", $link_opts, $link_attr)
  ?>
  </span>
  <span class='downvote'>
  <?php
    $link_opts = array('action' => 'rate', $id, $down_value);
    $link_attr = array('escape' => false);
    $link_attr['title'] = 'Vote&nbsp;Down';
    if($down_value == 0) {
      $link_attr['class'] = 'active';
      $link_attr['title'] = 'Remove&nbsp;Vote';
    }
    echo $this->Html->link("&nbsp;", $link_opts, $link_attr)
  ?>
  </span>
  <?php endif; ?>
</span>
</div>
