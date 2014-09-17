<?php
abstract class __postsms extends baseModuleAdmin {

// функция формирования страницы настроек 
public function config() {
	// // получить экземпляр для работы с реестром
	// $regedit = regedit::getInstance();
	// // создать массив
	// $params = array('config' => array('int:per_page' => NULL));
	
	// получить первый параметр строки запроса
	$mode = getRequest("param0");
	
	// Если режим Do 
	if($mode == "do") {
		//$params = $this->expectParams($params);
		//$regedit->setVar("//modules/modulelements/per_page", $params['config']['int:per_page']);
		$this->chooseRedirect();
	}
	
	// // запишем в массив значение из реестра
	// $params['config']['int:per_page'] = (int) $regedit->getVal("//modules/modulelements/per_page");
	
	$params = array('config' => array('string:message' => NULL));
	$params['config']['string:message'] = 'Здеся будет конфиг';
	 
	// установить тип данных и режим
	$this->setDataType("settings");
	$this->setActionType("modify");
	 
	// подготовка данных и вывод
	$data = $this->prepareData($params, "settings");
	 
	$this->setData($data);
	return $this->doData();
}
 
// функция формирования главной страницы модуля
public function mainpage() {
	// Установить формат вывода setDataType
	// Устанавливаем действие над списокм - setActionType
    $this->setDataType("settings");
    $this->setActionType("view");
	 
	$params['geninform']['string:message'] = 'Здеся информация от провайдера';	
	
	// Подготавливаем данные, чтобы потом корректно их вывести
    $data = $this->prepareData($params, 'settings');
    $this->setData($data);
    return $this->doData();
}
 
 
// Этот метод будет вызван системой и предоставит SMC необходимые 
// параметры инициализации описание полей см. api.docs.uni-cms.ru
public function getDatasetConfiguration($param = '') {
	return array(
		'methods' => array(
			array('title'=>getLabel('smc-load'), 'forload'=>true, 'module'=>'postsms', '#__name'=>'mainpage')),
		'types' => array(
			array('common' => 'true', 'id' => 'smsprovider')
		),
		'stoplist' => array(),
		// 'default' => 'h1[140px]'
	);
}
};
?>