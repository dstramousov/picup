<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function now()
{
	$now		= new DateTime("now");
	return $now->format('Y-m-d H:i:s');
} // end of function 

function changeNowByHour($hours)
{
	$now = new DateTime("now");
	$now->sub(new DateInterval('PT'.$hours.'H'));
	return $now->format('Y-m-d H:i:s');
} // end of function 


function systemFormatDate()
{
} // end of function 

function systemFormatTime()
{
} // end of function 

function systemFormatDateTime($__date, $show_time = FALSE)
{
	$ret = '';
	
	if(!$__date){
		$__date = date("Y-m-d H:i:s");
	}
	
	$__arr = preg_split('/ /', $__date, -1, PREG_SPLIT_NO_EMPTY);
	if(is_array($__arr)){
		$__arr2 = preg_split('/-/', $__arr[0], -1, PREG_SPLIT_NO_EMPTY);
		if(is_array($__arr2)){
			$y = $__arr2[0];
			$m = $__arr2[1];
			$d = _skipZerro($__arr2[2]);
		}
		
		$__arr3 = preg_split('/:/', $__arr[1], -1, PREG_SPLIT_NO_EMPTY);
		if(is_array($__arr2)){
			$h  = $__arr3[0];
			$mi = $__arr3[1];
			$s  = $__arr3[2];
		}
		
		$_rus_literation_month = getRusMonth($m);
		$ret = $d.' '.$_rus_literation_month.' '.$y.'.' ;
		
		if($show_time){
			$ret .= ' '.$h.':'.$mi;
		}		
	} //end if
	
	return $ret;
} // end of function.

function _skipZerro($m)
{
	if ($m=="01") {$m=1;return $m;}
	if ($m=="02") {$m=2;return $m;}
	if ($m=="03") {$m=3;return $m;}
	if ($m=="04") {$m=4;return $m;}
	if ($m=="05") {$m=5;return $m;}
	if ($m=="06") {$m=6;return $m;}
	if ($m=="07") {$m=7;return $m;}
	if ($m=="08") {$m=8;return $m;}
	if ($m=="09") {$m=9;return $m;}
	
	return $m;
}

function getUFNowDate($__date = NULL, $__show_time=false)
{
	$__ret = '';;
	
	$q[]="";
	$q[]="января";
	$q[]="февраля";
	$q[]="марта";
	$q[]="апреля";
	$q[]="мая";
	$q[]="июня";
	$q[]="июля";
	$q[]="августа";
	$q[]="сентября";
	$q[]="октября";
	$q[]="ноября";
	$q[]="декабря";

	//-- определяем массив для дней недели --
	$e[0]="воскресенье";
	$e[1]="понедельник";
	$e[2]="вторник";
	$e[3]="среда";
	$e[4]="четверг";
	$e[5]="пятница";
	$e[6]="суббота";

	// ---- считываем месяц
	$m=date('m');
	if ($m=="01") $m=1;
	if ($m=="02") $m=2;
	if ($m=="03") $m=3;
	if ($m=="04") $m=4;
	if ($m=="05") $m=5;
	if ($m=="06") $m=6;
	if ($m=="07") $m=7;
	if ($m=="08") $m=8;
	if ($m=="09") $m=9;

	// ---- считываем день недели
	if(!$__date){
		$y = date('Y');
		$we=date('w');
		// ---- считываем число
		$chislo=date('d');
		// - извлекаем из день недели
		$den_nedeli = $e[$we];
		// - извлекаем значениечение месяца
		$mesyac = $q[$m];
	} else {
	/*
		$__arr = preg_split('/ /', $__date, -1, PREG_SPLIT_NO_EMPTY);
		if(is_array($__arr)){
			$__arr2 = preg_split('/-/', $__arr[0], -1, PREG_SPLIT_NO_EMPTY);
			$chislo = $__arr2[1];
			dump($__arr2);
		}
	*/
	}
	
	$__ret = _skipZerro($chislo)." "._skipZerro($mesyac).", ".$y.'.'; 
	if($__show_time){
		$__ret .= ' '._skipZerro(date("H")).':'._skipZerro(date("i"));
	}		
	
	return $__ret;
} // end of function


function getRusMonth($month)
{
	if($month > 12 || $month < 1) return FALSE;
	$aMonth = array('января', 'февраля', 'марта', 'апреля', 'мая', 'июня', 'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря');
	
	return $aMonth[$month - 1];
}

function time_since($since) {
    $chunks = array(
        array(60 * 60 * 24 * 365 , 'год'),
        array(60 * 60 * 24 * 30 , 'мес.'),
        array(60 * 60 * 24 * 7, 'нед.'),
        array(60 * 60 * 24 , 'дн.'),
        array(60 * 60 , 'час.'),
        array(60 , 'мин.'),
        array(1 , 'сек.')
    );

    for ($i = 0, $j = count($chunks); $i < $j; $i++) {
        $seconds = $chunks[$i][0];
        $name = $chunks[$i][1];
        if (($count = floor($since / $seconds)) != 0) {
            break;
        }
    }

    $print = ($count == 1) ? '1 '.$name : "$count {$name}";
    return $print;
}


/* End of file date_helper.php */
/* Location: helpers/awatar_helper */