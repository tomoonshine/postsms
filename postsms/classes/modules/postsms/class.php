<?php

// Методы доступные в публичной части
 
class postsms extends def_module {


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
		// Подгрузить библиотеку __admin.php и класс 
		$this->__loadLib("__admin.php");
		$this->__implement("__postsms");
	} 
}
        
public function getProvider(){

	echo 'default';
    return ;
}
        

public function config() {
	return __postsms::config();
}

 
};
?>