<?php

namespace jugger;

class Application
{
	protected $di;
	protected $params;
	protected $rootDir;

	public function __construct(array $params = [], DependencyContainer $di = null)
	{
		$this->params = $params;
		$this->setDi($di);
	}

	public function get(string $name)
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

	public function run(array $params, $data)
	{
		$di = $this->getDi();

		$request = $di->getClass(Request::class);
		$request->setParams($params);
		$request->setData($data);

		return $this->runByRequest($request);
	}

	public function runByRequest(Request $request)
	{
		$di = $this->getDi();
		try {
			$router = $di->getClass(Router::class);
			$route = $router->getRoute($request);
			if (!$route) {
				throw new \Exception("Не удалось выгрузить маршрут из запроса");
			}

			$action = $this->getAction($route);
			if (!$action) {
				throw new \Exception("Не удалось найти экшон по маршруту '{$route}'");
			}

			$action->setRequest($request);
			$response = $action->run();
			$response->send();
		}
		catch (\Throwable $e) {
			$errorHanlder = $di->getClass(ErrorHandler::class);
			if ($errorHanlder) {
				$errorHanlder->process($e);
			}
			else {
				throw $e;
			}
		}
	}

	public function getAction(string $route)
	{
		// поиск по rootDir
	}
}
