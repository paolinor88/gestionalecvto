<?php
function corretta(){
		$ordine = array(16,13,34,2,27,4,32,1,30,6,14,5,17,10,28,7,18,24,20,11,23,9,26,15,33,12,21,31,3,29,25,22,19,8);
        $ColS = array(1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39);
        $ColD = array(78,77,76,75,74,73,72,71,70,69,68,67,66,65,64,63,62,61,60,59,58,57,56,55,54,53,52,51,50,49,48,47,46,45,44,43,42,41,40);
        $turno =
            array(
                '06:00/13:36',
                '08:00/16:00',
                '13:00/20:36',
                '06:00/14:00',
                '13:30/20:06',
                '07:00/15:00',
                '13:00/20:36',
                '06:00/14:00',
                '13:30/20:06',
                '08:00/16:00',
                '06:00/13:36',
                '08:00/16:00',
                '06:00/13:36',
                '08:00/16:00',
                '13:30/20:06',
                '08:00/16:00',
                '06:00/13:36',
                '10:00/18:00',
                '06:00/13:36',
                '09:00/17:00',
                '06:30/14:06',
                '08:00/16:00',
                '06:00/13:36',
                '20:00/06:00',
                '13:30/20:06',
                '09:00/17:00',
                '06:00/13:36',
                '13:00/20:36',
                '07:00/15:00',
                '13:30/20:06',
                '10:00/18:00',
                '06:00/13:36',
                '06:00/13:36',
                '08:00/16:00',
                '08:00/14:30',
                '20:00/06:00',
                '07:30/15:30',
                '06:00/13:36',
                '10:00/18:00');
        $postazione = array(
            '610',
            'S 13',
            '055',
            'S 02',
            '400',
            'S 04',
            '007',
            'S 01',
            '610',
            'S 06',
            '039',
            'S 05',
            '430',
            'S 10',
            '039',
            'S 07',
            '400',
            'S 14',
            '007',
            'S 11',
            '023',
            'S 09',
            '410',
            'NOTTI AGG',
            '011',
            'S 12',
            '011',
            '003',
            'S 03',
            '270',
            'S 15',
            '055',
            '003',
            'S 08',
            '680',
            '410',
            'S 16',
            '270',
            'S 17');
        $m = 1;
        $s = 0;
        for ($i=1;$i<40;$i++):
            if ($i==1):
                for ($a=0;$a<39;$a++):


                    echo 'INSERT INTO modello_turni SET settimana="'.$i .'", dip_1=' .$ColS[$a].', dip_2=' .$ColD[$a].',orario="'. $turno[$a]. '",postazione="'.$postazione[$a]. '";<br> ';
                endfor;
            else:
                $last = count($ColS)-$s;
                $z = 0;

                for ($r=0;$r<39;$r++):
                    if (!empty($ColS[$last+$r])):
                        $keys = $last +$r;
                        echo 'INSERT INTO modello_turni SET settimana="'.$i .'", dip_1=' .$ColS[$keys].', dip_2=' .$ColD[$m] .',orario="'. $turno[$r] .'",postazione="'.$postazione[$r]. '";<br> ';
                    else:
                        echo 'INSERT INTO modello_turni SET settimana="'.$i .'", dip_1=' .$ColS[$z].', dip_2=' .$ColD[$m].',orario="'. $turno[$r] .'",postazione="'.$postazione[$r].'";<br> ';
                        $z++;

                    endif;
                    $m ++;
                    if (empty($ColD[$m])):
                        $m =0;
                        /*else:
                            $m ++;
							,ordine="'.$ordine[$a].'" ORDER BY ordine
							*/
                    endif;
                endfor;
                $m++;
            endif;
            $s++;
        endfor;
    }

corretta();