<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------
| AUTO-LOADER
| -------------------------------------------------------------------
| This file specifies which systems should be loaded by default.
|
| In order to keep the framework as light-weight as possible only the
| absolute minimal resources are loaded by default. For example,
| the database is not connected to automatically since no assumption
| is made regarding whether you intend to use it.  This file lets
| you globally define which systems you would like loaded with every
| request.
|
| -------------------------------------------------------------------
| Instructions
| -------------------------------------------------------------------
|
| These are the things you can load automatically:
|
| 1. Packages
| 2. Libraries
| 3. Drivers
| 4. Helper files
| 5. Custom config files
| 6. Language files
| 7. Models
|
*/

/*
| -------------------------------------------------------------------
|  Auto-load Packages
| -------------------------------------------------------------------
| Prototype:
|
|  $autoload['packages'] = array(APPPATH.'third_party', '/usr/local/shared');
|
*/
$autoload['packages'] = array();

/*
| -------------------------------------------------------------------
|  Auto-load Libraries
| -------------------------------------------------------------------
| These are the classes located in system/libraries/ or your
| application/libraries/ directory, with the addition of the
| 'database' library, which is somewhat of a special case.
|
| Prototype:
|
|	$autoload['libraries'] = array('database', 'email', 'session');
|
| You can also supply an alternative library name to be assigned
| in the controller:
|
|	$autoload['libraries'] = array('user_agent' => 'ua');
*/
$autoload['libraries'] = array('vadmin', 'session', 'form_validation', 'database', 'pagination', 'email', 'system_vars');
/*
| 
| vAdministrator Load Modules Libraries
|
*/
$autoload['libraries'][] = 'vadmin_mods/Tb';
$autoload['libraries'][] = 'vadmin_mods/Tb';
$autoload['libraries'][] = 'vadmin_mods/Md5';
$autoload['libraries'][] = 'vadmin_mods/Rf';
$autoload['libraries'][] = 'vadmin_mods/Html';
$autoload['libraries'][] = 'vadmin_mods/Sb';
$autoload['libraries'][] = 'vadmin_mods/Rd';
$autoload['libraries'][] = 'vadmin_mods/Dt';
$autoload['libraries'][] = 'vadmin_mods/Date_time';
$autoload['libraries'][] = 'vadmin_mods/Dollar';
$autoload['libraries'][] = 'vadmin_mods/Lb';
//$autoload['libraries'][] = 'vadmin_mods/M';
$autoload['libraries'][] = 'vadmin_mods/Fl';
$autoload['libraries'][] = 'vadmin_mods/Url';
$autoload['libraries'][] = 'vadmin_mods/Ta';
$autoload['libraries'][] = 'vadmin_mods/Boolean';
$autoload['libraries'][] = 'vadmin_mods/Gd';
$autoload['libraries'][] = 'vadmin_mods/Attachment';
$autoload['libraries'][] = 'vadmin_mods/Regex';
/*
| -------------------------------------------------------------------
|  Auto-load Drivers
| -------------------------------------------------------------------
| These classes are located in system/libraries/ or in your
| application/libraries/ directory, but are also placed inside their
| own subdirectory and they extend the CI_Driver_Library class. They
| offer multiple interchangeable driver options.
|
| Prototype:
|
|	$autoload['drivers'] = array('cache');
*/
$autoload['drivers'] = array();

/*
| -------------------------------------------------------------------
|  Auto-load Helper Files
| -------------------------------------------------------------------
| Prototype:
|
|	$autoload['helper'] = array('url', 'file');
*/
$autoload['helper'] = array('url', 'inflector', 'socketio', 'auth');

/*
| -------------------------------------------------------------------
|  Auto-load Config files
| -------------------------------------------------------------------
| Prototype:
|
|	$autoload['config'] = array('config1', 'config2');
|
| NOTE: This item is intended for use ONLY if you have created custom
| config files.  Otherwise, leave it blank.
|
*/
$autoload['config'] = array();

/*
| -------------------------------------------------------------------
|  Auto-load Language files
| -------------------------------------------------------------------
| Prototype:
|
|	$autoload['language'] = array('lang1', 'lang2');
|
| NOTE: Do not include the "_lang" part of your file.  For example
| "codeigniter_lang.php" would be referenced as array('codeigniter');
|
*/
$autoload['language'] = array();

/*
| -------------------------------------------------------------------
|  Auto-load Models
| -------------------------------------------------------------------
| Prototype:
|
|	$autoload['model'] = array('first_model', 'second_model');
|
| You can also supply an alternative model name to be assigned
| in the controller:
|
|	$autoload['model'] = array('first_model' => 'first');
*/
$autoload['model'] = array('Base_model', 'Inbound_message_model', 'Outbound_message_model', 'Psychic_model');
