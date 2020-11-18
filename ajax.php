<?php
if(isset($_REQUEST["getnews"])){
	$result = Array(
		"newslent" => Array(
			"body" => '
				<div class="hblock">
					<div class="newsItem major place clearfix">
						<a href="#" class="placeAvatar">
							<img src="images/place.png" alt="" title="Бульвар Пушкина"/>
							<img src="images/spacer.gif" alt="" title="" class="icon beer" />
						</a>
						<a href="#" class="placeName">Бульвар Пушкина</a>
						<span class="newsDevider pad0"></span>
						бул. Пушкина
						<span class="newsDevider pad0"></span>
						<span class="newsAction"><img src="images/spacer.gif" class="icon placeup rmar5" alt="" title="" /><a href="#" class="action">Апнуть место</a> · 8 км от Вас</span>
						<span class="newsDevider tpad10"></span>
						<a href="#" class="rmar5"><img src="images/placeppl.png" alt="" title="" /></a>
						<a href="#" class="rmar5"><img src="images/placeppl.png" alt="" title="" /></a>
						<a href="#" class="rmar5"><img src="images/placeppl.png" alt="" title="" /></a>
						<a href="#" class="rmar5"><img src="images/spacer.gif" alt="" class="icon placemore" title="" /></a>
					</div>
					<div class="newsItem major clearfix">
						<a href="#" class="userAvatar"><img src="images/mediumava.png" alt="" title="Александра Оголева"/></a>
						<a href="#" class="userName">Александра Оголева</a><span class="action">Оставила комментарий</span>
						<span class="newsDevider"></span>
						Я принимаю участие в бесплатном вебинаре «Секреты саундпродюсирования или как создать клевый музыкальный продукт».
						<span class="newsDevider"></span>
						<span class="newsDate">09.08.2012</span><a href="#" class="newsAction">Апнуть</a>
					</div>
					<div class="newsItem major clearfix">
						<a href="#" class="userAvatar"><img src="images/mediumava.png" alt="" title="Александра Оголева"/></a>
						<a href="#" class="userName">Виктор Корвик</a><span class="action">Апнул место</span>
						<span class="newsDevider"></span>
						<a href="#" class="clearfix"><img src="images/spacer.gif" class="fleft icon placelink rmar15" alt="" title="Львівський головний залізничний вокзал / Lviv Main Railway Terminal" />Львівський головний залізничний вокзал / Lviv Main Railway Terminal</a>
						<span class="newsDevider"></span>
						<span class="newsDate">09.08.2012</span><a href="#" class="newsAction">Апнуть</a>
					</div>
					<div class="newsItem clearfix">
						<a href="#" class="userAvatar"><img src="images/mediumava.png" alt="" title="Александра Оголева"/></a>
						<a href="#" class="userName">Виктор Корвик</a><span class="action">Апнул место</span>
						<span class="newsDevider"></span>
						<a href="#" class="clearfix"><img src="images/spacer.gif" class="fleft icon placelink rmar15" alt="" title="Львівський головний залізничний вокзал / Lviv Main Railway Terminal" />Львівський головний залізничний вокзал / Lviv Main Railway Terminal</a>
						<span class="newsDevider"></span>
						<span class="newsDate">09.08.2012</span><a href="#" class="newsAction">Апнуть</a>
					</div>
					<div class="newsItem clearfix">
						<a href="#" class="userAvatar"><img src="images/mediumava.png" alt="" title="Александра Оголева"/></a>
						<a href="#" class="userName">Александра Оголева</a><span class="action">Оставила комментарий</span>
						<span class="newsDevider"></span>
						Я принимаю участие в бесплатном вебинаре «Секреты саундпродюсирования или как создать клевый музыкальный продукт».
						<span class="newsDevider"></span>
						<span class="newsDate">09.08.2012</span><a href="#" class="newsAction">Апнуть</a>
					</div>
					<div class="newsItem clearfix">
						<a href="#" class="userAvatar"><img src="images/mediumava.png" alt="" title="Александра Оголева"/></a>
						<a href="#" class="userName">Антон Гончаров</a><span class="action">Добавил новые фотографии</span>
						<span class="newsDevider"></span>
						Фотографии с концерта Port-Royal в Донецке
						<span class="newsDevider"></span>
						<img src="images/news1.png" alt="" title="Картинка номер 1" />
						<span class="newsDevider"></span>
						<img src="images/news2.png" class="rmar5" alt="" title="Картинка номер 2" /><img src="images/news2.png" class="rmar5" alt="" title="Картинка номер 3" /><img src="images/news2.png" alt="" title="Картинка номер 4" />
						<span class="newsDevider"></span>
						<span class="newsDate">09.08.2012</span><a href="#" class="newsAction">Апнуть</a>
					</div>
					<div class="newsItem clearfix bnone">
						<a href="#" class="userAvatar"><img src="images/mediumava.png" alt="" title="Александра Оголева"/></a>
						<a href="#" class="userName">Виктор Корвик</a><span class="action">Апнул место</span>
						<span class="newsDevider"></span>
						<a href="#" class="clearfix"><img src="images/spacer.gif" class="fleft icon placelink rmar15" alt="" title="Львівський головний залізничний вокзал / Lviv Main Railway Terminal" />Львівський головний залізничний вокзал / Lviv Main Railway Terminal</a>
						<span class="newsDevider"></span>
						<span class="newsDate">09.08.2012</span><a href="#" class="newsAction">Апнуть</a>
					</div>
				</div>
			',
			"status" => "0",
			"errstatus" => "",
			"countitems" => "1"
		),
		"datelent" => Array(
			"body" => '
				<a href="#" class="item">19</a>
				<a href="#" class="item">18</a>
				<a href="#" class="item">17</a>
				<a href="#" class="item pevent">16</a>
				<a href="#" class="item">15</a>
				<a href="#" class="item">14</a>
				<a href="#" class="item pevent">13</a>
				<a href="#" class="item pevent">12</a>
				<a href="#" class="item pevent">11</a>
				<a href="#" class="item pevent">10</a>
				<a href="#" class="item">9</a>
				<a href="#" class="item">8</a>
				<a href="#" class="item">7</a>
				<a href="#" class="item pevent">6</a>
				<a href="#" class="item pevent">5</a>
				<a href="#" class="item">4</a>
				<a href="#" class="item pevent">3</a>
				<a href="#" class="item">2</a>
				<a href="#" class="item">1</a>
				<a href="#" class="item fevent">31</a>
				<a href="#" class="item fevent">30</a>
				<a href="#" class="item fevent">29</a>
				<a href="#" class="item fevent">28</a>
				<a href="#" class="item fevent">27</a>
				<a href="#" class="item">26</a>
				<a href="#" class="item">25</a>
				<a href="#" class="item">24</a>
				<a href="#" class="item">23</a>
				<a href="#" class="item">22</a>
				<a href="#" class="item">21</a>
				<a href="#" class="item today">20</a>
			'
		),
	);	
	echo json_encode($result);
	die();
}

if($_REQUEST["getcomments"] == "Y"){
	$result = Array(
		"html" => "<b>Hello comments world</b>",
		"status" => "0",
		"errstatus" => ""
	);
	echo json_encode($result);
	die();
}

$result = Array("status"=>"ok", "resulttype"=>"message", "type"=>"corner", "text"=>"hello corner ajax window", "settings"=>Array("showtime"=>4000));
echo json_encode($result);
?>