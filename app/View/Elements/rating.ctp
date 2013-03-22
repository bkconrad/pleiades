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
<?php echo $rating ?>
  <span class='upvote'>
  <?php
    $link_opts = array('action' => 'rate', $id, $up_value);
    $link_attr = array();
    if($up_value == 0) {
      $link_attr['class'] = 'active';
    }
    echo $this->Html->link('Vote Up', $link_opts, $link_attr)
  ?>
  </span>
  <span class='downvote'>
  <?php
    $link_opts = array('action' => 'rate', $id, $down_value);
    $link_attr = array();
    if($down_value == 0) {
      $link_attr['class'] = 'active';
    }
    echo $this->Html->link('Vote Down', $link_opts, $link_attr)
  ?>
  </span>
</span>
