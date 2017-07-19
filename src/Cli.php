<?php
namespace BitrixMigrations
{
    use Arrilot\BitrixMigrations\Commands\MakeCommand;
    use Arrilot\BitrixMigrations\Commands\InstallCommand;
    use Arrilot\BitrixMigrations\Commands\MigrateCommand;
    use Arrilot\BitrixMigrations\Commands\RollbackCommand;
    use Arrilot\BitrixMigrations\Commands\TemplatesCommand;
    use Arrilot\BitrixMigrations\Commands\StatusCommand;
    use Arrilot\BitrixMigrations\Migrator;
    use Arrilot\BitrixMigrations\Storages\BitrixDatabaseStorage;
    use Arrilot\BitrixMigrations\TemplatesCollection;
    use Symfony\Component\Console\Application;
    use Webmozart\PathUtil\Path;
    use BitrixMigrations\Command\Update as UpdateCommand;
    use CModule;

    /**
     * Содержит метод запуска командного интерфейса управления миграциями БД.
     */
    class Cli
    {
        /**
         * Запускает командный интерфейс управления миграциями.
         * @param  string $table Имя таблицы в БД, в которой хранится информация об
         *                       установленных миграциях.
         * @param  string $path  Каталог (относительно корня сайта), в котором
         *                       хранятся файлы миграций.
         * @return void
         */
        public static function run($table, $path)
        {
            CModule::IncludeModule('iblock');
            CModule::IncludeModule('highloadblock');

            $path = Path::makeAbsolute($path, $_SERVER['DOCUMENT_ROOT']);

            $config = [
                'table' => $table,
                'dir' => $path
            ];

            $database = new BitrixDatabaseStorage($table);
            $templates = new TemplatesCollection();
            $templates->registerBasicTemplates();

            $migrator = new Migrator($config, $templates, $database);

            $app = new Application('Migrator');
            $app->add(new MakeCommand($migrator));
            $app->add(new InstallCommand($table, $database));
            $app->add(new MigrateCommand($migrator));
            $app->add(new RollbackCommand($migrator));
            $app->add(new TemplatesCommand($templates));
            $app->add(new StatusCommand($migrator));
            $app->add(new UpdateCommand($migrator, $table, $database));
            $app->run();
        }
    }
}