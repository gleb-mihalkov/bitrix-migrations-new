<?php
namespace BitrixMigrations
{
    use BitrixMigrations\Autocreate\Manager;
    use Webmozart\PathUtil\Path;

    /**
     * Запускает отслеживание изменений БД через админ-панель и автоматическое создание
     * соответствующих миграций.
     */
    class Observer
    {
        /**
         * Запускает отслеживание изменений БД через админ-панель.
         * @param  string $table Имя таблицы в БД, в которой хранится информация об
         *                       установленных миграциях.
         * @param  string $path  Каталог (относительно корня сайта), в котором
         *                       хранятся файлы миграций.
         * @return void
         */
        public static function run($table, $path)
        {
            $path = Path::makeAbsolute($path, $_SERVER['DOCUMENT_ROOT']);
            Manager::init($path, $table);
        }
    }
}