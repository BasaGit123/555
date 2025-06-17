<?php

	if($act=='index') {

		echo'

		<div class="header">
			<div class="container">
				<div class="mobile-menu-wrapper">
					<ul class="nav">
						
						<a class="link" href="index.php">&#8592; Вернуться назад</a>

					</ul>
				</div>
			</div>
		</div>
	
		<div class="container mb-4">
			<form name="forma" action="module.php?module='.$MODULE.'" method="post">
				<INPUT TYPE="hidden" NAME="act" VALUE="add">

				<div class="accordion col-lg-6" id="accordionExample">
					<div class="accordion-item">
						<h3 class="accordion-header">
						<button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
							<strong>Контакты</strong>
						</button>
						</h3>
						<div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#accordionExample">
							<div class="accordion-body">
							
								<div class="mb-3">
									<label class="form-label">Телефон1</label>
									<input class="form-control tel1" type="text" name="tel1" value="'.$Contact->tel1.'" placeholder = "+7(999) 999-99-99">
								</div>
							
								<div class="mb-3">
									<label class="form-label">Телефон2</label>
									<input class="form-control tel1" type="text" name="tel2" value="'.$Contact->tel2.'" placeholder = "+7(999) 999-99-99">
								</div>

								<div class="mb-3">
									<label class="form-label">Whatsapp</label>
									<input class="form-control wa" type="text" name="wa" value="'.$Contact->wa.'" placeholder = "79999999999">
								</div>

								<div class="mb-3">
									<label class="form-label">Telegram</label>
									<input class="form-control" type="text" name="tg" value="'.$Contact->tg.'" placeholder = "Только имя профиля">
								</div>

								<div class="mb-3">
									<label class="form-label">Вконтакте</label>
									<input class="form-control" type="text" name="vk" value="'.$Contact->vk.'" placeholder = "Только имя профиля">
								</div>

								<div class="mb-3">
									<label class="form-label">Email</label>
									<input class="form-control" type="text" name="mail" value="'.$Contact->mail.'" placeholder = "exemple@mail.ru">
								</div>

							</div>
						</div>
					</div>


					<div class="accordion-item">
						<h3 class="accordion-header">
						<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
							<strong>Адрес и режим работы</strong>
						</button>
						</h3>
						<div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
							<div class="accordion-body">
							
								<div class="mb-3">
									<label class="form-label">Город</label>
									<input class="form-control" type="text" name="city" value="'.$Contact->city.'">
								</div>
							
								<div class="mb-3">
									<label class="form-label">Улица, дом</label>
									<input class="form-control" type="text" name="address" value="'.$Contact->address.'">
								</div>

								<label class="form-label">Часы работы</label>

								<div class="input-group mb-3">
									<span class="input-group-text">Пн - Пт</span>
									<input type="text" class="form-control" placeholder="с 10:00 до 18:00" name="hour1" value="'.$Contact->hour1.'">
								</div>

								<div class="input-group mb-3">
									<span class="input-group-text">Суббота</span>
									<input type="text" class="form-control" placeholder="с 10:00 до 16:00" name="hour2" value="'.$Contact->hour2.'">
								</div>

								<div class="input-group mb-3">
									<span class="input-group-text">Воск-нье</span>
									<input type="text" class="form-control" placeholder="Выходной" name="hour3" value="'.$Contact->hour3.'">
									<small class="mt-2">Если суббота и воскресенье выходные, то оставьте эти поля пустыми, на сайте будет показано что Сб-Вс: выходной</small>
								</div>

								<div class="mb-3">
									<label class="form-label">Располежение на карте</label>
									<textarea class="form-control" type="text" rows="4" name="location" placeholder = "Создайте карту на костструкторе карт от Яндекс и вставте iframe код в это поле">'.$Contact->location.'</textarea>
									<small class="mt-2"><a href="https://yandex.ru/map-constructor/" target="_blank">Нажмите сюда чтобы создать карту</a></small>
								</div>

							</div>
						</div>
					</div>

				</div>

	


					<div class="btn-gr">
						<input class="btn btn-primary" type="submit" name="" value="Сохранить">
					</div>

				
			</form>
		</div>';
    }

	if($act=='add'){

		$Contact->tel1 = htmlspecialchars(specfilter($_POST['tel1']));
		$Contact->tel2 = htmlspecialchars(specfilter($_POST['tel2']));
		$Contact->wa   = htmlspecialchars(specfilter($_POST['wa']));
		$Contact->tg   = htmlspecialchars(specfilter($_POST['tg']));
		$Contact->vk   = htmlspecialchars(specfilter($_POST['vk']));
		$Contact->mail = htmlspecialchars(specfilter($_POST['mail']));

		$Contact->city = htmlspecialchars(specfilter($_POST['city']));
		$Contact->address = htmlspecialchars(specfilter($_POST['address']));
		$Contact->hour1 = htmlspecialchars(specfilter($_POST['hour1']));
		$Contact->hour2 = htmlspecialchars(specfilter($_POST['hour2']));
		$Contact->hour3 = htmlspecialchars(specfilter($_POST['hour3']));
		$Contact->location = $_POST['location'];

		if(ClassContact::saveContact($Contact)){

			echo '<div class="msg">Изменения успешно сохранены</div>';

			System::notification('Изменено содержание. Модуль "Дополнительный контент"', 'g');

		} else {

			echo'<div class="msg">Ошибка при сохранении</div>';

			System::notification('Ошибка при сохранении изменеий. Модуль "Дополнительный контент"', 'r');

		} ?>

		<script type="text/javascript">

			setTimeout('window.location.href = \'module.php?module=<?php echo $MODULE;?>\';', 200);

		</script>

		<?php

	}

    

?>

<style>
	#accordionExample {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

	#accordionExample {
	border: 0;
    border-radius: 0;
	}

	#accordionExample .accordion-item {
	border: 0;
    border-radius: 0;
	box-shadow: var(--box-shadow);
	border-radius: var(--border-radius);
	overflow: hidden;
	}

	

</style>