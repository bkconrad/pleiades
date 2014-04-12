<div class="notification">
  <div class="notification-created">
    <?php echo $notification['created']; ?>
  </div>
  <div class="notification-message">
    <?php echo $notification['message']; ?>
  </div>
  <div class="notification-links">
    <?php echo $this->Html->link('view', array('action' => 'delete', $notification['id'])); ?>
  </div>
</div>

