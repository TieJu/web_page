<?php
echo $this->Form->create('User', $DEFAULT_FORM_OPTIONS);
echo $this->Form->inputs(array('legend' => __('Register'),
                               'username' => array('type' => 'text'),
                               'email',
                               'password',
                               'captcha_code' => array( 'type' => 'text'
                                                      , 'size' => 10
                                                      , 'before' => '<div>'
                                                      , 'between' => '</div><div>' . $this->Html->image($captcha_image_url, array('id' => 'captcha', 'alt' => 'CAPTCHA Image' )) . '</div><div>'
                                                      , 'after' => '</div><div>' . $this->Html->link('<span class="glyphicon glyphicon-refresh"></span>', '#', array('escape' => false, 'onclick' => 'document.getElementById(\'captcha\').src = \'' . $this->webroot . 'users/securimage/\' + Math.random(); return false')) . '</div>')
));
echo $this->Form->end(array('label' => __('Register'), 'class' => 'btn btn-primary'));