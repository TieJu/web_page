<?php
echo $this->Form->create('UserGroup', $DEFAULT_FORM_OPTIONS);
echo $this->Form->inputs(array('legend' => __('Usergroup')
                              ,'name'));
echo $this->Form->input('UserGroupPermission', array('multiple' => 'checkbox', 'type' => 'select', 'options' => $permissionSet, 'value' => $permissionSelect) );
echo $this->Form->submit(__('Save'), array('class' => 'btn btn-primary'));
echo $this->Html->link(__('Back'), $back_url, array('class' => 'btn btn-default'));
echo $this->Form->end();