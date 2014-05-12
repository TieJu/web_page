<section class="well well-lg">
<table class="table table-hover">
<caption><h1><?php echo __('Forums'); ?></h1>
</caption>
<thead>
<tr>
<th><?php echo __('Name'); ?></th>
<th><?php echo __('Parent'); ?></th>
<th></th>
</tr>
</thead>
<tbody>
<?php foreach ( $data as $forum ): ?>
<tr>
<td><?php echo $this->Html->link($forum['Forum']['name'], array('action' => 'edit', $forum['Forum']['id'])); ?></td>
<td>
<?php
if ( isset($forum['Parent']['name']) ) {
  echo $this->Html->link($forum['Parent']['name'], array('action' => 'edit', $forum['Parent']['id']));
}
?>
</td>
<td><?php echo $this->Html->link('<span class="glyphicon glyphicon-remove"></span>', array('action' => 'delete', $forum['Forum']['id']), array('escape' => false),__('Are you sure to delete the forum %s ?', $forum['Forum']['name']) )?></td>
</tr>
<?php endforeach; ?>
</tbody>
<tfoot>
<tr>
<td colspan="3">
<? echo $this->Html->link(__('Add Forum'), array('action' => 'edit'), array('class' => 'btn btn-primary')); ?>
</td>
</tr>
</tfoot>
</table>
</section>