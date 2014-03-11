<?php
/**
* 2007-2014 PrestaShop
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
* @author    PrestaShop SA <contact@prestashop.com>
* @copyright 2007-2014 PrestaShop SA
* @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
* International Registered Trademark & Property of PrestaShop SA
*/

include_once(dirname(__FILE__).'/../../classes/DataProcess.php');

class AdminModuleGeneratorController extends ModuleAdminController
{
	/** @var protected string tmp module web path (eg. '/shop/modules/module_name/tmp/') */
	protected $module_path;
	/** @var protected string tmp module web path (eg. '/shop/modules/module_name/tmp/') */
	protected $tmp_path;
	/** @var protected string tmp module web path (eg. '/shop/modules/module_name/tmp/') */
	protected $render_path;
	/** @var protected string tmp module web path (eg. '/shop/modules/module_name/tmp/') */
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
		if (isset($_FILES['upl']) && $_FILES['upl']['error'] === 0)
		{
			$upload_name = $_FILES['upl']['name'];
			$extension = pathinfo($upload_name, PATHINFO_EXTENSION);
			if (!in_array(Tools::strtolower($extension), $allowed))
			{
				header("HTTP/1.0 500 An error occurred while uploading this file");
				$this->ajaxProcessModuleGeneratorDelete(false);
				die(Tools::jsonEncode($this->l('An error occurred while uploading this file')));
			}
			elseif (move_uploaded_file($_FILES['upl']['tmp_name'], $this->tmp_path.$upload_name))
			{
				if (!DataProcess::resize($this->tmp_path.$upload_name, $this->tmp_path.'logo.png'))
				{
					header("HTTP/1.0 500 An error occurred while copying image");
					unlink($this->tmp_path.$upload_name);
					$this->ajaxProcessModuleGeneratorDelete(false);
					die(Tools::jsonEncode($this->l('An error occurred while copying image:').' '.$upload_name));
				}
				elseif (!DataProcess::resize($this->tmp_path.$upload_name, $this->tmp_path.'logo.gif', 16, 16, 'gif'))
				{
					header("HTTP/1.0 500 An error occurred while copying image");
					unlink($this->tmp_path.$upload_name);
					$this->ajaxProcessModuleGeneratorDelete(false);
					die(Tools::jsonEncode($this->l('An error occurred while copying image:').' '.$upload_name));
				}
				elseif ($upload_name !== 'logo.png')
				{
					if (!unlink($this->tmp_path.$upload_name))
					{
						header("HTTP/1.0 500 An error occurred while delete image");
						die(Tools::jsonEncode($this->l('An error occurred while delete image:').' '.$upload_name));
					}
				}
			}
			die(Tools::jsonEncode($this->l('Success uploading').' '.$upload_name));
		}
		else {
			header("HTTP/1.0 500 An error occurred while uploading this file");
			die(Tools::jsonEncode($this->l('An error occurred while uploading this file')));
		}
	}

	public function ajaxProcessModuleGeneratorDelete($show_error = true)
	{
		if (file_exists($this->tmp_path.'logo.png'))
		{
			if (!unlink($this->tmp_path.'logo.png'))
			{
				if ($show_error === true) {
					header("HTTP/1.0 500 An error occurred while delete image");
					die(Tools::jsonEncode($this->l('An error occurred while delete image: logo.png')));
				}
			}
		}
		if (file_exists($this->tmp_path.'logo.gif'))
		{
			if (!unlink($this->tmp_path.'logo.gif'))
			{
				if ($show_error === true) {
					header("HTTP/1.0 500 An error occurred while delete image");
					die(Tools::jsonEncode($this->l('An error occurred while delete image: logo.gif')));
				}
			}
		}
		if ($show_error === true) {
			die(Tools::jsonEncode($this->l('Success deleting logo.png')));
		}
	}

	public function ajaxProcessModuleGeneratorDone()
	{
		// Get form data
		$params = Tools::getValue('data');

		// Parse data
		parse_str($params, $output);

		// Get html form
		$form = trim(Tools::getValue('form'));

		$module_name = strip_tags(Tools::strtolower($output['module_name']));
		$module_name_camel = Tools::ucfirst($module_name);

		$module_dir = $this->render_path.$module_name.'/';
		$sql_dir = $this->render_path.$module_name.'/sql/';
		$template_dir = $module_dir.'views/templates/';
		$hook_dir = $template_dir.'hook/';
		$hook_tpl = 'views/templates/hook/';

		$module_controller = (int)$output['back_controller'];
		$module_tab_controller = ($output['tabs_controller_back'] === 'null') ? '-1' : strip_tags($output['tabs_controller_back']);

		$module_sql = (int)$output['need_sql_install'];
		$module_sql_install = $output['sql_install'];
		$module_sql_uninstall = $output['sql_uninstall'];

		$module_tab = strip_tags(Tools::strtolower($output['module_tab']));
		$module_version = (int)$output['module_version'].'.'.(int)$output['module_version_func'].'.'.(int)$output['module_version_rev'];
		$module_author = strip_tags($output['module_author']);
		$module_instance = (int)$output['need_instance'];

		$module_display = strip_tags($output['module_display_name']);
		$module_desc = strip_tags($output['module_description']);

		$module_uninstall = (int)$output['confirm_uninstall'];
		$module_uninstall_text = strip_tags($output['module_uninstall']);

		$uninstall = '';
		if ($module_uninstall === 1)
			$uninstall = "\n\n\t\t".'$'."this->confirmUninstall = ".'$'."this->l('$module_uninstall_text');";

		$hook_front = strip_tags($output['module_hook_front']);
		$hook_back = strip_tags($output['module_hook_back']);

		$hook_install = $hook_func_front = $hook_func_back = '';
if ($hook_front !== 'null')
{
	$explode_front = explode(',', $hook_front);
	foreach ($explode_front as $val)
		$hook_install .= "\n\t\t\t|| ".'$'."this->registerHook('$val') === false";

	$hook_func_front .= "\n\t/**
	** FRONT HOOK
	*/
	";

	$have_left = false;
	foreach ($explode_front as $val)
	{
	$is_left = $is_right = $is_right = $is_header = $is_content = '';
	if ($val === 'displayLeftColumn')
	{
		$have_left = true;
		$is_left = DataProcess::standardTPL($module_name, $hook_tpl, $val);
	}
	elseif ($val === 'displayRightColumn')
	{
		if ($have_left === true)
			$is_right = "return ".'$'."this->hookDisplayLeftColumn(".'$'."params);";
		else
			$is_right = DataProcess::standardTPL($module_name, $hook_tpl, $val);
		$have_left = false;
	}
	elseif ($val === 'displayHeader')
	{
		$is_header = "// Load CSS
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
	elseif (strpos($val, 'action') !== false)
		$is_content = '';
	else
		$is_content = DataProcess::standardTPL($module_name, $hook_tpl, $val);

	$hook_func_front .= "public function hook$val(".'$'."params)
	{
		// Check if the module is active
		if (!".'$'."this->active)
			return;

		$is_header$is_left$is_right$is_content
	}\n\n\t";
	}

	if ($hook_back === 'null')
		$hook_install .= ')';
}

if ($hook_back !== 'null')
{
	$explode_back = explode(',', $hook_back);
	foreach ($explode_back as $val)
		$hook_install .= "\n\t\t\t|| ".'$'."this->registerHook('$val') === false";

	$hook_func_back .= "\n\t/**
	** BACK HOOK
	*/
	";
	$is_left = $is_right = '';
	foreach ($explode_back as $val)
	{
	$hook_func_back .= "public function hook$val(".'$'."params)
	{
		// Check if the module is active
		if (!".'$'."this->active)
			return;
	}\n\n\t";
	}

	// unset($explode_back, $val);
	$hook_install .= ')';
}

		if (trim($hook_func_front) === '')
			$hook_func_front = rtrim($hook_func_front);

		$hook_func_front = rtrim($hook_func_front);
		$hook_func_back = rtrim($hook_func_back);

		if ($hook_back === 'null' && $hook_front === 'null')
			$hook_install = ')';

		// Extends Modules
		$extends = '';
		if (strpos($module_tab, 'migration') !== false)
			$extends = 'Import';
		elseif (strpos($module_tab, 'payments') !== false)
			$extends = 'Payment';
		elseif (strpos($module_tab, 'billing') !== false)
			$extends = 'TaxManager';
		elseif (strpos($module_tab, 'shipping') !== false)
			$extends = 'Carrier';
		elseif (strpos($module_tab, 'quick') !== false)
			$extends = 'StockManager';

		$tabs_func_install = $tabs_func_uninstall = $tabs_install = $tabs_uninstall = '';
		if ($module_controller === 1)
		{
			if ($module_tab_controller === '-1')
				$parent_tab = '-1';
			else
				$parent_tab = "(int)Tab::getIdFromClassName('$module_tab_controller')";

	$tabs_func_install = "\n\t/**
	* Install Tab
	* @return boolean
	*/
	private function installTab()
	{
		".'$'."tab = new Tab();
		".'$'."tab->active = 1;
		".'$'."tab->class_name = 'Admin$module_name_camel';
		".'$'."tab->name = array();
		foreach (Language::getLanguages(true) as ".'$'."lang)
			".'$'."tab->name[".'$'."lang['id_lang']] = 'test';
		unset(".'$'."lang);
		".'$'."tab->id_parent = $parent_tab;
		".'$'."tab->module = ".'$'."this->name;
		return ".'$'."tab->add();
	}\n";

	$tabs_func_uninstall = "\n\t/**
	* Uninstall Tab
	* @return boolean
	*/
	private function uninstallTab()
	{
		".'$'."id_tab = (int)Tab::getIdFromClassName('Admin$module_name_camel');
		if (".'$'."id_tab)
		{
			".'$'."tab = new Tab(".'$'."id_tab);
			return ".'$'."tab->delete();
		}
		else
			return false;
	}\n";

			$tabs_install = "\n\t\t\t|| ".'$'."this->installTab() === false";
			$tabs_uninstall = "\n\t\t\t|| ".'$'."this->uninstallTab() === false";
		}

		$sql_constant = $sql_path = $sql_func_install = $sql_func_uninstall = $sql_install = $sql_uninstall = '';
		if ($module_sql === 1)
		{
			$sql_constant = "\t/* SQL files */\n\tconst INSTALL_SQL_FILE = 'install.sql';\n\n\tconst UNINSTALL_SQL_FILE = 'uninstall.sql';\n";

			$sql_path = "\n\t\t".'$'."this->sql_path = dirname(__FILE__).'/sql/';";

	$sql_func_install = "\n\t/**
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

	$sql_func_uninstall = "\n\t/**
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

			$sql_install = "\n\t\t\t|| ".'$'."this->installSQL() === false";
			$sql_uninstall = "\n\t\t\t|| ".'$'."this->uninstallSQL() === false";
		}

$license_tpl = "{**
* 2007-2014 PrestaShop
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
* @author    PrestaShop SA <contact@prestashop.com>
* @copyright 2007-2014 PrestaShop SA
* @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
* International Registered Trademark & Property of PrestaShop SA
*}";

$license_file = "/**
* 2007-2014 PrestaShop
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
* @author    PrestaShop SA <contact@prestashop.com>
* @copyright 2007-2014 PrestaShop SA
* @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
* International Registered Trademark & Property of PrestaShop SA
*/";
$mod = trim("<?php
$license_file

if (defined('_PS_VERSION_') === false)
	exit;

if (!class_exists('TinyCache'))
	include_once(dirname(__FILE__).'/classes/TinyCache.php');

class $module_name_camel extends ".$extends."Module
{
	/**
	* @var string Admin Module template path 
	* (eg. '/home/prestashop/modules/module_name/views/templates/admin/')
	*/
	protected ".'$'."admin_tpl_path = null;

	/**
	* @var string Admin Module template path 
	* (eg. '/home/prestashop/modules/module_name/views/templates/hook/')
	*/
	protected ".'$'."hooks_tpl_path = null;

	/** @var string Module js path (eg. '/shop/modules/module_name/js/') */
	protected ".'$'."js_path = null;

	/** @var string Module css path (eg. '/shop/modules/module_name/css/') */
	protected ".'$'."css_path = null;

	/** @var protected array cache filled with lang informations */
	protected static ".'$'."lang_cache;

$sql_constant
	public function __construct()
	{
		".'$'."this->name = '$module_name';
		".'$'."this->tab = '$module_tab';
		".'$'."this->version = '$module_version';
		".'$'."this->author = '$module_author';
		".'$'."this->need_instance = '$module_instance';

		".'$'."this->bootstrap = true;
		".'$'."this->secure_key = Tools::encrypt(".'$'."this->name);

		parent::__construct();

		".'$'."this->displayName = ".'$'."this->l('$module_display');
		".'$'."this->description = ".'$'."this->l('$module_desc');$uninstall

		".'$'."this->js_path = ".'$'."this->_path.'js/';
		".'$'."this->css_path = ".'$'."this->_path.'css/';$sql_path
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
		".'$'."cache = TinyCache::getCache('language');
		if (!empty(".'$'."cache))
		{
			self::".'$'."lang_cache = TinyCache::getCache('language');
			return;
		}

		if (self::".'$'."lang_cache === null)
		{
			if (".'$'."languages = Language::getLanguages())
			{
				foreach (".'$'."languages as ".'$'."row)
				{
					".'$'."exprow = explode(' (', ".'$'."row['name']);
					".'$'."subtitle = (isset(".'$'."exprow[1]) ? trim(Tools::substr(".'$'."exprow[1], 0, -1)) : '');
					self::".'$'."lang_cache[".'$'."row['iso_code']] = array (
						'id' => (int)".'$'."row['id_lang'],
						'title' => trim(".'$'."exprow[0]),
						'subtitle' => ".'$'."subtitle
					);
				}
				// Cache Data
				TinyCache::setCache('language', self::".'$'."lang_cache);
				// Clean memory
				unset(".'$'."row, ".'$'."exprow, ".'$'."subtitle, ".'$'."languages);
			}
		}
	}
$sql_func_install$sql_func_uninstall$tabs_func_install$tabs_func_uninstall
	/**
	* Insert module into datable
	* @return boolean result
	*/
	public function install()
	{
		if (Shop::isFeatureActive())
			Shop::setContext(Shop::CONTEXT_ALL);

		if (parent::install() === false$sql_install$tabs_install$hook_install
			return false;
		return true;
	}

	/**
	* Delete module from datable 
	* @return boolean result 
	*/
	public function uninstall()
	{
		if (parent::uninstall() === false$sql_uninstall$tabs_uninstall)
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
				".'$'."this->css_path.'bootstrap.extend.css',
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
				'module_active' => (bool)".'$'."this->active,
				'module_trad' => 'index.php?controller=AdminTranslations'.".'$'."token_trad.'&type=modules&lang=',
				'module_hook' => 'index.php?controller=AdminModulesPositions'.".'$'."token_pos.'&show_modules='.".'$'."this->id,
				'module_back' => 'index.php?controller=AdminModules'.".'$'."token_mod.".'$'."tab.'&module_name='.".'$'."this->name,
				'module_form' => 'index.php?controller=AdminModules&configure='.".'$'."this->name.".'$'."token_mod.".'$'."tab.'&module_name='.".'$'."this->name,
				'module_reset' => 'index.php?controller=AdminModules'.".'$'."token_mod.'&module_name='.".'$'."this->name.'&reset'.".'$'."tab,
			));
			// Clean memory
			unset(".'$'."tab, ".'$'."token_mod, ".'$'."token_pos, ".'$'."token_trad);
		}

		".'$'."lang = 'EN';
		if (".'$'."this->context->language->iso_code == 'fr')
			".'$'."lang = 'FR';

		".'$'."this->context->smarty->assign(array(
			'module_name' => ".'$'."this->name,
			'module_display' => ".'$'."this->displayName,
			'guide_link' => 'docs/'.".'$'."this->name.'_'.".'$'."lang.'.pdf',
			'ps_version' => (bool)version_compare(_PS_VERSION_, '1.6', '>'),
		));

		return ".'$'."this->display(__FILE__, 'views/templates/admin/configuration.tpl');
	}
$hook_func_front$hook_func_back
}");

		$structure = $module_dir.'views/';
		if (file_exists($module_dir))
			DataProcess::deleteRecursive($module_dir);

		if (!mkdir($structure, 0777, true))
			die('Echec lors de la création des répertoires... ('.$structure.')');

		if ($module_controller === 1)
		{
			mkdir($module_dir.'controllers');
			if (!empty($module_tab_controller))
			{
				mkdir($module_dir.'controllers/admin');
				DataProcess::copyRecursive($this->source_path.'index.php', $module_dir.'controllers/admin/index.php');

				$controller = ("<?php
				$license_file

				class Admin".$module_name_camel."Controller extends ModuleAdminController
				{

				}");
				file_put_contents($module_dir.'controllers/admin/Admin'.$module_name_camel.'Controller.php', $controller);
			}
			DataProcess::copyRecursive($this->source_path.'index.php', $module_dir.'controllers/index.php');
		}

		// Create module file
		file_put_contents($module_dir.$module_name.'.php', ($mod));

		// Copy file
		DataProcess::copyRecursive($this->source_path, $module_dir);

		// Move uploaded logo
		if (file_exists($this->tmp_path.'logo.png'))
			copy($this->tmp_path.'logo.png', $module_dir.'logo.png');

		if (file_exists($this->tmp_path.'logo.gif'))
			copy($this->tmp_path.'logo.gif', $module_dir.'logo.gif');

		// Replacement of some variables
		$conf = array(
			'[form]' => $form,
			'[license]' => $license_tpl,
			'[module]' => $module_name,
			'[text]' => $module_display,
			'<pstrad>' => "{l s='",
			'</pstrad>' => "' mod='$module_name'}",
		);

		$tpl_file = array(
			$template_dir.'admin/header.tpl',
			$template_dir.'admin/tabs/config.tpl',
			$template_dir.'admin/translations.tpl',
			$template_dir.'admin/tabs/contact.tpl',
			$template_dir.'admin/configuration.tpl',
			$template_dir.'admin/tabs/documentation.tpl',
		);

		DataProcess::replaceVar($conf, $template_dir.'admin/tabs/config.tpl');

		foreach ($tpl_file as $tpl)
			DataProcess::replaceVar($conf, $tpl);

		// Rename css & js
		rename($module_dir.'css/module.css', $module_dir.'css/'.$module_name.'.css');
		rename($module_dir.'js/module.js', $module_dir.'js/'.$module_name.'.js');

		// Write license to JS
		$file = $module_dir.'js/'.$module_name.'.js';
		$current = Tools::file_get_contents($file);
		$current = $license_file."\n\n".$current;
		file_put_contents($file, $current);

		if ($module_sql === 1)
		{
			$structure = $module_dir.'sql/';
			if (!mkdir($structure, 0777, true))
				die('Echec lors de la création des répertoires...');

			DataProcess::copyRecursive($this->source_path.'index.php', $structure.'index.php');
			file_put_contents($sql_dir.'/install.sql', $module_sql_install);
			file_put_contents($sql_dir.'/uninstall.sql', $module_sql_uninstall);
		}

		// Create tpl file for hook
		if (!empty($explode_front))
		{
			if (!mkdir($hook_dir, 0777, true))
				die('Echec lors de la création des répertoires hook...');

			DataProcess::copyRecursive($this->source_path.'index.php', $hook_dir.'index.php');

			$have_left = false;
			foreach ($explode_front as $val)
			{
				if ($val === 'displayHeader') {}
				elseif (strpos($val, 'action') !== false) {}
				else
				{
					if ($val === 'displayLeftColumn')
					{
						$have_left = true;
						file_put_contents($hook_dir.$module_name.$val.'.tpl', $license_tpl);
					}
					elseif ($val === 'displayRightColumn')
					{
						if ($have_left === false)
							file_put_contents($hook_dir.$module_name.$val.'.tpl', $license_tpl);
						$have_left = false;
					}
					else
						file_put_contents($hook_dir.$module_name.$val.'.tpl', $license_tpl);
				}
			}
			unset($val);
		}

		$zip_name = $module_name.'.zip';
		$zip_path = $this->render_path.$zip_name;
		$zip_url = 'http://'.$_SERVER['HTTP_HOST'].__PS_BASE_URI__.'modules/modulegenerator/renders/'.$zip_name;
		DataProcess::zipDir($module_dir, $zip_path);

		exit($zip_url);
	}
}