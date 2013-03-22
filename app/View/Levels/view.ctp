<h1><?php echo $level['Level']['name'] ?> by <?php echo $level['User']['username'] ?></h1>

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
<pre>
<?php echo $level['Level']['content'] ?>
</pre>
