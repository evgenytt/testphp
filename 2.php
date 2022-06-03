<?php
/**
 * @charset UTF-8
 *
 * Задание 2. Работа с массивами и строками.
 *
 * Есть список временных интервалов (интервалы записаны в формате чч:мм-чч:мм).
 *
 * Необходимо написать две функции:
 *
 *
 * Первая функция должна проверять временной интервал на валидность
 * 	принимать она будет один параметр: временной интервал (строка в формате чч:мм-чч:мм)
 * 	возвращать boolean
 *
 *
 * Вторая функция должна проверять "наложение интервалов" при попытке добавить новый интервал в список существующих
 * 	принимать она будет один параметр: временной интервал (строка в формате чч:мм-чч:мм)
 *  возвращать boolean
 *
 *  "наложение интервалов" - это когда в промежутке между началом и окончанием одного интервала,
 *   встречается начало, окончание или то и другое одновременно, другого интервала
 *
 *  пример:
 *
 *  есть интервалы
 *  	"10:00-14:00"
 *  	"16:00-20:00"
 *
 *  пытаемся добавить еще один интервал
 *  	"09:00-11:00" => произошло наложение
 *  	"11:00-13:00" => произошло наложение
 *  	"14:00-16:00" => наложения нет
 *  	"14:00-17:00" => произошло наложение
 */

$list = array (
    '09:00-11:00',
    '11:00-13:00',
    '15:00-16:00',
    '17:00-20:00',
    '20:30-21:30',
    '21:30-22:30',
);

function interval_validation($interval) {

    // в задании не указано, поэтому принял интервалы по типу 23:00 - 22:00 валидными (охватывает 23 часа)

    //  проверяем, что hh < 24 что mm < 60
    if (   substr($interval, 0,2) > 23
        || substr($interval, 3,2) > 60
        || substr($interval, 6,2) > 23
        || substr($interval, 9,2) > 60) return false;

    // проверяет, чтобы начало != конец интеравла
    if (substr($interval, 0,4) == substr($interval, 6,4))
        return false;

    // проверяем по регулярному выражению формат строки hh:mm-hh:mm
    $reg = "/[0-9]{2}:[0-9]{2}-[0-9]{2}:[0-9]{2}/";
    return preg_match($reg, $interval) == 1;
}

// конвертирование hh:mm в секунды 0..86399
function toSeconds($interval) {
    $result['start'] = (int)substr($interval, 0,2) * 60 * 60 + (int)substr($interval, 3,2) * 60;
    $result['end'] = (int)substr($interval, 6,2) * 60 * 60 + (int)substr($interval, 9,2) * 60;
    return $result;
}

function add($interval) {

    global $list;
    if (!interval_validation($interval)) return false;

    $timescale = new SplFixedArray(86400);
    $interval = toSeconds($interval);

    foreach ($list as $item) {
        $item = toSeconds($item);
        // заполняем массив суток (0..86400) из имеющихся интервалов
        for ($i = $item['start']; $i != $item['end']; $i++) {
            if ($i == 86400) $i = 0;
            $timescale[$i] = 1;
        }
        if ($timescale[$interval['start']] == 1 || $timescale[$interval['end']] == 1)
            return false;
    }
    return true;
}

// test
echo interval_validation('23:00-01:00') ? 'true' : 'false';
echo PHP_EOL;
echo interval_validation('23:00-24:00') ? 'true' : 'false';
echo PHP_EOL;
echo add('23:00-01:00') ? 'true' : 'false';
echo PHP_EOL;
echo add('23:00-09:00') ? 'true' : 'false';


