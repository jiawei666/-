<?php
return array(
    'app_init'          => array(
        'Onemla\\Behaviors\\OnemlaAppInitBehavior'
    ),
    'app_begin'          => array(
        'Onemla\\Behaviors\\OnemlaLangBehavior','Onemla\\Behaviors\\OnemlaKeyWorldsBehavior'
    ),
    'module_check'       => array(
        'Onemla\\Behaviors\\OnemlaModuleBehavior'
    ),
);