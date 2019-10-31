<?php

use \Bitrix\Main\Loader;

CModule::IncludeModule("iblock");

class TestprodProducts extends CBitrixComponent
{
    const IBLOCK_ID = 5;
    const SIZE_PROP_ID = 10;

    /**
     * Проверка наличия модулей требуемых для работы компонента
     * @return bool
     * @throws Exception
     */
    private function _checkModules()
    {
        if (!Loader::includeModule('iblock')) {
            throw new Main\LoaderException(Loc::getMessage('STANDARD_ELEMENTS_LIST_CLASS_IBLOCK_MODULE_NOT_INSTALLED'));
        }

        return true;
    }

    /**
     * Подготовка параметров компонента
     * @param $params
     * @return mixed
     */
    public function onPrepareComponentParams($params)
    {
        return $_REQUEST;
    }

    /**
     * Точка входа в компонент
     */
    public function executeComponent()
    {
        $this->_checkModules();
        $this->IncludeComponentTemplate();
    }

    /**
     * добавление элемента
     * @param $request
     */
    public static function addProd($request)
    {
        $el = new \CIBlockElement;

        $size_id = self::getSizeId($request);

        $prodVal = [
            "COLOR" => $request['color'],
            "SIZE" => ["VALUE" => $size_id]
        ];
        $productProperty = [
            "IBLOCK_ID" => self::IBLOCK_ID,
            "NAME" => $request['name'],
            "PROPERTY_VALUES" => $prodVal
        ];

        if ($PRODUCT_ID = $el->Add($productProperty)) {
            $db_props = $el->GetProperty(self::IBLOCK_ID, $PRODUCT_ID);
            while ($ar_props = $db_props->Fetch()) {
                $props[] = $ar_props;
            }
            echo 'Элемент ID=' . $PRODUCT_ID . ' добавлен.';
        } else {
            echo "Error: " . $el->LAST_ERROR;
        }

    }

    /**
     * удаление элемента
     * @param $request
     */
    public static function deleteProd($request)
    {

        $el = new \CIBlockElement;
        if ($el->Delete($request['id'])) {
            echo 'Элемент ID=' . $request['id'] . ' удален.';
        } else {
            echo "Error: " . $el->LAST_ERROR;
        }
    }

    /**
     * изменение элемента
     * @param $request
     */
    public static function updateProd($request)
    {
        $el = new \CIBlockElement;
        $prodProps = [
            "COLOR" => $request['color'],
            "SIZE" => self::getSizeID($request)
        ];
        $arFields = [
            "NAME" => $request['name'],
            "PROPERTY_VALUES" => $prodProps

        ];

        if ($el->Update($request['id'], $arFields)) {
            echo 'Элемент ID=' . $request['id'] . ' обновлен.';
        } else {
            echo "Error: " . $el->LAST_ERROR;
        }
    }

    /**
     * инфо об элементе
     * @param $request
     */
    public static function getInfoProd($request)
    {
        $el = new \CIBlockElement;
        $res = $el->GetByID($request['id']);
        if ($ar_res = $res->GetNextElement()) {
            $props = $ar_res->GetProperties();
            $fields = $ar_res->GetFields();
        }

        $html = "id: {$fields['ID']}<br>";
        $html .= "name: {$fields['NAME']}<br>";
        $html .= "color: {$props['COLOR']['VALUE']}<br>";
        $html .= "size: {$props['SIZE']['VALUE_ENUM']}<br>";
        echo $html;
    }

    /**
     * добавление или возврат id размера
     * @param $request
     * @return bool|false|int|string
     */
    public static function getSizeID($request)
    {
        $size_enums = CIBlockPropertyEnum::GetList(Array("DEF" => "DESC", "SORT" => "ASC"), Array("IBLOCK_ID" => self::IBLOCK_ID, "CODE" => "SIZE"));
        while ($size_fields = $size_enums->GetNext()) {
            $size_id_vals[$size_fields["ID"]] = $size_fields["VALUE"];
        }
        if (!in_array($request['size'], array_values($size_id_vals))) {
            $size = new CIBlockPropertyEnum;
            $size_id = $size->Add(["PROPERTY_ID" => self::SIZE_PROP_ID, "VALUE" => $request['size']]);
        } else {
            $size_id = array_search($request['size'], $size_id_vals);
        }
        return $size_id;
    }

}

