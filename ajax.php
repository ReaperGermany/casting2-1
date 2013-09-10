<?php
    header('Content-Type: text/html; charset=utf-8');
    require_once("php/core.php");

    $objCore = new Core();

    $objCore->initSessionInfo();
    $objCore->initFormController();

    if($objCore->getSessionInfo()->isLoggedIn())
    {
        if(isset($_POST['showContent'], $_POST['cnt']))
        {
            $content = (int)$_POST['cnt'];
            
            switch($content)
            {
                case 1:
                    echo '<div class="tabbable"> 
							  <ul class="nav nav-tabs">
								<li class="active"><a href="#tab1" data-toggle="tab">Основное</a></li>
								<li><a href="#tab2" data-toggle="tab">Контакты</a></li>
								<li><a href="#tab3" data-toggle="tab">Интересы</a></li>
								<li><a href="#tab4" data-toggle="tab">Образование</a></li>
								<li><a href="#tab5" data-toggle="tab">Карьера</a></li>
							  </ul>
							  <div class="tab-content">
								<div class="tab-pane active" id="tab1">
								  <p>Я в Разделе 1.</p>
								</div>
								<div class="tab-pane" id="tab2">
								  <p>Привет, я в Разделе 2.</p>
								</div>
								<div class="tab-pane" id="tab3">
								  <p>Привет, я в Разделе 3.</p>
								</div>
								<div class="tab-pane" id="tab4">
								  <p>Привет, я в Разделе 4.</p>
								</div>
								<div class="tab-pane" id="tab5">
								  <p>Привет, я в Разделе 5.</p>
								</div>
							  </div>
					</div>';
                    break;
                case 2:
                    echo 'CONTENT #2';
                    break;
                case 3:
                    echo 'CONTENT #3';
                    break;
                case 4:
                    echo 'CONTENT #4';
                    break;
                case 5:
                    echo '<div class="tabbable"> 
							  <ul class="nav nav-tabs">
								<li class="active"><a href="#tab1" data-toggle="tab">Основное</a></li>
								<li><a href="#tab2" data-toggle="tab">Контакты</a></li>
								<li><a href="#tab3" data-toggle="tab">Интересы</a></li>
								<li><a href="#tab4" data-toggle="tab">Образование</a></li>
								<li><a href="#tab5" data-toggle="tab">Карьера</a></li>
							  </ul>
							  <div class="tab-content">
								<div class="tab-pane active" id="tab1" style="width: 100%; height:100%; overflow: auto">
								 <form method = "post" action="php/corecontroller.php" style="padding-left:20px">
									<input type="hidden" value="1" id="anketa" name="anketa"/>
									<p><b>Фамилия:</b><br>
									<input type="text" name="famely" size="15" maxlength="25" value=""><br>
									<b>Имя:</b><br>
									<input type="text" name="name" size="15" maxlength="45" value=""><br>
									<b>Отчество:</b><br>
									<input type="text" name="forname" size="15" maxlength="45" value=""><br>
									<b>Дата рождения:</b>
									<input type="text" size="1px" name="birth_day"/>
									<input type="text" size="1px" name="birth_mon"/>
									<input type="text" size="5px" name="birth_jahr"/></br>
									<b>Семейное положение:</b><br>
									<select name="sp">
										<option value="1">Женат</option>
										<option value="2">Замужем</option>
										<option value="3">Не женат</option>
										<option value="4">Не замужем</option>
										<option value="5">В разводе</option>
										<option value="6">В поисках</option>
										<option value="7">Вдова</option>
										<option value="8">Вдовец</option>
									</select><br>
									<b>Ваши Дети (номер анкеты на нашем сайте):</b>
									<input type="text" name="kids"/><br>
									<b>Наличие загран паспорта:</b>
									<input type = "radio" name = "pasport_yes">Да</input>
									<input type = "radio" name = "pasport_no">Нет</input><br>
									<b>Постоянное место нахождение:</b><br>
									<textarea name="location" heigth="20px" id="location">Ваш адрес.</textarea><br>
									<b>Ближайшая станция метро:</b><br>
									<input type="text" name="metro"><br>
									<b>Цвет волос:</b><br>
									<input type="text" name="colorHear"><br>
									<b>Цвет глаз:</b>
									<input type="text" name="colorEye"/><br>
									<b>Рост:</b><br>
									<input type="text" name="stature"/><br>
									<b>Размер обуви:</b>
									<input type="text" name="shoeSize"/><br>
									<b>Размер одежды:</b></td>
									<input type="text" name="dressSize"/>
									<b>Размер головного убора:</b></td>
									<input type="text" name="hatSize"/><br>
									<b>Объем:</b><br>
										Талия: <input type="text" size="1px" name="tal"/><br>
										Грудь: <input type="text" size="1px" name="sis"/><br>
										Бедра: <input type="text" size="1px" name="bedr"/><br>
									<b>Наличие тату:</b><br>
										<input type = "radio" name = "tatoo">Да</td>
										<b>Где:</b><br>
										<input type="text" name="tatooWhere"/>
										<input type = "radio" name = "tatoo">Нет<br>
										<b>Наличие шрамов: </b><br>
										<input type = "radio" name = "cicatrice">Да
										<b>Где:</b></td>
										<input type="text" name="cicatriceWhere"/>
										<input type = "radio" name = "cicatrice">Нет<br> 
										<b>Приметные внешние особенности:</b><br>
										<textarea name="sings" heigth="20px" id="sings">Опишите Ваши особые приметы.</textarea><br>
										<input type="submit" value="Submit!">
									</form>
								</div>
																<div class="tab-pane" id="tab2">
																  <p>Привет, я в Разделе 2.</p>
																</div>
																<div class="tab-pane" id="tab3">
																  <p>Привет, я в Разделе 3.</p>
																</div>
																<div class="tab-pane" id="tab4">
																  <p>Привет, я в Разделе 4.</p>
																</div>
																<div class="tab-pane" id="tab5">
																  <p>Привет, я в Разделе 5.</p>
																</div>
															  </div>
													</div>';
													
														
													
													
                    break;
                default:
                    echo 'CONTENT #DEFAULT';
                    break;
            }
        }
    }