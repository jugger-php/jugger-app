https://habrahabr.ru/post/215605/

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

Presenter.php:

```php
class CatalogPresenter extends Presenter
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

$products = $presenter->getProductList();

?>

<!-- HTML код -->

```
