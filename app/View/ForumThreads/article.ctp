<?php /*$this->Html->script('ckeditor/ckeditor', array('inline' => false))*/ ?>
<section class="well well-lg">
<article>
<header>
<h1><?php echo $thread['ForumThread']['name'] ?></h1>
</header>
<?php echo $this->Parsedown->text($thread['ForumPost'][0]['post']) ?>
<footer>
<?php foreach ( $thread['ForumPost'][0]['Tag'] as $tag): ?>
<?php $tag_set[] = $this->Html->link($tag['name'], array('controller' => 'tags', 'action' => 'articles', $tag['id'])) ?>
<?php endforeach; ?>
<?php if ( isset($tag_set) ):?>
<nav>
<header><?php echo __('Tags') ?></header>
<ul style="list-style:none;padding:0">
<li style="display:inline;padding-right:0.25em">
<?php echo implode(', </li><li style="display:inline;padding-right:0.25em">', $tag_set) ?>
</li>
</ul>
</nav>
<?php endif;?>
<nav><?php echo $this->Html->link(__('Back to %s', $thread['Forum']['name']), $back_url, array('class' => 'btn btn-default')) ?></nav>
<?php echo __('by %s on ', $thread['Author']['username']) ?><time datetime="<?php echo $thread['ForumThread']['created'] ?>"><?php echo CakeTime::niceShort($thread['ForumThread']['created']) ?></time>
</footer>
</article>
<?php $limit = count($thread['ForumPost']); ?>
<?php for ( $i = 1; $i < $limit; ++$i ) : ?>
<?php $comment = $thread['ForumPost'][$i]; ?>
<section class="well">
<header>
<h4><?php echo $comment['Author']['username'] ?></h4>
</header>
<?php echo $this->Parsedown->text($comment['post']) ?>
<footer>
<?php echo CakeTime::niceShort($comment['created']) ?>
</footer>
</section>
<?php endfor; ?>
<?php if ( !empty($user) && !$thread['ForumThread']['locked'] ): ?>
<section>
<?php
echo $this->Form->create('ForumPost');
echo $this->Form->input('post', array('type' => 'textarea', 'rows' => 6, 'label' => '', 'class' => 'form-control', 'after' => $this->Html->link('Markdown syntax', 'http://www.markdown.de/')));
echo $this->Form->end(array('label' => __('Post Comment'), 'class' => 'btn btn-primary'));
?>
</section>
<?php endif; ?>
</section>