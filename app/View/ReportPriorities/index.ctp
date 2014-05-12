<section class="well well-lg">
<table class="table table-hover">
<caption>
<h1><?php echo __('Report Priorities') ?></h1>
</caption>
<thead>
<tr>
<th><?php echo __('Name') ?></th>
<th></th>
</tr>
</thead>
<tbody>
<?php foreach ( $data as $priority ): ?>
<tr>
<td><?php echo $this->Html->link($priority['ReportPriority']['name'], array('action' => 'edit', $priority['ReportPriority']['id'])) ?></td>
<td><?php echo $this->Html->link('<span class="glyphicon glyphicon-remove"></span>', array('action' => 'delete', $priority['ReportPriority']['id']), array('escape' => false), __('Are you sure to delete the report priority %s ?', $priority['ReportPriority']['name'])) ?></td>
</tr>
<?php endforeach; ?>
</tbody>
<tfoot>
<tr>
<td colspan="2">
<nav>
<ul style="padding:0">
<li style="display:inline"><?php echo $this->Html->link(__('Create Report Priority'), array('action' => 'edit'), array('class' => 'btn btn-primary')) ?></li>
<li style="display:inline"><?php echo $this->Html->link(__('Back'), $back_url, array('class' => 'btn btn-default')) ?>
</ul>
</nav>
</td>
</tr>
</tfoot>
</table>
</section>