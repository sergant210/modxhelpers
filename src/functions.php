<?php
require_once __DIR__ . '/classes.php';
require_once __DIR__ . '/../../../fzaninotto/faker/src/autoload.php';

/***********************************************/
/*              Functions                      */
/***********************************************/
if (!function_exists('url')) {
    /**
     * Формирует Url
     * @param int $id Page id
     * @param string $context Context
     * @param array $arg Ling arguments
     * @param mixed $scheme Scheme
     * <pre>
     *      -1 : (default value) URL is relative to site_url
     *       0 : see http
     *       1 : see https
     *    full : URL is absolute, prepended with site_url from config
     *     abs : URL is absolute, prepended with base_url from config
     *    http : URL is absolute, forced to http scheme
     *   https : URL is absolute, forced to https scheme
     * </pre>
     * @param array $options Option
     * @return string
     */
    function url($id, $context = '', $arg = array(), $scheme = -1, array $options = array())
    {
        global $modx;
        return $modx->makeUrl($id, $context, $arg, $scheme, $options);
    }
}
if (!function_exists('redirect')) {
    /**
     * Redirect to the specified url or page
     * @param string|int $url Url or page id
     * @param array|string|bool $options Options
     * @param string $type
     * @param string $responseCode
     */
    function redirect($url, $options = false, $type = '', $responseCode = '')
    {
        global $modx;
        if (is_numeric($url)) {
            if (is_string($options)) {
                $ctx = $options;
            } else {
                $ctx = isset($options['context']) ? $options['context'] : '';
            }
            $url = url($url, $ctx);
        }
        if (!empty($url)) $modx->sendRedirect($url, $options, $type, $responseCode);
    }
}
if (!function_exists('forward')) {
    /**
     * Forwards the request to another resource without changing the URL.
     *
     * @param integer $id The resource identifier.
     * @param string $options An array of options for the process.
     */
    function forward($id, $options = null)
    {
        global $modx;

        if (!empty($id)) $modx->sendForward($id, $options);
    }
}
if (!function_exists('abort')) {
    /**
     * Send to the error page or to the unauthorized page
     * @param array|int $options Options or response code - 401,403,404
     */
    function abort($options = null)
    {
        global $modx;
        if (!is_array($options) && is_numeric($options)) {
            $error_type = (int) $options;
        } elseif (is_array($options) && isset($options['error_type'])) {
            $error_type = $options['error_type'];
        } else {
            $error_type = 404;
        }
        switch ($error_type) {
            case 401:
            case 403:
                $modx->sendUnauthorizedPage($options);
                break;
            case 404:
            default:
                $modx->sendErrorPage($options);
        }
    }
}
if (!function_exists('config')) {
    /**
     * Gets and sets the system settings
     * @param string|array $key
     * @param null|mixed $default
     * @return array|null|string
     */
    function config($key = '', $default = '')
    {
        global $modx;
        if (!empty($key)) {
            if (is_array($key)) {
                if (!can('settings')) return false;
                foreach ($key as $itemKey => $itemValue) {
                    $modx->config[$itemKey] = $itemValue;
                }
                return true;
            }
            return isset($modx->config[$key]) ? $modx->config[$key] : $default;
        } else {
            return $modx->config;
        }
    }
}
if (!function_exists('session')) {
    /**
     * Manages the session
     * @param string $key Use the dot notation.
     * @param string|null $value Value or NULL.
     * @return mixed
     */
    function session($key = '', $value = '')
    {
        if (empty($key)) {
            return $_SESSION;
        }
        $delete = is_null($value);
        if (!empty($value) || $delete) {
            $keys = explode('.', $key);
            if (count($keys) == 1) {
                $rootKey = array_shift($keys);
                $_SESSION[$rootKey] = $value;
            } else {
                $_key = array_shift($keys);
                if (!isset($rootKey)) $rootKey = $_key;
                if (! isset($array[$key]) || ! is_array($array[$key])) {
                    $_SESSION[$_key] = array();
                }
                $array =& $_SESSION[$_key];
                while (count($keys) > 1) {
                    $_key = array_shift($keys);
                    $array[$_key] = array();
                    $array = &$array[$_key];
                }
                $array[array_shift($keys)] = $delete ? null : $value;
            }
            return $_SESSION[$rootKey];
        }
        if (isset($_SESSION[$key])) {
            return $_SESSION[$key];
        }
        $array = $_SESSION;
        foreach (explode('.', $key) as $segment) {
            if (isset($array[$segment])) {
                $array = $array[$segment];
            } else {
                return '';
            }
        }

        return $array;
    }
}
if (!function_exists('cache')) {
    /**
     * Manages the cache
     * @see https://docs.modx.com/revolution/2.x/developing-in-modx/advanced-development/caching
     * @param string|array $key
     * @param null|int|string|array $options
     * @return mixed|modCacheManager
     */
    function cache($key = '', $options = NULL)
    {
        global $modx;
        if (func_num_args() == 0) {
            return new extCacheManager($modx->getCacheManager());
        }
        if (is_string($options)) {
            $options = array(xPDO::OPT_CACHE_KEY => $options);
        } elseif (is_numeric($options)) {
            $options = array(xPDO::OPT_CACHE_EXPIRES => (int) $options);
        }
        if (is_array($key)) {
            foreach ($key as $itemKey => $itemValue) {
                $lifetime = isset($options[xPDO::OPT_CACHE_EXPIRES]) ? $options[xPDO::OPT_CACHE_EXPIRES] : 0;
                $response = $modx->getCacheManager()->set($itemKey, $itemValue, $lifetime, $options);
            }
            return $response;
        } else {
            return $modx->getCacheManager()->get($key, $options);
        }
    }
}
if (!function_exists('parents')) {
    /**
     * Gets all of the parent resource ids for a given resource.
     * @param int $id
     * @param int $height
     * @param array $options
     * @return array
     */
    function parents($id = null, $height = 10,array $options = array())
    {
        global $modx;
        return $modx->getParentIds($id, $height, $options);
    }
}
if (!function_exists('children')) {
    /**
     * Gets all of the child resource ids for a given resource.
     * @param int $id
     * @param int $depth
     * @param array $options
     * @return array
     */
    function children($id = null, $depth = 10,array $options = array())
    {
        global $modx;
        return $modx->getChildIds($id, $depth, $options);
    }
}

if (!function_exists('pls')) {
    /**
     * Gets/sets placeholders
     * @param string|array $key String to get a placeholder, array ('key'=>'value') - to set one/ones.
     * @param string $default
     * @return array|bool|string
     */
    function pls($key = '', $default = '')
    {
        global $modx;

        if (empty($key)) {
            return $modx->placeholders;
        }
        if (is_array($key)) {
            foreach ($key as $itemKey => $itemValue) {
                $modx->placeholders[$itemKey] = $itemValue;
            }
            return true;
        } else {
            return isset($modx->placeholders[$key]) ? $modx->placeholders[$key] : $default;
        }
    }
}
if (!function_exists('pls_delete')) {
    /**
     * Removes the specified placeholders
     * @param string|array $keys Key/array of keys
     */
    function pls_delete($keys)
    {
        global $modx;
        if (is_array($keys)) {
            $modx->unsetPlaceholders($keys);
        } else {
            $modx->unsetPlaceholder($keys);
        }
    }
}
if (!function_exists('email')) {
    /**
     * Send Email.
     * @param string|array $email Email.
     * @param string|array $subject Subject or an array of options. Required option keys - subject, content. Optional - sender, from, fromName.
     * @param string $content
     * @return bool|modHelperMailer
     */
    function email($email='', $subject='', $content = '')
    {
        global $modx;
        if (func_num_args() == 0) return new modHelperMailer($modx);
        if (is_array($subject)) {
            $options = $subject;
        } else {
            $options = compact('subject','content');
        }
        if (empty($email)) return false;
        $options['sender'] = isset($options['sender']) ? $options['sender'] : $modx->getOption('emailsender');
        $options['from'] = isset($options['from']) ? $options['from'] : $modx->getOption('emailsender');
        $options['fromName'] = isset($options['fromName']) ? $options['emailFromName'] : $modx->getOption('site_name');
        /* @var modPHPMailer $mail */
        $mail = $modx->getService('mail', 'mail.modPHPMailer');
        $mail->setHTML(true);
        $mail->set(modMail::MAIL_SUBJECT, $options['subject']);
        $mail->set(modMail::MAIL_BODY, $options['content']);
        $mail->set(modMail::MAIL_SENDER, $options['sender']);
        $mail->set(modMail::MAIL_FROM, $options['from']);
        $mail->set(modMail::MAIL_FROM_NAME, $options['fromName']);
        if (!empty($options['cc'])) $mail->address('cc', $options['cc']);
        if (!empty($options['bcc'])) $mail->address('bcc', $options['bcc']);
        if (!empty($options['replyTo'])) $mail->address('reply-to', $options['replyTo']);
        if (!empty($options['attach'])) {
            if (is_array($options['attach'])) {
                foreach ($options['attach'] as $name => $file) {
                    if (!is_string($name)) $name = '';
                    $mail->attach($file, $name);
                }
            } else {
                $mail->attach($options['attach']);
            }
        }

        if (is_array($email)) {
            foreach ($email as $e) {
                $mail->address('to', $e);
            }
        } else {
            $mail->address('to', $email);
        }
        if (!$mail->send()) {
            $modx->log(modX::LOG_LEVEL_ERROR, 'An error occurred while trying to send the email: ' . $mail->mailer->ErrorInfo);
            $mail->reset();
            return false;
        }
        $mail->reset();
        return true;
    }
}
if (!function_exists('email_user')) {
    /**
     * Sends email to the specified user.
     * @param int|string|modUser $user User id or username or user object.
     * @param string|array $subject Magic. Subject or an array of options. Required option keys - subject, content. Optional - sender, from, fromName.
     * @param string $content
     * @return bool
     */
    function email_user($user, $subject, $content = '')
    {
        global $modx;
        if (!is_array($user)) $user = compact('user');
        $email = array();
        foreach ($user as $usr) {
            if (is_numeric($usr)) {
                $usr = $modx->getObject('modUser', array('id' => (int)$usr));
            } elseif (is_string($usr)) {
                $usr = $modx->getObject('modUser', array('username' => $usr));
            }
            if ($usr instanceof modUser && $eml = $usr->Profile->get('email')) $email[] = $eml;
        }
        return !empty($email) ? email($email, $subject, $content) : false;
    }
}

if (!function_exists('css')) {
    /**
     * Register CSS to be injected inside the HEAD tag of a resource.
     * @param string $src
     * @param string $media
     */
    function css($src, $media = null)
    {
        global $modx;
        $modx->regClientCSS($src, $media);
    }
}
if (!function_exists('script')) {
    /**
     * Register JavaScript.
     * @param string $src
     * @param bool|string $start Inject inside the HEAD tag of a resource.
     * @param bool|string $plaintext
     * @param bool|string $attr async defer
     */
    function script($src, $start = false, $plaintext = false, $attr = false)
    {
        global $modx;

        switch (true) {
            case is_string($start):
                $attr = $start;
                $start = false;
                $plaintext = true;
                break;
            case is_string($plaintext):
                $attr = $plaintext;
                $plaintext = true;
                break;
            case is_string($attr):
                $plaintext = true;
                break;
        }
        if ($attr) $src = '<script '. $attr .' type="text/javascript" src="' . $src . '"></script>';

        if ($start) {
            $modx->regClientStartupScript($src, $plaintext);
        } else {
            $modx->regClientScript($src, $plaintext);
        }
    }
}
if (!function_exists('html')) {
    /**
     * Register HTML
     * @param string $src
     * @param bool $start Inject inside the HEAD tag of a resource.
     */
    function html($src, $start = false)
    {
        $plaintext = true;
        script($src, $start, $plaintext);
    }
}
if (!function_exists('lang')) {
    /**
     * Grabs a processed lexicon string.
     * @param $key
     * @param array $params
     * @param string $language
     * @return null|string
     */
    function lang($key, $params = array(), $language = '')
    {
        global $modx;
        return $modx->lexicon($key, $params, $language);
    }
}
if (!function_exists('chunk')) {
    /**
     * Process and return the output from a Chunk by name.
     * @param $chunkName
     * @param array $properties
     * @return string
     */
    function chunk($chunkName, array $properties= array ())
    {
        global $modx;
        $output = '';
        //$store = isset($modx->getCacheManager()->store) ? $modx->getCacheManager()->store : array('modChunk'=>array());
        if (strpos($chunkName, '/') !== false && file_exists($chunkName)) {
            $content = file_get_contents($chunkName);
            /** @var modChunk $chunk */
            $chunk = $modx->newObject('modChunk', array('name' => basename($chunkName)));
            $chunk->_cacheable = false;
            $chunk->_processed = false;
            $chunk->_content = '';
            $output = $chunk->process($properties, $content);
            /*        } elseif ($pdo = pdotools()) {
                        $output = $pdo->getChunk($chunkName, $properties);*/
        } else {
            $output = $modx->getChunk($chunkName, $properties);
        }
        return $output;
    }
}
if (!function_exists('snippet')) {
    /**
     * Runs the specified MODX snippet or file.
     * @param string $snippetName
     * @param array $scriptProperties
     * @param int|string|array $cacheOptions
     * @return string
     */
    function snippet($snippetName, array $scriptProperties = array (), $cacheOptions = array())
    {
        $result = cache($snippetName);
        if (isset($result)) {
            return $result;
        }
        global $modx;
        if (strpos($snippetName, '/') !== false && file_exists($snippetName)) {
            ob_start();
            extract($scriptProperties, EXTR_SKIP);
            $result = include $snippetName;
            $result = ($result === null ? '' : $result);
            if (ob_get_length()) {
                $result = ob_get_contents() . $result;
            }
            ob_end_clean();
            /*        } elseif ($pdo = pdotools()) {
                        $result =  $pdo->runSnippet($snippetName, $scriptProperties);*/
        } else {
            $result = $modx->runSnippet($snippetName, $scriptProperties);
        }
        if (!empty($cacheOptions)) {
            cache(array($snippetName => $result), $cacheOptions);
        }
        return $result;
    }
}
if (!function_exists('processor')) {
    /**
     * Runs the specified processor.
     * @param string $action
     * @param array $scriptProperties
     * @param array $options
     * @return mixed
     */
    function processor($action = '',$scriptProperties = array(),$options = array())
    {
        global $modx;
        return $modx->runProcessor($action, $scriptProperties, $options);
    }
}
if (!function_exists('object')) {
    /**
     * Gets an object of the specified class.
     * @param string $class
     * @param integer|array $criteria
     * @return ObjectManager
     */
    function object($class, $criteria = null)
    {
        global $modx;
        $object = new ObjectManager($modx, $class);
        if (isset($criteria)) {
            if (is_numeric($criteria)) {
                $pk = $modx->getPK($class);
                $where = array($pk => (int) $criteria);
            } elseif (is_array($criteria)) {
                $where = $criteria;
            }
            if (isset($where)) {
                $object->where($where);
            }
        }
        return $object;
    }
}
if (!function_exists('collection')) {
    /**
     * Gets a collection of the specified class.
     * @param string $class
     * @param array $criteria
     * @return CollectionManager
     */
    function collection($class = '', $criteria = null)
    {
        global $modx;
        $collection = new CollectionManager($modx, $class);
        if (isset($criteria) && is_array($criteria)) {
            $collection->where($criteria);
        }
        return $collection;
    }
}
if (!function_exists('resource')) {
    /**
     * Gets a resource object/array.
     * @param int|array $criteria Resource id or array with criteria.
     * @param bool $asObject True to return an object. Otherwise - an array.
     * @return array|modResource|bool|ObjectManager
     */
    function resource($criteria = null, $asObject = true)
    {
        /** @var ObjectManager $resourceManager */
        if (is_numeric($criteria)) {
            $criteria = array('id' => (int) $criteria);
        }
        $resourceManager = object('modResource', $criteria);
        if (!isset($criteria)) return $resourceManager;

        return $asObject ? $resourceManager->get() : $resourceManager->toArray();
    }
}
if (!function_exists('resources')) {
    /**
     * Gets a collection of the resources.
     * @param array $criteria Criteria
     * @param bool $asObject True to return an array of the objects. Otherwise - an array of resources data arrays.
     * @return array|bool|CollectionManager
     */
    function resources($criteria = null, $asObject = false)
    {
        global $modx;
        /** @var CollectionManager $collection */
        $collection = collection('modResource');

        if (!isset($criteria)) {
            return $collection;
        }
        if (is_array($criteria)) {
            $select = isset($criteria['select']) ? $criteria['select'] : $modx->getSelectColumns('modResource');
            $collection->select($select);
            if (isset($criteria['sortby'])) {
                list($sortby, $sortdir) = explode(',', $criteria['sortby']);
                $sortdir = is_null($sortdir) ? 'ASC' : trim($sortdir);
                $collection->sortby($sortby, $sortdir);
            }
            if (isset($criteria['limit'])) {
                list($limit, $offset) = explode(',', $criteria['limit']);
                $offset = is_null($offset) ? 0 : $offset;
                $collection->limit($limit, $offset);
            }
            $complex = isset($criteria['select']) || isset($criteria['sortby']) || isset($criteria['limit']) || isset($criteria['where']);
            if ($complex) {
                $where = isset($criteria['where']) ? $criteria['where'] : false;
            } else {
                $where = $criteria;
            }
            if ($where) $collection->where($where);
        }
        if (!isset($criteria)) return $collection;

        return $asObject ? $collection->get() : $collection->toArray();
    }
}

if (!function_exists('user')) {
    /**
     * Gets a user object.
     * @param int|string|array $criteria User id, username or array.
     * @param bool $asObject True to return an object. Otherwise - an array.
     * @return array|modUser
     */
    function user($criteria = null, $asObject = true)
    {
        /** @var ObjectManager $userManager */
        if (is_numeric($criteria)) {
            $criteria = array('id' => (int) $criteria);
        } elseif (is_string($criteria)) {
            $criteria = array('username' => $criteria);
        }
        $userManager = object('modUser', $criteria);

        return (isset($criteria) && $asObject) ? $userManager->get() : $userManager->toArray();
    }
}
if (!function_exists('users')) {
    /**
     * @param array $criteria
     * @param bool $asObject True to return an array of the user objects. Otherwise - an array of users data arrays.
     * @return array|CollectionManager
     */
    function users($criteria = null, $asObject = false)
    {
        global $modx;
        /** @var CollectionManager $collection */
        $collection = collection('modUser');

        if (!isset($criteria)) {
            return $collection;
        }
        if (is_array($criteria)) {
            $select = isset($criteria['select']) ? $criteria['select'] : $modx->getSelectColumns('modUser');
            $collection->select($select);
            if (isset($criteria['sortby'])) {
                list($sortby, $sortdir) = explode(',', $criteria['sortby']);
                $sortdir = is_null($sortdir) ? 'ASC' : trim($sortdir);
                $collection->sortby($sortby, $sortdir);
            }
            if (isset($criteria['limit'])) {
                list($limit, $offset) = explode(',', $criteria['limit']);
                $offset = is_null($offset) ? 0 : $offset;
                $collection->limit($limit, $offset);
            }
            $complex = isset($criteria['select']) || isset($criteria['sortby']) || isset($criteria['limit']) || isset($criteria['where']);
            if ($complex) {
                $where = isset($criteria['where']) ? $criteria['where'] : false;
            } else {
                $where = $criteria;
            }
            if ($where) $collection->where($where);
        }
        return $asObject ? $collection->get() : $collection->toArray();
    }
}
if (!function_exists('is_auth')) {
    /**
     * Determines if this user is authenticated in a specific context or current context.
     * @param string $ctx
     * @return bool
     */
    function is_auth($ctx = '')
    {
        global $modx;
        if (empty($ctx)) $ctx = $modx->context->get('key');
        return ($modx->user->id > 0) ? $modx->user->isAuthenticated($ctx) : false;
    }
}
if (!function_exists('is_guest')) {
    /**
     * Checks the user is guest.
     * @return bool
     */
    function is_guest()
    {
        global $modx;
        return $modx->user->id == 0;
    }
}
if (!function_exists('can')) {
    /**
     * Returns true if user has the specified policy permission.
     * @param string $pm Permission
     * @return bool
     */
    function can($pm)
    {
        global $modx;

        return $modx->hasPermission($pm);
    }
}
if (!function_exists('quote')) {
    /**
     * Quote the string.
     * @see http://php.net/manual/ru/function.pdo-quote.php
     * @param string $string
     * @param int $parameter_type
     * @return string
     */
    function quote($string, $parameter_type = PDO::PARAM_STR)
    {
        global $modx;

        return $modx->quote($string, $parameter_type);
    }
}
if (!function_exists('escape')) {
    /**
     * Escapes the provided string using the platform-specific escape character.
     * @param string $string
     * @return string
     */
    function escape($string)
    {
        global $modx;

        return $modx->escape($string);
    }
}
if (!function_exists('object_exists')) {
    /**
     * Checks the object existence
     * @param string $className
     * @param int|string|array $criteria
     * @return bool
     */
    function object_exists($className, $criteria = null)
    {
        global $modx;
        if (is_scalar($criteria)) {
            $pk = $modx->getPK($className);
            $criteria = array($pk => $criteria);
        }

        return $modx->getCount($className, $criteria) ? true : false;
    }
}
if (!function_exists('resource_exists')) {
    /**
     * Checks the resource existence
     * @param array $criteria
     * @return bool
     */
    function resource_exists($criteria = null)
    {
        return object_exists('modResource', $criteria);
    }
}
if (!function_exists('user_exists')) {
    /**
     * Checks the user existence.
     * @param array $criteria
     * @return bool
     */
    function user_exists($criteria = null)
    {
        function trimFields ($value) {
            return trim($value,' `');
        }
        function prepareUserFields ($value) {
            return 'modUser.' . $value;
        }
        function prepareProfileFields ($value) {
            return 'Profile.' . $value;
        }
        global $modx;
        $userFields = explode(',', $modx->getSelectColumns('modUser'));
        $userFields = array_map('trimFields',$userFields);
        $fullUserFields = array_map('prepareUserFields',$userFields);

        $profileFields = explode(',', $modx->getSelectColumns('modUserProfile','','',array('id'),true));
        $profileFields = array_map('trimFields',$profileFields);
        $fullProfileFields = array_map('prepareProfileFields',$profileFields);

        $fields = array_merge($userFields, $profileFields);
        $fullFields = array_merge($fullUserFields, $fullProfileFields);
        $query = $modx->newQuery('modUser');
        $query->innerJoin('modUserProfile', 'Profile');
        if (is_numeric($criteria)) {
            $criteria = array('id' => $criteria);
        }
        if (is_array($criteria)) {
            $where = array();
            foreach ($criteria as $key => $value) {
                $key = str_replace($fields,$fullFields, $key);
                $where[$key] = $value;
            }
            $query->where($where);
        }
        $rowCount = null;
        if ($query->prepare() && $query->stmt->execute()) {
            $rowCount = $query->stmt->rowCount();
        }
        return isset($rowCount) ? $rowCount > 0 : $rowCount;
    }
}
if (!function_exists('user_id')) {
    /**
     * Gets id of the current user.
     * @return int
     */
    function user_id()
    {
        global $modx;
        return isset($modx->user) ? $modx->user->id : false;
    }
}
if (!function_exists('res_id')) {
    /**
     * Gets id of the current resource.
     * @return int
     */
    function res_id()
    {
        return resource_id();
    }
}
if (!function_exists('resource_id')) {
    /**
     * Gets id of the current resource.
     * @return int
     */
    function resource_id()
    {
        global $modx;
        return isset($modx->resource) ? $modx->resource->id : false;
    }
}
if (!function_exists('template_id')) {
    /**
     * Gets the template id of the current resource.
     * @return int
     */
    function template_id()
    {
        global $modx;
        return isset($modx->resource) ? $modx->resource->template : false;
    }
}

if (!function_exists('tv')) {
    /**
     * Gets TV of the current resource.
     * @param mixed $id
     * @return null|mixed
     */
    function tv($id)
    {
        global $modx;

        return isset($modx->resource) ? $modx->resource->getTVValue($id) : false;
    }
}

if (!function_exists('str_clean')) {
    /**
     * Sanitize the specified string
     *
     * @param string $str
     * @param string|array $chars Magic. Chars or allowed tags.
     * @param array $allowedTags Allowed tags.
     * @return string .
     */
    function str_clean($str, $chars = '/\'"();><', $allowedTags = array())
    {
        if (is_string($chars)) {
            $chars = str_split($chars);
        } elseif (is_array($chars)) {
            $allowedTags = implode('', $chars);
            $chars = str_split('/\'"();><');
        }
        if (!empty($allowedTags) && is_array($allowedTags)) $allowedTags = implode('', $allowedTags);

        return str_replace($chars, '', strip_tags($str, $allowedTags));
    }
}
if (!function_exists('table_name')) {
    /**
     * Gets the actual run-time table name from a specified class name.
     *
     * @param string $className
     * @param bool $includeDb
     * @return string Название таблицы.
     */
    function table_name($className, $includeDb = false)
    {
        global $modx;
        return $modx->getTableName($className, $includeDb);
    }
}

if (!function_exists('columns')) {
    /**
     * Gets select columns from a specific class for building a query
     *
     * @param string $className Имя класса
     * @param string $tableAlias
     * @param string $columnPrefix
     * @param array $columns
     * @param bool $exclude
     * @return string Колонки.
     */
    function columns($className, $tableAlias = '', $columnPrefix = '', $columns = array (), $exclude= false)
    {
        global $modx;
        return $modx->getSelectColumns($className, $tableAlias, $columnPrefix, $columns, $exclude);
    }
}
if (!function_exists('is_email')) {
    /**
     * Validates the email
     *
     * @param string
     * @return bool
     */
    function is_email($string)
    {
        return preg_match('/^[a-zA-Z0-9_.+-]+@[a-z0-9_-]+(\.[a-z0-9_-]+)*\.[a-z]{2,6}$/',$string);
    }
}
if (!function_exists('is_url')) {
    /**
     * Validates the URL
     *
     * @param string
     * @return bool
     */
    function is_url($string)
    {
        return preg_match('/^((https|http):\/\/)?([a-z0-9]{1})([\w\.]+)\.([a-z]{2,6}\.?)(\/[\w\.]*)*\/?$/',$string);
    }
}
if (!function_exists('log_error')) {
    /**
     * $modx->log(modX::LOG_LEVEL_ERROR,$message)
     *
     * @param string $message
     * @param bool $changeLevel Change log level
     * @param string $target
     */
    function log_error($message, $changeLevel = false, $target = '')
    {
        LogManager::error($message, $changeLevel, $target);
    }
}
if (!function_exists('log_warn')) {
    /**
     * $modx->log(modX::LOG_LEVEL_WARN, $message)
     *
     * @param string $message
     * @param bool $changeLevel Change log level
     * @param string $target
     */
    function log_warn($message, $changeLevel = false, $target = '')
    {
        LogManager::warn($message, $changeLevel, $target);
    }
}
if (!function_exists('log_info')) {
    /**
     * $modx->log(modX::LOG_LEVEL_INFO, $message)
     *
     * @param string $message
     * @param bool $changeLevel Change log level
     * @param string $target
     */
    function log_info($message, $changeLevel = false, $target = '')
    {
        LogManager::info($message, $changeLevel, $target);
    }
}
if (!function_exists('log_debug')) {
    /**
     * $modx->log(modX::LOG_LEVEL_DEBUG, $message)
     *
     * @param string $message
     * @param bool $changeLevel Change log level
     * @param string $target
     */
    function log_debug($message, $changeLevel = false, $target = '')
    {
        LogManager::debug($message, $changeLevel, $target);
    }
}
if (!function_exists('context')) {
    /**
     * Gets the specified property of the current context
     * @param string $key
     * @return string
     */
    function context($key = 'key')
    {
        global $modx;
        return $modx->context->get($key);
    }
}
if (!function_exists('query')) {
    /**
     * Manages a SQL query
     * @param string $query
     * @return QueryManager
     */
    function query($query)
    {
        global $modx;
        return new QueryManager($modx, $query);
    }
}
if (!function_exists('memory')) {
    /**
     * Return the formatted amount of memory allocated to PHP
     * @param string $unit
     * @return string
     */
    function memory($unit = 'KB')
    {
        switch ($unit) {
            case 'byte':
                $value = number_format(memory_get_usage(true), 0,","," ") . " $unit";
                break;
            case 'MB':
                $value = number_format(memory_get_usage(true) / (1024*1024), 0,","," ") . " $unit";
                break;
            case 'KB':
            default:
                $value = number_format(memory_get_usage(true) / 1024, 0,","," ") . " $unit";
        }

        return $value;
    }
}
if (!function_exists('faker')) {
    $Faker = false;
    /**
     * Makes fake data
     * @see https://github.com/fzaninotto/Faker
     * @param string|array $property
     * @param string $locale
     * @return mixed
     */
    function faker($property = '', $locale = '')
    {
        global $Faker;
        if (!$Faker) {
            if (empty($locale)) {
                $lang = config('cultureKey');
                switch ($lang) {
                    case 'ru':
                        $locale = 'ru_RU';
                        break;
                    case 'de':
                        $locale = 'de_DE';
                        break;
                    case 'fr':
                        $locale = 'fr_FR';
                        break;
                    default:
                        $locale = 'en_US';
                }
            }

            $Faker = \Faker\Factory::create($locale);
        }
        if (func_num_args() == 0) return $Faker;

        try {
            if (is_array($property)) {
                $func = key($property);
                $params = current($property);
                $output = call_user_func_array(array($Faker, $func), $params);
            } else {
                $output = $Faker->$property;
            }
        } catch (Exception $e) {
            log_error($e->getMessage());
            $output = '';
        }

        return  $output;
    }
}
if (!function_exists('img')) {
    /**
     * Returns the HTML tag "img".
     * @param string $src
     * @param array $attrs
     * @return string
     */
    function img($src, $attrs = array())
    {
        $attributes = '';
        if (!empty($attrs) && is_array($attrs)) {
            foreach ($attrs as $k => $v) {
                $attributes .= $k . '="' . $v . '" ';
            }
        }
        return '<img src="'. $src.'" ' . $attributes . '>';
    }
}

if (!function_exists('load_model')) {
    /**
     * Load a model for a custom table.
     * @param string $class Class name.
     * @param string|callable $table Table name without the prefix or Closure.
     * @param callable $callback Closure
     * @return bool
     */
    function load_model($class, $table, $callback = NULL)
    {
        global $modx;
        if (func_num_args() == 2 && is_callable($table)) {
            $callback = $table;
            $table = '';
        }
        $key = strtolower($class) . '_map';
        if (config('modHelpers_cache_model', true) && $map = cache($key)) {
            if (!empty($table)) {
                $modx->map[$class] = $map;
            } else {
                $modx->map[$class] = array_merge_recursive($modx->map[$class], $map);
            }
            return true;
        }
        $model = new modHelperModelBuilder($table);
        if (is_callable($callback)) {
            $callback($model);
            $map = $model->output();
            if (!empty($map)) {
                if (!empty($table)) {
                    $modx->map[$class] = $map;
                } else {
                    $modx->map[$class] = array_merge_recursive($modx->map[$class], $map);
                }
                if (config('modHelpers_cache_model', true)) cache()->set($key,$map);
                return true;
            }
        }
        return false;
    }
}
if (!function_exists('login')) {
    /**
     * Logs in the specified user.
     * @param int|modUser $user
     * @return bool
     */
    function login($user)
    {
        global $modx;
        if (is_numeric($user)) $user = user($user);
        if ($user instanceof modUser) {
            $modx->user = $user;
            $modx->user->addSessionContext($modx->context->key);
            return true;
        }
        return false;
    }
}
if (!function_exists('logout')) {
    /**
     * Logs out the current user.
     * @param bool $redirect True to redirect to the unauthorized page.
     * @param int $code Response code
     * @return bool
     */
    function logout($redirect = false, $code = 401)
    {
        global $modx;
        $response = $modx->runProcessor('security/logout');
        if ($response->isError()) {
            $modx->log(modX::LOG_LEVEL_ERROR, 'Logout error of the user: '.$modx->user->get('username').' ('.$modx->user->get('id').').');
            return false;
        }
        $modx->user = $modx->getAuthenticatedUser('mgr');
        if (!is_object($modx->user) || !$modx->user instanceof modUser) {
            if ($redirect) abort($code);
            $modx->user = $modx->newObject('modUser');
            $modx->user->fromArray(array(
                'id' => 0,
                'username' => $modx->getOption('default_username', '', '(anonymous)', true)
            ), '', true);
            $modx->toPlaceholders($modx->user->get(array('id','username')),'modx.user');
        }
        return true;
    }
}
if (!function_exists('is_ajax')) {
    /**
     * Checks request is ajax or not.
     * @return bool
     */
    function is_ajax()
    {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }
}