<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	https://codeigniter.com/user_guide/general/hooks.html
|
*/

/*pre_system – Hook point. The hook will be called very early during system execution.
class – The name of the class wish to invoke.
function – The method name wish to call.
filename – The file name containing the class/function.
filepath – The name of the directory containing hook script.*/

$hook['pre_system'][] = array(
    'class'    => 'maintenance_hook',
    'function' => 'offline_check',
    'filename' => 'maintenance_hook.php',
    'filepath' => 'hooks'
);