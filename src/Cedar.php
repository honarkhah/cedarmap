<?php
namespace Cedar;

use Cedar\Exception\InvalidVersion;
use Cedar\Exception\ServiceNotSupported;

class Cedar{
	protected $config;
	protected $version;

	public function __construct($version = 'default'){
		$this->config = config('cedar');
		if (empty($this->config['versions'][$version])) {
			throw new InvalidVersion("Version {$version} Not Supported!");
		}

		$this->version = $version;
	}

	public function load($service){
		if (empty($this->config['versions'][$this->version]['services'][$service])) {
			throw new ServiceNotSupported("Service {$service} Not Supported In Version {$this->version}!");
		}

		$serviceConfig = $this->config['versions'][$this->version]['services'][$service];

		$className = '\\Cedar\\Services\\' . ucfirst(camel_case($service));

		if (!class_exists($className)) {
			$className = '\\Cedar\\Service';
		}

		$class = new $className($service);
		
		return $class
					->setVersion($this->version)
					->setConfig($this->config)
					->build();
	}
}