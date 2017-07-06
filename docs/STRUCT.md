https://habrahabr.ru/post/215605/

Структура:

```
- index.php
- public/
	- assets/ (ресурсы страниц и виджетов)
		- dirName/
			- assetName.*
	- upload/ (файловый менеджер)
		- ...

- protected/
	- .htaccess
	- config/
		- di (внедрение зависимостей)
		- url (маршрутизатор)
		- app (настройки приложения)

	- libs/ (глобальные вспомогательные пакеты)
		- jugger/
			- ...

	- modules/
		- moduleName/
			- install.php (установочный/деинсталиционный скрипт)
			- install/ (параметры установки: подготовленные страницы и сервисы)
			- models/ (модели)
			- repos/ (репозитории)
			- factories/ (фабрики)
			- widgets/ (виджеты)
			- libs/ (вспомогательные пакеты)

		- moduleNameAddon/ (кастомные дополнения к модулю)
		 	- ...

	- templates/
		- common/
			- moduleName/
				- widgetName/
					- view.php
					- style.css
					- script.js

		- templateName1
		- templateName2
			- view.php
			- style.css
			- script.js
			- widgets/
				- moduleName/
					- widgetName/
						- ...

	- actions/
		- dir/
			- subDir/
				- actionName/
					- Action.php (extends BaseAction)
					- view1.php
					- view2.php

		- anotherDir/
			- pageName/
				- Action.php (extends PageAction)
				- view1.php
				- view2.php

```

Пространство имен:

```
'app/...' => '/protected/...',
'app/modules/shop/...' => '/protected/modules/shop/...',

```
