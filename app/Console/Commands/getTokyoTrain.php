<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use DB;

class getTokyoTrain extends Command
{

    protected $signature = 'getTokyoTrain';

    protected $description = 'Command description';

    public function handle()
    {

        $sql = " select * from t_train ";
        $result = DB::select($sql);
        foreach ($result as $k => $v) {

if($k>30){
//break;
}



            $update = [];
            $update['tokyo_train'] = 0;



            $url = "https://express.heartrails.com/api/json?method=getStations&line={$v->train_name}";

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_URL, $url);

            $result = curl_exec($ch);

            $jsonStr = json_decode($result);

            $flag = false;
            $ary = [];
            foreach($jsonStr->response as $v2){
                if(is_array($v2)){
                    $flag = true;
                    $ary = $v2;
                }
            }

            if($flag){

                $prefs = [];
                foreach($ary as $v3){
                    $prefs[] = $v3->prefecture;
                }

                if(in_array('東京都', $prefs)){
                    // print_r($v->id);
                    // echo "\n";
                    print_r($v->train_name);
                    // echo "\n";
                    // print_r($ary);
                    // echo "\n";
                    // print_r($prefs);
                    echo "\n";echo "\n";echo "\n";

                    $update['tokyo_train'] = 1;
                }
            }

            DB::table('t_train')->where('id', $v->id)->update($update);
        }
    }
}