<?php
echo "<h2> {$data['User']['username']}'s Levels</h2>";
?>
<table>

<?php
echo "<tr><th>Name<th>Rating</tr>";
foreach ($data['Level'] as $level) {
	$link = $this->Html->link($level['name'], "/levels/view/{$level['id']}");
	echo "<tr><td>$link<td>{$level['rating']}</tr>";
}
?>

</table>
