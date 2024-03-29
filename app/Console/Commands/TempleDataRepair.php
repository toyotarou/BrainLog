<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use DB;

class TempleDataRepair extends Command
{

    protected $signature = 'TempleDataRepair';

    protected $description = 'Command description';

    public function handle()
    {

        $str = "
2014/8/8,愛宕神社,,火産霊命,東京都港区愛宕1-5-3,新橋駅
2014/8/30,小網神社,,倉稲魂神,東京都中央区日本橋小網町16-23,茅場町駅
2014/10/14,下総国一宮　香取神宮,,経津主大神,千葉県香取市香取1697-1,香取駅
2015/1/1,日光東照宮,,徳川家康公,栃木県日光市山内2301,東武日光駅
2015/5/30,善光寺,,,長野県長野市元善町491,善光寺下駅
2015/11/14,浅草寺,,,東京都台東区浅草2-3-1,浅草駅
2016/1/1,増上寺,,,東京都港区芝公園4-7-35,大門駅
2016/4/24,熱田神宮,,熱田大神,愛知県名古屋市熱田区神宮1-1-1,熱田駅
2016/5/5,亀戸天神社,,天満大神,東京都江東区亀戸3-6-1,亀戸駅
2016/10/10,湯島聖堂,,,東京都文京区湯島1-4-25,御茶ノ水駅
2017/1/1,増上寺,,,東京都港区芝公園4-7-35,大門駅
2017/3/11,武蔵国一宮　氷川神社,,須佐之男命,埼玉県さいたま市大宮区高鼻町1-407,大宮駅
2017/6/3,根津神社,湯島神社,須佐之男命,東京都文京区根津1-28-9,上野駅
2017/6/24,日枝神社,,大山咋神,東京都千代田区永田町2-10-5,赤坂見附駅
2017/6/27,神田明神,靖国神社,大己貴命,東京都千代田区外神田2-16-2,秋葉原駅
2017/7/8,乃木神社,,乃木希典将軍,東京都港区赤坂8-11-27,乃木坂駅
2017/8/12,東伏見稲荷神社,,宇迦御魂大神,東京都西東京市東伏見1-5-38,西武柳沢駅
2017/8/14,高野山東京別院,,弘法大師,東京都港区高輪3-15-18,品川駅
2017/11/5,小野照崎神社,,小野篁公,東京都台東区下谷2-13-14,日暮里駅
2017/12/23,皆中稲荷神社,,宇迦之御魂大神,東京都新宿区百人町1-11-16,新大久保駅
2018/1/1,上総国一宮　玉前神社,,玉依姫命,千葉県長生郡一宮町一宮3048,上総一ノ宮駅
2018/1/2,井草八幡宮,,八幡大神,東京都杉並区善福寺1-33-1,荻窪駅
2018/1/3,杵築大社,井の頭弁財天（大盛寺）,大國主大神,東京都武蔵野市境南町2-10-11,武蔵境駅
2018/1/13,東京大神宮,,天照皇大神,東京都千代田区富士見2-4-1,飯田橋駅
2018/4/1,常陸国一宮　鹿島神宮,,武甕槌大神,茨城県鹿嶋市宮中2306-1,鹿島神宮駅
2018/5/3,成田山新勝寺,,,千葉県成田市成田1,成田駅
2018/5/4,柴又帝釈天,,,東京都葛飾区柴又7-10-3,柴又駅
2018/5/26,秩父聖神社,慈眼寺、知知夫国一宮　秩父神社、小林寺,金山彦命,埼玉県秩父市黒谷2191,和銅黒谷駅
2018/6/23,川崎大師,,弘法大師,神奈川県川崎市川崎区大師町4-48,川崎大師駅
2018/7/10,東京大神宮,,天照皇大神,東京都千代田区富士見2-4-1,飯田橋駅
2018/7/14,亀戸浅間神社,,木花之佐久夜毘売命,東京都江東区亀戸9-15-7,東大島駅
2018/8/11,東大島神社,,天照皇大神,東京都千代田区富士見2-4-1,飯田橋駅
2018/8/14,深大寺,,,東京都調布市深大寺元町5-15-1,吉祥寺駅
2018/8/15,新井薬師,,薬師如来,東京都中野区新井5-3-5,新井薬師前駅
2018/10/27,浅草寺,,,東京都台東区浅草2-3-1,浅草駅
2018/11/3,浄光寺,,,千葉県市川市大野町3-1917,市川大野駅
2018/11/17,湯島天神,深川不動尊、富岡八幡宮,天之手力雄命,東京都文京区湯島3-30-1,湯島駅
2018/11/23,白山神社,薬研堀不動尊,菊理媛大神,東京都文京区白山5-31-26,白山駅
2018/12/1,福徳神社,,宇迦御魂大神,東京都中央区日本橋室町2-4-14,三越前駅
2019/1/1,安房国一宮　安房神社,,天太玉命,千葉県館山市大神宮589,館山駅
2019/1/2,井草八幡宮,,八幡大神,東京都杉並区善福寺1-33-1,荻窪駅
2019/2/11,竹下稲荷神社,,蒼稲魂命,東京都練馬区関町南2-3-22,上石神井駅
2019/2/16,品川貴船神社,,高龍神,東京都品川区西品川3-16-31,大崎駅
2019/3/2,杉並大宮八幡宮,,八幡大神,東京都杉並区大宮2-3-1,永福町駅
2019/3/17,品川神社,波除神社、築地本願寺、小網神社,天比理乃咩命,東京都品川区北品川3-7-15,北品川駅
2019/3/30,善光寺,豊川稲荷、日枝神社、愛宕神社,,東京都港区北青山3-5-17,表参道駅
2019/4/6,西新井大師,,十一面観世音菩薩,東京都足立区西新井1-15-1,大師前駅
2019/4/20,三河島稲荷,牛嶋神社,倉稲魂命,東京都荒川区荒川3-66-5,三河島駅
2019/4/27,高岩寺,神田明神,,東京都豊島区巣鴨3-35-2,巣鴨駅
2019/4/30,下加茂神社,晴明神社、御金神社、伏見稲荷大社、清水寺,賀茂建角身命,京都府京都市左京区下鴨泉川町59,京都駅
2019/5/3,光前寺,,,長野県駒ヶ根市赤穂29,駒ヶ根駅
2019/5/19,香取神社,,経津主大神,東京都江東区亀戸3-57-22,亀戸駅
2019/9/7,東京大神宮,,天照皇大神,東京都千代田区富士見2-4-1,飯田橋駅
2019/9/21,光専寺,,,東京都武蔵野市吉祥寺本町1-10-21,吉祥寺駅
2019/9/28,神田明神,,大己貴命,東京都千代田区外神田2-16-2,秋葉原駅
2019/11/6,法華経寺,,,千葉県市川市中山2-10-1,下総中山駅
2019/11/10,葛飾八幡宮,,誉田別命,千葉県市川市八幡4-2-1,本八幡駅
2019/11/18,三峰神社,,伊弉諾尊,埼玉県秩父市三峰298-1,三峰口駅
2019/11/26,常陸国一宮　鹿島神宮,成田山新勝寺,武甕槌大神,茨城県鹿嶋市宮中2306-1,鹿島神宮駅
2019/12/29,花園神社,,倉稲魂命,東京都新宿区新宿5-17-3,新宿駅
2020/1/1,北口本宮冨士浅間神社,,浅間大神,山梨県富士吉田市上吉田5558,富士山駅
2020/1/2,井草八幡宮,大和市神社,八幡大神,東京都杉並区善福寺1-33-1,荻窪駅
2020/1/3,大法寺,武蔵野八幡宮、安養寺、大盛寺、杵築大社、延命寺,,東京都武蔵野市吉祥寺東町2-9-13,吉祥寺駅
2020/1/4,小網神社,茶の木神社、水天宮、松島神社、末廣神社、笠間稲荷神社、椙森神社、寶田恵比寿神社,倉稲魂神,東京都中央区日本橋小網町16-23,茅場町駅
2020/1/19,杉並大宮八幡宮,,八幡大神,東京都杉並区大宮2-3-1,永福町駅
2020/1/22,日枝神社,豊川稲荷,大山咋神,東京都千代田区永田町2-10-5,赤坂見附駅
2020/1/25,蛇窪神社,芝大神宮,天照大神,東京都品川区二葉4-4-12,西大井駅
2020/2/1,浅草寺,,,東京都台東区浅草2-3-1,浅草駅
2020/2/15,東京大神宮,,天照皇大神,東京都千代田区富士見2-4-1,飯田橋駅
2020/2/24,大國魂神社,,大國魂大神,東京都府中市宮町3-1,府中駅
2020/3/7,今戸神社,,応神天皇,東京都台東区今戸1-5-22,浅草駅
2020/3/20,清光院,素戔嗚神社、八幡神社、三嶋神社、濟廣寺、伊豆山神社,黄金蛇石,静岡県賀茂郡東伊豆町稲取344,伊豆稲取駅
2020/4/11,南沢氷川神社,田無神社,素盞嗚命,東京都東久留米市南沢3-5-8,東久留米駅
2020/4/12,荻窪八幡神社,,誉田別命,東京都杉並区上荻4-19-2,荻窪駅
2020/4/21,船橋大神宮,,天照皇大御神,千葉県船橋市宮本5-2-1,船橋駅
2020/4/22,亀戸天神社,,天満大神,東京都江東区亀戸3-6-1,亀戸駅
2020/4/23,葛西神社,柴又帝釈天,経津主神,東京都葛飾区東金町6-10-5,金町駅
2020/4/24,徳大寺,,,東京都台東区上野4-6-2,御徒町駅
2020/4/27,将門塚,,平将門公,東京都千代田区大手町1-2-1,大手町駅
2020/5/3,新宿下落合氷川神社,,素盞嗚命,東京都新宿区下落合2-7-12,高田馬場駅
2020/5/14,代々木八幡宮,金王八幡宮,応神天皇,東京都渋谷区代々木5-1-1,渋谷駅
2020/5/17,寒川神社,宮山神社,寒川比古命,神奈川県高座郡寒川町宮山3916,宮山駅
2020/5/31,千葉神社,稲毛浅間神社、検見川神社、船橋大神宮,天之御中主大神,千葉県千葉市中央区院内1-16-1,千葉駅
2020/6/20,明治神宮,鳩森八幡宮,明治天皇,東京都渋谷区代々木神園町1-1,原宿駅
2020/7/2,東京大神宮,,天照皇大神,東京都千代田区富士見2-4-1,飯田橋駅
2020/7/4,正八幡神社,,品陀和気尊,東京都文京区関口2-3-21,江戸川橋駅
2020/7/19,葛飾八幡宮,,誉田別命,千葉県市川市八幡4-2-1,本八幡駅
2020/8/10,深大寺,,,東京都調布市深大寺元町5-15-1,吉祥寺駅
2020/9/29,靖国神社,日枝神社,護国の英霊,東京都千代田区九段北3-1-1,九段下駅
2020/10/1,銀杏岡八幡神社,,誉田別皇命,東京都台東区浅草橋1-29-11,浅草橋駅
2020/10/17,成田山新勝寺,,,千葉県成田市成田1,成田駅
2020/11/7,葛西神社,,経津主神,東京都葛飾区東金町6-10-5,金町駅
2020/11/14,芝大神宮,小網神社,天照皇大御神,東京都港区芝大門1-12-7,大門駅
2020/11/26,金網稲荷神社,,宇迦之御魂神,東京都千代田区神田和泉町1,秋葉原駅
2020/11/28,芝大神宮,,天照皇大御神,東京都港区芝大門1-12-7,大門駅
2020/12/8,雷電稲荷神社,,受持神,東京都新宿区新宿4-4-23,新宿駅
2020/12/12,山野浅間神社,,木花咲耶姫命,千葉県船橋市西船1-5-7,西船橋駅
2020/12/30,玉川神社,等々力不動尊,伊弉諾尊,東京都世田谷区等々力3-27-7,等々力駅
";

        $ex_str = explode("\n", $str);
        foreach ($ex_str as $v) {
            if (trim($v) == "") {
                continue;
            }
            $ex_v = explode(",", trim($v));

            $date = trim($ex_v[0]);
            $ex_date = explode("/", $date);
            $year = trim($ex_date[0]);
            $month = sprintf("%02d", trim($ex_date[1]));
            $day = sprintf("%02d", trim($ex_date[2]));

            $temple = trim($ex_v[1]);
            $memo = trim($ex_v[2]);
            $gohonzon = trim($ex_v[3]);
            $address = trim($ex_v[4]);
            $station = trim($ex_v[5]);

            $insert = [];
            $insert['year'] = $year;
            $insert['month'] = $month;
            $insert['day'] = $day;
            $insert['temple'] = $temple;
            $insert['memo'] = $memo;
            $insert['gohonzon'] = $gohonzon;
            $insert['address'] = $address;
            $insert['station'] = $station;

            print_r($insert);

            DB::table('t_temple')->insert($insert);
        }
    }
}
