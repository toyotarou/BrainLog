<?php

namespace App\Http\Controllers\Other;

use App\Http\Controllers\Controller;
use App\MyClass\Utility;
use DB;
use Illuminate\Http\Request;

class OtherController extends Controller
{

    public $Utility;

    public function __construct()
    {

        $sql = "SET sql_mode = '';";
        DB::statement($sql);

        $this->Utility = new Utility;
    }

    public function tuning()
    {
        return view('other.tuning');
    }

    public function holiday()
    {
        //------------------//
        $holiday = [];
        $file = public_path() . "/mySetting/holiday.data";
        $content = file_get_contents($file);
        $ex_content = explode("\n", mb_convert_encoding($content, "utf8", "sjis-win"));
        foreach ($ex_content as $v) {
            if (trim($v) == "") {
                continue;
            }
            $holiday[] = trim($v);
        }
        sort($holiday);
        //------------------//

        return view('other.holiday')
            ->with('holiday', $holiday);
    }

    public function holidayinput()
    {
        $file = public_path() . "/mySetting/holiday.data";
        file_put_contents($file, $_POST['holiday']);
        return redirect('/article/index');
    }

    public function user()
    {
        $user = "";

        $file = public_path() . "/mySetting/user.data";
        $content = file_get_contents($file);

        if (!empty($content)) {
            $user = mb_convert_encoding($content, "utf8", "sjis-win");
        }

        return view('other.user')
            ->with('user', $user);
    }

    public function userinput()
    {
        $file = public_path() . "/mySetting/user.data";
        file_put_contents($file, $_POST['user']);
        return redirect('/article/index');
    }

    public function weather()
    {
        $open_url = "https://tenki.jp/week/3/15/";
        $content = file_get_contents($open_url);
        $ex_content = explode("\n", $content);

        $a = [];
        foreach ($ex_content as $k => $v) {
            if (preg_match("/week-index-table/", trim($v))) {
                $a[] = $k;
            }
        }

        $AAA = "";
        for ($i = $a[0]; $i < $a[1]; $i++) {
            $AAA .= trim($ex_content[$i]);
        }

        $ex_AAA = explode("|", strtr($AAA, ['<tr' => '|<tr']));

        /////////////////////
        $date = [];
        $ex_AAA1 = explode("|", strtr($ex_AAA[1], ['><' => '>|<']));
        foreach ($ex_AAA1 as $value) {
            if (preg_match("/<th.+class=\"date\">(.+)<\/th>/", trim($value), $m)) {
                $ex_m1 = explode("日", trim($m[1]));
                $date[] = trim($ex_m1[0]);
            }
        }

        $startDate = date("Y-m-") . $date[0];
        $date[1] = $startDate;
        for ($i = 2; $i < count($date); $i++) {
            $date[$i] = date("Y-m-d", strtotime($startDate) + (86400 * ($i - 1)));
        }
        /////////////////////

        /////////////////////
        $weather = [];
        $ex_AAA2 = explode("|", strtr($ex_AAA[2], ['><' => '>|<']));
        $i = 1;
        foreach ($ex_AAA2 as $value) {
            if (preg_match("/<span class=\"forecast-telop\">(.+)<\/span>/", trim($value), $m)) {
                $weather[$i] = trim($m[1]);
                $i++;
            }
        }
        /////////////////////

        $DDD = [];
        $open_url = public_path() . "/mySetting/weather.data";
        if (file_exists($open_url)) {
            $content = file_get_contents($open_url);
            $ex_content = explode("\n", $content);

            foreach ($ex_content as $v) {
                if (trim($v) == "") {
                    continue;
                }

                $ex_v = explode("|", trim($v));

                $DDD[trim($ex_v[0])] = trim($ex_v[1]);
            }
        }

        for ($i = 1; $i < count($date); $i++) {
            $DDD[$date[$i]] = $weather[$i];
        }

        ksort($DDD);

        $EEE = array();
        foreach ($DDD as $k => $v) {
            if (trim($v) == "") {
                continue;
            }
            $EEE[] = $k . "|" . $v;
        }

        file_put_contents($open_url, implode("\n", $EEE));

        @chmod($open_url, 0777);

        return redirect('/article/index');
    }

    public function tag()
    {
        $tag = [];

        $file = public_path() . "/mySetting/tag.data";
        $content = file_get_contents($file);

        if (!empty($content)) {
            $ex_content = explode("\n", $content);
            if (!empty($ex_content)) {
                foreach ($ex_content as $v) {
                    if (trim($v) == "") {
                        continue;
                    }
                    $tag[] = trim($v);
                }
            }
        }

        return view('other.tag')
            ->with('tag', $tag);
    }

    public function taginput()
    {
        $file = public_path() . "/mySetting/tag.data";
        file_put_contents($file, $_POST['tag']);
        return redirect('/article/index');
    }

    public function seiyuu()
    {
        $data = [];

        $cols = ['itemid', 'itemname', 'price', 'url', 'img'];

        $file = public_path() . "/mySetting/seiyuu.data";
        $content = file_get_contents($file);

        if (!empty($content)) {
            $ex_content = explode("\n", $content);
            if (!empty($ex_content)) {
                $i = 0;
                foreach ($ex_content as $v) {
                    if (trim($v) == "") {
                        continue;
                    }

                    $ex_v = explode("|", trim($v));

                    foreach ($cols as $k2 => $v2) {
                        if (!isset($ex_v[$k2])) {
                            continue;
                        }
                        $data[$i][$v2] = $ex_v[$k2];
                    }

                    $i++;
                }
            }
        }

        for ($j = $i; $j < ($i + 3); $j++) {
            $data[$j]['itemname'] = "";
            $data[$j]['url'] = "";
        }

        return view('other.seiyuu')
            ->with('data', $data);
    }

    public function seiyuuinput()
    {
        $data = [];

        ////////////////////////////////
        $cols = ['itemid', 'itemname', 'price', 'url', 'img'];

        $file = public_path() . "/mySetting/seiyuu.data";
        $content = file_get_contents($file);

        if (!empty($content)) {
            $ex_content = explode("\n", $content);
            if (!empty($ex_content)) {
                $i = 0;
                foreach ($ex_content as $k => $v) {
                    if (trim($v) == "") {
                        continue;
                    }

                    //削除
                    if (!empty($_POST['delete'][$k])) {
                        continue;
                    }

                    $ex_v = explode("|", trim($v));

                    foreach ($cols as $k2 => $v2) {
                        if (!isset($ex_v[$k2])) {
                            continue;
                        }
                        $data[$i][$v2] = $ex_v[$k2];
                    }

                    $i++;
                }
            }
        }
        ////////////////////////////////

        if (!empty($_POST['url'])) {
            foreach ($_POST['url'] as $k => $v) {
                if (trim($v) == "") {
                    continue;
                }
                $data[$k][0] = "";
                $data[$k][1] = "";
                $data[$k][2] = "";
                $data[$k][3] = $v;
            }
        }

        $out = [];
        foreach ($data as $v) {
            $out[] = implode("|", $v);
        }

        file_put_contents($file, implode("\n", $out));

        return redirect('/other/seiyuu');
    }

    public function seiyuuarticle()
    {
        //---------//
        $article = $_POST['article'];
        sort($article);
        //---------//

        $data = [];

        $cols = ['itemid', 'itemname', 'price', 'url', 'img'];

        $file = public_path() . "/mySetting/seiyuu.data";
        $content = file_get_contents($file);

        if (!empty($content)) {
            $ex_content = explode("\n", $content);
            if (!empty($ex_content)) {
                $i = 0;
                foreach ($ex_content as $k => $v) {
                    if (trim($v) == "") {
                        continue;
                    }

                    $ex_v = explode("|", trim($v));

                    foreach ($cols as $k2 => $v2) {
                        if (!isset($ex_v[$k2])) {
                            continue;
                        }
                        $data[$i][$v2] = $ex_v[$k2];
                    }

                    $i++;
                }
            }
        }

        $data2 = [];
        $i = 0;
        $item_base = "{ITEMNAME}　{PRICE}円";
        foreach ($data as $v) {
            if (in_array($v['itemid'], $article, true)) {
                $data2[$i] = strtr($item_base, ['{ITEMNAME}' => $v['itemname'], '{PRICE}' => $v['price']]);
                $i++;
            }
        }

        //>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
        $result = DB::table('t_article' . date("Y"))->where('year', '=', date("Y"))->where('month', '=', date("m"))->where('day', '=', date("d"))->get(['id']);

        $insert = [];
        $insert['year'] = date("Y");
        $insert['month'] = date("m");
        $insert['day'] = date('d');
        $insert['num'] = (!empty($result)) ? count($result) : 0;
        $insert['article'] = implode("\n", $data2);
        $insert['tag'] = "西友";
        $insert['created_at'] = date("Y-m-d");
        $insert['updated_at'] = date("Y-m-d");

        DB::table('t_article' . date("Y"))->insert($insert);
        //>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>

        return redirect("/article/" . date("Y-m-d") . "/display");
    }

    public function work()
    {
        return view('other.work');
    }

    public function workinput()
    {
        if (!empty($_POST['work'])) {
            $ex_work = explode("\n", $_POST['work']);
            array_pop($ex_work);

            $YM = trim($ex_work[0]);
            $year = substr($YM, 0, 4);
            $month = substr($YM, 4, 2);

            $param = [];
            foreach ($ex_work as $k => $v) {
                if ($k == 0) {
                    continue;
                }
                if ($k == 1) {
                    continue;
                }

                $ex_value = explode("\t", trim($v));
                $ary = [];
                $j = 0;
                foreach ($ex_value as $v) {
                    if (trim($v) != "") {
                        $ary[$j] = trim($v);
                        $j++;
                    }
                }

                $day = sprintf("%02d", ($k - 1));
                $param[$year . "-" . $month . "-" . $day] = $ary;
            }

            if (!empty($param)) {
                $insert = [];
                foreach ($param as $date => $v) {
                    if (empty($v)) {
                        continue;
                    }

                    list($insert[$date]['year'], $insert[$date]['month'], $insert[$date]['day']) = explode("-", $date);
                    $insert[$date]['work_start'] = $v[0];
                    $insert[$date]['work_end'] = $v[1];
                }

                DB::table('t_worktime')->insert($insert);
            }
        }

        return redirect("/article/index");
    }

    public function shokureki()
    {

        $color = [];
        $color['A'] = "#E60012";
        $color['B'] = "#EB6100";
        $color['C'] = "#F39800";
        $color['D'] = "#FCC800";
        $color['E'] = "#FFF100";
        $color['F'] = "#CFDB00";
        $color['G'] = "#8FC31F";
        $color['H'] = "#22AC38";
        $color['I'] = "#009944";
        $color['J'] = "#009B6B";
        $color['K'] = "#009E96";
        $color['L'] = "#00A0C1";
        $color['M'] = "#00A0E9";
        $color['N'] = "#0086D1";
        $color['O'] = "#0068B7";
        $color['P'] = "#00479D";
        $color['Q'] = "#1D2088";
        $color['R'] = "#601986";
        $color['S'] = "#920783";
        $color['T'] = "#BE0081";
        $color['U'] = "#E4007F";
        $color['V'] = "#E5006A";
        $color['W'] = "#E5004F";
        $color['X'] = "#E60033";

        $hanrei = "";
        $file = public_path() . "/mySetting/companyHistory.data";
        $content = file_get_contents($file);
        if (!empty($content)) {
            $ex_content = explode("\n", $content);
            if (!empty($ex_content)) {
                $count = count($ex_content);
                $YM = [];
                $hanrei .= "<table class='dispTable'>";
                foreach ($ex_content as $k => $v) {
                    if (trim($v) == "") {
                        continue;
                    }
                    list($flag, $term, $company) = explode("\t", trim($v));
                    $bgColor = "background : " . $color[$flag] . ";";
                    $hanrei .= "<tr style='" . $bgColor . "'>";
                    $hanrei .= "<td>" . $flag . "</td>";
                    $hanrei .= "<td>" . $term . "</td>";
                    $hanrei .= "<td>" . $company . "</td>";
                    $hanrei .= "</tr>";
                    if ($k == ($count - 2)) {
                        $term .= date("Y/m");
                    }
                    list($termStart, $termEnd) = explode(" - ", $term);
                    $startDate = strtr($termStart, ['/' => '']) . "01";
                    $endDate = strtr($termEnd, ['/' => '']) . "20";
                    for ($i = strtotime($startDate); $i < strtotime($endDate); $i += 86400) {
                        $YM[$flag][date("Y", $i)][date("m", $i)] = "";
                    }
                }
                $hanrei .= "</table>";
            }
        }

        $param = [];
        foreach ($YM as $flag => $v) {
            foreach ($v as $year => $v2) {
                foreach ($v2 as $month => $v3) {
                    $param[$year][$month] = $flag;
                }
            }
        }

        $param2 = [];
        for ($i = 1999; $i <= date("Y"); $i++) {
            for ($j = 1; $j <= 12; $j++) {
                if (isset($param[$i][sprintf("%02d", $j)])) {
                    $param2[$i][$j] = $param[$i][sprintf("%02d", $j)];
                } else {
                    $param2[$i][$j] = "";
                }
            }
        }

        $data = "";
        $data .= "<table class='dispTable'>";
        for ($i = 1999; $i <= date("Y"); $i++) {
            $data .= "<tr>";
            for ($j = 1; $j <= 12; $j++) {
                $bgColor = (trim($param2[$i][$j]) != "") ? "background : " . $color[$param2[$i][$j]] . ";" : "";
                $data .= "<td style='" . $bgColor . "'>";
                $data .= "<div>" . $i . "-" . sprintf("%02d", $j) . "</div>";
                $data .= "<div>";
                $data .= (trim($param2[$i][$j]) != "") ? $param2[$i][$j] : "<br>";
                $data .= "</div>";
                $data .= "</td>";
            }
            $data .= "</tr>";
        }
        $data .= "</table>";

        return view('other.shokureki')
            ->with('data', $data)
            ->with('hanrei', $hanrei);
    }

    public function souvenir(Request $request)
    {
        $upload = 0;
        if (isset($_POST['upload'])) {
            $path_before = $_FILES["aaaaa"]["tmp_name"];
            $path_after = "/var/www/html/souvenir/" . $_FILES["aaaaa"]["name"];
            move_uploaded_file($path_before, $path_after);

            $upload = 1;
        }

        return view('other.souvenir')
            ->with('upload', $upload);
    }

    public function kinmu($yearmonth = null)
    {
        //------------------//
        $holiday = [];
        $file = public_path() . "/mySetting/holiday.data";
        $content = file_get_contents($file);
        $ex_content = explode("\n", mb_convert_encoding($content, "utf8", "sjis-win"));
        foreach ($ex_content as $v) {
            if (trim($v) == "") {
                continue;
            }
            $holiday[] = strtr(trim($v), ['-' => '']);
        }
        sort($holiday);
        //------------------//

        if (!empty($yearmonth)) {
            list($gYear, $gMonth) = explode("-", $yearmonth);
        }

        $thisMonthYear = (!empty($gYear)) ? $gYear : date("Y");
        $thisMonthMonth = (!empty($gMonth)) ? $gMonth : date("m");

        $thisMonthEnd = date("t", strtotime($thisMonthYear . $thisMonthMonth));

        //------------------//
        $workTime = [];
        $table_exists = [];
        $result = DB::table('t_worktime')->where('year', '=', $thisMonthYear)->where('month', '=', $thisMonthMonth)->orderBy('day')->get(['year', 'month', 'day', 'work_start', 'work_end']);
        if (!empty($result)) {
            foreach ($result as $v) {
                $workTime[$v->year . $v->month . $v->day]['work_start'] = date("H:i", strtotime($v->work_start));
                $workTime[$v->year . $v->month . $v->day]['work_end'] = date("H:i", strtotime($v->work_end));

                if ((trim($v->work_start) != "") and (trim($v->work_end) != "")) {
                    $table_exists[$v->year . $v->month . $v->day] = "";
                }
            }
        }
        //------------------//

        $youbi = ['日', '月', '火', '水', '木', '金', '土'];

        $calender = "";
        $calender .= "<table border='0' cellspacing='2' cellpadding='2' id='tbl_calender'>";
        $ymdAry = [];
        $workShoukei = [];
        for ($i = 1; $i <= $thisMonthEnd; $i++) {

            if (!checkdate($thisMonthMonth, $i, $thisMonthYear)) {
                continue;
            }

            $Ymd = $thisMonthYear . $thisMonthMonth . sprintf("%02d", $i);
            $ymdAry[] = $Ymd;

            $_youbi = date("w", strtotime($Ymd));

            $defaultStart = "9:00";
            $defaultEnd = "18:00";
            $bgColorStyle = "";
            switch ($_youbi) {
                case 0:
                    $defaultStart = "";
                    $defaultEnd = "";
                    $bgColorStyle = "background : #ffcccc;";
                    break;

                case 6:
                    $defaultStart = "";
                    $defaultEnd = "";
                    $bgColorStyle = "background : #ccccff;";
                    break;
            }

            if (in_array($Ymd, $holiday, true)) {
                $defaultStart = "";
                $defaultEnd = "";
                $bgColorStyle = "background : #ffcccc;";
            }

            if (isset($workTime[$Ymd]['work_start'])) {
                $defaultStart = $workTime[$Ymd]['work_start'];
            }
            if (isset($workTime[$Ymd]['work_end'])) {
                $defaultEnd = $workTime[$Ymd]['work_end'];
            }

            $calender .= "<tr style='" . $bgColorStyle . "'>";
            $calender .= "<td>";
            $calender .= sprintf("%02d", $i);
            if (isset($table_exists[$Ymd])) {
                $calender .= "*";
            }
            $calender .= "</td>";
            $calender .= "<td>" . $youbi[$_youbi] . "</td>";

            $calender .= "<td id='StartTime_" . $Ymd . "' class='TimeTd'>";
            $calender .= "<div id='disp_StartTime_" . $Ymd . "'>" . $defaultStart . "</div>";
            $calender .= "<input type='hidden' id='value_StartTime_" . $Ymd . "' name='StartTime[" . $Ymd . "]' value='" . $defaultStart . "'>";
            $calender .= "</td>";

            $calender .= "<td id='EndTime_" . $Ymd . "' class='TimeTd'>";
            $calender .= "<div id='disp_EndTime_" . $Ymd . "'>" . $defaultEnd . "</div>";
            $calender .= "<input type='hidden' id='value_EndTime_" . $Ymd . "' name='EndTime[" . $Ymd . "]' value='" . $defaultEnd . "'>";
            $calender .= "</td>";

            $calender .= "</tr>";

            if (strtotime($defaultEnd) - strtotime($defaultStart) > 0) {
                $workShoukei[$Ymd] = strtotime($defaultEnd) - strtotime($defaultStart);
            }
        }
        $calender .= "</table>";

        ///////////////////////////////////
        $target_day = $thisMonthYear . "-" . $thisMonthMonth . "-01";

        $prevMonth = "0";
        if (strtotime($thisMonthYear . "-" . $thisMonthMonth . "-01") > strtotime("2014-10")) {
            $prevMonth = date("Y-m", strtotime($target_day . "-1 month"));
        }

        $nextMonth = "0";
        if (strtotime($thisMonthYear . "-" . $thisMonthMonth . "-01") < strtotime(date("Y-m") . "-01")) {
            $nextMonth = date("Y-m", strtotime($target_day . "+1 month"));
        }
        ///////////////////////////////////

        $sum_work = (array_sum($workShoukei) / 60 / 60) - count($workShoukei);
        $ex_sw = explode(".", $sum_work);
        $ary = [];
        $ary[] = $ex_sw[0] . "時間";
        if (count($ex_sw) > 1) {
            $tmp = $ex_sw[1] / 100 * 60;
            $ary[] = (strlen($tmp) == 1) ? $tmp . "0分" : $tmp . "分";
        }
        $totalWorkTime = implode("", $ary);

        return view('other.kinmu')
            ->with('thisMonthYear', $thisMonthYear)
            ->with('thisMonthMonth', $thisMonthMonth)
            ->with('calender', $calender)
            ->with('ymdStr', implode("|", $ymdAry))
            ->with('prevMonth', $prevMonth)
            ->with('nextMonth', $nextMonth)
            ->with('totalWorkTime', $totalWorkTime);
    }

    public function kinmuinput()
    {
        $jumpYear = "";
        $jumpMonth = "";

        $workTime = [];
        foreach ($_POST['StartTime'] as $date => $time) {
            if (isset($_POST['EndTime'][$date])) {
                if (strtotime($date) > strtotime(date("Ymd"))) {
                    continue;
                }

                //両方入っている場合のみ
                if (trim($_POST['StartTime'][$date]) != "" and trim($_POST['EndTime'][$date]) != "") {
                    $workTime[$date] = $_POST['StartTime'][$date] . "|" . $_POST['EndTime'][$date];

                    $year = substr($date, 0, 4);
                    $month = substr($date, 4, 2);

                    $jumpYear = $year;
                    $jumpMonth = $month;
                }
            }
        }

        //いったん削除
        DB::table('t_worktime')->where('year', '=', $jumpYear)->where('month', '=', $jumpMonth)->delete();
        DB::statement("alter table t_worktime auto_increment = 1;");

        if (!empty($workTime)) {
            foreach ($workTime as $date => $v) {
                list($work_start, $work_end) = explode("|", $v);

                $year = substr($date, 0, 4);
                $month = substr($date, 4, 2);
                $day = substr($date, 6, 2);

                $insert = [];
                $insert['year'] = $year;
                $insert['month'] = $month;
                $insert['day'] = $day;
                $insert['work_start'] = $work_start;
                $insert['work_end'] = $work_end;

                DB::table('t_worktime')->insert($insert);
            }
        }

        return redirect("/other/" . $jumpYear . "-" . $jumpMonth . "/kinmu");
    }

    public function weathermonthapi($yearmonth)
    {

        $tenki_image = [
            '晴' => 'mark_tenki_hare.png',
            '晴のち一時雨' => 'mark_tenki_hare_ame.png',
            '晴のち時々曇' => 'mark_tenki_hare_kumori.png',
            '晴のち時々雨' => 'mark_tenki_hare_ame.png',
            '晴のち曇' => 'mark_tenki_hare_kumori.png',
            '晴のち雨' => 'mark_tenki_hare_ame.png',
            '晴一時雨' => 'mark_tenki_hare_ame.png',
            '晴一時雪' => 'mark_tenki_hare_yuki.png',
            '晴時々曇' => 'mark_tenki_hare_kumori.png',
            '晴時々雨' => 'mark_tenki_hare_ame.png',

            '曇' => 'mark_tenki_kumori.png',
            '曇のち一時雨' => 'mark_tenki_kumori_ame.png',
            '曇のち時々晴' => 'mark_tenki_hare_kumori.png',
            '曇のち時々雨' => 'mark_tenki_kumori_ame.png',
            '曇のち晴' => 'mark_tenki_hare_kumori.png',
            '曇のち雨' => 'mark_tenki_kumori_ame.png',
            '曇のち雨か雪' => 'mark_tenki_kumori_ame.png',
            '曇のち雪か雨' => 'mark_tenki_kumori_yuki.png',
            '曇一時雨' => 'mark_tenki_kumori_ame.png',
            '曇一時雨か雪' => 'mark_tenki_kumori_ame.png',
            '曇一時雪' => 'mark_tenki_kumori_yuki.png',
            '曇時々晴' => 'mark_tenki_hare_kumori.png',
            '曇時々雨' => 'mark_tenki_kumori_ame.png',
            '曇時々雨か雪' => 'mark_tenki_kumori_ame.png',
            '曇時々雪' => 'mark_tenki_kumori_yuki.png',

            '雨' => 'mark_tenki_ame.png',
            '雨か雪' => 'mark_tenki_ame.png',
            '雨か雪のち曇' => 'mark_tenki_kumori_ame.png',
            '雨で暴風を伴う' => 'mark_tenki_ame.png',
            '雨のち晴' => 'mark_tenki_hare_ame.png',
            '雨のち曇' => 'mark_tenki_kumori_ame.png',
            '雨一時雪' => 'mark_tenki_ame_yuki.png',
            '雨時々止む' => 'mark_tenki_ame.png',

            '雪か雨のち曇' => 'mark_tenki_kumori_yuki.png',
            '雪のち曇' => 'mark_tenki_kumori_yuki.png',
        ];

        list($year, $month) = explode("-", $yearmonth);

        $WeatherData = [];

        $file = "/var/www/html/BrainLog/public/mySetting/weather.data";
        if (file_exists($file)) {
            $content = file_get_contents($file);
            $ex_content = explode("\n", $content);
            if (!empty($ex_content)) {
                foreach ($ex_content as $v) {
                    if (trim($v) == "") {
                        continue;
                    }

                    list($date, $weather) = explode("|", trim($v));
                    list($data_year, $data_month, $data_day) = explode("-", $date);

                    if ($year == $data_year and $month == $data_month) {
                        $ary = [];
                        $ary['date'] = $date;
                        $ary['weather'] = $weather;
                        $ary['image'] = (isset($tenki_image[$weather])) ? $tenki_image[$weather] : "mark_tenki_other.png";
                        $WeatherData['data'][] = $ary;

                        /*
                    $ary[] = $date;
                    $ary[] = $weather;
                    $ary[] = (isset($tenki_image[$weather])) ? $tenki_image[$weather] : "mark_tenki_other.png";
                    $WeatherData['data'][] = implode("|", $ary);
                     */
                    }
                }
            }
        }

//print_r($WeatherData);

        echo json_encode($WeatherData);
    }


    public function kabukaapi()
    {

        $data = [];

        $create_count = [];

        $sql = "select code, sum(point) goukei, count(code) cnt from t_stock group by code order by goukei desc, cnt desc;";
        $result = DB::select($sql);

        foreach ($result as $k => $v) {
            $result2 = DB::table('t_stock')
                ->where('code', '=', $v->code)
                ->orderBy('created_at', 'desc')
                ->take(1)
                ->get();

            $data['data'][$k]['code'] = $v->code;

            $data['data'][$k]['company'] = $result2[0]->company;
            $data['data'][$k]['industry'] = $result2[0]->industry;
            $data['data'][$k]['market'] = $result2[0]->market;
            $data['data'][$k]['torihikichi'] = $result2[0]->torihikichi;
            $data['data'][$k]['grade'] = $result2[0]->grade;
            $data['data'][$k]['percentage'] = $result2[0]->percentage;

            $data['data'][$k]['goukei'] = $v->goukei;
            $data['data'][$k]['cnt'] = $v->cnt;

            $create_count[$result2[0]->created_at] = "";
        }

        foreach ($data['data'] as $k => $v) {
            $data['data'][$k]['average'] = (51 - ceil($v['goukei'] / count($create_count)));
        }

        echo "<pre>";
        print_r($data);
        echo "</pre>";

    }


    public function kabukaselectapi($str)
    {

        $data = [];

        $create_count = [];

        /////////////////////////////////////////
        $ex_str = explode("|", $str);
        if ($ex_str[0] == "D" || $ex_str[0] == "G") {

            switch ($ex_str[0]) {
                case "D":
                    //日付指定
                    $sql = "select code, sum(point) goukei, count(code) cnt from t_stock where created_at like '" . $ex_str[1] . "%' group by code order by goukei desc, cnt desc;";
                    break;

                case "G":
                    //グレード指定
                    $sql = "select code, sum(point) goukei, count(code) cnt from t_stock where grade = '" . $ex_str[1] . "' group by code order by goukei desc, cnt desc;";
                    break;
            }

            $result = DB::select($sql);

            foreach ($result as $k => $v) {

                $data['data'][$v->code]['code'] = $v->code;

                switch ($ex_str[0]) {
                    case "D":
                        //日付指定
                        $result2 = DB::table('t_stock')
                            ->where('code', '=', $v->code)
                            ->where('created_at', 'like', $ex_str[1] . '%')
                            ->orderBy('id')->get();
                        break;

                    case "G":
                        //グレード指定
                        $result2 = DB::table('t_stock')
                            ->where('code', '=', $v->code)
                            ->where('grade', '=', $ex_str[1])
                            ->orderBy('id')->get();
                        break;
                }

                $ary = [];
                foreach ($result2 as $k2 => $v2) {
                    $data['data'][$v->code]['company'] = $v2->company;
                    $data['data'][$v->code]['industry'] = $v2->industry;
                    $data['data'][$v->code]['market'] = $v2->market;

                    $ary[$k2]['created_at'] = date("Y-m-d H", strtotime($v2->created_at));
                    $ary[$k2]['rank'] = $v2->rank;
                    $ary[$k2]['torihikichi'] = $v2->torihikichi;
                    $ary[$k2]['grade'] = $v2->grade;
                    $ary[$k2]['percentage'] = $v2->percentage;
                    $ary[$k2]['zenjitsuhi'] = $v2->zenjitsuhi;
                    $ary[$k2]['dekidaka'] = $v2->dekidaka;
                    $ary[$k2]['point'] = $v2->point;

                    $create_count[$v2->created_at] = "";
                }

                $data['data'][$v->code]['record'] = $ary;
            }
        } else {

            switch ($ex_str[0]) {
                case "C":
                    //コード指定
                    $result = DB::table('t_stock')->where('code', '=', $ex_str[1])->orderBy('id')->get();
                    break;

                case "CD":
                    //コード、日付指定
                    $ex_str_1 = explode("]", $ex_str[1]);
                    $result = DB::table('t_stock')
                        ->where('code', '=', $ex_str_1[0])
                        ->where('created_at', 'like', $ex_str_1[1] . '%')->orderBy('id')->get();
                    break;
            }

            foreach ($result as $k => $v) {

                $data['data'][$v->code]['code'] = $v->code;
                $data['data'][$v->code]['company'] = $v->company;
                $data['data'][$v->code]['industry'] = $v->industry;
                $data['data'][$v->code]['market'] = $v->market;

                $ary = [];
                $ary['created_at'] = date("Y-m-d H", strtotime($v->created_at));
                $ary['rank'] = $v->rank;
                $ary['torihikichi'] = $v->torihikichi;
                $ary['grade'] = $v->grade;
                $ary['percentage'] = $v->percentage;
                $ary['zenjitsuhi'] = $v->zenjitsuhi;
                $ary['dekidaka'] = $v->dekidaka;
                $ary['point'] = $v->point;

                $data['data'][$v->code]['record'][$k] = $ary;

                $create_count[$v->created_at] = "";
            }
        }
        /////////////////////////////////////////

        $sum = [];
        foreach ($data['data'] as $code => $value) {
            foreach ($value['record'] as $v) {
                $sum[$code][] = $v['point'];
            }
        }

        foreach ($sum as $code => $value) {
            $data['data'][$code]['sum'] = array_sum($value);
            $data['data'][$code]['count'] = count($value);

            $data['data'][$code]['average'] = (51 - ceil($data['data'][$code]['sum'] / count($create_count)));
        }

        //配列のキーをシーケンスに変更
        $data2 = $data;
        $data = [];

        $i = 0;
        foreach ($data2['data'] as $value) {
            $data['data'][$i] = $value;
            $i++;
        }

        echo "<pre>";
        print_r($data);
        echo "</pre>";

    }


    public function route()
    {
        return view('other.route');
    }

    public function routemap()
    {
        if (trim($_POST['origin']) == "" || trim($_POST['destination']) == "") {
            return redirect("/other/route");
        }

        $jitaku = "千葉県船橋市二子町492-25-101";
        $jikka = "東京都杉並区善福寺4-22-11";

        $origin = "";
        switch (trim($_POST['origin'])) {
            case "自宅":
                $origin = urlencode($jitaku);
                break;
            case "実家":
                $origin = urlencode($jikka);
                break;
            default:
                $origin = urlencode(trim($_POST['origin']));
                break;
        }

        $destination = "";
        switch (trim($_POST['destination'])) {
            case "自宅":
                $destination = urlencode($jitaku);
                break;
            case "実家":
                $destination = urlencode($jikka);
                break;
            default:
                $destination = urlencode(trim($_POST['destination']));
                break;
        }

        $url = "https://maps.googleapis.com/maps/api/directions/json?origin=" . $origin . "&destination=" . $destination . "&mode=walking&key=AIzaSyD9PkTM1Pur3YzmO-v4VzS0r8ZZ0jRJTIU";
        $data = json_decode(file_get_contents($url));

        $distance = $data->routes[0]->legs[0]->distance->text;
        $duration = $data->routes[0]->legs[0]->duration->text;
        $start_address = $data->routes[0]->legs[0]->start_address;
        $end_address = $data->routes[0]->legs[0]->end_address;

        $latlng = implode("/", [
            $data->routes[0]->legs[0]->start_location->lat,
            $data->routes[0]->legs[0]->start_location->lng,
            $data->routes[0]->legs[0]->end_location->lat,
            $data->routes[0]->legs[0]->end_location->lng
        ]);


        //station//start
        $station_start = [];
        $xml_url = "http://map.simpleapi.net/stationapi?x=" . $data->routes[0]->legs[0]->start_location->lng . "&y=" . $data->routes[0]->legs[0]->start_location->lat;
        $xml = file_get_contents($xml_url);
        $xmlObject = simplexml_load_string($xml);
        $xmlArray = json_decode(json_encode($xmlObject), TRUE);
        foreach ($xmlArray['station'] as $k => $v) {
            $station_start[$k]['name'] = $v['name'];
            $station_start[$k]['line'] = $v['line'];
            $station_start[$k]['distance'] = $v['distance'];
            $station_start[$k]['traveltime'] = $v['traveltime'];
        }

        //station//end
        $station_end = [];
        $xml_url = "http://map.simpleapi.net/stationapi?x=" . $data->routes[0]->legs[0]->end_location->lng . "&y=" . $data->routes[0]->legs[0]->end_location->lat;
        $xml = file_get_contents($xml_url);
        $xmlObject = simplexml_load_string($xml);
        $xmlArray = json_decode(json_encode($xmlObject), TRUE);
        foreach ($xmlArray['station'] as $k => $v) {
            $station_end[$k]['name'] = $v['name'];
            $station_end[$k]['line'] = $v['line'];
            $station_end[$k]['distance'] = $v['distance'];
            $station_end[$k]['traveltime'] = $v['traveltime'];
        }

        return view('other.routemap', [
            'distance' => $distance,
            'duration' => $duration,
            'start_address' => $start_address,
            'end_address' => $end_address,
            'latlng' => $latlng,
            'station_start' => $station_start,
            'station_end' => $station_end
        ]);


    }


    /**
     *
     */
    public function youtubedatalist()
    {

        $result = DB::table('t_youtube_data')
            ->orderBy('getdate', 'desc')
            ->get();

        return view('other.youtubedatalist')
            ->with('result', $result);
    }

    /**
     *
     */
    public function youtubedatainput()
    {
        return view('other.youtubedatainput');
    }

    /**
     *
     */
    public function youtubedatainputexecute()
    {
        if (trim($_POST['youtubedata']) != "") {
            $ex_youtubedata = explode("\n", trim($_POST['youtubedata']));
            foreach ($ex_youtubedata as $v) {
                if (trim($v) == "") {
                    continue;
                }

                $ex_v = explode("\t", trim($v));

                $insert = [];
                $insert['getdate'] = trim(strtr($ex_v[0], ['/' => '']));

                preg_match("/\"(.+)\"/", trim($ex_v[1]), $m);

                if (!isset($m[1])) {
                    continue;
                }

                $insert['title'] = trim($m[1]);

                preg_match("/youtu\.be\/(.+)/", trim($ex_v[2]), $m);

                if (!isset($m[1])) {
                    continue;
                }

                $insert['youtube_id'] = trim($m[1]);
                $insert['url'] = trim($ex_v[2]);

                $result = DB::table('t_youtube_data')
                    ->where('youtube_id', '=', $insert['youtube_id'])
                    ->first();

                if (!empty($result)) {
                    continue;
                }

                DB::table('t_youtube_data')->insert($insert);
            }
        }

        return redirect('/other/youtubedatalist');
    }


    /**
     *
     */
    public function walkdatalist()
    {

        $result = DB::table('t_walk_record')
            ->orderBy('id')
            ->get();

        return view('other.walkdatalist')
            ->with('result', $result);
    }

    /**
     *
     */
    public function walkdatainput()
    {
        return view('other.walkdatainput');
    }

    /**
     *
     */
    public function walkdatainputexecute()
    {
        if (trim($_POST['walkdata']) != "") {

            $ex_walkdata = explode("\n", trim($_POST['walkdata']));

            foreach ($ex_walkdata as $v) {
                if (trim($v) == "") {
                    continue;
                }

                $ex_v = explode("\t", trim($v));

                $insert = [];
                $insert['year'] = trim($ex_v[0]);
                $insert['month'] = trim($ex_v[1]);
                $insert['day'] = trim($ex_v[2]);
                $insert['step'] = trim($ex_v[3]);
                $insert['distance'] = trim($ex_v[4]);

                DB::table('t_walk_record')->insert($insert);
            }
        }

        return redirect('/other/walkdatalist');
    }

    /**
     *
     */
    public function youtubeShortcutDataInput()
    {
        return view('other.youtubeShortcutDataInput');
    }

    /**
     *
     */
    public function youtubeShortcutDataInputExecute()
    {
        if (trim($_POST['mode']) == "windows") {

            $url = trim($_POST['youtubeShortcutData']);

            $ex_url = explode("/", $url);
            $ex_url_url = explode("=", trim($ex_url[count($ex_url) - 1]));
            $youtube_id = trim($ex_url_url[1]);

            $result = DB::table('t_youtube_data')
                ->where('youtube_id', '=', $youtube_id)
                ->first();

            if (empty($result)) {

                $sUrl = "https://youtu.be/{$youtube_id}";

                $insert = [];
                $insert['getdate'] = date("Ymd");
                $insert['youtube_id'] = $youtube_id;
                $insert['title'] = "-";
                $insert['url'] = $sUrl;

                DB::table('t_youtube_data')->insert($insert);

            }

        } else if (trim($_POST['mode']) == "mac") {

            $title = $_FILES['youtubeShortcutData']['name'];

            $content = simplexml_load_file($_FILES['youtubeShortcutData']['tmp_name']);
            $url = $content->dict->string;

            $ex_url = explode("/", $url);
            $ex_url_url = explode("=", trim($ex_url[count($ex_url) - 1]));
            $youtube_id = trim($ex_url_url[1]);

            $result = DB::table('t_youtube_data')
                ->where('youtube_id', '=', $youtube_id)
                ->first();

            if (empty($result)) {

                $sUrl = "https://youtu.be/{$youtube_id}";

                $insert = [];
                $insert['getdate'] = date("Ymd");
                $insert['title'] = trim($title);
                $insert['youtube_id'] = $youtube_id;
                $insert['url'] = $sUrl;

                DB::table('t_youtube_data')->insert($insert);
            }
        }

        return redirect('/other/youtubedatalist');
    }


    /**
     *
     */
    public function seiyuPhotoList()
    {

        return view('other.seiyuPhotoList');
    }

    /**
     *
     */
    public function seiyuPhotoInput()
    {
        return view('other.seiyuPhotoInput');
    }

    /**
     *
     */
    public function seiyuPhotoInputExecute()
    {
        if (trim($_POST['seiyuPhotoData']) != "") {

            //------------------//
            $seiyuPhoto = [];
            $file = public_path() . "/mySetting/seiyuPhoto.data";
            $content = file_get_contents($file);
            $ex_content = explode("\n", $content);
            foreach ($ex_content as $v) {
                if (trim($v) == "") {
                    continue;
                }
                $seiyuPhoto[trim($v)] = "";
            }
            //------------------//

            $exData = explode("\n", trim($_POST['seiyuPhotoData']));
            $str = implode("", $exData);
            $exStr = explode("@", strtr($str, ['><' => '>@<']));

            foreach ($exStr as $v) {
                if (preg_match("/<img/", trim($v))) {

                    preg_match("/src=\"(.+)\?fit/", trim($v), $m);
                    preg_match("/alt=\"(.+)\">/", trim($v), $m2);

                    $a = trim($m[1]);
                    $b = trim($m2[1]);

                    $seiyuPhoto["https:{$a}|{$b}"] = "";

                }
            }

            $file = public_path() . "/mySetting/seiyuPhoto.data";
            file_put_contents($file, implode("\n", array_keys($seiyuPhoto)));

            return redirect('/other/seiyuPhotoList');
        }
    }


    /**
     *
     */
    public function amazonPhotoList()
    {

        return view('other.amazonPhotoList');
    }

    /**
     *
     */
    public function amazonPhotoInput()
    {
        return view('other.amazonPhotoInput');
    }

    /**
     *
     */
    public function amazonPhotoInputExecute()
    {
        if (trim($_POST['amazonPhotoData']) != "") {
            //------------------//
            $amazonPhoto = [];
            $file = public_path() . "/mySetting/amazonPhoto.data";
            $content = file_get_contents($file);
            $ex_content = explode("\n", $content);
            foreach ($ex_content as $v) {
                if (trim($v) == "") {
                    continue;
                }
                $amazonPhoto[trim($v)] = "";
            }
            //------------------//

            $exData = explode("\n", trim($_POST['amazonPhotoData']));

            $zStr = [];
            for ($i = 0; $i < count($exData); $i++) {
                $zStr[] = trim(strtr($exData[$i], ['<img' => 'img']));
            }
            $str = implode("", $zStr);

            $exStr = explode("@", strtr($str, ['><' => '>@<']));

            $pickup = [];
            for ($i = 0; $i < count($exStr); $i++) {
                if (
                    preg_match("/class=\"a-link-normal\"/", trim($exStr[$i])) &&
                    !preg_match("/こちら/", trim($exStr[$i])) &&
                    !preg_match("/購入明細書/", trim($exStr[$i]))
                ) {
                    $pickup[] = trim($exStr[$i]);
                }
            }

            for ($i = 0; $i < count($pickup); $i += 2) {
                $exLine0 = explode("\"", trim($pickup[$i]));
                $img = "";
                foreach ($exLine0 as $v) {
                    if (preg_match("/SY90/", $v)) {
                        $img = $v;
                        break;
                    }
                }

                $item = trim(strip_tags($pickup[$i + 1]));
                $item = strtr($item, ['"' => '\"']);
                $amazonPhoto["{$img}|{$item}"] = "";
            }

            $file = public_path() . "/mySetting/amazonPhoto.data";
            file_put_contents($file, implode("\n", array_keys($amazonPhoto)));

            return redirect('/other/amazonPhotoList');
        }
    }

    /**
     *
     */
    public function youtubeUrlList()
    {

        $result = DB::table('t_youtube_data')
            ->orderBy('getdate', 'desc')
            ->orderBy('id', 'desc')
            ->get();

        return view('other.youtubeUrlList')
            ->with('result', $result);
    }

    /**
     *
     */
    public function youtubeUrlInput()
    {
        return view('other.youtubeUrlInput');
    }

    /**
     *
     */
    public function youtubeUrlInputExecute()
    {

        if (trim($_POST['youtubeUrlData']) != "") {

            $exData = explode("\n", trim($_POST['youtubeUrlData']));

            foreach ($exData as $v){

                if (trim($v) == "") {
                    continue;
                }

                $ex_v = explode("?", trim($v));
                $ex_v_1 = explode("&", trim($ex_v[1]));

                $youtube_id = "";
                foreach ($ex_v_1 as $v2){
                    if (preg_match("/v=(.+)/", trim($v2), $m)){
                        $youtube_id = $m[1];
                        break;
                    }
                }

                if ($youtube_id == ""){
                    continue;
                }

                $result = DB::table('t_youtube_data')
                    ->where('youtube_id', '=', $youtube_id)
                    ->first();

                if (isset($result->id)) {
                    continue;
                }

                $url = "https://www.googleapis.com/youtube/v3/videos?id={$youtube_id}&part=snippet,contentDetails&key=AIzaSyD9PkTM1Pur3YzmO-v4VzS0r8ZZ0jRJTIU";

                $content = file_get_contents($url);
                $jsonStr = json_decode($content);

                $v3_pubDate = substr($jsonStr->items[0]->snippet->publishedAt, 0, 10);
                $v3_channelId = $jsonStr->items[0]->snippet->channelId;
                $v3_channelTitle = $jsonStr->items[0]->snippet->channelTitle;
                $v3_playtime = $jsonStr->items[0]->contentDetails->duration;
                $v3_title = $jsonStr->items[0]->snippet->title;

                $insert = [];
                $insert['youtube_id'] = $youtube_id;
                $insert['title'] = $v3_title;
                $insert['url'] = "https://youtu.be/{$youtube_id}";

                $insert['del'] = 0;
                $insert['special'] = 0;

                $insert['getdate'] = date("Ymd");

                $insert['pubdate'] = $v3_pubDate;
                $insert['channel_id'] = $v3_channelId;
                $insert['channel_title'] = $v3_channelTitle;
                $insert['playtime'] = $v3_playtime;

                DB::table('t_youtube_data')->insert($insert);

            }
        }

        return redirect('/other/youtubeUrlList');

    }

}
