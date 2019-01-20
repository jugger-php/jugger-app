<?php

namespace jugger;

class DependencyContainer
{
	const TYPE_OTHER = 1;
	const TYPE_SINGLETON = 2;

	protected $classes = [];
	protected $singletones = [];

	public function setClass(string $abstractClass, $builder, int $type = null)
	{
		$type = $type === self::TYPE_SINGLETON
			? self::TYPE_SINGLETON
			: self::TYPE_OTHER;
		$this->classes[$abstractClass] = [
			'type' => $type,
			'builder' => $builder,
		];
	}

	public function setSingleton(string $abstractClass, $builder)
	{
		$this->setClass($abstractClass, $builder, self::TYPE_SINGLETON);
	}

	public function getClass(string $className)
	{
		$config = $this->classes[$className] ?? null;
		if (!$config) {
			return null;
		}

		$object = null;
		$singleton = $this->singletones[$className] ?? null;
		if ($config['type'] === self::TYPE_SINGLETON) {
			if (!$singleton) {
				$singleton = $this->getObjectByBuilder($config['builder']);
				$this->singletones[$className] = $singleton;
			}
			$object = $singleton;
		}
		else {
			$object = $this->getObjectByBuilder($config['builder']);
		}
		return $object;
	}

	protected function getObjectByBuilder($builder)
	{
		if (is_string($builder)) {
			return new $builder;
		}
		else if (is_callable($builder)) {
			return call_user_func($builder, $this);
		}
		else if (is_object($builder)) {
			return $builder;
		}
		else {
			throw new \Exception("Builder must be 'string className', 'callback' or 'object'");
		}
	}
}
