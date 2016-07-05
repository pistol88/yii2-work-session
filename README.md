Yii2-work-session
==========

Модуль предоставляет интерфейс для ведения учета рабочих смен сотрудников, а также организации в целом.

Фиксируется начало всей смены, время прихода и ухода каждого сотрудника, рассчитывается время фактического нахождения на рабочем месте.

Установка
---------------------------------

Выполнить команду

```
php composer require pistol88/yii2-work-session "*"
```

Или добавить в composer.json

```
"pistol88/yii2-work-session": "*",
```

И выполнить

```
php composer update
```

Далее, мигрируем базу:

```
php yii migrate --migrationPath=vendor/pistol88/yii2-work-session/migrations
```

Подключение и настройка
---------------------------------

В конфигурационный файл приложения добавить модуль worksess, настроив его

```php
    'modules' => [
        //...
        'worksess' => [
            'class' => 'pistol88\worksess\Module',
            'adminRoles' => ['administrator'],
            //модуль пользователей
            'userModel' => 'common\models\User',
            //callback функция, позвращающая список работников
            'workers' => function() {
                return \common\models\User::findAll(['status' => 2, 'id' => Yii::$app->authManager->getUserIdsByRole(['washer'])]);
            },
        ],
        //...
    ]
``` 

Управление сессиями по роуту worksess/session/current.	


Виджеты
---------------------------------
```php
<?php
use pistol88\worksess\widgets\ControlButton;
use pistol88\worksess\widgets\Info;
?>
```

Информация об общей смене сменой:
```php
<?=Info::widget();?>
```

Информация о смене сотрудника ($worker - модель пользователя):
```php
<?=Info::widget(['for' => $worker]);?>
```

Кнопки переключения старта\остановки общей сессии и сессии отдельного сотрудника (если передано свойство $worker):
```php
<?=ControlButton::widget(['for' => $worker]);?>
```