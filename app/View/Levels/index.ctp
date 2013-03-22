<pre>
<?php foreach ($levels as $level): ?>
<?php // print_r($level); ?>
<?php endforeach; ?>
</pre>

<table>
<th>Name<td>Uploader<td>Rating</th>
<?php foreach ($levels as $level) {
$options = array('escape' => true);
$rowdata = array(
  $this->Html->link($level['Level']['name'], array('action' => 'view', $level['Level']['id'])),
  $level["User"]['username'],
  $level['Level']['rating']
);
echo $this->Html->tableCells(array($rowdata), $options, $options);
}
?>
</table>
