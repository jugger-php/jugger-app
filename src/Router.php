<?php

namespace jugger;

class Router
{
	public function getRoute(Request $request)
	{
		$params = $request->getParams() ?? [];
		return $params['r'] ?? null;
	}
}
