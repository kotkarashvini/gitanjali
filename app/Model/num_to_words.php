<?php

/*
 * HERE MAIN TWO FUNCTION
 * 
 * in_words(12546);// PASS NUMERIC VALUE AND GET THAT VALUE IN "WORDS"
 * datetowords('26-03-1990'); pass date in "dd-mm-yyyy" format to get date in "words"
 * 
 */
App::uses('AppModel', 'Model');

class num_to_words extends AppModel {

    function str_replace_last($search, $replace, $str) {
        if (( $pos = strrpos($str, $search) ) !== false) {
            $search_length = strlen($search);
            $str = substr_replace($str, $replace, $pos, $search_length);
        }
        return $str;
    }

    function trim_all($str, $what = NULL, $with = ' ') {
        if ($what === NULL) {
            //  Character      Decimal      Use
            //  "\0"            0           Null Character
            //  "\t"            9           Tab
            //  "\n"           10           New line
            //  "\x0B"         11           Vertical Tab
            //  "\r"           13           New Line in Mac
            //  " "            32           Space

            $what = "\\x00-\\x20";    //all white-spaces and control chars
        }

        return trim(preg_replace("/[" . $what . "]+/", $with, $str), $what);
    }

    function in_words($num = '') {
        $number = (string) ( (float) $num );
       // pr($number);
        //$number = 190908100.25;
        $no = round($number);
        $point = round($number - $no, 2) * 100;
        $hundred = null;
        $digits_1 = strlen($no);
        $i = 0;
        $str = array();
        $words = array('0' => '', '1' => 'one', '2' => 'two',
            '3' => 'three', '4' => 'four', '5' => 'five', '6' => 'six',
            '7' => 'seven', '8' => 'eight', '9' => 'nine',
            '10' => 'ten', '11' => 'eleven', '12' => 'twelve',
            '13' => 'thirteen', '14' => 'fourteen',
            '15' => 'fifteen', '16' => 'sixteen', '17' => 'seventeen',
            '18' => 'eighteen', '19' => 'nineteen', '20' => 'twenty',
            '30' => 'thirty', '40' => 'forty', '50' => 'fifty',
            '60' => 'sixty', '70' => 'seventy',
            '80' => 'eighty', '90' => 'ninety');
        $digits = array('', 'hundred', 'thousand', 'lakh', 'crore','Billion');
        while ($i < $digits_1) {
            $divider = ($i == 2) ? 10 : 100;
            $number = floor($no % $divider);
            $no = floor($no / $divider);
            $i += ($divider == 10) ? 1 : 2;
            if ($number) {
                $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
                $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
                $str [] = ($number < 21) ? $words[$number] .
                        " " . $digits[$counter] . $plural . " " . $hundred :
                        $words[floor($number / 10) * 10]
                        . " " . $words[$number % 10] . " "
                        . $digits[$counter] . $plural . " " . $hundred;
            } else
                $str[] = null;
        }
        $str = array_reverse($str);
        $result = implode('', $str);
        return ucwords($result);
        $points = ($point) ?
                "." . $words[$point / 10] . " " .
                $words[$point = $point % 10] : '';
        //echo $result . "Rupees  " . $points . " Paise";
    }
    function datetowords($dateindigit) {
        $date = explode('-', $dateindigit);
        $dd = $date[0];
        $mm = $date[1];
        $year = $date[2];
        $strDateArray = array("First", "Second", "Third", "Fourth", "Fifth", "Sixth", "Seventh", "Eighth", "Ninth", "Tenth",
            "Eleventh", "Twelfth", "Thirteenth", "Fourteenth", "Fifteenth", "Sixteenth", "Seventeenth", "Eighteenth", "Nineteenth", "Twentieth",
            "Twenty-First", "Twenty-Second", "Twenty-Third", "Twenty-Fourth", "Twenty-Fifth", "Twenty-Sixth", "Twenty-Seventh", "Twenty-Eighth", "Twenty-Ninth", "Thirtieth", "Thirty-First");
        $month_names = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");

        $strDate = $strDateArray[$dd - 1] . " " . $month_names[$mm - 1] . " ";
        if ($year < 2000) {
            $strDate.=$this->in_words(substr($year, 0, 2)) . " ";
            $strDate.=$this->in_words(substr($year, 2, 2));
        } else {
            $strDate.=$this->in_words($year);
            $strDate = str_replace(" and", "", $strDate);
        }
        return $strDate;
    }

}
