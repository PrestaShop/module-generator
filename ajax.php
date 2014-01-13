<?php

// include_once dirname(__FILE__).'/modulegenerator.php';

$renderPath = 'renders/';
$sourcePath = 'sources/';

// Get form data
$params = $_POST['data'];

// Parse data
parse_str($params, $output);
array_walk($output, 'cleanUp');

echo '<pre>';
print_r($output);
echo '</pre>';

// Get html form
$form = trim($_POST['form']);

$moduleName = strip_tags(strtolower($output['module_name']));
$moduleNameCamel = ucfirst($moduleName);

$moduleDir = $renderPath.$moduleName.'/';
$sqlDir = $renderPath.$moduleName.'/sql/';
$templateDir = $moduleDir.'views/templates/';
$hookDir = $templateDir.'hook/';
$hookTPL = 'views/templates/hook/';

$moduleController = (int)$output['back_controller'];
$moduleTabController = strip_tags($output['tabs_controller_back']);

$moduleSQL = (int)$output['need_sql_install'];
$moduleSQLInstall = $output['sql_install'];
$moduleSQLUninstall = $output['sql_uninstall'];

$moduleTab = strip_tags(strtolower($output['module_tab']));
$moduleVersion = (int)$output['module_version'].'.'.(int)$output['module_version_func'].'.'.(int)$output['module_version_rev'];
$moduleAuthor = strip_tags($output['module_author']);
$moduleInstance = (int)$output['need_instance'];

$moduleDisplay = strip_tags($output['module_display_name']);
$moduleDesc = strip_tags($output['module_description']);

$moduleUninstall = (int)$output['confirm_uninstall'];
$moduleUninstallText = strip_tags($output['module_uninstall']);

$uninstall = '';
if ($moduleUninstall === 1)
	$uninstall = "\n\n\t\t".'$'."this->confirmUninstall = ".'$'."this->l('$moduleUninstallText');";

$hookFront = strip_tags($output['module_hook_front']);
$hookBack = strip_tags($output['module_hook_back']);

$hookInstall = $hookFuncFront = $hookFuncBack = '';
if ($hookFront !== 'null') {
	$explodeFront = explode(',', $hookFront);
	foreach($explodeFront as $val) {
		$hookInstall .= "\n\t\t\t|| ".'$'."this->registerHook('$val') === false";
	}

	$hookFuncFront .= '/*
	** FRONT HOOK
	*/
	';

	$haveLeft = false;
	foreach($explodeFront as $val) {
	$isLeft = $isRight = $isRight = $isHeader = $isContent = '';
	if ($val === 'displayLeftColumn') {
		$haveLeft = true;
		$isLeft = standardTPL($moduleName, $hookTPL, $val);
	}
	elseif ($val === 'displayRightColumn') {
		if ($haveLeft === true) {
			$isRight = "return ".'$'."this->hookDisplayLeftColumn(".'$'."params);";
		} else { 
			$isRight = standardTPL($moduleName, $hookTPL, $val);
		}
		$haveLeft = false;
	}
	elseif ($val === 'displayHeader') {
		$isHeader = "// Load CSS
		".'$'."css = array(
			".'$'."this->css_path.".'$'."this->name.'.css'
		);
		".'$'."this->context->controller->addCSS(".'$'."css, 'all');

		// Load JS
		".'$'."js = array(
			".'$'."this->js_path.".'$'."this->name.'.js'
		);
		".'$'."this->context->controller->addJS(".'$'."js);

		// Clean memory !
		unset(".'$'."js, ".'$'."css);";
	}
	elseif (strpos($val, 'action') !== false) {
		$isContent = '';
	}
	else {
		$isContent = standardTPL($moduleName, $hookTPL, $val);
	}

	$hookFuncFront .= "public function hook$val(".'$'."params)
	{
		// Check if the module is active
		if (!".'$'."this->active)
			return;

		$isHeader$isLeft$isRight$isContent
	}\n\n\t";
	}

	if ($hookBack === 'null')
		$hookInstall .= ')';
}

if ($hookBack !== 'null') {
	$explodeBack = explode(',', $hookBack);
	foreach($explodeBack as $val) {
		$hookInstall .= "\n\t\t\t|| ".'$'."this->registerHook('$val') === false";
	}

	$hookFuncBack .= '/*
	** BACK HOOK
	*/
	';
	$isLeft = $isRight = '';
	foreach($explodeBack as $val) {
	$hookFuncBack .= "public function hook$val(".'$'."params)
	{
		// Check if the module is active
		if (!".'$'."this->active)
			return;
	}\n\n\t";
	}

	// unset($explodeBack, $val);
	$hookInstall .= ')';
}

if (trim($hookFuncBack) === '') 
	$hookFuncFront = trim($hookFuncFront);

$hookFuncBack = trim($hookFuncBack);

if ($hookBack === 'null' && $hookFront === 'null')
	$hookInstall = ')';

// Extends Modules
$extends = '';
if (strpos($moduleTab, 'migration') !== false)
	$extends = 'Import';
elseif (strpos($moduleTab, 'payments') !== false)
	$extends = 'Payment';
elseif (strpos($moduleTab, 'billing') !== false)
	$extends = 'TaxManager';
elseif (strpos($moduleTab, 'shipping') !== false)
	$extends = 'Carrier';
elseif (strpos($moduleTab, 'quick') !== false)
	$extends = 'StockManager';

$tabsFuncInstall = $tabsFuncUninstall = $tabsInstall = $tabsUninstall ='';
if ($moduleController === 1) {
	$tabsFuncInstall = "\n\t/**
	 * Install Tab
	 * @return boolean
	 */
	private function installTab()
	{
		".'$'."tab = new Tab();
		".'$'."tab->active = 1;
		".'$'."tab->class_name = 'Admin$moduleNameCamel';
		".'$'."tab->name = array();
		foreach (Language::getLanguages(true) as ".'$'."lang)
			".'$'."tab->name[".'$'."lang['id_lang']] = 'test';
		unset(".'$'."lang);
		".'$'."tab->id_parent = (int)Tab::getIdFromClassName('$moduleTabController');
		".'$'."tab->module = ".'$'."this->name;
		return ".'$'."tab->add();
	}\n";

	$tabsFuncUninstall ="\n\t/**
	 * Uninstall Tab
	 * @return boolean
	 */
	private function uninstallTab()
	{
		".'$'."id_tab = (int)Tab::getIdFromClassName('Admin$moduleNameCamel');
		if (".'$'."id_tab)
		{
			".'$'."tab = new Tab(".'$'."id_tab);
			return ".'$'."tab->delete();
		}
		else
			return false;
	}\n";

	$tabsInstall = "\n\t\t\t|| ".'$'."this->installTab() === false";
	$tabsUninstall = "\n\t\t\t|| ".'$'."this->uninstallTab() === false";
}


$sqlConstant = $sqlPath = $sqlFuncInstall = $sqlFuncUninstall = $sqlInstall = $sqlUninstall = '';
if ($moduleSQL === 1)
{
	$sqlConstant = "\t/* SQL files */
	const INSTALL_SQL_FILE = 'install.sql';

	const UNINSTALL_SQL_FILE = 'uninstall.sql';\n";

	$sqlPath = "\n\t\t".'$'."this->sql_path = dirname(__FILE__).'/sql/';";

	$sqlFuncInstall = "\n\t/**
	 * Install SQL
	 * @return boolean
	 */
	private function installSQL()
	{
		// Create database tables from install.sql
		if (!file_exists(".'$'."this->sql_path.self::INSTALL_SQL_FILE))
			return false;

		if (!".'$'."sql = Tools::file_get_contents(".'$'."this->sql_path.self::INSTALL_SQL_FILE))
			return false;

		".'$'."replace = array(
			'PREFIX' => _DB_PREFIX_,
			'ENGINE_DEFAULT' => _MYSQL_ENGINE_
		);
		".'$'."sql = strtr(".'$'."sql, ".'$'."replace);
		".'$'."sql = preg_split(\"/;\s*[\\r\\n]+/\", ".'$'."sql);

		foreach (".'$'."sql as ".'$'."q)
			if (".'$'."q && count(".'$'."q) && !Db::getInstance()->Execute(trim(".'$'."q)))
				return false;
		// Clean memory
		unset(".'$'."sql, ".'$'."q, ".'$'."replace);

		return true;
	}\n";

	$sqlFuncUninstall = "\n\t/**
	 * Uninstall SQL
	 * @return boolean
	 */
	private function uninstallSQL()
	{
		// Create database tables from install.sql
		if (!file_exists(".'$'."this->sql_path.self::UNINSTALL_SQL_FILE))
			return false;

		if (!".'$'."sql = Tools::file_get_contents(".'$'."this->sql_path.self::UNINSTALL_SQL_FILE))
			return false;

		".'$'."replace = array(
			'PREFIX' => _DB_PREFIX_,
			'ENGINE_DEFAULT' => _MYSQL_ENGINE_
		);
		".'$'."sql = strtr(".'$'."sql, ".'$'."replace);
		".'$'."sql = preg_split(\"/;\s*[\\r\\n]+/\", ".'$'."sql);

		foreach (".'$'."sql as ".'$'."q)
			if (".'$'."q && count(".'$'."q) && !Db::getInstance()->Execute(trim(".'$'."q)))
				return false;
		// Clean memory
		unset(".'$'."sql, ".'$'."q, ".'$'."replace);

		return true;
	}\n";

	$sqlInstall = "\n\t\t\t|| ".'$'."this->installSQL() === false";
	$sqlUninstall = "\n\t\t\t|| ".'$'."this->uninstallSQL() === false";
}

$licenseTPL = "{*
* 2007-".date('Y')." PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-".date('Y')." PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}";

$licenseFile = "/*
* 2007-".date('Y')." PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-".date('Y')." PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/";

$mod = ("<?php
$licenseFile

if (defined('_PS_VERSION_') === false)
	exit;

class $moduleNameCamel extends ".$extends."Module
{
	/**
	 * @var string Admin Module template path 
	 * (eg. '/home/prestashop/modules/modulename/views/templates/admin/')
	 */
	protected ".'$'."admin_tpl_path = null;

	/**
	 * @var string Admin Module template path 
	 * (eg. '/home/prestashop/modules/modulename/views/templates/hook/')
	 */
	protected ".'$'."hooks_tpl_path = null;

	/** @var string Module js path (eg. '/shop/modules/modulename/js/') */
	protected ".'$'."js_path = null;

	/** @var string Module css path (eg. '/shop/modules/modulename/css/') */
	protected ".'$'."css_path = null;

	/** @var protected array cache filled with lang informations */
	protected static ".'$'."lang_cache;

$sqlConstant
	public function __construct()
	{
		".'$'."this->name = '$moduleName';
		".'$'."this->tab = '$moduleTab';
		".'$'."this->version = '$moduleVersion';
		".'$'."this->author = '$moduleAuthor';
		".'$'."this->need_instance = '$moduleInstance';

		".'$'."this->bootstrap = true;
		".'$'."this->secure_key = Tools::encrypt(".'$'."this->name);

		parent::__construct();

		".'$'."this->displayName = ".'$'."this->l('$moduleDisplay');
		".'$'."this->description = ".'$'."this->l('$moduleDesc');$uninstall

		".'$'."this->js_path = ".'$'."this->_path.'js/';
		".'$'."this->css_path = ".'$'."this->_path.'css/';$sqlPath
		".'$'."this->admin_tpl_path = ".'$'."this->local_path.'views/templates/admin/';
		".'$'."this->hooks_tpl_path = ".'$'."this->local_path.'views/templates/hook/';

		if (version_compare(_PS_VERSION_, '1.6', '<'))
			".'$'."this->getLang();
	}

	/**
	 * Get Language
	 * @return array Lang
	 */
	private function getLang()
	{
		if (self::".'$'."lang_cache == null && !is_array(self::".'$'."lang_cache))
		{
			self::".'$'."lang_cache = array();
			if (".'$'."languages = Language::getLanguages())
			{
				foreach (".'$'."languages as ".'$'."row)
				{
					".'$'."exprow = explode(' (', ".'$'."row['name']);
					".'$'."subtitle = (isset(".'$'."exprow[1]) ? trim(Tools::substr(".'$'."exprow[1], 0, -1)) : '');
					self::".'$'."lang_cache[".'$'."row['iso_code']] = array (
						'title' => trim(".'$'."exprow[0]),
						'subtitle' => ".'$'."subtitle
					);
				}
				// Clean memory
				unset(".'$'."row, ".'$'."exprow, ".'$'."result, ".'$'."subtitle, ".'$'."languages);
			}
		}
	}
$sqlFuncInstall$sqlFuncUninstall$tabsFuncInstall$tabsFuncUninstall
	/**
	 * Insert module into datable
	 * @return boolean result
	 */
	public function install()
	{
		if (Shop::isFeatureActive())
			Shop::setContext(Shop::CONTEXT_ALL);

		if (parent::install() === false$sqlInstall$tabsInstall$hookInstall
			return false;
		return true;
	}

	/**
	 * Delete module from datable 
	 * @return boolean result 
	 */
	public function uninstall()
	{
		if (parent::uninstall() === false$sqlUninstall$tabsUninstall)
			return false;
		return true;
	}

	/**
	 * Loads asset resources
	 */
	public function loadAsset()
	{
		".'$'."css_compatibility = ".'$'."js_compatibility = array();

		// Load CSS
		".'$'."css = array(
			".'$'."this->css_path.'bootstrap-select.min.css',
		);
		if (version_compare(_PS_VERSION_, '1.6', '<'))
		{
			".'$'."css_compatibility = array(
				".'$'."this->css_path.'bootstrap.min.css',
				".'$'."this->css_path.'bootstrap-responsive.min.css',
				".'$'."this->css_path.'font-awesome.min.css',
				".'$'."this->css_path.".'$'."this->name.'.css'
			);
			".'$'."css = array_merge(".'$'."css, ".'$'."css_compatibility);
		}
		".'$'."this->context->controller->addCSS(".'$'."css, 'all');

		// Load JS
		".'$'."js = array(
			".'$'."this->js_path.'bootstrap-select.min.js',
			".'$'."this->js_path.'jquery.autosize.min.js',
			".'$'."this->js_path.".'$'."this->name.'.js'
		);
		if (version_compare(_PS_VERSION_, '1.6', '<'))
		{
			".'$'."js_compatibility = array(
				".'$'."this->js_path.'bootstrap.min.js'
			);
			".'$'."js = array_merge(".'$'."js_compatibility, ".'$'."js);
		}
		".'$'."this->context->controller->addJS(".'$'."js);

		// Clean memory
		unset(".'$'."js, ".'$'."css, ".'$'."js_compatibility, ".'$'."css_compatibility);
	}

	/**
	 * Show the configuration module
	 */
	public function getContent()
	{
		// We load asset
		".'$'."this->loadAsset();

		// Clean the code use tpl file for html
		".'$'."tab = '&tab_module='.".'$'."this->tab;
		".'$'."token_mod = '&token='.Tools::getAdminTokenLite('AdminModules');
		".'$'."token_pos = '&token='.Tools::getAdminTokenLite('AdminModulesPositions');
		".'$'."token_trad = '&token='.Tools::getAdminTokenLite('AdminTranslations');
		".'$'."this->context->smarty->assign(array(
			'ps_version' => (bool) version_compare(_PS_VERSION_, '1.6', '>'),
			'lang_select' => self::".'$'."lang_cache,
			'module_name' => ".'$'."this->name,
			'module_active' => (bool)".'$'."this->active,
			'module_trad' => 'index.php?tab=AdminTranslations'.".'$'."token_trad.'&type=modules&lang=',
			'module_hook' => 'index.php?tab=AdminModulesPositions'.".'$'."token_pos.'&show_modules='.".'$'."this->id,
			'module_back' => 'index.php?tab=AdminModules'.".'$'."token_mod.".'$'."tab.'&module_name='.".'$'."this->name,
			'module_form' => 'index.php?tab=AdminModules&configure='.".'$'."this->name.".'$'."token_mod.".'$'."tab.'&module_name='.".'$'."this->name,
		));

		// Clean memory
		unset(".'$'."tab, ".'$'."token_mod, ".'$'."token_pos, ".'$'."token_trad);

		return ".'$'."this->display(__FILE__, 'views/templates/admin/configuration.tpl');
	}

	$hookFuncFront$hookFuncBack
}");

$structure = './'.$moduleDir.'views/';

if (file_exists($moduleDir)) {
	deleteRecursive($moduleDir);
}

if (!mkdir($structure, 0, true)) {
	die('Echec lors de la création des répertoires...');
}

if ($moduleController === 1) {
	mkdir($moduleDir.'controllers');
	if (!empty($moduleTabController)) {
		mkdir($moduleDir.'controllers/admin');
		copyRecursive($sourcePath.'index.php', './'.$moduleDir.'controllers/admin/index.php');

$controller = ("<?php
$licenseFile

class Admin".$moduleNameCamel."Controller extends ModuleAdminController
{

}");
		file_put_contents($moduleDir.'controllers/admin/Admin'.$moduleNameCamel.'Controller.php', $controller);
	}
	copyRecursive($sourcePath.'index.php', './'.$moduleDir.'controllers/index.php');

	// if (!empty($moduleFrontController)) {
	// 	mkdir($moduleDir.'controllers/front');
	// 	copyRecursive($sourcePath.'index.php', './'.$moduleDir.'controllers/front/index.php');
	// }
}

// Create module file
file_put_contents($moduleDir.$moduleName.'.php', $mod);
// Copy file
copyRecursive($sourcePath, $moduleDir);

$conf = array(
	'[license]' => $licenseTPL,
	'[form]' => $form,
	'[module]' => $moduleName,
	'[text]' => $moduleDisplay
);

replaceVar($conf, $templateDir.'admin/configuration.tpl');
replaceVar($conf, $templateDir.'admin/addons.tpl');
replaceVar($conf, $templateDir.'admin/header.tpl');
replaceVar($conf, $templateDir.'admin/translations.tpl');

// Rename css & js
rename($moduleDir.'css/module.css', $moduleDir.'css/'.$moduleName.'.css');
rename($moduleDir.'js/module.js', $moduleDir.'js/'.$moduleName.'.js');

// Write license to JS
$file = $moduleDir.'js/'.$moduleName.'.js';
$current = file_get_contents($file);
$current = $licenseFile."\n\n".$current;
file_put_contents($file, $current);

if ($moduleSQL === 1) {
	$structure = './'.$moduleDir.'sql/';
	if (!mkdir($structure, 0, true)) {
		die('Echec lors de la création des répertoires...');
	}
	copyRecursive($sourcePath.'index.php',$structure.'index.php');
	file_put_contents('./'.$sqlDir.'/install.sql', $moduleSQLInstall);
	file_put_contents('./'.$sqlDir.'/uninstall.sql', $moduleSQLUninstall);
}

// Create tpl file for hook
$haveLeft = false;
foreach($explodeFront as $val) {
	if ($val === 'displayHeader') {}
	elseif (strpos($val, 'action') !== false) {}
	else {
		if ($val === 'displayLeftColumn') {
			$haveLeft = true;
			file_put_contents('./'.$hookDir.$moduleName.$val.'.tpl', $licenseTPL);
		}
		elseif ($val === 'displayRightColumn') {
			if ($haveLeft === false) {
				file_put_contents('./'.$hookDir.$moduleName.$val.'.tpl', $licenseTPL);
			}
			$haveLeft = false;
		}
		else {
			file_put_contents('./'.$hookDir.$moduleName.$val.'.tpl', $licenseTPL);
		}
	}
}
unset($val);

/**
 * Remplace contents
 *
 * @param array $sourceDest Array with the key as the value to replace the value as content
 * @param string $file File in which we will make replacement
 * @return void
 */
function replaceVar(array $sourceDest, $file) {
	$template = strtr(file_get_contents($file), $sourceDest);
	file_put_contents($file, $template);
	unset($template, $sourceDest, $file);
}

/**
 * Copy a file or recursively copy a directories contents
 *
 * @param string $source The path to the source file/directory
 * @param string $dest The path to the destination directory
 * @return void
 */
function copyRecursive($source, $dest)
{
	if (is_dir($source))
	{
		$iterator = new RecursiveIteratorIterator(
			new RecursiveDirectoryIterator($source, RecursiveDirectoryIterator::SKIP_DOTS),
			RecursiveIteratorIterator::SELF_FIRST
		);
		foreach ($iterator as $file)
		{
			if ($file->isDir()) {
				if(!is_dir($dest.DIRECTORY_SEPARATOR.$iterator->getSubPathName()))
					mkdir($dest.DIRECTORY_SEPARATOR.$iterator->getSubPathName());
			}
			else
				copy($file, $dest.DIRECTORY_SEPARATOR.$iterator->getSubPathName());
		}
		unset($iterator, $file);
	}
	else
		copy($source, $dest);
}

/**
* Delete a file/recursively delete a directory
*
* NOTE: Be very careful with the path you pass to this!
*
* @param string $path The path to the file/directory to delete
* @return void
*/
function deleteRecursive($path)
{
	if (is_dir($path))
	{
		$iterator = new RecursiveIteratorIterator(
			new RecursiveDirectoryIterator($path, RecursiveDirectoryIterator::SKIP_DOTS),
			RecursiveIteratorIterator::CHILD_FIRST
		);

		foreach ($iterator as $file)
		{
			if ($file->isDir())
				rmdir($file->getPathname());
			else
				unlink($file->getPathname());
		}
		unset($iterator, $file);
		rmdir($path);
	}
	else
		unlink($path);
}

function cleanUp(&$item, $key)
{
	$output[$key] = trim($item);
}

function standardTPL ($moduleName, $hookTPL, $val)
{
	return "if (".'$'."this->isCached('$moduleName$val.tpl', ".'$'."this->getCacheId()) === false)
		{
			".'$'."this->smarty->assign(array(

			));
		}

		// Clean memory
		unset(".'$'."params);

		return ".'$'."this->display(__FILE__, '$hookTPL$moduleName$val.tpl', ".'$'."this->getCacheId());";
}

die();
?>