<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use DB;
use Exception;
use Illuminate\Http\Request;

class ApiControllerSecond extends Controller
{

    /*
     *
     */
    public function getKigoSeasonRandomList(Request $request): array
    {

        if ($request->season != '-') {
            $sql = " update t_haiku_season set cnt=cnt+1 where season_en = '{$request->season}'; ";
            DB::statement($sql);
        }

        $query_common = " select id from t_haiku_kigo";
        $query_common .= " where season = '{$request->season}'";

        $query1 = $query_common;
        $query1 .= " order by id limit 1; ";
        $result1 = DB::select($query1);

        $query2 = $query_common;
        $query2 .= " order by id desc limit 1; ";
        $result2 = DB::select($query2);

        $start = $result1[0]->id;
        $end = $result2[0]->id;

        ////////////////////////////////////////////////
        $list = [];

        $getIds = [];
        while (true) {
            $dice1 = mt_rand($start, $end);
            $getIds[$dice1] = "";
            if (count($getIds) > 20) {
                break;
            }
        }


        $result5 = DB::table('t_haiku_season')
            ->where('season_en', $request->season)
            ->first();


        foreach ($getIds as $k => $v) {

            $result = DB::table('t_haiku_kigo')
                ->where('id', '=', $k)->first();

            $list[] = [
                "title" => $result->title,
                "yomi" => $result->yomi,
                "detail" => $result->detail,
                "length" => $result->length,
                "category" => $result->category,
                "cnt" => ($result->cnt + 1),
                "seasonCnt" => $result5->cnt,
            ];

            DB::table('t_haiku_kigo')
                ->where('id', $k)
                ->update(['cnt' => ($result->cnt + 1)]);
        }


        ////////////////////////////////////////////////

        ////////////////////////////////////////////////
        $len = [];

        $inIds = [];
        for ($i = $start; $i <= $end; $i++) {
            $inIds[] = $i;
        }

        $result4 = DB::table('t_haiku_kigo')
            ->whereIn('id', $inIds)
            ->get(['length']);

        foreach ($result4 as $v4) {
            $len[] = $v4->length;
        }
        ////////////////////////////////////////////////

        return [
            'min' => min($len),
            'max' => max($len),
            'list' => $list
        ];

    }

    /*
     *
     */
    public function getKigoSearchedList(Request $request): array
    {

        if ($request->season != '-') {
            $sql = " update t_haiku_season set cnt=cnt+1 where season_en = '{$request->season}'; ";
            DB::statement($sql);
        }

        $query = " select * from t_haiku_kigo where season = '{$request->season}'";

        if (isset($request->title) && trim($request->title) != "") {
            $query .= " and title like '{$request->title}%'";
        }

        if (isset($request->yomi_head) && trim($request->yomi_head) != "") {
            $query .= " and yomi like '{$request->yomi_head}%'";
        }

        if (isset($request->length) && $request->length > 0) {
            $query .= " and length = {$request->length}";
        }

        if (isset($request->category) && trim($request->category) != "") {
            $query .= " and category = '{$request->category}'";
        }

        $query .= " order by id";

        $result = DB::select($query);

        ////////////////////////////////////////////////
        $len = [];


        $result5 = DB::table('t_haiku_season')
            ->where('season_en', $request->season)
            ->first();


        $list = [];
        foreach ($result as $v) {
            $list[] = [
                "title" => $v->title,
                "yomi" => $v->yomi,
                "detail" => $v->detail,
                "length" => $v->length,
                "category" => $v->category,
                "cnt" => ($v->cnt + 1),
                "seasonCnt" => $result5->cnt,
            ];

            $len[] = $v->length;

            DB::table('t_haiku_kigo')
                ->where('id', $v->id)
                ->update(['cnt' => ($v->cnt + 1)]);

        }
        ////////////////////////////////////////////////

        return [
            'min' => min($len),
            'max' => max($len),
            'list' => $list
        ];

    }

    /*
     *
     */
    public function getKigoSeasonList(Request $request): array
    {
        $result = DB::table('t_haiku_season')->get();

        $ary = [];
        foreach ($result as $v) {
            $ary[] = [
                'season_en' => $v->season_en,
                'season_jp' => $v->season_jp,
                'cnt' => $v->cnt,
            ];
        }

        return $ary;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function getAllTemple()
    {
        $response = [];

        $photoUrl = $this->getPhotoUrl();

        $ary = [];
        foreach ($photoUrl as $v) {
            foreach ($v as $date => $photo) {
                list($year, $month, $day) = explode("-", $date);
                $result = DB::table('t_temple')
                    ->where('year', '=', $year)
                    ->where('month', '=', $month)
                    ->where('day', '=', $day)
                    ->first();

                $rand = mt_rand(0, count($photo) - 1);
                $thumbnail = $photo[$rand];

                /////////////////////////////////////////////////
                $_lat = '';
                $_lng = '';

                if (trim($result->lat) == "" || trim($result->lng) == "") {

                    $url = "https://maps.googleapis.com/maps/api/geocode/json?address=" . $result->address . "&components=country:JP&key=AIzaSyD9PkTM1Pur3YzmO-v4VzS0r8ZZ0jRJTIU";
                    $content = file_get_contents($url);
                    $jsonStr = json_decode($content);

                    if (isset($jsonStr->results[0]->geometry->location->lat) and trim($jsonStr->results[0]->geometry->location->lat) != "") {
                        $_lat = $jsonStr->results[0]->geometry->location->lat;
                    }

                    if (isset($jsonStr->results[0]->geometry->location->lng) and trim($jsonStr->results[0]->geometry->location->lng) != "") {
                        $_lng = $jsonStr->results[0]->geometry->location->lng;
                    }

                    //------------------------
                    $update = [];
                    if (trim($_lat) != "") {
                        $update['lat'] = $_lat;
                    }
                    if (trim($_lng) != "") {
                        $update['lng'] = $_lng;
                    }
                    DB::table('t_temple')->where('id', '=', $result->id)->update($update);
                    //------------------------

                } else {
                    $_lat = trim($result->lat);
                    $_lng = trim($result->lng);
                }
                /////////////////////////////////////////////////

                //+---------+--------------+------+-----+---------+----------------+
                $result2 = DB::table('t_temple_latlng')
                    ->where('temple', '=', $result->temple)
                    ->first();
                if (empty($result2)) {
                    $insert = [
                        'temple' => $result->temple,
                        'address' => $result->address,
                        'lat' => $_lat,
                        'lng' => $_lng
                    ];

                    DB::table('t_temple_latlng')->insert($insert);
                }

                if (trim($result->memo) != "") {
                    $ex_memo = explode("、", $result->memo);
                    foreach ($ex_memo as $v2) {
                        $result3 = DB::table('t_temple_latlng')
                            ->where('temple', '=', $v2)
                            ->first();
                        if (empty($result3)) {
                            $insert = [
                                'temple' => $v2
                            ];

                            DB::table('t_temple_latlng')->insert($insert);
                        }
                    }
                }

                $ary['list'][] = [
                    'date' => $date,
                    'temple' => $result->temple,
                    'address' => $result->address,
                    'station' => $result->station,

                    'memo' => (trim($result->memo) != "") ? $result->memo : "",
                    'gohonzon' => (trim($result->gohonzon) != "") ? $result->gohonzon : "",

                    'start_point' => trim($result->start_point),
                    'end_point' => trim($result->end_point),

                    'thumbnail' => $thumbnail,
                    'lat' => $_lat,
                    'lng' => $_lng,
                    'photo' => $photo
                ];
            }
        }

        return $ary;

//        $response = $ary;
//
//        return response()->json(['data' => $response]);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function getDateTemple(Request $request)
    {
        $response = [];

        $ary = [];
        list($year, $month, $day) = explode("-", $request->date);
        $result = DB::table('t_temple')
            ->where('year', '=', $year)
            ->where('month', '=', $month)
            ->where('day', '=', $day)
            ->first();

        $photo = $this->getPhotoUrl($request->date);
        $rand = mt_rand(0, count($photo) - 1);
        $thumbnail = $photo[$rand];

        /////////////////////////////////////////////////
        $_lat = '';
        $_lng = '';

        if (trim($result->lat) == "" || trim($result->lng) == "") {

            $url = "https://maps.googleapis.com/maps/api/geocode/json?address=" . $result->address . "&components=country:JP&key=AIzaSyD9PkTM1Pur3YzmO-v4VzS0r8ZZ0jRJTIU";
            $content = file_get_contents($url);
            $jsonStr = json_decode($content);
            if (isset($jsonStr->results[0]->geometry->location->lat) and trim($jsonStr->results[0]->geometry->location->lat) != "") {
                $_lat = $jsonStr->results[0]->geometry->location->lat;
            }
            if (isset($jsonStr->results[0]->geometry->location->lng) and trim($jsonStr->results[0]->geometry->location->lng) != "") {
                $_lng = $jsonStr->results[0]->geometry->location->lng;
            }

            //------------------------
            $update = [];
            if (trim($_lat) != "") {
                $update['lat'] = $_lat;
            }
            if (trim($_lng) != "") {
                $update['lng'] = $_lng;
            }
            DB::table('t_temple')->where('id', '=', $result->id)->update($update);
            //------------------------

        } else {
            $_lat = trim($result->lat);
            $_lng = trim($result->lng);
        }

        /////////////////////////////////////////////////

        $ary = [
            'date' => $request->date,
            'temple' => $result->temple,
            'address' => $result->address,
            'station' => $result->station,

            'memo' => (trim($result->memo) != "") ? $result->memo : "",
            'gohonzon' => (trim($result->gohonzon) != "") ? $result->gohonzon : "",

            'thumbnail' => $thumbnail,
            'lat' => $_lat,
            'lng' => $_lng,
            'photo' => $photo
        ];

        return $ary;

//        $response = $ary;
//
//        return response()->json(['data' => $response]);
    }

    /**
     *
     */
    public function getTempleLatLng()
    {
        $response = [];

        $result = DB::table('t_temple_latlng')->get();

        $ary = [];
        foreach ($result as $v) {
            $ary['list'][] = [
                'temple' => $v->temple,
                'address' => $v->address,
                'lat' => $v->lat,
                'lng' => $v->lng
            ];
        }

        return $ary;

//        $response = $ary;
//
//        return response()->json(['data' => $response]);
    }

    /**
     * @param null $pickdate
     * @return array|mixed
     */
    private function getPhotoUrl($pickdate = null)
    {

        //-----------//
        $skiplist = [];
        $skipfile = "/var/www/html/Temple/public/mySetting/skiplist";
        $content = file_get_contents($skipfile);
        foreach (explode("\n", $content) as $v) {
            if (trim($v) == "") {
                continue;
            }
            $skiplist[] = trim($v);
        }
        //-----------//

        //-----------//
        $skiplist2 = [];
        $skipfile = "/var/www/html/Temple/public/mySetting/skiplist2";
        $content = file_get_contents($skipfile);
        foreach (explode("\n", $content) as $v) {
            if (trim($v) == "") {
                continue;
            }
            $skiplist2[] = trim($v);
        }
        //-----------//

        $_dir = "/var/www/html/BrainLog/public/UPPHOTO";
        $filelist = $this->scandir_r($_dir);

        sort($filelist);

        foreach ($filelist as $v) {

            $pos = strpos($v, 'UPPHOTO');
            $str = substr(trim($v), $pos);

            list(, $year, $date, $photo) = explode("/", $str);

            if (in_array($date, $skiplist)) {
                continue;
            }
            if (in_array($photo, $skiplist2)) {
                continue;
            }

            $photolist[$year][$date][] = strtr($v, ['/var/www/html' => 'http://toyohide.work']);
        }

        if (is_null($pickdate)) {
            return $photolist;
        } else {
            list($year, $month, $day) = explode("-", $pickdate);
            return $photolist[$year][$pickdate];
        }
    }

    /**
     * @param $dir
     * @return array
     */
    private function scandir_r($dir)
    {
        $list = scandir($dir);

        $results = array();

        foreach ($list as $record) {
            if (in_array($record, array(".", ".."))) {
                continue;
            }

            $path = rtrim($dir, "/") . "/" . $record;
            if (is_file($path)) {
                $results[] = $path;
            } else {
                if (is_dir($path)) {
                    $results = array_merge($results, $this->scandir_r($path));
                }
            }
        }

        return $results;
    }


    /*
     *
     */
    public function getTempleName(Request $request)
    {

        $response = [];

        $sql = " select * from t_temple where temple like '%{$request->name}%' or memo like '%{$request->name}%' order by year,month,day; ";
        $result = DB::select($sql);

        $ary = [];
        foreach ($result as $v) {
            $str = [];
            $str[] = $v->temple;
            if (is_null($v->memo) || trim($v->memo) == "") {
            } else {
                $str[] = $v->memo;
            }
            $ex_str = explode("、", implode("、", $str));

            $data = [];
            foreach ($ex_str as $v2) {
                $result2 = DB::table('t_temple_latlng')
                    ->where('temple', $v2)
                    ->first();

                $data[] = [
                    "temple" => $result2->temple,
                    "address" => $result2->address,
                    "lat" => $result2->lat,
                    "lng" => $result2->lng
                ];
            }

            $ary[] = [
                "year" => $v->year,
                "month" => $v->month,
                "day" => $v->day,
                "data" => $data
            ];
        }

        $response = $ary;

        return response()->json(['list' => $response]);

    }


    /**
     *
     */
    public function getCategoryRate(Request $request)
    {
        $response = [];

        //----------------------------------------//
        $ary2 = [];
        $result2 = DB::table('t_tarot')
            ->orderBy('id')
            ->get();
        foreach ($result2 as $v2) {
            $ary2[$v2->id] = 0;
        }
        //---
        $ary3 = [];
        $result3 = DB::table('t_tarotdraw')
            ->get();
        foreach ($result3 as $v3) {
            $ary3[$v3->tarot_id][] = "";
        }
        $allDraw = count($result3);
        //---
        $ary4 = [];
        foreach ($ary2 as $id => $v4) {
            $cnt = (isset($ary3[$id])) ? count($ary3[$id]) : 0;
            $ary4[$id] = "{$cnt} / {$allDraw}";
        }
        //----------------------------------------//

        $change = [];
        $change["Cups"] = "";
        $change["Pentacles"] = "";
        $change["Swords"] = "";
        $change["Wands"] = "";
        $change["of Cups"] = "";
        $change["of Pentacles"] = "";
        $change["of Swords"] = "";
        $change["of Wands"] = "";

        $result = DB::table("t_tarot")
            ->where("image", "like", $request->category . "%")
            ->orderBy('image')
            ->get();

        $ary = [];
        foreach ($result as $v) {
//            $ary[] = "{$v->id}:" . trim(strtr($v->name, $change)) . ":" . $ary4[$v->id];


            $name = trim(strtr($v->name, $change));
            $ary[] = [
                "id" => $v->id,
                "name" => "{$request->category} {$name}",
                "rate" => $ary4[$v->id],
            ];


        }

        $response = $ary;

        return response()->json(['data' => $response]);

    }

    /*
     *
     */
    public function getGooUranai(Request $request)
    {
        $file = public_path() . "/mySetting/uranai2.data";
        $content = file_get_contents($file);

        $ary = [];
        if (!empty($content)) {
            $ex_content = explode("\n", $content);

            foreach ($ex_content as $v) {
                $ex_v = explode("|", trim($v));

                $ex_all = explode(";", $ex_v[2]);
                $ex_love = explode(";", $ex_v[3]);
                $ex_money = explode(";", $ex_v[4]);
                $ex_work = explode(";", $ex_v[5]);
                $ex_health = explode(";", $ex_v[6]);

                $ary[] = [
                    "date" => $ex_v[0],
                    "rank" => $ex_v[1],
                    "uranai_all" => $ex_all[0],
                    "point_all" => $ex_all[1],
                    "uranai_love" => $ex_love[0],
                    "point_love" => $ex_love[1],
                    "uranai_money" => $ex_money[0],
                    "point_money" => $ex_money[1],
                    "uranai_work" => $ex_work[0],
                    "point_work" => $ex_work[1],
                    "uranai_health" => $ex_health[0],
                    "point_health" => $ex_health[1],
                ];
            }
        }

        return response()->json(['data' => $ary]);
    }


    /**
     * @param Request $request
     * @return mixed
     */
    public function getDutyData(Request $request)
    {
        $response = [];

        $dutyItems = ['所得税', '住民税', '年金', '国民年金基金', '国民健康保険'];

        $ary = [];
        foreach ($dutyItems as $duty) {
            $spend = DB::table('t_dailyspend')->where('koumoku', '=', $duty)
                ->where('year', '>=', '2020')
                ->orderBy('year')->orderBy('month')->orderBy('day')
                ->get();

            foreach ($spend as $v) {
                $ary[] = [
                    "date" => "{$v->year}-{$v->month}-{$v->day}",
                    "duty" => $duty,
                    "price" => $v->price,
                ];
            }

            $credit = DB::table('t_credit')->where('item', '=', $duty)
                ->where('year', '>=', '2020')
                ->orderBy('year')->orderBy('month')->orderBy('day')
                ->get();

            foreach ($credit as $v) {
                $ary[] = [
                    "date" => "{$v->year}-{$v->month}-{$v->day}",
                    "duty" => $duty,
                    "price" => $v->price,
                ];
            }
        }

        $response = $ary;

        return response()->json(['data' => $response]);
    }


    /**
     * @param Request $request
     * @return mixed
     */
    public function benefit(Request $request)
    {
        $response = [];

        $result = DB::table('t_salary')
            ->orderBy('year')
            ->orderBy('month')
            ->orderBy('day')
            ->get();

        $ary = [];
        foreach ($result as $v) {
//            $ary[] = "{$v->year}-{$v->month}-{$v->day}|{$v->year}-{$v->month}|{$v->salary}|{$v->company}";

            $ary[] = [
                "date" => "{$v->year}-{$v->month}-{$v->day}",
                "ym" => "{$v->year}-{$v->month}",
                "salary" => "{$v->salary}",
                "company" => "{$v->company}",
            ];
        }

        $response = $ary;

        return response()->json(['data' => $response]);
    }


    /**
     * @param Request $request
     * @return mixed
     */
    public function getMoneyAll(Request $request)
    {
        $response = [];

        $result = DB::table('t_money')
            ->orderBy('year')
            ->orderBy('month')
            ->orderBy('day')
            ->get();

        $ary = [];
        foreach ($result as $v) {

            if ($v->year < 2020) {
                continue;
            }

            $ary[] = [
                "date" => "{$v->year}-{$v->month}-{$v->day}",
                "ym" => "{$v->year}-{$v->month}",
                "yen_10000" => $v->yen_10000,
                "yen_5000" => $v->yen_5000,
                "yen_2000" => $v->yen_2000,
                "yen_1000" => $v->yen_1000,
                "yen_500" => $v->yen_500,
                "yen_100" => $v->yen_100,
                "yen_50" => $v->yen_50,
                "yen_10" => $v->yen_10,
                "yen_5" => $v->yen_5,
                "yen_1" => $v->yen_1,
                "bank_a" => $v->bank_a,
                "bank_b" => $v->bank_b,
                "bank_c" => $v->bank_c,
                "bank_d" => $v->bank_d,
                "bank_e" => $v->bank_e,
                "pay_a" => $v->pay_a,
                "pay_b" => $v->pay_b,
                "pay_c" => $v->pay_c,
                "pay_d" => $v->pay_d,
                "pay_e" => $v->pay_e
            ];
        }

        $response = $ary;

        return response()->json(['data' => $response]);
    }


    /**
     * @return mixed
     */
    public function balanceSheetRecord()
    {

        $response = [];

        $result = DB::table('t_balancesheet')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        $midashi = [
            'assets_total_deposit_start',
            'assets_total_deposit_debit',
            'assets_total_deposit_credit',
            'assets_total_deposit_end',

            'assets_total_receivable_start',
            'assets_total_receivable_debit',
            'assets_total_receivable_credit',
            'assets_total_receivable_end',

            'assets_total_fixed_start',
            'assets_total_fixed_debit',
            'assets_total_fixed_credit',
            'assets_total_fixed_end',

            'assets_total_lending_start',
            'assets_total_lending_debit',
            'assets_total_lending_credit',
            'assets_total_lending_end',


            'assets_consumption_tax_start',
            'assets_consumption_tax_debit',
            'assets_consumption_tax_credit',
            'assets_consumption_tax_end',


            'capital_total_liabilities_start',
            'capital_total_liabilities_debit',
            'capital_total_liabilities_credit',
            'capital_total_liabilities_end',

            'capital_total_borrow_start',
            'capital_total_borrow_debit',
            'capital_total_borrow_credit',
            'capital_total_borrow_end',

            'capital_total_principal_start',
            'capital_total_principal_debit',
            'capital_total_principal_credit',
            'capital_total_principal_end',

            'capital_total_income_start',
            'capital_total_income_debit',
            'capital_total_income_credit',
            'capital_total_income_end'
        ];

        $ary3 = [];
        foreach ($result as $v) {

            $ary = [];
            $ary2 = [];

            $assets_total = 0;
            $capital_total = 0;

            foreach ($midashi as $v2) {


                if (preg_match("/^assets_consumption_tax_/", $v2)) {
                    $ary[$v2] = (is_null($v->$v2)) ? 0 : $v->$v2;
                    if (preg_match("/_end$/", $v2)) {
                        if (!is_null($v->$v2)) {
                            $assets_total += $v->$v2;
                        }
                    }
                }


                if (preg_match("/^assets_total_/", $v2)) {
//                    $ary[] = $v2 . ":" . $v->$v2;
                    $ary[$v2] = $v->$v2;
                    if (preg_match("/_end$/", $v2)) {
                        $assets_total += $v->$v2;
                    }
                }

                if (preg_match("/^capital_total_/", $v2)) {
//                    $ary2[] = $v2 . ":" . $v->$v2;
                    $ary2[$v2] = $v->$v2;
                    if (preg_match("/_end$/", $v2)) {
                        $capital_total += $v->$v2;
                    }
                }
            }

            $ary3[] = [
                "ym" => "$v->year-$v->month",
                "assets_total" => $assets_total,
                "capital_total" => $capital_total,
                "assets" => $ary,
                "capital" => $ary2,
            ];

//            $ar = [];
//            $ar[] = "ym:$v->year-$v->month";
//            $ar[] = "assets_total:$assets_total";
//            $ar[] = "capital_total:$capital_total";
//            $ar[] = implode("|", $ary);
//            $ar[] = implode("|", $ary2);
//            $ary3[] = implode("|", $ar);
        }

        $response = $ary3;

        return response()->json(['data' => $response]);
    }


    /**
     * @param Request $request
     * @return mixed
     */
    public function getFund(Request $request)
    {
        $response = [];

        $youbi = ['日', '月', '火', '水', '木', '金', '土'];

        $result2 = DB::table('t_fund')
            ->orderBy('fundname')
            ->orderBy('year')
            ->orderBy('month')
            ->orderBy('day')
            ->get();

        $ary = [];
        $ary2 = [];

        foreach ($result2 as $v2) {
            $date = "$v2->year-$v2->month-$v2->day";
            $ary2[] = $date;

            $_youbi = $youbi[date("w", strtotime($date))];
            $flag = (preg_match("/\+/", trim($v2->compare_front))) ? 1 : 0;

            $ary9 = [];
            $ary9[] = $date;
            $ary9[] = $v2->base_price;
            $ary9[] = $v2->compare_front;
            $ary9[] = $v2->yearly_return;
            $ary9[] = $flag;

            $ary[$v2->fundname][$date] = implode("|", $ary9);
        }

        $date_min = $ary2[0];
        $date_max = $ary2[count($ary2) - 1];

        $ary3 = [];
        foreach ($ary as $name => $v) {
            for ($i = strtotime($date_min); $i <= strtotime($date_max); $i += 86400) {
                if (isset($v[date("Y-m-d", $i)])) {
                    $ary3[$name][date("Y-m-d", $i)] = $v[date("Y-m-d", $i)];
                } else {
                    $ary3[$name][date("Y-m-d", $i)] = date("Y-m-d", $i) . "|-|-|-|-";
                }
            }
        }

        $ary4 = [];
        $keep_name = "";
        foreach ($ary3 as $name => $v) {

            if ($keep_name != $name) {
                $check = false;
            }

            foreach ($v as $date => $v2) {
                if ($check == false) {
                    if (!preg_match("/\|-\|-/", $v2)) {
                        $check = true;
                    }
                }

                if ($check == true) {
                    $ary4[$name][$date] = $v2;
                }
            }

            $keep_name = $name;
        }

        $ary5 = [];
        foreach ($ary4 as $name => $v) {
            $ary5[] = $name . ":" . implode("/", $v);
        }


        ////////////////////////////// add

        $ary6 = [];
        foreach ($ary5 as $v5) {
            $ex_v5 = explode(":", $v5);
            $ex_v5_1 = explode("/", $ex_v5[1]);
            $ary7 = [];
            foreach ($ex_v5_1 as $v6) {
                list($v6_date, $v6_basePrice, $v6_compareFront, $v6_yearlyReturn, $v6_flag) = explode("|", $v6);
                $ary7[] = [
                    "date" => $v6_date,
                    "base_price" => $v6_basePrice,
                    "compare_front" => $v6_compareFront,
                    "yearly_return" => $v6_yearlyReturn,
                    "flag" => $v6_flag,
                ];
            }

            $ary6[] = [
                "name" => $ex_v5[0],
                "record" => $ary7,
            ];
        }


        ////////////////////////////// add


        $response = $ary6;


        return response()->json(['data' => $response]);
    }


    /**
     * @return mixed
     */
    public function gettrainrecord()
    {
        $response = [];

        //----------------------//
        $koutsuuhi = [];

        $result = DB::table('t_dailyspend')
            ->where('koumoku', '=', '交通費')
            ->orderBy('year')
            ->orderBy('month')
            ->orderBy('day')
            ->get();

        foreach ($result as $v) {
            $koutsuuhi[$v->year . "-" . $v->month . "-" . $v->day] = $v->price;
        }
        //----------------------//

        ///////////////////////////////////////////////
        $_tables = [];

        $sql = "SELECT table_name FROM information_schema.tables WHERE table_schema = database();";
        $result = DB::select($sql);

        foreach ($result as $v) {
            if (preg_match("/t_article/", $v->table_name)) {
                $_tables[] = $v->table_name;
            }
        }
        ///////////////////////////////////////////////

        foreach ($_tables as $table) {

            $traindata = DB::table($table)
                ->where('tag', '=', '電車乗車')
                ->orderBy('year')
                ->orderBy('month')
                ->orderBy('day')
                ->get();

            foreach ($traindata as $v) {

                if ($v->year <= 2019) {
                    continue;
                }

                $ymd = $v->year . "-" . $v->month . "-" . $v->day;

                $cnt = 0;
                if (isset($response[$ymd])) {
                    $cnt = count($response[$ymd]);
                }

                $response[$ymd][$cnt] = $v->article;
            }
        }

        $response2 = [];
        for ($i = strtotime("2020-01-01"); $i <= strtotime(date("Y-m-d")); $i += 86400) {
            if (isset($response[date("Y-m-d", $i)])) {
                $str = implode("\n", $response[date("Y-m-d", $i)]);
                $str .= "|";
                $str .= (isset($koutsuuhi[date("Y-m-d", $i)])) ? $koutsuuhi[date("Y-m-d", $i)] : "";

                //----------------------//
                $ary3 = [];
                foreach ($response[date("Y-m-d", $i)] as $v3) {
                    $ex_v3 = explode("\n", trim($v3));
                    foreach ($ex_v3 as $vv3) {
                        $ex_vv3 = explode("-", trim($vv3));
                        foreach ($ex_vv3 as $vvv3) {
                            $ary3[$vvv3][] = "";
                        }
                    }
                }

                $oufuku = 1;
                foreach ($ary3 as $vvvv3) {
                    if (count($vvvv3) == 1) {
                        $oufuku = 0;
                    }
                }

                $str .= "|" . $oufuku;
                //----------------------//


//                $response2[date("Y-m-d", $i)] = $str;
                list($station, $price, $ofk) = explode("|", $str);
                $response2[] = [
                    "date" => date("Y-m-d", $i),
                    "station" => $station,
                    "price" => $price,
                    "oufuku" => $ofk,
                ];
            }
        }

        return response()->json(['data' => $response2]);
    }


    /**
     * @return mixed
     */
    public function getWells()
    {

        $response = [];

        $result = DB::table('t_credit')
            ->where('price', '=', 55880)
            ->orderBy('ymd')
            ->get();

        $ary = [];
        $lastPrice = 0;
        foreach ($result as $k => $v) {
            $sumPrice = ($lastPrice + $v->price);
            $ary[$v->year][] = sprintf("%03d", ($k + 1)) . "|$v->month-$v->day|$v->price|$sumPrice";
            $lastPrice = $sumPrice;
        }

        $ary2 = [];
        foreach ($ary as $year => $v) {
            $ary3 = [];
            for ($i = 0; $i < 12; $i++) {
                $ary3[$i] = "|||";
            }

            $j = (12 - count($v));
            if ($year == date("Y")) {
                $j = 0;
            }

            foreach ($v as $v2) {
                $ary3[$j] = $v2;
                $j++;
            }

            $ary2[$year] = $ary3;
        }


//        $ary4 = [];
//        foreach ($ary2 as $year => $v) {
//            $ary4[] = "$year:" . implode("/", $v);
//        }
//
//        $response = $ary4;


        $ary4 = [];
        foreach ($ary2 as $year => $v) {
            foreach ($v as $v2) {
                if ($v2 == "|||") {
                    continue;
                }
                list($num, $ym, $price, $total) = explode("|", $v2);
                $ary4[] = [
                    "num" => $num,
                    "date" => "{$year}-{$ym}",
                    "price" => $price,
                    "total" => $total,
                ];
            }
        }

        $response = $ary4;


        return response()->json(['data' => $response]);
    }


    /**
     * @return mixed
     */
    public function homeFix()
    {

        $response = [];

        ///////////////////////////////////////////////
        $_tables = [];

        $sql = "SELECT table_name FROM information_schema.tables WHERE table_schema = database();";
        $result = DB::select($sql);

        foreach ($result as $v) {
            if (preg_match("/t_article/", $v->table_name)) {
                $_tables[] = $v->table_name;
            }
        }
        ///////////////////////////////////////////////

        $gas = [];
        $denki = [];
        $suidou = [];
        $mobile = [];
        $wifi = [];

        $_ym2 = [];
        foreach ($_tables as $table) {
            $result = DB::table($table)
                ->where('article', 'like', '%内訳%')
                ->orderBy('year')
                ->orderBy('month')
                ->orderBy('day')
                ->get();

            foreach ($result as $v) {

                $ym = "{$v->year}-{$v->month}";
                if ($v->year >= 2020) {
                    $_ym2[$ym] = "";
                }

                $ex_v = explode("\n", trim($v->article));
                foreach ($ex_v as $v2) {
                    if (preg_match("/\((.+)\) 水道光熱費(.+)円.+ガス代/", trim($v2), $m)) {
                        $day = trim($m[1]);
                        $price = trim(strtr($m[2], [',' => '']));
                        $gas[$ym][] = number_format($price) . " ({$day})";
                    }

                    if (preg_match("/\((.+)\) 水道光熱費(.+)円.+電気代/", trim($v2), $m)) {
                        $day = trim($m[1]);
                        $price = trim(strtr($m[2], [',' => '']));
                        $denki[$ym][] = number_format($price) . " ({$day})";
                    }

                    if (preg_match("/\((.+)\) 水道光熱費(.+)円.+水道代/", trim($v2), $m)) {
                        $day = trim($m[1]);
                        $price = trim(strtr($m[2], [',' => '']));
                        $suidou[$ym][] = number_format($price) . " ({$day})";
                    }

                    if (preg_match("/20.+楽天/", trim($v2))) {
                        $ex_v2 = explode("\t", trim($v2));
                        $ex_date = explode("/", trim($ex_v2[0]));
                        $yearmonth = "{$ex_date[0]}-{$ex_date[1]}";
                        $_day = trim($ex_date[2]);
                        $price = trim(strtr($ex_v2[4], ['¥' => '', ',' => '']));
                        if (preg_match("/ブロードバンド/", trim($v2))) {
                            $wifi[$yearmonth][] = number_format($price) . " ({$_day})";
                        } else {
                            if (
                                preg_match("/市場/", trim($v2)) ||
                                preg_match("/証券/", trim($v2))
                            ) {
                                //
                            } else {
                                $mobile[$yearmonth][] = number_format($price) . " ({$_day})";
                            }
                        }
                    }


                    if (preg_match("/ドコモご利用料金/", trim($v2))) {
                        $ex__v2 = explode(" ", trim($v2));
                        preg_match("/(.+)ドコモご利用料金/", trim($ex__v2[0]), $__m);
                        $__ymd = trim($__m[1]);
                        $ex__ymd = explode("/", trim($__ymd));
                        $__year = trim($ex__ymd[0]);
                        $__month = trim($ex__ymd[1]);
                        $__day = trim($ex__ymd[2]);
                        $__price = trim(strtr($ex__v2[1], [',' => '', 'リボへ変更する' => '']));
                        $wifi["{$__year}-{$__month}"][] = number_format($__price) . " ({$__day})";
                    }


                }
            }
        }

        $yachin = [];
        $result2 = DB::table("t_credit")
            ->where("price", "=", 67000)
            ->orderBy('year')
            ->orderBy('month')
            ->orderBy('day')
            ->get();

        foreach ($result2 as $v) {

            if ($v->year >= 2020) {
                $_ym2["{$v->year}-{$v->month}"] = "";
            }

            $yachin["{$v->year}-{$v->month}"][] = number_format($v->price) . " ({$v->day})";
        }

        $denki["2021-08"][] = "4,400 (19)";
        $suidou["2021-02"][] = "30,003 (05)";

        $wifi["2020-01"][] = "16,812 (06)";
        $wifi["2020-01"][] = "16,900 (31)";
        $wifi["2020-03"][] = "24,914 (02)";

        $mobile["2020-03"][] = "5,080 (31)";
        $mobile["2020-04"][] = "5,080 (30)";
        $mobile["2020-06"][] = "5,080 (01)";

        $YM2 = array_keys($_ym2);
        sort($YM2);

        $ary2 = [];
        foreach ($YM2 as $v3) {

            if (!isset($yachin[$v3])) {
                continue;
            }

            $ary2[] = [
                'ym' => $v3,
                'yachin' => (isset($yachin)) ? implode(" / ", $yachin[$v3]) : "",
                'wifi' => (isset($wifi[$v3])) ? implode(" / ", $wifi[$v3]) : "",
                'mobile' => (isset($mobile[$v3])) ? implode(" / ", $mobile[$v3]) : "",
                'gas' => (isset($gas[$v3])) ? implode(" / ", $gas[$v3]) : "",
                'denki' => (isset($denki[$v3])) ? implode(" / ", $denki[$v3]) : "",
                'suidou' => (isset($suidou[$v3])) ? implode(" / ", $suidou[$v3]) : ""
            ];
        }

//        $ary3 = [];
//        foreach ($ary2 as $v) {
//            $ary3[] = implode("|", $v);
//        }


        $response = $ary2;

        return response()->json(['data' => $response]);

    }


    /**
     * @param Request $request
     * @return mixed
     */
    public function moneydl(Request $request)
    {

        $response = [];

        list($year, $month, $day) = explode("-", $request->date);

        $result = DB::table('t_money')
            ->where('year', '=', $year)
            ->where('month', '=', $month)
            ->where('day', '=', $day)
            ->get();

        $ary = [];
        if (isset($result[0])) {

            $ary['yen_10000'] = $result[0]->yen_10000;
            $ary['yen_5000'] = $result[0]->yen_5000;
            $ary['yen_2000'] = $result[0]->yen_2000;
            $ary['yen_1000'] = $result[0]->yen_1000;
            $ary['yen_500'] = $result[0]->yen_500;
            $ary['yen_100'] = $result[0]->yen_100;
            $ary['yen_50'] = $result[0]->yen_50;
            $ary['yen_10'] = $result[0]->yen_10;
            $ary['yen_5'] = $result[0]->yen_5;
            $ary['yen_1'] = $result[0]->yen_1;

            $ary['bank_a'] = $result[0]->bank_a;
            $ary['bank_b'] = $result[0]->bank_b;
            $ary['bank_c'] = $result[0]->bank_c;
            $ary['bank_d'] = $result[0]->bank_d;
            $ary['bank_e'] = $result[0]->bank_e;

            $ary['pay_a'] = $result[0]->pay_a;
            $ary['pay_b'] = $result[0]->pay_b;
            $ary['pay_c'] = $result[0]->pay_c;
            $ary['pay_d'] = $result[0]->pay_d;
            $ary['pay_e'] = $result[0]->pay_e;

        }

        $sum = 0;
        foreach ($ary as $key => $value) {
            if (preg_match("/yen_(.+)/", $key, $m)) {
                $sum += ($value * $m[1]);
            } else {
                $sum += $value;
            }
        }

        $ary['sum'] = "{$sum}";


        $response = $ary;


//        else {
//            $response = "-";
//        }


        return response()->json(['data' => $response]);
    }


    /**
     * @param Request $request
     * @return mixed
     */
    public function spendMonthlyItem(Request $request)
    {

        $response = [];

        list($year, $month, $day) = explode("-", $request->date);

        //日々の消費額
        $dailySpend = DB::table('t_dailyspend')
            ->where('year', '=', $year)
            ->where('month', '=', $month)
            ->orderBy('day')
            ->orderBy('id')
            ->get();

        foreach ($dailySpend as $v) {
            $ymd = $v->year . "-" . $v->month . "-" . $v->day;

            $cnt = 0;
            if (isset($response[$ymd])) {
                $cnt = count($response[$ymd]);
            }


            $response[] = [
                'date' => $ymd,
                'koumoku' => $v->koumoku,
                'price' => $v->price,
                'bank' => 0,
            ];


//            $response[$ymd][$cnt]['koumoku'] = $v->koumoku;
//            $response[$ymd][$cnt]['price'] = $v->price;
//            $response[$ymd][$cnt]['bank'] = 0;
        }

        //クレジットでの消費額
        $credit = DB::table('t_credit')
            ->where('year', '=', $year)
            ->where('month', '=', $month)
            ->orderBy('day')
            ->orderBy('id')
            ->get();

        foreach ($credit as $v) {
            $ymd = $v->year . "-" . $v->month . "-" . $v->day;

            $cnt = 0;
            if (isset($response[$ymd])) {
                $cnt = count($response[$ymd]);
            }


            $response[] = [
                'date' => $ymd,
                'koumoku' => $v->item,
                'price' => $v->price,
                'bank' => 1,
            ];


//            $response[$ymd][$cnt]['koumoku'] = $v->item;
//            $response[$ymd][$cnt]['price'] = $v->price;
//            $response[$ymd][$cnt]['bank'] = 1;
        }

        return response()->json(['data' => $response]);
    }


    /**
     * @param Request $request
     * @return mixed
     */
    public function getmonthlytimeplace(Request $request)
    {

        $response = [];

        list($year, $month, $day) = explode("-", $request->date);

        $result = DB::table('t_timeplace')
            ->where('year', '=', $year)
            ->where('month', '=', $month)
            ->orderBy('day')
            ->orderBy('time')
            ->get();

        foreach ($result as $v) {
            $ymd = $v->year . "-" . $v->month . "-" . $v->day;

            $response[] = [
                'date' => $ymd,
                'time' => $v->time,
                'place' => $v->place,
                'price' => $v->price,
            ];

            /*
            $cnt = 0;
            if (isset($response[$ymd])) {
                $cnt = count($response[$ymd]);
            }

            $response[$ymd][$cnt]['time'] = $v->time;
            $response[$ymd][$cnt]['place'] = $v->place;
            $response[$ymd][$cnt]['price'] = $v->price;
            */
        }

        return response()->json(['data' => $response]);
    }


    /**
     * @param Request $request
     * @return mixed
     */
    public function getmonthlytraindata(Request $request)
    {

        $response = [];

        list($year, $month, $day) = explode("-", $request->date);

        $table = "t_article" . $year;

        $traindata = DB::table($table)
            ->where('year', '=', $year)
            ->where('month', '=', $month)
            ->where('tag', '=', '電車乗車')
            ->orderBy('day')
            ->orderBy('id')
            ->get();

        foreach ($traindata as $v) {
            $ymd = $v->year . "-" . $v->month . "-" . $v->day;


            $response[] = [
                "date" => $ymd,
                "train" => strtr($v->article, ['\r\n' => '|']),
            ];


//            $cnt = 0;
//            if (isset($response[$ymd])) {
//                $cnt = count($response[$ymd]);
//            }
//
//            $response[$ymd][$cnt] = $v->article;
        }

        return response()->json(['data' => $response]);

    }


    /**
     * @param Request $request
     * @return mixed
     */
    public function getmonthlyweeknum(Request $request)
    {

        $response = [];

        $time = strtotime($request->date);

        $response = 1 + date('W', $time);

        return response()->json(['data' => ["weeknum" => $response]]);

    }


    /**
     * @param Request $request
     * @return mixed
     */
    public function getmonthSpendItem(Request $request)
    {
        $response = [];

        list($year, $month, $day) = explode("-", $request->date);

        $ary = [];

        $result = DB::table('t_dailyspend')
            ->where('year', '=', $year)
            ->where('month', '=', $month)
            ->orderBy('day')
            ->orderBy('id')
            ->get();

        foreach ($result as $v) {
            $date = "{$v->year}-{$v->month}-{$v->day}";
            $ary[$date][] = "{$v->koumoku}|(daily)|{$v->price}";
        }

        $result = DB::table('t_credit')
            ->where('year', '=', $year)
            ->where('month', '=', $month)
            ->orderBy('day')
            ->orderBy('id')
            ->get();

        foreach ($result as $v) {
            $date = "{$v->year}-{$v->month}-{$v->day}";
            $ary[$date][] = "{$v->item}|(bank)|{$v->price}";
        }

        $result = DB::table('t_salary')
            ->where('year', '=', $year)
            ->where('month', '=', $month)
            ->orderBy('day')
            ->orderBy('id')
            ->get();

        foreach ($result as $v) {
            $date = "{$v->year}-{$v->month}-{$v->day}";
            $ary[$date][] = "収入|(income)|{$v->salary}";
        }

        $ary2 = [];
        foreach ($ary as $date => $v) {
            $ary2[] = [
                "date" => $date,
                "item" => $v,
            ];
        }


        $response = $ary2;

        return response()->json(['data' => $response]);
    }


    /**
     * @param Request $request
     * @return mixed
     */
    public function getSeiyuuPurchaseItemList(Request $request)
    {
        $response = [];
        $response2 = [];

        $ary = $this->getSeiyuuData($request->date);

        list($year, $month, $day) = explode("-", $request->date);

        $tma = Carbon::now()->subMonth(2)->format('Y-m-d');
        //$twoMonthAgo = new Carbon($tma);
        $ex_tma = explode("-", $tma);
        $twoMonthAgo = strtotime(date("$ex_tma[0]-$ex_tma[1]-01"));

        $ary2 = [];
        $ary3 = [];
        foreach ($ary as $v) {
            if ($v["item"] == "送料") {
                continue;
            }

            $ary2[$v["item"]][] = $v["date"] . "|" . $v["tanka"] . "|" . $v["kosuu"] . "|" . $v["price"];

//            $hikaku = new Carbon($v["date"]);
            $hikaku = strtotime($v["date"]);

            if ($hikaku > $twoMonthAgo) {
                $ary3[] = $v["item"];
            }
        }

        $ary4 = [];
        $ary5 = [];
        foreach ($ary2 as $item => $v) {
            if (in_array($item, $ary3)) {
                $ary4[$item] = $v;
            } else {
                $ary5[$item] = $v;
            }
        }

        $ary6 = [];
        foreach ($ary4 as $item => $v) {
            $str = implode("/", $v);
            $ary6[] = $item . ":" . $str;
        }

        $ary7 = [];
        foreach ($ary5 as $item => $v) {
            $str = implode("/", $v);
            $ary7[] = $item . ":" . $str;
        }

//
//        $response = $ary6;
//        $response2 = $ary7;
//
//


        $ary8 = [];

        foreach ($ary6 as $v6) {
            $ex_v6 = explode(":", $v6);
            $ex_v6_1 = explode("/", $ex_v6[1]);
            $list = [];
            foreach ($ex_v6_1 as $v61) {
                $list[] = $v61;
            }
            $ary8[] = [
                "item" => $ex_v6[0],
                "list" => $list,
            ];
        }


        foreach ($ary7 as $v6) {
            $ex_v6 = explode(":", $v6);
            $ex_v6_1 = explode("/", $ex_v6[1]);
            $list = [];
            foreach ($ex_v6_1 as $v61) {
                $list[] = $v61;
            }
            $ary8[] = [
                "item" => $ex_v6[0],
                "list" => $list,
            ];
        }


        $response = $ary8;


        return response()->json(['data' => $response]);
//        return response()->json(['data' => $response, 'data2' => $response2]);
    }


    /**
     * @param $date
     * @return array
     */
    private function getSeiyuuData($date)
    {

        list($year, $month, $day) = explode("-", $date);
        $table = 't_article' . $year;

        ///////////////////////////////////////////////
        $result = DB::table($table)->where('article', 'like', '%西友ネットスーパー内訳%')
            ->orderBy('year')->orderBy('month')->orderBy('day')
            ->get();
        foreach ($result as $k => $v) {
            $_tmp_date[$v->year . "-" . $v->month . "-" . $v->day] = "";
        }
        $_key_date = array_keys($_tmp_date);
        sort($_key_date);
        ///////////////////////////////////////////////

        //--------------------------------------------
        $seiyuPhoto = [];
        $file = public_path() . "/mySetting/seiyuPhoto.data";
        $content = file_get_contents($file);
//        $ex_content = explode("\n", mb_convert_encoding($content, "utf8", "sjis-win"));
        $ex_content = explode("\n", $content);

        foreach ($ex_content as $v) {
            if (trim($v) == "") {
                continue;
            }

            $ex_v = explode("|", trim($v));
            $seiyuPhoto[trim($ex_v[1])] = trim($ex_v[0]);
        }
        //--------------------------------------------

        $ary = [];
        $result = DB::table($table)->where('article', 'like', '%西友ネットスーパー内訳%')
            ->orderBy('year')->orderBy('month')->orderBy('day')
            ->get();
        foreach ($result as $k => $v) {
            $date = $v->year . "-" . $v->month . "-" . $v->day;
            $ex_article = explode(">", $v->article);
            for ($i = 1; $i < count($ex_article); $i++) {
                $ex_ex_article = explode("\n", $ex_article[$i]);

                $item = trim($ex_ex_article[1]);

                if (preg_match("/^【店内】/", $item)) {

                    $tanka = trim(strtr($ex_ex_article[6], ['円' => '', ',' => '']));
                    $kosuu = trim($ex_ex_article[7]);
                    $price = trim(strtr($ex_ex_article[8], ['円' => '', ',' => '']));
                } else {
                    $tanka = trim(strtr($ex_ex_article[7], ['円' => '', ',' => '']));
                    $kosuu = trim($ex_ex_article[8]);
                    $price = trim(strtr($ex_ex_article[9], ['円' => '', ',' => '']));
                }

                $pos = array_search($date, $_key_date);

                $imgItem = strtr(trim($item), ['　（非食品）' => '']);
                $img = (isset($seiyuPhoto[$imgItem])) ?
                    $seiyuPhoto[$imgItem] : "";

                $ary[] = [
                    'date' => $date,
                    'pos' => $pos,
                    'item' => $item,
                    'tanka' => $tanka,
                    'kosuu' => $kosuu,
                    'price' => $price,
                    'img' => $img
                ];
            }
        }

        return $ary;
    }


    /**
     * @return void
     */
    public function getAllBank()
    {
        $response = [];

        $result = DB::table('t_money')
            ->where('year', '>=', '2020')
            ->orderBy('id')
            ->get();

        foreach ($result as $v) {
            $response[] = [
                "date" => "{$v->year}-{$v->month}-{$v->day}",

                "bank_a" => $v->bank_a,
                "bank_b" => $v->bank_b,
                "bank_c" => $v->bank_c,
                "bank_d" => $v->bank_d,
                "bank_e" => $v->bank_e,

                "pay_a" => $v->pay_a,
                "pay_b" => $v->pay_b,
                "pay_c" => $v->pay_c,
                "pay_d" => $v->pay_d,
                "pay_e" => $v->pay_e,
            ];
        }

        return response()->json(['data' => $response]);
    }


    /////////////////////////////////////////////////// creditSummary
    private function getYearCredit($year)
    {

        $table = 't_article' . $year;


        //------------------------------------------//
        $result = DB::table($table)
            ->where('year', $year)
            ->where('article', 'like', '%paypayカード内訳%')->get();

        $ary = [];
        foreach ($result as $v2) {
            $ex_result = explode("\n", $v2->article);
            foreach ($ex_result as $v) {
                $val = trim(strip_tags($v));


                if (preg_match("/paypayカード内訳/", $val)) {
                    continue;
                }

                $ex_val = explode("\t", $val);

                $date = strtr(trim($ex_val[0]), ['/' => '-']);
                $price = strtr(trim($ex_val[4]), [',' => '', '円' => '']);

                if (trim($price) == "") {
                    continue;
                }

                $im = trim($ex_val[1]);
                $ary[$im][$v2->month][] = $price;

            }
        }

        //------------------------------------------//


        //------------------------------------------//
        $result = DB::table($table)
            ->where('year', $year)
            ->where('article', 'like', '%ユーシーカード内訳%')->get();

        foreach ($result as $v2) {
            $ex_result = explode("\n", $v2->article);
            foreach ($ex_result as $v) {
                $val = trim(strip_tags($v));
                if (preg_match("/円.+円/", trim($val))) {
                    $ex_val = explode("\t", $val);
                    $date = strtr(trim($ex_val[1]), ['/' => '-']);
                    $price = strtr(trim($ex_val[6]), [',' => '', '円' => '']);

                    if (trim($price) == "") {
                        continue;
                    }

                    $im = trim($ex_val[3]);
//                    $im = $this->makeItemName($im);
                    $ary[$im][$v2->month][] = $price;

                }
            }
        }
        //------------------------------------------//

        //------------------------------------------//
        $result = DB::table($table)
            ->where('year', $year)
            ->where('article', 'like', '%楽天カード内訳%')->get();

        foreach ($result as $v2) {
            $ex_result = explode("\n", $v2->article);
            foreach ($ex_result as $v) {
                $val = trim(strip_tags($v));
                if (preg_match("/本人/", trim($val))) {
                    $ex_val = explode("\t", $val);
                    $date = strtr(trim($ex_val[0]), ['/' => '-']);
                    $price = strtr(trim($ex_val[4]), [',' => '', '¥' => '']);

                    if (trim($price) == "") {
                        continue;
                    }

                    $im = trim($ex_val[1]);
//                    $im = $this->makeItemName($im);
                    $ary[$im][$v2->month][] = $price;

                }
            }
        }
        //------------------------------------------//

        //------------------------------------------//
        $result = DB::table($table)
            ->where('year', $year)
            ->where('article', 'like', '%Amexカード内訳%')->get();

        foreach ($result as $v2) {
            $ex_result = explode("\n", $v2->article);
            foreach ($ex_result as $v) {
                $val = trim(strip_tags($v));
                if (preg_match("/本人/", trim($val))) {
                    $ex_val = explode("\t", $val);
                    $date = strtr(trim($ex_val[0]), ['/' => '-']);
                    $price = strtr(trim($ex_val[4]), [',' => '', '¥' => '']);

                    if (trim($price) == "") {
                        continue;
                    }

                    $im = trim($ex_val[1]);
//                    $im = $this->makeItemName($im);
                    $ary[$im][$v2->month][] = $price;

                }
            }
        }
        //------------------------------------------//

        //------------------------------------------//
        $result = DB::table($table)
            ->where('year', $year)
            ->where('article', 'like', '%住友カード内訳%')->get();

        foreach ($result as $v2) {
            $ex_result = explode("\n", $v2->article);
            foreach ($ex_result as $v) {
                $val = trim(strip_tags($v));
                if (preg_match("/◎/", trim($val))) {
                    $ex_val = explode("\t", $val);
                    $date = strtr("20" . trim($ex_val[0]), ['/' => '-']);
                    $price = strtr(trim($ex_val[2]), [',' => '']);

                    if (trim($price) == "") {
                        continue;
                    }

                    $im = trim($ex_val[1]);
//                    $im = $this->makeItemName($im);
                    $ary[$im][$v2->month][] = $price;

                }
            }
        }
        //------------------------------------------//

        $ary2 = [];
        $item = [];
        foreach ($ary as $im => $v) {

            $item[] = $im;

            for ($i = 1; $i <= 12; $i++) {
                $month = sprintf("%02d", $i);
                $sum = (isset($ary[$im][$month])) ? array_sum($ary[$im][$month]) : 0;
                $ary2[$im][$month] = $sum;
            }
        }

        return [$item, $ary2];

    }


    /*




    private function makeItemName($im)
    {
        $im = mb_convert_kana($im, "aK");

        if (preg_match("/AMAZON.CO.JP/", $im)) {
            $im = "AMAZON";
        }
        if (preg_match("/アマソ゛ンフ゜ライムカイヒ/", $im)) {
            $im = "AMAZON PRIME会費";
        }
        if (preg_match("/アマソ゛ン/", $im)) {
            $im = "AMAZON";
        }
        if (preg_match("/AMAZON DOWNLOADS/", $im)) {
            $im = "AMAZON DOWNLOADS";
        }
        if (preg_match("/AmazonDownloads/", $im)) {
            $im = "AMAZON DOWNLOADS";
        }
        if (preg_match("/Amazon　Downloads/", $im)) {
            $im = "AMAZON DOWNLOADS";
        }

        if (preg_match("/YOUTUBE/", $im)) {
            $im = "YOUTUBE";
        }

        if (preg_match("/UDEMY/", $im)) {
            $im = "UDEMY";
        }

        if (preg_match("/VULTR/", $im)) {
            $im = "VULTR";
        }

        if (preg_match("/MICROSOFT/", $im)) {
            $im = "MICROSOFT";
        }

        if (preg_match("/MSFT/", $im)) {
            $im = "MICROSOFT";
        }

        if (preg_match("/NTTコミュニケーションズ/", $im)) {
            $im = "NTTコミュ";
        }

        if (preg_match("/PLAYSTATION/", $im)) {
            $im = "PLAYSTATION";
        }

        if (preg_match("/投信積立/", $im)) {
            $im = "投信積立";
        }

        if (preg_match("/楽天モバイル/", $im)) {
            $im = "楽天モバイル";
        }

        if (preg_match("/甘党・辛党丸田屋/", $im)) {
            $im = "甘党・辛党丸田屋";
        }

        if (preg_match("/西友/", $im)) {
            $im = "西友ネットスーパー";
        }

        if (preg_match("/マイクロソフト/", $im)) {
            $im = "MICROSOFT";
        }

        if (preg_match("/AMAZON WEB SERVICES/", $im)) {
            $im = "AMAZON WEB SERVICES";
        }

        if (preg_match("/PATREON/", $im)) {
            $im = "PATREON";
        }

        if (preg_match("/ストリートアカデミー/", $im)) {
            $im = "ストアカ";
        }

        if (preg_match("/お名前.com/", $im)) {
            $im = "お名前.com";
        }

        if (preg_match("/オナマエ/", $im)) {
            $im = "お名前.com";
        }

        if (preg_match("/ドットインストール/", $im)) {
            $im = "ドットインストール";
        }

        if (preg_match("/NTTコミュニケ-ションズ/", $im)) {
            $im = "NTTコミュ";
        }

        if (preg_match("/Amazonプライム会費/", $im)) {
            $im = "AMAZON PRIME会費";
        }

        if (preg_match("/GOOGLE/", $im)) {
            $im = "GOOGLE";
        }

        if (preg_match("/JCB国内利用　JCB モノタロウ/", $im)) {
            $im = "MonotaRO.com";
        }

        if (preg_match("/JCB国内利用　JCB/", $im)) {
            $im = "JCB国内利用　JCB";
        }

        if (preg_match("/^JCB海外利用　NINTENDO/", $im)) {
            $im = "NINTENDO";
        }

        if (preg_match("/^NINTENDO/", $im)) {
            $im = "NINTENDO";
        }

        if (preg_match("/^ドコモご利用料金/", $im)) {
            $im = "ドコモご利用料金";
        }

        if (preg_match("/^フリ-/", $im)) {
            $im = "フリー";
        }

        if (preg_match("/^フリー/", $im)) {
            $im = "フリー";
        }

        if (preg_match("/^エ-タ゛フ゛リユ-エス シ゛ヤハ/", $im)) {
            $im = "AMAZON WEB SERVICES";
        }

        if (preg_match("/^オリオンツアー/", $im)) {
            $im = "オリオンツアー";
        }

        if (preg_match("/^AUDIBLE/", $im)) {
            $im = "AUDIBLE";
        }

        if (preg_match("/^JCB海外利用　AUDIBLE/", $im)) {
            $im = "AUDIBLE";
        }

        if (preg_match("/^ブルーピーター/", $im)) {
            $im = "ブルーピーター";
        }

        if (preg_match("/^モモ商事/", $im)) {
            $im = "モモ商事";
        }

        if (preg_match("/^JCB国内利用　JCB/", $im)) {
            $im = "JCB";
        }

        if (preg_match("/^JCB海外利用　MCAFEE/", $im)) {
            $im = "MCAFEE";
        }

        if (preg_match("/^DRI FITBIT/", $im)) {
            $im = "FITBIT";
        }

        if (preg_match("/^EBAY JAPAN/", $im)) {
            $im = "EBAY";
        }

        if (preg_match("/^さくらインターネット/", $im)) {
            $im = "さくらインターネット";
        }

        return $im;
    }





    */


    public function getYearCreditSummaryItem(Request $request)
    {
        $response = [];

        $credit = $this->getYearCredit($request->year);

        $item = $credit[0];
        $ary2 = $credit[1];

        sort($item);

//        $response = ['midashi' => $item, 'summary' => $ary2];

//        return response()->json(['data' => $response]);
        return response()->json(['data' => $item]);
    }

    public function getYearCreditSummarySummary(Request $request)
    {
        $response = [];

        $credit = $this->getYearCredit($request->year);

        $item = $credit[0];
        $ary2 = $credit[1];

        sort($item);

        $ary3 = [];
        foreach ($ary2 as $k99 => $v99) {


            $ary99 = [];
            foreach ($v99 as $month => $price) {
                $ary99[] = [
                    "month" => $month,
                    "price" => $price,
                ];
            }

            $ary3[] = [
                "item" => $k99,
                "list" => $ary99,
            ];
        }


//        $response = ['midashi' => $item, 'summary' => $ary2];

//        return response()->json(['data' => $response]);
        return response()->json(['data' => $ary3]);
    }
    /////////////////////////////////////////////////// creditSummary


    /////////////////////////////////////////////////// spendSummary
    public function getYearSpendSummaySummary(Request $request)
    {
        $response = [];

        $item = $this->getItemMidashi();

        $ary = [];
        foreach ($item as $im) {
            for ($i = 1; $i <= 12; $i++) {

                $year = $request->year;
                $month = sprintf("%02d", $i);

                $sql1 = " select sum(price) as sum from t_dailyspend where koumoku = '{$im}' and year = '{$year}' and month = '{$month}'; ";
                $ans1 = DB::select($sql1);

                $sql2 = " select sum(price) as sum from t_credit where item = '{$im}' and year = '{$year}' and month = {$month}; ";
                $ans2 = DB::select($sql2);

                $ary[$im][$month] = ($ans1[0]->sum + $ans2[0]->sum);
            }
        }

        $ary2 = [];
        foreach ($ary as $item => $ary99) {
            $list99 = [];
            foreach ($ary99 as $month => $price) {
                $list99[] = [
                    "month" => $month,
                    "price" => $price,
                ];
            }

            $ary2[] = [
                "item" => $item,
                "list" => $list99,
            ];
        }


//        $response = ['midashi' => $item, 'summary' => $ary];

        return response()->json(['data' => $ary2]);
    }


    /**
     * @return array
     */
    private function getItemMidashi()
    {
        $item = [];

        $str = "
食費
牛乳代
弁当代
住居費
交通費
支払い
credit
遊興費
ジム会費
お賽銭
交際費
雑費
教育費
機材費
被服費
医療費
美容費
通信費
保険料
水道光熱費
共済代
GOLD
投資信託
株式買付
アイアールシー
手数料
不明
メルカリ
利息
プラス
所得税
住民税
年金
国民年金基金
国民健康保険
";

        $ex_str = explode("\n", $str);
        foreach ($ex_str as $v) {
            if (trim($v) == "") {
                continue;
            }
            $item[] = trim($v);
        }

        return $item;
    }

    /////////////////////////////////////////////////// spendSummary


    /**
     * @return void
     */
    public function getEverydayMoney()
    {

        $response = [];

        $file = public_path() . "/mySetting/MoneyTotal.data";
        $content = file_get_contents($file);
        $ex_content = explode("\n", mb_convert_encoding($content, "utf8", "sjis-win"));
        foreach ($ex_content as $v) {
            if (trim($v) == "") {
                continue;
            }

            list($date, $youbiNum, $sum, $spend) = explode("|", trim($v));
            $response[] = [
                "date" => $date,
                "youbiNum" => $youbiNum,
                "sum" => $sum,
                "spend" => $spend,
            ];
        }

        return response()->json(['data' => $response]);
    }


    /**
     * @param Request $request
     * @return mixed
     */
    public function getcompanycredit(Request $request)
    {
        list($year, $month, $day) = explode("-", $request->date);
        $table = 't_article' . $year;

        $ary = [];
        for ($i = 1; $i <= 12; $i++) {


            //------------------------------------------//
            $result = DB::table($table)
                ->where('year', $year)->where('month', sprintf("%02d", $i))
                ->where('article', 'like', '%paypayカード内訳%')->get();

            $sum = 0;
            foreach ($result as $v2) {
                $ex_result = explode("\n", $v2->article);
                foreach ($ex_result as $v) {
                    $val = trim(strip_tags($v));

                    if (preg_match("/paypayカード内訳/", $val)) {
                        continue;
                    }

                    $ex_val = explode("\t", $val);

                    $date = strtr(trim($ex_val[0]), ['/' => '-']);
                    $price = strtr(trim($ex_val[4]), [',' => '', '円' => '']);

                    if (trim($price) == "") {
                        continue;
                    }

//                    $ary[$genDate][] = [
//                        'item' => trim($ex_val[1]),
//                        'price' => $price,
//                        'date' => $date,
//                        'kind' => 'paypay'
//                    ];


                    $sum += $price;


                }
            }


            $sum_paypay = [
                "company" => "paypay",
                "sum" => $sum,
            ];


            //------------------------------------------//


            //------------------------------------------//
            $result = DB::table($table)
                ->where('year', $year)->where('month', sprintf("%02d", $i))
                ->where('article', 'like', '%ユーシーカード内訳%')->get();

            $sum = 0;
            foreach ($result as $v2) {
                $ex_result = explode("\n", $v2->article);
                foreach ($ex_result as $v) {
                    $val = trim(strip_tags($v));
                    if (preg_match("/円.+円/", trim($val))) {
                        $ex_val = explode("\t", $val);
                        $date = strtr(trim($ex_val[1]), ['/' => '-']);
                        $price = strtr(trim($ex_val[6]), [',' => '', '円' => '']);

                        if (trim($price) == "") {
                            continue;
                        }

                        $sum += $price;
                    }
                }
            }

            $sum_uc = [
                "company" => "uc",
                "sum" => $sum,
            ];
            //------------------------------------------//

            //------------------------------------------//
            $result = DB::table($table)
                ->where('year', $year)->where('month', sprintf("%02d", $i))
                ->where('article', 'like', '%楽天カード内訳%')->get();

            $sum = 0;
            foreach ($result as $v2) {
                $ex_result = explode("\n", $v2->article);
                foreach ($ex_result as $v) {
                    $val = trim(strip_tags($v));
                    if (preg_match("/本人/", trim($val))) {
                        $ex_val = explode("\t", $val);
                        $date = strtr(trim($ex_val[0]), ['/' => '-']);
                        $price = strtr(trim($ex_val[4]), [',' => '', '¥' => '']);

                        if (trim($price) == "") {
                            continue;
                        }

                        $sum += $price;
                    }
                }
            }

            $sum_rakuten = [
                "company" => "rakuten",
                "sum" => $sum,
            ];
            //------------------------------------------//

            //------------------------------------------//
            $result = DB::table($table)
                ->where('year', $year)->where('month', sprintf("%02d", $i))
                ->where('article', 'like', '%Amexカード内訳%')->get();

            $sum = 0;
            foreach ($result as $v2) {
                $ex_result = explode("\n", $v2->article);
                foreach ($ex_result as $v) {
                    $val = trim(strip_tags($v));
                    if (preg_match("/本人/", trim($val))) {
                        $ex_val = explode("\t", $val);
                        $date = strtr(trim($ex_val[0]), ['/' => '-']);
                        $price = strtr(trim($ex_val[4]), [',' => '', '¥' => '']);

                        if (trim($price) == "") {
                            continue;
                        }

                        $sum += $price;
                    }
                }
            }

            $sum_amex = [
                "company" => "amex",
                "sum" => $sum,
            ];
            //------------------------------------------//

            //------------------------------------------//
            $result = DB::table($table)
                ->where('year', $year)->where('month', sprintf("%02d", $i))
                ->where('article', 'like', '%住友カード内訳%')->get();

            $sum = 0;
            foreach ($result as $v2) {
                $ex_result = explode("\n", $v2->article);
                foreach ($ex_result as $v) {
                    $val = trim(strip_tags($v));
                    if (preg_match("/◎/", trim($val))) {
                        $ex_val = explode("\t", $val);
                        $date = strtr("20" . trim($ex_val[0]), ['/' => '-']);
                        $price = strtr(trim($ex_val[2]), [',' => '']);

                        if (trim($price) == "") {
                            continue;
                        }

                        $sum += $price;
                    }
                }
            }

            $sum_sumitomo = [
                "company" => "sumitomo",
                "sum" => $sum,
            ];
            //------------------------------------------//

            $ym = "{$year}-" . sprintf("%02d", $i);
            $ary[] = [


                "ym" => $ym,
                "list" => [
                    $sum_uc, $sum_rakuten, $sum_sumitomo, $sum_amex,
                    $sum_paypay
                ],


            ];


        }


//
//
//
//        $response = [
//            $sum_uc, $sum_rakuten, $sum_sumitomo, $sum_amex
//        ];
//
//


        $response = $ary;
        return response()->json(['data' => $response]);
    }


    /**
     * @param Request $request
     * @return void
     */
    public function getUdemyData(Request $request)
    {
        $response = [];

        $table = 't_article' . date("Y");

        $result = DB::table($table)
            ->where('article', 'like', 'Udemy購入履歴%')
            ->first();

        $ex_article = explode("\r\n", $result->article);

        $ary = [];
        foreach ($ex_article as $v) {
            if (preg_match("/■/", trim($v))) {
                list($category, $x, $title, $date, $price, $pay) = explode("\t", trim($v));

                $_pay = strtr(trim($pay), ["￥" => "", "," => ""]);

                $ary[] = [
                    "date" => date("Y-m-d", strtotime(trim($date))),
                    "category" => trim($category),
                    "title" => trim($title),
                    "price" => strtr(trim($price), ["¥" => "", "," => ""]),
                    "pay" => trim(strtr($_pay, [strtr(trim($price), ["¥" => "", "," => ""]) => ""])),
                ];
            }
        }

        $response = $ary;

        return response()->json(['data' => $response]);
    }


    /**
     * @param Request $request
     * @return void
     */
    public function getBankMove(Request $request)
    {
        $response = [];

        $result = DB::table('t_bank_move')
            ->orderBy('from_year')
            ->orderBy('from_month')
            ->orderBy('from_day')
            ->get();

        foreach ($result as $v) {
            $response[] = [
                "date" => "{$v->from_year}-{$v->from_month}-{$v->from_day}",
                "bank" => $v->from_bank,
                "price" => $v->price,
                "flag" => 0,
            ];
            $response[] = [
                "date" => "{$v->to_year}-{$v->to_month}-{$v->to_day}",
                "bank" => $v->to_bank,
                "price" => $v->price,
                "flag" => 1,
            ];
        }

        return response()->json(['data' => $response]);
    }


    /**
     * @param Request $request
     * @return void
     */
    public function spendItemInsert(Request $request)
    {
        try {
            DB::beginTransaction();

            list($year, $month, $day) = explode("-", $request->date);

            DB::table('t_dailyspend')
                ->where('year', '=', $year)
                ->where('month', '=', $month)
                ->where('day', '=', $day)
                ->delete();

            $insert = [];
            foreach ($request->spend as $v) {
                $insert[] = [
                    "year" => $year,
                    "month" => $month,
                    "day" => $day,
                    "ymd" => "{$year}{$month}{$day}",
                    'koumoku' => $v["item"],
                    'price' => $v["price"],
                    'created_at' => date("Y-m-d"),
                    'updated_at' => date("Y-m-d"),
                    'flag' => "",
                ];
            }

            DB::table('t_dailyspend')->insert($insert);

            DB::commit();

            $response = $request->all();
            return response()->json(['data' => $response]);
        } catch (Exception $e) {
            DB::rollBack();
            abort(500, $e->getMessage());
        }
    }


    /**
     * @param Request $request
     * @return void
     */
    public function timeplaceInsert(Request $request)
    {
        try {
            DB::beginTransaction();

            list($year, $month, $day) = explode("-", $request->date);

            DB::table('t_timeplace')
                ->where('year', '=', $year)
                ->where('month', '=', $month)
                ->where('day', '=', $day)
                ->delete();

            $insert = [];
            foreach ($request->timeplace as $v) {
                $hour = substr($v['time'], 0, 2);
                $minute = substr($v['time'], 2, 2);

                $insert[] = [
                    "year" => $year,
                    "month" => $month,
                    "day" => $day,
                    "ymd" => "{$year}{$month}{$day}",
                    "time" => $v['time'],
                    "place" => $v['place'],
                    "price" => $v['price'],
                    'created_at' => date("Y-m-d")
                ];
            }

            DB::table('t_timeplace')->insert($insert);

            DB::commit();

            $response = $request->all();
            return response()->json(['data' => $response]);
        } catch (Exception $e) {
            DB::rollBack();
            abort(500, $e->getMessage());
        }
    }


    /**
     * @return mixed
     */
    public function getYoutubeCategoryTree()
    {
        $response = [];

        $sql = " select category1, category2, bunrui from t_youtube_data where del=0 group by category1, category2, bunrui; ";
        $result = DB::select($sql);

        foreach ($result as $v) {
            $response[] = [
                "category1" => $v->category1,
                "category2" => $v->category2,
                "bunrui" => $v->bunrui];
        }

        return response()->json(['data' => $response]);
    }


    /**
     * @return void
     */
    public function updateYoutubeCategoryTree(Request $request)
    {
        try {
            DB::beginTransaction();

            $update = [
                "category1" => $request->category1,
                "category2" => $request->category2
            ];

            DB::table('t_youtube_data')->where('bunrui', $request->bunrui)->update($update);

            DB::commit();

            $response = $request->all();
            return response()->json(['data' => $response]);
        } catch (Exception $e) {
            DB::rollBack();
            abort(500, $e->getMessage());
        }
    }


    /**
     * @return void
     */
    public function getBlankBunruiVideo()
    {
        $response = [];

        $sql = " select * from t_youtube_data where (bunrui='' or bunrui is null) and del=0; ";
        $result = DB::select($sql);

        foreach ($result as $v) {
            $response[] = [
                "youtube_id" => $v->youtube_id,
                "title" => $v->title,
            ];
        }

        return response()->json(['data' => $response]);
    }


    /**
     * @return void
     */
    public function oneBunruiInput(Request $request)
    {
        try {
            DB::beginTransaction();

            $update = [
                "category1" => $request->category1,
                "category2" => $request->category2,
                "bunrui" => $request->bunrui,
            ];

            DB::table('t_youtube_data')->where('youtube_id', $request->youtube_id)->update($update);

            DB::commit();

            $response = $request->all();
            return response()->json(['data' => $response]);
        } catch (Exception $e) {
            DB::rollBack();
            abort(500, $e->getMessage());
        }
    }


    /**
     * @param Request $request
     * @return void
     */
    public function getStationStamp()
    {
        $response = [];

        $result = DB::table('t_station_stamp')
            ->orderBy('train_code')
            ->orderBy('image_code')
            ->get();


        $keep_trainCode = '';


        $ary = [];
        foreach ($result as $v) {
            if ($keep_trainCode != $v->train_code) {
                $result2 = DB::table('t_station')
                    ->where('train_number', $v->train_code)
                    ->get();
                $station = [];
                foreach ($result2 as $v2) {
                    $station[$v2->station_name] = [
                        "lat" => $v2->lat,
                        "lng" => $v2->lng,
                    ];
                }
            }


            $ary[] = [
                "train_code" => $v->train_code,
                "train_name" => $v->train_name,

                "station_code" => $v->station_code,
                "station_name" => $v->station_name,
                "lat" => $station[$v->station_name]['lat'],
                "lng" => $station[$v->station_name]['lng'],

                "image_folder" => $v->image_folder,
                "image_code" => $v->image_code,

                "poster_position" => $v->poster_position,
                "stamp_get_date" => $v->stamp_get_date,


                "stamp_get_order" => $v->stamp_get_order,


            ];

            $keep_trainCode = $v->train_code;
        }

        $response = $ary;

        return response()->json(['data' => $response]);

    }


    /**
     * @return void
     */
    public function inputSpendCheckItem(Request $request)
    {
        try {
            DB::beginTransaction();

//            list($year, $month, $day) = explode("-", trim($request->date));
//            DB::table('t_spend_check_item')
//                ->where('year', $year)
//                ->where('month', $month)
//                ->delete();
//
//            $sql = " ALTER TABLE t_spend_check_item auto_increment = 1; ";
//            DB::statement($sql);

            foreach ($request->items as $v) {
                list($date, $item, $price, $type) = explode("|", trim($v));
                list($v_year, $v_month, $v_day) = explode("-", trim($date));

                //すでに入っているものは入れない
                //新規追加のものしか入らない
                $result99 = DB::table('t_spend_check_item')
                    ->where('year', $v_year)->where('month', $v_month)->where('day', $v_day)
                    ->where('item', $item)->where('price', $price)
                    ->first();
                if (!is_null($result99)) {
                    continue;
                }

                $insert = [
                    "year" => $v_year,
                    "month" => $v_month,
                    "day" => $v_day,
                    "ymd" => trim(strtr($date, ['-' => ''])),
                    "item" => $item,
                    "price" => $price,
                    "flag" => $type
                ];

                DB::table('t_spend_check_item')->insert($insert);
            }

            DB::commit();

            $response = $request->all();
            return response()->json(['data' => $response]);
        } catch (Exception $e) {
            DB::rollBack();
            abort(500, $e->getMessage());
        }
    }


    /**
     * @param Request $request
     * @return void
     */
    public function getSpendCheckItem(Request $request)
    {
        $response = [];

        list($year, $month, $day) = explode("-", trim($request->date));

        $result = DB::table('t_spend_check_item')
            ->where('year', $year)
            ->where('month', $month)
            ->orderBy('day')
            ->get();

        $ary = [];
        foreach ($result as $v) {
            $dt = [$v->year, $v->month, $v->day];
            $ary2 = [implode("-", $dt), $v->item, $v->price, $v->flag];

            $ary3 = [];
            $ary3[] = implode("|", $ary2);
            $ary3[] = $v->id;
            $ary3[] = "{$v->category1}|{$v->category2}";

            $ary[] = implode(";", $ary3);
        }

        $response = $ary;

        return response()->json(['data' => $response]);
    }


    /***
     * @param Request $request
     * @return void
     */
    public function updateKeihiCategory(Request $request)
    {
        try {
            DB::beginTransaction();

            $update = [];
            $update['category1'] = $request->category1;
            $update['category2'] = $request->category2;

            DB::table('t_spend_check_item')->where('id', $request->id)->update($update);

            DB::commit();

            $response = $request->all();
            return response()->json(['data' => $response]);
        } catch (Exception $e) {
            DB::rollBack();
            abort(500, $e->getMessage());
        }
    }


    /**
     * @param Request $request
     * @return void
     */
    public function selectSpendCheckItem(Request $request)
    {
        $response = [];


        list($year, $month, $day) = explode("-", $request->date);

        $result = DB::table('t_spend_check_item')
            ->where('year', $year)
            ->orderBy('year')->orderBy('month')->orderBy('day')
            ->get();


        $ary = [];
        foreach ($result as $k => $v) {


            $ary[] = [
                "id" => $v->id,
                "date" => "{$v->year}-{$v->month}-{$v->day}",
                "item" => $v->item,
                "price" => $v->price,
                "category1" => $v->category1,
                "category2" => $v->category2,
                "flag" => $v->flag,
            ];
        }

        $response = $ary;

        return response()->json(['data' => $response]);
    }


    /**
     * @return void
     */
    public function getStationStampNotGet()
    {
        $response = [];

        //-----------------------------//
        $ary = [];

        $sql = " select station_name, address, lat, lng from t_station where train_number in (select train_number from t_train where company_id = 18)";
        $result = DB::select($sql);

        foreach ($result as $v) {
            $ary[$v->station_name] = [
                "lat" => $v->lat,
                "lng" => $v->lng,
                "address" => $v->address,
            ];
        }
        //-----------------------------//

        $ary2 = [];

        $sql2 = " select station_name, poster_position from t_station_stamp where stamp_get_date = '' ";
        $result2 = DB::select($sql2);

        foreach ($result2 as $v2) {

            $in_out = 0;
            if (preg_match("/（改札外）/", trim($v2->poster_position))) {
                $in_out = 1;
            }

            $ary2[] = [
                "station_name" => $v2->station_name,
                "lat" => $ary[$v2->station_name]['lat'],
                "lng" => $ary[$v2->station_name]['lng'],
                "address" => $ary[$v2->station_name]['address'],
                "in_out" => $in_out,
            ];
        }

        $response = $ary2;

        return response()->json(['data' => $response]);
    }


    /**
     * @return mixed
     */
    public function getTaxPaymentItem(Request $request)
    {
        $response = [];

        $ary = [];

        list($year, $month, $day) = explode("-", $request->date);

        $result = DB::table('t_tax_payment')
            ->where('year', $year)->first();

        $ary = $result;


//        $result = DB::table('t_tax_payment_item')
//            ->where('year', $year)
//            ->get();
//
//        foreach ($result as $v) {
//            $ary[] = [
//                "item" => $v->item,
//                "price" => $v->price,
//            ];
//        }


        $response = $ary;

        return response()->json(['data' => $response]);
    }


    /**
     * @param Request $request
     * @return void
     */
    public function getTimeLocation(Request $request)
    {
        $response = [];

        $ary = [];

        list($year, $month, $day) = explode("-", $request->date);

        $result = DB::table('t_geoloc')
            ->where('year', $year)
            ->where('month', $month)
            ->where('day', $day)
            ->orderBy('time')
            ->get();

        $keepLatLng = "";
        foreach ($result as $v) {

            $_lat = substr(($v->latitude * 10000000), 0, 5);
            $_lng = substr(($v->longitude * 10000000), 0, 6);

            if ($keepLatLng != "{$_lat}|{$_lng}") {

                list($lat1, $lat2) = explode('.', $v->latitude);
                list($lng1, $lng2) = explode('.', $v->longitude);

                $_lat2 = substr($lat2, 0, 5);
                $_lng2 = substr($lng2, 0, 5);

                $ary[] = [
                    "date" => $request->date,
                    "time" => date("H:i", strtotime($v->time)),
                    "latitude" => "{$lat1}.{$_lat2}",
                    "longitude" => "{$lng1}.{$_lng2}",
                ];
            }

            $keepLatLng = "{$_lat}|{$_lng}";
        }

        $response = $ary;

        return response()->json(['data' => $response]);
    }


    /**
     * @param Request $request
     * @return void
     */
    public function insertGeoloc(Request $request)
    {
        try {
            DB::beginTransaction();

            list($year, $month, $day) = explode("-", $request->date);

            $insert = [
                "year" => $year,
                "month" => $month,
                "day" => $day,
                "time" => $request->time,
                "latitude" => $request->latitude,
                "longitude" => $request->longitude
            ];

            DB::table('t_geoloc')->insert($insert);

            DB::commit();

            $response = $request->all();
            return response()->json(['data' => $response]);
        } catch (Exception $e) {
            DB::rollBack();
            abort(500, $e->getMessage());
        }
    }


    /**
     * @param Request $request
     * @return void
     */
    public function getGeoloc(Request $request)
    {
        $response = [];

        $ary = [];

        list($year, $month, $day) = explode("-", $request->date);

        $result = DB::table('t_geoloc')
            ->where('year', $year)
            ->where('month', $month)
            ->where('day', $day)
            ->orderBy('time', 'desc')
            ->get();


        foreach ($result as $k => $v) {

            list($lat1, $lat2) = explode('.', $v->latitude);
            list($lng1, $lng2) = explode('.', $v->longitude);

            $_lat2 = substr($lat2, 0, 5);
            $_lng2 = substr($lng2, 0, 5);

            $returnLat = "{$lat1}.{$_lat2}";
            $returnLng = "{$lng1}.{$_lng2}";

            $ary[] = [
                "date" => "{$v->year}-{$v->month}-{$v->day}",
                "time" => $v->time,
                "latitude" => $returnLat,
                "longitude" => $returnLng,
            ];


        }

        $cnt_ary = count($ary);

        for ($i = 0; $i < count($ary); $i++) {
            $percent = 0;

            if ($i < count($ary) - 1) {
                $str_a = "{$ary[$i]['latitude']}|{$ary[$i]['longitude']}";
                $str_b = "{$ary[$i+1]['latitude']}|{$ary[$i+1]['longitude']}";
                similar_text($str_a, $str_b, $percent);
            }

            $ary[$i]['similarPercent'] = round($percent);
        }

        $response = $ary;

        return response()->json(['data' => $response]);

    }


    /**
     * @param Request $request
     * @return void
     */
    public function getAllGeoloc(Request $request)
    {

        $response = [];

        list($year, $month, $day) = explode("-", $request->date);

        if ($request->flag == "year") {
            $result = DB::table('t_geoloc')
                ->where('year', $year)
                ->orderBy('month')
                ->orderBy('day')
                ->orderBy('time', 'desc')
                ->get();
        } else {
            $result = DB::table('t_geoloc')
                ->where('year', $year)
                ->where('month', $month)
                ->orderBy('month')
                ->orderBy('time', 'desc')
                ->get();
        }

        $ary = [];

        $keepLL = [];
        foreach ($result as $v) {
            $genLat = substr($v->latitude, 0, 5);
            $genLng = substr($v->longitude, 0, 6);

            if (!in_array("{$genLat}|{$genLng}", $keepLL)) {

                list($lat1, $lat2) = explode('.', $v->latitude);
                list($lng1, $lng2) = explode('.', $v->longitude);

                $_lat2 = substr($lat2, 0, 5);
                $_lng2 = substr($lng2, 0, 5);

                $ary[] = [
                    "latitude" => "{$lat1}.{$_lat2}",
                    "longitude" => "{$lng1}.{$_lng2}"
                ];
            }

            $keepLL[] = "{$genLat}|{$genLng}";
        }

        $response = $ary;

        return response()->json(['data' => $response]);
    }


    /**
     * @param Request $request
     * @return void
     */
    public function getNearArtFacilities(Request $request)
    {
        $response = [];

        $sql = ' select * from t_art_facilities where ';

        $where = [];
        if ($request->address != "") {
            $where[] = ' address like "' . $request->address . '%" ';
        } else {
            if (($request->latitude != "") && ($request->longitude != "")) {
                $search_lat = substr($request->latitude, 0, 4);
                $search_lng = substr($request->longitude, 0, 5);

                $where[] = ' latitude like "' . $search_lat . '%" and longitude like "' . $search_lng . '%" ';
            }
        }

        if ($request->genre != "") {
            $where[] = ' genre like "%' . $request->genre . '%" ';
        }

        $sql .= implode(" and ", $where);
        $result = DB::select($sql);

//        if (count($result) > 30) {
//            return response()->json(['data' => [
//                [
//                    "id" => 99999999,
//                    "name" => '',
//                    "genre" => '',
//                    "address" => '',
//                    "latitude" => '',
//                    "longitude" => '',
//                    "dist" => '',
//                ]
//            ]]);
//        }

        $ary = [];
        $ary2 = [];

        $facility_names = [];

        $resultIds = [];

        foreach ($result as $v) {

            if (in_array($v->name, $facility_names)) {
                continue;
            }

            if (trim($v->latitude) == "" || trim($v->longitude) == "") {
                continue;
            }

            if (in_array($v->id, $resultIds)) {
                continue;
            }

            $getDist = $this->getDistance(
                $request->latitude,
                $request->longitude,
                $v->latitude,
                $v->longitude
            );

            $_dist = $getDist[0] * 1000;

            if ($request->address == "") {
                if ($_dist > ($request->radius * 1000)) {
                    continue;
                }
            }

            $disp_dist = $getDist[1];

            $ary[$_dist][] = [
                "id" => $v->id,
                "name" => $v->name,
                "genre" => $v->genre,
                "address" => $v->address,
                "latitude" => $v->latitude,
                "longitude" => $v->longitude,
                "dist" => $disp_dist,
            ];

            $ary2[] = $_dist;

            $facility_names[] = $v->name;

            $resultIds[] = $v->id;
        }


        if (count($ary) == 0) {
            return response()->json(['data' => [
                [
                    "id" => 88888888,
                    "name" => '',
                    "genre" => '',
                    "address" => '',
                    "latitude" => '',
                    "longitude" => '',
                    "dist" => '',
                ]
            ]]);
        }


        $ary3 = [];
        $a2 = array_unique($ary2);
        if (count($a2) > 0) {
            sort($a2);
            foreach ($a2 as $v) {
                foreach ($ary[$v] as $v2) {
                    $ary3[] = $v2;
                }
            }
        }

        $response = $ary3;

        return response()->json(['data' => $response]);
    }


    /**
     * @param Request $request
     * @return void
     */
    public function getArtGenre(Request $request)
    {
        $response = [];

        if (trim($request->address) != "") {
            $sql = ' select genre from `t_art_facilities` where address like "' . $request->address . '%" group by genre; ';
        } else {
            $sql = " select genre from `t_art_facilities` group by genre; ";
        }

        $result = DB::select($sql);

        $ary = [];
        foreach ($result as $v) {
            $ex_genre = explode("_", $v->genre);
            foreach ($ex_genre as $v2) {
                $ary[$v2] = "";
            }
        }

        $keys = array_keys($ary);
        sort($keys);

        $ary2 = [];
        foreach ($keys as $v) {

            if (preg_match("/ギャラリー/", $v)) {
                continue;
            }

            if (preg_match("/ミュージアム/", $v)) {
                continue;
            }

            if (preg_match("/その他/", $v)) {
                continue;
            }

            if (preg_match("/天王洲/", $v)) {
                continue;
            }

            $ary2[] = $v;
        }

        $ary2[] = "ギャラリー";
        $ary2[] = "ミュージアム";
        $ary2[] = "その他";

        $response = $ary2;

        return response()->json(['data' => $response]);
    }


    /**
     * @param Request $request
     * @return void
     */
    public function getArtCity(Request $request)
    {
        $response = [];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application/json',
                'X-API-KEY: Ts179qBc5oStoDfIwKobqnZBH4nobSSbDGVX7CJq'
            )
        );

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_URL,
            "https://opendata.resas-portal.go.jp/api/v1/cities?prefCode={$request->prefCode}"
        );

        $result = curl_exec($ch);

        $jsonStr = json_decode($result);

        $ary = [];
        foreach ($jsonStr->result as $v) {
            $prefCity = "{$request->prefecture}{$v->cityName}";

            $sql = ' select count(*) as cnt from t_art_facilities where address like "' . $prefCity . '%"; ';
            $result2 = DB::select($sql);

            $ary[] = [
                "prefCode" => $v->prefCode,
                "cityCode" => $v->cityCode,
                "cityName" => $v->cityName,
                "bigCityFlag" => $v->bigCityFlag,
                "count" => $result2[0]->cnt,
            ];
        }

        $response = $ary;

        return response()->json(['data' => $response]);
    }


    /**
     * @param Request $request
     * @return void
     */
    public function getNearStation(Request $request)
    {
        $response = [];

        $trainName = [];
        $result = DB::table('t_train')->get();
        foreach ($result as $v) {
            $trainName[$v->train_number] = $v->train_name;
        }

        $ary = [];
        $keepStation = [];
        foreach ($request->searchStation as $v) {
            list($lat, $lng) = explode("|", trim($v));

            $p_lat = substr($lat, 0, 4);
            $p_lng = substr($lng, 0, 5);

            $s_lat = [];
            $s_lng = [];
            for ($i = 0; $i <= 9; $i++) {
                $s_lat[] = "{$p_lat}{$i}";
                $s_lng[] = "{$p_lng}{$i}";
            }

            $ss_lat = implode("','", $s_lat);
            $ss_lng = implode("','", $s_lng);

            $sql = " select * from t_station where substr(lat, 1, 5) in ('{$ss_lat}') and substr(lng, 1, 6) in ('{$ss_lng}'); ";

            $result = DB::select($sql);

            foreach ($result as $v) {

                $dist = $this->getDistance(
                    $lat,
                    $lng,
                    $v->lat,
                    $v->lng
                );

                if ($dist[0] > 1) {
                    continue;
                }

                if (!in_array($v->station_name, $keepStation)) {
                    $ary[$v->id] = [
                        "id" => $v->id,
                        "stationName" => $v->station_name,
                        "address" => $v->address,
                        "lat" => $v->lat,
                        "lng" => $v->lng
                    ];
                }

                $keepStation[] = $v->station_name;
            }
        }

        $ary2 = [];
        foreach ($ary as $v) {
            $ary2[] = $v;
        }

        $response = $ary2;

        return response()->json(['data' => $response]);
    }

    /**
     * @return array
     */
    private function getDistance($originLat, $originLng, $destLat, $destLng)
    {
        $distanceKm = 6371 *
            acos(
                cos($originLat / 180 * M_PI) *
                cos(($destLng - $originLng) / 180 * M_PI) *
                cos($destLat / 180 * M_PI) +
                sin($originLat / 180 * M_PI) * sin($destLat / 180 * M_PI)
            );

        list($seisuu, $shousuu) = explode(".", $distanceKm);

        $sho = substr($shousuu, 0, 2);


        return ["{$seisuu}.{$sho}", "{$seisuu}.{$sho} Km"];
    }

    /**
     * @param Request $request
     * @return void
     */
    public function getLatLngTemple(Request $request)
    {
        $response = [];


        ///////////////////////////////////////////////
        $latLng_Temple = [];
        $result = DB::table('t_temple')->get();
        foreach ($result as $v) {
            $tem = [$v->temple];

            if (trim($v->memo) != "") {
                $ex_memo = explode("、", $v->memo);
                foreach ($ex_memo as $v2) {
                    $tem[] = $v2;
                }
            }

            foreach ($tem as $v2) {
                $result2 = DB::table('t_temple_latlng')
                    ->where('temple', $v2)
                    ->first();

                $latLng_Temple["{$result2->lat}|{$result2->lng}"][] = $v2;
            }
        }
        ///////////////////////////////////////////////

        ///////////////////////////////////////////////
        $name_Temple = [];
        $result = DB::table('t_temple')->get();
        foreach ($result as $v) {
            $tem = [$v->temple];

            if (trim($v->memo) != "") {
                $ex_memo = explode("、", $v->memo);
                foreach ($ex_memo as $v2) {
                    $tem[] = $v2;
                }
            }

            foreach ($tem as $v2) {
                $name_Temple[$v2][] = $v2;
            }
        }
        ///////////////////////////////////////////////

        $sql = ' select * from t_temple_list where ';

        $where = [];

        $search_lat = substr($request->latitude, 0, 2);
        $search_lng = substr($request->longitude, 0, 3);

        $where[] = ' lat like "' . $search_lat . '%" and lng like "' . $search_lng . '%" ';

        $sql .= implode(" and ", $where);
        $result = DB::select($sql);

        $ary = [];
        $ary2 = [];

        foreach ($result as $v) {

            $cnt = 0;
            if (!empty($latLng_Temple["{$v->lat}|{$v->lng}"])) {
                $cnt = count($latLng_Temple["{$v->lat}|{$v->lng}"]);
            }

            if ($cnt == 0) {
                if (!empty($name_Temple[$v->name])) {
                    $cnt = count($name_Temple[$v->name]);
                }
            }

            $getDist = $this->getDistance(
                $request->latitude,
                $request->longitude,
                $v->lat,
                $v->lng
            );

            $_dist = $getDist[0] * 1000;


            if ($_dist > ($request->radius * 1000)) {
                continue;
            }


            $disp_dist = $getDist[1];

            $ary[$_dist][] = [
                "id" => $v->id,
                "city" => $v->city,
                "jinjachou_id" => $v->jinjachou_id,
                "url" => $v->url,
                "name" => $v->name,
                "address" => $v->address,
                "latitude" => $v->lat,
                "longitude" => $v->lng,
                "dist" => $disp_dist,
                "cnt" => $cnt
            ];

            $ary2[] = $_dist;
        }

        if (count($ary) == 0) {
            return response()->json(['data' => [
                [
                    "id" => '88888888',
                    "city" => '',
                    "jinjachou_id" => '',
                    "url" => '',
                    "name" => '',
                    "address" => '',
                    "latitude" => '',
                    "longitude" => '',
                    "dist" => '',
                    "cnt" => 0
                ]
            ]]);
        }

        $ary3 = [];
        $a2 = array_unique($ary2);
        if (count($a2) > 0) {
            sort($a2);
            foreach ($a2 as $v) {
                foreach ($ary[$v] as $v2) {
                    $ary3[] = $v2;
                }
            }
        }

        $response = $ary3;

        return response()->json(['data' => $response]);
    }


    /**
     * @param Request $request
     * @return void
     */
    public function getTokyoTrainStation(Request $request)
    {
        $response = [];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_URL,
            "https://geoapi.heartrails.com/api/json?method=getCities&prefecture=%E6%9D%B1%E4%BA%AC%E9%83%BD"
        );

        $result = curl_exec($ch);

        $jsonStr = json_decode($result);

        $city = [];
        foreach ($jsonStr->response->location as $v) {
            $city[] = $v->city;
        }
//        print_r($city);

        $_train = [];
        foreach ($city as $v) {
            $result = DB::table('t_station')
                ->where('address', 'like', "%東京都{$v}%")
                ->get();

            foreach ($result as $v2) {

                if (strlen($v2->train_number) == 4) {
                    continue;
                }

                $_train[$v2->train_number] = "";
            }
        }

        $train = array_keys($_train);
        sort($train);

        $train99 = [];
        foreach ($train as $v) {
            $result99 = DB::table('t_station')
                ->where('train_number', $v)
                ->orderBy('id')
                ->first();

            $result98 = DB::table('t_station')
                ->where('train_number', $v)
                ->orderBy('id', 'desc')
                ->first();

            $getDist = $this->getDistance(
                $result99->lat,
                $result99->lng,
                $result98->lat,
                $result98->lng
            );

            if ($getDist[0] > 80) {
                continue;
            }

            $train99[] = $v;
        }

        $ary = [];
        foreach ($train99 as $v) {
            $result = DB::table('t_train')
                ->where('train_number', $v)
                ->first();

            $result2 = DB::table('t_station')
                ->where('train_number', $v)
                ->get();

            $station = [];
            foreach ($result2 as $v2) {
                $station[] = [
                    "id" => "{$v2->train_number}-{$v2->id}",
                    "station_name" => $v2->station_name,
                    "address" => $v2->address,
                    "lat" => $v2->lat,
                    "lng" => $v2->lng
                ];
            }

            $ary[] = [
                "train_number" => $v,
                "train_name" => $result->train_name,
                "station" => $station
            ];
        }

        $response = $ary;

        return response()->json(['data' => $response]);
    }


    /**
     * @param Request $request
     * @return void
     */
    public function insertTempleRoute(Request $request)
    {
        try {
            DB::beginTransaction();

            list($year, $month, $day) = explode("-", $request->date);

            //----------------------------//
            $station = [];
            $result = DB::table('t_station')->get();
            foreach ($result as $v) {
                $station[$v->id] = "{$v->station_name}\n{$v->address}";
            }
            //----------------------------//

            $result = DB::table("t_article{$year}")
                ->where('year', $year)
                ->where('month', $month)
                ->where('day', $day)
                ->get();
            $cnt = count($result);

            $str = "";
            $st = [];
            foreach ($request->data as $v) {

                if (
                    preg_match("/start/", trim($v)) ||
                    preg_match("/goal/", trim($v))
                ) {
                    if (
                        preg_match("/start/", trim($v))
                    ) {
                        $ex_start = explode("-", $v);
                        if (isset($station[$ex_start[1]])) {
                            $st[] = $ex_start[1];
                            $st[] = $station[$ex_start[1]];
                            $st[] = "　";
                        } else {
                            continue;
                        }
                    }

                    if (
                        preg_match("/goal/", trim($v))
                    ) {
                        $ex_goal = explode("-", $v);
                        if (isset($station[$ex_goal[1]])) {
                            $st[] = $ex_goal[1];
                            $st[] = $station[$ex_goal[1]];
                            $st[] = "　";
                        } else {
                            continue;
                        }
                    }
                } else {
                    $result2 = DB::table('t_temple_list')
                        ->where('id', $v)
                        ->first();

                    $st[] = $v;
                    $st[] = $result2->name;
                    $st[] = $result2->address;
                    $st[] = "　";
                }

            }

            $str = implode("\n", $st);

            $insert = [
                "year" => $year,
                "month" => $month,
                "day" => $day,
                "num" => $cnt,
                "article" => $str,
                "hide" => 0,
                "tag" => "",
                "created_at" => date("Y-m-d"),
                "updated_at" => date("Y-m-d")
            ];

            DB::table("t_article{$year}")->insert($insert);

            DB::commit();

            $response = $request->all();
            return response()->json(['data' => $response]);
        } catch (Exception $e) {
            DB::rollBack();
            abort(500, $e->getMessage());
        }
    }


    /**
     * @param Request $request
     * @return void
     */
    public function getAllStation(Request $request)
    {

        $response = [];

        //---------------------------------------//
        $trains = [];
        $result2 = DB::table('t_train')->get();
        foreach ($result2 as $v) {
            $trains[$v->train_number] = $v->train_name;
        }
        //---------------------------------------//

        $result = DB::table('t_station')
            ->orderBy('id')
            ->get();

        $ary = [];
        foreach ($result as $k => $v) {
            $ary[] = [
                'id' => $v->id,
                'station_name' => $v->station_name,
                'address' => $v->address,
                'lat' => $v->lat,
                'lng' => $v->lng,
                'line_number' => $v->train_number,
                'line_name' => $trains[$v->train_number],
            ];
        }

        $response = $ary;

        return response()->json(['data' => $response]);

    }


    /**
     * @param Request $request
     * @return void
     */
    public function getMetropolitanPark()
    {
        $response = [];

        $result = DB::table('t_metropolitan_park')->get();

        $ary = [];
        foreach ($result as $v) {
            $ary[] = [
                "id" => $v->id,
                "name" => $v->name,
                "address" => $v->address,
                "latitude" => $v->latitude,
                "longitude" => $v->longitude,
            ];
        }

        $response = $ary;

        return response()->json(['data' => $response]);
    }


    /**
     * @param Request $request
     * @return void
     */
    public function getSameYearMonthDay(Request $request)
    {
        $response = [];

        list($year, $month, $day) = explode("-", $request->date);

        $file = public_path() . "/mySetting/MoneyTotal.data";
        $content = file_get_contents($file);
        $ex_content = explode("\n", $content);

        //---------------------------------------------//
        $ary = [];
        foreach ($ex_content as $k => $v) {
            if (trim($v) == "") {
                continue;
            }

            list($date, $x, $total, $spend) = explode("|", trim($v));
            list($mYear, $mMonth, $mDay) = explode("-", $date);

            if ($mYear < 2020) {
                continue;
            }

            if (preg_match("/01-01/", $date)) {
                $ary[$mYear]['start'] = $k;
            }
        }

        foreach ($ex_content as $k => $v) {
            if (trim($v) == "") {
                continue;
            }

            list($date, $x, $total, $spend) = explode("|", trim($v));
            list($mYear, $mMonth, $mDay) = explode("-", $date);

            if ($mYear < 2020) {
                continue;
            }

            if (preg_match("/{$month}-{$day}/", $date)) {
                $ary[$mYear]['end'] = $k;
            }
        }
        //---------------------------------------------//

        $ary3 = [];
        $result = DB::table('t_salary')->get();
        foreach ($result as $v) {
            $ary3["{$v->year}-{$v->month}"][] = $v->salary;
        }

        $ary4 = [];
        foreach ($ary3 as $ym => $v) {
            $ary4[$ym] = array_sum($v);
        }

        ////////////////////////////
        $ary2 = [];
        foreach ($ary as $year => $v) {
            $sum = 0;
            $sal = 0;


            if (isset($v['start']) && isset($v['end'])) {
                for ($i = $v['start']; $i <= $v['end']; $i++) {
                    list($date, $x, $total, $spend) = explode("|", trim($ex_content[$i]));
                    list($mYear, $mMonth, $mDay) = explode("-", $date);

                    if ($mDay == "01") {
                        if (isset($ary4["{$mYear}-{$mMonth}"])) {
                            $sal += $ary4["{$mYear}-{$mMonth}"];
                        }
                    }

                    $sum += $spend;
                }
            }

            $ary2[] = [
                "year" => $year,
                "spend" => $sum,
                "salary" => $sal
            ];
        }

        ////////////////////////////


        $response = $ary2;

        return response()->json(['data' => $response]);
    }


    /**
     * @return void
     */
    public function goshuin()
    {

        $response = [];

        ///////////////////////////////////////////////
        $_tables = [];

        $sql = "SELECT table_name FROM information_schema.tables WHERE table_schema = database();";
        $result = DB::select($sql);

        foreach ($result as $v) {
            if (preg_match("/t_article/", $v->table_name)) {
                $_tables[] = $v->table_name;
            }
        }
        ///////////////////////////////////////////////

        $_sql = [];
        foreach ($_tables as $table) {
            $_sql[] = " select * from " . $table . " where article like '%御朱印%' ";
        }
        $sql = implode(" union all ", $_sql);
        $result = DB::select($sql);

        $ary = [];

        foreach ($result as $v2) {
            $exV2 = explode("\n", $v2->article);
            foreach ($exV2 as $v3) {

                if (trim($v3) == '===') {
                    continue;
                }

                $exV3 = explode("\t", trim($v3));

                if (count($exV3) == 1) {
                    continue;
                }

                $_lat = "";
                $_lng = "";

                try {

                    $url9 = "https://maps.googleapis.com/maps/api/geocode/json?address=" . trim($exV3[2]) . "&components=country:JP&key=AIzaSyD9PkTM1Pur3YzmO-v4VzS0r8ZZ0jRJTIU";

                    $content9 = file_get_contents($url9);
                    $jsonStr = json_decode($content9);

                    if (isset($jsonStr->results[0]->geometry->location->lat) and trim($jsonStr->results[0]->geometry->location->lat) != "") {
                        $_lat = $jsonStr->results[0]->geometry->location->lat;
                    }

                    if (isset($jsonStr->results[0]->geometry->location->lng) and trim($jsonStr->results[0]->geometry->location->lng) != "") {
                        $_lng = $jsonStr->results[0]->geometry->location->lng;
                    }

                } catch (Exception $e) {
                }

                $ary[] = [
                    "id" => trim($exV3[0]),
                    "name" => trim($exV3[1]),
                    "address" => trim($exV3[2]),
                    "lat" => $_lat,
                    "lng" => $_lng,
                    "flag" => trim($exV3[3]),
                ];
            }
        }

        $response = $ary;

        return response()->json(['data' => $response]);

    }


    /**
     * @return void
     */
    public function templeNotReached()
    {
        $response = [];

        $response = $this->geTempleNearStation();

        return response()->json(['data' => $response]);
    }


    /**
     * @return void
     */
    private function geTempleNearStation()
    {


        $ary = [];

        //============================//
        $latLngTemple_name = [];
        $latLngTemple_address = [];
        $latLngTemple_latLng = [];
        $result = DB::table('t_temple_latlng')->get();
        foreach ($result as $v) {
            $latLngTemple_name[] = $v->temple;
            $latLngTemple_address[] = strtr($v->address, ['東京都' => '']);
            $latLngTemple_latLng[] = "{$v->lat}|{$v->lng}";
        }
        //============================//

        $result = DB::table('t_temple_list')->get();
        foreach ($result as $v) {

            if (in_array($v->name, $latLngTemple_name)) {
                continue;
            }

            if (in_array($v->address, $latLngTemple_address)) {
                continue;
            }

            if (in_array("{$v->lat}|{$v->lng}", $latLngTemple_latLng)) {
                continue;
            }

            $ary[] = [
                'id' => $v->id,
                'city' => $v->city,
                'jinjachou_id' => $v->jinjachou_id,
                'url' => $v->url,
                'name' => $v->name,
                'address' => $v->address,
                'lat' => $v->lat,
                'lng' => $v->lng,
                'near_station' => $v->near_station,
            ];
        }


        return $ary;


    }


    /**
     * @return void
     */
    public function nearStation()
    {
        $response = [];

        $staId = [];
        $result = DB::table('t_temple_list')->orderBy('id')->get();
        foreach ($result as $v) {
            $exNearStation = explode(",", trim($v->near_station));
            foreach ($exNearStation as $v2) {
                if (trim($v2) == "") {
                    continue;
                }
                $staId[trim($v2)] = "";
            }
        }

        $sid = array_keys($staId);
        sort($sid);


        $ary = $sid;

        $response = $ary;

        return response()->json(['data' => $response]);
    }


    public function notReachedTempleStation()
    {
        $response = [];


        ////////////////////////////////////////
        $train = [];
        $result = DB::table('t_train')->get();
        foreach ($result as $v) {
            $train[$v->train_number] = $v->train_name;
        }
        ////////////////////////////////////////

        $ary = [];
        $ary4 = [];
        $templeNearStation = $this->geTempleNearStation();
        foreach ($templeNearStation as $v) {
            if (trim($v['near_station']) == "") {
                continue;
            }

            $exNearStation = explode(",", $v['near_station']);
            foreach ($exNearStation as $v2) {
                $exV2 = explode("-", trim($v2));
                $ary[$exV2[0]] = "";

                $ary4[trim($v2)][] = "";
            }
        }

        $ary2 = array_keys($ary);
        sort($ary2);

        $ary3 = [];
        foreach ($ary2 as $v) {
            $result2 = DB::table('t_station')
                ->where('train_number', $v)
                ->orderBy('id')
                ->get();


            $ary5 = [];
            foreach ($result2 as $v2) {
                $tra_sta = $v . "-" . $v2->id;

                if (isset($ary4[$tra_sta])) {
                    $ary5[] = [
                        "station_id" => $tra_sta,
                        "station_name" => $v2->station_name,
                        "address" => $v2->address,
                        "lat" => $v2->lat,
                        "lng" => $v2->lng,
                        "count" => count($ary4[$tra_sta]),
                    ];
                }
            }

            if (count($ary5) > 0) {
                $ary3[] = [
                    "train_number" => $v,
                    "train_name" => $train[$v],
                    "list" => $ary5,
                ];
            }
        }

        $response = $ary3;

        return response()->json(['data' => $response]);
    }


    /**
     * @return void
     */
    public function getTempleDatePhoto()
    {
        $response = [];

        ///////////////////////////////////////////////
        $_tables = [];

        $sql = "SELECT table_name FROM information_schema.tables WHERE table_schema = database();";
        $result = DB::select($sql);

        foreach ($result as $v) {
            if (preg_match("/t_article/", $v->table_name)) {
                $_tables[] = $v->table_name;
            }
        }
        ///////////////////////////////////////////////


        $ary = [];
        foreach ($_tables as $table) {
            $result = DB::table($table)
                ->where("article", "like", "%神社写真%")
                ->get();

            foreach ($result as $v) {
                $date = "{$v->year}-{$v->month}-{$v->day}";

                $exArticle = explode("\n", $v->article);
                $photos = [];
                $temple = '';

                foreach ($exArticle as $k => $atcl) {

                    if (trim($atcl) == '---') {
                        $photos = [];
                        $temple = trim($exArticle[$k + 1]);
                    }

                    if (preg_match("/^http/", trim($atcl))) {
                        $photos[] = strtr(trim($atcl), ['160.16.145.135' => 'toyohide.work']);
                    }

                    if (count($photos) > 0) {
                        $ary["{$date}|{$temple}"] = $photos;
                    }
                }
            }
        }

        $ary2 = [];
        foreach ($ary as $dt => $photos) {
            list($date, $temple) = explode("|", $dt);
            $ary2[] = [
                'date' => $date,
                'temple' => $temple,
                'templephotos' => $photos,
            ];
        }

        $response = $ary2;

        return response()->json(['data' => $response]);
    }


}
