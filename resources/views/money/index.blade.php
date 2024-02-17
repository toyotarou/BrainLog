<?php

//---------//
$ex_phpself = explode("/", $_SERVER['PHP_SELF']);
array_pop($ex_phpself);
$public_path = implode("/", $ex_phpself);
//---------//

$td_bg = ['day' => '#ffff99', '10000' => '#ffffcc', '500' => '#ffffee',
    'sum' => '#ccccff',
    'bank_a' => '#ccffcc',
    'pay_a' => '#ffefe0',
    'all' => '#ccccff', 'spend' => '#ff9999', 'total' => '#ffcccc'];
$td_width = ['day' => 55, '10000' => 40, 'bank_a' => 55];

$link_prevMonth = "/money/" . $prevMonth . "/index";
$link_nextMonth = "/money/" . $nextMonth . "/index";
?>

@extends('layouts.brain')

@section('title', '所持金一覧')

@section('content')

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW"
            crossorigin="anonymous"></script>

    <table border="0">
        <tr>
            <td>
                <img src="{{ $public_path }}/img/calender.png" id="btn_article_index">
                <img src="{{ $public_path }}/img/list.png" id="btn_money_list">
                <img src="{{ $public_path }}/img/money.png" id="btn_money_input">
                <img src="{{ $public_path }}/img/repair.png" id="btn_money_repair">
                <img src="{{ $public_path }}/img/bank.png" id="btn_money_bank">
                <img src="{{ $public_path }}/img/salary.png" id="btn_money_salary">
                <img src="{{ $public_path }}/img/credit.png" id="btn_money_credit">
                <img src="{{ $public_path }}/img/history.png" id="btn_money_history">

                @if($prevMonth != 0)
                    <a href="{{ url($link_prevMonth) }}"><img src="{{ $public_path }}/img/cal_back.png"
                                                              class="btn_prev_next"></a>
                @else
                    <img src="{{ $public_path }}/img/cal_back_blank.png" class="btn_prev_next">
                @endif

                @if($nextMonth != 0)
                    <a href="{{ url($link_nextMonth) }}"><img src="{{ $public_path }}/img/cal_next.png"
                                                              class="btn_prev_next"></a>
                @else
                    <img src="{{ $public_path }}/img/cal_next_blank.png" class="btn_prev_next">
                @endif

            </td>
        </tr>
    </table>

    <div>{{ $year }}年{{ $month }}月</div>
    <div>前月繰越金：{!! number_format($bm_all) !!}円</div>
    <hr>

    <table border="0" cellspacing="2" cellpadding="2" id="tbl_money_list">

        @foreach($data as $line)

            @if($yNum[$year . "-" . $month . "-" . $line['day']] == 0)
                <tr>
                    @foreach($column as $col)

                        <?php
                        switch ($col) {
                            case "day":
                                echo "<td class='midashiTd' style='text-align : center;'>day</td>";
                                echo "<td class='midashiTd' style='text-align : center;'>youbi</td>";
                                break;

                            case "pay_a":
                                echo "<td class='midashiTd' style='text-align : center;'>Suica</td>";
                                break;
                            case "pay_b":
                                echo "<td class='midashiTd' style='text-align : center;'>paypay</td>";
                                break;

                            case "pay_c":
                                echo "<td class='midashiTd' style='text-align : center;'>Pasumo</td>";
                                break;

                            case "pay_d":
                                echo "<td class='midashiTd' style='text-align : center;'>Suica2</td>";
                                break;

                            case "pay_e":
                                echo "<td class='midashiTd' style='text-align : center;'>メルカリ</td>";
                                break;

                            default:
                                echo "<td class='midashiTd' style='text-align : center;'>" . $col . "</td>";
                                break;
                        }
                        ?>

                        <?php
                        /*
                        <td class="midashiTd" style="text-align : center;">{{ $col }}</td>

                        @if($col == "day")
                        <td class="midashiTd" style="text-align : center;">youbi</td>
                        @endif
                        */
                        ?>

                    @endforeach
                </tr>
            @endif

            <tr>
                {!! $bgColor = ""; !!}
                @foreach($column as $col)
                    <?php
                    if (!empty($td_bg[$col])) {
                        $bgColor = "background : " . $td_bg[$col] . ";";
                    }
                    if (!empty($td_width[$col])) {
                        $width = "width : " . $td_width[$col] . "px;";
                    }
                    switch ($yNum[$year . "-" . $month . "-" . $line['day']]) {
                        case 0:
                        case 6:
                            $bgColor = "";
                            break;
                    }

                    if (in_array($year . "-" . $month . "-" . $line['day'], $holiday)) {
                        $bgColor = "";
                    }
                    ?>
                    <td style="{{ $bgColor }}{{ $width }}">
                        {!! ($col != "day") ? number_format($line[$col]) : "<div style='text-align : center;'>" . $line[$col] . "</div>" !!}
                    </td>
                    @if($col == "day")
                        <td style="{{ $bgColor }}">
                            <div style="text-align : center;">
                                {!! $yAry[$year . "-" . $month . "-" . $line['day']] !!}
                            </div>
                        </td>
                    @endif
                @endforeach
            </tr>
        @endforeach

        <tr>
            <?php
            for ($i = 0; $i < 23; $i++) {
                echo "<td style='border: 0px;'><br></td>";
            }
            echo "<td>" . number_format($thisMonthSpendTotal) . "</td>";
            ?>
        </tr>

    </table>





    <table>
        <tr>
            <td style="padding: 20px;">

                <table style="width: 400px;">
                    <tr>
                        <td style="vertical-align: top;">
                            <div style="height: 400px; overflow: auto;">
                                <?php
                                if (trim($DailySpend) != "") {
                                    echo $DailySpend;
                                }
                                ?>
                            </div>
                        </td>
                    </tr>
                </table>

            </td>

            <td style="padding: 20px;">

                <table>
                    <tr>
                        <td style="vertical-align: top;">
                            <?php
                            if (count($DisplayKoumoku) > 0) {
                                echo "<div>" . $lastDay . "日分</div>";

                                echo "<table border='0' cellspacing='2' cellpadding='2' id='KoumokuTable'>";
                                foreach ($DisplayKoumoku as $koumoku => $price) {

                                    $percent = "0%";
                                    if ($thisMonthSpendTotal > 0) {
                                        $percent = ($koumoku == "プラス") ? '-' : ceil($price / $thisMonthSpendTotal * 100) . "%";
                                    }

                                    $average = '-';
                                    if ($koumoku == "食費") {
                                        $average = number_format(ceil($price / $lastDay)) . "円";
                                    }

                                    echo "<tr>";
                                    echo "<td>" . $koumoku . "</td>";
                                    echo "<td style='text-align: right;'>" . number_format($price) . "円</td>";
                                    echo "<td style='text-align: right;'>" . $percent . "</td>";
                                    echo "<td style='text-align: right;'>" . $average . "</td>";
                                    echo "</tr>";
                                }

                                echo "<tr>";
                                echo "<td>合計</td>";
                                echo "<td style='text-align: right;'>" . number_format($thisMonthSpendTotal) . "円</td>";
                                echo "<td></td>";
                                echo "<td>" . number_format(ceil($thisMonthSpendTotal / $lastDay)) . "円</td>";
                                echo "</tr>";

                                echo "</table>";
                            }
                            ?>

                            <button id="btn_summary_open">summary</button>

                        </td>
                    </tr>
                </table>

            </td>
        </tr>
    </table>

    <div><br><br></div>

    <div style="background: #ccccff;">
        <form method="POST" action="{{ url('/money/spendinput') }}" id="form_spend_input">
            {{ csrf_field() }}
            <input type="hidden" name="thisMonth" id="thisMonth" value="<?=$thisMonth?>">
            <textarea name="spenddata" id="spenddata"></textarea>
        </form>
        <button id="btn_spend_input">input</button>
    </div>

    <div><br><br></div>

    <div style="background: #ccffcc;">
        <form method="POST" action="{{ url('/money/timeplaceinput') }}" id="form_timeplace_input">
            {{ csrf_field() }}
            <input type="hidden" name="thisMonth" id="thisMonth" value="<?=$thisMonth?>">
            <textarea name="timeplacedata" id="timeplacedata"></textarea>
        </form>
        <button id="btn_timeplace_input">input</button>
    </div>

    <div><br><br></div>

    <form method="POST" action="{{ url('/money/makeMoneyTotalList') }}" id="form_makeMoneyTotalList_input">
        {{ csrf_field() }}
        <button class="btn bg-success text-white" class="m-1">makeMoneyTotalList</button>
    </form>

    <hr>
    <a href="https://www.rakuten-sec.co.jp/ITS/V_ACT_Login.html" target="_blank" class="btn btn-danger">link</a>
    <hr>

    <a href="{{ url('/money/golddatalist') }}" target="_blank" class="btn btn-primary m-1">GOLD</a>
    <a href="{{ url('/money/mercaridatalist') }}" target="_blank" class="btn btn-primary m-1">MERCARI</a>
    <a href="{{ url('/money/funddatalist') }}" target="_blank" class="btn btn-primary m-1">FUND</a>
    <a href="{{ url('/money/rsdatalist') }}" target="_blank" class="btn btn-primary m-1">RakutenStocks</a>
    <a href="{{ url('/money/balancesheetlist') }}" target="_blank" class="btn btn-primary m-1">BalanceSheet</a>

    <hr>

    <div>
        <a href="{{ url('/money/stockdatalist') }}" target="_blank" class="btn btn-info m-1">StockData</a>
        {{ $stockDatasDate }}
    </div>

    <div>
        <a href="{{ url('/money/shintakudatalist') }}" target="_blank" class="btn btn-info m-1">ShintakuData</a>
        {{ $shintakuDatasDate }}
    </div>

    <hr>

    <div>
        <a href="https://www2.uccard.co.jp/cs/atu/01.html" target="_blank">uc card</a>
        <div>
            hidechy<br>
            hidechy4819
        </div>
    </div>

    <br>

    <div>
        <a href="https://www.rakuten-card.co.jp/e-navi/" target="_blank">rakuten card</a>
        <div>
            hide.toyoda@gmail.com<br>
            4647
        </div>
    </div>

    <div><br><br></div>



<?php
//
//
//    $curl = curl_init();
//
//    curl_setopt_array($curl, [
//    CURLOPT_URL => "https://navitime-maps.p.rapidapi.com/map_script?host=localhost",
//    CURLOPT_RETURNTRANSFER => true,
//    CURLOPT_FOLLOWLOCATION => true,
//    CURLOPT_ENCODING => "",
//    CURLOPT_MAXREDIRS => 10,
//    CURLOPT_TIMEOUT => 30,
//    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//    CURLOPT_CUSTOMREQUEST => "GET",
//    CURLOPT_HTTPHEADER => [
//    "X-RapidAPI-Host: navitime-maps.p.rapidapi.com",
//    "X-RapidAPI-Key: 9398c31ec2msh833f19aef65438bp1c8edajsn6b5dab9ebadc"
//    ],
//    ]);
//
//    $response = curl_exec($curl);
//    $err = curl_error($curl);
//
//    curl_close($curl);
//
//    if ($err) {
//    echo "cURL Error #:" . $err;
//    } else {
//    echo $response;
//    }

?>



    <div><br><br></div>



    <style type="text/css">
        <!--
        #tbl_money_list td {
            border: 1px solid #cccccc;
            text-align: right;
        }

        #btn_money_input {
            cursor: pointer;
            margin: 2px;
        }

        #btn_money_bank {
            cursor: pointer;
            margin: 2px;
        }

        .btn_prev_next {
            width: 28px;
        }

        #btn_money_list {
            cursor: pointer;
            margin: 2px;
        }

        #btn_money_salary {
            cursor: pointer;
            margin: 2px;
        }

        #btn_money_credit {
            cursor: pointer;
            margin: 2px;
        }

        .midashiTd {
            background: #339933;
            color: #ffffff;
        }

        #btn_article_index {
            cursor: pointer;
            margin: 2px;
        }

        #btn_money_repair {
            cursor: pointer;
            margin: 2px;
        }

        #btn_money_history {
            cursor: pointer;
            margin: 2px;
        }

        #tbl_dailyspend td {
            border: 1px solid #cccccc;
            text-align: right;
        }

        .midashi_dailyspend td {
            background: #3333ff;
            color: #ffffff;
            font-weight: bold;
            text-align: center;
        }

        #spenddata {
            width: 600px;
            height: 200px;
            font-size: 12px;
        }

        #KoumokuTable td {
            border: 1px solid #cccccc;
        }

        #timeplacedata {
            width: 600px;
            height: 200px;
            font-size: 12px;
        }

        -->
    </style>

    <script type="text/javascript">
        <!--
        $("#btn_money_input").width(38);
        $("#btn_money_input").click(function () {
            location.href = "{{ url('/money/input') }}";
        });

        $("#btn_money_bank").width(38);
        $("#btn_money_bank").click(function () {
            location.href = "{{ url('/money/bank') }}";
        });

        $("#btn_money_list").width(38);
        $("#btn_money_list").click(function () {
            location.href = "{{ url('/money/summary') }}";
        });

        $("#btn_money_salary").width(38);
        $("#btn_money_salary").click(function () {
            location.href = "{{ url('/money/salary') }}";
        });

        $("#btn_money_credit").width(38);
        $("#btn_money_credit").click(function () {
            location.href = "{{ url('/money/credit') }}";
        });

        $("#btn_article_index").width(38);
        $("#btn_article_index").click(function () {
            location.href = "{{ url('/article/index') }}";
        });

        $("#btn_money_repair").width(38);
        $("#btn_money_repair").click(function () {
            location.href = "{{ url('/money/repair') }}";
        });

        $("#btn_money_history").width(38);
        $("#btn_money_history").click(function () {
            location.href = "{{ url('/money/history') }}";
        });

        $("#btn_summary_open").click(function () {
            window.open("{{ url('/money/' . $year . "-" . $month . '/itemsummary') }}");
        });

        $("#btn_spend_input").click(function () {
            let spenddata = $("#spenddata").val();
            if (spenddata == "") {
                window.alert("no data");
                return false;
            }
            $("#form_spend_input").submit();
        });

        $("#btn_timeplace_input").click(function () {
            let timeplacedata = $("#timeplacedata").val();
            if (timeplacedata == "") {
                window.alert("no data");
                return false;
            }
            $("#form_timeplace_input").submit();
        });
        -->
    </script>

@stop
