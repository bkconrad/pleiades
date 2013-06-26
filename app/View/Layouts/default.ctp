<?php
/**
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.View.Layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

?>
<!DOCTYPE html>
<html lang="en" xml:lang="en">
<head>
	<?php echo $this->Html->charset(); ?>
	<title>
		Pleiades - <?php echo $title_for_layout; ?>
	</title>
	<?php
		echo $this->Html->meta('icon');

		echo $this->Html->css('pleiades');

		echo $this->Html->meta('icon');

		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');

    echo $this->Html->script('jquery');
    echo $this->Html->script('pleiades');
	?>
</head>
<body>
	<div id="container">
      <div id="header_wrapper">
		<div id="header">
      <?php echo $this->Html->link($this->Html->image('pleiades.png', array('alt' => 'Pleiades - Bitfighter level database')), '/', array('escape' => false)) ?>
		</div>
      </div>
		<div id="content">
      <div id="menu"><?php echo $this->element('menu') ?></div>

			<?php echo $this->Session->flash(); ?>

			<?php echo $this->fetch('content'); ?>
		</div>
		<div id="footer">
		</div>
	</div>
    <div id="base_footer">
    </div>
	<?php echo $this->element('sql_dump'); ?>
	<?php echo $this->Js->writeBuffer(); ?>
</body>
</html>
