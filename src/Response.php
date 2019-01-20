<?php

namespace jugger;

class Response
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

	/**
	 * Возвращает данные с учетом типа (raw, json, xml, ...)
	 */
	public function getContent()
	{
		return $this->data;
	}
}
