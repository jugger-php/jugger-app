https://habrahabr.ru/post/215605/

```
Структура:
- index.php
- config/
	- di - внедрение зависимостей
	- url - маршрутизатор
	- app - настройки приложения

- modules/
	- moduleName/
		- install - параметры установки (подготовленные страницы и сервисы)
		- model - модели
		- repo - репозитории
		- factory - фабрики
		- widget - виджеты
		- helper - вспомогательные классы

	- moduleNameAddon/ - кастомные дополнения к модулю
	 	- ...

- api/
	- dir/
		- anotherDir/
			- actionName/
				- Action.php
				- view1.php
				- view2.php
				
- pages/
	- dir/
		- anotherDir/
			- pageName/
				- Page.php
				- view.php
```

Структура:

```
/index.php - точка входа
/modules
	/shop [moduleName]
		/models
		/repo
		/widgets
/api
	/shop
		/basket [modelName]
		/product
			/controller.php (список действий)
			/access.php (список правил доступа)
			/action.php (отдельное действие)
/pages
	/catalog [произвольное имя]
		/sub-dir
			/section (страница)
			/index
				/presenter.php (контроллер страницы, подготовка данных)
				/view.php (страница)
```

---

Workflow:

1. Вход
2. Проверка существования (Router, 404)
3. Проверка доступа (Access, 403)
4. Проверка корректности данных запроса (Action, 400)

---

Access.php:

```php
return [
	// shop/basket/add-product
	'add-product' => new ActionRule('add-product'),

	// кастомное правило
	'basket-owner' => new CallbackRule(function(){
		return true;
	})
];
```

Action.php:

```php
class AddProduct extends Action
{
	public function run()
	{

	}
}
```

Controller.php:

```php
class ProductController extends Controller
{
	function actionAddProduct()
	{

	}
}
```

PageController.php:

```php
class CatalogPage extends PageController
{
	function before()
	{
		// событие перед страницей
		// подготовка данных
		// выстановка счетчиков и т.д.
	}

	function getProductList()
	{

	}
}
```

View.php:

```php
<?php

$products = $page->getProductList();

?>

<!-- HTML код -->

```
