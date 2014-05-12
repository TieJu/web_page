<section class="well well-lg">
<?php foreach ( $forum as $thread ): ?>
<?php if ( $thread['ForumThread']['article'] ): ?>
<article class="well well-lg">
<header>
<?php if ( $thread['ForumThread']['sticky'] ): ?>
<span class="glyphicon glyphicon-pushpin" title="<?php echo __("Sticky") ?>"></span>
<?php endif?>
<h1><?php echo $thread['ForumThread']['name'] ?></h1>
</header>
<p><?php echo shorten_text($this->Parsedown->text($thread['ForumPost'][0]['post'])) ?></p>
<footer>
<nav><?php echo $this->Html->link(__('Read Article'), array('controller' => 'forum_threads', 'action' => 'article', $thread['ForumThread']['id']), array('class' => 'btn btn-primary')) ?></nav>
<?php echo __('by %s on ', $thread['Author']['username']) ?><time datetime="<?php echo $thread['ForumThread']['created'] ?>"><?php echo CakeTime::niceShort($thread['ForumThread']['created']) ?></time>
</footer>
</article>
<?php endif; ?>
<?php endforeach; ?>
<footer>
<?php echo $this->Paginator->pagination(array('ul' => 'pagination')); ?>
</footer>
</section>