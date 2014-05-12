<section class="well well-lg">
<head>
<h1><?php  echo __('Tags') ?></h1>
</head>
<?php
foreach ( $data as $tag ) {
  $c = count($tag['ForumPost']);
  if ( !isset($min) || $c < $min ) {
    $min = $c;
  }
  if ( !isset($max) || $c > $max ) {
    $max = $c;
  }
}
$min_scale = 1;
$max_scale = 3;
$tagset = '';
shuffle($data);
foreach ( $data as $tag ) {
  $c = ( ( count($tag['ForumPost']) - $min ) / ($max - $min) ) * ($max_scale - $min_scale) + $min_scale;

  $tagset[] = $this->Html->link($tag['Tag']['name'], array('action' => 'articles', $tag['Tag']['id']),array('style' => 'font-size:'.$c.'em'));
}

echo implode(' ', $tagset);

?>
</section>