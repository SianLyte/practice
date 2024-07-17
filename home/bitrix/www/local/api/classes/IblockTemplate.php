<?php

namespace Legacy\API;

use Bitrix\Iblock\ElementTable;
use Bitrix\Iblock\SectionTable;
use Legacy\General\Constants;
use Bitrix\Main\Loader;
use Legacy\Iblock\IblockElementTable;
use Legacy\Form\OrganizationTable;
use Bitrix\Main\Entity;
use Bitrix\Main\Entity\ReferenceField;
use Bitrix\Main\DB\SqlExpression;
use Bitrix\Iblock\ElementPropertyTable;
use CIBlockElement;
use CIBlockProperty;

class IblockTemplate
{
    public static function getEmployees($arRequest)
        // [домен]/api/IblockTemplate/getEmployees/?user_id={}&limit={}&offset={}
    {
        if (Loader::includeModule('iblock')) {

            $totalEmployeesCountQuery = IblockElementTable::query()
                ->addFilter('IBLOCK_ID', Constants::IB_EMPLOYEE)
                ->addFilter("EMPLOYEE_USER_ID", $arRequest["user_id"])
                ->setSelect(['ID',
                    'EMPLOYEE_USER_ID' => 'EMPLOYEE_PROPERTY_USER_ID.VALUE',])
                ->registerRuntimeField(
                    'EMPLOYEE_PROPERTY_USER_ID',
                    new ReferenceField(
                        'EMPLOYEE_PROPERTY_USER_ID',
                        ElementPropertyTable::class,
                        [
                            '=this.ID' => 'ref.IBLOCK_ELEMENT_ID',
                            '=ref.IBLOCK_PROPERTY_ID' => new SqlExpression('?', Constants::IB_EMPLOYEE_PROPERTY_USER_ID)
                        ]
                    )
                );

            $totalEmployeesCount = $totalEmployeesCountQuery->exec()->getSelectedRowsCount();
            $currentPage = ($arRequest["offset"] / $arRequest["limit"]) + 1;
            $totalPages = ceil($totalEmployeesCount / $arRequest["limit"]);

            $query = IblockElementTable::query()
                ->addFilter('IBLOCK_ID', Constants::IB_EMPLOYEE
                )
                ->addFilter("EMPLOYEE_USER_ID", $arRequest["user_id"])
                ->setLimit($arRequest["limit"])
                ->setOffset($arRequest["offset"])
                ->setSelect([
                    'EMPLOYEE_USER_ID' => 'EMPLOYEE_PROPERTY_USER_ID.VALUE',
                    'EMPLOYEE_JOB_TITLE' => 'EMPLOYEE_PROPERTY_JOB_TITLE.VALUE',
                    'EMPLOYEE_NAME' => 'EMPLOYEE_PROPERTY_NAME.VALUE',
                    'EMPLOYEE_EMAIL' => 'EMPLOYEE_PROPERTY_EMAIL.VALUE',
                    'EMPLOYEE_PHONE' => 'EMPLOYEE_PROPERTY_PHONE.VALUE',
                    'ORG_USER_ID' => 'ORG_PROPERTY_USER_ID.VALUE',
                    'ORG_NAME' => 'ORG_PROPERTY_NAME.VALUE',
                    'ORG_INN' => 'ORG_PROPERTY_INN.VALUE',
                    'ORG_OGRN' => 'ORG_PROPERTY_OGRN.VALUE',
                    'ORG_KPP' => 'ORG_PROPERTY_KPP.VALUE',
                    'ORG_LEADER' => 'ORG_PROPERTY_LEADER.VALUE'])// для получения элементов конкретного инфоблока, фильтруем записи по его id
                ->registerRuntimeField(
                    'ORG',
                    new ReferenceField(
                        'ORG',
                        ElementTable::class,
                        [
                            'this.organization.value' => 'ref.ID',
                        ]
                    )
                )
                ->registerRuntimeField(
                    'organization',
                    new ReferenceField(
                        'organization',
                        ElementPropertyTable::class,
                        [
                            'this.ID' => 'ref.IBLOCK_ELEMENT_ID',
                            'ref.IBLOCK_PROPERTY_ID' => new SqlExpression('?', Constants::IB_EMPLOYEE_PROPERTY_ORGANIZATION),
                        ]
                    )
                )
                ->registerRuntimeField(
                    'EMPLOYEE_PROPERTY_USER_ID',
                    new ReferenceField(
                        'EMPLOYEE_PROPERTY_USER_ID',
                        ElementPropertyTable::class,
                        [
                            '=this.ID' => 'ref.IBLOCK_ELEMENT_ID',
                            '=ref.IBLOCK_PROPERTY_ID' => new SqlExpression('?', Constants::IB_EMPLOYEE_PROPERTY_USER_ID)
                        ]
                    )
                )
                ->registerRuntimeField(
                    'EMPLOYEE_PROPERTY_JOB_TITLE',
                    new ReferenceField(
                        'EMPLOYEE_PROPERTY_JOB_TITLE',
                        ElementPropertyTable::class,
                        [
                            '=this.ID' => 'ref.IBLOCK_ELEMENT_ID',
                            '=ref.IBLOCK_PROPERTY_ID' => new SqlExpression('?', Constants::IB_EMPLOYEE_PROPERTY_JOB_TITLE)
                        ]
                    )
                )
                ->registerRuntimeField(
                    'EMPLOYEE_PROPERTY_NAME',
                    new ReferenceField(
                        'EMPLOYEE_PROPERTY_NAME',
                        ElementPropertyTable::class,
                        [
                            '=this.ID' => 'ref.IBLOCK_ELEMENT_ID',
                            '=ref.IBLOCK_PROPERTY_ID' => new SqlExpression('?', Constants::IB_EMPLOYEE_PROPERTY_NAME)
                        ]
                    )
                )
                ->registerRuntimeField(
                    'EMPLOYEE_PROPERTY_EMAIL',
                    new ReferenceField(
                        'EMPLOYEE_PROPERTY_EMAIL',
                        ElementPropertyTable::class,
                        [
                            '=this.ID' => 'ref.IBLOCK_ELEMENT_ID',
                            '=ref.IBLOCK_PROPERTY_ID' => new SqlExpression('?', Constants::IB_EMPLOYEE_PROPERTY_EMAIL)
                        ]
                    )
                )
                ->registerRuntimeField(
                    'EMPLOYEE_PROPERTY_PHONE',
                    new ReferenceField(
                        'EMPLOYEE_PROPERTY_PHONE',
                        ElementPropertyTable::class,
                        [
                            '=this.ID' => 'ref.IBLOCK_ELEMENT_ID',
                            '=ref.IBLOCK_PROPERTY_ID' => new SqlExpression('?', Constants::IB_EMPLOYEE_PROPERTY_PHONE)
                        ]
                    )
                )
                ->registerRuntimeField(
                    'ORG_PROPERTY_USER_ID',
                    new ReferenceField(
                        'ORG_PROPERTY_USER_ID',
                        ElementPropertyTable::class,
                        [
                            '=this.ORG.ID' => 'ref.IBLOCK_ELEMENT_ID',
                            '=ref.IBLOCK_PROPERTY_ID' => new SqlExpression('?', Constants::IB_ORGANIZATION_PROPERTY_USER_ID) // ID свойства "user_id"
                        ]
                    )
                )
                ->registerRuntimeField(
                    'ORG_PROPERTY_NAME',
                    new ReferenceField(
                        'ORG_PROPERTY_NAME',
                        ElementPropertyTable::class,
                        [
                            '=this.ORG.ID' => 'ref.IBLOCK_ELEMENT_ID',
                            '=ref.IBLOCK_PROPERTY_ID' => new SqlExpression('?', Constants::IB_ORGANIZATION_PROPERTY_NAME) // ID свойства "name"
                        ]
                    )
                )
                ->registerRuntimeField(
                    'ORG_PROPERTY_INN',
                    new ReferenceField(
                        'ORG_PROPERTY_INN',
                        ElementPropertyTable::class,
                        [
                            '=this.ORG.ID' => 'ref.IBLOCK_ELEMENT_ID',
                            '=ref.IBLOCK_PROPERTY_ID' => new SqlExpression('?', Constants::IB_ORGANIZATION_PROPERTY_INN) // ID свойства "inn"
                        ]
                    )
                )
                ->registerRuntimeField(
                    'ORG_PROPERTY_OGRN',
                    new ReferenceField(
                        'ORG_PROPERTY_OGRN',
                        ElementPropertyTable::class,
                        [
                            '=this.ORG.ID' => 'ref.IBLOCK_ELEMENT_ID',
                            '=ref.IBLOCK_PROPERTY_ID' => new SqlExpression('?', Constants::IB_ORGANIZATION_PROPERTY_OGRN) // ID свойства "ogrn"
                        ]
                    )
                )
                ->registerRuntimeField(
                    'ORG_PROPERTY_KPP',
                    new ReferenceField(
                        'ORG_PROPERTY_KPP',
                        ElementPropertyTable::class,
                        [
                            '=this.ORG.ID' => 'ref.IBLOCK_ELEMENT_ID',
                            '=ref.IBLOCK_PROPERTY_ID' => new SqlExpression('?', Constants::IB_ORGANIZATION_PROPERTY_KPP) // ID свойства "kpp"
                        ]
                    )
                )
                ->registerRuntimeField(
                    'ORG_PROPERTY_LEADER',
                    new ReferenceField(
                        'ORG_PROPERTY_LEADER',
                        ElementPropertyTable::class,
                        [
                            '=this.ORG.ID' => 'ref.IBLOCK_ELEMENT_ID',
                            '=ref.IBLOCK_PROPERTY_ID' => new SqlExpression('?', Constants::IB_ORGANIZATION_PROPERTY_LEADER) // ID свойства "leader"
                        ]
                    )

                );

            $db = $query->exec();

            $result = [];
            while ($res = $db->fetch()) {
                $result[] = $res;
            }

            $response = [
                "data" => $result,
                "pagination" => [
                    "total_count" => $totalEmployeesCount,
                    "current_page" => $currentPage,
                    "total_pages" => $totalPages,
                ]
            ];
            return $response;
        }
        throw new \Exception('Не удалось подключить необходимые модули');
    }

    public static function addEmployee($arRequest)
        //[домен]/api/IblockTemplate/addEmployee/?user_id={}&organization_id={}&job_title={}&email={}&phone={}&name={}
    {

        if (Loader::includeModule('iblock')) {
            $el = new CIBlockElement;

            $organizationExists = false;

            $organizationQuery = IblockElementTable::query()
                ->addFilter('IBLOCK_ID', Constants::IB_ORGANIZATION)
                ->addFilter("ID", $arRequest["organization_id"])
                ->addFilter("ACTIVE", "Y")
                ->addSelect("ID");

            if ($organizationQuery->Fetch()) {
                $organizationExists = true;
            }

            if (!$organizationExists) {
                echo "Организация с ID " . $arRequest["organization_id"]. " не найдена или неактивна.";
                return;
            }

            $arLoadEmployeeArray = array(
                'IBLOCK_ID'      => Constants::IB_EMPLOYEE,
                'NAME'           => $arRequest["name"],
                'ACTIVE'         => 'Y',
            );

            // Добавляем элемент
            if ($ID = $el->Add($arLoadEmployeeArray)) {
                $propertyValues = [
                    'user_id'      => $arRequest["user_id"],
                    'job_title'    => $arRequest["job_title"],
                    'email'       => $arRequest["email"],
                    'phone'      => $arRequest["phone"],
                    'name'         => $arRequest["name"],
                    'organization' => $arRequest["organization_id"],
                ];

                CIBlockElement::SetPropertyValuesEx($ID, Constants::IB_EMPLOYEE, $propertyValues);
                echo "Элемент успешно добавлен с ID: " . $ID;
            } else {
                global $APPLICATION;
                echo "Ошибка при добавлении элемента: " . $el->LAST_ERROR;
            }
        }
    }

    public static function updateEmployee($arRequest)
        //[домен]/api/IblockTemplate/updateEmployee/?employee_id={}&user_id={}&organization_id={}&job_title={}&email={}&phone={}&name={}
    {
        if (Loader::includeModule('iblock')) {
            $el = new CIBlockElement;

            $organizationExists = false;
            $organizationQuery = IblockElementTable::query()
                ->addFilter('IBLOCK_ID', Constants::IB_ORGANIZATION)
                ->addFilter("ID", $arRequest["organization_id"])
                ->addFilter("ACTIVE", "Y")
                ->addSelect("ID");

            if ($organizationQuery->Fetch()) {
                $organizationExists = true;
            }
            if (!$organizationExists) {
                echo "Организация с ID". $arRequest["organization_id"] . "не найдена или неактивна.";
                return;
            }


            $arUpdateEmployeeArray = array(
                'NAME' => $arRequest["name"],
                'ACTIVE' => 'Y',
                'PROPERTY_VALUES' => [
                    'user_id'      => $arRequest["user_id"],
                    'job_title'    => $arRequest["job_title"],
                    'email'        => $arRequest["email"],
                    'phone'        => $arRequest["phone"],
                    'name'         => $arRequest["name"],
                    'organization' => $arRequest["organization_id"],
                ]
            );

            if ($el->Update($arRequest["employee_id"], $arUpdateEmployeeArray)) {
                echo "Элемент успешно обновлен с ID: " . $arRequest["employee_id"];
            } else {
                global $APPLICATION;
                echo "Ошибка при обновлении элемента: " . $el->LAST_ERROR;
            }
        } else {
            echo "Модуль iblock не найден.";
        }
    }

    public static function deleteEmployee($arRequest) //[домен]/api/IblockTemplate/deleteEmployee/?employee_id={}
    {
        if (Loader::includeModule('iblock')) {

            $elementExists = false;
            $employeeQuery = IblockElementTable::query()
                ->addFilter('IBLOCK_ID', Constants::IB_EMPLOYEE)
                ->addFilter("ID", $arRequest["employee_id"])
                ->addFilter("ACTIVE", "Y")
                ->addSelect("ID");

            if ($employeeQuery->Fetch()) {
                $elementExists = true;
            }

            if (!$elementExists) {
                echo "Элемент с ID ".$arRequest["employee_id"]." не найден или неактивен.";
                return;
            }

            // Удаление элемента
            if (CIBlockElement::Delete($arRequest["employee_id"])) {
                echo "Элемент успешно удален с ID: " . $arRequest["employee_id"];
            } else {
                global $APPLICATION;
                echo "Ошибка при удалении элемента: " . $APPLICATION->GetException();
            }
        } else {
            echo "Модуль iblock не найден.";
        }
    }

    public static function getEmployeeById($arRequest) //[ip]/api/IblockTemplate/getEmployeeById/?employee_id={}&user_id={}
    {
        if (Loader::includeModule('iblock')) {

            $query = IblockElementTable::query()
                ->addFilter('IBLOCK_ID', Constants::IB_EMPLOYEE)
                ->addFilter("EMPLOYEE_USER_ID", $arRequest["user_id"])
                ->addFilter("ID", $arRequest["employee_id"])
                ->setSelect([
                    'EMPLOYEE_USER_ID' => 'EMPLOYEE_PROPERTY_USER_ID.VALUE',
                    'EMPLOYEE_JOB_TITLE' => 'EMPLOYEE_PROPERTY_JOB_TITLE.VALUE',
                    'EMPLOYEE_NAME' => 'EMPLOYEE_PROPERTY_NAME.VALUE',
                    'EMPLOYEE_EMAIL' => 'EMPLOYEE_PROPERTY_EMAIL.VALUE',
                    'EMPLOYEE_PHONE' => 'EMPLOYEE_PROPERTY_PHONE.VALUE',
                    'ORG_USER_ID' => 'ORG_PROPERTY_USER_ID.VALUE',
                    'ORG_NAME' => 'ORG_PROPERTY_NAME.VALUE',
                    'ORG_INN' => 'ORG_PROPERTY_INN.VALUE',
                    'ORG_OGRN' => 'ORG_PROPERTY_OGRN.VALUE',
                    'ORG_KPP' => 'ORG_PROPERTY_KPP.VALUE',
                    'ORG_LEADER' => 'ORG_PROPERTY_LEADER.VALUE']) // для получения элементов конкретного инфоблока, фильтруем записи по его id
                ->registerRuntimeField(
                    'organization',
                    new ReferenceField(
                        'organization',
                        ElementPropertyTable::class,
                        [
                            'this.ID' => 'ref.IBLOCK_ELEMENT_ID',
                            'ref.IBLOCK_PROPERTY_ID' => new SqlExpression('?', Constants::IB_EMPLOYEE_PROPERTY_ORGANIZATION),
                        ]
                    )
                )
                ->registerRuntimeField(
                    'EMPLOYEE_PROPERTY_USER_ID',
                    new ReferenceField(
                        'EMPLOYEE_PROPERTY_USER_ID',
                        ElementPropertyTable::class,
                        [
                            '=this.ID' => 'ref.IBLOCK_ELEMENT_ID',
                            '=ref.IBLOCK_PROPERTY_ID' => new SqlExpression('?', Constants::IB_EMPLOYEE_PROPERTY_USER_ID)
                        ]
                    )
                )
                ->registerRuntimeField(
                    'EMPLOYEE_PROPERTY_JOB_TITLE',
                    new ReferenceField(
                        'EMPLOYEE_PROPERTY_JOB_TITLE',
                        ElementPropertyTable::class,
                        [
                            '=this.ID' => 'ref.IBLOCK_ELEMENT_ID',
                            '=ref.IBLOCK_PROPERTY_ID' => new SqlExpression('?', Constants::IB_EMPLOYEE_PROPERTY_JOB_TITLE)
                        ]
                    )
                )
                ->registerRuntimeField(
                    'EMPLOYEE_PROPERTY_NAME',
                    new ReferenceField(
                        'EMPLOYEE_PROPERTY_NAME',
                        ElementPropertyTable::class,
                        [
                            '=this.ID' => 'ref.IBLOCK_ELEMENT_ID',
                            '=ref.IBLOCK_PROPERTY_ID' => new SqlExpression('?', Constants::IB_EMPLOYEE_PROPERTY_NAME)
                        ]
                    )
                )
                ->registerRuntimeField(
                    'EMPLOYEE_PROPERTY_EMAIL',
                    new ReferenceField(
                        'EMPLOYEE_PROPERTY_EMAIL',
                        ElementPropertyTable::class,
                        [
                            '=this.ID' => 'ref.IBLOCK_ELEMENT_ID',
                            '=ref.IBLOCK_PROPERTY_ID' => new SqlExpression('?', Constants::IB_EMPLOYEE_PROPERTY_EMAIL)
                        ]
                    )
                )
                ->registerRuntimeField(
                    'EMPLOYEE_PROPERTY_PHONE',
                    new ReferenceField(
                        'EMPLOYEE_PROPERTY_PHONE',
                        ElementPropertyTable::class,
                        [
                            '=this.ID' => 'ref.IBLOCK_ELEMENT_ID',
                            '=ref.IBLOCK_PROPERTY_ID' => new SqlExpression('?', Constants::IB_EMPLOYEE_PROPERTY_PHONE)
                        ]
                    )
                )
                ->registerRuntimeField(
                    'ORG',
                    new ReferenceField(
                        'ORG',
                        ElementTable::class,
                        [
                            'this.organization.value' => 'ref.ID',
                        ]
                    )
                )
                ->registerRuntimeField(
                    'ORG_PROPERTY_USER_ID',
                    new ReferenceField(
                        'ORG_PROPERTY_USER_ID',
                        ElementPropertyTable::class,
                        [
                            '=this.ORG.ID' => 'ref.IBLOCK_ELEMENT_ID',
                            '=ref.IBLOCK_PROPERTY_ID' => new SqlExpression('?', Constants::IB_ORGANIZATION_PROPERTY_USER_ID) // ID свойства "user_id"
                        ]
                    )
                )
                ->registerRuntimeField(
                    'ORG_PROPERTY_NAME',
                    new ReferenceField(
                        'ORG_PROPERTY_NAME',
                        ElementPropertyTable::class,
                        [
                            '=this.ORG.ID' => 'ref.IBLOCK_ELEMENT_ID',
                            '=ref.IBLOCK_PROPERTY_ID' => new SqlExpression('?', Constants::IB_ORGANIZATION_PROPERTY_NAME) // ID свойства "name"
                        ]
                    )
                )
                ->registerRuntimeField(
                    'ORG_PROPERTY_INN',
                    new ReferenceField(
                        'ORG_PROPERTY_INN',
                        ElementPropertyTable::class,
                        [
                            '=this.ORG.ID' => 'ref.IBLOCK_ELEMENT_ID',
                            '=ref.IBLOCK_PROPERTY_ID' => new SqlExpression('?', Constants::IB_ORGANIZATION_PROPERTY_INN) // ID свойства "inn"
                        ]
                    )
                )
                ->registerRuntimeField(
                    'ORG_PROPERTY_OGRN',
                    new ReferenceField(
                        'ORG_PROPERTY_OGRN',
                        ElementPropertyTable::class,
                        [
                            '=this.ORG.ID' => 'ref.IBLOCK_ELEMENT_ID',
                            '=ref.IBLOCK_PROPERTY_ID' => new SqlExpression('?', Constants::IB_ORGANIZATION_PROPERTY_OGRN) // ID свойства "ogrn"
                        ]
                    )
                )
                ->registerRuntimeField(
                    'ORG_PROPERTY_KPP',
                    new ReferenceField(
                        'ORG_PROPERTY_KPP',
                        ElementPropertyTable::class,
                        [
                            '=this.ORG.ID' => 'ref.IBLOCK_ELEMENT_ID',
                            '=ref.IBLOCK_PROPERTY_ID' => new SqlExpression('?', Constants::IB_ORGANIZATION_PROPERTY_KPP) // ID свойства "kpp"
                        ]
                    )
                )
                ->registerRuntimeField(
                    'ORG_PROPERTY_LEADER',
                    new ReferenceField(
                        'ORG_PROPERTY_LEADER',
                        ElementPropertyTable::class,
                        [
                            '=this.ORG.ID' => 'ref.IBLOCK_ELEMENT_ID',
                            '=ref.IBLOCK_PROPERTY_ID' => new SqlExpression('?', Constants::IB_ORGANIZATION_PROPERTY_LEADER) // ID свойства "leader"
                        ]
                    )
                );

            return $query->exec()->fetch();
        }
    }

    public static function getOrganizations($arRequest)
        //[ip]/api/IblockTemplate/getOrganizations/?user_id={}&limit={}&offset={}
    {
        if (Loader::includeModule('iblock')) {

            $totalCountQuery = IblockElementTable::query()
                ->addFilter('IBLOCK_ID', Constants::IB_ORGANIZATION)
                ->addFilter("ORGANIZATION_USER_ID", $arRequest["user_id"])
                ->setSelect(['ID',
                    'ORGANIZATION_USER_ID' => 'ORGANIZATION_PROPERTY_USER_ID.VALUE',])
                ->registerRuntimeField(
                    'ORGANIZATION_PROPERTY_USER_ID',
                    new ReferenceField(
                        'ORGANIZATION_PROPERTY_USER_ID',
                        ElementPropertyTable::class,
                        [
                            '=this.ID' => 'ref.IBLOCK_ELEMENT_ID',
                            '=ref.IBLOCK_PROPERTY_ID' => new SqlExpression('?', Constants::IB_ORGANIZATION_PROPERTY_USER_ID)
                        ]
                    )
                );

            $totalOrganizationsCount = $totalCountQuery->exec()->getSelectedRowsCount();
            $currentPage = ($arRequest["offset"] / $arRequest["limit"]) + 1;
            $totalPages = ceil($totalOrganizationsCount / $arRequest["limit"]);

            $query = IblockElementTable::query()
                ->addFilter('IBLOCK_ID', Constants::IB_ORGANIZATION)
                ->setLimit($arRequest["limit"])
                ->setOffset($arRequest["offset"])
                ->addFilter("ORGANIZATION_USER_ID", $arRequest["user_id"])
                ->setSelect([
                    'ORGANIZATION_USER_ID' => 'ORGANIZATION_PROPERTY_USER_ID.VALUE',
                    'ORGANIZATION_NAME' => 'ORGANIZATION_PROPERTY_NAME.VALUE',
                    'ORGANIZATION_INN' => 'ORGANIZATION_PROPERTY_INN.VALUE',
                    'ORGANIZATION_OGRN' => 'ORGANIZATION_PROPERTY_OGRN.VALUE',
                    'ORGANIZATION_KPP' => 'ORGANIZATION_PROPERTY_KPP.VALUE',
                    'ORGANIZATION_LEADER' => 'ORGANIZATION_PROPERTY_LEADER.VALUE',
                    ])
                ->registerRuntimeField(
                    'ORGANIZATION_PROPERTY_USER_ID',
                    new ReferenceField(
                        'ORGANIZATION_PROPERTY_USER_ID',
                        ElementPropertyTable::class,
                        [
                            '=this.ID' => 'ref.IBLOCK_ELEMENT_ID',
                            '=ref.IBLOCK_PROPERTY_ID' => new SqlExpression('?', Constants::IB_ORGANIZATION_PROPERTY_USER_ID)
                        ]
                    )
                )
                ->registerRuntimeField(
                    'ORGANIZATION_PROPERTY_NAME',
                    new ReferenceField(
                        'ORGANIZATION_PROPERTY_NAME',
                        ElementPropertyTable::class,
                        [
                            '=this.ID' => 'ref.IBLOCK_ELEMENT_ID',
                            '=ref.IBLOCK_PROPERTY_ID' => new SqlExpression('?', Constants::IB_ORGANIZATION_PROPERTY_NAME)
                        ]
                    )
                )
                ->registerRuntimeField(
                    'ORGANIZATION_PROPERTY_INN',
                    new ReferenceField(
                        'ORGANIZATION_PROPERTY_INN',
                        ElementPropertyTable::class,
                        [
                            '=this.ID' => 'ref.IBLOCK_ELEMENT_ID',
                            '=ref.IBLOCK_PROPERTY_ID' => new SqlExpression('?', Constants::IB_ORGANIZATION_PROPERTY_INN)
                        ]
                    )
                )
                ->registerRuntimeField(
                    'ORGANIZATION_PROPERTY_OGRN',
                    new ReferenceField(
                        'ORGANIZATION_PROPERTY_OGRN',
                        ElementPropertyTable::class,
                        [
                            '=this.ID' => 'ref.IBLOCK_ELEMENT_ID',
                            '=ref.IBLOCK_PROPERTY_ID' => new SqlExpression('?', Constants::IB_ORGANIZATION_PROPERTY_OGRN)
                        ]
                    )
                )
                ->registerRuntimeField(
                    'ORGANIZATION_PROPERTY_KPP',
                    new ReferenceField(
                        'ORGANIZATION_PROPERTY_KPP',
                        ElementPropertyTable::class,
                        [
                            '=this.ID' => 'ref.IBLOCK_ELEMENT_ID',
                            '=ref.IBLOCK_PROPERTY_ID' => new SqlExpression('?', Constants::IB_ORGANIZATION_PROPERTY_KPP)
                        ]
                    )
                )
                ->registerRuntimeField(
                    'ORGANIZATION_PROPERTY_LEADER',
                    new ReferenceField(
                        'ORGANIZATION_PROPERTY_LEADER',
                        ElementPropertyTable::class,
                        [
                            '=this.ID' => 'ref.IBLOCK_ELEMENT_ID',
                            '=ref.IBLOCK_PROPERTY_ID' => new SqlExpression('?', Constants::IB_ORGANIZATION_PROPERTY_LEADER)
                        ]
                    )
                );

            $db = $query->exec();

            $result = [];
            while ($res = $db->fetch()) {
                $result[] = $res;
            }
            $response = [
                "data" => $result,
                "pagination" => [
                    "total_count" => $totalOrganizationsCount,
                    "current_page" => $currentPage,
                    "total_pages" => $totalPages,
                ]
            ];
            return $response;
        }
        throw new \Exception('Не удалось подключить необходимые модули');
    }

    public static function addOrganization($arRequest)
        //[домен]/api/IblockTemplate/addOrganization/?user_id={}&name={}&inn={}&ogrn={}&kpp={}&leader={}
    {

        if (Loader::includeModule('iblock')) {
            $el = new CIBlockElement;

            $arLoadOrganizationArray = array(
                'IBLOCK_ID'      => Constants::IB_ORGANIZATION,
                'NAME'           => $arRequest["name"],
                'ACTIVE'         => 'Y',
            );

            if ($ID = $el->Add($arLoadOrganizationArray)) {
                $propertyValues = [
                    'user_id'      => $arRequest["user_id"],
                    'name'    => $arRequest["name"],
                    'inn'       => $arRequest["inn"],
                    'ogrn'      => $arRequest["ogrn"],
                    'kpp'         => $arRequest["kpp"],
                    'leader' => $arRequest["leader"],
                ];

                CIBlockElement::SetPropertyValuesEx($ID, Constants::IB_ORGANIZATION, $propertyValues);
                echo "Элемент успешно добавлен с ID: " . $ID;
            } else {
                global $APPLICATION;
                echo "Ошибка при добавлении элемента: " . $el->LAST_ERROR;
            }
        }
    }

    public static function updateOrganization($arRequest)
        //[домен]/api/IblockTemplate/updateOrganization/?organization_id={}&user_id={}&name={}&inn={}&ogrn={}&kpp={}&leader={}
    {
        if (Loader::includeModule('iblock')) {
            $el = new CIBlockElement;

            $arUpdateOrganizationArray = array(
                'NAME' => $arRequest["name"],
                'ACTIVE' => 'Y',
                'PROPERTY_VALUES' => [
                    'user_id'      => $arRequest["user_id"],
                    'name'    => $arRequest["name"],
                    'inn'       => $arRequest["inn"],
                    'ogrn'      => $arRequest["ogrn"],
                    'kpp'         => $arRequest["kpp"],
                    'leader' => $arRequest["leader"],
                ]
            );

            if ($el->Update($arRequest["organization_id"], $arUpdateOrganizationArray)) {
                echo "Элемент успешно обновлен с ID: " . $arRequest["organization_id"];
            } else {
                global $APPLICATION;
                echo "Ошибка при обновлении элемента: " . $el->LAST_ERROR;
            }
        } else {
            echo "Модуль iblock не найден.";
        }
    }

    public static function deleteOrganization($arRequest) //[домен]/api/IblockTemplate/deleteOrganization/?organization_id={}
    {
        if (Loader::includeModule('iblock')) {

            $elementExists = false;
            $employeeQuery = IblockElementTable::query()
                ->addFilter('IBLOCK_ID', Constants::IB_ORGANIZATION)
                ->addFilter("ID", $arRequest["organization_id"])
                ->addFilter("ACTIVE", "Y")
                ->addSelect("ID");

            if ($employeeQuery->Fetch()) {
                $elementExists = true;
            }

            if (!$elementExists) {
                echo "Элемент с ID ".$arRequest["organization_id"]." не найден или неактивен.";
                return;
            }

            if (CIBlockElement::Delete($arRequest["organization_id"])) {
                echo "Элемент успешно удален с ID: " . $arRequest["organization_id"];
            } else {
                global $APPLICATION;
                echo "Ошибка при удалении элемента: " . $APPLICATION->GetException();
            }
        } else {
            echo "Модуль iblock не найден.";
        }
    }

    public static function getOrganizationById($arRequest)
        //[ip]/api/IblockTemplate/getOrganizationById/?user_id={}&organization_id={}
    {
        if (Loader::includeModule('iblock')) { // чтобы работать с элементами модуля, необходимо его подключить, в примере работаем с инфоблоком

            $query = IblockElementTable::query()
                ->addFilter('IBLOCK_ID', Constants::IB_ORGANIZATION)
                ->setLimit($arRequest["limit"])
                ->setOffset($arRequest["offset"])
                ->addFilter("ORGANIZATION_USER_ID", $arRequest["user_id"])
                ->addFilter("ID", $arRequest["organization_id"])

                ->setSelect([
                    'ORGANIZATION_USER_ID' => 'ORGANIZATION_PROPERTY_USER_ID.VALUE',
                    'ORGANIZATION_NAME' => 'ORGANIZATION_PROPERTY_NAME.VALUE',
                    'ORGANIZATION_INN' => 'ORGANIZATION_PROPERTY_INN.VALUE',
                    'ORGANIZATION_OGRN' => 'ORGANIZATION_PROPERTY_OGRN.VALUE',
                    'ORGANIZATION_KPP' => 'ORGANIZATION_PROPERTY_KPP.VALUE',
                    'ORGANIZATION_LEADER' => 'ORGANIZATION_PROPERTY_LEADER.VALUE',
                ])
                // для получения элементов конкретного инфоблока, фильтруем записи по его id

                ->registerRuntimeField(
                    'ORGANIZATION_PROPERTY_USER_ID',
                    new ReferenceField(
                        'ORGANIZATION_PROPERTY_USER_ID',
                        ElementPropertyTable::class,
                        [
                            '=this.ID' => 'ref.IBLOCK_ELEMENT_ID',
                            '=ref.IBLOCK_PROPERTY_ID' => new SqlExpression('?', Constants::IB_ORGANIZATION_PROPERTY_USER_ID)
                        ]
                    )
                )
                ->registerRuntimeField(
                    'ORGANIZATION_PROPERTY_NAME',
                    new ReferenceField(
                        'ORGANIZATION_PROPERTY_NAME',
                        ElementPropertyTable::class,
                        [
                            '=this.ID' => 'ref.IBLOCK_ELEMENT_ID',
                            '=ref.IBLOCK_PROPERTY_ID' => new SqlExpression('?', Constants::IB_ORGANIZATION_PROPERTY_NAME)
                        ]
                    )
                )
                ->registerRuntimeField(
                    'ORGANIZATION_PROPERTY_INN',
                    new ReferenceField(
                        'ORGANIZATION_PROPERTY_INN',
                        ElementPropertyTable::class,
                        [
                            '=this.ID' => 'ref.IBLOCK_ELEMENT_ID',
                            '=ref.IBLOCK_PROPERTY_ID' => new SqlExpression('?', Constants::IB_ORGANIZATION_PROPERTY_INN)
                        ]
                    )
                )
                ->registerRuntimeField(
                    'ORGANIZATION_PROPERTY_OGRN',
                    new ReferenceField(
                        'ORGANIZATION_PROPERTY_OGRN',
                        ElementPropertyTable::class,
                        [
                            '=this.ID' => 'ref.IBLOCK_ELEMENT_ID',
                            '=ref.IBLOCK_PROPERTY_ID' => new SqlExpression('?', Constants::IB_ORGANIZATION_PROPERTY_OGRN)
                        ]
                    )
                )
                ->registerRuntimeField(
                    'ORGANIZATION_PROPERTY_KPP',
                    new ReferenceField(
                        'ORGANIZATION_PROPERTY_KPP',
                        ElementPropertyTable::class,
                        [
                            '=this.ID' => 'ref.IBLOCK_ELEMENT_ID',
                            '=ref.IBLOCK_PROPERTY_ID' => new SqlExpression('?', Constants::IB_ORGANIZATION_PROPERTY_KPP)
                        ]
                    )
                )
                ->registerRuntimeField(
                    'ORGANIZATION_PROPERTY_LEADER',
                    new ReferenceField(
                        'ORGANIZATION_PROPERTY_LEADER',
                        ElementPropertyTable::class,
                        [
                            '=this.ID' => 'ref.IBLOCK_ELEMENT_ID',
                            '=ref.IBLOCK_PROPERTY_ID' => new SqlExpression('?', Constants::IB_ORGANIZATION_PROPERTY_LEADER)
                        ]
                    )
                );

            //->withFilter()
            //->withOrder()
            //->withPage($arRequest['limit'], $arRequest['page']);
            $count = $query->queryCountTotal(); // количество записей ответа

            $db = $query->exec();

            $result = [];
            while ($res = $db->fetch()) {
                $result[] = $res;
            }
            return $result;
        }
        throw new \Exception('Не удалось подключить необходимые модули');
    }

}



