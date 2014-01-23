<?php
if (defined('_PS_VERSION_') === false)
	exit;

class ModuleGenerator extends Module
{
	/** @var protected string cache filled with tabs informations */
	protected $css_path;

	/** @var protected string cache filled with tabs informations */
	protected $js_path;

	/** @var protected string cache filled with tabs informations */
	protected $cache_path;

	/** @var protected array cache filled with tabs informations */
	protected static $tabs_cache;

	/** @var protected array cache filled with lang informations */
	protected static $lang_cache;

	function __construct()
	{
		$this->name = 'modulegenerator';
		$this->tab = 'administration';
		$this->version = '1.0.0';
		$this->author = 'Prestashop';
		$this->need_instance = 0;

		$this->bootstrap = true;
		parent::__construct();

		$this->displayName = $this->l('Module Generator');
		$this->description = $this->l('This is a skeleton template for making module quickly.');

		$this->confirmUninstall = $this->l('Are you sure you want to uninstall?');

		$this->css_path = $this->_path.'css/';
		$this->js_path = $this->_path.'js/';

		$this->cache_path = $this->local_path.'cache/';

		$this->getTabs();

		if (version_compare(_PS_VERSION_, '1.6', '<'))
			$this->getLang();
	}

	/*
	** Get all admin tab name
	*/
	private function getTabs()
	{
		if ($this->hasCache())
			self::$tabs_cache = $this->getCache();

		if (self::$tabs_cache === null && !is_array(self::$tabs_cache) && !$this->hasCache())
		{
			self::$tabs_cache = array();
			if ($result = Db::getInstance()->executeS('
				SELECT class_name AS optgroup, GROUP_CONCAT(DISTINCT class_name) AS options
				FROM '._DB_PREFIX_.'tab
				WHERE module = ""
				OR module IS NULL
				GROUP BY id_parent')
			)
			{
				foreach ($result as $row)
				{
					$exprow = explode(',', $row['options']);
					self::$tabs_cache[$row['optgroup']] = $exprow;
				}
				$this->saveCache(self::$tabs_cache);
				unset($row, $exprow, $result);
			}
		}
	}

	/*
	** Get all lang actived
	*/
	private function getLang()
	{
		if (self::$lang_cache === null && !is_array(self::$lang_cache))
		{
			self::$lang_cache = array();
			if ($languages = Language::getLanguages())
			{
				foreach ($languages as $row)
				{
					$exprow = explode(' (', $row['name']);
					$subtitle = (isset($exprow[1]) ? trim(substr($exprow[1], 0, -1)) : '');
					self::$lang_cache[$row['iso_code']] = array (
						'title' => trim($exprow[0]), 
						'subtitle' => $subtitle
					);
				}
				unset($row, $exprow, $languages, $subtitle);
			}
		}
	}

	public function installTab()
	{
		$tab = new Tab();
		$tab->active = 1;
		$tab->class_name = 'AdminModuleGenerator';
		$tab->name = array();
		foreach (Language::getLanguages(true) as $lang)
			$tab->name[$lang['id_lang']] = $this->displayName;
		$tab->id_parent = -1;
		$tab->module = $this->name;
		return $tab->add();
	}
	
	public function uninstallTab()
	{
		$id_tab = (int)Tab::getIdFromClassName('AdminModuleGenerator');
		if ($id_tab)
		{
			$tab = new Tab($id_tab);
			return $tab->delete();
		}
		else
			return false;
	}

	/*
	** Make install
	*/
	public function install()
	{
		if (parent::install() === false
		|| $this->installTab() === false
		|| $this->registerHook('DisplayBackOfficeHeader') === false)
			return false;
		return true;
	}

	/*
	** Make uninstall
	*/
	public function uninstall()
	{
		if (parent::uninstall() === false
		||$this->uninstallTab() === false)
			return false;
		return true;
	}

	/*
	** Make some design!
	*/
	public function loadAsset()
	{
		$css_compatibility = $js_compatibility = array();

		// Load CSS
		$css = array(
			$this->css_path.'bootstrap-select.min.css',
			$this->css_path.'bootstrap-dialog.min.css',
			$this->css_path.'bootstrap-form-builder.css',
			$this->css_path.'form-builder.css',
			$this->css_path.$this->name.'.css'
		);
		if (version_compare(_PS_VERSION_, '1.6', '<'))
		{
			$css_compatibility = array(
				$this->css_path.'bootstrap.min.css',
				$this->css_path.'bootstrap.extend.css',
				$this->css_path.'bootstrap-responsive.min.css',
				$this->css_path.'font-awesome.min.css',
			);
			$css = array_merge($css, $css_compatibility);
		}
		$this->context->controller->addCSS($css, 'all');

		// Load JS
		$js = array(
			$this->js_path.'jquery-2.0.3.min.js',
			$this->js_path.'jquery-migrate-1.2.1.min.js',
			$this->js_path.'jquery.mousewheel.js',
			$this->js_path.'jquery.validate.min.js',
			$this->js_path.'jquery.autosize.min.js',
			$this->js_path.'jquery.ui.widget.js',
			$this->js_path.'jquery.iframe-transport.js',
			$this->js_path.'jquery.fileupload.js',
			$this->js_path.'bootstrap-dialog.js',
			$this->js_path.'bootstrap-select.min.js',
			$this->js_path.'jquery.smartWizard.js',
			$this->js_path.$this->name.'.js'
		);
		if (version_compare(_PS_VERSION_, '1.6', '<'))
		{
			$js_compatibility = array(
				$this->js_path.'bootstrap.min.js'
			);
			$js = array_merge($js, $js_compatibility);
		}

		$this->context->controller->addJS($js);

		// Clean memory
		unset($js, $css, $js_compatibility, $css_compatibility);
	}

	/*
	** Show the configuration module
	*/
	public function getContent()
	{
		// We load asset
		$this->loadAsset();

		if (version_compare(_PS_VERSION_, '1.6', '<'))
		{
			// Clean the code use tpl file for html
			$tab = '&tab_module='.$this->tab;
			$token_mod = '&token='.Tools::getAdminTokenLite('AdminModules');
			$token_pos = '&token='.Tools::getAdminTokenLite('AdminModulesPositions');
			$token_trad = '&token='.Tools::getAdminTokenLite('AdminTranslations');

			$this->context->smarty->assign(array(
				'lang_select' => self::$lang_cache,
				'module_name' => $this->name,
				'module_active' => (bool)$this->active,
				'module_trad' => 'index.php?tab=AdminTranslations'.$token_trad.'&type=modules&lang=',
				'module_hook' => 'index.php?tab=AdminModulesPositions'.$token_pos.'&show_modules='.$this->id,
				'module_back' => 'index.php?tab=AdminModules'.$token_mod.$tab.'&module_name='.$this->name,
				'module_form' => 'index.php?tab=AdminModules&configure='.$this->name.$token_mod.$tab.'&module_name='.$this->name,
			));
			// Clean memory
			unset($tab, $token_mod, $token_pos, $token_trad);
		}

		$this->context->smarty->assign(array(
			'tab_select' => self::$tabs_cache,
			'ps_version' => (bool) version_compare(_PS_VERSION_, '1.6', '>'),
		));

		return $this->display(__FILE__, 'views/templates/admin/configuration.tpl');
	}

	/*
	** Display JS & CSS in BO header
	*/
	public function hookDisplayBackOfficeHeader($params) 
	{		
		// Load JS only if we configure the module
		if(!((Tools::getValue('tab_module') === $this->tab 
		|| Tools::getValue('controller') === 'AdminModules') 
		&& Tools::getValue('configure') === $this->name))
			return;

		// Call of Dirty
		// technical for js include...
		$html = '<script data-main="'.$this->js_path.'main-built.js" src="'.$this->js_path.'lib/require.js"></script>
		<script>
			var admin_modulegenerator_ajax_url = \''.$this->context->link->getAdminLink('AdminModuleGenerator').'\';
			var current_id_tab = '.(int)$this->context->controller->id.';
		</script>';
		return $html;
	}

	/**
	 * Check if a data is cached
	 *
	 * @return bool
	 */
	public function hasCache()
	{
		if ($this->isCacheLifeTimeExpired())
			return false;
		return true;
	}

	/**
	 * Check the time life of cache
	 *
	 * @param int $time 
	 * @return bool
	 */
	public function isCacheLifeTimeExpired($time = 45)
	{
		$get_cache_name = $this->cache_path.'tabs_cache.cache';
		if (file_exists($get_cache_name))
		{
			$cacheTime = filectime($get_cache_name);
			$livetime = (int)$time * 60;
			return ($cacheTime + $livetime < time());
		}
		return true;
	}

	/**
	 * Retrieve a data from cache
	 *
	 * @return array
	 */
	public function getCache()
	{
		if (file_exists($this->cache_path.'tabs_cache.cache'))
			return unserialize(file_get_contents($this->cache_path.'tabs_cache.cache'));
	}

	/**
	 * Store a data in cache
	 *
	 * @param mixed $value
	 * @return bool
	 */
	public function saveCache($data)
	{
		return file_put_contents($this->cache_path.'tabs_cache.cache', serialize($data));
	}
}