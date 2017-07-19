<?php
namespace BitrixMigrations\Autocreate
{
    use Bitrix\Highloadblock\HighloadBlockTable;
    use Bitrix\Main\Application;

    /**
     * Содержит вспомогательных методов для шаблонов миграций.
     * @internal
     */
    class Helper
    {
        /**
         * Получает ID highload-блока по имени его таблицы.
         * @param  string  $table Имя таблицы.
         * @return integer        ID.
         */
        public static function getHighloadIdByTable($table)
        {
            $highloads = HighloadBlockTable::getTableName();
            $db = Application::getConnection();

            $result = $db->query("SELECT * FROM $highloads WHERE TABLE_NAME = '$table';");
            $result = $result->fetch();

            return $result['ID'];
        }
    }
}