<?php

const ROOT = __DIR__;

require ROOT . "/functions/require.php";

logs();




$input = $_POST;


if (!empty($_GET["test"])) {
    $input = [

        "lastname" => "",
        "city" => "Ставрополь",
        "mkr" => "Российский",
        "phone" => "+7 (111) 111 11 11",
        "name" => "4444",
        "qc" => "44",
        "asmm" => "4"
    ];




}

$fullName = "{$input["name"]} {$input["lastname"]}";

$input_trim_lower = [];
foreach ($input as $key => $value) {

    if(!is_array($value)) {
        $input_trim_lower[$key] = trim(mb_strtolower($value));

    }
}
$pipeline_id = null;
$status_id = null;



if ($input_trim_lower["mkr"] == "вересаево") {
    $pipeline_id = 1399426;
    $status_id = 22090243;
    $respUserId = 6402732;

} elseif ($input_trim_lower["mkr"] == "левобережье") {
    $pipeline_id = 4242663;
    $status_id = 46035738;
    $respUserId = 8364786;

} elseif ($input_trim_lower["mkr"] == "губернский") {
    $pipeline_id = 1393867;
    $status_id = 22041304;
    $respUserId = 3899314;

} elseif ($input_trim_lower["mkr"] == "достояние") {
    $pipeline_id = 3302563;
    $status_id = 33254971;
    $respUserId = 7874628;

} elseif ($input_trim_lower["mkr"] == "архитектор") {
    $pipeline_id = 6427297;
    $status_id = 54951493;
    $respUserId = 8796285;

} elseif ($input_trim_lower["mkr"] == "российский") {
    $pipeline_id = 1399423;
    $status_id = 22090234;
    $respUserId = 9325605;

} elseif ($input_trim_lower["mkr"] == "квартет") {
    $pipeline_id = 5129982;
    $status_id = 46008504;
    $respUserId = 9325605;

} elseif ($input_trim_lower["mkr"] == "кварталы 17/77") {
    $pipeline_id = 4551390;
    $status_id = 41960496;
    $respUserId = 9325605;

} elseif ($input_trim_lower["mkr"] == "гк юси") {
    $pipeline_id = 1393867;
    $status_id = 22041304;
    $respUserId = 3899314;

}





if (!empty($pipeline_id)) {

    // ЧАСТЬ 1 - НАХОДИМ КОНТАКТ И АКТИВНЫЙ ЛИД В НЕМ
    $contact_id = null;
    $we_have_active_lead = false;
    $phone_to_search = preg_replace("/[^\d]/siu", "", $input["phone"]);
    if (mb_strlen($phone_to_search) == 11) {
        $phone_to_search = substr($phone_to_search, 1);
    }
    $existing_contacts = searchEntity(CRM_ENTITY_CONTACT, $phone_to_search);


    if (!empty($existing_contacts["_embedded"]["contacts"])) {

        $contact_id = $existing_contacts["_embedded"]["contacts"][0]["id"];

        foreach ($existing_contacts["_embedded"]["contacts"] as $existing_contact) {

            if (!empty($existing_contact["_embedded"]["leads"])) {
                foreach ($existing_contact["_embedded"]["leads"] as $existing_lead_link) {
                    $existing_lead = getEntity(CRM_ENTITY_LEAD, $existing_lead_link["id"]);



                    if (in_array($existing_lead["pipeline_id"], [1399426, 4242663, 1393867, 3302563, 6427297, 1399423, 5129982, 4551390, 1393867]) && !in_array($existing_lead["status_id"], [142, 143])) {

//                        echo "<h3>Есть активный лид {$existing_lead["id"]}</h3><pre>";
                        $we_have_active_lead = true;
                        break;
                    }
                }
                if ($we_have_active_lead) {
                    break;
                }
            }
        }
    }







    // ДАЛЬШЕ ТОЛЬКО ЕСЛИ НЕТ АКТИВНОГО ЛИДА
    if (!$we_have_active_lead) {
        // ЧАСТЬ 2 - ЕСЛИ КОНТАКТА НЕТ, СОЗДАЕМ
        if (empty($contact_id)) {
            $contact_add_data = [
                "created_at" => time(),
                "name" => $input["name"],
                "responsible_user_id" => $respUserId,
                "custom_fields_values" => []
            ];
            $contact_add_data["custom_fields_values"][] = [
                "field_id" => FIELD_ID_PHONE,
                "values" => [["value" => $input["phone"], "enum_code" => "MOB"]]
            ];




            $contact_add = addEntity(CRM_ENTITY_CONTACT, $contact_add_data);


            $contact_id = intval($contact_add["_embedded"]["contacts"][0]["id"]);
        }


        // ЧАСТЬ 3 - СОЗДАЕМ СДЕЛКУ
        $lead_add_data = [
            "created_at" => time(),
            "name" => "Лид c Авито",
            "responsible_user_id" => $respUserId,
            "pipeline_id" => $pipeline_id,
            "status_id" => $status_id,
            "custom_fields_values" => []
        ];
        $lead_add_data["_embedded"]["contacts"][]["id"] = $contact_id;

        $lead_add_data["_embedded"]["tags"][]["id"] = TAG_ID;


        $lead_add_data["custom_fields_values"][] = ["field_id" => FIELD_ID_CITY, "values" => [["value" => $input["city"]]]];


//        echo "<h3>Данные для создания сделки</h3><pre>";
//        echo json_print($lead_add_data);
//        echo "</pre>";

        $lead_add = addEntity(CRM_ENTITY_LEAD, $lead_add_data);
//        echo "<h3>Ответ на создание сделки</h3><pre>";
//        echo print_r($lead_add);
//        echo "</pre>";








        addNote("leads", $lead_add["_embedded"]["leads"][0]["id"], $input["qc"], $input["asmm"], $input["phone"], $fullName);

        addTask($lead_add["_embedded"]["leads"][0]["id"], $respUserId);





    } else {

        if(!empty($existing_lead["id"])) {
            addNote("leads", $existing_lead["id"], $input["qc"], $input["asmm"], $input["phone"], $fullName);
            addTask($existing_lead["id"], $respUserId);

        }


    }






}