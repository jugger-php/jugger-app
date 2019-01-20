<?php

namespace jugger;

abstract class Action
{
	protected $di;
	protected $request;

	public function __construct(DependencyContainer $di, Request $request)
	{
		$this->di = $di;
		$this->request = $request;
	}

	abstract public function runInternal();

	final public function run(): Response
	{
		$response = $this->runInternal();
		if ($response instanceof Response) {
			return $response;
		}
		else {
			$obj = $this->di->createClass(Response::class);
			$obj->setData($response);
			return $obj;
		}
	}
}
