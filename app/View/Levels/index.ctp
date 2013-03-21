<pre>
<?php foreach ($levels as $level): ?>
<?php // print_r($level); ?>
<?php endforeach; ?>
</pre>

<table>
<th>Name<td>Uploader<td>Rating</th>
<?php foreach ($levels as $level): ?>
<tr><td><?php echo $level['Level']['name'] ?><td><?php echo $level["User"]['username'] ?><td><?php echo $level['Level']['rating'] ?></tr>
<?php endforeach; ?>
</table>
