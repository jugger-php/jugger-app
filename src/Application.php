<?php

namespace jugger;

class Application
{
	protected $di;
	protected $params;
	protected $rootDir;

	public function __construct(DependencyContainer $di, array $params = [])
	{
		$this->params = $params;
		$this->setDi($di);
	}

	public function getParam(string $name)
	{
		return $this->params[$name] ?? null;
	}

	public function setDi(DependencyContainer $di)
	{
		$this->di = $di;
	}

	public function getDi()
	{
		return $this->di;
	}

	public function setRootDir(string $dir)
	{
		$this->rootDir = $dir;
	}

	public function getRootDir()
	{
		return $this->rootDir;
	}

	public function run(string $route, array $params, $data = null)
	{
		$di = $this->getDi();

		$request = $di->createClass(Request::class);
		$request->setParams($params);
		$request->setData($data);

		return $this->runByRequest($route, $request);
	}

	public function runByRequest(string $route, Request $request)
	{
		$di = $this->getDi();
		try {
			$router = new Router($this->rootDir);
			$actionClass = $router->getActionClass($route);
			if (!$actionClass) {
				throw new \Exception("Не удалось найти экшон по маршруту '{$route}'");
			}

			$reflectionClass = new \ReflectionClass($actionClass);
			if (!$reflectionClass->isSubclassOf(Action::class)) {
				throw new \Exception("Действие должно реализовывать класс ". Action::class);
			}

			$action = new $actionClass($this->di, $request);
			$response = $action->run();
			$response->send();
		}
		catch (\Throwable $e) {
			$errorHanlder = $di->createClass(ErrorHandler::class);
			if ($errorHanlder) {
				$errorHanlder->process($e);
			}
			else {
				echo "<pre>";
				throw $e;
			}
		}
	}

	public function checkDependencyContainer()
	{
		$obj = $this->di->createClass(Response::class);
		if (!$obj) {
			throw new \Exception("В контейнере должна быть реализация класса ". Response::class);
		}

		$obj = $this->di->createClass(Request::class);
		if (!$obj) {
			throw new \Exception("В контейнере должна быть реализация класса ". Request::class);
		}

		$obj = $this->di->createClass(UrlRewriter::class);
		if (!$obj) {
			throw new \Exception("В контейнере должна быть реализация класса ". UrlRewriter::class);
		}
	}
}
