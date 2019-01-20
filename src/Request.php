<?php

namespace jugger;

class Request
{
	protected $data;
    protected $params;

	public function setParams(array $params)
	{
		$this->params = $params;
	}

	public function getParams()
	{
		return $this->params ?? [];
	}

	public function setData($data)
	{
		$this->data = $data;
	}

	public function getData()
	{
		return $this->data;
	}
}
