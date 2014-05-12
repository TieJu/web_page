<?php
echo $this->Form->create('User', $DEFAULT_FORM_OPTIONS);
echo $this->Form->inputs(array(
                        'legend' => __('User Profile'),
                        'username',
                        'email',
                        'reset_pw' => array('label' => __('Reset Password'), 'type' => 'checkbox', 'class' => false),
                        'UserGroup' => array('multiple' => 'checkbox', 'options' => $groups, 'label' => __('User Group Membership'))));
echo $this->Form->submit(__('Update'), array('class' => 'btn btn-primary'));
echo $this->Html->link(__('Back'), $back_url, array('class' => 'btn btn-default'));
echo $this->Form->end();