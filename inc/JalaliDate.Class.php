<?php

/**
 * JalaliDate
 * Privides an exact scientific approach to creating Persian calendar
 * with an output interface as similar to php.date() as possible
 * 
 * @package mixed
 * @subpackage Jalali Date
 * @access protected static
 * @author keyhan sedaghat<keyhansedaghat@netscape.net>
 * @copyright 1371-1390 (1991-2011)
 * @version 3.2.2
 */

/**
 * Examples:
 *
 * Constructing the class (although this is not relly needed):
 * 1. Constructing a class from php.time():
 * 	$jDate_1 = new JalaliDate( ) ;
 * 2. Constructing the class from any given time stamp:
 * 	$jDate_2 = new JalaliDate( TIME_STAMP ) ;
 * 3. Constructing the class from (year, month, day, hour, minute, second) values:
 * 	This is the format used by php.mktime() but gets Persian date values
 * 	$jDate_3 = new JalaliDate( 1383, 12, 30, 13, 45, 25 ) ;
 *
 * Calling class methods:
 * 1. $jDate->date( FORMAT ) ; //syntax of FORMAT is same as php.date() syntax.
 * 	[see: hhtp://www.php.net/manual/en/function.date.php].
 * 2. $jDate->timestamp( ) ; //returns the used (internal) timestamp.
 * 	This is the same value returned from php.time() for the equivalent Julian date.
 * 	(php.date() returns equival Julian date for this time stamp.)
 *
 * Direct call:
 * 1. JalaliDate::date( format, [time stamp], [decorate] ) ; //using the format outputs the timestamp -- or the current
 * 	stamp if left blank, in the desired shape. use decorate to show numbers as Arabic numbers --or strings.
 * 2. JalaliDate::timestamp() ; //returns the used (internal) timestamp. php.date() returns equival Julian date
 * for this time stamp.
 *
 * In general only the following functions should be called:
 * 	mktime(year, month, day, hour, minute, second) to create time stamp for a given date.
 * 	date(format, [time stamp], [decorate]) to build readable output from time stamp.
 */
class JalaliDate {

	/**
	 * Corresponding to the real date-time.
	 * Timestamp in format of and equivalent to standard unix time.
	 *
	 * Note: For direct access to the functions --without constructing the class--
	 * all the members and methods are declared static
	 *
	 * @access private
	 * @var int
	 */
	private static $timeStamp = 0;
	/**
	 * Reference table made by Khayam for leap years
	 *
	 * @access protected
	 * @var int
	 */
	protected static $Khayamii = array(
		0 => 5, 9, 13, 17, 21, 25, 29,
		34, 38, 42, 46, 50, 54, 58, 62,
		67, 71, 75, 79, 83, 87, 91, 95,
		100, 104, 108, 112, 116, 120, 124, 0
	);
	/**
	 * Length of a year
	 * Calculated by Khayam is 365.2422 days (approx.);
	 * but as the years are getting shorter the new value
	 * (valid from year 1380 Per./2000 A.D.) is used instead.
	 *
	 * @access protected
	 * @var double
	 */
	protected static $Khayam_Year = 365.24218956;
	/**
	 * Recent calculations has introduced a correcting factor,
	 * which Khayam could not reach.
	 * This is used to better adjust length of each year in seconds.
	 *
	 * @access protected
	 * @var double
	 */
	protected static $Correcting_Khayam_Year = 0.00000006152;
	/**
	 * Count of days at the end of each Persian month
	 *
	 * @access protected
	 * @var int
	 */
	protected static $mountCounter = array(
		0 => 0, 31, 62, 93, 124, 155,
		186, 216, 246, 276, 306, 336
	);
	/**
	 * value of second in output time
	 *
	 * @access private
	 * @var int
	 */
	private static $second = 0;
	/**
	 * value of minute in output time
	 *
	 * @access private
	 * @var int
	 */
	private static $minute = 0;
	/**
	 * value of hour in output time
	 *
	 * @access private
	 * @var int
	 */
	private static $hour = 0;
	/**
	 * value of day in output time
	 *
	 * @access private
	 * @var int
	 */
	private static $day = 0;
	/**
	 * value of month in output time
	 *
	 * @access private
	 * @var int
	 */
	private static $month = 0;
	/**
	 * value of year in output time
	 *
	 * @access private
	 * @var int
	 */
	private static $year = 0;
	/**
	 * number of days from start of the current year
	 *
	 * @access private
	 * @var int
	 */
	private static $dayOfYear = 0;
	/**
	 * standard php timeZone identifier e.g.:('Asia/Tehran')
	 * ::date('e')
	 *
	 * @access private
	 * @var string
	 */
	private static $timeZone = '';
	/**
	 * standard php timeZone abbreviation e.g.:('IRST')
	 * ::date('T')
	 *
	 * @access private
	 * @var string
	 */
	private static $timeZoneAbb = '';
	/**
	 * day time saving (0|1)
	 * ::date('I')
	 *
	 * @access private
	 * @var int
	 */
	private static $DTS = 0;
	/**
	 * difference from GMT in hours
	 * ::date('O')
	 *
	 * @access private
	 * @var int
	 */
	private static $GMTDiff = 0;
	/**
	 * difference from GMT in hours including colon
	 * ::date('P')
	 *
	 * @access private
	 * @var string
	 */
	private static $GMTDiffC = "";
	/**
	 * defference from GMT in seconds
	 * ::date('Z')
	 *
	 * @access private
	 * @var int
	 */
	private static $timezoneOffset = 0;

	/**
	 * JalaliDate::__construct()
	 * 
	 * @param integer $year --Optional timestamp or hour value
	 * @param integer $month --Optional
	 * @param integer $day --Optional
	 * @param integer $hour --Optional
	 * @param integer $minute --Optional
	 * @param integer $second --Optional
	 * 
	 * single parameter is supposed to be a valid timestamp
	 * multiple --6-- parameters are supposed to be year, month, day, hour, minute, second values.
	 * 
	 * @return object
	 */
	public function __construct($year=0, $month=0, $day=0, $hour=0, $minute=0, $second=0) {
		if (func_num_args() == 1) {
			self::$timeStamp = $year;
		} else if (func_num_args() == 6) {
			self::$timeStamp = self::mktime($year, $month, $day, $hour, $minute, $second);
		} else {
			self::$timeStamp = time();
		}
		self::_date( );
	}

	/**
	 * JalaliDate::ParsiNumbers()
	 *
	 * converts digits to Persian traditional font face and
	 * corrects the no-width space in words
	 * use the function on the returned value in the source code
	 * 
	 * @access protected
	 * @param string $phrase
	 * @return string
	 */
	protected static function ParsiNumbers($phrase) {
		$L = array("0", "1", "2", "3", "4", "5", "6", "7", "8", "9");
		$F = array("۰", "۱", "۲", "۳", "۴", "۵", "۶", "۷", "۸", "۹");
		return str_replace($L, $F, $phrase);
	}

	/**
	 * JalaliDate::dayOfYear()
	 *
	 * get value of the day in the year
	 *  
	 * @access protected
	 * @return int
	 */
	protected static function dayOfYear() {
		return self::$dayOfYear;
	}

	/**
	 * JalaliDate::weekOfYear()
	 * 
	 * @access private
	 * @return int
	 */
	private static function weekOfYear() {
		$x = ( 7 - self::dayOfWeek(self::$year, 1) ) % 7;
		$z = self::$dayOfYear - $x;
		return abs(ceil($z / 7));
	}

	/**
	 * JalaliDate::filterErrors()
	 * 
	 * global function for checking the data and filter any wrong data
	 * 
	 * @param int $dataValue
	 * @param string $dataType
	 * @access private
	 * @return int
	 */
	protected static function filterErrors($dataValue, $dataType, $otherData = 0) {
		return true;
		switch (strtolower($dataType)) {
			case "d" :
			case "day":
				if ($dataValue > 31 || $dataValue < 1) {
					echo "Wrong day of month value";
					return false;
				}
				switch ($otherData) {
					case 1:case 2:case 3:case 4:case 5:case 6:
						$validday = 31;
						break;
					case 7:case 8:case 9:case 10:case 11:case 12:
						$validday = 30;
						break;
					default :
						echo "Wrong month value";
						return false;
				}
				break;

			case "w" :
			case "week":
				if ($dataValue > 6 || $dataValue < 0) {
					echo "Wrong day of week value";
					return false;
				}
				break;

			case "m" :
			case "month":
				if ($dataValue > 12 || $dataValue < 1) {
					echo "Wrong month value";
					return false;
				}
				break;

			case "y" :
			case "year":
				if ($dataValue > 3000 || $dataValue < 1) {
					echo "Wrong year value";
					return false;
				}
				break;

			default :
				return false;
		}
		return true;
	}

	/**
	 * JalaliDate::calcRasad()
	 * 
	 * calculates the years from reference Observation year
	 * 
	 * @param int $yearValue
	 * @param boolean $calendarType
	 * @access protected
	 * @return int
	 */
	protected static function calcRasad($yearValue) {
		if (!JalaliDate::filterErrors($yearValue, 'y'))
			return;
		$Rasad = $yearValue + 2346;
		return $Rasad;
	}

	/**
	 * JalaliDate::isLeap()
	 * 
	 * Checks the specified year for a leap year
	 * The return value is the number of the leap year (1 - 31)in one cycle
	 * for leap years and false for normal years
	 * 
	 * @param int $yearValue
	 * @access protected
	 * @return mixed
	 */
	protected static function isLeap($yearValue) {
		if (!JalaliDate::filterErrors($yearValue, 'y'))
			return;
		$Rasad = JalaliDate::calcRasad($yearValue);
		$yrNam = $Rasad % 2820;
		$yrNam = $yrNam % 128;
		$leapCount = array_search($yrNam, JalaliDate::$Khayamii);
		return $leapCount;
	}

	/**
	 * JalaliDate::dayOfWeek()
	 * 
	 * returns weekday of the specified day of the year
	 * 
	 * @param int $yearValue
	 * @param boolean $calendarType
	 * @access protected
	 * @return mixed
	 */
	protected static function dayOfWeek($yearValue, $dayOfYear = 0) {
		if (!JalaliDate::filterErrors($yearValue, 'y'))
			return;
		$Rasad = JalaliDate::calcRasad($yearValue);

		$count2820 = floor($Rasad / 2820);
		$mod2820 = $Rasad % 2820;
		$count128 = floor($mod2820 / 128);
		$mod128 = $mod2820 % 128;

		$leapCount = 0;
		while ($mod128 > JalaliDate::$Khayamii [$leapCount])
			$leapCount++;

		$yearStartDay = ( $count2820 + 1 ) * 3 +
				$count128 * 5 +
				$mod128 +
				$leapCount
		;
		if ($dayOfYear > 0

			)$dayOfYear--;
		return ($yearStartDay + $dayOfYear) % 7;
	}

	/**
	 * JalaliDate::dayName()
	 * 
	 * returns names of the weekday
	 * 
	 * @param int $dayValue
	 * @access protected
	 * @return string
	 */
	protected static function dayName($dayValue) {
		$weekAlpha = array(
			0 => 'شنبه', 'يکشنبه', 'دوشنبه', 'سه شنبه', 'چهارشنبه', 'پنج شنبه', 'آدينه'
		);
		if (!JalaliDate::filterErrors($dayValue, 'w'))
			return;
		return $weekAlpha [$dayValue];
	}

	/**
	 * JalaliDate::dayShortName()
	 * 
	 * returns abbreviated names of the weekday
	 * 
	 * @param int $dayValue
	 * @access protected
	 * @return string
	 */
	protected static function dayShortName($dayValue) {
		$weekShort = array(
			0 => 'ش', 'ي', 'د', 'س', 'چ', 'پ', 'آ'
		);
		if (!JalaliDate::filterErrors($dayValue, 'w'))
			return;
		return $weekShort [$dayValue];
	}

	/**
	 * JalaliDate::monthName()
	 * 
	 * returns names of the month
	 * 
	 * @param int $monthValue
	 * @access protected
	 * @return string
	 */
	protected static function monthName($monthValue) {
		$monthAlpha = array(
			1 => 'فروردين', 'ارديبهشت', 'خرداد',
			'تير', 'امرداد', 'شهريور',
			'مهر', 'آبان', 'آذر',
			'دي', 'بهمن', 'اسفند'
		);
		if (!JalaliDate::filterErrors($monthValue, 'm'))
			return;
		return $monthAlpha [$monthValue];
	}

	/**
	 * JalaliDate::monthShortName()
	 * 
	 * returns abbreviated names of the month
	 * 
	 * @param int $monthValue
	 * @access protected
	 * @return string
	 */
	protected static function monthShortName($monthValue) {
		$monthShort = array(
			1 => 'فرو', 'ارد', 'خرد',
			'تير', 'امر', 'شهر',
			'مهر', 'آبا', 'آذر',
			'دي', 'بهم', 'اسف'
		);
		if (!JalaliDate::filterErrors($monthValue, 'm'))
			return;
		return $monthShort [$monthValue];
	}

	/**
	 * JalaliDate::monthDayString()
	 * 
	 * returns long text day of the month
	 * 
	 * @param int $monthDayValue
	 * @access protected
	 * @return string
	 */
	protected static function monthDayString($monthDayValue) {
//echo "monthDayString($monthDayValue)<br/>";
		if (!JalaliDate::filterErrors($monthDayValue, 'd', self::$month))
			return;
		$monthDays = array(
			1 => 'یکم', 'دوم', 'سوم', 'چهارم', 'پنجم', 'ششم',
			'هفتم', 'هشتم', 'نهم', 'دهم', 'یازدهم', 'دوازهم',
			'سیزدهم', 'چهاردهم', 'پانزدهم', 'شانزدهم', 'هفدهم', 'هژدهم',
			'نوزدهم', 'بیستم', 'بیست و یکم', 'بیست و دوم', 'بیست و سوم', 'بیست و چهارم',
			'بیست و پنجم', 'بیست و ششم', 'بیست و هفتم', 'بیست و هشتم', 'بیست و نهم', 'سی ام', 'سی و یکم'
		);
		return $monthDays [$monthDayValue];
	}

	/**
	 * JalaliDate::_date()
	 * 
	 * sets the full date data Y;M;D;H;I;S in various class attributes
	 * 
	 * @access private
	 * @return void
	 */
	protected function _date() {
		self::zone();
		$timeStamp = self::$timeStamp;
		$timeStamp = $timeStamp + self::$timezoneOffset;

		$Seconds = floor($timeStamp % 60);
		$Minutes = floor(( $timeStamp % 3600 ) / 60);
		$Hours = floor(( $timeStamp % 86400 ) / 3600);
		$Days = floor($timeStamp / 86400);
		$Days += 287;
		$Years = floor(( $Days / self::$Khayam_Year ) - ( $Days * self::$Correcting_Khayam_Year ));
		$dayOfYear = $Days - round($Years * self::$Khayam_Year, 0);
		if ($dayOfYear == 0)
			$dayOfYear = 366;
		$Years += 1348;
		$Months = 0;
		while ($Months < 12 && $dayOfYear > self::$mountCounter [$Months])
			$Months++;
		$Days = $dayOfYear - self::$mountCounter [$Months - 1];

		self::$second = $Seconds;
		self::$minute = $Minutes;
		self::$hour = $Hours;
		self::$day = $Days;
		self::$month = $Months;
		self::$year = $Years;
		self::$dayOfYear = $dayOfYear;
	}

	/**
	 * JalaliDate::date()
	 *
	 * this is a clone of the internal php function date()
	 * with a few exceptions in the acceptable parameters
	 *
	 * These are the supported formats from php.date():
	  //a: Lowercase Ante meridiem and Post meridiem  	am or pm
	  //A: Uppercase Ante meridiem and Post meridiem 	AM or PM
	  //d: days from 01 to 31
	  //D: days --short-- from ش to آ
	  //j: days from 1 to 31
	  //l (lowercase 'L'): days from شنبه to آدینه
	  //N: number of day in week from 1 (شنبه) to 7 (آدینه)
	  //w: number of day in week
	  //S: month days from یکم to سی و یکم -- this is slightly different from php.date()!
	  //z: day in the year
	  //W: week in the year
	  //F: Month name from قروردین to اسفند
	  //m: Month number from 01 to 12
	  //M: month from فرو to اسف
	  //n: Month number from 1 to 12
	  //Y: full year numeric representation -- 4 digit
	  //y: year numeric representation -- 2 digit
	  //g: 12-hour format of an hour without leading zeros 	1 through 12
	  //G: 24-hour format of an hour without leading zeros 	0 through 23
	  //h: 12-hour format of an hour with leading zeros 	01 through 12
	  //H: 24-hour format of an hour with leading zeros 	00 through 23
	  //i: Minutes with leading zeros 	00 to 59
	  //s: Seconds, with leading zeros 	00 through 59
	  //T: Timezone abbreviation 	Examples: EST, MDT ...
	  //U: Seconds since the Unix Epoch (January 1 1970 00:00:00 GMT) 	See also time()
	  //L: whether it's a leap year
	  //I: (capital i) Whether or not the date is in daylight saving time 1 if Daylight Saving Time, 0 otherwise.
	  //O: Difference to Greenwich time (GMT) in hours 	Example: +0200
	  //P: Difference to Greenwich time (GMT) with colon between hours and minutes (added in PHP 5.1.3)
	  //Z: Timezone offset in seconds. The offset for timezones west of UTC is always negative, and for those east of UTC is always positive. 	-43200 through 50400
	  //c: ISO 8601 date (added in PHP 5) 	2004-02-12T15:19:21+00:00
	  //r: » RFC 2822 formatted date 	Example: Thu, 21 Dec 2000 16:01:07 +0200
	  //e: Timezone identifier (added in PHP 5.1.0) 	Examples: UTC, GMT, Atlantic/Azores
	 *
	 * The following identifiers are not available:
	  //t: number of days in the given month
	  //o: year number
	  //B: Swatch Internet time 	000 through 999
	  //u: Microseconds (added in PHP 5.2.2) 	Example: 54321	 *
	 * @param string $format
	 * @param int $timestamp the unix-type timestamp to be used for output
	 * @param boolean $decorate if true function decorate is used for chanhing the face of output
	 * if false the normal face of output is returned. for numbers false returns number, true returns string.
	 * @access public
	 * @return mixed
	 */
	public static function date($format, $timestamp = 0, $decorate = true) {
		if (func_num_args() == 1) {
			if (self::timestamp( ) > 0) {
				$timestamp = self::timestamp( );
			} else {
				$timestamp = time();
			}
		}
		self::$timeStamp = $timestamp;
		self::_date( );
		$format = str_replace("a", ( self::$hour <= 12 ? "ق.ظ" : "ب.ظ"), $format);
		$format = str_replace("A", ( self::$hour <= 12 ? "ق.ظ" : "ب.ظ"), $format);
		$format = str_replace("d", str_pad(self::$day, 2, '0', STR_PAD_LEFT), $format);
		$format = str_replace("D", self::dayShortName(self::dayOfWeek(self::$year, self::$dayOfYear)), $format);
		$format = str_replace("j", self::$day, $format);
		$format = str_replace("l", self::dayName(self::dayOfWeek(self::$year, self::$dayOfYear)), $format);
		$format = str_replace("N", self::dayOfWeek(self::$year, self::$dayOfYear) + 1, $format);
		$format = str_replace("w", self::dayOfWeek(self::$year, self::$dayOfYear), $format);
		$format = str_replace("S", self::monthDayString(self::$day), $format);
		$format = str_replace("z", self::$dayOfYear, $format);
		$format = str_replace("W", self::weekOfYear(), $format);
		$format = str_replace("F", self::monthName(self::$month), $format);
		$format = str_replace("m", str_pad(self::$month, 2, '0', STR_PAD_LEFT), $format);
		$format = str_replace("M", self::monthShortName(self::$month), $format);
		$format = str_replace("n", self::$month, $format);
		$format = str_replace("Y", self::$year, $format);
		$format = str_replace("y", ( self::$year % 100), $format);
		$format = str_replace("g", ( self::$hour % 12), $format);
		$format = str_replace("G", self::$hour, $format);
		$format = str_replace("h", str_pad(( self::$hour % 12), 2, '0', STR_PAD_LEFT), $format);
		$format = str_replace("H", str_pad(self::$hour, 2, '0', STR_PAD_LEFT), $format);
		$format = str_replace("i", str_pad(self::$minute, 2, '0', STR_PAD_LEFT), $format);
		$format = str_replace("s", str_pad(self::$second, 2, '0', STR_PAD_LEFT), $format);
		$format = str_replace("U", self::$timeStamp, $format);
		$format = str_replace("L", self::isLeap(self::$year), $format);
		$format = str_replace("Y", self::$year, $format);
		$format = str_replace("I", self::$DTS, $format);
		$format = str_replace("O", self::$GMTDiff, $format);
		$format = str_replace("P", self::$GMTDiffC, $format);
		$format = str_replace("Z", self::$timezoneOffset, $format);
		$format = str_replace("c", self::$year . "-" .
						str_pad(self::$month, 2, '0', STR_PAD_LEFT) . "-" .
						str_pad(self::$day, 2, '0', STR_PAD_LEFT) . "ز" .
						str_pad(self::$hour, 2, '0', STR_PAD_LEFT) . ":" .
						str_pad(self::$minute, 2, '0', STR_PAD_LEFT) . ":" .
						str_pad(self::$second, 2, '0', STR_PAD_LEFT) .
						self::$GMTDiffC, $format)
		;
		$format = str_replace("r", self::dayShortName(self::dayOfWeek(self::$year, self::$dayOfYear)) . "، " .
						self::$day . " " .
						self::monthShortName(self::$month) . " " .
						self::$year . " " .
						self::$hour . ":" .
						self::$minute . ":" .
						self::$second .
						self::$GMTDiff, $format)
		;
		$format = str_replace("T", self::$timeZoneAbb, $format);
		$format = str_replace("e", self::$timeZone, $format);

		if ($decorate) {
			return self::ParsiNumbers($format);
		} else {
			return $format;
		}
	}

	/**
	 * JalaliDate::mktime()
	 * 
	 * creates timestamp depending on the Persian time parameters
	 * 
	 * @access protected 
	 *
	 * @param mixed $year
	 * @param mixed $month
	 * @param mixed $day
	 * @param mixed $hour
	 * @param mixed $minute
	 * @param mixed $second
	 * @return integer time stamp
	 */
	public static function mktime($year, $month, $day, $hour=0, $minute=0, $second=0) {
		$timeStamp = $second;
		$timeStamp += $minute * 60;
		$timeStamp += $hour * 60 * 60;

		$dayOfYear = ($day + self::$mountCounter[$month - 1]);
		if ($year < 1300)
			$year += 1300;
		$year -= 1348;
		$day = $dayOfYear + round(( self::$Khayam_Year * $year), 0);
		$day -=287;
		$timeStamp += $day * 86400;
		self::$timeStamp = $timeStamp ;
		self::zone();
		$timeStamp = $timeStamp - self::$timezoneOffset;
		return $timeStamp;
	}

	/**
	 * JalaliDate::timestamp()
	 * 
	 * returns current timestamp of the object
	 * this time stamp is equivalent to system timestamp; for current time
	 *
	 * @access public 
	 * @return int
	 */
	public static function timestamp() {
		return self::$timeStamp;
	}

	/**
	 * JalaliDate::zone()
	 *
	 * set all properties related to time zone
	 *
	 * @access private
	 * @return void
	 */
	private static function zone() {
//	 * ::date('e')
		self::$timeZone = date_default_timezone_get();
//	 * ::date('T')
		$timeZoneAbb = date('T', self::$timeStamp);
		self::$timeZoneAbb = date('T', self::$timeStamp);
//	 * ::date('I')
		self::$DTS = date('I', self::$timeStamp);
//	 * ::date('O')
		self::$GMTDiff = date('O', self::$timeStamp);
//	 * ::date('P')
		self::$GMTDiffC = date('P', self::$timeStamp);
//	 * ::date('Z')
		self::$timezoneOffset = date('Z', self::$timeStamp);
	}

}

