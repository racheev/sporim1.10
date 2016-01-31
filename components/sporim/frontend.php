<?php
function sporim(){
# компонент бесплатный 31.01.2016
	//Инициализируем объекты, которые нам понадобятся
	//для работы: ядро, страница(шаблонизатор) и пользователь
	$inCore = cmsCore::getInstance();
	$inPage = cmsPage::getInstance();
	$inUser = cmsUser::getInstance();
 
	//загрузим модель
	$inCore->loadModel('sporim');
	//создадим объект модели
	$model = new cms_model_sporim();

	//Получаем текущее действие из URL страницы
	$action     = $inCore->request('action', 'str', 'view'); // вывод самого компонента без параметров
	$zapros     = $inCore->request('zapros', 'str', ''); //


	if ($action == 'view'){ // смотрим все споры
		// получаем сообщения из базы
		$messages = $model->getAllSpor();
		// устанавливаем заголовок страницы и титл
		$inPage->setTitle("Давай поспорим");
		$inPage->addPathway('Давай поспорим');
		// подключаем шаблонизатор Smarty и сообщаем ему имя шаблона
		$smarty = $inCore->initSmarty('components', 'com_sporim_view_all.tpl');
		// передаем в шаблон 
		$smarty->assign('messages', $messages);
		// выводим шаблон на экран
		$smarty->display('com_sporim_view_all.tpl');
		//завершаем работу компонента
		return;
	}

	else if ($action == 'add'){
		$is_send = $inCore->inRequest('send'); // кнопка отправки
		if (!$is_send){            		
            //подключаем шаблон и выводим на экран
			if ($inUser->id>=1){ // авторизованый может создать
				$smarty = $inCore->initSmarty('components', 'com_sporim_add.tpl');
				$smarty->display('com_sporim_add.tpl');
			} else {
				cmsCore::addSessionMessage('Вы должны быть авторизованы на сайте!', 'error');
			}
				$inPage->setTitle("Добавить новый прецендент");
				$inPage->addPathway('Давай поспорим', '/sporim');
				$inPage->addPathway('Добавить новый прецендент');		
		}
        if ($is_send){
			// получаем все параметры сообщения
			$title      = $inCore->request('title', 'str');
			$description    = $inCore->request('description', 'str');
			$keywords    = $inCore->request('keywords', 'str');
			$keywords    = chop($keywords, ' ,');
			$user_id    = $inUser->id; // юзер
 
			// если не указана тема или сообщение
			if (!$title || !$description) {
				//сохраняем текст ошибки в сессию
				cmsCore::addSessionMessage('Укажите название и описание!', 'error');
				//и делаем редирект обратно на форму, завершая выполнение скрипта
				$inCore->redirectBack(); exit;
			}
			// добавляем сообщение и получаем его номер
			$message_id = $model->addSpor($title, $description, $keywords, $user_id);
			
			// если номер сообщения получен
			if ($message_id){
				// запишем в ленту активности событие
				// версия 2
				
				cmsActions::log('add_sporim', array( // пишем в ленту
   					'object' => $title, //Название 
   					'object_url' => '/sporim/'.$message_id.'', //ссылка на страницу с турниром
   					'object_id' => $user_id, //Уникальный ID юзера
   					'target' => $message_id, // результат
   					'target_url' => '/sporim/'.$message_id.'',  // ссылка на него 
   					'target_id' => $message_id,
   					'description' => 'Примите участие.' // описание события
				));				
				//сохраняем текст успеха в сессию
				cmsCore::addSessionMessage('Новый спор успешно добавлен!', 'success');
				
			}
			// если по каким-то причинам сообщение не добавилось
			if (!$message_id){
				//сохраняем текст неудачи
				cmsCore::addSessionMessage('Ошибка добавления спора!', 'error');
			}
			// делаем редирект на список сообщений и завершаем скрипт
			$inCore->redirect('/sporim'); exit;
		}
	}

	else if ($action == 'delete'){ // если понадобится сделаем удалялку
		//получаем ID сообщения
		$message_id = $inCore->request('zapros', 'int', 0);
		//если ID не получен или пользователь не администратор, делаем редирект обратно
		if (!$message_id || !$inUser->is_admin) {
			$inCore->redirectBack(); exit;
		}
		//удаляем сообщение
		$model->deleteSpor($message_id);
		//делаем редирект обратно к списку сообщений
		$inCore->redirect('/sporim'); exit;
		//$inCore->redirectBack(); exit;
	}
	else if ($action == 'edit'){ // если понадобится сделаем удалялку
		//получаем ID сообщения
		$message_id = $inCore->request('zapros', 'int', 0);
		//если ID не получен или пользователь не администратор, делаем редирект обратно
		if (!$message_id || !$inUser->is_admin) {
			$inCore->redirectBack(); exit;
		}
		// выбираем сообщение из базы
		$messages = $model->selectSpor();
		//делаем редирект обратно к списку сообщений
		// получаем сообщения из базы
		//$model->getAllSpor();
		// устанавливаем заголовок страницы и титл
		$inPage->setTitle('Редактируем спор - '.$messages[0]['title']);
		$inPage->addPathway('Спорим','/sporim');
		$inPage->addPathway('Редактируем спор - '.$messages[0]['title']); // название в хлебные крошки
		// подключаем шаблонизатор Smarty и сообщаем ему имя шаблона
		$smarty = $inCore->initSmarty('components', 'com_sporim_edit.tpl');
		// передаем в шаблон 
		$smarty->assign('messages', $messages[0]);
		//print_r($messages[0]);
		// выводим шаблон на экран
		$smarty->display('com_sporim_edit.tpl');
		//завершаем работу компонента
		return;
	}
//=====================================
	else if ($action == 'edited'){
		$is_send = $inCore->inRequest('send'); // кнопка отправки
		if (!$is_send) {   // если не было кнопки отправить
			//сохраняем текст ошибки в сессию
			cmsCore::addSessionMessage('Не правильные данные!', 'error');
			//и делаем редирект обратно на форму, завершая выполнение скрипта
			$inCore->redirectBack(); exit;      		
            //подключаем шаблон и выводим на экран
			//if ($inUser->id>=1){ // авторизованый может создать
			//	$smarty = $inCore->initSmarty('components', 'com_sporim_add.tpl');
			//	$smarty->display('com_sporim_add.tpl');
			//} else {
			//	cmsCore::addSessionMessage('Вы должны быть авторизованы на сайте!', 'error');
			//}
				//$inPage->setTitle("Добавить новый прецендент");
				//$inPage->addPathway('Давай поспорим', '/sporim');
				//$inPage->addPathway('Добавить новый прецендент');		
		}
        if ($is_send){
			// получаем все параметры сообщения
			$title      = $inCore->request('title', 'str');
			$description    = $inCore->request('description', 'str');
			$keywords    = $inCore->request('keywords', 'str');
			$keywords    = chop($keywords, ' ,');
			$id    = $inCore->request('id', 'int', 0);
// print_r($_POST);
			// если не указана тема или сообщение
			if (!$title || !$description) {
				//сохраняем текст ошибки в сессию
				cmsCore::addSessionMessage('Укажите название и описание!', 'error');
				//и делаем редирект обратно на форму, завершая выполнение скрипта
				$inCore->redirectBack(); exit;
			} else { // иначе редактируем сообщение
				$model->editedSpor($title, $description, $keywords, $id);
				cmsCore::addSessionMessage('Спор успешно отредактирован!', 'success');	
				$inCore->redirect('/sporim'); exit;
				
			}
			// добавляем сообщение и получаем его номер
			//$message_id = $model->addSpor($title, $description, $keywords, $user_id);
			
			// если номер сообщения получен
			//if ($message_id){
				//сохраняем текст успеха в сессию
			//	cmsCore::addSessionMessage('Спор успешно отредактирован!', 'success');		
			//}
			// если по каким-то причинам сообщение не добавилось
			//if (!$message_id){
				//сохраняем текст неудачи
			//	cmsCore::addSessionMessage('Ошибка редактирования спора!', 'error');
			//}
			// делаем редирект на список сообщений и завершаем скрипт
			//$inCore->redirect('/sporim'); exit;
		}
	}
//======================

	else if ($action == 'da'){
		if ($inUser->id>=1){ // авторизованый может голосовать
		//получаем ID сообщения из поста
		$s_id = $inCore->request('s_id', 'int', 0); // получаем номер спора
		$user_id = $inUser->id; // плучаем юзера кто голосует
		//обновляем голоса	
		// получим ок если в модели return true
		$ok = $model->addDa($s_id, $user_id);
		
			if (!$ok){
				//сохраняем текст неудачи
				cmsCore::addSessionMessage('Вы сегодня уже отдали свой голос в этом споре!', 'error');
			} else {
				cmsCore::addSessionMessage('Ваш голос принят, спасибо!', 'success');
			}
			
		} else {
				cmsCore::addSessionMessage('Вы должны быть авторизованы на сайте!', 'error');
		}
		//делаем редирект обратно к списку сообщений
		$inCore->redirectBack(); exit;

	} else if ($action == 'no'){
		if ($inUser->id>=1){ // авторизованый может голосовать
		//получаем ID сообщения
		$s_id = $inCore->request('s_id', 'int', 0);
		$user_id = $inUser->id;   
		//обновляем голоса
		// получим ок если в модели return true
		$ok = $model->addNo($s_id, $user_id);
		
			if (!$ok){
				//сохраняем текст неудачи
				cmsCore::addSessionMessage('Вы сегодня уже отдали свой голос в этом споре!', 'error');
			} else {
				cmsCore::addSessionMessage('Ваш голос принят, спасибо!', 'success');
			}
		
		} else {
				cmsCore::addSessionMessage('Вы должны быть авторизованы на сайте!', 'error');
			}
		//делаем редирект обратно к списку сообщений
		$inCore->redirectBack(); exit;
	}

	// иначе выводим сам спор. 
	else { 
		//получаем сообщения из базы
		$messages = $model->getSpor();
		//Устанавливаем заголовок страницы
		$inPage->setTitle('Давай поспорим - '. $messages[0]['title']);
		$inPage->setDescription('Давай поспорим - '. $messages[0]['description']);
		$inPage->setKeywords($messages[0]['keywords']);
		$inPage->addPathway('Давай поспорим', '/sporim');
		$inPage->addPathway($messages[0]['title']); // название в хлебные крошки
		//подключаем шаблонизатор Smarty и сообщаем ему имя шаблона
		$smarty = $inCore->initSmarty('components', 'com_sporim.tpl');
		// передаем в шаблон 
		//$usrrrr=$inUser->getKarmaFormat('1', false, 0);
		//print_r($inUser->user_id);
		//if ($messages[0]['user_id']==$inUser->is_admin) {
		if ($inUser->is_admin) {
			$smarty->assign('edited', 1);
		} else {
			$smarty->assign('edited', 0);
		}
		
		$karma = cmsUser::getKarma(1, false, 0);
		$smarty->assign('karma', $karma);
		$smarty->assign('messages', $messages);
		//выводим шаблон на экран
		$smarty->display('com_sporim.tpl');
		// версия 2
		
		$inCore->includeComments(); // подключаем коменты 
		comments('sporim', $action); // айди комента
		//завершаем работу компонента
		return;
	}
}




