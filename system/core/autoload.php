<?php
/*
+--------------------------------------------------------------------------
|   WeCenter [#RELEASE_VERSION#]
|   ========================================
|   by WeCenter Software
|   © 2011 - 2014 WeCenter. All Rights Reserved
|   http://www.wecenter.com
|   ========================================
|   Support: WeCenter@qq.com
|
+---------------------------------------------------------------------------
*/

class core_autoload
{
	public static $loaded_class = array();

	private static $aliases = array(
		'TPL'				=> 'class/cls_template.inc.php',
		'FORMAT'			=> 'class/cls_format.inc.php',
		'UF'				=> 'class/cls_user_format.inc.php',
		'HTTP'				=> 'class/cls_http.inc.php',
		'H'					=> 'class/cls_helper.inc.php',
	);

	public function __construct()
	{
		set_include_path(AWS_PATH);

		foreach (self::$aliases AS $key => $val)
		{
			self::$aliases[$key] = AWS_PATH . $val;
		}

		spl_autoload_register(array($this, 'loader'));
	}

    private static function loader($class_name)
	{
		if (preg_match('#[^a-zA-Z0-9_\\\\]#', $class_name))
		{
			return false;
		}
		
		$require_file = AWS_PATH . str_replace(array('_', '\\'), '/', $class_name) . '.php';

		if (file_exists($require_file))
		{
			$class_file_location = $require_file;
		}
		else
		{
			if (isset(self::$aliases[$class_name]))
			{
				$class_file_location = self::$aliases[$class_name];
			}
			// 查找 models 目录
			else if (file_exists(ROOT_PATH . 'models/' . str_replace(array('_class', '_'), array('', '/'), $class_name) . '.php'))
			{
				$class_file_location = ROOT_PATH . 'models/' . str_replace(array('_class', '_'), array('', '/'), $class_name) . '.php';
			}
			// 查找 class
			else if (file_exists(AWS_PATH . 'class/' . $class_name . '.inc.php'))
			{
				$class_file_location = AWS_PATH . 'class/' . $class_name . '.inc.php';
			}
		}

		if ($class_file_location)
		{
			require $class_file_location;

			self::$loaded_class[$class_name] = $class_file_location;

			if ($class_name == 'TPL')
			{
				TPL::initialize();
			}

			return true;
		}
	}
}