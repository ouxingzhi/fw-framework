<?php

namespace Fw\Utils;

class Page{
    public static function info($cur,$total,$count,$step=8){
        
        $totalpage = ceil($total / $count);
        if($totalpage <= 0) $totalpage = 1;
        if($cur < 1) $cur = 1;
        if($cur > $totalpage) $cur = $totalpage;
        $hasPre = $cur <= 1 ? false : true;
        $hasNext = $cur < $totalpage ? true : false;
        if($step > $totalpage) $step = $totalpage;
        $middle = $step % 2 ? ceil($step / 2) : ceil(($step - 1) / 2);
        $mend = $step - $middle;
        $start = max(min($cur + $mend,$totalpage)-$step,0);

        $steps = array();
        
        for($i=1;$i<=$step;$i++){
             $steps[] = $start + $i;   
        }
        
        $offset = ($cur-1) * $count;
        
        return array(
            'cur'=>$cur,
            'total'=>$total,
            'totalpage'=>$totalpage,
            'haspre'=>$hasPre,
            'hasnext'=>$hasNext,
            'step'=>$step,
            'steps'=>$steps,
            'offset'=>$offset,
            'count'=>$count
        );
    }
}