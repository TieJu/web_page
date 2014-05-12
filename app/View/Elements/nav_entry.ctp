<?php if ( empty($data['Child'])) :?>
  <?php if ( is_null($data['Parent']['id'])): ?>
    <li>
      <?php echo $this->Html->link($data['Section']['name'], array('controller' => $data['Section']['controller'], 'action' => $data['Section']['action'], $data['Section']['params'])); ?>
    </li>
  <?php  endif; ?>
<?php else: ?>
  <li class="dropdown">
    <a href="#" id="<?php echo $data['Section']['name'] ?>_dowp_down" role="button" class="dropdown-toggle" data-toggle="dropdown"><?php echo $data['Section']['name'] ?> <b class="caret"></b></a>
    <ul class="dropdown-menu" role="menu" aria-labelledby="<?php echo $data['Section']['name'] ?>_dowp_down">
      <?php foreach ( $data['Child'] as $child ) :?>
        <li class="presentation"><?php echo $this->Html->link($child['name'], array('controller' => $child['controller'], 'action' => $child['action'], $child['params']), array('role' => 'menuitem', 'tabindex' => '-1') )?></li>
      <?php endforeach; ?>
    </ul>
  </li>
<?php endif; ?>