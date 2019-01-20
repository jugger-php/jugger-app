<?php

namespace jugger\classes;

use jugger\Request;

class WebRequest extends Request
{
	public static function build()
	{
		$self = new static();
		$self->setParams($_GET);
		$self->setData($_POST);
		return $self;
	}
}
