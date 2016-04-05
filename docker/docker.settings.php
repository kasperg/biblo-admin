<?php

$databases['default']['default'] = array(
  'driver' => 'mysql',
  'database' => 'db',
  'username' => 'db',
  'password' => 'bP4pFxUvFcF2b1bV',
  'host' => 'db',
  'prefix' => '',
);

$config_directories = array(
  CONFIG_SYNC_DIRECTORY => '/var/www/web/config/sync',
);

$settings['hash_salt'] = '3yR1W3__wdXH8R_08gA1ZtNeuMOlFfwffJIk2vvXX-Yg6B4O3Q0OMcpVuP3_Y6go3m7Cy7k3pw';
$settings['update_free_access'] = FALSE;
$settings['container_yamls'][] = __DIR__ . '/services.yml';

$settings['install_profile'] = 'minimal';

if (file_exists(__DIR__ . '/settings.local.php')) {
  include __DIR__ . '/settings.local.php';
}
