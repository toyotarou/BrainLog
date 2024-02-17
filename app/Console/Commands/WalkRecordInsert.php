<?php

namespace App\Console\Commands;

use DB;
use Illuminate\Console\Command;

class WalkRecordInsert extends Command
{

    protected $signature = 'WalkRecordInsert';

    protected $description = 'Command description';

    public function handle()
    {


        $str = "
2020	8	8	5228	3500
2020	8	9	12626	8500
2020	8	10	7069	4700
2020	8	11	308	206
2020	8	12	5868	3900
2020	8	13	8701	5800
2020	8	14	8835	5900
2020	8	15	2949	2000
2020	8	16	79	52
2020	8	17	8696	5800
2020	8	18	8866	5900
2020	8	19	8672	5800
2020	8	20	8626	5800
2020	8	21	8531	5700
2020	8	22	43	28
2020	8	23	8058	5400
2020	8	24	8626	5800
2020	8	25	8864	5900
2020	8	26	8437	5700
2020	8	27	8264	5500
2020	8	28	8758	5900
2020	8	29	101	67
2020	8	30	10147	6800
2020	8	31	8980	6000
2020	9	1	10564	7100
2020	9	2	9695	6500
2020	9	3	9476	6300
2020	9	4	8723	5800
2020	9	5	3755	2500
2020	9	6	42	28
2020	9	7	9008	6000
2020	9	8	8962	6000
2020	9	9	829	555
2020	9	10	11363	7600
2020	9	11	8352	5600
2020	9	12	2210	1500
2020	9	13	8049	5400
2020	9	14	8600	5800
2020	9	15	9211	6200
2020	9	16	8792	5900
2020	9	17	9296	6200
2020	9	18	9217	6200
2020	9	19	3645	2400
2020	9	20	12675	8500
2020	9	21	13438	9000
2020	9	22	3819	2600
2020	9	23	8503	5700
2020	9	24	8480	5700
2020	9	25	8723	5800
2020	9	26	8899	6000
2020	9	27	7869	5300
2020	9	28	8665	5800
2020	9	29	17525	11700
2020	9	30	9229	6200
2020	10	1	9735	6500
2020	10	2	10641	7100
2020	10	3	2023	1400
2020	10	4	11257	7500
2020	10	5	7645	5100
2020	10	6	8433	5700
2020	10	7	7633	5100
2020	10	8	7854	5300
2020	10	9	7683	5100
2020	10	10	69	46
2020	10	11	7217	4800
2020	10	12	7338	4900
2020	10	13	7498	5000
2020	10	14	6233	4200
2020	10	15	7639	5100
2020	10	16	8320	5600
2020	10	17	15825	10600
2020	10	18	12952	8700
2020	10	19	6716	4500
2020	10	20	7411	5000
2020	10	21	6979	4700
2020	10	22	7370	4900
2020	10	23	7453	5000
2020	10	24	3875	2600
2020	10	25	7465	5000
2020	10	26	8362	5600
2020	10	27	6847	4600
2020	10	28	7442	5000
2020	10	29	6803	4600
2020	10	30	9652	6500
2020	10	31	3629	2400
2020	11	1	7389	5000
2020	11	2	6952	4700
2020	11	3	70	46
2020	11	4	8045	5400
2020	11	5	8920	6000
2020	11	6	7766	5200
2020	11	7	20838	14000
2020	11	8	7265	4900
2020	11	9	7279	4900
2020	11	10	6851	4600
2020	11	11	7430	5000
2020	11	12	7203	4800
2020	11	13	7289	4900
2020	11	14	15937	10700
2020	11	15	6934	4600
2020	11	16	7170	4800
2020	11	17	7150	4800
2020	11	18	6529	4400
2020	11	19	6591	4400
2020	11	20	7293	4900
2020	11	21	11486	7700
2020	11	22	14209	9500
2020	11	23	2348	1600
2020	11	24	6729	4500
2020	11	25	6404	4300
2020	11	26	9580	6400
2020	11	27	7857	5300
2020	11	28	19937	13400
2020	11	29	8967	6000
2020	11	30	9902	6600
2020	12	1	8057	5400
2020	12	2	7351	4900
2020	12	3	7265	4900
2020	12	4	7938	5300
2020	12	5	58	38
2020	12	6	2598	1700
2020	12	7	8188	5500
2020	12	8	6974	4700
2020	12	9	6858	4600
2020	12	10	7243	4900
2020	12	11	7442	5000
2020	12	12	12121	8100
2020	12	13	9682	6500
2020	12	14	7450	5000
2020	12	15	7618	5100
2020	12	16	7189	4800
2020	12	17	6713	4500
2020	12	18	10080	6800
2020	12	19	11351	7600
2020	12	20	7555	5100
2020	12	21	7282	4900
2020	12	22	7442	5000
2020	12	23	6905	4600
2020	12	24	8539	5700
2020	12	25	8656	5800
2020	12	26	4975	3300
2020	12	27	10029	6700
2020	12	28	7306	4900
2020	12	29	5336	3600
2020	12	30	7314	4900
2020	12	31	176	117
2021	1	1	11292	7600
2021	1	2	5023	3400
2021	1	3	7937	5300
2021	1	4	7857	5300
2021	1	5	103	69
2021	1	6	3490	2300
2021	1	7	3584	2400
2021	1	8	103	69
2021	1	9	97	64
2021	1	10	10709	7200
2021	1	11	16	10
2021	1	12	2683	1800
2021	1	13	157	105
2021	1	14	2721	1800
2021	1	15	185	123
2021	1	16	63	42
2021	1	17	7639	5100
2021	1	18	6104	4100
2021	1	19	110	73
2021	1	20	2838	1900
2021	1	21	97	64
2021	1	22	167	111
2021	1	23	4771	3200
2021	1	24	24	16
2021	1	25	2640	1800
2021	1	26	44	29
2021	1	27	149	99
2021	1	28	92	61
2021	1	29	2739	1800
2021	1	30	5458	3700
2021	1	31	7302	4900
2021	2	1	4899	3300
2021	2	2	5471	3700
2021	2	3	174	116
2021	2	4	93	62
2021	2	5	42	28
2021	2	6	7793	5200
2021	2	7	118	79
2021	2	8	95	63
2021	2	9	5089	3400
2021	2	10	66	44
2021	2	11	3255	2200
2021	2	12	156	104
2021	2	13	270	180
2021	2	14	7652	5100
2021	2	15	117	78
2021	2	16	4984	3300
2021	2	17	2772	1900
2021	2	18	48	32
2021	2	19	101	67
2021	2	20	5265	3500
2021	2	21	7560	5100
2021	2	22	19	12
2021	2	23	154	103
2021	2	24	5342	3600
2021	2	25	89	59
2021	2	26	5017	3400
2021	2	27	112	75
2021	2	28	87	58
2021	3	1	97	64
2021	3	2	6110	4100
2021	3	3	148	99
2021	3	4	3048	2000
2021	3	5	39	26
2021	3	6	7647	5100
2021	3	7	7800	5200
2021	3	8	116	77
2021	3	9	6548	4400
2021	3	10	36	24
2021	3	11	80	53
2021	3	12	145	97
2021	3	13	121	81
2021	3	14	7836	5300
2021	3	15	164	109
2021	3	16	6734	4500
2021	3	17	204	136
2021	3	18	125	83
2021	3	19	145	97
2021	3	20	211	141
2021	3	21	2098	1400
2021	3	22	153	102
2021	3	23	6445	4300
2021	3	24	81	54
2021	3	25	307	205
2021	3	26	51	34
2021	3	27	4990	3300
2021	3	28	9386	6300
2021	3	29	148	99
2021	3	30	6936	4600
2021	3	31	2093	1400
2021	4	1	66	44
2021	4	2	16016	107000
2021	4	3	7320	4900
2021	4	4	7825	5200
2021	4	5	121	81
2021	4	6	5950	4000
2021	4	7	88	58
2021	4	8	283	189
2021	4	9	335	224
2021	4	10	131	87
2021	4	11	9062	6100
2021	4	12	66	44
2021	4	13	8210	5500
2021	4	14	209	140
2021	4	15	148	99
2021	4	16	57	38
2021	4	17	5251	3500
2021	4	18	2105	1400
2021	4	19	96	64
2021	4	20	7645	5100
2021	4	21	145	97
2021	4	22	129	86
2021	4	23	136	91
2021	4	24	88	58
2021	4	25	8339	5600
2021	4	26	76	50
2021	4	27	7573	5100
2021	4	28	1593	1100
2021	4	29	5321	3600
2021	4	30	211	141
2021	5	1	265	177
2021	5	2	12685	8500
2021	5	3	13954	9300
2021	5	4	151	101
2021	5	5	6034	4000
2021	5	6	2949	2000
2021	5	7	61	40
2021	5	8	148	99
2021	5	9	21	14
2021	5	10	2754	1800
2021	5	11	6548	4400
2021	5	12	25	16
2021	5	13	804	538
2021	5	14	2064	1400
2021	5	15	9312	6200
2021	5	16	7993	5400
2021	5	17	2963	2000
2021	5	18	6830	4600
2021	5	19	151	101
2021	5	20	237	158
2021	5	21	224	150
2021	5	22	4453	3000
2021	5	23	8070	5400
2021	5	24	226	151
2021	5	25	7053	4700
2021	5	26	74	49
2021	5	27	24	16
2021	5	28	113	75
2021	5	29	86	57
2021	5	30	8247	5500
2021	5	31	9030	6100
2021	6	1	50	33
2021	6	2	893	598
2021	6	3	56	37
2021	6	4	71	47
2021	6	5	7760	5200
2021	6	6	8812	5900
2021	6	7	81	54
2021	6	8	6749	4500
2021	6	9	183	122
2021	6	10	24042	161000
2021	6	11	6048	4100
2021	6	12	4211	2800
2021	6	13	9993	6700
2021	6	14	1617	1100
2021	6	15	4229	2800
2021	6	16	2340	1600
2021	6	17	2332	1600
2021	6	18	8684	5800
2021	6	19	4086	2700
2021	6	20	3034	2000
2021	6	21	2651	1800
2021	6	22	78	52
2021	6	23	128	85
2021	6	24	241	161
2021	6	25	2340	1600
2021	6	26	4748	3200
2021	6	27	8220	5500
2021	6	28	114	76
2021	6	29	1956	1300
2021	6	30	2153	1400
2021	7	1	144	96
2021	7	2	7084	4700
2021	7	3	5223	3500
2021	7	4	8115	5400
2021	7	5	18	12
2021	7	6	25	16
2021	7	7	2233	1500
2021	7	8	107	71
2021	7	9	8401	5600
2021	7	10	23	15
2021	7	11	8476	5700
2021	7	12	32	21
2021	7	13	45	30
2021	7	14	1857	1200
2021	7	15	980	656
2021	7	16	59	39
2021	7	17	1121	751
2021	7	18	2039	1400
2021	7	19	1857	1200
2021	7	20	85	56
2021	7	21	78	52
2021	7	22	6510	4400
2021	7	23	12512	8400
2021	7	24	176	117
2021	7	25	12700	8500
2021	7	26	40	26
2021	7	27	48	32
2021	7	28	54	36
2021	7	29	859	575
2021	7	30	6168	4100
2021	7	31	993	665
2021	8	1	7892	5300
2021	8	2	21	14
2021	8	3	44	29
2021	8	4	80	53
2021	8	5	60	40
2021	8	6	72	48
2021	8	7	5428	3600
2021	8	8	12051	8100
2021	8	9	58	38
2021	8	10	0	0
2021	8	11	7777	5200
2021	8	12	861	576
2021	8	13	26	17
2021	8	14	987	661
2021	8	15	68	45
2021	8	16	71	47
2021	8	17	61	40
2021	8	18	116	77
2021	8	19	6514	4400
2021	8	20	948	635
2021	8	21	1074	719
2021	8	22	8920	6000
2021	8	23	116	77
2021	8	24	44	29
2021	8	25	43	28
2021	8	26	798	534
2021	8	27	5481	3700
2021	8	28	36	24
2021	8	29	8167	5500
2021	8	30	6369	4300
2021	8	31	1697	1100
2021	9	1	64	42
2021	9	2	43	28
2021	9	3	1456	975
2021	9	4	18769	12600
2021	9	5	15998	10700
2021	9	6	3637	2400
2021	9	7	1811	1200
2021	9	8	1965	1300
2021	9	9	2082	1400
2021	9	10	7665	5100
2021	9	11	77	51
2021	9	12	8915	6000
2021	9	13	359	240
2021	9	14	2076	1400
2021	9	15	2144	1400
2021	9	16	173	115
2021	9	17	5996	4000
2021	9	18	11908	8000
2021	9	19	10884	7300
2021	9	20	5726	3800
2021	9	21	1863	1200
2021	9	22	2270	1500
2021	9	23	77	51
2021	9	24	6109	4100
2021	9	25	2970	2000
2021	9	26	14019	9400
2021	9	27	39	26
2021	9	28	139	93
2021	9	29	163	109
2021	9	30	6412	4300
2021	10	1	114	76
2021	10	2	55	36
2021	10	3	7760	5200
2021	10	4	25156	16900
2021	10	5	101	67
2021	10	6	3918	2600
2021	10	7	81	54
2021	10	8	22070	14800
2021	10	9	214	143
2021	10	10	11153	7500
2021	10	11	43	28
2021	10	12	12845	8600
2021	10	13	15377	10300
2021	10	14	37	24
2021	10	15	47	31
2021	10	16	123	82
2021	10	17	7756	5200
2021	10	18	69	46
2021	10	19	15414	10300
2021	10	20	23237	15600
2021	10	21	30	20
2021	10	22	88	58
2021	10	23	91	60
2021	10	24	12852	8600
2021	10	25	2302	1500
2021	10	26	19473	13000
2021	10	27	52	34
2021	10	28	52	34
2021	10	29	17107	11500
2021	10	30	805	539
2021	10	31	10366	6900
2021	11	1	10452	7000
2021	11	2	74	49
2021	11	3	30984	20800
2021	11	4	79	52
2021	11	5	54	36
2021	11	6	39946	26800
2021	11	7	7774	5200
2021	11	8	157	105
2021	11	9	24	16
2021	11	10	55	36
2021	11	11	136	91
2021	11	12	17743	11900
2021	11	13	33059	22100
2021	11	14	8384	5600
2021	11	15	49	32
2021	11	16	39	26
2021	11	17	66	44
2021	11	18	1895	1300
2021	11	19	39	26
2021	11	20	21556	14400
        ";

        $ex_str = explode("\n", $str);

        foreach ($ex_str as $v) {
            if (trim($v) == "") {
                continue;
            }

            list($year, $month, $day, $step, $distance) = explode("\t", trim($v));

            $insert = [];
            $insert['year'] = trim($year);
            $insert['month'] = trim($month);
            $insert['day'] = trim($day);
            $insert['step'] = trim($step);
            $insert['distance'] = trim($distance);

            print_r($insert);

            DB::table('t_walk_record')->insert($insert);
        }
    }
}
