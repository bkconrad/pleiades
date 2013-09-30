<h1>Mass Upload</h1>
<p>
You can upload .zip archives of levels and levelgens, and we'll do our best to upload them for you.
</p>
<?php
echo $this->Form->create(array('type' => 'file'));
echo $this->Form->file('zipFile');
echo $this->Form->end('submit');

if(!empty($uploads)) {
	$results = array(
		'success' => array(),
		'failure' => array(),
		'warning' => array()
	);

	$output = '';
	$output .= $this->Html->tableHeaders(array('Filename', 'Level Name', 'Messages'));

	foreach($uploads as $i => $upload) {
		if(empty($upload['errors'])) {
			if(empty($upload['warnings'])) {
				// success
				$link = $this->Html->link($upload['name'], array('action' => 'view', $upload['id']));
				array_push($results['success'], array($upload['filename'], $link, ''));

			} else {
				// warning
				$row = array(
					$upload['filename'],
					$upload['name'],
					array(
						$this->Html->nestedList($upload['warnings']),
						array('class' => 'messages')
						)
					);

				array_push($results['warning'], $row);
			}
		} else {
			// failure
			$row = array(
				$upload['filename'],
				$upload['name'],
				array(
					$this->Html->nestedList($upload['errors']),
					array('class' => 'messages')
					)
				);
			array_push($results['failure'], $row);
		}
	}

	$output .= $this->Html->tableCells($results['failure'], array('class' => 'failure'), array('class' => 'failure'), false, true);
	$output .= $this->Html->tableCells($results['warning'], array('class' => 'warning'), array('class' => 'warning'), false, true);
	$output .= $this->Html->tableCells($results['success'], array('class' => 'success'), array('class' => 'success'), false, true);

	echo $this->Html->tag('table', $output, array('class' => 'mass-upload-info'));
}

?>