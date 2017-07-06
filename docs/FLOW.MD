**in**

1. создание приложения "Application"
1. init глобальных событий
1. событие (before_request)
1. создание объекта "Request"
1. событие (before_controller)
1. создание контроллера "Controller"
1. init событий контроллера
1. событие (after_controller)
1. создание действия "Action"
1. событие (before_action)
1. обработка запроса (выполнение действия)
1. подключения "View"
1. создание объекта "Response"
1. событие (before_send_response)
1. отправка ответа "Response->send()"

**exit**
