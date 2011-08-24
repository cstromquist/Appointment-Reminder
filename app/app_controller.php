<?php
/**
 * Wildflower AppController
 *
 * WF AppController does:
 * - authentificate users
 * - set WF Configure settings
 * - load necessary Helpers and Components
 * - provides some global controller actions like delete (@TODO: this could be dangerous - revisit)
 */
App::import('Sanitize');
App::import('Core', 'l10n');

class AppController extends Controller {

	public $components = array('Acl', 'Auth', 'Cookie', 'RequestHandler', 'Seo', 'Access');
	public $currentUserId;
	public $helpers = array(
	    'Html', 
	    'Htmla', 
	    'Form', 
	    'Javascript', 
	    'Wild', 
	    'Navigation', 
	    'PartialLayout', 
	    'Textile', 
	    'Tree', 
	    'Text',
	    'Time'
	);
	public $view = 'Theme';
	public $homePageId;
	public $isAuthorized = false;
    public $isHome = false;
	private $_isDatabaseConnected = true;
	
	/**
	 * Configure and initialize everything Wildflower needs
	 *
     * Should be called before all controller actions in AppController::beforeFilter().
     * 
     * Does 3 things:
     *   1. protect admin area
     *   2. check for user sessions
     *   3. set site settings, parameters and global view vars
     */
	private function _configureWildflower() {
	    // AuthComponent config
        $this->Auth->userModel = 'User';
        $this->Auth->fields = array('username' => 'login', 'password' => 'password');
        $prefix = Configure::read('Routing.admin');
        $this->Auth->loginAction = array('controller' => 'users', 'action' => 'login', 'admin' => false);
        $this->Auth->logoutAction = array('controller' => 'users', 'action' => 'logout', 'admin' => false);
        $this->Auth->autoRedirect = false;
        $this->Auth->allow('update_root_cache'); // requestAction() actions need to be allowed
        $this->Auth->loginRedirect = "/$prefix";
		$this->Auth->actionPath = 'controllers/';
		$this->Auth->allowedActions = array('display');
	    
	    // Site settings
		$settings = ClassRegistry::init('Setting')->getKeyValuePairs();
        Configure::write('AppSettings', $settings); // @TODO add under Wildlfower. configure namespace
        Configure::write('Wildflower.settings', $settings); // The new namespace for WF settings
        
        // Admin area requires authentification
		if ($this->isAdminAction()) {
			// bad code. refactor when Auth is figured out.
			if($this->action != 'admin_error' && $_SERVER['REQUEST_URI'] != '/admin' && $_SERVER['REQUEST_URI'] != '/admin/') {
				$this->Access->startup();
				if(!$this->Access->checkCompanyStatus()) {
					$this->redirect(array('controller' => 'faults', 'action' => 'error', 'activation'));
				}
				if(!$this->Access->checkCompanySubscription()) {
					$this->redirect(array('controller' => 'faults', 'action' => 'error', 'subscription'));
				}
			}
			if($this->Auth->User('group_id') == 6)
				$this->layout = 'crp_default';
			elseif($this->Auth->User('group_id') == 5)
				$this->layout = 'manager_default';
			else
				$this->layout = 'admin_default';
			
			$this->set('username', $this->Auth->user('name'));
			
		} else {
			$this->layout = 'default';
			$this->Auth->allow('*');
		}
		
		// Internationalization
		$this->L10n = new L10n();
        $this->L10n->get('eng');
        Configure::write('Config.language', 'en');

		// Home page ID
		$this->homePageId = intval(Configure::read('AppSettings.home_page_id'));

		// Set cookie defaults
		$this->cookieName = Configure::read('Wildflower.cookie.name');
		$this->cookieTime = Configure::read('Wildflower.cookie.expire');
		$this->cookieDomain = '.' . getenv('SERVER_NAME');

		// Compress output to save bandwith / speed site up
		if (!isset($this->params['requested']) && Configure::read('Wildflower.gzipOutput')) {
		    $this->gzipOutput();
		}
	}
	
	function afterFilter() {
		
	}
	
    function beforeFilter() {
        parent::beforeFilter();
		$this->_configureWildflower();
    }

    /**
     * @TODO legacy code, refactor!
     *
     * Delete an item
     *
     * @param int $id
     */
    function wf_delete($id = null) {
    	$id = intval($id);
    	$model = $this->modelClass;
    	
        if ($this->RequestHandler->isAjax()) {
            $success = $this->{$model}->del($id);
            
            $responce = json_encode(array('success' => $success, 'id' => $id));
            header('Content-type: text/plain');
            exit($responce);
        }
        
        if (empty($this->data)) {
            $this->data = $this->{$model}->findById($id);
            if (empty($this->data)) {
                $this->indexRedirect();
            }
        } else {
            if ($this->{$model}->del($this->data[$model][$this->{$model}->primaryKey])) {
                $this->Session->setFlash("{$model} #$id was deleted.");
                $this->redirect(array('action' => 'index'));
            } else {
            	$this->Session->setFlash("Error while deleting {$model} #$id.");
            }
        }
    }
    
    /**
     * Update more records at once
     *
     * @TODO Could be much faster using custom UPDATE or DELETE queries
     */
    function admin_mass_update() {
        //fb($this->data);exit();
        if ($this->data['__action'] == 'delete') {
            $this->data['__action'] = 'mass_delete';
        }

        $availActions = array('mass_delete', 'publish', 'unpublish', 'approve', 'unapprove', 'spam', 'unspam');
        // Collect selected item IDs
        $ids = array();
        if (isset($this->data['__action'])) {
            foreach ($this->data[$this->modelClass]['id'] as $id => $checked) {
                if (intval($checked) === 1) {
                    $ids[] = intval($id);
                }
            }
        }
        // If the action is recognized execute it
        if (in_array($this->data['__action'], $availActions, true)) {
        	foreach($ids as $id){
            	$result = $this->{$this->modelClass}->{$this->data['__action']}($id);
			}
        }

    	$redirect = am($this->params['named'], array('action' => 'wf_index'));
        $this->redirect($this->referer($redirect));
    }
    
    /**
     * Admin search
     *
     * @param string $query Search term, encoded by Javascript's encodeURI()
     */
    function admin_search($query = '') {
        $query = urldecode($query);
        $results = $this->{$this->modelClass}->search($query);
        $this->set('results', $results);
        $this->render('/dashboards/wf_search');
    }
	
	/**
	 * Make sure the application returns 404 if it's not a requested action
	 *
	 */
	function assertInternalRequest() {
	    $this->autoRender = false;
	    
	    if ($this->params['requested']) {
	        return true;
	    }
	    
	    $this->do404();
	    return false;
	}
	
	function xssBlackHole() {
	    $this->cakeError('xss');
	}
    
    /**
     * @TODO Under construction
     *
     * Launch callbacks if they exist for current controller/method
     *
     * Callback for controllers are stored in <code>app/controllers/wildflower-callbacks/</code>.
     * The name convencions is unserscored class that you want to plug into with "_callback"
     * suffix. Examples:
     *    
     *    - pages_controller_callback.php
     *    - comments_controller_callback.php
     *    - wildflower_app_controller_callback.php
     *
     * @param string $when Launch <code>before</code> or <code>after</code> current action
     */
    function wildflowerCallback($when = 'after') {
        // app_controller
        if (class_exists('AppControllerCallback')) {
            $plugin = new AppControllerCallback;
            foreach (array('beforeFilter', 'afterFilter') as $filter) {
                $method = $filter;
                if (method_exists($plugin, $method)) {
                    $plugin->{$method}();
                    return;
                } 
            }
        }
        
        $className = Inflector::camelize($this->params['controller']) . 'ControllerCallback';
        if (class_exists($className)) {
            $plugin = new $className;
            $method = $when . '_' . $this->params['action'];
            if (method_exists($plugin, $method)) {
                $plugin->{$method}();
                return;
            }
        }
    }
    
    /**
     * Before rendering
     * 
     * Set nice SEO titles.
     */
    function beforeRender() {
        parent::beforeRender();
        
        // // @TODO: Hmmmm?
        // if (!$this->_isDatabaseConnected) {
        //     return;
        // }

        $this->Seo->title();
        
    	/** @var $refeter string Convenient $referer var in all views **/
    	$this->set('referer', $this->referer());
    	
    	// Set view parameters (CmsHelper uses some of these for example)
        $params = array(
            'siteName' => Configure::read('AppSettings.site_name'),
            'siteDescription' => Configure::read('AppSettings.description'),
            'isLogged' => $this->Auth->isAuthorized(),
            'isAuthorized' => $this->Auth->isAuthorized(),
            'isPage' => false,
            'isPosts' => false,
            'isHome' => $this->isHome,
            'homePageId' => $this->homePageId,
            // Here without base
            'here' => substr($this->here, strlen($this->base) - strlen($this->here)),
        );
        $this->params['Wildflower']['view'] = $params;
    	$this->set($params);
    	
    	// User ID for views
		$this->set('loggedUserId', $this->Auth->user('id'));
		
		$this->theme = Configure::read('Wildflower.settings.theme');
    }

	function do404() {
		$this->pageTitle = 'Page not found';
        
        $this->cakeError('error404', array(array(
                'message' => 'Requested page was not found.',
                'base' => $this->base)));
	}
	
    function getLoggedInUserId() {
        return $this->Auth->user('id');
    }
	
	/**
	 * Create a preview cache file
	 *
	 * @return void
	 */
	function admin_create_preview() {
        $cacheDir = Configure::read('Wildflower.previewCache') . DS;
        
        // Create a unique file name
        $fileName = time();
        $path = $cacheDir . $fileName . '.json';
        while (file_exists($path)) {
            $fileName++;
            $path = $cacheDir . $fileName . '.json';
        }
        
        // Write POST data to preview file
        $data = json_encode($this->data[$this->modelClass]);
        file_put_contents($path, $data);
        
        // Garbage collector
        $this->__previewCacheGC($cacheDir);
        
        $responce = array('previewFileName' => $fileName);
        $this->set('data', $responce);
        $this->render('/elements/json');
    }

    /**
     * Tell wheather the current action should be protected
     *
     * @return bool
     */
    function isAdminAction() {
        if (isset($this->params[Configure::read('Routing.admin')]) and $this->params[Configure::read('Routing.admin')]) {
            return true;
        }
        return false;
    }

    /**
     * Delete old files from preview cache
     * 
     * @link http://www.jonasjohn.de/snippets/php/delete-temporary-files.htm
     *
     * @param string $path
     */
    protected function __previewCacheGC($path) {
        // Filetypes to check (you can also use *.*)
        $fileTypes = '*.json';
         
        // Here you can define after how many
        // minutes the files should get deleted
        $expire_time = 120;
         
        // Find all files of the given file type
        foreach (glob($path . $fileTypes) as $Filename) {
            // Read file creation time
            $FileCreationTime = filectime($Filename);
            // Calculate file age in seconds
            $FileAge = time() - $FileCreationTime; 
            // Is the file older than the given time span?
            if ($FileAge > ($expire_time * 60)) {
                unlink($Filename);
            }
        }
    }
    
     /**
      * Read and decode data from preview cache
      * 
      * @param string $fileName
      * @return array
      */
     protected function __readPreviewCache($fileName) {
         $previewCachePath = Configure::read('Wildflower.previewCache') . DS . $fileName . '.json';
         if (!file_exists($previewCachePath)) {
             return trigger_error("Cache file $previewCachePath does not exist!");
         }

         $json = file_get_contents($previewCachePath);
         $item[$this->modelClass] = json_decode($json, true);

         return $item;
     }
	
	/**
	 * Gzip output
	 * 
	 * Cuts the bandwith cost down to half.
	 * Helps the responce time.
	 */
	function gzipOutput() {
		if (@ob_start('ob_gzhandler')) {
			header('Content-type: text/html; charset=UTF-8');
			header('Cache-Control: must-revalidate');
			$offset = -1;
			$expireTime = gmdate('D, d M Y H:i:s', time() + $offset);
			$expireHeader = "Expires: $expireTime GMT";
			header($expireHeader);
		}
	}
	
	/**
	 * Test if we have the connection to the database
	 *
	 * @return bool
	 */
	private function _assertDatabaseConnection() {
	    if (Configure::read('debug') < 1) {
	        return true;
	    }
	    
	    $db = @ConnectionManager::getDataSource('default');
	    if ($db->connected) {
	        return true;
	    }
	    
	    $this->_isDatabaseConnected = false;
	    $this->set('database_config', $db->config);
        $this->render('/errors/no_database', 'no_database');
	    exit();
	}
	
	/**
	 * @TODO duplicate in AppHelper
	 * Returns a string with all spaces converted to $replacement and non word characters removed.
	 *
	 * @param string $string
	 * @param string $replacement
	 * @return string
	 * @static
	 */
    static function slug($string, $replacement = '-') {
    	$string = trim($string);
        $map = array(
            '/à|á|å|â|ä/' => 'a',
            '/è|é|ê|ẽ|ë/' => 'e',
            '/ì|í|î/' => 'i',
            '/ò|ó|ô|ø/' => 'o',
            '/ù|ú|ů|û/' => 'u',
            '/ç|č/' => 'c',
            '/ñ|ň/' => 'n',
            '/ľ/' => 'l',
            '/ý/' => 'y',
            '/ť/' => 't',
            '/ž/' => 'z',
            '/š/' => 's',
            '/æ/' => 'ae',
            '/ö/' => 'oe',
            '/ü/' => 'ue',
            '/Ä/' => 'Ae',
            '/Ü/' => 'Ue',
            '/Ö/' => 'Oe',
            '/ß/' => 'ss',
            '/[^\w\s]/' => ' ',
            '/\\s+/' => $replacement,
            String::insert('/^[:replacement]+|[:replacement]+$/', 
            array('replacement' => preg_quote($replacement, '/'))) => '',
        );
        $string = preg_replace(array_keys($map), array_values($map), $string);
        return low($string);
    }
    
    function wf_get_fields() {
        if (Configure::read('debug') < 1) {
            return;
        }

        $output = '';
        foreach ($this->{$this->modelClass}->schema() as $name => $column) {
            $output .= "'$name' => array(";
            
            // Fields
            foreach ($column as $field => $value) {
                if (is_null($value) or $value === '') {
                    continue;
                }
                $output .= "'$field' => ";
                $value = str_replace("'", "\'", $value);
                if (!is_numeric($value)) {
                    $value = "'$value'";
                }
                $output .= $value . ', ';
            }
            
            $output .= "),\n";
        }
        
        pr($output);
        die();
    }
    
/**
 * Abstracts cakephp's Email component Send function and sets default values
 *@access public
 *
 *@param string $templateName 	The name of the template to use in sending the message
 *@param string $emailSubject
 *@param array $recipients 	An associative array with keys 'to', 'cc', and 'bcc'. 'cc' and 'bcc' are arrays
 *@param string $from
 *@param string $replyToEmail
 *@param string $sendAs
 *
 *@return boolean 	true if mail is successfully sent
 */	

	function _sendEmail($templateName,
			    $emailSubject,
			    $recipients = array(),
			    $from = null,
			    $sendAs = 'both',
			    $replyToEmail = null
			    ){
		
		if(!empty($recipients['to']))
			$this->Email->to = $recipients['to'];

		if(!empty($recipients['cc']))
			$this->Email->cc = $recipients['cc'];

		if(!empty($recipients['bcc']))
			$this->Email->bcc = $recipients['bcc'];
		
		$this->Email->delivery = Configure::read('Wildflower.settings.email_delivery');
		
    		if ($this->Email->delivery == 'smtp') {
        		$this->Email->smtpOptions = array(
                    'username' => Configure::read('Wildflower.settings.smtp_username'),
                    'password' => Configure::read('Wildflower.settings.smtp_password'),
                    'host' => Configure::read('Wildflower.settings.smtp_server'),
        		    'port' => 25, // @TODO add port to settings
        		    'timeout' => 30
        		);
		}
		
		$this->Email->subject = $emailSubject;
		
		if(!empty($from)){
			$this->Email->from = $from;
		} else {
			$this->Email->from = Configure::read('Wildflower.settings.contact_email');
		}
		
		if(empty($replyToEmail)){
			$this->Email->replyTo = $from;
		} else {
			$this->Email->replyTo = $replyToEmail;
		}
		
		$this->Email->from = $from;
		$this->Email->template = $templateName;
		$this->Email->sendAs = $sendAs;
		
		return $this->Email->send();
	}    
	
	/*
	 * Build ACL
	 * 
	 */
	function build_acl() {
		if (!Configure::read('debug')) {
			return $this->_stop();
		}
		$log = array();

		$aco =& $this->Acl->Aco;
		$root = $aco->node('controllers');
		if (!$root) {
			$aco->create(array('parent_id' => null, 'model' => null, 'alias' => 'controllers'));
			$root = $aco->save();
			$root['Aco']['id'] = $aco->id; 
			$log[] = 'Created Aco node for controllers';
		} else {
			$root = $root[0];
		}   

		App::import('Core', 'File');
		$Controllers = Configure::listObjects('controller');
		$appIndex = array_search('App', $Controllers);
		if ($appIndex !== false ) {
			unset($Controllers[$appIndex]);
		}
		$baseMethods = get_class_methods('Controller');
		$baseMethods[] = 'build_acl';

		$Plugins = $this->_getPluginControllerNames();
		$Controllers = array_merge($Controllers, $Plugins);

		// look at each controller in app/controllers
		foreach ($Controllers as $ctrlName) {
			$methods = $this->_getClassMethods($this->_getPluginControllerPath($ctrlName));

			// Do all Plugins First
			if ($this->_isPlugin($ctrlName)){
				$pluginNode = $aco->node('controllers/'.$this->_getPluginName($ctrlName));
				if (!$pluginNode) {
					$aco->create(array('parent_id' => $root['Aco']['id'], 'model' => null, 'alias' => $this->_getPluginName($ctrlName)));
					$pluginNode = $aco->save();
					$pluginNode['Aco']['id'] = $aco->id;
					$log[] = 'Created Aco node for ' . $this->_getPluginName($ctrlName) . ' Plugin';
				}
			}
			// find / make controller node
			$controllerNode = $aco->node('controllers/'.$ctrlName);
			if (!$controllerNode) {
				if ($this->_isPlugin($ctrlName)){
					$pluginNode = $aco->node('controllers/' . $this->_getPluginName($ctrlName));
					$aco->create(array('parent_id' => $pluginNode['0']['Aco']['id'], 'model' => null, 'alias' => $this->_getPluginControllerName($ctrlName)));
					$controllerNode = $aco->save();
					$controllerNode['Aco']['id'] = $aco->id;
					$log[] = 'Created Aco node for ' . $this->_getPluginControllerName($ctrlName) . ' ' . $this->_getPluginName($ctrlName) . ' Plugin Controller';
				} else {
					$aco->create(array('parent_id' => $root['Aco']['id'], 'model' => null, 'alias' => $ctrlName));
					$controllerNode = $aco->save();
					$controllerNode['Aco']['id'] = $aco->id;
					$log[] = 'Created Aco node for ' . $ctrlName;
				}
			} else {
				$controllerNode = $controllerNode[0];
			}

			//clean the methods. to remove those in Controller and private actions.
			foreach ($methods as $k => $method) {
				if (strpos($method, '_', 0) === 0) {
					unset($methods[$k]);
					continue;
				}
				if (in_array($method, $baseMethods)) {
					unset($methods[$k]);
					continue;
				}
				$methodNode = $aco->node('controllers/'.$ctrlName.'/'.$method);
				if (!$methodNode) {
					$aco->create(array('parent_id' => $controllerNode['Aco']['id'], 'model' => null, 'alias' => $method));
					$methodNode = $aco->save();
					$log[] = 'Created Aco node for '. $method;
				}
			}
		}
		if(count($log)>0) {
			debug($log);
		}
	}

	function _getClassMethods($ctrlName = null) {
		App::import('Controller', $ctrlName);
		if (strlen(strstr($ctrlName, '.')) > 0) {
			// plugin's controller
			$num = strpos($ctrlName, '.');
			$ctrlName = substr($ctrlName, $num+1);
		}
		$ctrlclass = $ctrlName . 'Controller';
		$methods = get_class_methods($ctrlclass);

		// Add scaffold defaults if scaffolds are being used
		$properties = get_class_vars($ctrlclass);
		if (array_key_exists('scaffold',$properties)) {
			if($properties['scaffold'] == 'admin') {
				$methods = array_merge($methods, array('admin_add', 'admin_edit', 'admin_index', 'admin_view', 'admin_delete'));
			} else {
				$methods = array_merge($methods, array('add', 'edit', 'index', 'view', 'delete'));
			}
		}
		return $methods;
	}

	function _isPlugin($ctrlName = null) {
		$arr = String::tokenize($ctrlName, '/');
		if (count($arr) > 1) {
			return true;
		} else {
			return false;
		}
	}

	function _getPluginControllerPath($ctrlName = null) {
		$arr = String::tokenize($ctrlName, '/');
		if (count($arr) == 2) {
			return $arr[0] . '.' . $arr[1];
		} else {
			return $arr[0];
		}
	}

	function _getPluginName($ctrlName = null) {
		$arr = String::tokenize($ctrlName, '/');
		if (count($arr) == 2) {
			return $arr[0];
		} else {
			return false;
		}
	}

	function _getPluginControllerName($ctrlName = null) {
		$arr = String::tokenize($ctrlName, '/');
		if (count($arr) == 2) {
			return $arr[1];
		} else {
			return false;
		}
	}

/**
 * Get the names of the plugin controllers ...
 * 
 * This function will get an array of the plugin controller names, and
 * also makes sure the controllers are available for us to get the 
 * method names by doing an App::import for each plugin controller.
 *
 * @return array of plugin names.
 *
 */
	function _getPluginControllerNames() {
		App::import('Core', 'File', 'Folder');
		$paths = Configure::getInstance();
		$folder =& new Folder();
		$folder->cd(APP . 'plugins');

		// Get the list of plugins
		$Plugins = $folder->read();
		$Plugins = $Plugins[0];
		$arr = array();

		// Loop through the plugins
		foreach($Plugins as $pluginName) {
			// Change directory to the plugin
			$didCD = $folder->cd(APP . 'plugins'. DS . $pluginName . DS . 'controllers');
			// Get a list of the files that have a file name that ends
			// with controller.php
			$files = $folder->findRecursive('.*_controller\.php');

			// Loop through the controllers we found in the plugins directory
			foreach($files as $fileName) {
				// Get the base file name
				$file = basename($fileName);

				// Get the controller name
				$file = Inflector::camelize(substr($file, 0, strlen($file)-strlen('_controller.php')));
				if (!preg_match('/^'. Inflector::humanize($pluginName). 'App/', $file)) {
					if (!App::import('Controller', $pluginName.'.'.$file)) {
						debug('Error importing '.$file.' for plugin '.$pluginName);
					} else {
						/// Now prepend the Plugin name ...
						// This is required to allow us to fetch the method names.
						$arr[] = Inflector::humanize($pluginName) . "/" . $file;
					}
				}
			}
		}
		return $arr;
	}
	
}