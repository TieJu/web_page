<table class="table table-hover">
<caption>
<h1><?php echo __('Report Satuses') ?></h1>
</caption>
<thead>
<tr>
<th><?php echo __('Name') ?></th>
<th></th>
</tr>
</thead>
<tbod>
<?php foreach( $data as $status ): ?>
<tr>
<td><?php echo $this->Html->link($status['ReportStatus']['name'], array('action' => 'edit', $status['ReportStatus']['id'])) ?></td>
<td><?php echo $this->Html->link('<span class="glyphicon glyphicon-remove"></span>', array('action' => 'delete', $status['ReportStatus']['id']), array('escape' => false), __('Are you sure to delete the report status %s ?', $status['ReportStatus']['name'])) ?></td>
</tr>
<?php endforeach; ?>
</tbod>
<tfoot>
<tr>
<td colspan="2">
<nav>
<ul style="padding:0">
<li style="display:inline"><?php echo $this->Html->link(__('Create Report Status'), array('action' => 'edit'), array('class' => 'btn btn-primary')) ?></li>
<li style="display:inline"><?php echo $this->Html->link(__('Back'), $back_url, array('class' => 'btn btn-default')) ?>
</ul>
</nav>
</tr>
</tfoot>
</table>