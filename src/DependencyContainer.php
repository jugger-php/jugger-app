<?php

namespace jugger;

/**
 * Контейнер зависимостей
 *
 * Пример использования:
 * 	$di = new \jugger\DependencyContainer();
 * 	$di->setClass('\jugger\Router', '\jugger\DefaultRouter');
 * 	$di->setSingleton('\jugger\Router', new DefaultRouter);
 * 	$di->setClass('\jugger\Router', function($di){
 * 		$firstClass = $di->createClass('FirstClass');
 * 		return new SecondClass($firstClass);
 * 	});
 */
class DependencyContainer
{
	const TYPE_OTHER = 1;
	const TYPE_SINGLETON = 2;

	protected $classes = [];
	protected $singletonEntities = [];

	public function setClass(string $abstractClass, $builder, int $type = null)
	{
		$abstractClass = ltrim($abstractClass, '\\');
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

	public function createClass(string $className)
	{
		$config = $this->classes[$className] ?? null;
		if (!$config) {
			return null;
		}

		$object = null;
		if ($config['type'] === self::TYPE_SINGLETON) {
			$singleton = $this->singletonEntities[$className] ?? null;
			if (!$singleton) {
				$singleton = $this->getObjectByBuilder($config['builder']);
				$this->singletonEntities[$className] = $singleton;
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
