<?php
/*
* 2007-2014 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Open Software License (OSL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/osl-3.0.php
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
*  @copyright  2007-2014 PrestaShop SA
*  @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

include_once(dirname(__FILE__).'/../../classes/Generator.php');

class AdminModuleGeneratorController extends ModuleAdminController
{
	/** @var protected string tmp module web path (eg. '/shop/modules/modulename/tmp/') */
	protected $module_path;
	/** @var protected string tmp module web path (eg. '/shop/modules/modulename/tmp/') */
	protected $tmp_path;
	/** @var protected string tmp module web path (eg. '/shop/modules/modulename/tmp/') */
	protected $render_path;
	/** @var protected string tmp module web path (eg. '/shop/modules/modulename/tmp/') */
	protected $source_path;

	public function __construct()
	{
		$this->module_path = _PS_MODULE_DIR_.'modulegenerator/';
		$this->tmp_path = $this->module_path.'tmp/';
		$this->source_path = $this->module_path.'sources/';
		$this->render_path = $this->module_path.'renders/';

		$this->bootstrap = true;
		$this->display = 'view';
		parent::__construct();
		if (!$this->module->active)
			Tools::redirectAdmin($this->context->link->getAdminLink('AdminHome'));
	}

	public function ajaxProcessModuleGeneratorUpload()
	{
		// A list of permitted file extensions
		$allowed = array('png', 'jpg', 'jpeg', 'gif');
		if(isset($_FILES['upl']) && $_FILES['upl']['error'] == 0) {
			$upload_name = $_FILES['upl']['name'];
			$extension = pathinfo($upload_name, PATHINFO_EXTENSION);
			if(!in_array(strtolower($extension), $allowed)) {
				header("HTTP/1.0 500 An error occurred while uploading this file");
				echo '{"status":"An error occurred while uploading this file"}';
				exit;
			}
			if(move_uploaded_file($_FILES['upl']['tmp_name'], $this->tmp_path.$upload_name)) {
				if (!Generator::resize($this->tmp_path.$upload_name, $this->tmp_path.'logo.png')){
					header("HTTP/1.0 500 An error occurred while copying image");
					echo '{"status":"An error occurred while copying image: '.$upload_name.'"}';
				} elseif (!Generator::resize($this->tmp_path.$upload_name, $this->tmp_path.'logo.gif', 16, 16, 'gif')){
					header("HTTP/1.0 500 An error occurred while copying image");
					echo '{"status":"An error occurred while copying image: '.$upload_name.'"}';
				} elseif ($upload_name !== 'logo.png') {
					if (!unlink($this->tmp_path.$upload_name)) {
						header("HTTP/1.0 500 An error occurred while delete image");
						echo '{"status":"An error occurred while delete image: '.$upload_name.'"}';
					}
				} else {
					echo '{"status":"Success uploading '.$upload_name.'"}';
				}
				exit;
			}
		}
		header("HTTP/1.0 500 An error occurred while uploading this file");
		echo '{"status":"An error occurred while uploading this file"}';
		exit;
	}

	public function ajaxProcessModuleGeneratorDelete()
	{
		if (file_exists($this->tmp_path.'logo.png')) {
			if (!unlink($this->tmp_path.'logo.png')) {
				header("HTTP/1.0 500 An error occurred while delete image");
				echo '{"status":"An error occurred while delete image: logo.png"}';
			}
		}
		if (file_exists($this->tmp_path.'logo.gif')) {
			if (!unlink($this->tmp_path.'logo.gif')) {
				header("HTTP/1.0 500 An error occurred while delete image");
				echo '{"status":"An error occurred while delete image: logo.gif"}';
			}
		}
		echo '{"status":"Success deleting logo.png"}';
		exit;
	}

	public function ajaxProcessModuleGeneratorDone()
	{
		// Get form data
		$params = Tools::getValue('data');

		// Parse data
		parse_str($params, $output);
		//array_map( array('Generator','cleanUp') , $output);

		echo '<pre>';
		print_r($output);
		echo '</pre>';

		// Get html form
		$form = trim(Tools::getValue('form'));

		$moduleName = strip_tags(strtolower($output['module_name']));
		$moduleNameCamel = ucfirst($moduleName);

		$moduleDir = $this->render_path.$moduleName.'/';
		$sqlDir = $this->render_path.$moduleName.'/sql/';
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

	$hookFuncFront .= "\n\t/**
	** FRONT HOOK
	*/
	";

	$haveLeft = false;
	foreach($explodeFront as $val) {
	$isLeft = $isRight = $isRight = $isHeader = $isContent = '';
	if ($val === 'displayLeftColumn') {
		$haveLeft = true;
		$isLeft = Generator::standardTPL($moduleName, $hookTPL, $val);
	}
	elseif ($val === 'displayRightColumn') {
		if ($haveLeft === true) {
			$isRight = "return ".'$'."this->hookDisplayLeftColumn(".'$'."params);";
		} else { 
			$isRight = Generator::standardTPL($moduleName, $hookTPL, $val);
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
		$isContent = Generator::standardTPL($moduleName, $hookTPL, $val);
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

	$hookFuncBack .= "\n\t/**
	** BACK HOOK
	*/
	";
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
			$hookFuncFront = rtrim($hookFuncFront);

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
			$sqlConstant = "\t/* SQL files */\n\tconst INSTALL_SQL_FILE = 'install.sql';\n\n\tconst UNINSTALL_SQL_FILE = 'uninstall.sql';\n";

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
		// Create database tables from uninstall.sql
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

		if (version_compare(_PS_VERSION_, '1.6', '<'))
		{
			// Clean the code use tpl file for html
			".'$'."tab = '&tab_module='.".'$'."this->tab;
			".'$'."token_mod = '&token='.Tools::getAdminTokenLite('AdminModules');
			".'$'."token_pos = '&token='.Tools::getAdminTokenLite('AdminModulesPositions');
			".'$'."token_trad = '&token='.Tools::getAdminTokenLite('AdminTranslations');

			".'$'."this->context->smarty->assign(array(
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
		}

		".'$'."this->context->smarty->assign(array(
			'ps_version' => (bool) version_compare(_PS_VERSION_, '1.6', '>'),
		));

		return ".'$'."this->display(__FILE__, 'views/templates/admin/configuration.tpl');
	}
$hookFuncFront$hookFuncBack
}");

		$structure = $moduleDir.'views/';
		if (file_exists($moduleDir)) {
			Generator::deleteRecursive($moduleDir);
		}

		if (!mkdir($structure, 0, true)) {
			die('Echec lors de la création des répertoires...');
		}

		if ($moduleController === 1) {
			mkdir($moduleDir.'controllers');
			if (!empty($moduleTabController)) {
				mkdir($moduleDir.'controllers/admin');
				Generator::copyRecursive($this->source_path.'index.php', $moduleDir.'controllers/admin/index.php');

				$controller = ("<?php
				$licenseFile

				class Admin".$moduleNameCamel."Controller extends ModuleAdminController
				{

				}");
				file_put_contents($moduleDir.'controllers/admin/Admin'.$moduleNameCamel.'Controller.php', $controller);
			}
			Generator::copyRecursive($this->source_path.'index.php', $moduleDir.'controllers/index.php');

			// if (!empty($moduleFrontController)) {
			// 	mkdir($moduleDir.'controllers/front');
			// 	Generator::copyRecursive($this->source_path.'index.php', $moduleDir.'controllers/front/index.php');
			// }
		}

		// Create module file
		file_put_contents($moduleDir.$moduleName.'.php', ($mod));

		// Copy file
		Generator::copyRecursive($this->source_path, $moduleDir);

		// Move uploaded logo
		if (file_exists($this->tmp_path.'logo.png')) {
			if (copy($this->tmp_path.'logo.png', $moduleDir.'logo.png')) {
				// unlink($this->tmp_path.'logo.png');
			}
		}
		if (file_exists($this->tmp_path.'logo.gif')) {
			if (copy($this->tmp_path.'logo.gif', $moduleDir.'logo.gif')) {
				// unlink($this->tmp_path.'logo.gif');
			}
		}

		// Replacement of some variables
		$conf = array(
			'[license]' => $licenseTPL,
			'[form]' => $form,
			'[module]' => $moduleName,
			'[text]' => $moduleDisplay
		);
		Generator::replaceVar($conf, $templateDir.'admin/configuration.tpl');
		Generator::replaceVar($conf, $templateDir.'admin/addons.tpl');
		Generator::replaceVar($conf, $templateDir.'admin/header.tpl');
		Generator::replaceVar($conf, $templateDir.'admin/translations.tpl');

		// Rename css & js
		rename($moduleDir.'css/module.css', $moduleDir.'css/'.$moduleName.'.css');
		rename($moduleDir.'js/module.js', $moduleDir.'js/'.$moduleName.'.js');

		// Write license to JS
		$file = $moduleDir.'js/'.$moduleName.'.js';
		$current = file_get_contents($file);
		$current = $licenseFile."\n\n".$current;
		file_put_contents($file, $current);

		if ($moduleSQL === 1) {
			$structure = $moduleDir.'sql/';
			if (!mkdir($structure, 0, true)) {
				die('Echec lors de la création des répertoires...');
			}
			Generator::copyRecursive($this->source_path.'index.php',$structure.'index.php');
			file_put_contents($sqlDir.'/install.sql', $moduleSQLInstall);
			file_put_contents($sqlDir.'/uninstall.sql', $moduleSQLUninstall);
		}

		// Create tpl file for hook
		if (!empty($explodeFront)) {
			if (!mkdir($hookDir, 0, true)) {
				die('Echec lors de la création des répertoires...');
			}
			$haveLeft = false;
			foreach($explodeFront as $val) {
				if ($val === 'displayHeader') {}
				elseif (strpos($val, 'action') !== false) {}
				else {
					if ($val === 'displayLeftColumn') {
						$haveLeft = true;
						file_put_contents($hookDir.$moduleName.$val.'.tpl', $licenseTPL);
					}
					elseif ($val === 'displayRightColumn') {
						if ($haveLeft === false) {
							file_put_contents($hookDir.$moduleName.$val.'.tpl', $licenseTPL);
						}
						$haveLeft = false;
					}
					else {
						file_put_contents($hookDir.$moduleName.$val.'.tpl', $licenseTPL);
					}
				}
			}
			unset($val);
		}
		exit;
	}
}