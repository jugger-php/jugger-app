<?php

namespace jugger\implement;

use jugger\Response;

class RawResponse extends Response
{
	public function getContent()
	{
		return $this->getData();
	}

	public function send()
	{
		echo $this->getContent();
	}
}
