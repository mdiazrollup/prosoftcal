<?php
class Calendar {
    private $holidayApi = "http://holidayapi.com/v1/holidays?";

	private $dayLabels = array("S","M","T","W","T","F","S");
     
    private $startYear=0;
     
    private $startMonth=0;
     
    private $startDay=0;

    private $endYear=0;
     
    private $endMonth=0;
     
    private $endDay=0;

    private $startTime = 0;

    private $endTime = 0;

    private $totalDays = 1;

    private $countryCode = "US";

    private $monthsHolidays =[];

	public function __construct(){   
		$a = func_get_args(); 
        $i = func_num_args(); 
        if (method_exists($this,$f='__construct'.$i)) { 
            call_user_func_array(array($this,$f),$a); 
        } 
    }

    public function __construct3($start,$totalDays,$cc){ 
    	$this->startTime = $start;
    	$this->totalDays = $totalDays;
    	$this->countryCode = $cc;

    	$this->startDay = date("j",$this->startTime);
		$this->startMonth = date("n",$this->startTime);
		$this->startYear = date("Y",$this->startTime);

		$this->endTime = mktime(0, 0, 0, $this->startMonth, $this->startDay +$totalDays,$this->startYear);

		$this->endDay = date("j",$this->endTime);
		$this->endMonth = date("n",$this->endTime);
		$this->endYear = date("Y",$this->endTime);
    }

    public function draw(){
    	$calendar = '';
    	$startTime = mktime(0, 0, 0, $this->startMonth, 1,$this->startYear);
    	while($startTime <= $this->endTime){
    		$calendar .= $this->drawMonth($startTime);
    		$startTime = mktime(0, 0, 0, date("n",$startTime) + 1, 1,date("Y",$startTime));
    	}
        return $calendar;
    }

    private function drawMonth($datetime){
        $content="";
    	$startDay = date("j",$datetime);
    	$month = date("n",$datetime);
        $monthTitle = date("F",$datetime);
    	$year = date("Y",$datetime);
        $this->monthsHolidays = $this->getMonthHolidays($month,$year,$this->countryCode);
    	$numberOfDays = $this->daysInMonth($month,$year);
    	$endDay = $numberOfDays;
    	if($month == $this->startMonth && $year == $this->startYear){
    		$startDay = $this->startDay;
    	}
    	if($month == $this->endMonth && $year == $this->endYear){
    		$endDay = $this->endDay;
    	}
        $startWeekDay = date("w",mktime(0, 0, 0, $month,$startDay,$year));
        $endWeekDay = date("w",mktime(0, 0, 0, $month,$endDay,$year));
        $content.= '<div class="month-container">';
        $content.='<ul class="title">'.$monthTitle.' '.$year.'</ul>';
        $content.='<ul class="label">'.$this->createLabels().'</ul>';   

        $dayofweek = 0;
    	for($i =$startDay; $i <= $endDay;$i++){
            if($dayofweek ==7){
                $dayofweek =0;
            }
            if($i == $startDay && $startWeekDay!=0){
                $content.=$this->drawEmptyDays($dayofweek,$startWeekDay-1);
                $dayofweek += $startWeekDay;
            }
            $isHoliday = $this->isHoliday($i,$month,$year);
            $content.=$this->addDay($i,$dayofweek,$isHoliday);
            if($i == $endDay && $endWeekDay!=6){
                $content.=$this->drawEmptyDays($endWeekDay+1,6);
            }
            $dayofweek++;
    	}
        $content.='</div>';
        return $content;
    }

    private function drawEmptyDays($start,$end){
        $content = "";
        for($i=$start;$i<=$end;$i++){
            $content.=$this->addDay("",$i);
        }
        return $content;
    }

    private function addDay($day,$dayofweek,$isHoliday=false){
        $content="";
        $styleClass= "";
        if(!empty($day)){
            $styleClass=($isHoliday)?'holiday':(($dayofweek == 0 || $dayofweek == 6)?'weekend':'weekday');
        }
        if($dayofweek == 0 ){
            $content.='<ul class="dates">';
        }
        $content.='<li class="day '.$styleClass.'">'.$day.'</li>';
        if($dayofweek==6){
            $content.='</ul>';
        }
        return $content;
    }

	private function daysInMonth($month,$year){ 
        return date('t',strtotime($year.'-'.$month.'-01'));
    }

    private function createLabels(){           
        $content='';  
        foreach($this->dayLabels as $index=>$label){      
            $content.='<li class="title">'.$label.'</li>';
        }  
        return $content;
    }

    private function getMonthHolidays($month,$year,$countryCode){
        $service_url = $this->holidayApi."country=".$countryCode."&year=".$year."&month=".$month;
        $curl = curl_init($service_url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $curl_response = curl_exec($curl);
        if ($curl_response === false) {
            $info = curl_getinfo($curl);
            curl_close($curl);
            die('error occured during curl exec. Additioanl info: ' . var_export($info));
        }
        curl_close($curl);
        $decoded = json_decode($curl_response);
        if (isset($decoded->status) && $decoded->status != 200) {
            die('error occured: ' . $decoded->response->status);
        }
        return $decoded->holidays;
    }

    private function isHoliday($day,$month,$year){
        $date = mktime(0, 0, 0, $month,$day,$year);
        for($i=0;$i<count($this->monthsHolidays);$i++){
            $entry = $this->monthsHolidays[$i];
            $dArray = split('-',$entry->date);
            $entryTime = mktime(0, 0, 0, $dArray[1],$dArray[2],$dArray[0]);
            if($date == $entryTime){
                return true;
            }
        }
        return false;
    }
}
?>