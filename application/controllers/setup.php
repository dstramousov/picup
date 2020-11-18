<?php

define('RESERVED_USER_ID', 1);


/**
 * application\controllers\Setup.php
 *
 * Setup controller for manipulation with databse.
 *
 */

class Setup extends UP_Controller {


	var $places_type = array();
	var $alt_names = array();


    var $content = 'Фотохо́стинг  photo hosting веб-сайт, позволяющий публиковать любые изображения (например, цифровые фотографии) в Интернете. Любой человек, имеющий доступ к Интернету, может использовать фотохостинги для размещения, хранения и показа изображений другим пользователям сети.
Основное преимущество, которое предоставляет фотохостинг пользователям — удобство демонстрации фотографий. При размещении на фотохостинге, каждому фото присваивается уникальный адрес — URL. Автор снимка может легко поделиться гиперссылкой, ведущей на фотографию, с любым человеком, имеющим доступ в Интернет, при помощи email или IM, а также разместив её на своём сайте или блоге.
Иногда, такой сервис требует регистрации пользователя, предлагая взамен увеличение максимального размера загружаемого файла, а также предоставляя различные платные медиауслуги (печать фотографий и пр).
В США бум открытия хостингов картинок начался довольно давно. Первым был Flickr (который одним из первых сайтов внедрил понятие тегов). Интерес к фотохостингу был вызван расцветом интернет-аукционов. Поскольку для размещения лота требовались фотографии, эти сервисы стали востребованы.
Загруженные фотографии группируются в именованных альбомах, которые могут иметь четыре уровня вложенности. Внутри альбома возможны несколько способов сортировки — по времени загрузки (используется по умолчанию), дате съемки (на основе EXIF), а также ручная сортировка, когда порядок следования фотографий устанавливается мышью.
Для аннотирования фотографий помимо полей содержащих название и описание, могут использоваться теги, а на самих изображениях мышью могут указываться прямоугольные области, которым назначаются произвольные подписи, либо указывается пользователь Я.ру. Изменяя настройки своей учётной записи пользователь может разрешить другим указывать себя на фотографиях, а также добавлять теги на его фотографии.
Управление уровнями доступа позволяет ограничивать видимость как отдельных фотографий (видна всем, только друзьям на Я.ру либо только для самого владельца), а также защищать от просмотра паролем целые альбомы.
Загруженные фотографии могут быть подвергнуты простейшей обработке — поворот, кадрирование, линейная тоновая коррекция (яркость, контраст, насыщенность).
Пользователи принимают участие в разнообразных творческих фотоконкурсах, с системой голосования, которая исключает махинации с голосами и их накрутку.EXIF-данные в усечённом виде доступны для просмотра. Отображаются следующие из них: условия съёмки (выдержка, диафрагма, фокусное расстояние, ISO, значение экспокоррекции), а также время съемки и производитель с моделью камеры. Последнее является гиперссылкой, перейдя по которой пользователь попадает на описание соответствующего устройства на Яндекс.Маркет. На основе данных о времени съёмки — изображения можно сортировать внутри альбома, используя поиск по сервису — возможно найти снимки сделанные определённой моделью фотоаппарата. EXIF-данные содержит только изображение-оригинал, уменьшенные же варианты — их не имеют.
Фотокамеры, оснащённые датчиком ориентации, записывают своё положение на момент съёмки в EXIF, что позволяет сервису Яндекс. Фотки разворачивать фотографии в нормальное положение без дополнительных действий со стороны пользователя.
Если в Exif фотографии прописаны координаты места съёмки (либо GPS-приёмником встроенным в камеру или пользователем вручную), то после загрузки снимки будут автоматически позиционированы на карте мира, которая внедряется в страницу из сервиса Яндекс.Карты. Из других фотохостингов подобной возможностью обладают Panoramio, Flickr и Picasa Web Albums. Также, если в EXIF (а точнее в IPTC) фотографии есть теги или описание — то они будут учтены при загрузке фотографий на сервис (только в случае использования страницы Adobe Flash загрузчика';

    function __construct()
    {
		$this->app_mode = 'setup';

		parent::__construct();

		$this->create_current_user();

		$this->load->dbforge();

		log_message('debug', 'Setup controller has initialized');
	}

    function create_current_user() {
        $this->authorize();
    }

	/**
	 * Main action.
     *
     * @access public
	 */
	function index()
	{
        $data = array('msg' => '');
        $this->load->view('setup/index.php', $data);
	}

	function insert_init()
	{
		$this->yw_templater->assign('msg', 'INIT data inserted successful.');
	}
	
	// _makeRandomDateInclusive('2009-04-01','2009-04-03');
	function _makeRandomDateInclusive($startDate,$endDate)
	{
		$datestart = strtotime($startDate);//you can change it to your timestamp;
		$dateend = strtotime($endDate);//you can change it to your timestamp;

		// Generate random number using above bounds
		$val = rand($datestart, $dateend);

		// Convert back to desired date format
		return date('Y-m-d H:i:s', $val);
	
		/*
		$datestart = strtotime($startDate);//you can change it to your timestamp;
		$dateend = strtotime($endDate);//you can change it to your timestamp;
		

		$daystep = 86400;

		$datebetween = abs(($dateend - $datestart) / $daystep);
		dump($datebetween);

		$randomday = rand(0, $datebetween);


		dump(date("Y-m-d", $datestart + ($randomday * $daystep)));
		
		
		
	
	
		$days = round((strtotime($endDate) - strtotime($startDate)) / (60 * 60 * 24));
		
		$minutes = round((strtotime($endDate) - strtotime($startDate)) / (60));
		$n = rand(0,$days);
		$nn = rand(0,$minutes);
		return date("Y-m-d H:i:s",strtotime("$startDate + $n days + $nn minutes"));   
		*/
	} // end of function 
	
	function random_float ($min,$max) {
		return ($min+lcg_value()*(abs($max-$min)));
	}	
	
	
	function generateNickname()
	{
		$first_l = array("Cool","Masked","Bloody","Lame","Big","Stupid","Drunk","Rotten",
							"Blue","Black","White","Red","Purple","Golden","Silver", 'Вовчик', 'Петька', 'Димон', 'Тоха', 'Эдик', );
			
		$second_l = array("Hamster","Moose","Lama","Duck","Bear","Eagle","Tiger",
							"Rocket","Bullet","Knee","Foot","Hand", 'Рябой','Косой','Прямой','Чоткий','Красава','Мурзик','Пупсик','Ботан','Кабан','Шкал');
							
		$i1 = array_rand($first_l, 1);
		$i2 = array_rand($second_l, 1);
		return ($first_l[$i1].' '.strtolower($second_l[$i2]));
	}
	
	function generateRandomString($min, $max, $type = 1)
	{
		if($type == 1){
			$letters = 'qwertyuiopasdfghjklzxcvbnm';
		} elseif($type == 2){
			$letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
		} elseif($type == 3){
			$letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		}
		
		$length = rand($min, $max);
		
		$s = '';
		$lettersLength = strlen($letters)-1;
     
		for($i = 0 ; $i < $length ; $i++){
			$s .= $letters[rand(0,$lettersLength)];
		}
     
		return $s;
	} 	
	
	function genDesc($_needed_word)
	{
		$_arr = $this->str_word_count_utf8($this->content,1);
		$wc = explode(' ',$this->content);
		
		$from = rand(1, $_arr-50);
		$out = array_slice($wc, $from, $_needed_word);  
        $__s = implode(" ", $out);
        
        $__s = mb_strtolower($__s);
        
        $vowels = array(",", ".", "[", "]", "{", "}", "(", ")", "'", "-", "\r\n", "\r", "\n", "\t");
		return str_replace($vowels, "", $__s);
	}
	
	function str_word_count_utf8($str) {
		return count(preg_split('~[^\p{L}\p{N}\']+~u',$str));
	}
	
	function substr_unicode($str, $s, $l = null) {
		return join("", array_slice(
			preg_split("//u", $str, -1, PREG_SPLIT_NO_EMPTY), $s, $l));
	}

	function my_ucfirst($string, $e ='utf-8')
	{
        $orig_string = $string;
        if (function_exists('mb_strtoupper') && function_exists('mb_substr') && !empty($string)) {
            $string = mb_strtolower($string, $e);
            $upper = mb_strtoupper($string, $e);
            preg_match('#(.)#us', $upper, $matches);
            $string = $matches[1] . mb_substr($string, 1, mb_strlen($string, $e), $e);
        } else {
            $string = ucfirst($string);
        }
       
        $string1 = $this->substr_unicode($string, 0, 1);
        $string2 = $this->substr_unicode($orig_string, 1);
        $string = $string1 . $string2;
       
        return $string;
    }


	function ra($_a){
		$rand_keys = array_rand ($_a);
		return $_a[$rand_keys];	
	}
    
    function getLD()
    {
        return '2005-01-01';
    }
	
	function fillCountSeeIt()
	{
		// 6 gallery 
        $__data = array();
		for($i = 1 ; $i <= 300 ; $i++ )
		{
			$data = array(
				'created'       =>  $this->_makeRandomDateInclusive($this->getLD(),$this->getN()),
				'gallery_id'	=> rand(1, 6),
			);
            array_push($__data, $data);
		}
		$this->db->insert_batch('countseeit', $__data);
		
		// 30 photos
        $__data = array();
		for($i = 1 ; $i <= 300 ; $i++ )
		{
			$data = array(
				'created'       =>  $this->_makeRandomDateInclusive($this->getLD(),$this->getN()),
				'photo_id'	=> rand(1, 30),
			);
                array_push($__data, $data);
		}
        
		$this->db->insert_batch('countseeit', $__data);
	} // end of function 
	
	function fillFollowUp()
	{        
        $__data = array();
		for($i = 1 ; $i <= 100 ; $i++ )
		{
			$data = array(
				'created'       =>  $this->_makeRandomDateInclusive($this->getLD(),$this->getN()),
				'user_id'	=> rand(1, 100),
				'fup_user_id'	=> rand(1, 100),
			);
                array_push($__data, $data);
		//	$this->db->insert('followup', $data);
		}
		//$this->db->insert_batch('followup', $__data);
        
        $__data = array();
		for($i = 1 ; $i <= 100 ; $i++ )
		{
			$data = array(
				'created'       =>  $this->_makeRandomDateInclusive($this->getLD(),$this->getN()),
				'user_id'	=> rand(1, 100),
				'fup_place_id'	=> rand(1, 50),
			);
                array_push($__data, $data);
			//$this->db->insert('followup', $data);
		}
		//$this->db->insert_batch('followup', $__data);
        //dump(1);
        
        $__data = array();
        for($i = 1 ; $i <= 100 ; $i++ )
		{
			$data = array(
				'created'       =>  $this->_makeRandomDateInclusive($this->getLD(),$this->getN()),
				'user_id'	    => rand(1, 100),
				'fup_group_id'	=> rand(1, 100),
			);
                array_push($__data, $data);
		}
		$this->db->insert_batch('followup', $__data);
	} // end of function 
	
	function fillKarma()
	{	
		$min = $this->config->item('karma_min_value');
		$max = $this->config->item('karma_max_value');
	
        $__data = array();
		for($i = 1 ; $i <= 100 ; $i++ )
		{
			$data = array(
				'user_id'	=> $i,
				'updated'   =>  $this->_makeRandomDateInclusive($this->getLD(),$this->getN()),
				'value'		=> rand($min, $max),
			);
                array_push($__data, $data);
		}
		$this->db->insert_batch('karma', $__data);
	} // end of function 
	
	function getNow()
	{
		$now		= new DateTime("now");
		return $now->format('Y-m-d H:i:s');
	} // end of function 
	
	function getN()
	{
		$now		= new DateTime("now");
		return $now->format('Y-m-d');
	} // end of function 
	
	function fillPlaceType()
	{
		$data = array(
			'name'	=> 'Бары/Рестораны',
			'icon'	=> 'eat.jpg',
		);
		$this->db->insert('placetype', $data);
	
		$data = array(
			'name'	=> 'Исторические памятники',
			'icon'	=> 'history.jpg',
		);
		$this->db->insert('placetype', $data);

		$data = array(
			'name'	=> 'Кинотеатры',
			'icon'	=> 'cinema.jpg',
		);
		$this->db->insert('placetype', $data);
		
		$data = array(
			'name'	=> 'Отдых/Курорты',
			'icon'	=> 'free.jpg',
		);
		$this->db->insert('placetype', $data);

		$data = array(
			'name'	=> 'Другое',
			'icon'	=> 'other.jpg',
		);
		$this->db->insert('placetype', $data);
		
		$data = array(
			'name'	=> 'Улица/Проспект',
			'icon'	=> 'prospect.jpg',
		);
		$this->db->insert('placetype', $data);
		
		$data = array(
			'name'	=> 'Район',
			'icon'	=> 'area.jpg',
		);
		$this->db->insert('placetype', $data);

		$data = array(
			'name'	=> 'Город',
			'icon'	=> 'city.jpg',
		);
		$this->db->insert('placetype', $data);
		
		$data = array(
			'name'	=> 'Область',
			'icon'	=> 'countryarea.jpg',
		);
		$this->db->insert('placetype', $data);
		
		$data = array(
			'name'	=> 'Страна',
			'icon'	=> 'country.jpg',
		);
		$this->db->insert('placetype', $data);
	} // end of function 
	
	function fillNews()
	{
		$min = $this->config->item('karma_min_value');
		$max = $this->config->item('karma_max_value');
	
        $__data = array();
		for($i = 1 ; $i <= 500 ; $i++ )
		{
			$data = array(
				'user_id'		=> rand(1, 100),
				'coordinate_id'	=> rand(1, 60),
				'created'		=> $this->_makeRandomDateInclusive('2009-06-12 12:12:12','2010-06-12 13:13:13'),
				'datestart'		=> $this->_makeRandomDateInclusive('2010-07-12 12:12:12','2011-06-12 13:13:13'),
				'datestop'		=> $this->_makeRandomDateInclusive('2011-06-12 13:13:13','2012-12-12 13:13:13'),
				'body'			=> $this->my_ucfirst(strtolower($this->genDesc(100))),
				'radius'		=> rand($min, $max),
			);
            array_push($__data, $data);
		}
        $this->db->insert_batch('news', $__data);
	} // end of function 
    
	function fillGroupNews()
	{	
		$min = $this->config->item('karma_min_value');
		$max = $this->config->item('karma_max_value');
	
        $__data = array();
		for($i = 1 ; $i <= 200 ; $i++ )
		{
			$data = array(
				'user_id'		=> rand(1, 100),
				'group_id'		=> rand(1, 50),
				'created'		=> $this->_makeRandomDateInclusive('2009-06-12 12:12:12','2010-06-12 13:13:13'),
				'datestart'		=> $this->_makeRandomDateInclusive('2010-07-12 12:12:12','2011-06-12 13:13:13'),
				'datestop'		=> $this->_makeRandomDateInclusive('2011-06-12 13:13:13','2012-12-12 13:13:13'),
				'body'			=> $this->my_ucfirst(strtolower($this->genDesc(100))),
//				'radius'		=> rand($min, $max),
			);
            array_push($__data, $data);
		}
		$this->db->insert_batch('group_news', $__data);
        //dump();
	} // end of function 
    
    
		
	function placeDescription()
	{
        $__data = array();
		for($i = 1 ; $i <= 100 ; $i++ )
		{
			$data = array(
				'user_id'		=> rand(1, 100),
				'place_id'		=> rand(1, 10),
				'description'	=> $this->genDesc(50),
				'created'		=> $this->_makeRandomDateInclusive($this->getLD(),$this->getNow()),
			);
            array_push($__data, $data);
		}
        $this->db->insert_batch('placedescription', $__data);
	} // end of function
	
	
	function upIt()
	{
        $__data = array();
		for($i = 1 ; $i <= 200 ; $i++ )
		{
			$data = array(
				'user_id'		=> rand(1, 100),
				'created'		=> $this->_makeRandomDateInclusive($this->getLD(),$this->getN()),
				'upit_user_id'		=> rand(1, 100),
			);
            array_push($__data, $data);
			//$this->db->insert('upit', $data);
			
            $data = array();
			$data = array(
				'user_id'		=> rand(1, 100),
				'created'		=> $this->_makeRandomDateInclusive($this->getLD(),$this->getN()),
				'upit_place_id'		=> rand(1, 100),
			);
            array_push($__data, $data);
			//$this->db->insert('upit', $data);
			
            $data = array();
			$data = array(
				'user_id'		=> rand(1, 100),
				'created'		=> $this->_makeRandomDateInclusive($this->getLD(),$this->getN()),
				'upit_gallery_id'		=> rand(1, 100),
			);
            array_push($__data, $data);
			//$this->db->insert('upit', $data);
			
            $data = array();
			$data = array(
				'user_id'		=> rand(1, 100),
				'created'		=> $this->_makeRandomDateInclusive($this->getLD(),$this->getN()),
				'upit_photo_id'		=> rand(1, 10),
			);
            array_push($__data, $data);
		}
        
        $this->db->insert_batch('upit', $__data);
        
	} // end of function 
    
    
    function fillGroup()
    {
        $__data = array();
		for($i = 1 ; $i <= 100 ; $i++ )
		{
			$data = array(
                'group_name'    => ucfirst(strtolower($this->genDesc(1))),
                'internal_name' => md5(strtolower(random_string('alnum', 1))),
				'created'		=> $this->_makeRandomDateInclusive($this->getLD(),$this->getN()),
				'who_created_user_id' => rand(1, 100),
			);
            
            array_push($__data, $data);        
        }
        
		$this->db->insert_batch('group', $__data);
    } // end of function 
    
    function fillMessages()
    {
        $count = 500;
        $__data = array();
		for($i = 1 ; $i <= $count ; $i++ )
		{
			$data = array(
                'status'                => $this->ra(array('unreaded','readed', 'archived', 'closed')),
                'body'                  => $this->genDesc(20),
				'created'		        => $this->_makeRandomDateInclusive($this->getLD(),$this->getN()),
				'sender_user_id'        => rand(1, 100),
				'recipient_user_id'     => rand(1, 20),
			);
            array_push($__data, $data);
        }
        $this->db->insert_batch('messages', $__data);
    } // end of function 
	
	
	function insert_test()
	{    
		// News /////////////////
		//$this->fillNews();

        // groupnews
        //$this->fillGroupNews();
        
        
		$count_tested_records = 100;
		
		$this->load->helper('string');
        
        // comments
        //$this->fillComments();
        

        // messages
        //$this->fillMessages();
        //dump(1);
       
        // group
        //$this->fillGroup();
		
        
        
        // karms
		//$this->fillKarma();		
		
						
        ///////////////////////////////		
		// UpIt /////////////////
		//$this->upIt();
						
		// placetype /////////////////
		//$this->fillPlaceType();
        ///////////////////////////////		
		
		// placetype /////////////////
		//$this->placeDescription();
        ///////////////////////////////		
				
		// followup
		//$this->fillFollowUp();
		
		// Countseeit /////////////////
		//$this->fillCountSeeIt();
        //dump(1);
								
		// PLACES AND COORDINATES  /////////////////////////////////////////////////////////
		$this->parseGeoNamesORGData();
		//$this->fillPlacesAndCoordinates();
		////////////////////////////////////////////////////////////////////////////////////
						
		$this->load->helper('awatar');
		$_arr_awatars = getAwatarsArray();
        // 1. User
        $data = array(
			'id' 			=>  RESERVED_USER_ID,
            'created'       =>  $this->getNow(),
            'email'         =>  "admin@picup.com.ua",
            'nickname'      =>  "creator",
            'first_name'    =>  "creator",
            'last_name'     =>  "creator",
            'password'      =>  md5('test'),
            'status'        =>  'suspended',
			'usertype'		=>  'normal',
			'last_coordinate_id' => NULL,
			'last_checkin'	=> NULL,
			'last_login'	=> NULL,
			'last_ip'		=> '192.168.0.1',
        );
        $this->db->insert('user', $data);
		
		
        $data = array(
            'created'       =>  $this->_makeRandomDateInclusive($this->getLD(),$this->getN()),
            'email'         =>  "user1@gmail.com",
            'nickname'      =>  "putin",
            'first_name'    =>  "Владимир",
            'last_name'     =>  "Путин",
            'password'      =>  md5('test'),
            'status'        =>  'suspended',
			'usertype'		=>  'gold',
			'last_coordinate_id' => rand(1, 100),
			'avatar'		=> 	array_rand($_arr_awatars, 1),
			'last_checkin'	=> $this->_makeRandomDateInclusive($this->getLD(),$this->getN()),
			'last_login'	=> $this->_makeRandomDateInclusive($this->getLD(),$this->getN()),
			'last_ip'		=> '192.168.0.1',
        );
        $this->db->insert('user', $data);
        
        $data = array(
            'created'       =>  $this->_makeRandomDateInclusive($this->getLD(),$this->getN()),
            'email'         =>  "user2@gmail.com",
            'nickname'      =>  "andrushaka",
            'first_name'    =>  "Андрей",
            'last_name'     =>  "Кама",
            'password'      =>  md5('test'),
            'status'        =>  'suspended',
			'usertype'		=>  'silver',
			'last_coordinate_id' => rand(1, 100),
			'avatar'		=> 	array_rand($_arr_awatars, 1),
			'last_checkin'	=> $this->_makeRandomDateInclusive($this->getLD(),$this->getN()),
			'last_login'	=> $this->_makeRandomDateInclusive($this->getLD(),$this->getN()),
			'last_ip'		=> '192.168.0.1',
        );
        $this->db->insert('user', $data);
        
        $data = array(
            'created'       =>  $this->_makeRandomDateInclusive($this->getLD(),$this->getN()),
            'email'         =>  "user3@gmail.com",
            'nickname'      =>  "stalker",
            'first_name'    =>  "Владимир",
            'last_name'     =>  "Ульянов",
            'password'      =>  md5('test'),
            'status'        =>  'active',
			'usertype'		=>  'platinum',
			'last_coordinate_id' => rand(1, 100),
			'avatar'		=> 	array_rand($_arr_awatars, 1),
			'last_checkin'	=> $this->_makeRandomDateInclusive($this->getLD(),$this->getN()),
			'last_login'	=> $this->_makeRandomDateInclusive($this->getLD(),$this->getN()),
			'last_ip'		=> '192.168.0.1',
        );
        $this->db->insert('user', $data);

		//////////////////////////////////////////////////////
        $data = array(
            'created'       =>  $this->_makeRandomDateInclusive($this->getLD(),$this->getN()),
            'email'         =>  "user56@gmail.com",
            'nickname'      =>  "stalker12",
            'first_name'    =>  "Василий",
            'last_name'     =>  "Пупкин",
            'password'      =>  md5('test'),
            'status'        =>  'active',
			'usertype'		=>  'gold',
			'last_coordinate_id' => rand(1, 100),
			'avatar'		=> 	array_rand($_arr_awatars, 1),
			'last_checkin'	=> $this->_makeRandomDateInclusive($this->getLD(),$this->getN()),
			'last_login'	=> $this->_makeRandomDateInclusive($this->getLD(),$this->getN()),
			'last_ip'		=> '192.168.0.1',
        );
        $this->db->insert('user', $data);
        $data = array(
            'created'       =>  $this->_makeRandomDateInclusive($this->getLD(),$this->getN()),
            'email'         =>  "user78@gmail.com",
            'nickname'      =>  "pupkin",
            'first_name'    =>  "Alesandro",
            'last_name'     =>  "Che",
            'password'      =>  md5('test'),
            'status'        =>  'active',
			'usertype'		=>  'gold',
			'last_coordinate_id' => rand(1, 100),
			'avatar'		=> 	array_rand($_arr_awatars, 1),
			'last_checkin'	=> $this->_makeRandomDateInclusive($this->getLD(),$this->getN()),
			'last_login'	=> $this->_makeRandomDateInclusive($this->getLD(),$this->getN()),
			'last_ip'		=> '192.168.0.1',
        );
        $this->db->insert('user', $data);
        
        $data = array(
            'created'       =>  $this->_makeRandomDateInclusive($this->getLD(),$this->getN()),
            'email'         =>  "user@gmail.com",
            'nickname'      =>  "cherdak",
            'first_name'    =>  "Лена",
            'last_name'     =>  "Толстая",
            'password'      =>  md5('test'),
            'status'        =>  'active',
			'usertype'		=>  'gold',
			'last_coordinate_id' => rand(1, 100),
			'avatar'		=> 	array_rand($_arr_awatars, 1),
			'last_checkin'	=> $this->_makeRandomDateInclusive($this->getLD(),$this->getN()),
			'last_login'	=> $this->_makeRandomDateInclusive($this->getLD(),$this->getN()),
			'last_ip'		=> '192.168.0.1',
        );
        $this->db->insert('user', $data);
        
        $data = array(
            'created'       =>  $this->_makeRandomDateInclusive($this->getLD(),$this->getN()),
            'email'         =>  "user@gmail.com",
            'nickname'      =>  "bbb1987",
            'first_name'    =>  "Натик",
            'last_name'     =>  "Круксин",
            'password'      =>  md5('test'),
            'status'        =>  'active',
			'usertype'		=>  'silver',
			'last_coordinate_id' => rand(1, 100),
			'avatar'		=> 	array_rand($_arr_awatars, 1),
			'last_checkin'	=> $this->_makeRandomDateInclusive($this->getLD(),$this->getN()),
			'last_login'	=> $this->_makeRandomDateInclusive($this->getLD(),$this->getN()),
			'last_ip'		=> '192.168.0.1',
        );
        $this->db->insert('user', $data);
        
        $data = array(
            'created'       =>  $this->_makeRandomDateInclusive($this->getLD(),$this->getN()),
            'email'         =>  "bullet@gmail.com",
            'nickname'      =>  "bullet",
            'first_name'    =>  "Иван",
            'last_name'     =>  "Дурак",
            'password'      =>  md5('test'),
            'status'        =>  'active',
			'usertype'		=>  'normal',
			'last_coordinate_id' => rand(1, 100),
			'avatar'		=> 	array_rand($_arr_awatars, 1),
			'last_checkin'	=> $this->_makeRandomDateInclusive($this->getLD(),$this->getN()),
			'last_login'	=> $this->_makeRandomDateInclusive($this->getLD(),$this->getN()),
			'last_ip'		=> '192.168.0.1',
        );
        $this->db->insert('user', $data);
        
        $data = array(
            'created'       =>  $this->_makeRandomDateInclusive($this->getLD(),$this->getN()),
            'email'         =>  "car@gmail.com",
            'nickname'      =>  "car",
            'first_name'    =>  "Ленка",
            'last_name'     =>  "Перова",
            'password'      =>  md5('test'),
            'status'        =>  'active',
			'usertype'		=>  'normal',
			'last_coordinate_id' => rand(1, 100),
			'avatar'		=> 	array_rand($_arr_awatars, 1),
			'last_checkin'	=> $this->_makeRandomDateInclusive($this->getLD(),$this->getN()),
			'last_login'	=> $this->_makeRandomDateInclusive($this->getLD(),$this->getN()),
			'last_ip'		=> '192.168.0.1',
        );
        $this->db->insert('user', $data);
        $data = array(
            'created'       =>  $this->_makeRandomDateInclusive($this->getLD(),$this->getN()),
            'email'         =>  "noname@gmail.com",
            'nickname'      =>  "noname",
            'first_name'    =>  "Хрен",
            'last_name'     =>  "Узнаете",
            'password'      =>  md5('test'),
            'status'        =>  'active',
			'usertype'		=>  'normal',
			'last_coordinate_id' => rand(1, 100),
			'avatar'		=> 	'noavatar.jpg',
			'last_checkin'	=> $this->_makeRandomDateInclusive($this->getLD(),$this->getN()),
			'last_login'	=> $this->_makeRandomDateInclusive($this->getLD(),$this->getN()),
			'last_ip'		=> '192.168.0.1',
        );
        $this->db->insert('user', $data);
		
		$count_users = 100000;
		
        $__data = array();
		for($i = 1 ; $i <= $count_users ; $i++)
		{
			$data = array(
				'created'       =>  $this->_makeRandomDateInclusive($this->getLD(),$this->getN()),
				'email'         =>  strtolower(random_string('alnum', 16))."@gmail.com",
				'nickname'      =>  $this->generateNickname(),
				'first_name'    =>  strtolower(random_string('alnum', 5)),
				'last_name'     =>  strtolower(random_string('alnum', 10)),
				'password'      =>  md5('test'),
				'status'        =>  $this->ra(array('active','suspended', 'closed')),
				'usertype'		=>  $this->ra(array('normal','silver', 'gold', 'platinum')),
				'last_coordinate_id' => rand(1, 100),
				'avatar'		=> 	array_rand($_arr_awatars, 1),
				'last_checkin'	=> $this->_makeRandomDateInclusive($this->getLD(),$this->getN()),
				'last_login'	=> $this->_makeRandomDateInclusive($this->getLD(),$this->getN()),
				'last_ip'		=> '192.168.0.1',
			);
            array_push($__data, $data);
            //			$this->db->insert('user', $data);
		}
        $this->db->insert_batch('user', $__data);
		///////////////////////////////////////////////////////
        
       // 1. Gallery
		$g_p_id_1 = rand(1, 100);
        $data = array(
            'created'       =>  date("Y-m-d H:i:s") ,
            'user_id'       =>  1,
			'coordinate_id'		=> rand(1, 100),
			'name'			=> $this->my_ucfirst(strtolower($this->genDesc(100))),
            'internal_name' =>  md5('2012-01-01 10:18:36'),			
            'status'        =>  'active',
            'description'   =>  $this->my_ucfirst(strip_tags(strtolower($this->genDesc(100)))),
            'user_perm'     =>  'all',
        );
        $this->db->insert('gallery', $data);
        
		$g_p_id_2 = rand(1, 100);
        $data = array(
            'created'       =>  date("Y-m-d H:i:s") ,
            'user_id'       =>  1,
			'coordinate_id'		=> rand(1, 100),
			'name'			=> $this->my_ucfirst(strtolower($this->genDesc(100))),
            'internal_name' =>  md5('2012-01-01 12:18:36'),
            'status'        =>  'active',
            'description'   =>  $this->my_ucfirst(strip_tags(strtolower($this->genDesc(100)))),
            'user_perm'     =>  'all',
        );
        $this->db->insert('gallery', $data);
        
		$g_p_id_3 = rand(1, 100);
        $data = array(
            'created'       =>  date("Y-m-d H:i:s") ,
            'user_id'       =>  2,
			'coordinate_id'		=> rand(1, 100),
			'name'			=> $this->my_ucfirst(strtolower($this->genDesc(100))),
            'internal_name' =>  md5('2012-01-01 9:18:36'),
            'status'        =>  'active',
            'description'   =>  $this->my_ucfirst(strip_tags(strtolower($this->genDesc(100)))),
            'user_perm'     =>  'noany',
        );
        $this->db->insert('gallery', $data);
        
		$g_p_id_4 = rand(1, 100);
        $data = array(
            'created'       =>  date("Y-m-d H:i:s") ,
            'user_id'       =>  2,
			'coordinate_id'		=> $g_p_id_4,
			'name'			=> $this->my_ucfirst(strtolower($this->genDesc(100))),
            'internal_name' =>  md5('2012-01-01 9:11:36'),
            'status'        =>  'active',
            'description'   =>  $this->my_ucfirst(strip_tags(strtolower($this->genDesc(100)))),
            'user_perm'     =>  'all',
        );
        $this->db->insert('gallery', $data);

		$g_p_id_5 = rand(1, 100);
        $data = array(
            'created'       =>  date("Y-m-d H:i:s") ,
            'user_id'       =>  3,
			'coordinate_id'		=> $g_p_id_5,
			'name'			=> $this->my_ucfirst(strtolower($this->genDesc(100))),
            'internal_name' =>  md5('2011-01-01 1:11:36'),
            'status'        =>  'active',
            'description'   =>  $this->my_ucfirst(strip_tags(strtolower($this->genDesc(100)))),
            'user_perm'     =>  'all',
        );
        $this->db->insert('gallery', $data);
        
		$g_p_id_6 = rand(1, 100);
        $data = array(
            'created'       =>  date("Y-m-d H:i:s") ,
            'user_id'       =>  3,
			'coordinate_id'		=> $g_p_id_6,
			'name'			=> $this->my_ucfirst(strtolower($this->genDesc(100))),
            'internal_name' =>  md5('2010-01-01 1:11:36'),
            'status'        =>  'suspended',
            'description'   =>  $this->my_ucfirst(strip_tags(strtolower($this->genDesc(100)))),
            'user_perm'     =>  'all',
        );
        $this->db->insert('gallery', $data);
		
        // 1. Photo
		$upl_date = '2010-01-01 1:11:36';
        $data = array(
            'created'       	=>  $upl_date ,
            'user_id'			=>  1,
            'internal_name'		=>  md5($upl_date),
            'gallery_id'       	=>  1,
			'coordinate_id'			=> $g_p_id_1, 
            'status'  			=>  'active',
            'user_perm'    		=>  'all',
			'extension'			=>	'.jpg',
			'name'			=> $this->my_ucfirst(strtolower($this->genDesc(100))),
            'description'   =>  $this->my_ucfirst(strip_tags(strtolower($this->genDesc(50)))),
        );
        $this->db->insert('photo', $data);
		
		$upl_date = '2010-01-01 1:11:40';
        $data = array(
            'created'       	=>  $upl_date ,
            'user_id'			=>  1,
            'internal_name'		=>  md5($upl_date),
            'gallery_id'       	=>  1,
			'coordinate_id'			=> $g_p_id_1, 
            'status'  			=>  'active',
            'user_perm'    		=>  'all',
			'extension'			=>	'.jpg',
			'name'			=> $this->my_ucfirst(strtolower($this->genDesc(100))),
            'description'   =>  $this->my_ucfirst(strip_tags(strtolower($this->genDesc(50)))),
        );
        $this->db->insert('photo', $data);
		
		$upl_date = '2010-01-01 1:11:45';
        $data = array(
            'created'       	=>  $upl_date ,
            'user_id'			=>  1,
            'internal_name'		=>  md5($upl_date),
            'gallery_id'       	=>  1,
			'coordinate_id'			=> $g_p_id_1, 
            'status'  			=>  'active',
            'user_perm'    		=>  'noany',
			'extension'			=>	'.jpg',
			'name'			=> $this->my_ucfirst(strtolower($this->genDesc(100))),
            'description'   =>  $this->my_ucfirst(strip_tags(strtolower($this->genDesc(50)))),
        );
        $this->db->insert('photo', $data);
		
		$upl_date = '2010-01-01 1:11:47';
        $data = array(
            'created'       	=>  $upl_date ,
            'user_id'			=>  1,
            'internal_name'		=>  md5($upl_date),
            'gallery_id'       	=>  2,
			'coordinate_id'			=> $g_p_id_2, 
            'status'  			=>  'active',
            'user_perm'    		=>  'all',
			'extension'			=>	'.jpg',
			'name'			=> $this->my_ucfirst(strtolower($this->genDesc(100))),
            'description'   =>  $this->my_ucfirst(strip_tags(strtolower($this->genDesc(50)))),
        );
        $this->db->insert('photo', $data);
		
		$upl_date = '2010-01-01 1:11:49';
        $data = array(
            'created'       	=>  $upl_date ,
            'user_id'			=>  1,
            'internal_name'		=>  md5($upl_date),
            'gallery_id'       	=>  2,
			'coordinate_id'			=> $g_p_id_2, 
            'status'  			=>  'active',
            'user_perm'    		=>  'all',
			'extension'			=>	'.jpg',
			'name'			=> $this->my_ucfirst(strtolower($this->genDesc(100))),
            'description'   =>  $this->my_ucfirst(strip_tags(strtolower($this->genDesc(50)))),
        );
        $this->db->insert('photo', $data);
		
		$upl_date = '2010-01-01 1:12:36';
        $data = array(
            'created'       	=>  $upl_date ,
            'user_id'			=>  2,
            'internal_name'		=>  md5($upl_date),
            'gallery_id'       	=>  3,
			'coordinate_id'			=> $g_p_id_3, 
            'status'  			=>  'active',
            'user_perm'    		=>  'all',
			'extension'			=>	'.jpg',
			'name'			=> $this->my_ucfirst(strtolower($this->genDesc(100))),
            'description'   =>  $this->my_ucfirst(strip_tags(strtolower($this->genDesc(50)))),
        );
        $this->db->insert('photo', $data);
		
		$upl_date = '2010-01-01 1:13:36';
        $data = array(
            'created'       	=>  $upl_date ,
            'user_id'			=>  3,
            'internal_name'		=>  md5($upl_date),
            'gallery_id'       	=>  5,
			'coordinate_id'			=> $g_p_id_5, 
            'status'  			=>  'active',
            'user_perm'    		=>  'all',
			'extension'			=>	'.jpg',
			'name'			=> $this->my_ucfirst(strtolower($this->genDesc(100))),
            'description'   =>  $this->my_ucfirst(strip_tags(strtolower($this->genDesc(50)))),
        );
        $this->db->insert('photo', $data);
		
		$upl_date = '2010-01-01 1:14:36';
        $data = array(
            'created'       	=>  $upl_date ,
            'user_id'			=>  3,
            'internal_name'		=>  md5($upl_date),
            'gallery_id'       	=>  5,
			'coordinate_id'		=> $g_p_id_5, 
            'status'  			=>  'active',
            'user_perm'    		=>  'all',
			'extension'			=>	'.jpg',
			'name'			=> $this->my_ucfirst(strtolower($this->genDesc(100))),
            'description'   =>  $this->my_ucfirst(strip_tags(strtolower($this->genDesc(50)))),
        );
        $this->db->insert('photo', $data);
		
		$upl_date = '2010-01-01 1:15:36';
        $data = array(
            'created'       	=>  $upl_date ,
            'user_id'			=>  3,
            'internal_name'		=>  md5($upl_date),
            'gallery_id'       	=>  6,
			'coordinate_id'			=> $g_p_id_6, 
            'status'  			=>  'suspended',
            'user_perm'    		=>  'all',
			'extension'			=>	'.jpg',
			'name'			=> $this->my_ucfirst(strtolower($this->genDesc(100))),
            'description'   =>  $this->my_ucfirst(strip_tags(strtolower($this->genDesc(50)))),
        );
        $this->db->insert('photo', $data);
		
		$upl_date = '2010-01-01 1:16:36';
        $data = array(
            'created'       	=>  $upl_date ,
            'user_id'			=>  3,
            'internal_name'		=>  md5($upl_date),
            'gallery_id'       	=>  6,
			'coordinate_id'			=> $g_p_id_6, 
            'status'  			=>  'active',
            'user_perm'    		=>  'all',
			'extension'			=>	'.jpg',
			'name'			=> $this->my_ucfirst(strtolower($this->genDesc(100))),
            'description'   =>  $this->my_ucfirst(strip_tags(strtolower($this->genDesc(50)))),
        );
        $this->db->insert('photo', $data);
		
		$upl_date = '2010-01-01 1:17:36';
        $data = array(
            'created'       	=>  $upl_date ,
            'user_id'			=>  3,
            'internal_name'		=>  md5($upl_date),
            'gallery_id'       	=>  6,
			'coordinate_id'			=> $g_p_id_6, 
            'status'  			=>  'active',
            'user_perm'    		=>  'all',
			'extension'			=>	'.jpg',
			'name'			=> $this->my_ucfirst(strtolower($this->genDesc(100))),
            'description'   =>  $this->my_ucfirst(strip_tags(strtolower($this->genDesc(50)))),
        );
        $this->db->insert('photo', $data);
		
		$upl_date = '2010-01-01 1:22:36';
        $data = array(
            'created'       	=>  $upl_date ,
            'user_id'			=>  3,
            'internal_name'		=>  md5($upl_date),
            'gallery_id'       	=>  6,
			'coordinate_id'			=> $g_p_id_6, 
            'status'  			=>  'active',
            'user_perm'    		=>  'all',
			'extension'			=>	'.jpg',
			'name'			=> $this->my_ucfirst(strtolower($this->genDesc(100))),
            'description'   =>  $this->my_ucfirst(strip_tags(strtolower($this->genDesc(50)))),
        );
        $this->db->insert('photo', $data);
		
		$upl_date = '2010-01-01 1:31:36';
        $data = array(
            'created'       	=>  $upl_date ,
            'user_id'			=>  3,
            'internal_name'		=>  md5($upl_date),
            'gallery_id'       	=>  6,
			'coordinate_id'			=> $g_p_id_6, 
            'status'  			=>  'suspended',
            'user_perm'    		=>  'all',
			'extension'			=>	'.jpg',
			'name'			=> $this->my_ucfirst(strtolower($this->genDesc(100))),
            'description'   =>  $this->my_ucfirst(strip_tags(strtolower($this->genDesc(50)))),
        );
        $this->db->insert('photo', $data);
		
		//
		$upl_date = '2010-01-01 2:13:32';
        $data = array(
            'created'       	=>  $upl_date ,
            'user_id'			=>  2,
            'internal_name'		=>  md5($upl_date),
            'gallery_id'       	=>  4,
			'coordinate_id'			=> $g_p_id_4, 
            'status'  			=>  'active',
            'user_perm'    		=>  'noany',
			'extension'			=>	'.jpg',
			'name'			=> $this->my_ucfirst(strtolower($this->genDesc(100))),
            'description'   =>  $this->my_ucfirst(strip_tags(strtolower($this->genDesc(50)))),
        );
        $this->db->insert('photo', $data);
		
		$upl_date = '2010-01-01 3:13:32';
        $data = array(
            'created'       	=>  $upl_date ,
            'user_id'			=>  2,
            'internal_name'		=>  md5($upl_date),
            'gallery_id'       	=>  4,
			'coordinate_id'			=> $g_p_id_4, 
            'status'  			=>  'active',
            'user_perm'    		=>  'all',
			'extension'			=>	'.jpg',
			'name'			=> $this->my_ucfirst(strtolower($this->genDesc(100))),
            'description'   =>  $this->my_ucfirst(strip_tags(strtolower($this->genDesc(50)))),
        );
        $this->db->insert('photo', $data);
		
		$upl_date = '2010-01-01 4:13:32';
        $data = array(
            'created'       	=>  $upl_date ,
            'user_id'			=>  2,
            'internal_name'		=>  md5($upl_date),
            'gallery_id'       	=>  4,
			'coordinate_id'			=> $g_p_id_4, 
            'status'  			=>  'active',
            'user_perm'    		=>  'all',
			'extension'			=>	'.jpg',
			'name'			=> $this->my_ucfirst(strtolower($this->genDesc(100))),
            'description'   =>  $this->my_ucfirst(strip_tags(strtolower($this->genDesc(50)))),
        );
        $this->db->insert('photo', $data);
		
		$upl_date = '2010-01-01 5:13:32';
        $data = array(
            'created'       	=>  $upl_date ,
            'user_id'			=>  2,
            'internal_name'		=>  md5($upl_date),
            'gallery_id'       	=>  4,
			'coordinate_id'			=> $g_p_id_4, 
            'status'  			=>  'active',
            'user_perm'    		=>  'all',
			'extension'			=>	'.jpg',
			'name'			=> $this->my_ucfirst(strtolower($this->genDesc(100))),
            'description'   =>  $this->my_ucfirst(strip_tags(strtolower($this->genDesc(50)))),
        );
        $this->db->insert('photo', $data);
		
		$upl_date = '2010-01-01 6:13:32';
        $data = array(
            'created'       	=>  $upl_date ,
            'user_id'			=>  2,
            'internal_name'		=>  md5($upl_date),
            'gallery_id'       	=>  4,
			'coordinate_id'			=> $g_p_id_4, 
            'status'  			=>  'active',
            'user_perm'    		=>  'all',
			'extension'			=>	'.jpg',
			'name'			=> $this->my_ucfirst(strtolower($this->genDesc(100))),
            'description'   =>  $this->my_ucfirst(strip_tags(strtolower($this->genDesc(50)))),
        );
        $this->db->insert('photo', $data);
		
		$upl_date = '2010-01-01 1:13:32';
        $data = array(
            'created'       	=>  $upl_date ,
            'user_id'			=>  2,
            'internal_name'		=>  md5($upl_date),
            'gallery_id'       	=>  4,
			'coordinate_id'			=> $g_p_id_4, 
            'status'  			=>  'active',
            'user_perm'    		=>  'all',
			'extension'			=>	'.jpg',
			'name'			=> $this->my_ucfirst(strtolower($this->genDesc(100))),
            'description'   =>  $this->my_ucfirst(strip_tags(strtolower($this->genDesc(50)))),
        );
        $this->db->insert('photo', $data);
		
		$upl_date = '2010-02-02 1:13:32';
        $data = array(
            'created'       	=>  $upl_date ,
            'user_id'			=>  2,
            'internal_name'		=>  md5($upl_date),
            'gallery_id'       	=>  4,
			'coordinate_id'			=> $g_p_id_4, 
            'status'  			=>  'suspended',
            'user_perm'    		=>  'all',
			'extension'			=>	'.jpg',
			'name'			=> $this->my_ucfirst(strtolower($this->genDesc(100))),
            'description'   =>  $this->my_ucfirst(strip_tags(strtolower($this->genDesc(50)))),
        );
        $this->db->insert('photo', $data);
		
		// anonymous photo
		$upl_date = '2010-02-02 1:13:32';
        $data = array(
            'created'       	=>  $upl_date ,
            'internal_name'		=>  md5($upl_date),
            'status'  			=>  'active',
            'user_perm'    		=>  'all',
			'extension'			=>	'.jpg',
			'name'			=> $this->my_ucfirst(strtolower($this->genDesc(100))),
            'description'   =>  $this->my_ucfirst(strip_tags(strtolower($this->genDesc(50)))),
        );
        $this->db->insert('photo', $data);
		
		$upl_date = '2011-05-05 1:13:32';
        $data = array(
            'created'       =>  $upl_date ,
            'internal_name'	=>  md5($upl_date),
            'status'  		=>  'active',
            'user_perm'    	=>  'all',
			'extension'		=>	'.jpg',
			'name'			=> $this->my_ucfirst(strtolower($this->genDesc(100))),
            'description'   =>  $this->my_ucfirst(strip_tags(strtolower($this->genDesc(50)))),
        );
        $this->db->insert('photo', $data);
		
		$upl_date = '2009-01-12 1:13:32';
        $data = array(
            'created'       	=>  $upl_date ,
            'internal_name'		=>  md5($upl_date),
            'status'  			=>  'active',
            'user_perm'    		=>  'all',
			'extension'			=>	'.jpg',
			'name'			=> $this->my_ucfirst(strtolower($this->genDesc(100))),
            'description'   =>  $this->my_ucfirst(strip_tags(strtolower($this->genDesc(50)))),
        );
        $this->db->insert('photo', $data);
		
		$upl_date = '2011-03-09 1:13:32';
        $data = array(
            'created'       	=>  $upl_date ,
            'internal_name'		=>  md5($upl_date),
            'status'  			=>  'active',
            'user_perm'    		=>  'all',
			'extension'			=>	'.jpg',
			'name'			=> $this->my_ucfirst(strtolower($this->genDesc(100))),
            'description'   =>  $this->my_ucfirst(strip_tags(strtolower($this->genDesc(50)))),
        );
        $this->db->insert('photo', $data);
		
		$upl_date = '2010-05-12 1:13:32';
        $data = array(
            'created'       	=>  $upl_date ,
            'internal_name'		=>  md5($upl_date),
            'status'  			=>  'active',
            'user_perm'    		=>  'all',
			'extension'			=>	'.jpg',
			'name'			=> $this->my_ucfirst(strtolower($this->genDesc(100))),
            'description'   =>  $this->my_ucfirst(strip_tags(strtolower($this->genDesc(50)))),
        );
        $this->db->insert('photo', $data);
		
		$upl_date = '2010-06-12 1:13:32';
        $data = array(
            'created'       	=>  $upl_date ,
            'internal_name'		=>  md5($upl_date),
            'status'  			=>  'active',
            'user_perm'    		=>  'all',
			'extension'			=>	'.jpg',
			'name'			=> $this->my_ucfirst(strtolower($this->genDesc(100))),
            'description'   =>  $this->my_ucfirst(strip_tags(strtolower($this->genDesc(50)))),
        );
        $this->db->insert('photo', $data);
		
		$upl_date = '2010-12-12 1:13:32';
        $data = array(
            'created'       	=>  $upl_date ,
            'internal_name'		=>  md5($upl_date),
            'status'  			=>  'active',
            'user_perm'    		=>  'all',
			'extension'			=>	'.jpg',
			'name'			=> $this->my_ucfirst(strtolower($this->genDesc(100))),
            'description'   =>  $this->my_ucfirst(strip_tags(strtolower($this->genDesc(50)))),
        );
        $this->db->insert('photo', $data);
		
		$upl_date = '2011-11-02 1:13:32';
        $data = array(
            'created'       	=>  $upl_date ,
            'internal_name'		=>  md5($upl_date),
            'status'  			=>  'active',
            'user_perm'    		=>  'all',
			'extension'			=>	'.jpg',
			'name'			=> $this->my_ucfirst(strtolower($this->genDesc(100))),
            'description'   =>  $this->my_ucfirst(strip_tags(strtolower($this->genDesc(50)))),
        );
        $this->db->insert('photo', $data);
		
		$upl_date = '2011-01-03 1:13:32';
        $data = array(
            'created'       	=>  $upl_date ,
            'internal_name'		=>  md5($upl_date),
            'status'  			=>  'active',
            'user_perm'    		=>  'all',
			'extension'			=>	'.jpg',
			'name'			=> $this->my_ucfirst(strtolower($this->genDesc(100))),
            'description'   =>  $this->my_ucfirst(strip_tags(strtolower($this->genDesc(50)))),
        );
        $this->db->insert('photo', $data);
		
		$upl_date = '2011-04-01 1:13:32';
        $data = array(
            'created'       	=>  $upl_date ,
            'internal_name'		=>  md5($upl_date),
            'status'  			=>  'active',
            'user_perm'    		=>  'all',
			'extension'			=>	'.jpg',
			'name'			=> $this->my_ucfirst(strtolower($this->genDesc(100))),
            'description'   =>  $this->my_ucfirst(strip_tags(strtolower($this->genDesc(50)))),
        );
        $this->db->insert('photo', $data);
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        
        $data = array('msg' => 'TEST data inserted successful.');
        $this->load->view('setup/index', $data);		
	} // end of function 
    
    
    function fillComments()
    {
		// Comments for photo
		$count_comment = 100;
        $__data = array();
		for($i = 1 ; $i <= $count_comment ; $i++)
        {
			$data = array(
				'created'       	=>  $this->_makeRandomDateInclusive($this->getLD(),$this->getN()),
				'user_id'			=>  rand(1, 10000),
				'photo_id'       	=>  rand(1, 30),
				'parent_id'       	=>  0,
				'status'  			=>  $this->ra(array('active','suspended')),
				'body'   =>  $this->my_ucfirst(strip_tags(strtolower($this->genDesc(50)))),
			);
            array_push($__data, $data);
		}
		$this->db->insert_batch('comments', $__data);			
		
		// Comments for gallery
        $__data = array();
		$count_comment = 100;
		for($i = 1 ; $i <= $count_comment ; $i++){
			$data = array(
				'created'       	=>  $this->_makeRandomDateInclusive($this->getLD(),$this->getN()),
				'user_id'			=>  rand(1, 10000),
				'gallery_id'       	=>  rand(1, 6),
				'parent_id'       	=>  0,
				'status'  			=>  $this->ra(array('active','suspended')),
				'body'              =>  $this->my_ucfirst(strip_tags(strtolower($this->genDesc(50)))),
			);
            array_push($__data, $data);
		}        
		$this->db->insert_batch('comments', $__data);			
    } // end of function
	
	
	function loadPlacesType()
	{
		$data_folders = 'add_names/places_type.txt';
		$handle = @fopen($data_folders, "r");
		$_arr = array();
		
		if ($handle) {
			while (($buffer = fgets($handle, 4096)) !== false) {
				$arr = preg_split('/\t/', $buffer, -1);
				if($arr){
					$name = $this->mb_ucfirst(trim($arr[1]));
					if($arr[2]){
						$arr[2] = $this->mb_ucfirst(trim($arr[2]));
					}
					$data = array(
						'name'		=> $name,
						'code'		=> $arr[0],
						's_desc'	=> $name,
						'e_desc'	=> $arr[2],
						'icon'	=> 'default.jpg',
					);
					$this->db->insert('placetype', $data);
				} // end if
			} // end while
		} // end if
	} // end of function 
	
	function mb_ucfirst ($word)
	{
		return mb_strtoupper(mb_substr($word, 0, 1, 'UTF-8'), 'UTF-8') . mb_substr(mb_convert_case($word, MB_CASE_LOWER, 'UTF-8'), 1, mb_strlen($word), 'UTF-8');
	}	
	
	
	function parseGeoNamesORGData()
	{
		$data_folders = 'pl_co/';
		
		// 1. load places type
		$this->loadPlacesType();
		
		if ($handle = opendir($data_folders)) {
			while (false !== ($entry = readdir($handle))) {
				if ($entry != "." && $entry != "..") {
					$this->parseDataGeoFile($data_folders.$entry);
				}
			}
			closedir($handle);
		}		
		
	}
	
	
	function parseDataGeoFile($parsed_file)
	{
		$handle = @fopen($parsed_file, "r");
		$_arr = array();
		
		if ($handle) {
			while (($buffer = fgets($handle, 4096)) !== false) {
				$arr = preg_split('/\t/', $buffer, -1);
				$_USER_ID = rand(1, 20);
				
				$__date = $this->_makeRandomDateInclusive($this->getLD(),$this->getN());
				
				// coordinates
				$__NAME = $this->db->escape_str($arr[1]);
				$___arr3 = preg_split('/,/', $arr[3]);
				if($___arr3){
					foreach($___arr3 as $n){
						$patter = "|[а-яё]|is"; 
						if(preg_match($patter, $n)){
							$__NAME = $n;
						}
					}
				} // end if 
				
				$data = array(
					'user_id'       		=> $_USER_ID,
					'created'       		=> $__date,
					'latitude'				=> number_format($arr[4], 14, '.', ''),
					'longitude'  			=> number_format($arr[5], 14, '.', ''),
					'altitude'    			=> $arr[15],
					'trusted'				=> 'yes',
					'maxapproximation'		=>	rand(5, 14),
				);
				$this->db->insert('coordinates', $data);
				$_COORD_ID = $this->db->insert_id();
				
				if($arr[6] && $arr[7]){
					$PL_CODE = $arr[6].'.'.$arr[7];
				} else {
					$PL_CODE = 'll';
				}
				
				// places				
				$data = array(
					'created'       		=> $__date,
					'coord_id'       		=> $_COORD_ID,
					'user_id'				=> $_USER_ID,
					'placetype_code'		=> $PL_CODE,
					'geonameid'				=> $this->db->escape_str($arr[0]),
					'name'					=> $__NAME,
					'asciiname'				=> $this->db->escape_str($arr[2]),
					'alternatenames'		=> $this->db->escape_str($arr[3]),
//					'feature_class'			=> $this->db->escape_str($arr[6]),
//					'feature_code'			=> $this->db->escape_str($arr[7]), 	
					'country_code'			=> $this->db->escape_str($arr[8]),
					'cc2'					=> $this->db->escape_str($arr[9]),
					'admin1_code'			=> $this->db->escape_str($arr[10]),
					'admin2_code'			=> $this->db->escape_str($arr[11]),
					'admin3_code'			=> $this->db->escape_str($arr[12]),
					'admin4_code'			=> $this->db->escape_str($arr[13]),
					'population'			=> $this->db->escape_str($arr[14]),
					'elevation'				=> $this->db->escape_str($arr[16]),
					'dem'					=> $this->db->escape_str($arr[16]),
					'timezone'				=> $this->db->escape_str($arr[17]),
					'sysdescription'   		=> $this->my_ucfirst(strip_tags(strtolower($this->genDesc(50)))),
				);
				$this->db->insert('place', $data);
			}
			if (!feof($handle)) {
				echo "Error: unexpected fgets() fail\n";
			}
			fclose($handle);
		}		
	} // end of function 
	
	

	function update_table()
	{
        $data = array('msg' => 'Function in current time not supported.');
        $this->load->view('setup/index', $data);
	}

	function delete_table()
	{           
        $query = $this->db->query("SHOW TABLES");
        $__tbl_names = array();
        
        foreach ($query->result_array() as $row)
        {   
            $this->dbforge->drop_table($row['Tables_in_pikup']);
            array_push($__tbl_names, $row['Tables_in_pikup']);
        }
    
        if($__tbl_names){
            $data = array('msg' => 'Tables ['.implode(', <br/>', $__tbl_names).'] was DELETED successful.');
        } else {
            $data = array('msg' => 'Tables not found in to DB.');
        }
        
        $this->load->view('setup/index', $data);
	}


	function create_table()
	{
		$__tbl_names = array();

		// tables definition ////////////////////////////////////////////////////
        
		// table name: User
		$tablename = 'user';
		$this->dbforge->add_field("`".CT_PK."` int(11) NOT NULL auto_increment");
        $this->dbforge->add_field("`created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP");
		$this->dbforge->add_field("`nickname` varchar(50) NOT NULL");
		$this->dbforge->add_field("`first_name` char(50) NULL");
		$this->dbforge->add_field("`last_name` char(100) NULL");
		$this->dbforge->add_field("`email` varchar(100) NOT NULL");
		$this->dbforge->add_field("`status` enum('active','suspended','closed') NOT NULL default 'active'");
		$this->dbforge->add_field("`usertype` enum('normal', 'silver', 'gold', 'platinum') NOT NULL default 'normal'");
        $this->dbforge->add_field("`last_coordinate_id` INT(11) NULL");
        $this->dbforge->add_field("`relation_id` INT(11) NULL");  // for which user 
		$this->dbforge->add_field("`gender` enum('male','female','unset') NOT NULL default 'male'");
        $this->dbforge->add_field("`last_checkin` DATETIME NULL");
        $this->dbforge->add_field("`last_login` DATETIME NULL");
		$this->dbforge->add_field("`last_ip` char(15) NULL");
        $this->dbforge->add_field("`password` char(32) NULL");
		$this->dbforge->add_field("`avatar` char(50) NOT NULL DEFAULT 'noavatar.jpg'");
		
        $this->dbforge->add_key(CT_PK, TRUE);
        $this->dbforge->add_key('email');
        $this->dbforge->add_key('nickname');
        $this->dbforge->add_key('created');

        $this->dbforge->create_table($tablename, TRUE);
		array_push($__tbl_names, $tablename);

		// table name: Group
		$tablename = 'user_event';
		$this->dbforge->add_field("`".CT_PK."` int(11) NOT NULL auto_increment");
		$this->dbforge->add_field("`user_id` int(11) NOT NULL");  
		$this->dbforge->add_field("`description` varchar(255) NOT NULL");  
        $this->dbforge->add_field("`created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP");
        $this->dbforge->add_field("`date` DATETIME NOT NULL");
		$this->dbforge->add_field("`user_perm` enum('i', 'friend', 'all') NOT NULL default 'i'");
		$this->dbforge->add_field("`status` enum('active','suspended','closed') NOT NULL default 'active'");
//		$this->dbforge->add_field("`period` enum('active','suspended','closed') NOT NULL default 'active'");
		
        $this->dbforge->add_key(CT_PK, TRUE);
        $this->dbforge->add_key('user_id');

        $this->dbforge->create_table($tablename, TRUE);
		array_push($__tbl_names, $tablename);
				
		// table name: Relations
		$tablename = 'relations';
		$this->dbforge->add_field("`".CT_PK."` int(11) NOT NULL auto_increment");
		$this->dbforge->add_field("`name` varchar(100) NOT NULL");  
		
        $this->dbforge->add_key(CT_PK, TRUE);
        $this->dbforge->add_key('name');

        $this->dbforge->create_table($tablename, TRUE);
		array_push($__tbl_names, $tablename);		

		// table name: Messages
		$tablename = 'messages';
		$this->dbforge->add_field("`".CT_PK."` int(11) NOT NULL auto_increment");
		$this->dbforge->add_field("`sender_user_id` int(11) NOT NULL");  
		$this->dbforge->add_field("`recipient_user_id` int(11) NOT NULL");  
		$this->dbforge->add_field("`body` varchar(1024) NOT NULL");  
        $this->dbforge->add_field("`created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP");
		$this->dbforge->add_field("`status` enum('unreaded', 'readed', 'archived','closed') NOT NULL default 'unreaded'");
		
        $this->dbforge->add_key(CT_PK, TRUE);
        $this->dbforge->add_key('sender_user_id');
        $this->dbforge->add_key('recipient_user_id');
        $this->dbforge->add_key('created');
        $this->dbforge->add_key('status');

        $this->dbforge->create_table($tablename, TRUE);
		array_push($__tbl_names, $tablename);

        
		// table name: Group
		$tablename = 'group';
		$this->dbforge->add_field("`".CT_PK."` int(11) NOT NULL auto_increment");
		$this->dbforge->add_field("`group_name` varchar(100) NOT NULL");  
		$this->dbforge->add_field("`internal_name` char(32) NOT NULL");  
		$this->dbforge->add_field("`icon` char(20) NULL");
        $this->dbforge->add_field("`who_created_user_id` INT(11) NULL");  // who created  
        $this->dbforge->add_field("`created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP");
		
        $this->dbforge->add_key(CT_PK, TRUE);

        $this->dbforge->create_table($tablename, TRUE);
		array_push($__tbl_names, $tablename);
        
        // table name: group_news
		$tablename = 'group_news';
		$this->dbforge->add_field("`".CT_PK."` int(11) NOT NULL auto_increment");
        $this->dbforge->add_field("`user_id` INT(11) NULL");
        $this->dbforge->add_field("`group_id` INT(11) NULL");  // для какой группы
        $this->dbforge->add_field("`created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP");
        $this->dbforge->add_field("`datestart` DATETIME NOT NULL");
        $this->dbforge->add_field("`datestop`  DATETIME NULL");
		$this->dbforge->add_field("`status` enum('active','suspended','closed') NOT NULL default 'active'");
		$this->dbforge->add_field("`body` varchar(1000) NOT NULL");   // Тело новости
		$this->dbforge->add_field("`coordinate_id` INT(11) NULL");
		$this->dbforge->add_field("`radius` INT(7) UNSIGNED NOT NULL default 0");
		
        $this->dbforge->add_key(CT_PK, TRUE);
        $this->dbforge->add_key('user_id');
        $this->dbforge->add_key('group_id');
        $this->dbforge->add_key('coordinate_id');
        $this->dbforge->add_key('datestart');
        $this->dbforge->add_key('datestop');
        $this->dbforge->add_key('radius');

        $this->dbforge->create_table($tablename, TRUE);
		array_push($__tbl_names, $tablename);
		        
        
        
		// table name: relation
		$tablename = 'relation';
		$this->dbforge->add_field("`".CT_PK."` int(11) NOT NULL auto_increment");
        $this->dbforge->add_field("`who_user_id` INT(11) NULL");  // who created relation 
        $this->dbforge->add_field("`which_user_id` INT(11) NULL");  // for which user 
        $this->dbforge->add_field("`created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP");
		$this->dbforge->add_field("`status` enum('approved','notapproved','closed') NOT NULL default 'notapproved'");
		$this->dbforge->add_field("`relation_name_id` INT(11) NULL");
		
        $this->dbforge->add_key(CT_PK, TRUE);
        $this->dbforge->add_key('who_user_id');
        $this->dbforge->add_key('which_user_id');
        $this->dbforge->add_key('status');

        $this->dbforge->create_table($tablename, TRUE);
		array_push($__tbl_names, $tablename);
        
		// table name: relation_names
		$tablename = 'relation_names';
		$this->dbforge->add_field("`".CT_PK."` int(11) NOT NULL auto_increment");
        $this->dbforge->add_field("`relation_name` varchar(100) NOT NULL");   // 
        $this->dbforge->add_field("`who_user_id` INT(11) NULL");  // who created this name 
        $this->dbforge->add_field("`created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP");
		$this->dbforge->add_field("`status` enum('active','suspended','closed') NOT NULL default 'active'");
		
        $this->dbforge->add_key(CT_PK, TRUE);
        $this->dbforge->add_key('who_user_id');
        $this->dbforge->add_key('status');

        $this->dbforge->create_table($tablename, TRUE);
		array_push($__tbl_names, $tablename);
        
        		
		// table name: news
		$tablename = 'news';
		$this->dbforge->add_field("`".CT_PK."` int(11) NOT NULL auto_increment");
        $this->dbforge->add_field("`user_id` INT(11) NULL");
        $this->dbforge->add_field("`created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP");
        $this->dbforge->add_field("`datestart` DATETIME NOT NULL");
        $this->dbforge->add_field("`datestop`  DATETIME NULL");
		$this->dbforge->add_field("`status` enum('active','suspended','closed') NOT NULL default 'active'");
		$this->dbforge->add_field("`body` varchar(1000) NOT NULL");   // Тело новости
		$this->dbforge->add_field("`coordinate_id` INT(11) NULL");
		$this->dbforge->add_field("`radius` INT(7) UNSIGNED NOT NULL default 0");
		
        $this->dbforge->add_key(CT_PK, TRUE);
        $this->dbforge->add_key('user_id');
        $this->dbforge->add_key('coordinate_id');
        $this->dbforge->add_key('datestart');
        $this->dbforge->add_key('datestop');
        $this->dbforge->add_key('radius');

        $this->dbforge->create_table($tablename, TRUE);
		array_push($__tbl_names, $tablename);
				
		// table name: Karma Это как раз и есть наш upradius или радиус известности 
		$tablename = 'karma';
		$this->dbforge->add_field("`".CT_PK."` int(11) NOT NULL auto_increment");
        $this->dbforge->add_field("`user_id` INT(11) NULL");
        $this->dbforge->add_field("`updated` TIMESTAMP DEFAULT CURRENT_TIMESTAMP");
		$this->dbforge->add_field("`value` INT(7) UNSIGNED NOT NULL default 0");
		
        $this->dbforge->add_key(CT_PK, TRUE);
        $this->dbforge->add_key('user_id');

        $this->dbforge->create_table($tablename, TRUE);
		array_push($__tbl_names, $tablename);
		
		// table name: Place
		$tablename = 'place';
		$this->dbforge->add_field("`".CT_PK."` int(11) NOT NULL auto_increment");
        $this->dbforge->add_field("`created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP");
        $this->dbforge->add_field("`coord_id` INT(11) NULL");
        $this->dbforge->add_field("`user_id` INT(11) NOT NULL default 1");
        $this->dbforge->add_field("`placetype_code` VARCHAR(12) NOT NULL default 'll'");
//		$this->dbforge->add_field("`alternatenames` varchar(1000) NULL");
		// add names from geonames.org
        $this->dbforge->add_field("`geonameid` INT(11) NOT NULL");
		$this->dbforge->add_field("`name` varchar(200) NOT NULL");
		$this->dbforge->add_field("`asciiname` varchar(200) NOT NULL");
		$this->dbforge->add_field("`alternatenames` varchar(5000) NULL");
//		$this->dbforge->add_field("`feature_class` char(1) NULL");
//		$this->dbforge->add_field("`feature_code` varchar(10) NULL");
		$this->dbforge->add_field("`country_code` char(2) NULL");
		$this->dbforge->add_field("`cc2` char(60) NOT NULL");
		$this->dbforge->add_field("`admin1_code` varchar(20) NULL");
		$this->dbforge->add_field("`admin2_code` varchar(80) NULL");
		$this->dbforge->add_field("`admin3_code` varchar(20) NULL");
		$this->dbforge->add_field("`admin4_code` varchar(20) NULL");
        $this->dbforge->add_field("`population` INT(8) NULL default 0");
        $this->dbforge->add_field("`elevation` INT(4) NULL default 0");
        $this->dbforge->add_field("`dem` INT(8) NULL");
		$this->dbforge->add_field("`timezone` varchar(40) NULL");
		$this->dbforge->add_field("`sysdescription` varchar(1000) NULL");   // Системное описание места (добавляет админ)
		
        $this->dbforge->add_key(CT_PK, TRUE);
        $this->dbforge->add_key('user_id');

        $this->dbforge->create_table($tablename, TRUE);
		array_push($__tbl_names, $tablename);
		
		// table name: PlaceType
		$tablename = 'placetype';
		$this->dbforge->add_field("`".CT_PK."` int(11) NOT NULL auto_increment");
		$this->dbforge->add_field("`code` varchar(10) NOT NULL");  
		$this->dbforge->add_field("`s_desc` varchar(200) NOT NULL");  
		$this->dbforge->add_field("`e_desc` varchar(500) NULL default ''");  
		$this->dbforge->add_field("`name` varchar(500) NOT NULL");  // Адрес опознвнный гуглом
		$this->dbforge->add_field("`icon` char(20) NOT NULL");  // Адрес опознвнный гуглом
		
        $this->dbforge->add_key(CT_PK, TRUE);

        $this->dbforge->create_table($tablename, TRUE);
		array_push($__tbl_names, $tablename);
		
		// table name: DecriptionPlace  Дополнительные описание расположения.  (что-то вроде отзывов или комментов)
		$tablename = 'placedescription';
		$this->dbforge->add_field("`".CT_PK."` int(11) NOT NULL auto_increment");
        $this->dbforge->add_field("`created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP");
        $this->dbforge->add_field("`user_id` INT(11) NULL");
		$this->dbforge->add_field("`place_id` INT(11) NOT NULL");
		$this->dbforge->add_field("`status` enum('active','suspended','closed') NOT NULL default 'active'");
		$this->dbforge->add_field("`description` varchar(1024) NULL");
		
        $this->dbforge->add_key(CT_PK, TRUE);
        $this->dbforge->add_key('user_id');

        $this->dbforge->create_table($tablename, TRUE);
		array_push($__tbl_names, $tablename);
		
		
		// table name: FollowUP
		$tablename = 'followup';
		$this->dbforge->add_field("`".CT_PK."` int(11) NOT NULL auto_increment");
        $this->dbforge->add_field("`created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP");
        $this->dbforge->add_field("`user_id` INT(11) NOT NULL");   // кто 
        $this->dbforge->add_field("`fup_user_id` INT(11) NULL");   // за кем 
        $this->dbforge->add_field("`fup_place_id` INT(11) NULL");  // за каким местом 
        $this->dbforge->add_field("`fup_group_id` INT(11) NULL");  // за какой группой
		$this->dbforge->add_field("`status` enum('active','suspended','closed') NOT NULL default 'active'");  // статус отношения. 
		
        $this->dbforge->add_key(CT_PK, TRUE);
        $this->dbforge->add_key('fup_user_id');
        $this->dbforge->add_key('fup_place_id');

        $this->dbforge->create_table($tablename, TRUE);
		array_push($__tbl_names, $tablename);
		
		// table name: UPIT
		// аналог функционала "мне нравится" но только в нашей системе.
		$tablename = 'upit';
		$this->dbforge->add_field("`".CT_PK."` int(11) NOT NULL auto_increment");
        $this->dbforge->add_field("`created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP");
        $this->dbforge->add_field("`user_id` INT(11) NOT NULL");
        $this->dbforge->add_field("`upit_user_id` INT(11) NULL");
        $this->dbforge->add_field("`upit_place_id` INT(11) NULL");
        $this->dbforge->add_field("`upit_gallery_id` INT(11) NULL");
        $this->dbforge->add_field("`upit_photo_id` INT(11) NULL");
		
        $this->dbforge->add_key(CT_PK, TRUE);
        $this->dbforge->add_key('upit_user_id');
        $this->dbforge->add_key('upit_place_id');
        $this->dbforge->add_key('upit_gallery_id');
        $this->dbforge->add_key('upit_photo_id');

        $this->dbforge->create_table($tablename, TRUE);
		array_push($__tbl_names, $tablename);
		
		// table name: Face ID technical information.
		$tablename = 'face_information';
		$this->dbforge->add_field("`".CT_PK."` int(11) NOT NULL auto_increment");
        $this->dbforge->add_field("`user_id` INT(11) NOT NULL");
		
        $this->dbforge->add_key(CT_PK, TRUE);

        $this->dbforge->create_table($tablename, TRUE);
		array_push($__tbl_names, $tablename);
		
		
		// table name: countseeit
		$tablename = 'countseeit';
		$this->dbforge->add_field("`".CT_PK."` int(11) NOT NULL auto_increment");
        $this->dbforge->add_field("`created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP");
        $this->dbforge->add_field("`photo_id` INT(11) NULL");
        $this->dbforge->add_field("`gallery_id` INT(11) NULL");
		
        $this->dbforge->add_key(CT_PK, TRUE);
        $this->dbforge->add_key('photo_id');
        $this->dbforge->add_key('gallery_id');
        $this->dbforge->add_key('created');

        $this->dbforge->create_table($tablename, TRUE);
		array_push($__tbl_names, $tablename);
		
		// table name: Photo
		$tablename = 'photo';
		$this->dbforge->add_field("`".CT_PK."` int(11) NOT NULL auto_increment");
        $this->dbforge->add_field("`created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP");
        $this->dbforge->add_field("`user_id` INT(11) NULL");
        $this->dbforge->add_field("`coordinate_id` INT(11) NULL");
        $this->dbforge->add_field("`gallery_id` INT(11) NULL");
        $this->dbforge->add_field("`place_id` INT(11) NULL");
		$this->dbforge->add_field("`name` VARCHAR(100) NULL");
		$this->dbforge->add_field("`extension` char(5) NOT NULL default 'jpg'");
		$this->dbforge->add_field("`status` enum('active','suspended','closed') NOT NULL default 'suspended'");
		$this->dbforge->add_field("`user_perm` enum('i', 'friend', 'all') NOT NULL default 'all'");
		$this->dbforge->add_field("`description` varchar(250) NULL");
        $this->dbforge->add_field("`internal_name` char(32) NOT NULL");
		
        $this->dbforge->add_key(CT_PK, TRUE);
        $this->dbforge->add_key('user_id');
        $this->dbforge->add_key('gallery_id');
        $this->dbforge->add_key('internal_name');

        $this->dbforge->create_table($tablename, TRUE);
		array_push($__tbl_names, $tablename);
				
		// table name: GPS coordinates
		$tablename = 'coordinates';
		$this->dbforge->add_field("`".CT_PK."` int(11) NOT NULL auto_increment");
        $this->dbforge->add_field("`created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP");
		$this->dbforge->add_field("`trusted` enum('yes','no') NOT NULL default 'no'");
        $this->dbforge->add_field("`user_id` INT(11) NULL");
        $this->dbforge->add_field("`latitude` varchar(20) NOT NULL");
        $this->dbforge->add_field("`longitude` varchar(20) NOT NULL");
        $this->dbforge->add_field("`altitude` INT(10) NOT NULL default 0");
        $this->dbforge->add_field("`maxapproximation` INT(3) NOT NULL");
		/*
		В.
			Например, Москва, Минская, 13 имеет координаты (55,74087 - 37,48125)
			Но на карте эти координаты представлены широтой и долготой, как: 55°44' 27.13'' - 37°28' 52.41''
			Какую формулу нужно использовать, чтобы перевести координаты WGS-84 (55,74087 - 37,48125) в стандартную широту и долготу (55°44' 27.13'' - 37°28' 52.41'') ?
			
		О.
			55,74087 - это будет 55 градусов. Далее нужно 0,74087*60=44,4522 минут - целая часть это минуты. Затем для получения секунд нужно 0,4522*60 секунд=27,132 секунд.
			В результате получили 55 градусов 44 минуты 27,132 секунды.
		*/
		
        $this->dbforge->add_key(CT_PK, TRUE);
        $this->dbforge->add_key('user_id');
		$this->dbforge->add_key('latitude');
		$this->dbforge->add_key('longitude');
		

        $this->dbforge->create_table($tablename, TRUE);
		array_push($__tbl_names, $tablename);		
        
		// table name: Geocodes for cashe goole
		/*
		$tablename = 'geocodes';
		$this->dbforge->add_field("`".CT_PK."` int(11) NOT NULL auto_increment");
        $this->dbforge->add_field("`created` datetime NOT NULL");
        $this->dbforge->add_field("`user_id` INT(11) NULL");
        $this->dbforge->add_field("`latitude` varchar(10) NOT NULL");
        $this->dbforge->add_field("`longitude` varchar(10) NOT NULL");
		
        $this->dbforge->add_key(CT_PK, TRUE);
        $this->dbforge->add_key('user_id');

        $this->dbforge->create_table($tablename, TRUE);
		array_push($__tbl_names, $tablename);		
		*/
        
		// table name: Gallery
		$tablename = 'gallery';
		$this->dbforge->add_field("`".CT_PK."` int(11) NOT NULL auto_increment");
        $this->dbforge->add_field("`created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP");
        $this->dbforge->add_field("`user_id` INT(11) NOT NULL");
        $this->dbforge->add_field("`coordinate_id` INT(11) NULL");
		$this->dbforge->add_field("`name` VARCHAR(100) NULL");
		$this->dbforge->add_field("`status` enum('active','suspended','closed') NOT NULL default 'suspended'");
		$this->dbforge->add_field("`user_perm` enum('i', 'friend', 'all') NOT NULL default 'all'");
		$this->dbforge->add_field("`description` varchar(1024) NULL");
        $this->dbforge->add_field("`internal_name` char(32) NOT NULL");
		
        $this->dbforge->add_key(CT_PK, TRUE);
        $this->dbforge->add_key('user_id');
        $this->dbforge->add_key('created');
        $this->dbforge->add_key('internal_name');

        $this->dbforge->create_table($tablename, TRUE);
		array_push($__tbl_names, $tablename);
        
		// table name: Comments
		$tablename = 'comments';
		$this->dbforge->add_field("`".CT_PK."` int(11) NOT NULL auto_increment");
        $this->dbforge->add_field("`created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP");
		$this->dbforge->add_field("`body` TINYTEXT NOT NULL");
        $this->dbforge->add_field("`user_id` INT(11) NOT NULL");
        $this->dbforge->add_field("`photo_id` INT(11) NULL");
        $this->dbforge->add_field("`gallery_id` INT(11) NULL");
        $this->dbforge->add_field("`parent_id` INT(11) NULL");
		$this->dbforge->add_field("`status` enum('active','suspended') NOT NULL default 'active'");
		
        $this->dbforge->add_key(CT_PK, TRUE);
        $this->dbforge->add_key('created');
        $this->dbforge->add_key('user_id');
        $this->dbforge->add_key('photo_id');
        $this->dbforge->add_key('parent_id');
        $this->dbforge->add_key('status');

        $this->dbforge->create_table($tablename, TRUE);
		array_push($__tbl_names, $tablename);
        
		// table name: Session
		$tablename = 'session';
		$this->dbforge->add_field("`".CT_PK."` CHAR(32) NOT NULL");
        $this->dbforge->add_field("`user_id` INT(11) NOT NULL");
        $this->dbforge->add_field("`stored` datetime NOT NULL");
        $this->dbforge->add_field("`updated` datetime NOT NULL");
		$this->dbforge->add_field("`host` varchar(15) NOT NULL");
		
        $this->dbforge->add_key(CT_PK, TRUE);
        $this->dbforge->add_key('user_id');
        $this->dbforge->add_key('stored');
        $this->dbforge->add_key('updated');
        $this->dbforge->add_key('host');
        $this->dbforge->create_table($tablename, TRUE);
		array_push($__tbl_names, $tablename);

		// table name: ext_links
		$tablename = 'ext_links';
		$this->dbforge->add_field("`".CT_PK."` int(11) NOT NULL auto_increment");
        $this->dbforge->add_field("`internal_name` char(32) NOT NULL");
        $this->dbforge->add_field("`external_name` char(32) NOT NULL");
        $this->dbforge->add_field("`created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP");
		
        $this->dbforge->add_key(CT_PK, TRUE);
        $this->dbforge->add_key('external_name');
        $this->dbforge->add_key('created');
		
        $this->dbforge->create_table($tablename, TRUE);
		array_push($__tbl_names, $tablename);
        
        
        /*
		// table name: relations
		$tablename = 'relations';
		$this->dbforge->add_field("`".CT_PK."` int(11) NOT NULL auto_increment");
        $this->dbforge->add_field("`internal_name` char(32) NOT NULL");
        $this->dbforge->add_field("`external_name` char(32) NOT NULL");
        $this->dbforge->add_field("`created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP");
		
        $this->dbforge->add_key(CT_PK, TRUE);
        $this->dbforge->add_key('external_name');
        $this->dbforge->add_key('created');
		
        $this->dbforge->create_table($tablename, TRUE);
		array_push($__tbl_names, $tablename);
        
		// table name: relationstype
		$tablename = 'relationstype';
		$this->dbforge->add_field("`".CT_PK."` int(11) NOT NULL auto_increment");
        $this->dbforge->add_field("`internal_name` char(32) NOT NULL");
        $this->dbforge->add_field("`external_name` char(32) NOT NULL");
        $this->dbforge->add_field("`created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP");
		
        $this->dbforge->add_key(CT_PK, TRUE);
        $this->dbforge->add_key('external_name');
        $this->dbforge->add_key('created');
		
        $this->dbforge->create_table($tablename, TRUE);
		array_push($__tbl_names, $tablename);
        ////////////////////////////////////////////////////////////////////////////
    */
        $data = array('msg' => 'Tables ['.implode(', <br/>', $__tbl_names).'] was CREATED successful.');
        $this->load->view('setup/index', $data);
	}
}

/* End of file start.php */
/* Location: application/controllers/start.php */