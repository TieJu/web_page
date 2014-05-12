<section class="well well-lg">
<table class="table table-hover">
<thead>
<caption>
<h1><?php echo __('Page Sections') ?></h1>
</caption>
<tr>
<th><?php echo __('Name') ?></th>
<th><?php echo __('Controller') ?></th>
<th><?php echo __('Action') ?></th>
<th><?php echo __('Params') ?></th>
<th><?php echo __('Relative Ordering') ?></th>
<th><?php echo __('Parent') ?></th>
<th><?php echo __('Right Side') ?></th>
<th><?php echo __('Link for Testing') ?></th>
<th></th>
</tr>
</thead>
<tbody>
<?php foreach ( $sections as $section ): ?>
<tr>
<td><?php echo $this->Html->link($section['Section']['name'], array('action' => 'edit', $section['Section']['id'])) ?></td>
<td><?php echo $this->Html->link($section['Section']['controller'], array('action' => 'edit', $section['Section']['id'])) ?></td>
<td><?php echo $this->Html->link($section['Section']['action'], array('action' => 'edit', $section['Section']['id'])) ?></td>
<td><?php echo $this->Html->link($section['Section']['params'], array('action' => 'edit', $section['Section']['id'])) ?></td>
<td><?php echo $this->Html->link($section['Section']['ordering'], array('action' => 'edit', $section['Section']['id'])) ?></td>
<td>
<?php if ( !is_null($section['Parent']['name']) ): ?>
<?php echo $this->Html->link($section['Parent']['name'], array('action' => 'edit', $section['Section']['id'])) ?>
<?php endif; ?>
</td>
<td><?php echo $this->Html->link(( $section['Section']['right'] ? 'yes' : 'no' ), array('action' => 'edit', $section['Section']['id'])) ?></td>
<td><?php echo $this->Html->link($section['Section']['name'], array('controller' => $section['Section']['controller'], 'action' => $section['Section']['action'], $section['Section']['params'])) ?></td>
<td><?php echo $this->Html->link('<span class="glyphicon glyphicon-remove"></span>', array('action' => 'delete', $section['Section']['id']), array('escape' => false), __('Are you sure to delete the page section %s ?', $section['Section']['name'])) ?></td>
<td></td>
</tr>
<?php endforeach; ?>
</tbody>
<tfoot>
<tr>
<td colspan="9">
<?php echo $this->Html->link(__('Create Page Section'), array('action' => 'edit'), array('class' => 'btn btn-primary')); ?>
</td>
</tr>
</tfoot>
</table>
</section>