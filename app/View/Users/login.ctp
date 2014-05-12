<?php
echo $this->Form->create('User', $DEFAULT_FORM_OPTIONS);
echo $this->Form->inputs(array(
    'legend' => __('Login'),
    'username' => array('label' => __('Uername or Email')),
    'password',
    'remember_me' => array('type' => 'checkbox', 'class' => false)
));
echo $this->Form->end(array('label' => __('Login'), 'class' => 'btn btn-primary'));