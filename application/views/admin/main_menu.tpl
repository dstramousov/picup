<ul id="nav">
	<li class="top"><a href="{php} echo base_url(); {/php}admin" id="privacy" class="top_link"><span>-</span></a></li>
	<li class="top"><a href="{php} echo base_url(); {/php}admin/view/user" id="products" class="top_link"><span class="down">Пользователи</span></a>
		<ul class="sub">
			<li><a href="{php} echo base_url(); {/php}admin/view/user">Просмотр</a></li>
			<li><a href="{php} echo base_url(); {/php}admin/useradd">Добавить</a></li>
			<!--<li><a href="{php} echo base_url(); {/php}admin/usersearch">Поиск</a></li>-->
			<li class="mid"><a href="{php} echo base_url(); {/php}admin/faces" class="fly">Лица</a>
					<ul>
						<li><a href="{php} echo base_url(); {/php}admin/facesview">Просмотр</a></li>
						<li><a href="{php} echo base_url(); {/php}admin/facessearch">Поиск</a></li>
						<li><a href="{php} echo base_url(); {/php}admin/facesload">Загрузка</a></li>
						<li><a href="{php} echo base_url(); {/php}admin/faceslearn">Обучение</a></li>
						<li><a href="{php} echo base_url(); {/php}admin/facesalg1">Алгоритм 1 </a></li>
						<li><a href="{php} echo base_url(); {/php}admin/facesalg2">Алгоритм 2 </a></li>
					</ul>
			</li>
			<li><a href="{php} echo base_url(); {/php}admin/usermapcheckout">На карте</a></li>
		</ul>
	</li>
	<li class="top"><a href="{php} echo base_url(); {/php}admin/view/coordinates" id="shop" class="top_link"><span class="down">GPS координаты</span></a>
		<ul class="sub">
			<li><a href="{php} echo base_url(); {/php}admin/view/coordinates">Просмотр</a></li>
			<li><a href="{php} echo base_url(); {/php}admin/gpsadd">Добавить</a></li>
			<li><a href="{php} echo base_url(); {/php}admin/googlemapapi">Google API</a></li>
			<li><a href="{php} echo base_url(); {/php}admin/gpsadd2">Google API +</a></li>
		</ul>
	</li>
	<li class="top"><a href="{php} echo base_url(); {/php}admin/view/photo" id="services" class="top_link"><span class="down">Фотографии</span></a>
		<ul class="sub">
			<li class="mid"><a href="{php} echo base_url(); {/php}admin/view/photo" class="fly">Анонимные</a>
					<ul>
						<li><a href="{php} echo base_url(); {/php}admin/photoanonymview">Просмотр</a></li>
						<!--<li><a href="{php} echo base_url(); {/php}admin/photoanonymsearch">Поиск</a></li>-->
					</ul>
			</li>
			<li class="mid"><a href="{php} echo base_url(); {/php}admin/view/gallery" class="fly">Пользоват.</a>
					<ul>
						<li><a href="{php} echo base_url(); {/php}admin/photoregview">Просмотр</a></li>
						<!--<li><a href="{php} echo base_url(); {/php}admin/photoregsearch">Поиск</a></li>-->
					</ul>
			</li>
			<li class="mid"><a href="{php} echo base_url(); {/php}admin/photostat" class="fly">Статистика</a>
					<ul>
						<li><a href="{php} echo base_url(); {/php}admin/photostat/1">за 1 день</a></li>
						<li><a href="{php} echo base_url(); {/php}admin/photostat/2">за 3 дня</a></li>
						<li><a href="{php} echo base_url(); {/php}admin/photostat/7">за 7 дней</a></li>
						<li><a href="{php} echo base_url(); {/php}admin/photostat/30">за 30 дней</a></li>
					</ul>
			</li>
		</ul>
	</li>
	<li class="top"><a href="{php} echo base_url(); {/php}admin/view/gallery" id="shop" class="top_link"><span class="down">Галлереи</span></a>
		<ul class="sub">
			<li><a href="{php} echo base_url(); {/php}admin/view/gallery">Просмотр</a></li>
			<li><a href="{php} echo base_url(); {/php}admin/galleryadd">Добавить</a></li>
			<!--<li><a href="{php} echo base_url(); {/php}admin/gallerysearch">Поиск</a></li>-->
		</ul>
	</li>
	<li class="top"><a href="{php} echo base_url(); {/php}admin/view/place" id="shop" class="top_link"><span class="down">Места</span></a>
		<ul class="sub">
			<li><a href="{php} echo base_url(); {/php}admin/view/place">Просмотр</a></li>
			<!--<li><a href="{php} echo base_url(); {/php}admin/edit/place">Добавить</a></li> -->
			<li class="mid"><a href="{php} echo base_url(); {/php}admin/edit/place" class="fly">Добавить</a>
					<ul>
						<li><a href="{php} echo base_url(); {/php}admin/place/addfrommap">На карте</a></li>
						<li><a href="{php} echo base_url(); {/php}admin/edit/place">Формой</a></li>
					</ul>
			</li>
			<!--<li><a href="{php} echo base_url(); {/php}admin/placessearch">Поиск</a></li>-->
			<li class="mid"><a href="{php} echo base_url(); {/php}admin/view/placedescription" class="fly">Описан. мест</a>
					<ul>
						<li><a href="{php} echo base_url(); {/php}admin/view/placedescription">Просмотр</a></li>
						<li><a href="{php} echo base_url(); {/php}admin/edit/placedescription">Добавить</a></li>
					</ul>
			</li>
			<li class="mid"><a href="{php} echo base_url(); {/php}admin/view/placetype" class="fly">Типы мест</a>
					<ul>
						<li><a href="{php} echo base_url(); {/php}admin/view/placetype">Просмотр</a></li>
						<li><a href="{php} echo base_url(); {/php}admin/edit/placetype">Добавить</a></li>
					</ul>
			</li>			
			<li><a href="{php} echo base_url(); {/php}admin/check/placemap">На карте</a></li>
		</ul>
	</li>
	<li class="top"><a href="{php} echo base_url(); {/php}admin/view/news" id="shop" class="top_link"><span class="down">Новости</span></a>
		<ul class="sub">
			<li><a href="{php} echo base_url(); {/php}admin/view/news">Просмотр</a></li>
			<li><a href="{php} echo base_url(); {/php}admin/add/news">Добавить</a></li>
			<li><a href="{php} echo base_url(); {/php}admin/news/inmap">На карте</a></li>
			<!--<li><a href="{php} echo base_url(); {/php}admin/search/news">Поиск</a></li>-->
		</ul>
	</li>
	<li class="top"><a href="{php} echo base_url(); {/php}admin/view/comment" id="shop" class="top_link"><span class="down">Комментарии</span></a>
		<ul class="sub">
			<li><a href="{php} echo base_url(); {/php}admin/view/comment">Просмотр</a></li>
			<li><a href="{php} echo base_url(); {/php}admin/edit/comment">Добавить</a></li>
			<!--<li><a href="{php} echo base_url(); {/php}admin/commentsearch">Поиск</a></li>-->
		</ul>
	</li>
	<li class="top"><a href="{php} echo base_url(); {/php}admin/view/karma" id="shop" class="top_link"><span class="down">PicUP карма</span></a>
		<ul class="sub">
			<li><a href="{php} echo base_url(); {/php}admin/view/karma">Просмотр</a></li>
			<li><a href="{php} echo base_url(); {/php}admin/edit/karma">Изменить</a></li>
			<!--<li><a href="{php} echo base_url(); {/php}admin/karmasearch">Поиск</a></li>-->
		</ul>
	</li>
	<li class="top"><a href="{php} echo base_url(); {/php}admin/statistic" id="privacy" class="top_link"><span>Статистика</span></a></li>
</ul>
