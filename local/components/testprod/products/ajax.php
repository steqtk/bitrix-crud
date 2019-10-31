<?
use Bitrix\Main\Application;
require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");

CBitrixComponent::includeComponentClass("testprod:products");
$request = Application::getInstance()->getContext()->getRequest();

        switch ($request['query'])
        {
            case 'add':
                TestprodProducts::addProd($request);
                break;
            case 'delete':
                TestprodProducts::deleteProd($request);
                break;
            case 'update':
                TestprodProducts::updateProd($request);
                break;
            case 'getinfo':
                TestprodProducts::getInfoProd($request);
                break;


        }