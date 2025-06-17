<?php
if (!class_exists('System')) exit; // Запрет прямого доступа
$CustomizeStorage = Module::isTemlate($Page->template)?$Page->template:$Config->template;
$Customize = new EngineStorage('module.customize.'.$CustomizeStorage);
if($status == 'admin'){
	if(isset($_POST['customize'])){
		if($_POST['customize'] == 'save'){
			if (md5($_POST['ticket'].$Config->ticketSalt) == $_COOKIE['ticketCustomize']){
				foreach($_POST as $key => $value){
					if ($key != 'ticket' && $key != 'customize'){
						$Customize->set($key, trim($value));
					}
				}
				echo'Сохранено';
			}
			exit;
		}
	}
	function customizeInit(){
		global $Config, $URI;
		$ticket = random(255);
		setcookie('ticketCustomize',md5($ticket.$Config->ticketSalt),time()+32000000,'/');
		echo '<script>customizeInit("'.$ticket.'", "'.REQUEST_URI.'");</script>';
	}
	
$Page->headhtml.= '

<!-- Load Customize Module -->
<script src="/modules/customize/ckeditor/ckeditor.js"></script>
<script>
'.file_get_contents('modules/customize/jloader/jloader.min.js').'
</script>
<script>
'.file_get_contents('modules/customize/customize.min.js').'
</script>
<style>
'.file_get_contents('modules/customize/style.min.css').'
</style>

';
}

return null;
?>