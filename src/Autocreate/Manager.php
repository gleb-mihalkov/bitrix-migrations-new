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
         * Перезаписывает отдельный стандартный шаблон.
         * @param  string $templates Коллекция шаблонов.
         * @param  string $name      Имя шаблона.
         * @return void
         */
        protected static function overrideTemplate($templates, $name)
        {
            $base = Path::makeAbsolute('../Templates', __DIR__);
            $name = $name.'.template';
            $path = Path::join($base, $name);

            $templates->registerTemplate([
                'name' => 'auto_'.$name,
                'path' => $path
            ]);
        }

        /**
         * Перезаписывает некоторые стандартные шаблоны автосоздания миграции.
         * @return void
         */
        protected static function overrideTemplates($templates)
        {
            self::overrideTemplate($templates, 'add_hlblock');
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
    }
}