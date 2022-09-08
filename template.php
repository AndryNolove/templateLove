<?
function template($array,$template,$regexp) {
	if ( $template != strip_tags($template) ? false:true ) { // http://subinsb.com/php-check-if-string-is-html/
		$template = file_get_contents($template); // если ссылка
	} 
	if( !$regexp ){ $regexp = '/\[.*?]/s'; }
	$replace = preg_replace_callback( $regexp,  // шаблон поиска
		function($match) use ($array,$template) { //var_dump($match); // замена найденых переменных	
			$name = substr($match[0],1,-1); //var_dump($name); // получени имени переменной 
			if( !$name ) { return $match[0]; } // исключения // $match[0] == '[]' 
			if( substr($name,0,1) == "%" ) { // проверка на список и обработка списков
				$exp = explode("%",$name); //var_dump($exp); // отделить строку массива
				$temp = $exp[2]; //var_dump($temp); // шаблон массива
				foreach( explode("|",$exp[1]) as $value ) { $array = $array[$value]; } // выбор подмассива если установлен client|name|id
				if( is_array($array) ) {
					foreach( $array as $ask ) {
						if( is_array($ask) ) {
							$return .= template($ask,$temp,'/\{.*?}/s');
						} else {
							$return = "Error: rps item!";
						}
					}
				} elseif( is_string($array) || isset($array) ) { // заменить строкой
					$return = $array;
				} else {
					$return = "Error: rps!";
				}
				return $return;
			} else {
				$item = $array;
				foreach( explode("|",$name) as $value ) { $array = $array[$value]; } // выбор подмассива если установлен client|name|id
				if( isset($array) ) { // если есть такой подмассив
					return $array; 
				} elseif( strpos($name, "|") && strpos( $name, ")" ) ) { // если это список
					$search  = array('(',')','|'); // замена символов
					$replace = array('{','}','%'); // замена символов
					$template = "[%".str_replace($search, $replace, $name)."]"; // {item|<li>(name)</li>} => [%item%<li>{name}</li>]
					return template($item,$template,$regexp);
				} else {
					if( stripos($name, "\"") !== false || stripos($name, '\'') !== false ) { //обрабатываем только []
						return "[$name]"; // возвращаем обратно если содержит ковычки [''][""]
					} else {
						return "Error: $name"; // возвращаем ошибку если не найден шаблон замены
					}
				}
			}
		},
		$template
	);
	return $replace;
}
?>