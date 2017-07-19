# Миграции БД для CMS Битрикс

Библиотека является оберткой для библиотеки [arrilot/bitrix-migrations](https://github.com/arrilot/bitrix-migrations), немного расширяющей стандартный функционал.

## Установка

Упрощен способ создания интерфейса командной строки. Теперь для использования мигратора достачно создать файл *.php,
и добавить туда следующий код:

```php
// Подключаем ядро Битрикс.
$_SERVER['DOCUMENT_ROOT'] = dirname(__DIR__);

define('BX_NO_ACCELERATOR_RESET', true);
define('NOT_CHECK_PERMISSIONS', true);
define('NO_KEEP_STATISTIC', true);

require $_SERVER['DOCUMENT_ROOT'].'bitrix/modules/main/include/prolog_before.php';

// Если нужно, подключаем Composer.
require __DIR__.'/vendor/autoload.php';

// Запускаем интерфейс мигратора.
BitrixMigrations\Cli::run('migrations', 'local/migrations');
```

* Метод `BitrixMigrations\Cli::run` принимает два аргумента:

    * Имя таблицы с установленными миграциями;

    * Путь к папке с файлами миграций (относительно корня сайта).

* В примере предполагается, что сам файл, папка с миграциями и `composer.json` находятся в каталоге `корень_сайта/local`. Вы можете изменять пути на свои.

Теперь файл можно использовать:

`php имя_файла.php имя_команды`

Список команд в описании [исходной библиотеки](https://github.com/arrilot/bitrix-migrations#Доступные-команды).

## Безопасная установка миграций

К списку исходных команд была добавлена команда `update`. Если таблицы с установленными миграциями нет в БД, она создается; после чего на БД накатываютс все актуальные миграции.

## Автоматическое создание миграций

Для автоматического создания миграций, следует добавить в файл `init.php` примерно следующее:

```php
BitrixMigrations\Observer::run('migrations', 'local/migrations');
```

Метод принимает те же параметры, что и `BitrixMigrations\Cli::run`.

## Прочие изменения

*В процессе наполения*