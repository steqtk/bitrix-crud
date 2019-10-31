<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Продукция");
?>
<?
$APPLICATION->IncludeComponent(
	"testprod:products", 
	".default", 
	array(
		"IBLOCK_TYPE" => "products",
		"IBLOCK_ID" => "5",
	),false);
?>
    <div>
        <form id="form">
            <p>ID: <input type="text" name="id"></>
            <p>Название: <input type="text" name="name"></p>
            <p>Цвет: <input type="text" name="color"></p>
            <p>Размер: <input type="text" name="size"></p>
            <p>Тип запроса: <select name="query">
                <option value="add">Add</option>
                <option value="delete">Delete</option>
                <option value="update">Update</option>
                <option value="getinfo">GetInfo</option>
            </select></p>
            <input type="hidden" name="ajax" value="yes">
            <button id="send">Отправить</button>
        </form>
    </div>
    <div id="product"></div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>