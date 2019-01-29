<?php
	$menu = array (
  0 => 
  array (
    'name' => 'Магазины',
    'icon' => 'star',
    'link' => 'index.php',
    'roles' => 'super_admin, admin, manager',
    'visible' => 1,
  ),
  1 => 
  array (
    'name' => 'Администраторы магазинов',
    'icon' => 'star',
    'link' => 'shops_admins.php',
    'roles' => 'super_admin, admin, manager',
    'visible' => 1,
  ),
  2 => 
  array (
    'name' => 'МФО',
    'icon' => 'star',
    'link' => 'mfo.php',
    'roles' => 'super_admin, admin, manager',
    'visible' => 1,
  ),
  3 => 
  array (
    'name' => 'Сотрудничество МФО и магазинов',
    'icon' => 'star',
    'link' => 'mfo_shop_cooperation.php',
    'roles' => 'super_admin, admin, manager',
    'visible' => 1,
  ),
  4 => 
  array (
    'name' => 'Клиенты',
    'icon' => 'star',
    'link' => 'clients.php',
    'roles' => 'super_admin, admin, manager',
    'visible' => 1,
  ),
  5 => 
  array (
    'name' => 'Заявки на кредит',
    'icon' => 'star',
    'link' => 'requests.php',
    'roles' => 'super_admin, admin, manager',
    'visible' => 1,
  ),
  6 => 
  array (
    'name' => 'Партнёры',
    'icon' => 'star',
    'link' => 'partners.php',
    'roles' => 'super_admin, admin, manager',
    'visible' => 1,
  ),
  7 => 
  array (
    'name' => 'Плагины',
    'icon' => 'star',
    'link' => 'integration_plugins.php',
    'roles' => 'super_admin, admin, manager',
    'visible' => 1,
  ),
  8 => 
  array (
    'name' => 'Администраторы Bliss',
    'icon' => 'star',
    'link' => 'admins.php',
    'roles' => 'super_admin',
    'visible' => 1,
  ),
);
	$project_name = 'Bliss'; 

	$project_wireframe = '0'; 

	$mysql_user_table = 'admins'; 

	$mysql_user_login = 'email'; 

	$mysql_user_pass = 'password_hash'; 

	$mysql_user_role = 'role'; 

	$auth_bg = ''; 

	$auth_bg_block = '/assets/img/admin-page-background.png'; 

	$logo = '/assets/img/bliss-logotype.png'; 

	$login_validation = 'none'; 

	$auth_page_caption = 'Контрольная панель'; 

	$project_tint = '#c6273a'; 
