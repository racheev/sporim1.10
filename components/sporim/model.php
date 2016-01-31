<?php
if(!defined('VALID_CMS')) { die('ACCESS DENIED'); }
 
class cms_model_sporim{

	function __construct(){
		$this->inDB = cmsDatabase::getInstance();
	}
	
	
	// добавляем комментарии к спорам
	
	public function getCommentTarget($target, $target_id) { // че -то тут не нравится кодеру
	
		$inCore = cmsCore::getInstance();
	
		$sql = "SELECT `title`
				FROM `cms_sporim`
				WHERE `id` = '{$target_id}'";		
		$result = $this->inDB->query($sql);
		
		while ($row = mysql_fetch_assoc($result)) {
			$title = $row['title'];
		}

		$sporim = array();
	
			$sporim['link'] = '/sporim/'.$target_id;		
			$sporim['title'] = $title;		

		return $sporim;
	}
	
	
	
	
	
	
	public function addSpor($title, $description,  $keywords, $user_id) { // добавляем спор
		//готовим запрос для добавления записи
		$sql = "INSERT INTO cms_sporim (title, description, keywords, user_id, pubdate)
				VALUES ('{$title}', '{$description}', '{$keywords}', '{$user_id}', NOW())";
		//выполняем запрос
		$this->inDB->query($sql);
		

		
		//если возникла ошибка, вернем false
		if ($this->inDB->error()) { return false; }
		//если ошибок не было, вернем ID новой записи
		return $this->inDB->get_last_id('cms_sporim');
	}
	
	
	public function editedSpor($title, $description, $keywords, $id) { // добавляем спор
		//готовим запрос для обновления записи
		
		$sql = "
		UPDATE cms_sporim 
		SET title ='{$title}'
		, description='{$description}' 
		, keywords='{$keywords}'
		WHERE id ='{$id}'
		";
		//выполняем запрос
		$this->inDB->query($sql);
		//если возникла ошибка, вернем false
		if ($this->inDB->error()) { return false; }
		//если ошибок не было, вернем ID новой записи
		return true;
	}	
		
	
	
	

	public function deleteSpor($message_id) { // удаляем спор
		$sql = "DELETE FROM cms_sporim WHERE id = '{$message_id}'";
		//выполняем запрос
		$this->inDB->query($sql);
		// надо бы и коменты удалить
		$sql = "DELETE FROM cms_comments WHERE target='sporim' AND target_id = '{$message_id}'";
		$this->inDB->query($sql);
		
		//если возникла ошибка, вернем false
		if ($this->inDB->error()) { return false; }
		//если ошибок не было, вернем true
		return true;
	}






	public function getAllSpor() { // показывает все споры
		//Подключим ядро движка, оно потребуется ниже
		$inCore = cmsCore::getInstance();
		$sql = "
		SELECT c.id, login, nickname, title, c.description, pubdate, da, no, imageurl
		FROM cms_sporim AS c
		JOIN cms_users AS u ON u.id=c.user_id
		JOIN cms_user_profiles AS p ON p.user_id=u.id
		ORDER BY pubdate
		DESC
		";
		//выполняем запрос
		$result = $this->inDB->query($sql);
		//если возникла ошибка, вернем false
		if ($this->inDB->error()) { return false; }
		//если не было получено ни одного сообщения - вернем false
		if (!$this->inDB->num_rows($result)) { return false; }
		//массив в который мы будем складывать сообщения
		//он необходим для того, чтобы мы могли вернуть все сообщения
		//за один вызов функции
		$messages = array();
		
		//получаем все сообщения по-очереди и складываем в массив
		while ($message = $this->inDB->fetch_assoc($result)){
			//c помощью метода ядра форматируем дату сообщения
			//(приводим ее в русский вид)
			$message['pubdate'] = $inCore->dateFormat($message['pubdate']);
			//и добавляем сообщение в массив
			$message['comov'] = $inCore->getCommentsCount('sporim', $message['id']);
			$messages[] = $message;
		}
		//возращаем массив сообщений
		//название каждого элемента в нем совпадает с названием полей
		//в таблице 
		return $messages;
	}




	public function getSpor() { // показывает один спор
	//echo $date=date('Y-m-d',time());
		//Подключим ядро движка, оно потребуется ниже
		$inCore = cmsCore::getInstance();
		$get_id = $inCore->request('action', 'int', 0);
		$sql = "
		SELECT c.id, c.user_id, login, nickname, title, c.description, c.keywords, pubdate, da, no, imageurl
		FROM cms_sporim AS c
		JOIN cms_users AS u ON u.id=c.user_id
		JOIN cms_user_profiles AS p ON p.user_id=u.id
		WHERE c.id='{$get_id}'
		";
		//выполняем запрос
		$result = $this->inDB->query($sql);
		//если возникла ошибка, вернем false
		if ($this->inDB->error()) { return false; }
		//если не было получено ни одного сообщения - вернем false
		if (!$this->inDB->num_rows($result)) { return false; }
		//массив в который мы будем складывать сообщения
		//он необходим для того, чтобы мы могли вернуть все сообщения
		//за один вызов функции
		$messages = array();
		//получаем все сообщения по-очереди и складываем в массив
		while ($message = $this->inDB->fetch_assoc($result)){
			//c помощью метода ядра форматируем дату сообщения
			//(приводим ее в русский вид)
			$message['pubdate'] = $inCore->dateFormat($message['pubdate']);
			//и добавляем сообщение в массив
			// посчитаем карму и в массив ее
			//$message['karma'] = cmsUser::getKarmaFormat($message['user_id'], false, 0);
			$messages[] = $message;
		}
		//возращаем массив сообщений
		//название каждого элемента в нем совпадает с названием полей
		//в таблице
		return $messages;
	}


//=================================================================================


	public function selectSpor() { // показывает один спор для редактирования
		//Подключим ядро движка, оно потребуется ниже
		$inCore = cmsCore::getInstance();
		$get_id = $inCore->request('zapros', 'int', 0);
		$sql = "
		SELECT id, title, description, keywords
		FROM cms_sporim
		WHERE id='{$get_id}'
		";
		//выполняем запрос
		$result = $this->inDB->query($sql);
		//print_r($result);
		//если возникла ошибка, вернем false
		if ($this->inDB->error()) { return false; }
		//если не было получено ни одного сообщения - вернем false
		if (!$this->inDB->num_rows($result)) { return false; }
		//массив в который мы будем складывать сообщения
		//он необходим для того, чтобы мы могли вернуть все сообщения
		//за один вызов функции
		$messages = array();
		//получаем все сообщения по-очереди и складываем в массив
		while ($message = $this->inDB->fetch_assoc($result)){
			//c помощью метода ядра форматируем дату сообщения
			//(приводим ее в русский вид)
			//$message['pubdate'] = $inCore->dateFormat($message['pubdate']);
			//и добавляем сообщение в массив
			// посчитаем карму и в массив ее
			//$message['karma'] = cmsUser::getKarmaFormat($message['user_id'], false, 0);
			$messages[] = $message;
		}
		//возращаем массив сообщений
		//название каждого элемента в нем совпадает с названием полей
		//в таблице
		return $messages;
	}


//=================================================================================
	public function addDa($s_id, $user_id) { // ставим плюс в спор
		// сначала проверим есть ли в базе запись за сегодняшний день
		$date=date('Y-m-d',time());
		$sqls = "
		SELECT id
		FROM cms_sporim_rate 
		WHERE user_id='{$user_id}' AND s_id='{$s_id}' AND date='{$date}'
		";
		if (mysql_num_rows($this->inDB->query($sqls))){		
			return false;
		} else {
		//готовим запрос для добавления записи 
		$sql = "
		UPDATE `cms_sporim` 
		SET `da` = da+1
		WHERE `id` ='{$s_id}'
		";
		//выполняем запрос
		$this->inDB->query($sql);       
		//готовим запрос для добавления записи
		$sqlu = "INSERT INTO cms_sporim_rate (s_id, user_id, date, time)
				VALUES ('{$s_id}', '{$user_id}', NOW(), NOW())";
		//выполняем запрос
		$this->inDB->query($sqlu);
		//если возникла ошибка, вернем false
		if ($this->inDB->error()) { return false; }
		//если ошибок не было, вернем ID новой записи
		//return $this->inDB->get_last_id('cms_sporim');
		return true; // сам
		}
	}




	public function addNo($s_id, $user_id) { // ставим минус в спор
		// сначала проверим есть ли в базе запись за сегодняшний день
		$date=date('Y-m-d',time());
		$sqls = "
		SELECT id
		FROM cms_sporim_rate 
		WHERE user_id='{$user_id}' AND s_id='{$s_id}' AND date='{$date}'
		";
		if (mysql_num_rows($this->inDB->query($sqls))){		
			return false;
		} else {
		//готовим запрос для добавления  минуса
		$sql = "
		UPDATE `cms_sporim` 
		SET `no` = no+1
		WHERE `id` ='{$s_id}'
		";
		//выполняем запрос
		$this->inDB->query($sql);
		//готовим запрос для добавления записи юзера отдавшего голос
		$sqlu = "INSERT INTO cms_sporim_rate (s_id, user_id, date, time)
				VALUES ('{$s_id}', '{$user_id}', NOW(), NOW())";
		//выполняем запрос
		$this->inDB->query($sqlu);
		//если возникла ошибка, вернем false
		if ($this->inDB->error()) { return false; }
		//если ошибок не было, вернем ID новой записи
		//return $this->inDB->get_last_id('cms_sporim');
		return true; // сам
		}
	}
}