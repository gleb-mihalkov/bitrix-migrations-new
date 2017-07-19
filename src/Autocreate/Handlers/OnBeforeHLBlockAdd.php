<?php
namespace BitrixMigrations\Autocreate\Handlers
{
    use Arrilot\BitrixMigrations\Autocreate\Handlers\OnBeforeHLBlockAdd as Base;

    /**
     * Перекрывает обработчик события добавления Highload-блока.
     * @internal
     */
    class OnBeforeHLBlockAdd extends Base
    {
        public function getReplace()
        {
            $replace = [];
            $replace['fields'] = var_export($this->fields, true);
            $replace['table'] = $this->fields['TABLE_NAME'];
            return $replace;
        }
    }
}