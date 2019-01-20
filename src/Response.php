<?php

namespace jugger;

abstract class Response
{
	protected $data;

	public function setData($data)
	{
		$this->data = $data;
	}

	public function getData()
	{
		return $this->data;
	}

	abstract public function send();

	abstract public function getContent();
}
