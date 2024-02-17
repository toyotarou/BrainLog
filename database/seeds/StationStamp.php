<?php

use Illuminate\Database\Seeder;

class StationStamp extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {


        $str = "
5838,28001,渋谷,東京メトロ銀座線,G01,G,01,,,●,,銀座線　駅事務室付近（改札内）,●, ,2023/1/21
5837,28001,表参道,東京メトロ銀座線,G02,G,02,,,●,,青山通り方面改札付近（改札外）,●, ,2023/1/21
5836,28001,外苑前,東京メトロ銀座線,G03,G,03,,,●,,駅事務室付近（改札外）,●, ,2023/1/21
5835,28001,青山一丁目,東京メトロ銀座線,G04,G,04,,,●,,駅事務室付近（改札外）,●, ,2023/1/21
5834,28001,赤坂見附,東京メトロ銀座線,G05,G,05,,,●,,駅事務室付近（改札内）,●, ,2023/1/21
5833,28001,溜池山王,東京メトロ銀座線,G06,G,06,,,●,,駅事務室付近（改札内）,●, ,2023/1/21
5832,28001,虎ノ門,東京メトロ銀座線,G07,G,07,,,●,,1番出口付近（改札外）,●, ,2023/1/21
5831,28001,新橋,東京メトロ銀座線,G08,G,08,,,●,,3番出口付近（改札外）,●, ,2022/12/3
5830,28001,銀座,東京メトロ銀座線,G09,G,09,,,●,,駅事務室付近（改札内）,●, ,2023/1/8
5829,28001,京橋,東京メトロ銀座線,G10,G,10,京橋　駅事務室付近（改札外）,,,,駅事務室付近（改札外）,, ,
5828,28001,日本橋,東京メトロ銀座線,G11,G,11,,,●,,A6出口付近（改札外）,●, ,2023/1/28
5827,28001,三越前,東京メトロ銀座線,G12,G,12,,,●,,銀座線　駅事務室付近（改札外） / B5出口付近（改札外）,●, ,2023/1/28
5826,28001,神田,東京メトロ銀座線,G13,G,13,,,●,,３番出口付近（改札外）,●, ,2023/1/28
5825,28001,末広町,東京メトロ銀座線,G14,G,14,,,●,,2番出口付近（改札外） / 3番出口付近（改札外）,●, ,2023/1/28
5824,28001,上野広小路,東京メトロ銀座線,G15,G,15,,,●,,A1出口方面付近（改札外）,●, ,2023/1/28
5823,28001,上野,東京メトロ銀座線,G16,G,16,,,●,,駅事務室付近（改札外）,●, ,2023/1/28
5822,28001,稲荷町,東京メトロ銀座線,G17,G,17,,,●,,1番出口方面付近（改札外）,●, ,2023/1/28
5821,28001,田原町,東京メトロ銀座線,G18,G,18,,,●,,西浅草１丁目側エレベーター専用改札付近（改札外）,●, ,2023/1/28
5820,28001,浅草,東京メトロ銀座線,G19,G,19,,,●,,吾妻橋方面改札付近（改札外）,●, ,2023/1/28
5863,28002,荻窪,東京メトロ丸ノ内線,M01,M,01,,,●,,駅事務室付近（改札外）,●, ,2022/12/30
5862,28002,南阿佐ケ谷,東京メトロ丸ノ内線,M02,M,02,,,●,,杉並郵便局方面改札付近（改札外）,●, ,2022/12/30
5861,28002,新高円寺,東京メトロ丸ノ内線,M03,M,03,,,●,,駅事務室付近（改札外）,●, ,2022/12/30
5860,28002,東高円寺,東京メトロ丸ノ内線,M04,M,04,,,●,,蚕糸の森公園方面改札付近（改札外）,●, ,2022/12/30
5859,28002,新中野,東京メトロ丸ノ内線,M05,M,05,,,●,,鍋屋横丁交差点南改札付近（改札内）,●, ,2022/12/30
5858,28002,中野坂上,東京メトロ丸ノ内線,M06,M,06,,,●,,駅事務室付近（改札外）,●, ,2022/12/30
5857,28002,西新宿,東京メトロ丸ノ内線,M07,M,07,,,●,,駅事務室付近（改札外）,●, ,2022/12/30
5856,28002,新宿,東京メトロ丸ノ内線,M08,M,08,,,●,,東改札付近（改札外）,●, ,2022/12/30
5855,28002,新宿三丁目,東京メトロ丸ノ内線,M09,M,09,新宿三丁目　丸ノ内線　駅事務室付近（改札外）　E5出口付近（改札外）,,,,丸ノ内線　駅事務室付近（改札外） / E5出口付近（改札外）,, ,
5854,28002,新宿御苑前,東京メトロ丸ノ内線,M10,M,10,新宿御苑前　新宿門方面改札付近（改札内）,,,,新宿門方面改札付近（改札内）,, ,
5853,28002,四谷三丁目,東京メトロ丸ノ内線,M11,M,11,四谷三丁目　駅事務室付近（改札外）,,,,駅事務室付近（改札外）,, ,
5852,28002,四ツ谷,東京メトロ丸ノ内線,M12,M,12,四ツ谷　赤坂方面改札付近（改札内）　南北線　駅事務室付近（改札内）,,,,,, ,
5851,28002,赤坂見附,東京メトロ丸ノ内線,M13,M,13,,,●,,駅事務室付近（改札内）,●, ,2023/1/21
5850,28002,国会議事堂前,東京メトロ丸ノ内線,M14,M,14,国会議事堂前　3番出口付近（改札外）,,,,3番出口付近（改札外）,, ,
5849,28002,霞ケ関,東京メトロ丸ノ内線,M15,M,15,,,●,,丸ノ内線 桜田通り方面改札付近（改札内） / 千代田線 駅事務室付近（改札内）,●, ,2023/1/15
5848,28002,銀座,東京メトロ丸ノ内線,M16,M,16,,,●,,駅事務室付近（改札内）,●, ,2023/1/8
5847,28002,東京,東京メトロ丸ノ内線,M17,M,17,東京　中央改札きっぷうりば付近（改札外）,,,,中央改札きっぷうりば付近（改札外）,, ,
5846,28002,大手町,東京メトロ丸ノ内線,M18,M,18,,,●,,東西線 駅事務室付近（改札外） / 半蔵門線 駅事務室付近（改札外）,●, ,2023/1/21
5845,28002,淡路町,東京メトロ丸ノ内線,M19,M,19,淡路町　小川町方面改札付近（改札外）,,,,小川町方面改札付近（改札外）,, ,
5844,28002,御茶ノ水,東京メトロ丸ノ内線,M20,M,20,御茶ノ水　１番出口付近（改札外）,,,,１番出口付近（改札外）,, ,
5843,28002,本郷三丁目,東京メトロ丸ノ内線,M21,M,21,本郷三丁目　駅事務室付近（改札内）,,,,駅事務室付近（改札内）,, ,
5842,28002,後楽園,東京メトロ丸ノ内線,M22,M,22,後楽園　南北線　駅事務室付近（改札外）,,,,南北線　駅事務室付近（改札外）,, ,
5841,28002,茗荷谷,東京メトロ丸ノ内線,M23,M,23,茗荷谷　春日通り方面改札付近（改札内）,,,,春日通り方面改札付近（改札内）,, ,
5840,28002,新大塚,東京メトロ丸ノ内線,M24,M,24,新大塚　北改札付近（改札外）,,,,北改札付近（改札外）,, ,
5839,28002,池袋,東京メトロ丸ノ内線,M25,M,25,,,●,,西通路西改札付近(改札外) / 有楽町線　駅事務室付近(改札外）,●, ,2023/1/3
5866,28002,方南町,東京メトロ丸ノ内線,Mb03,M,b03,,,●,,駅事務室付近（改札外）,●, ,2022/1/30
5865,28002,中野富士見町,東京メトロ丸ノ内線,Mb04,M,b04,,,●,,改札付近（改札内）,●, ,2022/1/30
5864,28002,中野新橋,東京メトロ丸ノ内線,Mb05,M,b05,,,●,,改札付近（改札内）,●, ,2022/1/30
5888,28003,中目黒,東京メトロ日比谷線,H01,H,01,中目黒　恵比寿駅 ２番出口付近（改札外）,,,,恵比寿駅 ２番出口付近（改札外）,, ,
5887,28003,恵比寿,東京メトロ日比谷線,H02,H,02,恵比寿　２番出口付近（改札外）,,,,２番出口付近（改札外）,, ,
5886,28003,広尾,東京メトロ日比谷線,H03,H,03,広尾　４番出口付近（改札外）,,,,４番出口付近（改札外）,, ,
5885,28003,六本木,東京メトロ日比谷線,H04,H,04,,,●,,駅事務室付近（改札外）,●, ,2023/1/21
5884,28003,神谷町,東京メトロ日比谷線,H05,H,05,,,●,,虎ノ門方面改札付近（改札外）,●, ,2023/1/21
5883,28003,虎ノ門ヒルズ,東京メトロ日比谷線,H06,H,06,,,●,,駅事務室付近（改札外）,●, ,2023/1/21
5882,28003,霞ケ関,東京メトロ日比谷線,H07,H,07,,,●,,丸ノ内線 桜田通り方面改札付近（改札内） / 千代田線 駅事務室付近（改札内）,●, ,2023/1/15
5881,28003,日比谷,東京メトロ日比谷線,H08,H,08,,,●,,駅事務室付近（改札外）,●, ,2023/1/21
5880,28003,銀座,東京メトロ日比谷線,H09,H,09,,,●,,駅事務室付近（改札内）,●, ,2023/1/8
5879,28003,東銀座,東京メトロ日比谷線,H10,H,10,東銀座　4番出口付近（改札外）,,,,4番出口付近（改札外）,, ,
5878,28003,築地,東京メトロ日比谷線,H11,H,11,築地　３ｂ出口付近（改札外）,,,,３ｂ出口付近（改札外）,, ,
5877,28003,八丁堀,東京メトロ日比谷線,H12,H,12,八丁堀　八丁堀交差点方面改札付近（改札外）,,,,八丁堀交差点方面改札付近（改札外）,, ,
5876,28003,茅場町,東京メトロ日比谷線,H13,H,13,,,●,,８番出口付近（改札外）,●, ,2022/12/3
5875,28003,人形町,東京メトロ日比谷線,H14,H,14,,,●,,駅事務室付近（改札外）,●, ,2023/1/28
5874,28003,小伝馬町,東京メトロ日比谷線,H15,H,15,小伝馬町　本町方面改札付近（改札外）,,,,本町方面改札付近（改札外）,, ,
5873,28003,秋葉原,東京メトロ日比谷線,H16,H,16,,,●,,駅事務室付近（改札外）,●, ,2023/1/28
5872,28003,仲御徒町,東京メトロ日比谷線,H17,H,17,仲御徒町　中央改札付近（改札内）,,,,中央改札付近（改札内）,, ,
5871,28003,上野,東京メトロ日比谷線,H18,H,18,,,●,,駅事務室付近（改札外）,●, ,2023/1/28
5870,28003,入谷,東京メトロ日比谷線,H19,H,19,,,●,,４番出口付近（改札外）,●, ,2023/1/28
5869,28003,三ノ輪,東京メトロ日比谷線,H20,H,20,,,●,,駅事務室付近（改札外）,●, ,2023/1/28
5868,28003,南千住,東京メトロ日比谷線,H21,H,21,,,●,,駅事務室付近（改札内）,●, ,2023/1/22
5867,28003,北千住,東京メトロ日比谷線,H22,H,22,,,●,,千代田線 駅事務室付近（改札内）,●, ,2023/1/22
5889,28004,中野,東京メトロ東西線,T01,T,01,,,●,,落合駅 1番出口付近（改札外）,●, ,2022/12/10
5890,28004,落合,東京メトロ東西線,T02,T,02,,,●,,1番出口付近（改札外）,●, ,2022/12/10
5891,28004,高田馬場,東京メトロ東西線,T03,T,03,,,●,,5番出口付近（改札外）,●, ,2022/1/29
5892,28004,早稲田,東京メトロ東西線,T04,T,04,早稲田　早稲田大学方面改札付近（改札外）　早稲田大学穴八幡方面改札付近（改札外）,,,,早稲田大学方面改札付近（改札外） / 早稲田大学穴八幡方面改札付近（改札外）,, ,
5893,28004,神楽坂,東京メトロ東西線,T05,T,05,神楽坂　駅事務室付近（改札内）,,,,駅事務室付近（改札内）,, ,
5894,28004,飯田橋,東京メトロ東西線,T06,T,06,,,●,,飯田橋交差点方面改札付近（改札内） / 有楽町線・南北線駅事務室付近（改札外）,●, ,2023/1/4
5895,28004,九段下,東京メトロ東西線,T07,T,07,九段下　駅事務室付近（改札外）,,,,駅事務室付近（改札外）,, ,
5896,28004,竹橋,東京メトロ東西線,T08,T,08,竹橋　大手町方面改札付近（改札外）,,,,大手町方面改札付近（改札外）,, ,
5897,28004,大手町,東京メトロ東西線,T09,T,09,,,●,,東西線 駅事務室付近（改札外） / 半蔵門線 駅事務室付近（改札外）,●, ,2023/1/21
5898,28004,日本橋,東京メトロ東西線,T10,T,10,,,●,,A6出口付近（改札外）,●, ,2023/1/28
5899,28004,茅場町,東京メトロ東西線,T11,T,11,,,●,,８番出口付近（改札外）,●, ,2022/12/3
5900,28004,門前仲町,東京メトロ東西線,T12,T,12,,,●,,富岡八幡宮方面改札付近(改札内),●, ,2023/1/12
5901,28004,木場,東京メトロ東西線,T13,T,13,木場　駅事務室付近（改札外）,,,,駅事務室付近（改札外）,, ,
5902,28004,東陽町,東京メトロ東西線,T14,T,14,,,●,,西改札付近（改札内）,●, ,2023/1/11
5903,28004,南砂町,東京メトロ東西線,T15,T,15,,,●,,駅事務室付近（改札内）,●, ,2023/1/19
5904,28004,西葛西,東京メトロ東西線,T16,T,16,西葛西　駅事務室付近（改札外）,,,,駅事務室付近（改札外）,, ,
5905,28004,葛西,東京メトロ東西線,T17,T,17,葛西　西口出口付近（改札外）,,,,西口出口付近（改札外）,, ,
5906,28004,浦安,東京メトロ東西線,T18,T,18,,,●,,２階 西船橋方面ホーム行階段下付近（改札内）,●, ,2023/1/8
5907,28004,南行徳,東京メトロ東西線,T19,T,19,,,●,,駅事務室付近（改札内）,●, ,2023/1/18
5908,28004,行徳,東京メトロ東西線,T20,T,20,,,●,,改札付近（改札内）,●, ,2023/1/11
5909,28004,妙典,東京メトロ東西線,T21,T,21,,,●,,２階 中野方面ホーム行階段下付近（改札内）,●, ,2023/1/12
5910,28004,原木中山,東京メトロ東西線,T22,T,22,,,●,,駅事務室付近（改札外）,●, ,2023/1/21
5911,28004,西船橋,東京メトロ東西線,T23,T,23,,,●,,原木中山駅 事務室付近（改札外）,●, ,2023/1/21
5931,28005,代々木上原,東京メトロ千代田線,C01,C,01,,,●,,代々木公園駅 2番出口付近（改札外）,●, ,2023/1/21
5930,28005,代々木公園,東京メトロ千代田線,C02,C,02,,,●,,2番出口付近（改札外）,●, ,2023/1/21
5929,28005,明治神宮前〈原宿〉,東京メトロ千代田線,C03,C,03,,,●,,千代田線　駅事務室付近（改札外）,●, ,2023/1/21
5928,28005,表参道,東京メトロ千代田線,C04,C,04,,,●,,青山通り方面改札付近（改札外）,●, ,2023/1/21
5927,28005,乃木坂,東京メトロ千代田線,C05,C,05,乃木坂　外苑東通り方面改札付近（改札内）,,,,外苑東通り方面改札付近（改札内）,, ,
5926,28005,赤坂,東京メトロ千代田線,C06,C,06,赤坂　1番・2番出口方面付近（改札外）,,,,1番・2番出口方面付近（改札外）,, ,
5925,28005,国会議事堂前,東京メトロ千代田線,C07,C,07,国会議事堂前　3番出口付近（改札外）,,,,3番出口付近（改札外）,, ,
5924,28005,霞ケ関,東京メトロ千代田線,C08,C,08,,,●,,丸ノ内線 桜田通り方面改札付近（改札内） / 千代田線 駅事務室付近（改札内）,●, ,2023/1/15
5923,28005,日比谷,東京メトロ千代田線,C09,C,09,,,●,,駅事務室付近（改札外）,●, ,2023/1/21
5922,28005,二重橋前,東京メトロ千代田線,C10,C,10,,,●,,駅事務室付近（改札外）,●, ,2023/1/21
5921,28005,大手町,東京メトロ千代田線,C11,C,11,,,●,,東西線 駅事務室付近（改札外） / 半蔵門線 駅事務室付近（改札外）,●, ,2023/1/21
5920,28005,新御茶ノ水,東京メトロ千代田線,C12,C,12,新御茶ノ水　駅事務室付近（改札外）,,,,駅事務室付近（改札外）,, ,
5919,28005,湯島,東京メトロ千代田線,C13,C,13,湯島　駅事務室付近（改札外）,,,,駅事務室付近（改札外）,, ,
5918,28005,根津,東京メトロ千代田線,C14,C,14,,,●,,根津交差点方面改札付近（改札内）,●, ,2023/2/25
5917,28005,千駄木,東京メトロ千代田線,C15,C,15,千駄木　団子坂方面改札付近（改札外）,,,,団子坂方面改札付近（改札外）,, ,
5916,28005,西日暮里,東京メトロ千代田線,C16,C,16,,,●,,道灌山方面改札付近（改札内）,●, ,2023/2/25
5915,28005,町屋,東京メトロ千代田線,C17,C,17,,,●,,町屋方面改札（改札外）,●, ,2023/2/25
5914,28005,北千住,東京メトロ千代田線,C18,C,18,,,●,,千代田線 駅事務室付近（改札内）,●, ,2023/1/22
5913,28005,綾瀬,東京メトロ千代田線,C19,C,19,,,●,,駅事務室付近（改札内）,●, ,2023/2/25
5912,28005,北綾瀬,東京メトロ千代田線,C20,C,20,,,●,,中央改札付近（改札外）,●, ,2023/2/25
5932,28006,和光市,東京メトロ有楽町線,Y01,Y,01,,,●,,地下鉄成増駅 事務室付近（改札外）,●, ,2023/1/3
5933,28006,地下鉄成増,東京メトロ有楽町線,Y02,Y,02,,,●,,駅事務室付近（改札外）,●, ,2023/1/3
5934,28006,地下鉄赤塚,東京メトロ有楽町線,Y03,Y,03,,,●,,駅事務室付近（改札外）,●, ,2023/1/3
5935,28006,平和台,東京メトロ有楽町線,Y04,Y,04,,,●,,駅事務室付近（改札外）,●, ,2023/1/3
5936,28006,氷川台,東京メトロ有楽町線,Y05,Y,05,,,●,,２番出口付近（改札外）,●, ,2023/1/3
5937,28006,小竹向原,東京メトロ有楽町線,Y06,Y,06,,,●,,4番出口付近（改札外）,●, ,2023/1/3
5938,28006,千川,東京メトロ有楽町線,Y07,Y,07,,,●,,駅事務室付近（改札外）,●, ,2023/1/3
5939,28006,要町,東京メトロ有楽町線,Y08,Y,08,,,●,,２番出口付近（改札外）,●, ,2023/1/3
5940,28006,池袋,東京メトロ有楽町線,Y09,Y,09,,,●,,西通路西改札付近(改札外) / 有楽町線　駅事務室付近(改札外）,●, ,2023/1/3
5941,28006,東池袋,東京メトロ有楽町線,Y10,Y,10,東池袋　駅事務室付近（改札外）,,,,駅事務室付近（改札外）,, ,
5942,28006,護国寺,東京メトロ有楽町線,Y11,Y,11,護国寺　音羽方面改札付近（改札外）,,,,音羽方面改札付近（改札外）,, ,
5943,28006,江戸川橋,東京メトロ有楽町線,Y12,Y,12,江戸川橋　駅事務室付近（改札外）,,,,駅事務室付近（改札外）,, ,
5944,28006,飯田橋,東京メトロ有楽町線,Y13,Y,13,,,●,,飯田橋交差点方面改札付近（改札内） / 有楽町線・南北線駅事務室付近（改札外）,●, ,2023/1/4
5945,28006,市ケ谷,東京メトロ有楽町線,Y14,Y,14,,,●,,市谷田町方面改札付近（改札内）,●, ,2023/1/12
5946,28006,麹町,東京メトロ有楽町線,Y15,Y,15,,,●,,6番出口方面付近（改札外）,●, ,2023/1/4
5947,28006,永田町,東京メトロ有楽町線,Y16,Y,16,永田町　紀尾井町方面改札付近（改札内）　駅事務室付近（改札内）,,,,紀尾井町方面改札付近（改札内） / 駅事務室付近（改札内）,, ,
5948,28006,桜田門,東京メトロ有楽町線,Y17,Y,17,,,●,,4番出口付近（改札外）,●, ,2023/1/21
5949,28006,有楽町,東京メトロ有楽町線,Y18,Y,18,,,●,,ＪＲ有楽町駅方面改札付近（改札外）,●, ,2023/1/21
5950,28006,銀座一丁目,東京メトロ有楽町線,Y19,Y,19,銀座一丁目　駅事務室付近（改札外）,,,,駅事務室付近（改札外）,, ,
5951,28006,新富町,東京メトロ有楽町線,Y20,Y,20,新富町　５番出口付近（改札外）,,,,５番出口付近（改札外）,, ,
5952,28006,月島,東京メトロ有楽町線,Y21,Y,21,月島　駅事務室付近（改札外）,,,,駅事務室付近（改札外）,, ,
5953,28006,豊洲,東京メトロ有楽町線,Y22,Y,22,豊洲　駅事務室付近（改札内）,,,,駅事務室付近（改札内）,, ,
5954,28006,辰巳,東京メトロ有楽町線,Y23,Y,23,辰巳　駅事務室付近（改札内）,,,,駅事務室付近（改札内）,, ,
5955,28006,新木場,東京メトロ有楽町線,Y24,Y,24,新木場　駅事務室付近（改札内）,,,,駅事務室付近（改札内）,, ,
5956,28008,渋谷,東京メトロ半蔵門線,Z01,Z,01,,,●,,銀座線　駅事務室付近（改札内）,●, ,2023/1/21
5957,28008,表参道,東京メトロ半蔵門線,Z02,Z,02,,,●,,青山通り方面改札付近（改札外）,●, ,2023/1/21
5958,28008,青山一丁目,東京メトロ半蔵門線,Z03,Z,03,,,●,,駅事務室付近（改札外）,●, ,2023/1/21
5959,28008,永田町,東京メトロ半蔵門線,Z04,Z,04,永田町　紀尾井町方面改札付近（改札内）　駅事務室付近（改札内）,,,,紀尾井町方面改札付近（改札内） / 駅事務室付近（改札内）,, ,
5960,28008,半蔵門,東京メトロ半蔵門線,Z05,Z,05,,,●,,3番出口付近（改札外）,●, ,2023/1/11
5961,28008,九段下,東京メトロ半蔵門線,Z06,Z,06,九段下　駅事務室付近（改札外）,,,,駅事務室付近（改札外）,, ,
5962,28008,神保町,東京メトロ半蔵門線,Z07,Z,07,神保町　専修大学方面改札付近（改札外）,,,,専修大学方面改札付近（改札外）,, ,
5963,28008,大手町,東京メトロ半蔵門線,Z08,Z,08,,,●,,東西線 駅事務室付近（改札外） / 半蔵門線 駅事務室付近（改札外）,●, ,2023/1/21
5964,28008,三越前,東京メトロ半蔵門線,Z09,Z,09,,,●,,銀座線　駅事務室付近（改札外） / B5出口付近（改札外）,●, ,2023/1/28
5965,28008,水天宮前,東京メトロ半蔵門線,Z10,Z,10,,,●,,水天宮方面改札付近（改札外）,●, ,2023/1/28
5966,28008,清澄白河,東京メトロ半蔵門線,Z11,Z,11,,,●,,B1出口付近（改札外）,●, ,2023/1/28
5967,28008,住吉,東京メトロ半蔵門線,Z12,Z,12,,,●,,B1出口付近（改札外）,●, ,2023/1/28
5968,28008,錦糸町,東京メトロ半蔵門線,Z13,Z,13,,,●,,定期券うりば付近（改札外）,●, ,2023/1/28
5969,28008,押上〈スカイツリー前〉,東京メトロ半蔵門線,Z14,Z,14,,,●,,業平方面改札付近（改札外）,●, ,2023/1/28
5988,28009,目黒,東京メトロ南北線,N01,N,01,目黒　白金台駅 事務室付近（改札外）,,,,白金台駅 事務室付近（改札外）,, ,
5987,28009,白金台,東京メトロ南北線,N02,N,02,白金台　駅事務室付近（改札外）,,,,駅事務室付近（改札外）,, ,
5986,28009,白金高輪,東京メトロ南北線,N03,N,03,白金高輪　駅事務室付近（改札外）,,,,駅事務室付近（改札外）,, ,
5985,28009,麻布十番,東京メトロ南北線,N04,N,04,麻布十番　駅事務室付近（改札外）,,,,駅事務室付近（改札外）,, ,
5984,28009,六本木一丁目,東京メトロ南北線,N05,N,05,,,●,,3番出口方面付近（改札外）,●, ,2023/1/21
5983,28009,溜池山王,東京メトロ南北線,N06,N,06,,,●,,駅事務室付近（改札内）,●, ,2023/1/21
5982,28009,永田町,東京メトロ南北線,N07,N,07,永田町　紀尾井町方面改札付近（改札内）　駅事務室付近（改札内）,,,,紀尾井町方面改札付近（改札内） / 駅事務室付近（改札内）,, ,
5981,28009,四ツ谷,東京メトロ南北線,N08,N,08,四ツ谷　赤坂方面改札付近（改札内）　南北線　駅事務室付近（改札内）,,,,赤坂方面改札付近（改札内） / 南北線　駅事務室付近（改札内）,, ,
5980,28009,市ケ谷,東京メトロ南北線,N09,N,09,,,●,,市谷田町方面改札付近（改札内）,●, ,2023/1/12
5979,28009,飯田橋,東京メトロ南北線,N10,N,10,,,●,,飯田橋交差点方面改札付近（改札内） / 有楽町線・南北線駅事務室付近（改札外）,●, ,2023/1/4
5978,28009,後楽園,東京メトロ南北線,N11,N,11,後楽園　南北線　駅事務室付近（改札外）,,,,南北線　駅事務室付近（改札外）,, ,
5977,28009,東大前,東京メトロ南北線,N12,N,12,東大前　駅事務室付近（改札外）,,,,駅事務室付近（改札外）,, ,
5976,28009,本駒込,東京メトロ南北線,N13,N,13,本駒込　駅事務室付近（改札外）,,,,駅事務室付近（改札外）,, ,
5975,28009,駒込,東京メトロ南北線,N14,N,14,駒込　駅事務室付近（改札外）,,,,駅事務室付近（改札外）,, ,
5974,28009,西ケ原,東京メトロ南北線,N15,N,15,西ケ原　駅事務室付近（改札外）,,,,駅事務室付近（改札外）,, ,
5973,28009,王子,東京メトロ南北線,N16,N,16,王子　駅事務室付近（改札外）,,,,駅事務室付近（改札外）,, ,
5972,28009,王子神谷,東京メトロ南北線,N17,N,17,王子神谷　駅事務室付近（改札内）,,,,駅事務室付近（改札内）,, ,
5971,28009,志茂,東京メトロ南北線,N18,N,18,志茂　1番出口付近（改札外）,,,,1番出口付近（改札外）,, ,
5970,28009,赤羽岩淵,東京メトロ南北線,N19,N,19,赤羽岩淵　駅事務室付近（改札内）,,,,駅事務室付近（改札内）,, ,
5989,28010,和光市,東京メトロ副都心線,F01,F,01,,,●,,地下鉄成増駅 事務室付近（改札外）,●, ,2023/1/3
5990,28010,地下鉄成増,東京メトロ副都心線,F02,F,02,,,●,,駅事務室付近（改札外）,●, ,2023/1/3
5991,28010,地下鉄赤塚,東京メトロ副都心線,F03,F,03,,,●,,駅事務室付近（改札外）,●, ,2023/1/3
5992,28010,平和台,東京メトロ副都心線,F04,F,04,,,●,,駅事務室付近（改札外）,●, ,2023/1/3
5993,28010,氷川台,東京メトロ副都心線,F05,F,05,,,●,,２番出口付近（改札外）,●, ,2023/1/3
5994,28010,小竹向原,東京メトロ副都心線,F06,F,06,,,●,,4番出口付近（改札外）,●, ,2023/1/3
5995,28010,千川,東京メトロ副都心線,F07,F,07,,,●,,駅事務室付近（改札外）,●, ,2023/1/3
5996,28010,要町,東京メトロ副都心線,F08,F,08,,,●,,２番出口付近（改札外）,●, ,2023/1/3
5997,28010,池袋,東京メトロ副都心線,F09,F,09,,,●,,西通路西改札付近(改札外) / 有楽町線　駅事務室付近(改札外）,●, ,2023/1/3
5998,28010,雑司が谷,東京メトロ副都心線,F10,F,10,雑司が谷　2番出口方面付近（改札外）,,,,2番出口方面付近（改札外）,, ,
5999,28010,西早稲田,東京メトロ副都心線,F11,F,11,西早稲田　早大理工方面改札付近（改札外）,,,,早大理工方面改札付近（改札外）,, ,
6000,28010,東新宿,東京メトロ副都心線,F12,F,12,東新宿　駅事務室付近（改札外）,,,,駅事務室付近（改札外）,, ,
6001,28010,新宿三丁目,東京メトロ副都心線,F13,F,13,新宿三丁目　丸ノ内線　駅事務室付近（改札外）　E5出口付近（改札外）,,,,丸ノ内線　駅事務室付近（改札外） / E5出口付近（改札外）,, ,
6002,28010,北参道,東京メトロ副都心線,F14,F,14,北参道　改札付近（改札外）,,,,改札付近（改札外）,, ,
6003,28010,明治神宮前〈原宿〉,東京メトロ副都心線,F15,F,15,,,●,,千代田線　駅事務室付近（改札外）,●, ,2023/1/21
6004,28010,渋谷,東京メトロ副都心線,F16,F,16,,,●,,銀座線　駅事務室付近（改札内）,●, ,2023/1/21

        ";


        DB::table('t_station_stamp')->delete();


        $ex_str = explode("\n", $str);

        foreach ($ex_str as $v) {


            if (trim($v) == "") {
                continue;
            }


            $ex_v = explode(",", trim($v));
            $insert = [
                "station_code" => trim($ex_v[0]),
                "train_code" => trim($ex_v[1]),
                "image_folder" => trim($ex_v[5]),
                "image_code" => trim($ex_v[6]),
                "poster_position" => trim($ex_v[11]),
                "stamp_get_date" => (trim($ex_v[14]) == '') ? '' : date($ex_v[14]),

                "station_name" => trim($ex_v[2]),
                "train_name" => trim($ex_v[3]),
            ];
            DB::table('t_station_stamp')->insert($insert);
        }


    }
}
