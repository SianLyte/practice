<?php

namespace Legacy\Settings;
use Bitrix\Main\Entity;
class CheckTable extends Entity\DataManager
{
    public static function getTableName()
    {
        return 'check';
    }

    public static function getUfId()
    {
        return 'MY_BOOK';
    }
    public static function getMap()
    {
        return array(
            new Entity\IntegerField('ID', array(
            'primary' => true,
            'autocomplete' => true
            )),
            new Entity\StringField('NAME', array(
            'required' => true
            ))
        );
    }
}