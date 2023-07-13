<?php
// //Сбор данных из полей формы.
$name = $_POST['name'];// Берём данные из input c атрибутом name="name"
$phone = $_POST['tel']; // Берём данные из input c атрибутом name="phone"
$email = $_POST['mail']; // Берём данные из input c атрибутом name="mail"
$message = $_POST['content']; // Берём данные из input c атрибутом name="mail"


define('TELEGRAM_TOKEN', '6121550912:AAFamlKbKIQxK-T4TtihOafBNg_VLHizDIM');

define('TELEGRAM_CHATID', '-934735790');

$sitename = "kbr-mebel.ru"; //Указываем название сайта

$arr = array(

  'Заявка с сайта (заказ сайта): ' => $sitename,
  'Имя: ' => $name,
  'Телефон: ' => $phone,
  'Email: ' => $email,
  'Сообщение' => $message
);

foreach($arr as $key => $value) {
  $txt .= $key."-->".$value."%0A";
};

// токен бота
$ch = curl_init('https://api.telegram.org/bot'.TELEGRAM_TOKEN.'/sendMessage?chat_id='.TELEGRAM_CHATID.'&text='.$txt); // URL
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Не возвращать ответ
curl_exec($ch); // Делаем запрос
curl_close($ch); // Завершаем сеанс cURL

?>
