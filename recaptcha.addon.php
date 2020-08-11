<?php
if (!defined('RX_VERSION'))
{
	exit;
}
if ($called_position !== 'before_module_init')
{
	return;
}
if (!$addon_info->site_key || !$addon_info->secret_key || Rhymix\Framework\Session::isAdmin())
{
	return;
}
$is_session = !empty($_SESSION['recaptcha_authenticated']);
if ($is_session && ($addon_info->first_time_only === 'Y' || $addon_info->first_time_only === 'login_only' && Context::get('is_logged')))
{
	return;
}

require_once __DIR__ . '/class.php';
Addons\recaptcha::init($addon_info);

if (starts_with('proc', Context::get('act'), false))
{
	getController('module')->addTriggerFunction('moduleObject.proc', 'before', 'Addons\recaptcha::verify');
}
else
{
	getController('module')->addTriggerFunction('display', 'before', 'Addons\recaptcha::setHTML');
}
