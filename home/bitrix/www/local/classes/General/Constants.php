<?php
namespace Legacy\General;

class Constants
{
	/** ПОЛЬЗОВАТЕЛИ, ИМЕЮЩИЕ ПРАВО ГОЛОСОВАТЬ ЗА РЕЙТИНГ */
	const IB_EMPLOYEE = 3;

    const IB_ORGANIZATION = 2;

    const IB_EMPLOYEE_PROPERTY_USER_ID = 8;

    const IB_EMPLOYEE_PROPERTY_JOB_TITLE = 9;

    const IB_EMPLOYEE_PROPERTY_NAME = 10;

    const IB_EMPLOYEE_PROPERTY_EMAIL = 11;

    const IB_EMPLOYEE_PROPERTY_PHONE = 12;

    const IB_EMPLOYEE_PROPERTY_ORGANIZATION = 13;


    /**ORGANIZATION */
    const IB_ORGANIZATION_PROPERTY_USER_ID = 2;

    const IB_ORGANIZATION_PROPERTY_NAME = 3;

    const IB_ORGANIZATION_PROPERTY_INN = 4;

    const IB_ORGANIZATION_PROPERTY_OGRN = 5;

    const IB_ORGANIZATION_PROPERTY_KPP = 6;

    const IB_ORGANIZATION_PROPERTY_LEADER = 7;

	/** ПОЛЬЗОВАТЕЛИ ИМЕЮЩИЕ ПРАВО ГОЛОСОВАТЬ ЗА АВТОРИТЕТ */
	 const GROUP_RATING_VOTE_AUTHORITY = '4';

	/** ПОЧТОВЫЕ ПОЛЬЗОВАТЕЛИ */
	 const GROUP_MAIL_INVITED = '5';

	/** PRODUCTMARKINGCODEGROUP */
	 const HLBLOCK_B_HLSYS_MARKING_CODE_GROUP = '1';
}
