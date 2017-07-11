<?php
// entry data
$params = $request->getParams();
$data = $request->getData();
// property
$action = new Action();
$action->setParams($params);
$action->setData($data);
$response = $action->execute();
// constructor
$action = new Action($params, $data);
$response = $action->execute();
// one line
$response = Action::executeByRequest($request);

// ---

$app = new Application($config);
$app->execute() = function() {
    $request = $this->getRequest();
    $route = $this->getRouter()->getRouteByUrl($request->getUrl());
    $action = $this->getActionByRoute($route);
    
    if ($action) {
        if ($data = $request->getData()) {
            $action->setData($data);
        }
        if ($params = $request->getParams()) {
            $action->setParams($params);
        }
        $response = $action->execute();
    }
    else {
        throw new HttpException(404);
    }
    
    $response->send();
    exit(0);
}
