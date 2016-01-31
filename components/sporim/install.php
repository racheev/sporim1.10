<?php
// ========================================================================== //

	function info_component_sporim(){
        //Описание компонента
		$_component['title']        = 'Давай поспорим';
		$_component['description']  = 'Компонент позволяет создавать споры на сайте.';
		$_component['link']         = 'sporim';
		$_component['author']       = 'Рачей';
		$_component['internal']     = '0';
		$_component['version']      = '1.10.3';
		$_component['system']    	= '0';
		return $_component;
	}

// ========================================================================== //

	function install_component_sporim(){
		$inCore     = cmsCore::getInstance();       //подключаем ядро
		$inDB       = cmsDatabase::getInstance();   //подключаем базу данных
		$inConf     = cmsConfig::getInstance();
		include($_SERVER['DOCUMENT_ROOT'].'/includes/dbimport.inc.php');
		dbRunSQL($_SERVER['DOCUMENT_ROOT'].'/components/sporim/install.sql', $inConf->db_prefix);
		
		
		// версия 2 лента и коментарии
		
		// проверим если коменты уже к компоненту, если да то не пишем!
		$sqls = "
		SELECT id
		FROM cms_comment_targets 
		WHERE target='sporim' AND component='sporim'
		";
		if (!mysql_num_rows($inDB->query($sqls))){
		$sql = "INSERT INTO cms_comment_targets (target, component, title)
				VALUES ('sporim', 'sporim', 'Давай поспорим')";
		//выполняем запрос
		$inDB->query($sql);
		}
		// проверили наличие компонентов.
		// удалим лог спора из таблицы
		$sql = "DELETE FROM cms_actions WHERE component = 'sporim'";
		//выполняем запрос
		$inDB->query($sql);
		// удалили
		// очистим лог при переинсталяции ?? потом если желание будет
		
		// удалили

		// добавление в ленту активности задел на следующую версию
		if(!cmsActions::getAction('sporim')){
			cmsActions::registerAction('sporim',
				array(
					'name'=>'add_sporim',
					'title'=>'Давай поспорим',
					'message'=>'Предлагает новую тему для спора: %s, |'
				)
			);
		}
		
		//
		
		// лента активности
		return true;

	}
// ========================================================================== //

	function upgrade_component_sporim(){
		$inCore     = cmsCore::getInstance();       //подключаем ядро
		$inDB       = cmsDatabase::getInstance();   //подключаем базу данных
		$inConf     = cmsConfig::getInstance();
		$cfg        = $inCore->loadComponentConfig('sporim');
		include($_SERVER['DOCUMENT_ROOT'].'/includes/dbimport.inc.php');
		dbRunSQL($_SERVER['DOCUMENT_ROOT'].'/components/sporim/upgrade.sql', $inConf->db_prefix);
		return true;
	}

// ========================================================================== //
?>
