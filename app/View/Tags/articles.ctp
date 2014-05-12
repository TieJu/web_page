<section class="well well-lg">
<table class="table table-hover">
<caption>
<h1><?php echo __('All articles tagged with %s', $data['Tag']['name']) ?></h1>
</caption>
<thead>
<tr>
<th><?php echo __('Post Name') ?></th>
</tr>
</thead>
<tbody>
<?php foreach ( $data['ForumPost'] as $post ): ?>
<tr>
<td><?php echo $this->Html->link($post['name'], array('controller' => 'forum_threads', 'action' => 'article', $post['thread_id'])) ?></td>
</tr>
<?php endforeach; ?>
</tbody>
<tfoot>
<tr>
<td><?php echo $this->Html->link(__('All Tags'), array('action' => 'index'), array('class' => 'btn btn-primary')) ?> </td>
</tr>
</tfoot>
</table>
</section>