<?php
/**
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.View.Layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

$cakeDescription = __d('cake_dev', 'CakePHP: the rapid development php framework');
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<?php echo $this->Html->charset(); ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>
		<?php echo $title_for_layout; ?>
	</title>
	<?php
		echo $this->Html->meta('icon');
		echo $this->fetch('meta');

		echo $this->Html->css('bootstrap.min');
		//echo $this->Html->css('tj');
		echo $this->fetch('css');

		//echo $this->Html->css('cake.generic');
	?>
</head>
<body style="padding-top: 50px;">
<header class="navbar navbar-inverse navbar-fixed-top bs-docs-nav" role="banner">
  <div class="container">
    <div class="navbar-header">
      <button class="navbar-toggle" type="button" data-toggle="collapse" data-target=".bs-navbar-collapse">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
    </div>
    <nav class="collapse navbar-collapse bs-navbar-collapse" role="navigation">
      <ul class="nav navbar-nav">
        <?php foreach ( $sections as $section ) : ?>
          <?php if ( $section ['Section']['right'] ) continue; ?>
          <?php echo $this->element('nav_entry', array('data' => $section)) ?>
        <?php endforeach; ?>
       </ul>
      <ul class="nav navbar-nav navbar-right">
      <?php foreach ( $sections as $section ) : ?>
      	<li>
        	<?php if ( !$section['Section']['right'] ) continue; ?>
        	<?php echo $this->element('nav_entry', array('data' => $section)) ?>
        </li>
	<?php endforeach; ?>
	<?php /* admin and login/logout stuff is hardcoded, all other links are done via sections */?>
<?php if ( isset($permissions[PERMISION_ADMIN] ) ) : ?>
        <li class="dropdown">
            <a href="#" id="admin_drop" role="button" class="dropdown-toggle" data-toggle="dropdown"><?php echo __('Administration'); ?> <b class="caret"></b></a>
            <ul class="dropdown-menu" role="menu" aria-labelledby="admin_drop">
<?php if ( isset($permissions[PERMISION_ADMIN_USER]) ) : ?>
                <li role="presentation"><?php echo $this->Html->link(__('User'), array('controller' => 'Users', 'action' => 'index'), array('role' => 'menuitem', 'tabindex' => '-1')); ?></li>
<?php endif; ?>
<?php if ( isset($permissions[PERMISION_ADMIN_GROUP]) ) : ?>
                <li role="presentation"><?php echo $this->Html->link(__('Group'), array('controller' => 'UserGroups', 'action' => 'index'), array('role' => 'menuitem', 'tabindex' => '-1')); ?></li>
<?php endif; ?>
<?php if ( isset($permissions[PERMISION_ADMIN_FORUM]) ) : ?>
                <li role="presentation"><?php echo $this->Html->link(__('Forum'), array('controller' => 'Forums', 'action' => 'admin_list'), array('role' => 'menuitem', 'tabindex' => '-1')); ?></li>
<?php endif; ?>
<?php if ( isset($permissions[PERMISION_ADMIN_SECTION]) ) : ?>
                <li role="presentation"><?php echo $this->Html->link(__('Section'), array('controller' => 'Sections' ), array('role' => 'menuitem', 'tabindex' => '-1')); ?></li>
<?php endif; ?>
<?php if ( isset($permissions[PERMISION_ADMIN_PROJECT]) ) : ?>
                <li role="presentation"><?php echo $this->Html->link(__('Project'), array('controller' => 'Projects', 'action' => 'index'), array('role' => 'menuitem', 'tabindex' => '-1')); ?></li>
<?php endif; ?>
<?php if ( isset($permissions[PERMISION_ADMIN_SERVER]) ) : ?>
                <li role="presentation"><?php echo $this->Html->link(__('Server'), array('controller' => 'Settings', 'action' => 'index'), array('role' => 'menuitem', 'tabindex' => '-1')); ?></li>
<?php endif; ?>
            </ul>
        </li>
<?php endif; ?>
        <li class="dropdown">
<?php
if ( empty($user) ) {
    $login_label = __('Login');
} else {
    $login_label = $user['User']['username'];
}
?>
          <a href="#" id="login_drop" role="button" class="dropdown-toggle" data-toggle="dropdown"><?php echo $login_label; ?> <b class="caret"></b></a>
          <ul class="dropdown-menu" role="menu" aria-labelledby="login_drop">
<?php if ( empty($user) ) : ?>
            <li role="presentation"><?php echo $this->Html->link(__('Register'), array('controller' => 'Users', 'action' => 'register'), array('role' => 'menuitem', 'tabindex' => '-1')); ?></li>
            <li role="presentation"><?php echo $this->Html->link(__('Login'), array('controller' => 'Users', 'action' => 'login'), array('role' => 'menuitem', 'tabindex' => '-1')); ?></li>
<?php else : ?>
            <li role="presentation"><?php echo $this->Html->link(__('Edit Profile'), array('controller' => 'Users', 'action' => 'edit'), array('role' => 'menuitem', 'tabindex' => '-1')); ?></li>
            <li role="presentation"><?php echo $this->Html->link(__('Logout'), array('controller' => 'Users', 'action' => 'logout'), array('role' => 'menuitem', 'tabindex' => '-1')); ?></li>
<?php endif; ?>
          </ul>
        </li>
      </ul>
    </nav>
  </div>
</header>
		<div class="container">

			<?php echo $this->Session->flash(); ?>
			<?php echo $this->Session->flash('auth'); ?>

			<?php echo $this->fetch('content'); ?>
		</div>
		<footer>
			<?php echo $this->Html->link(
					$this->Html->image('cake.power.gif', array('alt' => $cakeDescription, 'style' => 'border:0', 'width' => '98', 'height' => '13')),
					'http://www.cakephp.org/',
					array(/*'target' => '_blank',*/ 'escape' => false)
				);
			?>
		</footer>
	<?php /*echo $this->element('sql_dump');*/ ?>

</body>
</html>
<?php /*
<script type="text/javascript">
function downloadCSSAtOnLoad() {
	document.getElementsByTagName("head")[0].innerHTML += '<?php echo $this->Html->css('bootstrap.min'); ?>'
                                                     +  '<?php echo $this->Html->css('tj') ?>'
                                                     +  '<?php echo $this->fetch('css') ?>';
}
function downloadAtLoad() {
	downloadCSSAtOnLoad();
}
 if (window.addEventListener)
 window.addEventListener("load", downloadAtLoad, false);
 else if (window.attachEvent)
 window.attachEvent("onload", downloadAtLoad);
 else window.onload = downloadAtLoad;
</script>*/
?>
<script defer type="text/javascript" src="https://code.jquery.com/jquery.min.js"></script>
<?php echo str_replace('<script', '<script defer',$this->Html->script('bootstrap.min'));?>
<?php echo str_replace('<script', '<script defer',$this->Html->script('jquery.autosize.min'));?>
<?php echo str_replace('<script', '<script defer',$this->fetch('script'));?>
<script type="text/javascript">
$('textarea').autosize()
</script>
