<?php 

class ClassContact {

    public static function saveContact($Contact) {

		$json = json_encode($Contact);

		return filefputs(DR.'/modules/contact/config.dat', $json, 'w+');

	}

    public static function getContact() {

            if(file_exists(DR.'/modules/contact/config.dat')) { // проверяем существование конфигурвционного файла
                $Contact = json_decode(file_get_contents(DR.'/modules/contact/config.dat'));	// создали обект с конфигами
            }

            if(!isset($Contact)) {
                $Contact = new stdClass(); // Создали пустой объект если нет объекта с конфигами
                // начиная с php8 объект должен быть обязательно создан, чтобы добавить ему свойства.
            }
            
            // определим значения поумолчанию , чтобы небыло ошибок уровня notice (необязательно, но желательно)
            
            if(!isset($Contact->tel1)) $Contact->tel1   = '';
            if(!isset($Contact->tel2)) $Contact->tel2   = '';
            if(!isset($Contact->wa))   $Contact->wa     = '';
            if(!isset($Contact->tg))   $Contact->tg     = '';
            if(!isset($Contact->vk))   $Contact->vk     = '';
            if(!isset($Contact->mail)) $Contact->mail   = '';

            if(!isset($Contact->city)) $Contact->city   = '';
            if(!isset($Contact->address)) $Contact->address   = '';
            if(!isset($Contact->hour1)) $Contact->hour1 = '';
            if(!isset($Contact->hour2)) $Contact->hour2 = '';
            if(!isset($Contact->hour3)) $Contact->hour3 = '';
            if(!isset($Contact->location)) $Contact->location = '';

            return $Contact;

    }	
}

$Contact = ClassContact::getContact();

?>