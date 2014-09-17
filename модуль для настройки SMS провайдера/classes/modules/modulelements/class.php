<?php

// Методы доступные в публичной части
 
class modulelements extends def_module {
public $per_page;

// Инициализация 
public function __construct() {
	parent::__construct();
	
	// Если режим работы admin
	if(cmsController::getInstance()->getCurrentMode() == "admin") {
		
		// Добавление вкладок (config стандартная вкладка)
		$configTabs = $this->getConfigTabs();
		if ($configTabs) {
			$configTabs->add("config");
		}
		// Подгрузить библиотеку __admin.php и класс __modulelements
		$this->__loadLib("__admin.php");
		$this->__implement("__modulelements");
	} else {
		// Получить из реестра значение per_page
		$this->per_page = regedit::getInstance()->getVal("//modules/modulelements/per_page");
	}
}
 
public function groupelements($path = "", $template = "default") {
	if($this->breakMe()) return;
	// получаем id текущей страницы
	$element_id = cmsController::getInstance()->getCurrentElementId();
	/*
        Сообщаем системе, что мы хотим разрешить отображать нашу страницу в панели быстрого редактирования,
        в блоке "Что редактировать", а так же в меню быстрого редактирования, доступного по нажатию Shift+D
    */
	templater::pushEditable("modulelements", "groupelements", $element_id);

	// Возвращает значения функций этого класса
	return $this->group($element_id, $template) . $this->listElements($element_id, $template);
}
 
public function item_element() {
	if($this->breakMe()) return;
	$element_id = (int) cmsController::getInstance()->getCurrentElementId();
	return $this->view($element_id);
}
 
public function group($elementPath = "", $template = "default", $per_page = false) {

	if($this->breakMe()) return;
	$hierarchy = umiHierarchy::getInstance();
	list($template_block) = def_module::loadTemplates("tpls/modulelements/{$template}.tpl", "group");

	$elementId = $this->analyzeRequiredPath($elementPath);
	 
	$element = $hierarchy->getElement($elementId);
	 
	templater::pushEditable("modulelements", "groupelements", $element->id);
	
	/*
		Возвращаем блок шаблона, полученный выше через метод loadTemplates.
		В качестве 2 аргумента передаем массив, ключи которого заменяться на макросы %array_key%
		3й аргумент означает, что этот блок шаблона выводится для страницы с id $element_id, и все макросы, которые встретятся
		в шаблоне будут заменены на соответствующие значения свойств у страницы $element_id
    */
	return self::parseTemplate($template_block, array('id' => $element->id), $element->id);
}
 
public function view($elementPath = "", $template = "default") {
	if($this->breakMe()) return;
	
	$hierarchy = umiHierarchy::getInstance();
	
	list($template_block) = def_module::loadTemplates("tpls/modulelements/{$template}.tpl", "view");
	 
	$elementId = $this->analyzeRequiredPath($elementPath);
	//echo $elementId;
	
	$element = $hierarchy->getElement($elementId);
	 
	templater::pushEditable("modulelements", "item_element", $element->id);
	
	return self::parseTemplate($template_block, array('id' => $element->id), $element->id);
}
 
 
// Выводит подкаталоги указаннго каталога с глубиной level
public function listGroup($elementPath, $level=1) {

	// Сделать выборку по групповым элементам
	$pages = new selector('pages');
	$pages->types('hierarchy-type')->name('modulelements', 'groupelements');
	
	// Если не указан каталог то вывести корневые каталоги
	if((string) $elementPath != '0')
	{
		$elementPath = $this->analyzeRequiredPath($elementPath);
		$pages->where('hierarchy')->page($elementPath)->childs($level);
	}
	else 
		$pages->where('hierarchy')->page('root');


	$block_arr = Array();	
    $lines = Array();   	
	$hierarchy = umiHierarchy::getInstance();
	foreach($pages as $page){
        $element_id = $page->id;
        $element = $hierarchy->getElement($element_id);
        if (!$element) continue; // just for safe code
 

        $line_arr = array();
        $line_arr['attribute:id'] = $page->id;
        $line_arr['attribute:name'] = $element->getName();
        $line_arr['attribute:link'] = $hierarchy->getPathById($element_id);
        $line_arr['xlink:href'] = 'upage://' . $element_id;
        if ($cur_page_id == $element_id) {
            $line_arr['attribute:active'] = '1';
        }
                 
        $lines[] = $line_arr;
	}
	
	$block_arr['subnodes:items'] = $lines;
    $block_arr['total'] = $pages->length();
    return $this->parseTemplate('', $block_arr, null);
}
 
 
public function listElements($elementPath, $template = "default", $per_page = false, $ignore_paging = false) {
// Код метода
}
 
public function config() {
	return __modulelements::config();
}
 
public function getEditLink($element_id, $element_type) {
	
	$element = umiHierarchy::getInstance()->getElement($element_id);
	$parent_id = $element->getParentId();
	 
	switch($element_type) {
		case "groupelements": {
			$link_add = $this->pre_lang . "/admin/modulelements/add/{$element_id}/item_element/";
			$link_edit = $this->pre_lang . "/admin/modulelements/edit/{$element_id}/";
			 
			return Array($link_add, $link_edit);
			break;
		}
 
		case "item_element": {
			$link_edit = $this->pre_lang . "/admin/modulelements/edit/{$element_id}/";
		 
			return Array(false, $link_edit);
			break;
		}
		 
		default: {
			return false;
		}
	}
}
 
};
?>