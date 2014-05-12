<?php

echo $this->Form->create('ServerConfig', $DEFAULT_FORM_OPTIONS);
echo $this->Form->inputs(array(
    'legend' => __('Server Configuration'),
    INDEX_LINK => array('label' => __('Index link')),
    SERVER_EMAIL_RESET_EMAIL => array('label' => __('Server email reset address'), 'type' => 'email'),
    SERVER_PASSWORD_GEN_LENGTH => array('label' => __('Generated password length'), 'type' => 'number')
));
echo $this->Form->end(array('label' => __('Save'), 'class' => 'btn btn-primary'));