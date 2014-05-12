<section class="well well-lg">
<table class="table table-hover">
<caption>
<h1><?php echo _('Project Version Status Types') ?></h1>
</caption>
<thead>
<tr>
<th><?php echo __('Name') ?></th>
<th><?php echo __('Property String') ?></th>
<th></th>
</tr>
</thead>
<tbody>
<?php foreach ( $data as $status ): ?>
<tr>
<td><?php echo $this->Html->link($status['ProjectVersionStatus']['name'], array('action' => 'edit', $status['ProjectVersionStatus']['id'])) ?></td>
<td><?php echo $this->Html->link($status['ProjectVersionStatus']['property_string'], array('action' => 'edit', $status['ProjectVersionStatus']['id'])) ?></td>
<td><?php echo $this->Html->link('<span class="glyphicon glyphicon-remove"></span>', array('action' => 'delete', $status['ProjectVersionStatus']['id']), array('escape' => false), __('Are you sure to delete the project version status %s ?', $status['ProjectVersionStatus']['name'])) ?>
</tr>
<?php endforeach; ?>
</tbody>
<tfoot>
<tr>
<td colspan="3">
<nav>
<ul style="padding:0">
<li style="display:inline"><?php echo $this->Html->link(__('Create Project Version Status'), array('action' => 'edit', -1), array('class' => 'btn btn-primary')) ?></li>
<li style="display:inline"><?php echo $this->Html->link(__('Back'), $back_url, array('class' => 'btn btn-default'))?></li>
</ul>
</nav>
</td>
</tr>
</tfoot>
</table>
</section>