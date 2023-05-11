<?php

const ROOT = __DIR__ . "/..";

require ROOT . "/functions/require.php";
//echo strtotime("next friday");

$contact_add_data = [
    "created_at" => time(),
    "name" => "name",
    "responsible_user_id" => "RUID",
    "custom_fields_values" => []
];


$contact_add_data["custom_fields_values"][] = [
    "field_id" => "FIELD_ID_PHONE",
    "values" => [["value" => "phone", "enum_code" => "MOB"]]
];





echo "<pre>";
print_r([$contact_add_data]);
//$endWorkDayStr = date("Y-m-d 20:00:00");
//
//$endWorkDayUnix= strtotime($endWorkDayStr);
//
//$now = time();
//
//$dayNumber = date("N");
//
//if($dayNumber == 5) {
//    $res = strtotime(date("Y-m-d 20:00:00", strtotime($endWorkDayStr.'+ 3 days')));
//} else if($dayNumber == 6) {
//    $res = strtotime(date("Y-m-d 20:00:00", strtotime($endWorkDayStr.'+ 2 days')));
//
//} else if($now >= $endWorkDayUnix) {
//    $res = strtotime(date("Y-m-d 20:00:00", strtotime($endWorkDayStr.'+ 1 days')));
//
//} else {
//    $before = $endWorkDayUnix - $now;
//    $res = $now + $before;
//}
//
//


//foreach($dates as $d){
//    echo "<pre>";
//    print_r([
//        'date' => $d,
//        'dayOfWeek' => $days[ date("w", strtotime($d) )]
//    ]);
//}



//$endWorkDayStr = date("Y-m-d 20:00:00");
//
//$endWorkDayUnix= strtotime($endWorkDayStr);
//$date1 = strtotime(date("Y-m-d 20:00:00", strtotime($endWorkDayStr.'+ 1 days')));
//echo $date1;
//$now = time();
//
//
//
//$before = $endWorkDayUnix - $now;
//$res = $now + $before;



//$lead_add = getEntity(CRM_ENTITY_LEAD, 29497836);
//echo "<h3>Ответ на создание сделки</h3><pre>";
//echo json_print($lead_add);
//echo "</pre>";
//




////print_r(GetCreate::getCompanieId(24865111));
//echo "<pre>";


//// пример для дебага хука
//$check = file_get_contents(ROOT . "/check.json");
//$check = json_decode($check, true);
//$check[date("Y-m-d H:i:s")] = $out->_links;
//$check = json_encode($check, JSON_UNESCAPED_UNICODE);
//file_put_contents(ROOT . "/check.json", $check);
