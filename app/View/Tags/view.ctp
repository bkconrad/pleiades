<div class="tags view">
<h2><?php  echo __('Tag'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($tag['Tag']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Name'); ?></dt>
		<dd>
			<?php echo h($tag['Tag']['name']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Tag'), array('action' => 'edit', $tag['Tag']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Tag'), array('action' => 'delete', $tag['Tag']['id']), null, __('Are you sure you want to delete # %s?', $tag['Tag']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Tags'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Tag'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Levels'), array('controller' => 'levels', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Level'), array('controller' => 'levels', 'action' => 'add')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php echo __('Related Levels'); ?></h3>
	<?php if (!empty($tag['Level'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('User Id'); ?></th>
		<th><?php echo __('Name'); ?></th>
		<th><?php echo __('Description'); ?></th>
		<th><?php echo __('Content'); ?></th>
		<th><?php echo __('Levelgen'); ?></th>
		<th><?php echo __('Levelgen Filename'); ?></th>
		<th><?php echo __('Rating'); ?></th>
		<th><?php echo __('Tags'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($tag['Level'] as $level): ?>
		<tr>
			<td><?php echo $level['id']; ?></td>
			<td><?php echo $level['user_id']; ?></td>
			<td><?php echo $level['name']; ?></td>
			<td><?php echo $level['description']; ?></td>
			<td><?php echo $level['content']; ?></td>
			<td><?php echo $level['levelgen']; ?></td>
			<td><?php echo $level['levelgen_filename']; ?></td>
			<td><?php echo $level['rating']; ?></td>
			<td><?php echo $level['tags']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'levels', 'action' => 'view', $level['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'levels', 'action' => 'edit', $level['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'levels', 'action' => 'delete', $level['id']), null, __('Are you sure you want to delete # %s?', $level['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Level'), array('controller' => 'levels', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>
