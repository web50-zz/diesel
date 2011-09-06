<?php
/**
* Класс text
*
* @author undefined
* @version 1.0
* @package emker-4
*/
class text
{
	// numerals
	private static $LIBTEXT_GLOBALS = array(
		'numerals' => array
		(
			'cardinal' => array
			(
				'ones' => array
				(
					1 => array(1 => array(1 => 'один', 'одна', 'одно'), array(1 => 'одного', 'одной', 'одного'), array(1 => 'одному', 'одной', 'одному'), array(1 => array(1 => 'один', 'одну', 'одно'), array(1 => 'одного', 'одну', 'одно')), array(1 => 'одним', 'одной', 'одним'), array(1 => 'одном', 'одной', 'одном')), // 1 м.р./ж.р.
					2 => array(1 => array(1 => 'два', 'две', 'два'), 'двух', 'двум', array(1 => array(1 => 'два', 'две', 'два'), array(1 => 'двух', 'двух', 'двух')), 'двумя', 'двух'), // 2 мн.ч.
					3 => array(1 => 'три', 'трёх', 'трём', array(1 => 'три', 'трёх'), 'тремя', 'трёх'), // 3 мн.ч.
					4 => array(1 => 'четыре', 'четырёх', 'четырём', array(1 => 'четыре', 'четырёх'), 'четырьмя', 'четырёх'), // 3 мн.ч.
					5 => array(1 => 'пять', 'пяти', 'пяти', 'пять', 'пятью', 'пяти'),  // 5 мн.ч.
					6 => array(1 => 'шесть', 'шести', 'шести', 'шесть', 'шестью', 'шести'),  // 6 мн.ч.
					7 => array(1 => 'семь', 'семи', 'семи', 'семь', 'семью', 'семи'),  // 7 мн.ч.
					8 => array(1 => 'восемь', 'восьми', 'восьми', 'восемь', 'восемью', 'восьми'),  // 8 мн.ч.
					9 => array(1 => 'девять', 'девяти', 'девяти', 'девять', 'девятью', 'девяти')  // 9 мн.ч.
				),

				'exceptions' => array
				(
					11 => array(1 => 'одиннадцать', 'одиннадцати', 'одиннадцати', 'одиннадцать', 'одиннадцатью', 'одиннадцати'),  // 11 мн.ч.
					12 => array(1 => 'двенадцать', 'двенадцати', 'двенадцати', 'двенадцать', 'двенадцатью', 'двенадцати'),  // 12 мн.ч.
					13 => array(1 => 'тринадцать', 'тринадцати', 'тринадцати', 'тринадцать', 'тринадцатью', 'тринадцати'),  // 13 мн.ч.
					14 => array(1 => 'четырнадцать', 'четырнадцати', 'четырнадцати', 'четырнадцать', 'четырнадцатью', 'четырнадцати'),  // 14 мн.ч.
					15 => array(1 => 'пятнадцать', 'пятнадцати', 'пятнадцати', 'пятнадцать', 'пятнадцатью', 'пятнадцати'),  // 15 мн.ч.
					16 => array(1 => 'шестнадцать', 'шестнадцати', 'шестнадцати', 'шестнадцать', 'шестнадцатью', 'шестнадцати'),  // 16 мн.ч.
					17 => array(1 => 'семнадцать', 'семнадцати', 'семнадцати', 'семнадцать', 'семнадцатью', 'семнадцати'),  // 17 мн.ч.
					18 => array(1 => 'восемнадцать', 'восемнадцати', 'восемнадцати', 'восемнадцать', 'восемнадцатью', 'восемнадцати'),  // 18 мн.ч.
					19 => array(1 => 'девятнадцать', 'девятнадцати', 'девятнадцати', 'девятнадцать', 'девятнадцатью', 'девятнадцати')  // 19 мн.ч.
				),

				'tens' => array
				(
					10 => array(1 => 'десять', 'десяти', 'десяти', 'десять', 'десятью', 'десяти'),  // 10 мн.ч.
					20 => array(1 => 'двадцать', 'двадцати', 'двадцати', 'двадцать', 'двадцатью', 'двадцати'),  // 20 мн.ч.
					30 => array(1 => 'тридцать', 'тридцати', 'тридцати', 'тридцать', 'тридцатью', 'тридцати'),  // 30 мн.ч.
					40 => array(1 => 'сорок', 'сорока', 'сорока', 'сорок', 'сорока', 'сорока'),  // 40 мн.ч.
					50 => array(1 => 'пятьдесят', 'пятидесяти', 'пятидесяти', 'пятьдесят', 'пятьюдесятью', 'пятидесяти'),  // 50 мн.ч.
					60 => array(1 => 'шестьдесят', 'шестидесяти', 'шестидесяти', 'шестьдесят', 'шестьюдесятью', 'шестидесяти'),  // 60 мн.ч.
					70 => array(1 => 'семьдесят', 'семидесяти', 'семидесяти', 'семьдесят', 'семьюдесятью', 'семидесяти'),  // 70 мн.ч.
					80 => array(1 => 'восемьдесят', 'восьмидесяти', 'восьмидесяти', 'восемьдесят', 'восемьюдесятью', 'восьмидесяти'),  // 80 мн.ч.
					90 => array(1 => 'девяносто', 'девяноста', 'девяноста', 'девяносто', 'девяноста', 'девяноста')  // 90 мн.ч.
				),

				'hundreds' => array
				(
					100 => array(1 => 'сто', 'ста', 'ста', 'сто', 'ста', 'ста'),  // 100 мн.ч.
					200 => array(1 => 'двести', 'двухсот', 'двумстам', 'двести', 'двумястами', 'двухстах'),  // 200 мн.ч.
					300 => array(1 => 'триста', 'трёхсот', 'трёмстам', 'триста', 'тремястами', 'трёхстах'),  // 300 мн.ч.
					400 => array(1 => 'четыреста', 'четырёхсот', 'четырёмстам', 'четыреста', 'четырьмястами', 'четырёхстах'),  // 400 мн.ч.
					500 => array(1 => 'пятьсот', 'пятисот', 'пятистам', 'пятьсот', 'пятьюстами', 'пятистах'),  // 500 мн.ч.
					600 => array(1 => 'шестьсот', 'шестисот', 'шестистам', 'шестьсот', 'шестьюстами', 'шестистах'),  // 600 мн.ч.
					700 => array(1 => 'семьсот', 'семисот', 'семистам', 'семьсот', 'семьюстами', 'семистах'),  // 700 мн.ч.
					800 => array(1 => 'восемьсот', 'восьмисот', 'восьмистам', 'восемьсот', 'восемьюстами', 'восьмистах'),  // 800 мн.ч.
					900 => array(1 => 'девятьсот', 'девятисот', 'девятистам', 'девятьсот', 'девятьюстами', 'девятистах')  // 900 мн.ч.
				)
			),

			'ordinal' => array
			(

				'default_endings' => array
				(
					1 => array(1 => 'ый', 'ая', 'ое'),	array(1 => 'ого', 'ой', 'ого'), array(1 => 'ому', 'ой', 'ому'), array(1 => array(1 => 'ый', 'ую', 'ое'), array(1 => 'ого', 'ую', 'ое')), array(1 => 'ым', 'ой', 'ым'), array(1 => 'ом', 'ой', 'ом')
				),

				// NULL directs to default ending
				'words' => array
				(
					1 => array('перв', NULL, NULL, NULL, NULL, NULL, NULL), // 1
					2 => array('втор', array(1 => 'ой', 'ая', 'ое'), NULL, NULL, array(1 => array(1 => 'ой', 'ую', 'ое'), array(1 => 'ого', 'ую', 'ое')), NULL, NULL), // 2
					3 => array('трет', array(1 => 'ий', 'ья', 'ье'), array(1 => 'ьего', 'ью', 'ьего'), array(1 => 'ьему', 'ьей', 'ьему'), array(1 => array(1 => 'ий', 'ью', 'ье'), array(1 => 'ьего', 'ью', 'ье')),	array(1 => 'ьим', 'ьей', 'ьим'),	array(1 => 'ьем', 'ьей', 'ьем')), // 3
					4 => array('четвёрт', NULL, NULL, NULL, NULL, NULL, NULL), // 3
					5 => array('пят', NULL, NULL, NULL, NULL, NULL, NULL),  // 5
					6 => array('шест', array(1 => 'ой', 'ая', 'ое'), NULL, NULL, array(1 => array(1 => 'ой', 'ую', 'ое'), array(1 => 'ого', 'ую', 'ое')), NULL, NULL),  // 6
					7 => array('седьм', array(1 => 'ой', 'ая', 'ое'), NULL, NULL, array(1 => array(1 => 'ой', 'ую', 'ое'), array(1 => 'ого', 'ую', 'ое')), NULL, NULL),  // 7
					8 => array('восьм', array(1 => 'ой', 'ая', 'ое'), NULL, NULL, array(1 => array(1 => 'ой', 'ую', 'ое'), array(1 => 'ого', 'ую', 'ое')), NULL, NULL),  // 8
					9 => array('девят', NULL, NULL, NULL, NULL, NULL, NULL),  // 9

					11 => array('одиннадцат', NULL, NULL, NULL, NULL, NULL, NULL),  // 11
					12 => array('двенадцат', NULL, NULL, NULL, NULL, NULL, NULL),  // 12
					13 => array('тринадцат', NULL, NULL, NULL, NULL, NULL, NULL),  // 13
					14 => array('четырнадцат', NULL, NULL, NULL, NULL, NULL, NULL),  // 14
					15 => array('пятнадцат', NULL, NULL, NULL, NULL, NULL, NULL),  // 15
					16 => array('шестнадцат', NULL, NULL, NULL, NULL, NULL, NULL),  // 16
					17 => array('семнадцат', NULL, NULL, NULL, NULL, NULL, NULL),  // 17
					18 => array('восемнадцат', NULL, NULL, NULL, NULL, NULL, NULL),  // 18
					19 => array('девятнадцат', NULL, NULL, NULL, NULL, NULL, NULL),  // 19

					10 => array('десят', NULL, NULL, NULL, NULL, NULL, NULL),  // 10
					20 => array('двадцат', NULL, NULL, NULL, NULL, NULL, NULL),  // 20
					30 => array('тридцат', NULL, NULL, NULL, NULL, NULL, NULL),  // 30
					40 => array('сороков', array(1 => 'ой', 'ая', 'ое'), NULL, NULL, array(1 => array(1 => 'ой', 'ую', 'ое'), array(1 => 'ого', 'ую', 'ое')), NULL, NULL),  // 40
					50 => array('пятидесят', NULL, NULL, NULL, NULL, NULL, NULL),  // 50
					60 => array('шестидесят', NULL, NULL, NULL, NULL, NULL, NULL),  // 60
					70 => array('семидесят', NULL, NULL, NULL, NULL, NULL, NULL),  // 70
					80 => array('восьмидесят', NULL, NULL, NULL, NULL, NULL, NULL),  // 80
					90 => array('девяност', NULL, NULL, NULL, NULL, NULL, NULL),  // 90

					100 => array('сот', NULL, NULL, NULL, NULL, NULL, NULL),  // 100
					200 => array('двухсот', NULL, NULL, NULL, NULL, NULL, NULL),  // 200
					300 => array('трёхсот', NULL, NULL, NULL, NULL, NULL, NULL),  // 300
					400 => array('четырёхсот', NULL, NULL, NULL, NULL, NULL, NULL),  // 400
					500 => array('пятисот', NULL, NULL, NULL, NULL, NULL, NULL),  // 500
					600 => array('шестисот', NULL, NULL, NULL, NULL, NULL, NULL),  // 600
					700 => array('семисот', NULL, NULL, NULL, NULL, NULL, NULL),  // 700
					800 => array('восьмисот', NULL, NULL, NULL, NULL, NULL, NULL),  // 800
					900 => array('девятисот', NULL, NULL, NULL, NULL, NULL, NULL),  // 900

					1000 => array('тысячн', NULL, NULL, NULL, NULL, NULL, NULL),  // 1000
					1000000 => array('миллионн', NULL, NULL, NULL, NULL, NULL, NULL),  // 1000000
					1000000000 => array('миллиардн', NULL, NULL, NULL, NULL, NULL, NULL)  // 1000000000
				)
			)
		),

		// nouns
		'nouns' => array
		(
			'thousand' => array
			(
				1 => array(1 => 'тысяча', 'тысячи', 'тысяче', 'тысячу', 'тысячей', 'тысяче'), // ед.ч. (1)
				array(1 => 'тысячи', 'тысяч', 'тысячам', 'тысячи', 'тысячами', 'тысячах') // мн.ч. (2-4) + мн.ч. (11-19, х5-х0)
			),

			// он
			'million' => array
			(
				1 => array(1 => 'миллион', 'миллиона', 'миллиону', 'миллион', 'миллионом', 'миллионе'), // ед.ч. (1)
				array(1 => 'миллиона', 'миллионов', 'миллионам', 'миллиона', 'миллионами', 'миллионах') // мн.ч. (2-4) + мн.ч. (11-19, х5-х0)
			),

			// он
			'billion' => array
			(
				1 => array(1 => 'миллиард', 'миллиарда', 'миллиарду', 'миллиард', 'миллиардом', 'миллиарде'), // ед.ч. (1)
				array(1 => 'миллиарда', 'миллиардов', 'миллиардам', 'миллиарда', 'миллиардами', 'миллиардах') // мн.ч. (2-4) + мн.ч. (11-19, х5-х0)
			)
		),

		'currency' => array
		(
			1 => array(
				1 => array(1 => 'рубль', 'рубля', 'рублю', 'рубль', 'рублём', 'рубле'), // ед.ч. (1)
				array(1 => 'рубля', 'рублей', 'рублям', 'рубля', 'рублями', 'рублях') // мн.ч. (2-4) + мн.ч. (11-19, х5-х0)
			),
			array(
				1 => array(1 => 'доллар', 'доллара', 'доллару', 'доллар', 'долларом', 'долларе'), // ед.ч. (1)
				array(1 => 'доллара', 'долларов', 'долларам', 'доллара', 'долларами', 'долларах') // мн.ч. (2-4) + мн.ч. (11-19, х5-х0)
			),
		),

		'currency2' => array(
			1 => array(
				1 => array(1 => 'копейка', 'копейки', 'копейке', 'копейку', 'копейкой', 'копейке'), // ед.ч. (1)
				array(1 => 'копейки', 'копеек', 'копейкам', 'копейки', 'копейками', 'копейках') // мн.ч. (2-4) + мн.ч. (11-19, х5-х0)
			),
			array(
				1 => array(1 => 'цент', 'цента', 'центу', 'цент', 'центом', 'центе'), // ед.ч. (1)
				array(1 => 'цента', 'центов', 'центам', 'цента', 'центами', 'центах') // мн.ч. (2-4) + мн.ч. (11-19, х5-х0)
			),
		),

		'ccy_postfix' => array(
			1 => '',
			' США',
		),

		// она
		'whole' => array
		(
			1 => array(1 => 'целая', 'целой', 'целой', 'целую', 'целой', 'целой'), // ед.ч. (1)
			array(1 => 'целых', 'целых', 'целыми', 'целые', 'целыми', 'целых') // мн.ч. (2-4) + мн.ч. (11-19, х5-х0)
		),

		// он
		'year' => array
		(
			1 => array(1 => 'год', 'года', 'году', 'год', 'годом', 'годе'), // ед.ч. (1)
			array(1 => 'года', 'лет', 'годам', 'года', 'годами', 'годах') // мн.ч. (2-4) + мн.ч. (11-19, х5-х0)
		),

		'months' => array(
			1 => array(1 => 'январь', 2 => 'февраль', 3 => 'март', 4 => 'апрель', 5 => 'май', 6 => 'июнь', 7 => 'июль', 8 => 'август', 9 => 'сентябрь', 10 => 'октябрь', 11 => 'ноябрь', 12 => 'декабрь'),
			2 => array(1 => 'января', 2 => 'февраля', 3 => 'марта', 4 => 'апреля', 5 => 'мая', 6 => 'июня', 7 => 'июля', 8 => 'августа', 9 => 'сентября', 10 => 'октября', 11 => 'ноября', 12 => 'декабря'),
			3 => array(1 => 'ЯНВ', 2 => 'ФЕВ', 3 => 'МАР', 4 => 'АПР', 5 => 'МАЙ', 6 => 'ИЮН', 7 => 'ИЮЛ', 8 => 'АВГ', 9 => 'СЕН', 10 => 'ОКТ', 11 => 'НОЯ', 12 => 'ДЕК'),
		),
	);

	public function __construct()
	{
	}

	public static function strip_html($text, $allowed = '')
	{
		//return strip_tags($text, $allowed);
		return preg_replace('/<[\/\!]*?[^<>]*?>/si', '', $text);
	}

	public static function cyr2ent($text)
	{
		$ret = '';

		for ($i = 0; $i < strlen($text); $i++)
		{
			$char = ord($text[$i]);
			$ret .= ($char > 192) ? '&#' . (1040 + ($char - 192)) . ';' : $text[$i];
		}

		return $ret;
	}

	public static function cyr2trans($st)
	{
		$st=strtr($st,"абвгдеёзийклмнопрстуфхъыэ", "abvgdeeziyklmnoprstufh'ie");
		$st=strtr($st,"АБВГДЕЁЗИЙКЛМНОПРСТУФХЪЫЭ", "ABVGDEEZIYKLMNOPRSTUFH'IE");
		// Затем  "многосимвольные".
		$st=strtr($st, array("ж"=>"zh", "ц"=>"ts", "ч"=>"ch", "ш"=>"sh", "щ"=>"shch","ь"=>"", "ю"=>"yu", "я"=>"ya", "Ж"=>"ZH", "Ц"=>"TS", "Ч"=>"CH", "Ш"=>"SH", "Щ"=>"SHCH","Ь"=>"", "Ю"=>"YU", "Я"=>"YA"));
		// Возвращаем результат.
		return $st;
	}

	/**
	*	Функция взята с http://ru.php.net/manual/ru/function.floatval.php
	* This function should be able to cover almost all floats that appear in an european environment.
	*
	* @author	pillepop2003@yahoo.de
	* @param	string	$str	Преобразуемое значение
	* @param	mixed	$set	You can choose how a single dot is treated with the (bool) 'single_dot_as_decimal' directive.
	*/
	public static function float($str, $set = FALSE)
	{
		if (preg_match("/([\d\s\.,-]+)/", $str, $match))
		{
			// Found number in $str, so set $str that number
			$str = $match[0];

			if (strstr($str, ','))
			{
				// A comma exists, that makes it easy, cos we assume it separates the decimal part.
				$str = str_replace(array(' ', '.'), '', $str);    // Erase thousand seps
				$str = str_replace(',', '.', $str);    // Convert , to . for floatval command

				return floatval($str);
			}
			else
			{
				// No comma exists, so we have to decide, how a single dot shall be treated
				if (preg_match("/^[0-9-]*[\.]{1}[0-9-]+$/", $str) == TRUE && $set['single_dot_as_decimal'] == TRUE)
				{
					// Treat single dot as decimal separator
					return floatval($str);
				}
				else
				{
					// Else, treat all dots as thousand seps
					$str = str_replace(array(' ', '.'), '', $str);    // Erase thousand seps
					return floatval($str);
				}
			}
		}
		else
		{
			// No number found, return zero
			return 0;
		}
	}
	
	/**
	* @param $case Падеж; используется при выводе даты прописью
	*
	*/
	public static function mysql_date_format($date = '', $format = 'd.m.Y H:i:s', $case = 1)
	{
		if ($date == '' || $date == '0000-00-00' || $date == '0000-00-00 00:00:00' || $date == '0') return;

		$parts_datetime = explode(' ', $date);
		$parts_date = explode('-', $parts_datetime[0]);

		$parts_time = ($parts_datetime[1]) ? explode(':', $parts_datetime[1]) : array();
		$parts = array_merge($parts_date, $parts_time);

		if (preg_match('/d/', $format))
			$format = preg_replace('/d/', $parts[2], $format);

		// День прописью
		if (preg_match('/D/', $format))
			$format = preg_replace('/D/', text::numtostr($parts[2], 2, $case, 3), $format); //доработать на удаление лидирующих нулей

		if (preg_match('/j/', $format))
			$format = preg_replace('/j/', $parts[2], $format); //доработать на удаление лидирующих нулей

		// Месяц прописью
		if (strstr($format, 'F'))
			$format = str_replace('F', self::$LIBTEXT_GLOBALS['months'][2][sprintf('%d', $parts[1])], $format);

		if (strstr($format, 'M'))
			$format = str_replace('M', self::$LIBTEXT_GLOBALS['months'][3][sprintf('%d', $parts[1])], $format);

		if (preg_match('/m/', $format))
			$format = preg_replace('/m/', $parts[1], $format);

		if (preg_match('/n/', $format))
			$format = preg_replace('/n/', $parts[2], $format);//доработать на удаление лидирующих нулей

		if (preg_match('/Y/', $format))
			$format = preg_replace('/Y/', $parts[0], $format);

		if (preg_match('/y/', $format))
			$format = preg_replace('/y/', substr($parts[0], 2, 2), $format);//доработать на удаление лидирующих нулей

		// Год прописью
		if (preg_match('/e/', $format))
			$format = preg_replace('/e/', text::numtostr($parts[0], 2, $case, 1), $format);

		if (preg_match('/H/', $format))
			$format = preg_replace('/H/', $parts[3], $format);

		if (preg_match('/i/', $format))
			$format = preg_replace('/i/', $parts[4], $format);

		if (preg_match('/s/', $format))
			$format = preg_replace('/s/', $parts[5], $format);

		return $format;
	}

	/**
	* Number to string conversion issues
	*/
	public static function fetch_ordinal_word($value, $case, $gender, $subject_type)
	{
		$numerals = self::$LIBTEXT_GLOBALS['numerals'];
		$words = $numerals['ordinal']['words'];
		assert(array_key_exists($value, $words)); // Value must be defined in the words array
		$word = $words[$value][0]; // Fetch base part of the word
		// Use either default or custom ending
		$ending = ($words[$value][$case] === NULL) ? $numerals['ordinal']['default_endings'][$case] : $words[$value][$case];

		if ($case == 4) // Apply gender and subject type in accusative case
		{
			$word .= $ending[$subject_type][$gender];
		}
		else // Apply gender in every case except accusative
		{
			$word .= $ending[$gender];
		}

		return $word;
	}

	public static function parse_ordinal_triplet($triplet, $order, $case, $gender, $subject_type, $noun, $with_declension, $custom_gender, $custom_subject_type, $custom_noun)
	{
		$numerals = self::$LIBTEXT_GLOBALS['numerals'];
		$str = array();
		assert(bccomp($triplet, '0') == 1); // Triplet must be nonzero
		$declined = false;
		$noun_variant = 0; // Varies from 1 to 3 (3rd uses data of 2nd with two exceptions)
		// Fetch number except hundreds (to check for exceptions between 11 and 19)
		$remainder = bcmod($triplet, '100');

		if (bccomp($remainder, '0') == 1)
		{
			if (bccomp('11', $remainder) != 1 && bccomp($remainder, '19') != 1)
			{
				// Whether to decline last triplet
				if ($with_declension)
				{
					array_unshift($str, text::fetch_ordinal_word(intval($remainder), $case, $gender, $subject_type));
					$declined = true;
				}
				else
				{
					array_unshift($str, $numerals['cardinal']['exceptions'][$remainder][$case]);
					// Define noun variant
					$noun_variant = 3; // Final variant
				}
			}
			else
			{
				$ones = bcmod($remainder, '10');

				if (bccomp($ones, '0') == 1)
				{
					if ($with_declension)
					{
						if ($order == 1)
						{
							// Final word

							array_unshift($str, text::fetch_ordinal_word(intval($ones), $case, $gender, $subject_type));
						}
						else
						{
							// Final trailing word expected further (like 'тысячный', 'миллионный' ...)

							if (bccomp($triplet, '1') != 0) // totally skip word for triplet equal to 1 (this will leave just trailing word for the whole triplet)
							{
								$number = $numerals['cardinal']['ones'][$ones][2]; // Use cardinal numerals in genitive case

								if (bccomp($ones, '1') == 0) // for 1
								{
									array_unshift($str, $number[$gender]); // apply gender
								}
								else array_unshift($str, $number); // use plain value
							}
						}

						$declined = true;
					}
					else
					{
						$number = $numerals['cardinal']['ones'][$ones][1]; // Use case 1 only

						// Exception: apply gender and subject type
						if (bccomp($ones, '1') == 0) // for 1
						{
							array_unshift($str, $number[$gender]);
						}
						elseif (bccomp($ones, '2') == 0) // for 2
						{
							array_unshift($str, $number[$gender]);
						}
						elseif (bccomp($ones, '3') == 0) // for 3
						{
							array_unshift($str, $number);
						}
						elseif (bccomp($ones, '4') == 0) // for 4
						{
							array_unshift($str, $number);
						}
						else array_unshift($str, $number); // for all others
					}


					// Define noun variant
					if (bccomp($ones, '1') == 0) $noun_variant = 1; // equal to 1
					elseif (bccomp('2', $ones) != 1 && bccomp($ones, '4') != 1) $noun_variant = 2; // between 2 and 4
					else $noun_variant = 3; // between 5 and 9
				}


				$tens = bcdiv($remainder, '10');

				if (bccomp($tens, '0') == 1)
				{
					if ($with_declension)
					{
						if ($order > 1)
						{
							// Final trailing word expected further (like 'тысячный', 'миллионный' ...)

							array_unshift($str, $numerals['cardinal']['tens'][bcmul($tens, '10')][2]); // Use cardinal numerals in genitive case
						}
						elseif (! $declined)
						{
							// Final word

							array_unshift($str, text::fetch_ordinal_word(intval(bcmul($tens, '10')), $case, $gender, $subject_type));
						}
						else
						{
							array_unshift($str, $numerals['cardinal']['tens'][bcmul($tens, '10')][1]);

							// Define noun variant
							if (empty($noun_variant)) $noun_variant = 3; // Assume 3rd, may be overridden further
						}

						$declined = true;
					}
					else
					{
						array_unshift($str, $numerals['cardinal']['tens'][bcmul($tens, '10')][1]);

						// Define noun variant
						if (empty($noun_variant)) $noun_variant = 3; // Assume 3rd, may be overridden further
					}
				}
			}
		}


		// Fetch hundreds

		$hundreds = bcdiv($triplet, '100');

		if (bccomp($hundreds, '0') == 1)
		{
			if ($with_declension)
			{
				if ($order > 1)
				{
					// Final trailing word expected further (like 'тысячный', 'миллионный' ...)

					array_unshift($str, $numerals['cardinal']['hundreds'][bcmul($hundreds, '100')][2]); // Use cardinal numerals in genitive case
				}
				elseif (! $declined)
				{
					// Final word

					array_unshift($str, text::fetch_ordinal_word(intval(bcmul($hundreds, '100')), $case, $gender, $subject_type));
				}
				else
				{
					array_unshift($str, $numerals['cardinal']['hundreds'][bcmul($hundreds, '100')][1]);

					// Define noun variant if not already set
					if (empty($noun_variant)) $noun_variant = 3; // Assume 3rd, may be overridden further
				}

			}
			else
			{
				array_unshift($str, $numerals['cardinal']['hundreds'][bcmul($hundreds, '100')][1]);

				// Define noun variant if not already set
				if (empty($noun_variant)) $noun_variant = 3; // Assume 3rd, may be overridden further
			}
		}


		// Append appropriate noun

		if ($noun != NULL) // if noun selected
		{
			if ($with_declension && $order > 1)
			{
				// Actually use special and custom nouns

				switch ($order)
				{
					case 2:
						$str[] = text::fetch_ordinal_word(1000, $case, $custom_gender, $custom_subject_type); // Add 'тысячный' in the case of custom noun
					break;

					case 3:
						$str[] = text::fetch_ordinal_word(1000000, $case, $custom_gender, $custom_subject_type); // Add 'миллионный' in the case of custom noun
					break;

					case 4:
						$str[] = text::fetch_ordinal_word(1000000000, $case, $custom_gender, $custom_subject_type); // Add 'миллиардный' in the case of custom noun
					break;
				}

				// Actually use custom noun

				$str[] = ' ';
				$str[] = $custom_noun[1][$case];
			}
			elseif ($with_declension && $order == 1)
			{
				// Actually use custom noun

				$str[] = $noun[1][$case];
			}
			else
			{
				switch ($noun_variant)
				{
					case 1:
					case 2:
						$str[] = $noun[$noun_variant][1];
					break;

					case 3:
						// if ($case == 1 || $case == 4) $case = 2;


						$str[] = $noun[2][2];
					break;

					default:
						assert(false); // This must not happen!
				}
			}
		}



		$delimiter = ' ';

		if ($with_declension && $order > 1) $delimiter = ''; // Disable delimiter when complex nouns are used


		return implode($delimiter, $str);
	}

	/**
	* @version 1.0 beta
	*
	* @param int $triplet
	* @param int $numeral_type Тип числительного: 1 - количественное, 2 - порядковое
	* @param int $case	Падеж: 1 - именительный, 2 - родительный, 3 - дательный, 4 - винительный, 5 - творительный, 6 - предложный
	* @param int $gender	Пол: 1 - мужской, 2 - женский
	* @param int $subject_type 1 - неодушевленный, 2 - одушевленный
	* @param array $noun таблица со спряжениями сопутствующего существительного
	*/
	// function parse_cardinal_triplet($triplet, $case, $gender, $subject_type, $noun)
	public static function parse_cardinal_triplet($triplet, $order, $case, $gender, $subject_type, $noun, $with_declension, $custom_noun)
	{
		$numerals = self::$LIBTEXT_GLOBALS['numerals'];

		$str = array();

		assert(bccomp($triplet, '0') == 1); // Triplet must be nonzero

		$noun_variant = 0; // Varies from 1 to 3 (3rd uses data of 2nd with two exceptions)

		// Fetch hundreds

		$hundreds = bcdiv($triplet, '100');

		if (bccomp($hundreds, '0') == 1)
		{
			$str[] = $numerals['cardinal']['hundreds'][bcmul($hundreds, '100')][$case];

			// Define noun variant
			$noun_variant = 3; // Assume 3rd, may be overridden further
		}

		// Fetch number except hundreds (to check for exceptions between 11 and 19)

		$remainder = bcmod($triplet, '100');

		if (bccomp($remainder, '0') == 1)
		{
			if (bccomp('11', $remainder) != 1 && bccomp($remainder, '19') != 1)
			{
				$str[] = $numerals['cardinal']['exceptions'][$remainder][$case];

				// Define noun variant
				$noun_variant = 3; // Final variant
			}
			else
			{
				$tens = bcdiv($remainder, '10');

				if (bccomp($tens, '0') == 1)
				{
					$str[] = $numerals['cardinal']['tens'][bcmul($tens, '10')][$case];

					// Define noun variant
					$noun_variant = 3; // Assume 3rd, may be overridden further
				}

				$ones = bcmod($remainder, '10');

				if (bccomp($ones, '0') == 1)
				{
					$number = $numerals['cardinal']['ones'][$ones][$case];

					// Exception: apply gender and subject type
					if (bccomp($ones, '1') == 0) // for 1
					{
						if ($case == 4) $str[] = $number[$subject_type][$gender]; // apply $subject_type and gender in accusative case
						else $str[] = $number[$gender]; // apply gender only in all other cases
					}
					elseif (bccomp($ones, '2') == 0) // for 2
					{
						if ($case == 1) $str[] = $number[$gender]; // apply gender only in nominative case
						elseif ($case == 4) $str[] = $number[$subject_type][$gender]; // apply $subject_type and gender in accusative case
						else $str[] = $number;
					}
					elseif (bccomp($ones, '3') == 0) // for 3
					{
						if ($case == 4) $str[] = $number[$subject_type]; // apply $subject_type in accusative case
						else $str[] = $number;
					}
					elseif (bccomp($ones, '4') == 0) // for 4
					{
						if ($case == 4) $str[] = $number[$subject_type]; // apply $subject_type in accusative case
						else $str[] = $number;
					}
					else $str[] = $number; // for all others

					// Define noun variant
					if (bccomp($ones, '1') == 0) $noun_variant = 1; // equal to 1
					elseif (bccomp('2', $ones) != 1 && bccomp($ones, '4') != 1) $noun_variant = 2; // between 2 and 4
					else $noun_variant = 3; // between 5 and 9
				}
			}
		}


		// Append appropriate noun
		if ($noun != NULL) // if noun selected (internal or custom)
		{
			switch ($noun_variant)
			{
				case 1:
				case 2:
					$str[] = $noun[$noun_variant][$case];
				break;

				case 3:
					if ($case == 1 || $case == 4) $case = 2;

					$str[] = $noun[2][$case];
				break;

				default:
					assert(false); // This must not happen!
			}

			// Add custom noun just after internal at the end of a number which is more than 999 and there are no numbers in the lowest triplet
			if ($with_declension && $order > 1 && $custom_noun !== NULL)
			{
				$str[] = $custom_noun[2][2];
			}
		}


		return implode(' ', $str);
	}

	/**
	* Денежная сумма прописью
	* @version 1.0 beta
	*
	* @param int $sum
	* @param mixed $currency	Валюта; Символьный код или индекс 1 - RUR, 2 - USD
	* @param string $delimiter Разделитель для конечного варианта
	* @param int $case	Падеж: 1 - именительный, 2 - родительный, 3 - дательный, 4 - винительный, 5 - творительный, 6 - предложный
	*/
	public static function moneytostr($sum, $currency = 'RUR', $delimiter = '.', $case = 1)
	{
		if ($currency == 'RUR') $currency = 1;
		else if ($currency == 'USD') $currency = 2;

		/*
		// !!! for further development

		$currency_codes = array(1 => 'RUR', 'USD');

		if (is_int($currency))$currency = $currency_codes[$currency];

		switch ($currency)
		{
			case 'RUR':
			break;

			case 'USD':
			break;
		}

		*/

		$rouble = self::$LIBTEXT_GLOBALS['currency'][$currency];
		$kopeck = self::$LIBTEXT_GLOBALS['currency2'][$currency];

		$str = '';

		$parts = explode($delimiter, $sum);

		if (isset($parts[0])) $str .= text::numtostr($parts[0], 1, $case, 1, 1, $rouble);
		if (isset($parts[1]))
		{
			$parts[1] = substr($parts[1], 0, 2);
			$str .= ' ' . text::numtostr($parts[1], 1, $case, 2, 1, $kopeck);
		}

		return $str;
	}

	/**
	*	Получить денежныую сумму прописью в указаном формате.
	*
	* @param string	$format		Шаблон где:
	*					`%n` - сумма с в формате "1 000 000,00"
	*					`%N` - сумма с в формате "1 000 000,01", если после запятой == 0, то выводится целая только часть "1 000 000"
	*					`%o` - часть суммы до запятой числом;
	*					`%i` - часть суммы до запятой прописью с маленькой буквы;
	*					`%I` - часть суммы до запятой прописью с большой буквы;
	*					`%f` - часть суммы после запятой числом;
	*					`%F` - часть суммы после запятой числом, если == 0, то не выводить;
	*					`%d` - часть суммы после запятой прописью с маленькой буквы;
	*					`%D` - часть суммы после запятой прописью с большой буквы;
	*					`%c` - валюта типа руб., долл. с маленькой буквы
	*					`%C` - валюта типа руб., долл. с большой буквы
	*					`%s` - валюта типа коп., цент. с маленькой буквы
	*					`%S` - валюта типа коп., цент. с большой буквы
	*					`%e` - страна - костыль, для России нихуя, для штатов суффикс США
	*					Например "( %i ) %с" -> "( сто двадцать три ) рубля";
	* @param int	$sum		Сумма в виде числа
	* @param mixed	$currency	Индекс валюты:
	*					1 - RUR;
	*					2 - USD;
	* @param string	$delimiter	Разделитель для конечного варианта
	* @param int	$case		Падеж: 1 - именительный, 2 - родительный, 3 - дательный, 4 - винительный, 5 - творительный, 6 - предложный
	* @return	Преобразованную строку если число соответствует стандарту, либо исходную строку.
	*/
	public static function moneytostrf($format, $sum, $currency = 1, $delimiter = '.', $case = 1)
	{
		// NOTE: Требуется привести к стандарту (123456.89):
		//	не должно садержать пробелы
		//	дробная часть после точки `.`
		//	два знака в дробной части
		if ($delimiter == '.')
			$sum = number_format(text::float($sum, array('single_dot_as_decimal' => TRUE)), 2, '.', '');
		else
			$sum = number_format(text::float($sum), 2, '.', '');

		if (preg_match('/(\d+)(?:[' . $delimiter . ']{1}(\d+))?/', $sum, $matches))
		{
			$_i = '';
			$_I = '';
			$_d = '';
			$_D = '';
			$_c = '';
			$_C = '';
			$_s = '';
			$_S = '';
			$_o = intval($matches[1]);
			$aX = self::$LIBTEXT_GLOBALS['currency'][$currency];
			$_f = sprintf("%02d", (!empty($matches[2])) ? intval(substr($matches[2], 0, 2)) : 0);
			$_F = ($_f > 0) ? $_f : '';
			$_n = number_format($sum, 2, ',', ' ');
			$_N = ($_f > 0) ? $_n : number_format(intval($sum), 0, 0, ' ');
			$aY = self::$LIBTEXT_GLOBALS['currency2'][$currency];

			$x = text::numtostr($_o, 1, $case, 1, 1, $aX);
			if (!empty($aX))
			{
				$x = explode(' ', $x);
				$_c = array_pop($x);
				$_C = ucfirst($_c);
				$_i = join(' ', $x);
			}
			else
			{
				$_i = $x;
			}
			$_I = ucfirst($_i);

			$y = text::numtostr($_f, 1, $case, 2, 1, $aY);
			if (!empty($aY))
			{
				if ($y)
				{
					$y = explode(' ', $y);
					$_s = array_pop($y);
					$_S = ucfirst($_s);
					$_d = join(' ', $y);
					$_D = ucfirst($_d);
					$_p = $dec . '/100';
				}
				else if ($_f == '00')
				{
					$_s = $aY['2']['2'];
					$_S = ucfirst($_s);
				}
			}
			else
			{
				$_d = $y;
			}
			$_D = ucfirst($_d);
			$_p = $dec . '/100';

			if ($_c)
				$_e = self::$LIBTEXT_GLOBALS['ccy_postfix'][$currency];

			$replace_what = array('%n', '%N', '%o', '%i', '%I', '%f', '%F', '%d', '%D', '%p', '%c', '%C', '%s', '%S', '%e');
			$replace_with = array($_n, $_N, $_o, $_i, $_I, $_f, $_F, $_d, $_D, $_p, $_c, $_C, $_s, $_S, $_e);

			return trim(str_replace(
				$replace_what,
				$replace_with,
				$format));
		}
		else
		{
			return $sum;
		}
	}

	/**
	*	Проценты прописью
	*/
	public static function percenttostr($sum, $delimiter = '.', $case = 1, $fraction_as_string = false)
	{
		$whole = self::$LIBTEXT_GLOBALS['whole'];

		$str = '';

		$parts = explode($delimiter, $sum);

		if (isset($parts[1]))
		{
			$gender = 2; // добавляем целые (женский род)
			$noun = $whole;
		}
		else
		{
			$gender = 1; // подразумеваем проценты (мужской род)
			$noun = NULL;
		}

		if (isset($parts[0])) $str .= text::numtostr($parts[0], 1, $case, $gender, 1, $noun);
		if (isset($parts[1]))
		{
			$parts[1] = substr($parts[1], 0, 2);
			$str .= ' ' . sprintf('%-02s', strval($parts[1])) . '/100';
		}

		return $str;
	}

	/**
	* Число прописью
	* @version 1.0 beta
	*
	* @param int $num
	* @param int $numeral_type Тип числительного: 1 - количественное, 2 - порядковое
	* @param int $case	Падеж: 1 - именительный, 2 - родительный, 3 - дательный, 4 - винительный, 5 - творительный, 6 - предложный
	* @param int $gender	Пол: 1 - мужской, 2 - женский
	* @param int $subject_type 1 - неодушевленный, 2 - одушевленный
	* @param array $noun  таблица со спряжениями сопутствующего существительного
	*/
	public static function numtostr($num, $numeral_type = 1, $case = 1, $gender = 1, $subject_type = 1, $noun = NULL)
	{
		$nouns = self::$LIBTEXT_GLOBALS['nouns'];

		$str = array();

		if (bccomp('1000000000000', $num) != 1 || bccomp($num, '0') != 1) return false; // sum must be from 0 to 1 000 000 000 000

		if (bccomp($num, '0') == 0) ; // TODO: special case, sum is equal to zero

		// Fetch triplets one by one from the lowest one
		$thresholds = array( 1 => '1000', '1000000', '1000000000'); // Define thresholds

		// Set initial data
		$order = 1;
		$triplet = bcmod($num, '1000');

		$lowest_nonzero_triplet_found = false; // indicates declension need for ordinal numerals

		while (bccomp($num, '0') == 1)
		{
			if (bccomp($triplet, '0') == 1)
			{
				$with_declension = false; // Whether to decline the last numerical (for ordinal numerals only)

				if (! $lowest_nonzero_triplet_found) $with_declension = true;


				// Define set of nouns

				$triplet_noun = NULL;
				$triplet_gender = 0;
				$triplet_subject_type = 0;

				switch ($order)
				{
					case 1:
						$triplet_noun = $noun;
						$triplet_gender = $gender;
						$triplet_subject_type = $subject_type;
					break;

					case 2:
						// Use internal noun for thousands

						$triplet_noun = $nouns['thousand'];
						$triplet_gender = 2;
						$triplet_subject_type = 1;
					break;

					case 3:
						// Use internal noun for millions

						$triplet_noun = $nouns['million'];
						$triplet_gender = 1;
						$triplet_subject_type = 1;
					break;

					case 4:
						// Use internal noun for billions

						$triplet_noun = $nouns['billion'];
						$triplet_gender = 1;
						$triplet_subject_type = 1;
					break;

					default:
						assert(false); // This must not happen!
				}

				if ($numeral_type == 1)
				{
					array_unshift($str, text::parse_cardinal_triplet($triplet, $order, $case, $triplet_gender, $triplet_subject_type, $triplet_noun, $with_declension, $noun));
					// In this case, with_declension has nothing about declension but indicates the need to add custom noun
					// when order is more than 1 (actually, at the end of the result string)
				}
				elseif ($numeral_type == 2)
				{
					array_unshift($str, text::parse_ordinal_triplet($triplet, $order, $case, $triplet_gender, $triplet_subject_type, $triplet_noun, $with_declension, $gender, $subject_type, $noun));
				}

				if (! $lowest_nonzero_triplet_found) $lowest_nonzero_triplet_found = true;
			}

			$num = bcdiv(bcsub($num, $triplet), '1000');

			$triplet = bcmod($num, '1000');

			$order++;
		}

		return implode(' ', $str);
	}
}
?>
