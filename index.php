<?php 

$example_persons_array = [
    [
        'fullname' => 'Иванов Иван Иванович',
        'job' => 'tester',
    ],
    [
        'fullname' => 'Степанова Наталья Степановна',
        'job' => 'frontend-developer',
    ],
    [
        'fullname' => 'Пащенко Владимир Александрович',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Громов Александр Иванович',
        'job' => 'fullstack-developer',
    ],
    [
        'fullname' => 'Славин Семён Сергеевич',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Цой Владимир Антонович',
        'job' => 'frontend-developer',
    ],
    [
        'fullname' => 'Быстрая Юлия Сергеевна',
        'job' => 'PR-manager',
    ],
    [
        'fullname' => 'Шматко Антонина Сергеевна',
        'job' => 'HR-manager',
    ],
    [
        'fullname' => 'аль-Хорезми Мухаммад ибн-Муса',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Бардо Жаклин Фёдоровна',
        'job' => 'android-developer',
    ],
    [
        'fullname' => 'Шварцнегер Арнольд Густавович',
        'job' => 'babysitter',
    ],
];


//объединение ФИО
function getFullnameFromParts ($surname, $name, $patronymic){
    return $surname .' '. $name .' '. $patronymic;
} 

//разделение ФИО
function getPartsFromFullname ($arr) {
    $dataPerson = ['surname', 'name', 'patronymic'];
    $separateFullName = array_combine($dataPerson, explode(" ", $arr));
    return $separateFullName;
}

//сокращение ФИО
function getShortName ($arr) {
    $separateFullName = getPartsFromFullname($arr);
    $name = $separateFullName['name']; 
    $shortSurname = mb_substr($separateFullName['surname'], 0, 1, "UTF-8");
    return "$name $shortSurname.";
}


//определение пола

function getGenderFromName ($arr) {
    $separateFullName = getPartsFromFullname($arr);
    $genderSum = 0;

    
    
    if (mb_substr($separateFullName['patronymic'], -3) == 'вна') $genderSum += -1;
    else if (mb_substr($separateFullName['patronymic'], -2) == 'ич') $genderSum += 1;
    
    
    if (mb_substr($separateFullName['name'], -1) == 'я') $genderSum += -1;
    else if (mb_substr($separateFullName['name'], -1) == 'й') $genderSum += 1;
    else if (mb_substr($separateFullName['name'], -1) == 'н') $genderSum += 1;

   
    if (mb_substr($separateFullName['surname'], -2) == 'ва') $genderSum += -1;
    else if (mb_substr($separateFullName['surname'], -1) == 'в') $genderSum += 1;
    
    
    $gender = $genderSum <=> 0;
    
    if ($gender == 1) $gender = "мужской";
    else if ($gender == -1) $gender = "женский";
    else if ($gender == 0) $gender = "неопределен";     

    return $gender;
}


//определение полового состава аудитории

function  getGenderDescription ($arr) {

     foreach ($arr as $key) {
         $gender_arr[] = getGenderFromName ($key['fullname']);
    }
    $man_count = count(array_filter($gender_arr,function ($key) {
        return $key == "мужской";
    }));
    
    $woman_count = count(array_filter($gender_arr,function ($key) {
        return $key == "женский";
    }));

    $undef_count = count(array_filter($gender_arr,function ($key) {
        return $key == "неопределен";
    }));

    
    $man_percent = round($man_count/count($arr)*100, 1);
    $woman_percent = round($woman_count/count($arr)*100, 1);
    $undef_percent = round($undef_count/count($arr)*100, 1);
   
    $result = <<<RESULT
Гендерный состав аудитории: <br>
--------------------------- <br>
Мужчины - $man_percent% <br>
Женщины - $woman_percent% <br>
Не удалось определить - $undef_percent% <br>
RESULT;

echo $result;
}

//определение «идеальной» пары

function getPerfectPartner ($surname, $name, $patronymic, $arr) {
    
    
    $surname = mb_convert_case($surname, MB_CASE_TITLE);
    $name = mb_convert_case($name, MB_CASE_TITLE);
    $patronymic = mb_convert_case($patronymic, MB_CASE_TITLE);

    $fullName = getFullnameFromParts($surname, $name, $patronymic);

    $shortName = getShortName($fullName);
 
    $personGender = getGenderFromName ($fullName);
   
    do {
        $i = rand(0, (count($arr)-1));
        $coupleGender = getGenderFromName($arr[$i]['fullname']);
    } while ($coupleGender ==  $personGender || $coupleGender == "неопределен");

   $shortCoupleName = getShortName($arr[$i]['fullname']);
   $compatibility = round(rand(0, 10000)/100, 2);
   $result = <<<RESULT
   $shortName + $shortCoupleName = <br>
   ♡ Идеально на $compatibility% ♡ <br>
RESULT;
   
   echo $result;     
}


$separateFullName = getPartsFromFullname("Иванов Иван Иванович");

echo "Массив ФИО: <br>";
print_r($separateFullName);
echo "<br>";
echo "<br>";

$surname = $separateFullName['surname'];
$name = $separateFullName['name'];
$patronymic = $separateFullName['patronymic'];

$fullName = getFullnameFromParts ($surname, $name, $patronymic); 
echo("Объединенное ФИО: <br>");
print_r($fullName);
echo "<br>";
echo "<br>";

$shortName = getShortName("Иванов Иван Иванович"); 
echo("Сокращенное ФИО: <br>");
print_r($shortName);
echo "<br>";
echo "<br>";

$gender = getGenderFromName("Иванов Иван Иванович"); 
echo("Пол: <br>");
print_r($gender);
echo "<br>";
echo "<br>";

getGenderDescription ($example_persons_array); 
echo "<br>";
echo "<br>";

getPerfectPartner('Иванов', 'Иван', 'Иванович', $example_persons_array);

echo "<br>";

getPerfectPartner('Петрова', 'Анна', 'Сергеевна', $example_persons_array); 
?>