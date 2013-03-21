<pre>
<?php foreach ($levels as $level): ?>
<?php // print_r($level); ?>
<?php endforeach; ?>
</pre>

<table>
<?php foreach ($levels as $level): ?>
<th>Name<td>Uploader<td>Rating</th>
<tr><td><?php echo $level['Level']['name'] ?><td><?php echo $level["User"]['username'] ?><td><?php echo $level['Level']['rating'] ?></tr>
<?php endforeach; ?>
</table>
