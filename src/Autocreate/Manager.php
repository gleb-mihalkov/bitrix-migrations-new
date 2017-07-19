<?php
namespace BitrixMigrations\Autocreate
{
    use Arrilot\BitrixMigrations\Autocreate\Manager as Base;
    use Arrilot\BitrixMigrations\TemplatesCollection;
    use Arrilot\BitrixMigrations\Migrator;
    use Webmozart\PathUtil\Path;

    /**
     * Перекрывает методы автоматического создания миграций.
     */
    class Manager extends Base
    {
        /**
         * Коллекция перегруженных стандартных шаблонов и обработчиков.
         * @var array
         */
        protected static $overrides = [
            'OnBeforeHLBlockAdd' => 'add_hlblock'
        ];

        /**
         * Перезаписывает некоторые стандартные шаблоны автосоздания миграции.
         * @return void
         */
        protected static function overrideTemplates($templates)
        {
            $base = Path::makeAbsolute('../Templates', __DIR__);
            $names = array_values(self::$overrides);

            foreach ($names as $name)
            {
                $path = Path::join($base, $name.'.tempalte');
                $name = 'auto_'.$name;

                $templates->registerTemplate([
                    'name' => $name,
                    'path' => $path
                ]);
            }
        }

        /**
         * Расширяет метод инициализации автосоздания.
         * @param  string $path  Путь к каталогу с миграциями.
         * @param  string $table Имя таблицы с миграциями.
         * @return void
         */
        public static function init($path, $table = null)
        {
            $templates = new TemplatesCollection();
            $templates->registerAutoTemplates();

            self::overrideTemplates($templates);

            $config = [
                'dir'   => $path,
                'table' => is_null($table) ? 'migrations' : $table,
            ];

            static::$migrator = new Migrator($config, $templates);

            static::addEventHandlers();

            static::turnOn();
        }

        /**
         * Перегружает получение экземпляра обработчика события.
         * @param  string       $handler    Имя обработчика.
         * @param  array<mixed> $parameters Аргументы вызова события.
         * @return mixed                    Сущность обработчика.
         */
        protected static function instantiateHandler($handler, $parameters)
        {
            $isNative = !isset(self::$overrides[$handler]);
            if ($isNative) return parent::instantiateHandler($handler, $parameters);

            $class = __NAMESPACE__.'\\Handlers\\'.$handler;
            return new $class($parameters);
        }
    }
}