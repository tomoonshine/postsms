<?php
abstract class __modulelements extends baseModuleAdmin {

// функция формирования страницы настроек 
public function config() {
	// получить экземпляр для работы с реестром
	$regedit = regedit::getInstance();
	// создать массив
	$params = array('config' => array('int:per_page' => NULL));
	// получить первый параметр строки запроса
	$mode = getRequest("param0");
	
	// Если режим Do 
	if($mode == "do") {
		$params = $this->expectParams($params);
		$regedit->setVar("//modules/modulelements/per_page", $params['config']['int:per_page']);
		$this->chooseRedirect();
	}
	
	// запишем в массив значение из реестра
	$params['config']['int:per_page'] = (int) $regedit->getVal("//modules/modulelements/per_page");
	 
	// установить тип данных и режим
	$this->setDataType("settings");
	$this->setActionType("modify");
	 
	// подготовка данных и вывод
	$data = $this->prepareData($params, "settings");
	 
	$this->setData($data);
	return $this->doData();
}
 
// функция формирования главной страницы модуля
public function lists() {
	// Установить формат вывода list
	// Устанавливаем действие над списокм - "view" - просмотр списка
	$this->setDataType("list");
	$this->setActionType("view");
	 
	// если не XmlMode то выдоча того что есть
	if($this->ifNotXmlMode()) return $this->doData();
	 
	// Установить лимит в 20
	$limit = 20;
	 /**
    * Обратите внимание!
    * Эта строка необходима для корректного определения 
    * текущей страницы
		Возвращает параметр из $_SERVER['QUERY_STRING']
    */
	$curr_page = getRequest('p');
	// вычисляем смещение от начала списка 
	$offset = $curr_page * $limit;
	 
	// Установить параметры выборки 
	// Выбирать страницы связанных с методами groupelements и item_element
	// Установить лимит Смещение от начала списка и количество страниц
	$sel = new selector('pages');
	$sel->types('hierarchy-type')->name('modulelements', 'groupelements');
	$sel->types('hierarchy-type')->name('modulelements', 'item_element');
	$sel->limit($offset, $limit);
	 
	// ? Очевидно какой-нибудь фильтр
	selectorHelper::detectFilters($sel);
	
	// Подготавливаем данные, чтобы потом корректно их вывести
	$data = $this->prepareData($sel-> result, "pages");
	// Данные, длинна
	$this->setData($data, $sel->length);
	$this->setDataRangeByPerPage($limit, $curr_page);
	return $this->doData();
}
 
// добавление элемента или объекта
public function add() {
	$parent = $this->expectElement("param0");
	$type = (string) getRequest("param1");
	$mode = (string) getRequest("param2");
	 
	$this->setHeaderLabel("header-modulelements-add-" . $type);
	 
	$inputData = Array(	"type" => $type,
	"parent" => $parent,
	'type-id' => getRequest('type-id'),
	"allowed-element-types" => Array('groupelements', 'item_element'));
	 
	if($mode == "do") {
		$element_id = $this->saveAddedElementData($inputData);
		if($type == "item") {
			umiHierarchy::getInstance()->moveFirst($element_id, ($parent instanceof umiHierarchyElement)?$parent->getId():0);
		}
		$this->chooseRedirect();
	}
	 
	$this->setDataType("form");
	$this->setActionType("create");
	 
	$data = $this->prepareData($inputData, "page");
	 
	$this->setData($data);
	return $this->doData();
}
 
 
public function edit() {
	// Получение родительской страницы. Если передан неверный id, будет выброшен exception
	$element = $this->expectElement('param0', true);
	// echo gettype($element);
	// return;
	// Возвращает параметр из $_SERVER['QUERY_STRING']
	$mode = (string) getRequest('param1');
	 
	//
	$this->setHeaderLabel("header-modulelements-edit-" . $this->getObjectTypeMethod($element->getObject()));
	// echo $this->getObjectTypeMethod($element->getObject());
	// return;
	
	$inputData = array(
		'element'				=> $element,
		'allowed-element-types'	=> array('groupelements', 'item_element')
	);
	 
	// echo "mode ".$mode;
	// return;
	// Если режим Do 
	if($mode == "do") {
		// запись Редактированных данных
		$this->saveEditedElementData($inputData);
		//Делаем переадресацию
		$this->chooseRedirect();
	}
	
	// Установка типа данных
	// И действия для них
	$this->setDataType("form");
	$this->setActionType("modify");
	
	//Подготавливаем данные формирование xml 
	$data = $this->prepareData($inputData, "page");
	 
	$this->setData($data);
	return $this->doData();
}
 
// удаление элемента или объекта
public function del() {
	$elements = getRequest('element');

	if(!is_array($elements)) {
		$elements = array($elements);
	}
	 
	foreach($elements as $elementId) {
		$element = $this->expectElement($elementId, false, true);
	 
		$params = array(
			"element" => $element,
			"allowed-element-types" => Array('groupelements', 'item_element')
		);
		$this->deleteElement($params);
	}
	 
	$this->setDataType("list");
	$this->setActionType("view");
	$data = $this->prepareData($elements, "pages");
	$this->setData($data);
	 
	return $this->doData();
}
 
 
public function activity() {
	
	$elements = getRequest('element');
	
	// echo "+++++++++++++ this ++++++++++++++";
	// return;
	
	if(!is_array($elements)) {
		$elements = array($elements);
	}
	
	$is_active = getRequest('active');
	 
	foreach($elements as $elementId) {
		$element = $this->expectElement($elementId, false, true);
	 
		$params = array(
			"element" => $element,
			"allowed-element-types" => Array('groupelements', 'item_element'),
			"activity" => $is_active
		);
	
		$this->switchActivity($params);
		$element->commit();
	}
	 
	$this->setDataType("list");
	$this->setActionType("view");
	$data = $this->prepareData($elements, "pages");
	$this->setData($data);
	 
	return $this->doData();
}
 
// Этот метод будет вызван системой и предоставит SMC необходимые 
// параметры инициализации описание полей см. api.docs.uni-cms.ru
public function getDatasetConfiguration($param = '') {
	return array(
		'methods' => array(
			array('title'=>getLabel('smc-load'), 'forload'=>true, 'module'=>'modulelements', '#__name'=>'lists'),
			array('title'=>getLabel('smc-delete'),'module'=>'modulelements', '#__name'=>'del', 'aliases' => 'tree_delete_element,delete,del'),
			array('title'=>getLabel('smc-activity'),'module'=>'modulelements', '#__name'=>'activity', 'aliases' => 'tree_set_activity,activity'),
			array('title'=>getLabel('smc-copy'), 'module'=>'content', '#__name'=>'tree_copy_element'),
			array('title'=>getLabel('smc-move'),'module'=>'content', '#__name'=>'tree_move_element'),
			array('title'=>getLabel('smc-change-template'), 'module'=>'content', '#__name'=>'change_template'),
			array('title'=>getLabel('smc-change-lang'), 'module'=>'content', '#__name'=>'move_to_lang')),
		'types' => array(
			array('common' => 'true', 'id' => 'item_element')
		),
		'stoplist' => array(),
		'default' => 'h1[140px]'
	);
}
};
?>