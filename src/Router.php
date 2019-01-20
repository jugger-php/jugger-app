<?php

namespace jugger;

class Router
{
	protected $rootDir;

	public function __construct(string $rootDir)
	{
		$this->rootDir = $rootDir;
	}

	/**
	 * Роутер должен быть в формате '/moduleName/path/to/ActionClassName'
	 * и преобразовываться в '/moduleName/path/to/ActionClassName.php'
	 * @param  string $route путь до экшона
	 */
	public function getActionClass(string $route): ?string
	{
		$route = $this->normolizeRoute($route);
		list($actionPath, $actionClass) = $this->parse($route);
		if ($actionClass && file_exists($actionPath)) {
			include_once $actionPath;
			if (class_exists($actionClass)) {
				return $actionClass;
			}
		}
		return null;
	}

	public function normolizeRoute(string $route)
	{
		return trim($route, '/');
	}

	public function parse(string $route)
	{
		$parts = explode('/', $route);
		if (count($parts) < 2) {
			throw new \Exception("Указан некорректный путь до экшона");
		}

		$moduleName = array_shift($parts);
		$actionName = array_pop($parts);

		$partsPath = join('/', $parts);
		$basePath = realpath($this->rootDir.'/modules');
		$actionPath = realpath("{$basePath}/{$moduleName}/actions/{$partsPath}/{$actionName}.php");
		if (!$actionPath) {
			// возвращаем пустой массив, чтобы можно было корректно обрабатывать 404 ошибку
			return [];
		}
		else if (strpos($actionPath, $basePath) !== 0) {
			throw new \Exception("Указан некорректный формат пути до экшона");
		}

		$actionNamespace = join('\\', $parts);
		$actionClass = str_replace('\\\\', '\\', "app\\modules\\{$moduleName}\\actions\\{$actionNamespace}\\{$actionName}");
		return [$actionPath, $actionClass];
	}
}
