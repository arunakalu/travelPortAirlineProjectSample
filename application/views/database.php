<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
  | -------------------------------------------------------------------
  | DATABASE CONNECTIVITY SETTINGS
  | -------------------------------------------------------------------
  | This file will contain the settings needed to access your database.
  |
  | For complete instructions please consult the 'Database Connection'
  | page of the User Guide.
  |
  | -------------------------------------------------------------------
  | EXPLANATION OF VARIABLES
  | -------------------------------------------------------------------
  |
  |	['hostname'] The hostname of your database server.
  |	['username'] The username used to connect to the database
  |	['password'] The password used to connect to the database
  |	['database'] The name of the database you want to connect to
  |	['dbdriver'] The database type. ie: mysql.  Currently supported:
  mysql, mysqli, postgre, odbc, mssql, sqlite, oci8
  |	['dbprefix'] You can add an optional prefix, which will be added
  |				 to the table name when using the  Active Record class
  |	['pconnect'] TRUE/FALSE - Whether to use a persistent connection
  |	['db_debug'] TRUE/FALSE - Whether database errors should be displayed.
  |	['cache_on'] TRUE/FALSE - Enables/disables query caching
  |	['cachedir'] The path to the folder where cache files should be stored
  |	['char_set'] The character set used in communicating with the database
  |	['dbcollat'] The character collation used in communicating with the database
  |				 NOTE: For MySQL and MySQLi databases, this setting is only used
  | 				 as a backup if your server is running PHP < 5.2.3 or MySQL < 5.0.7
  |				 (and in table creation queries made with DB Forge).
  | 				 There is an incompatibility in PHP with mysql_real_escape_string() which
  | 				 can make your site vulnerable to SQL injection if you are using a
  | 				 multi-byte character set and are running versions lower than these.
  | 				 Sites using Latin-1 or UTF-8 database character set and collation are unaffected.
  |	['swap_pre'] A default table prefix that should be swapped with the dbprefix
  |	['autoinit'] Whether or not to automatically initialize the database.
  |	['stricton'] TRUE/FALSE - forces 'Strict Mode' connections
  |							- good for ensuring strict SQL while developing
  |
  | The $active_group variable lets you choose which connection group to
  | make active.  By default there is only one group (the 'default' group).
  |
  | The $active_record variables lets you determine whether or not to load
  | the active record class
 */

$active_group = 'fmf_user_profile';
$active_record = TRUE;

$db['default']['hostname'] = 'localhost';
$db['default']['username'] = 'root';
$db['default']['password'] = '';
$db['default']['database'] = 'useraccount_v1';
$db['default']['dbdriver'] = 'mysql';
$db['default']['dbprefix'] = '';
$db['default']['pconnect'] = TRUE;
$db['default']['db_debug'] = TRUE;
$db['default']['cache_on'] = FALSE;
$db['default']['cachedir'] = '';
$db['default']['char_set'] = 'utf8';
$db['default']['dbcollat'] = 'utf8_general_ci';
$db['default']['swap_pre'] = '';
$db['default']['autoinit'] = TRUE;
$db['default']['stricton'] = FALSE;


$db['cms_sel']['hostname'] = 'mysql:host=cms.findmyfare.com';
$db['cms_sel']['username'] = 'fmfcms_sel_v1';
$db['cms_sel']['password'] = 'alMo&r0Ct*1Q2y&ghrlNbVnEV^4';
$db['cms_sel']['database'] = 'cms_fmf';
$db['cms_sel']['dbdriver'] = 'pdo';
$db['cms_sel']['dbprefix'] = '';
$db['cms_sel']['pconnect'] = TRUE;
$db['cms_sel']['db_debug'] = TRUE;
$db['cms_sel']['cache_on'] = FALSE;
$db['cms_sel']['cachedir'] = '';
$db['cms_sel']['char_set'] = 'utf8';
$db['cms_sel']['dbcollat'] = 'utf8_general_ci';
$db['cms_sel']['swap_pre'] = '';
$db['cms_sel']['autoinit'] = TRUE;
$db['cms_sel']['stricton'] = FALSE;

$db['sqlc_sel']['hostname'] = 'mysql:host=sqlc.findmyfare.com';
$db['sqlc_sel']['username'] = 'fmf_lhms_dbuser';
$db['sqlc_sel']['password'] = 'htovDm5tAm71TxP32WPQlAPvg8v';
$db['sqlc_sel']['database'] = 'fmf_lhms_v1';
$db['sqlc_sel']['dbdriver'] = 'pdo';
$db['sqlc_sel']['dbprefix'] = '';
$db['sqlc_sel']['pconnect'] = TRUE;
$db['sqlc_sel']['db_debug'] = TRUE;
$db['sqlc_sel']['cache_on'] = FALSE;
$db['sqlc_sel']['cachedir'] = '';
$db['sqlc_sel']['char_set'] = 'utf8';
$db['sqlc_sel']['dbcollat'] = 'utf8_general_ci';
$db['sqlc_sel']['swap_pre'] = '';
$db['sqlc_sel']['autoinit'] = TRUE;
$db['sqlc_sel']['stricton'] = FALSE;

$db['cms_ins']['hostname'] = 'mysql:host=cms.findmyfare.com';
$db['cms_ins']['username'] = 'fmfsite2acc';
$db['cms_ins']['password'] = 'f5Sf1g6xh@$fSn@z@DTMdtx0m5Z';
$db['cms_ins']['database'] = 'cms_fmf';
$db['cms_ins']['dbdriver'] = 'pdo';
$db['cms_ins']['dbprefix'] = '';
$db['cms_ins']['pconnect'] = TRUE;
$db['cms_ins']['db_debug'] = TRUE;
$db['cms_ins']['cache_on'] = FALSE;
$db['cms_ins']['cachedir'] = '';
$db['cms_ins']['char_set'] = 'utf8';
$db['cms_ins']['dbcollat'] = 'utf8_general_ci';
$db['cms_ins']['swap_pre'] = '';
$db['cms_ins']['autoinit'] = TRUE;
$db['cms_ins']['stricton'] = FALSE;


$db['fmf_user_profile']['hostname'] = 'mysql:host=sqlc.findmyfare.com';
$db['fmf_user_profile']['username'] = 'fmfmain_user2';
$db['fmf_user_profile']['password'] = '456@fmf';
$db['fmf_user_profile']['database'] = 'fmfmain_v1db';
$db['fmf_user_profile']['dbdriver'] = 'pdo';
$db['fmf_user_profile']['dbprefix'] = '';
$db['fmf_user_profile']['pconnect'] = TRUE;
$db['fmf_user_profile']['db_debug'] = TRUE;
$db['fmf_user_profile']['cache_on'] = FALSE;
$db['fmf_user_profile']['cachedir'] = '';
$db['fmf_user_profile']['char_set'] = 'utf8';
$db['fmf_user_profile']['dbcollat'] = 'utf8_general_ci';
$db['fmf_user_profile']['swap_pre'] = '';
$db['fmf_user_profile']['autoinit'] = TRUE;
$db['fmf_user_profile']['stricton'] = FALSE;


$db['fmf_int_hotels']['hostname'] = 'mysql:host=sqlc.findmyfare.com';
$db['fmf_int_hotels']['username'] = 'fmfeandev2';
$db['fmf_int_hotels']['password'] = 'YaCVXa6RD6utWZaT';
$db['fmf_int_hotels']['database'] = 'fmf_ean_v1';
$db['fmf_int_hotels']['dbdriver'] = 'pdo';
$db['fmf_int_hotels']['dbprefix'] = '';
$db['fmf_int_hotels']['pconnect'] = TRUE;
$db['fmf_int_hotels']['db_debug'] = TRUE;
$db['fmf_int_hotels']['cache_on'] = FALSE;
$db['fmf_int_hotels']['cachedir'] = '';
$db['fmf_int_hotels']['char_set'] = 'utf8';
$db['fmf_int_hotels']['dbcollat'] = 'utf8_general_ci';
$db['fmf_int_hotels']['swap_pre'] = '';
$db['fmf_int_hotels']['autoinit'] = TRUE;
$db['fmf_int_hotels']['stricton'] = FALSE;

$db['dev_database']['hostname'] = 'mysql:host=mysql.kbtpl.com';
$db['dev_database']['username'] = 'fmfintdev1';
$db['dev_database']['password'] = 'su6V4sJuxf2sCSAv';
$db['dev_database']['database'] = 'fmf_intlp_v1';
$db['dev_database']['dbdriver'] = 'pdo';
$db['dev_database']['dbprefix'] = '';
$db['dev_database']['pconnect'] = TRUE;
$db['dev_database']['db_debug'] = TRUE;
$db['dev_database']['cache_on'] = FALSE;
$db['dev_database']['cachedir'] = '';
$db['dev_database']['char_set'] = 'utf8';
$db['dev_database']['dbcollat'] = 'utf8_general_ci';
$db['dev_database']['swap_pre'] = '';
$db['dev_database']['autoinit'] = TRUE;
$db['dev_database']['stricton'] = FALSE;



$db['cms_new']['hostname'] = 'mysql:host=sqlc.findmyfare.com';
$db['cms_new']['username'] = 'fmfmain_user2';
$db['cms_new']['password'] = '456@fmf';
$db['cms_new']['database'] = 'fmfmain_v1db';
$db['cms_new']['dbdriver'] = 'pdo';
$db['cms_new']['dbprefix'] = '';
$db['cms_new']['pconnect'] = TRUE;
$db['cms_new']['db_debug'] = TRUE;
$db['cms_new']['cache_on'] = FALSE;
$db['cms_new']['cachedir'] = '';
$db['cms_new']['char_set'] = 'utf8';
$db['cms_new']['dbcollat'] = 'utf8_general_ci';
$db['cms_new']['swap_pre'] = '';
$db['cms_new']['autoinit'] = TRUE;
$db['cms_new']['stricton'] = FALSE;

$db['fmf_refund']['hostname'] = 'mysql:host=mysql.kbtpl.com';
$db['fmf_refund']['username'] = 'kbtdbusertest';
$db['fmf_refund']['password'] = 'kfP3ryzg';
$db['fmf_refund']['database'] = 'kbtdbtest_v1';
$db['fmf_refund']['dbdriver'] = 'pdo';
$db['fmf_refund']['dbprefix'] = '';
$db['fmf_refund']['pconnect'] = TRUE;
$db['fmf_refund']['db_debug'] = TRUE;
$db['fmf_refund']['cache_on'] = FALSE;
$db['fmf_refund']['cachedir'] = '';
$db['fmf_refund']['char_set'] = 'utf8';
$db['fmf_refund']['dbcollat'] = 'utf8_general_ci';
$db['fmf_refund']['swap_pre'] = '';
$db['fmf_refund']['autoinit'] = TRUE;
$db['fmf_refund']['stricton'] = FALSE;


$db['tour_package']['hostname'] = 'mysql:host=sqlc.findmyfare.com';
$db['tour_package']['username'] = 'holdevsel';
$db['tour_package']['password'] = 'r32UMRtXg52zWZ45';
$db['tour_package']['database'] = 'fmf_holiday_v1';
$db['tour_package']['dbdriver'] = 'pdo';
$db['tour_package']['dbprefix'] = '';
$db['tour_package']['pconnect'] = TRUE;
$db['tour_package']['db_debug'] = TRUE;
$db['tour_package']['cache_on'] = FALSE;
$db['tour_package']['cachedir'] = '';
$db['tour_package']['char_set'] = 'utf8';
$db['tour_package']['dbcollat'] = 'utf8_general_ci';
$db['tour_package']['swap_pre'] = '';
$db['tour_package']['autoinit'] = TRUE;
$db['tour_package']['stricton'] = FALSE;



// use :  $this->load->database("cms" , TRUE);



/* End of file database.php */
/* Location: ./application/config/database.php */