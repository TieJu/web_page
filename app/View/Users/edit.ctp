<?php
echo $this->Form->create('User', $DEFAULT_FORM_OPTIONS);
echo $this->Form->inputs(array(
                        'legend' => __('User Profile'),
                        'username',
                        'email',
                        'password',
                        'id' => array('type' => 'hidden')));
echo $this->Form->end(array('label' => __('Update'), 'class' => 'btn btn-primary'));