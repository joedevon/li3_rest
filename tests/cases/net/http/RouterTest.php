<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2011, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace li3_rest\tests\cases\net\http;

use lithium\action\Request;
use lithium\net\http\Route;
use li3_rest\net\http\Router;
use li3_rest\net\http\Resource;
use lithium\action\Response;

class RouterTest extends \lithium\test\Unit {

	protected $_routes = array();

	public function setUp() {
		$this->request = new Request();
		$this->_routes = Router::get();
		Router::reset();
	}

	public function tearDown() {
		Router::reset();

		foreach ($this->_routes as $route) {
			Router::connect($route);
		}
	}

	public function testResource() {
		Router::resource('Posts');
		$resource = Resource::config();
		$types = $resource['types'];
		$typeKeys = array_keys($types);
		$routes = Router::get();

		$this->assertEqual(count($types), count($routes));

		for($i = 0; $i < count($types); $i++) {
			$type = $types[$typeKeys[$i]];
			$route = $routes[$i]->export();
			$this->assertEqual(str_replace('{:resource}', 'posts', $type['template']), $route['template']);
			$this->assertEqual($type['params']['http:method'], $route['meta']['http:method']);
		}
	}

}

?>