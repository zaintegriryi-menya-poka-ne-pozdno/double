<?php
header('Content-Type: text/html; charset=utf-8');

// данные amo
$subdomain = 'emomalisharifov98yandexru';
$login = 'emomali.sharifov98@yandex.ru';
$hash = 'aba270c13df8682df545519e6dc93135e6c787ff';
$user=[
    'USER_LOGIN'=>$login,
    'USER_HASH'=>$hash
];

amoAuth($user, $subdomain);
allContact($subdomain);

p(allContact($subdomain));
p(delContact(355627,$subdomain));


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
// Список контактов
function allContact($subdomain)
{
    #Формируем ссылку для запроса
    $link='https://'.$subdomain.'.amocrm.ru/api/v4/contacts';
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
    $rr = json_decode($out, false);
    $arrid = array();
    for ($j = 0; $j < count($rr->_embedded->contacts); ++$j) {
        for ($i = 0; $i < count($rr->_embedded->contacts); ++$i) {
            if (($rr->_embedded->contacts[$i]->custom_fields_values[0] != null) && ($rr->_embedded->contacts[$j]->custom_fields_values[0] != null) && ($j > $i)) {
//                $arrid[$i] = substr(preg_replace("/[^0-9]/", '', $rr->_embedded->contacts[$i]->custom_fields_values[0]->values[0]->value), 1);
                $number = substr(preg_replace("/[^0-9]/", '', $rr->_embedded->contacts[$j]->custom_fields_values[0]->values[0]->value), 1);
                $number1 = substr(preg_replace("/[^0-9]/", '', $rr->_embedded->contacts[$i]->custom_fields_values[0]->values[0]->value), 1);
                if($number == $number1){
                    $id = $rr->_embedded->contacts[$i]->id;
                    delContact($id,$subdomain);
                }
            }
        }
    }
    for ($j = 0; $j < count($rr->_embedded->contacts); ++$j) {
        for ($i = 0; $i < count($rr->_embedded->contacts); ++$i) {
            if (($rr->_embedded->contacts[$i]->custom_fields_values[1] != null) && ($rr->_embedded->contacts[$j]->custom_fields_values[1] != null) && ($j > $i)) {
//                $arrid[$i] = substr(preg_replace("/[^0-9]/", '', $rr->_embedded->contacts[$i]->custom_fields_values[0]->values[0]->value), 1);
                $email = $rr->_embedded->contacts[$j]->custom_fields_values[1]->values[0]->value;
                $email1 = $rr->_embedded->contacts[$i]->custom_fields_values[1]->values[0]->value;
                if ($email == $email1) {
                    $id = $rr->_embedded->contacts[$i]->id;
                    delContact($id, $subdomain);
                }
            }
        }
    }
    curl_close($curl); #Завершаем сеанс cURL
    getError($code);
    return $rr->_embedded->contacts;
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

    return $out;
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
