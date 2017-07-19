<?php
namespace BitrixMigrations\Command
{
    use Arrilot\BitrixMigrations\Interfaces\DatabaseStorageInterface;
    use Arrilot\BitrixMigrations\Migrator;
    use Arrilot\BitrixMigrations\Commands\AbstractCommand as Base;

    /**
     * Команда обновления миграций. Совмещает в себе команды install и migrate.
     * @internal
     */
    class Update extends Base
    {
        /**
         * Имя таблицы с миграциями.
         * @var string
         */
        protected $table;

        /**
         * Подключение к БД.
         * @var DatabaseStorageInterface
         */
        protected $database;

        /**
         * Сущность мигратора.
         * @var Migrator
         */
        protected $migrator;

        /**
         * Создает экземпляр класса.
         * @param string                   $table    Имя таблицы с миграциями.
         * @param DatabaseStorageInterface $database Подключение к БД.
         * @param Migrator                 $migrator Сущность мигратора.
         */
        public function __construct($table, $database, $migrator)
        {
            $this->database = $database;
            $this->table = $table;
            $this->migrator = $migrator;

            parent::__construct();
        }

        /**
         * Задает настройки команды.
         */
        protected function configure()
        {
            $this
                ->setName('update')
                ->setDescription('Create the datatable or run all outstanding migrations');
        }

        /**
         * Запускает команду.
         * @return void
         */
        protected function fire()
        {
            $isCreate = !$this->database->checkMigrationTableExistence();

            if ($isCreate)
            {
                $this->database->createMigrationTable();
                $this->info('Migration table has been successfully created!');
            }

            $list = $this->migrator->getMigrationsToRun();

            if (empty($list))
            {
                $this->info('Nothing to migrate');
                return;
            }

            foreach ($list as $item)
            {
                $this->migrator->runMigration($item);
                $this->message("<info>Migrated:</info> {$item}.php");
            }
        }
    }
}