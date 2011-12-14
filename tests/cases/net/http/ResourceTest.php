<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2011, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace li3_rest\tests\cases\net\http;

use li3_rest\net\http\Resource;

class ResourceTest extends \lithium\test\Unit {

	public function testResource() {
		$routes = Resource::connect('Posts');
		$resource = Resource::config();
		$types = $resource['types'];
		$typeKeys = array_keys($types);

		$this->assertEqual(count($types), count($routes));

		for($i = 0; $i < count($types); $i++) {
			$type = $types[$typeKeys[$i]];
			$route = $routes[$i]->export();
			$this->assertEqual(str_replace('{:resource}', 'posts', $type['template']), $route['template']);
			$this->assertEqual($type['params']['http:method'], $route['meta']['http:method']);
		}
	}

	public function testExcludedResources() {
		$resource = Resource::config();
		$types = $resource['types'];
		$typeKeys = array_keys($types);

		// randomly exclude one
		$options = array('exclude' => array('delete'));
		$routes = Resource::connect('Posts', $options);
		$routeCount = count($routes);

		$this->assertEqual((count($types)-1), $routeCount);

		// make sure it did not get included
		for($i = 0; $i < $routeCount; $i++) {
			$type = $types['delete'];
			$route = $routes[$i]->export();
			$this->assertNotEqual('DELETE', $route['meta']['http:method']);
		}
	}

}

?>