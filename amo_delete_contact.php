<?php
    header('Content-Type: text/html; charset=utf-8');

// данные amo
$subdomain = 'tema24';
$login = '666@2810101.ru';
$hash = 'ced8d14801596715d8b197956c30b6be13612412';
$user=[
    'USER_LOGIN'=>$login,
    'USER_HASH'=>$hash
];

amoAuth($user, $subdomain);

p(allLeads($subdomain));
//p(addTaksToLeads(1742061,$subdomain));
//    p(allLeads($subdomain));
//p(delContact(355627,$subdomain));


function p($input,$die=0)
{
    echo '<pre>';
    print_r($input);
    echo '</pre>';
    if ($die) {
        die;
    }
}

// авторизация
function amoAuth($user, $subdomain)
{
    #Формируем ссылку для запроса
    $link='https://'.$subdomain.'.amocrm.ru/private/api/auth.php?type=json';
    // p($user);
    $curl=curl_init(); #Сохраняем дескриптор сеанса cURL
    #Устанавливаем необходимые опции для сеанса cURL
    curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($curl,CURLOPT_USERAGENT,'amoCRM-API-client/1.0');
    curl_setopt($curl,CURLOPT_URL,$link);
    curl_setopt($curl,CURLOPT_CUSTOMREQUEST,'POST');
    curl_setopt($curl,CURLOPT_POSTFIELDS,json_encode($user));
    curl_setopt($curl,CURLOPT_HTTPHEADER,array('Content-Type: application/json'));
    curl_setopt($curl,CURLOPT_HEADER,false);
    curl_setopt($curl,CURLOPT_COOKIEFILE,dirname(__FILE__).'/cookie.txt');
    curl_setopt($curl,CURLOPT_COOKIEJAR,dirname(__FILE__).'/cookie.txt');
    curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,0);
    curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,0);
    $out=curl_exec($curl); #Инициируем запрос к API и сохраняем ответ в переменную
    $code=curl_getinfo($curl,CURLINFO_HTTP_CODE); #Получим HTTP-код ответа сервера
    curl_close($curl); #Завершаем сеанс cURL
    getError($code);
    $response=json_decode($out,true);
    $response=$response['response'];

    if(isset($response['auth'])) {#Флаг авторизации доступен в свойстве "auth"
        return true;
    }else {
        return false;
    }

}
function linkLeadsToContact($idcontact,$idleads,$subdomain)
{
    $data =array(array(
        "to_entity_id" => $idleads,
        "to_entity_type" =>"leads"
    )
    );
    #Формируем ссылку для запроса
    $link = 'https://' . $subdomain . '.amocrm.ru/api/v4/contacts/'.$idcontact.'/link';
    $curl=curl_init(); #Сохраняем дескриптор сеанса cURL
    #Устанавливаем необходимые опции для сеанса cURL
    curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($curl,CURLOPT_USERAGENT,'amoCRM-API-client/1.0');
    curl_setopt($curl,CURLOPT_URL,$link);
    curl_setopt($curl,CURLOPT_CUSTOMREQUEST,'POST');
    curl_setopt($curl,CURLOPT_POSTFIELDS,json_encode($data));
    curl_setopt($curl,CURLOPT_HTTPHEADER,array(
        'X-Requested-With: XMLHttpRequest',
    ));
    curl_setopt($curl,CURLOPT_COOKIEFILE,dirname(__FILE__).'/cookie.txt');
    curl_setopt($curl,CURLOPT_COOKIEJAR,dirname(__FILE__).'/cookie.txt');
    curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,0);
    curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,0);

    $out = curl_exec($curl); #Инициируем запрос к API и сохраняем ответ в переменную
    $code = curl_getinfo($curl, CURLINFO_HTTP_CODE); #Получим HTTP-код ответа сервера
    $allleads = json_decode($out, false);
    $arrid = array();
    curl_close($curl); #Завершаем сеанс cURL
    getError($code);
    var_dump($allleads);
}
//// Добавить задачу к сделке КЦ:
//function addTaksToLeads($idleadks,$subdomain)
//{
//    var_dump("дошел до addTaksToLeads ");
//    $data =array(array(
//        "responsible_user_id" => '19158061',
//        "text"=> "Проанализировать склейку",
//        "complete_till"=> 1616561100,
//        "entity_id"=> $idleadks,
//        "entity_type"=> "leads",
//        "task_type_id"=> ,
//        "request_id"=> "example"
//
//    ));
//    #Формируем ссылку для запроса
//    $link = 'https://' . $subdomain . '.amocrm.ru/api/v4/tasks';
//    $curl=curl_init(); #Сохраняем дескриптор сеанса cURL
//    #Устанавливаем необходимые опции для сеанса cURL
//    curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
//    curl_setopt($curl,CURLOPT_USERAGENT,'amoCRM-API-client/1.0');
//    curl_setopt($curl,CURLOPT_URL,$link);
//    curl_setopt($curl,CURLOPT_CUSTOMREQUEST,'POST');
//    curl_setopt($curl,CURLOPT_POSTFIELDS,json_encode($data));
//    curl_setopt($curl,CURLOPT_HTTPHEADER,array(
//        'X-Requested-With: XMLHttpRequest',
//    ));
//    curl_setopt($curl,CURLOPT_COOKIEFILE,dirname(__FILE__).'/cookie.txt');
//    curl_setopt($curl,CURLOPT_COOKIEJAR,dirname(__FILE__).'/cookie.txt');
//    curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,0);
//    curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,0);
//
//    $out = curl_exec($curl); #Инициируем запрос к API и сохраняем ответ в переменную
//    $code = curl_getinfo($curl, CURLINFO_HTTP_CODE); #Получим HTTP-код ответа сервера
//    $allleads = json_decode($out, false);
//    $arrid = array();
//    curl_close($curl); #Завершаем сеанс cURL
//    getError($code);
//    var_dump($allleads);
//}
// Список сделок
function allLeads($subdomain)
{
   for ($j = 0; $j <= 10; ++$j) {
       #Формируем ссылку для запроса
       $link = 'https://' . $subdomain . '.amocrm.ru/api/v4/leads';
       $curl = curl_init(); #Сохраняем дескриптор сеанса cURL
       #Устанавливаем необходимые опции для сеанса cURL
       curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
       curl_setopt($curl, CURLOPT_USERAGENT, 'amoCRM-API-client/1.0');
       curl_setopt($curl, CURLOPT_URL, $link);
       curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
       curl_setopt($curl, CURLOPT_HTTPHEADER, array(
           'X-Requested-With: XMLHttpRequest',
       ));
       curl_setopt($curl, CURLOPT_COOKIEFILE, dirname(__FILE__) . '/cookie.txt');
       curl_setopt($curl, CURLOPT_COOKIEJAR, dirname(__FILE__) . '/cookie.txt');
       curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
       curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);

       $out = curl_exec($curl); #Инициируем запрос к API и сохраняем ответ в переменную
       $code = curl_getinfo($curl, CURLINFO_HTTP_CODE); #Получим HTTP-код ответа сервера
       $allleads = json_decode($out, false);
       $arrid = array();
       curl_close($curl); #Завершаем сеанс cURL
       getError($code);
       echo '<pre>';
       var_dump($allleads);
       var_dump(count($allleads->_embedded->items));
   }
//    for ($j = 0; $j <= count($allleads->_embedded->items); ++$j) {
//        //        for ($i = 0; $i < count($rr->_embedded->contacts); ++$i) {
//        if (($allleads->_embedded->items[$j]->status_id == 38437138) && ($allleads->_embedded->items[$j]->pipeline_id == 4053727)) {
//            $idleadxolod = $allleads->_embedded->items[$j];
//            if ($allleads->_embedded->items[$j]->contacts->id[0] != null) {
//                $idcontactxolod = (int)$allleads->_embedded->items[$j]->contacts->id[0];
//                var_dump( $idleadxolod = $allleads->_embedded->items[$j]);
//                $link = 'https://' . $subdomain . '.amocrm.ru/api/v2/companies';
//                $curl = curl_init(); #Сохраняем дескриптор сеанса cURL
//                #Устанавливаем необходимые опции для сеанса cURL
//                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
//                curl_setopt($curl, CURLOPT_USERAGENT, 'amoCRM-API-client/1.0');
//                curl_setopt($curl, CURLOPT_URL, $link);
//                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
//                curl_setopt($curl, CURLOPT_HTTPHEADER, array(
//                    'X-Requested-With: XMLHttpRequest',
//                ));
//                curl_setopt($curl, CURLOPT_COOKIEFILE, dirname(__FILE__) . '/cookie.txt');
//                curl_setopt($curl, CURLOPT_COOKIEJAR, dirname(__FILE__) . '/cookie.txt');
//                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
//                curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
//                $out = curl_exec($curl); #Инициируем запрос к API и сохраняем ответ в переменную
//                $code = curl_getinfo($curl, CURLINFO_HTTP_CODE); #Получим HTTP-код ответа сервера
//                $contact = json_decode($out, false);
//                curl_close($curl); #Завершаем сеанс cURL
//                getError($code);
//                var_dump("Comp");
//                var_dump("$contact");
//                for ($i = 0; $i <= count($contact->_embedded->items); ++$i) {
//                    if ($contact->_embedded->items[$i]->id == $idcontactxolod) {
//                        $telxolod =  (substr(preg_replace("/[^0-9]/", '', $contact->_embedded->items[$i]->custom_fields[0]->values[0]->value),1));
//                        var_dump($telxolod);
//                        var_dump('Номер телефона');
//                        allLeadsKS($telxolod,$idcontactxolod,$idleadxolod,$subdomain);
//                    }
//                }
//
//            }
//        }
//
//    }
    return true;
}
// Список леадс
function allLeadsKS($telxolod,$idcontactxolod,$idleadxolod,$subdomain)
{
    $link='https://'.$subdomain.'.amocrm.ru/api/v2/leads';
    $curl=curl_init(); #Сохраняем дескриптор сеанса cURL
    #Устанавливаем необходимые опции для сеанса cURL
    curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($curl,CURLOPT_USERAGENT,'amoCRM-API-client/1.0');
    curl_setopt($curl,CURLOPT_URL,$link);
    curl_setopt($curl,CURLOPT_CUSTOMREQUEST,'GET');
    curl_setopt($curl,CURLOPT_HTTPHEADER,array(
        'X-Requested-With: XMLHttpRequest',
    ));
    curl_setopt($curl,CURLOPT_COOKIEFILE,dirname(__FILE__).'/cookie.txt');
    curl_setopt($curl,CURLOPT_COOKIEJAR,dirname(__FILE__).'/cookie.txt');
    curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,0);
    curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,0);

    $out=curl_exec($curl); #Инициируем запрос к API и сохраняем ответ в переменную
    $code=curl_getinfo($curl,CURLINFO_HTTP_CODE); #Получим HTTP-код ответа сервера
    $allleads = json_decode($out, false);
    $arrid = array();
    curl_close($curl); #Завершаем сеанс cURL
    getError($code);
//        var_dump($allleads);
    for ($j = 0; $j <= count($allleads->_embedded->items); ++$j) {
        if (($allleads->_embedded->items[$j]->status_id != 38841718) && ($allleads->_embedded->items[$j]->pipeline_id != 4112143)) {
            $idleadks = $allleads->_embedded->items[$j]->id;
            if (($allleads->_embedded->items[$j]->contacts->id[0] != null) && ($allleads->_embedded->items[$j]->contacts->id[0] != $idcontactxolod)) {
                $idcontactks = (int)$allleads->_embedded->items[$j]->contacts->id[0];
                var_dump("Это контакты с КЦ");
                var_dump($idleadks);
                var_dump($idcontactks);
                $link = 'https://' . $subdomain . '.amocrm.ru/api/v2/contacts';
                $curl = curl_init(); #Сохраняем дескриптор сеанса cURL
                #Устанавливаем необходимые опции для сеанса cURL
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_USERAGENT, 'amoCRM-API-client/1.0');
                curl_setopt($curl, CURLOPT_URL, $link);
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
                curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                    'X-Requested-With: XMLHttpRequest',
                ));
                curl_setopt($curl, CURLOPT_COOKIEFILE, dirname(__FILE__) . '/cookie.txt');
                curl_setopt($curl, CURLOPT_COOKIEJAR, dirname(__FILE__) . '/cookie.txt');
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
                curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);

                $out = curl_exec($curl); #Инициируем запрос к API и сохраняем ответ в переменную
                $code = curl_getinfo($curl, CURLINFO_HTTP_CODE); #Получим HTTP-код ответа сервера
                $contact = json_decode($out, false);
                curl_close($curl); #Завершаем сеанс cURL
                getError($code);
                for ($i = 0; $i <= count($contact->_embedded->items); ++$i) {
                    if ($contact->_embedded->items[$i]->id == $idcontactks) {
                        $telks = substr(preg_replace("/[^0-9]/", '', $contact->_embedded->items[$i]->custom_fields[0]->values[0]->value), 1);
                        var_dump($telks);
                        var_dump($telxolod);
                        var_dump("Это номер с КЦ");
                        if ($telxolod == $telks) {
                            $leadks = $allleads->_embedded->items[$j]->id;
//                        var_dump($leadks);
//                        var_dump($idleadxolod);
                            var_dump("telxolod == telks");
                            var_dump($idcontactks);
                            var_dump($idleadxolod);
//                            delContact($idleadxolod->contacts->id[0], $subdomain);
//                            linkLeadsToContact($idcontactks, $idleadxolod->id, $subdomain);
//                            var_dump("linkLeadsToContact Это был");
//                            var_dump("добавляем задачу ");
//                            var_dump($idleadks);
//                            addTaksToLeads($idleadks,$subdomain);

                        }

                    }
                }
            }
        }

        //    #Формируем ссылку для запроса
        //    $link='https://'.$subdomain.'.amocrm.ru/api/v4/contacts';
        //    $curl=curl_init(); #Сохраняем дескриптор сеанса cURL
        //    #Устанавливаем необходимые опции для сеанса cURL
        //    curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
        //    curl_setopt($curl,CURLOPT_USERAGENT,'amoCRM-API-client/1.0');
        //    curl_setopt($curl,CURLOPT_URL,$link);
        //    curl_setopt($curl,CURLOPT_CUSTOMREQUEST,'GET');
        //    curl_setopt($curl,CURLOPT_HTTPHEADER,array(
        //        'X-Requested-With: XMLHttpRequest',
        //    ));
        //    curl_setopt($curl,CURLOPT_COOKIEFILE,dirname(__FILE__).'/cookie.txt');
        //    curl_setopt($curl,CURLOPT_COOKIEJAR,dirname(__FILE__).'/cookie.txt');
        //    curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,0);
        //    curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,0);
        //
        //    $out=curl_exec($curl); #Инициируем запрос к API и сохраняем ответ в переменную
        //    $code=curl_getinfo($curl,CURLINFO_HTTP_CODE); #Получим HTTP-код ответа сервера
        //    $rr = json_decode($out, false);
        //    $arrid = array();
        //    for ($j = 0; $j < count($rr->_embedded->contacts); ++$j) {
        //        for ($i = 0; $i < count($rr->_embedded->contacts); ++$i) {
        //            if (($rr->_embedded->contacts[$i]->custom_fields_values[0] != null) && ($rr->_embedded->contacts[$j]->custom_fields_values[0] != null) && ($j > $i)) {
        ////                $arrid[$i] = substr(preg_replace("/[^0-9]/", '', $rr->_embedded->contacts[$i]->custom_fields_values[0]->values[0]->value), 1);
        //                $number = substr(preg_replace("/[^0-9]/", '', $rr->_embedded->contacts[$j]->custom_fields_values[0]->values[0]->value), 1);
        //                $number1 = substr(preg_replace("/[^0-9]/", '', $rr->_embedded->contacts[$i]->custom_fields_values[0]->values[0]->value), 1);
        //                if($number == $number1){
        //                    $id = $rr->_embedded->contacts[$i]->id;
        //                    delContact($id,$subdomain);
        //                }
        //            }
        //        }
        //    }
        //    for ($j = 0; $j < count($rr->_embedded->contacts); ++$j) {
        //        for ($i = 0; $i < count($rr->_embedded->contacts); ++$i) {
        //            if (($rr->_embedded->contacts[$i]->custom_fields_values[1] != null) && ($rr->_embedded->contacts[$j]->custom_fields_values[1] != null) && ($j > $i)) {
        ////                $arrid[$i] = substr(preg_replace("/[^0-9]/", '', $rr->_embedded->contacts[$i]->custom_fields_values[0]->values[0]->value), 1);
        //                $email = $rr->_embedded->contacts[$j]->custom_fields_values[1]->values[0]->value;
        //                $email1 = $rr->_embedded->contacts[$i]->custom_fields_values[1]->values[0]->value;
        //                if ($email == $email1) {
        //                    $id = $rr->_embedded->contacts[$i]->id;
        //                    delContact($id, $subdomain);
        //                }
        //            }
        //        }
        //    }
        //    curl_close($curl); #Завершаем сеанс cURL
        //    getError($code);
        //    return $rr->_embedded->contacts;
    }
    return $allleads;
}
// удаление контакта
function delContact($id_contact,$subdomain)
{
    $data = [
        'request[multiactions][add][0][entity_type]' => 17,
        'request[multiactions][add][0][multiaction_type]' => 4,
        'request[multiactions][add][0][data][data][ACTION]' => 'DELETE',
        'request[multiactions][add][0][ids][]' => $id_contact
    ];
    #Формируем ссылку для запроса
    $link='https://'.$subdomain.'.amocrm.ru/ajax/v1/multiactions/set';
    $curl=curl_init(); #Сохраняем дескриптор сеанса cURL
    #Устанавливаем необходимые опции для сеанса cURL
    curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($curl,CURLOPT_USERAGENT,'amoCRM-API-client/1.0');
    curl_setopt($curl,CURLOPT_URL,$link);
    curl_setopt($curl,CURLOPT_CUSTOMREQUEST,'POST');
    curl_setopt($curl,CURLOPT_POSTFIELDS,$data);
    curl_setopt($curl,CURLOPT_HTTPHEADER,array(
        'X-Requested-With: XMLHttpRequest',
    ));
    curl_setopt($curl,CURLOPT_COOKIEFILE,dirname(__FILE__).'/cookie.txt');
    curl_setopt($curl,CURLOPT_COOKIEJAR,dirname(__FILE__).'/cookie.txt');
    curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,0);
    curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,0);

    $out=curl_exec($curl); #Инициируем запрос к API и сохраняем ответ в переменную
    $code=curl_getinfo($curl,CURLINFO_HTTP_CODE); #Получим HTTP-код ответа сервера
    curl_close($curl); #Завершаем сеанс cURL
    getError($code);

    var_dump($out);
}

// обработчик ошибок amoCRM
function getError($code)
{
    /* Теперь мы можем обработать ответ, полученный от сервера. Это пример. Вы можете обработать данные своим способом. */
    $code=(int)$code;
    $errors=array(
        301=>'Moved permanently',
        400=>'Bad request',
        401=>'Unauthorized',
        403=>'Forbidden',
        404=>'Not found',
        500=>'Internal server error',
        502=>'Bad gateway',
        503=>'Service unavailable'
    );
    try
    {
        /* Если код ответа не равен 200 или 204 - возвращаем сообщение об ошибке */
        if($code!=200 && $code!=204) {
            throw new Exception(isset($errors[$code]) ? $errors[$code] : 'Undescribed error',$code);
        }
    }
    catch(Exception $E)
    {
        die('Ошибка: '.$E->getMessage().PHP_EOL.'Код ошибки: '.$E->getCode());
    }
}
