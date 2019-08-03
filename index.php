<?php
echo '
<html>
<body>
  <script>
function printMessage(){
  document.getElementById(\'message_wrap\').innerHTML = \'<p>Если вы ответите на вопросы, мы сможем предложить вам варианты мест для вечеринки. Удачного дня, cегодня</p>\';
}

function showIframe() {
  document.getElementById("iframe").style.display="block";
}

  </script>
 <div id="main_wrap" style="width: 50%; margin-right: auto; margin-left: auto;">
 <div id = "right_wrap" style="width: 40%; float: left;">
  <p>Добро пожаловать в \'Конструктор вечеринок\'</p>
 <form name="test" method="post" action="index.phtml">
  <p><b>Ваше имя:</b><br>
   <input type="text" name="userName" size="40">
  </p>
  <p><b>Место вечеринки:</b><Br>
   <input type="radio" name="location" value="cafe">Кафе и рестораны<Br>
   <input type="radio" name="location" value="house">Коттеджи<Br>
   <input type="radio" name="location" value="flat">Квартиры<Br>
  </p>
  <p>Комментарий<Br>
  <textarea name="comment" cols="40" rows="3"></textarea></p>
  <p><input type="submit" value="Отправить">
   <input type="reset" value="Очистить"></p>
 </form>
</div>
 <div id="left_wrap" style="width: 20%; float: right;">
<p>InfoTec</p>
 <div id="message_wrap"></div>
echo date("Y:m:d H:i:s"); 
</div>
<div id="location_wrap"></div>
</div>
</body>
</html>
';

function getPage($url) {
  if(!$url) {
    return false;
  }
$userAgent = 'Mozilla/5.0 (X11; Ubuntu; Linux i686; rv:67.0) Gecko/20100101 Firefox/67.0';
$options = array(

            CURLOPT_CUSTOMREQUEST  =>"GET",        //set request type post or get
            CURLOPT_POST           =>false,        //set to GET
            CURLOPT_USERAGENT      => $userAgent, //set user agent
            CURLOPT_COOKIEFILE     =>"cookie.txt", //set cookie file
            CURLOPT_COOKIEJAR      =>"cookie.txt", //set cookie jar
            CURLOPT_RETURNTRANSFER => true,     // return web page
            CURLOPT_HEADER         => false,    // don't return headers
            CURLOPT_FOLLOWLOCATION => true,     // follow redirects
            CURLOPT_ENCODING       => "",       // handle all encodings
            CURLOPT_AUTOREFERER    => true,     // set referer on redirect
            CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect
            CURLOPT_TIMEOUT        => 120,      // timeout on response
            CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects
        );

  $ch = curl_init();
  curl_setopt($ch,CURLOPT_URL,$url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt_array( $ch, $options );
  $response = curl_exec($ch);
  curl_close($ch);
  return $response;

}

if (isset($_POST['location']) && !empty($_POST['location'])) {
  $location = $_POST['location'];

if (isset($location) && !empty($location)) {
    switch ($location) {
      case 'cafe':
        $url = 'https://lmgtfy.com/?q=%D0%BA%D0%B0%D1%84%D0%B5+%D0%B8+%D1%80%D0%B5%D1%81%D1%82%D0%BE%D1%80%D0%B0%D0%BD%D1%8B+%D1%81+%D0%B2%D0%BA%D1%83%D1%81%D0%BD%D0%BE%D0%B9+%D0%B5%D0%B4%D0%BE%D0%B9+%D0%B8+%D0%BF%D1%80%D0%B8%D1%8F%D1%82%D0%BD%D0%BE%D0%B9+%D0%B0%D1%82%D0%BC%D0%BE%D1%81%D1%84%D0%B5%D1%80%D0%BE%D0%B9';
        $location = 'Кафе и рестораны. Современный выбор и убираться не придется ';
        break;
      case 'house':
        $url = 'https://lmgtfy.com/?q=%D0%BA%D0%BE%D1%82%D1%82%D0%B5%D0%B4%D0%B6%D0%B8+%D0%B8+%D0%B7%D0%B0%D0%BC%D0%BA%D0%B8';
        $location = 'Коттеджи. Традиционный выбор для серьезного веселья. Кстати есть еще более профессиональный вариант, поговаривают, что в мире еще есть несколько мастеров, способных провести такого уровня меропрития в собственном доме, но это всего лишь слухи ';
        break;
      case 'flat':
        $url = 'https://lmgtfy.com/?q=%D0%BA%D0%B2%D0%B0%D1%80%D1%82%D0%B8%D1%80%D1%83+%D1%81%D0%BD%D1%8F%D1%82%D1%8C';
        $location = 'Квартиры. Выбор для тех, кому не хватает кафе, но коттедж пока не нужен. К тому же можно найти место максимально близко ко всем участникам веселья или же близко к центру с развлечениями ';
        break;      
      default:
        $url = false;
        $location = 'ничего выбрете в следующий раз ';
        break;
    }
  }

  if ($url) {
    $content = getPage($url);
    if (!$content) {return;}
    $url='"'.$url.'"'; // в кавычки перменную чтобы js выполнился
    print '<script> content = \'<div id="iframe" style="display:none; width:100%; heigth:100%;"><iframe src="\'
    + '.$url.' + \'" name="iframe_a" height="100%" width="100%"></iframe></div><a href="\'+'.$url
    .'+\'" target="iframe_a" onclick="showIframe()">Открыть сайт</a></div>\';
     document.getElementById(\'location_wrap\').innerHTML = content;
    </script> ';
  }

}
else {
  echo " 
  <script>
  printMessage();</script>";
}

if (isset($_POST['comment']) && !empty($_POST['comment'])) {

$text = $_POST['comment'];
$text = str_replace("\n.","\n..",$text);
// сокращаем текст до 70 символов
$text = wordwrap($text,70,"\r\n");

$to      = 'ruzal.gabdrahmanoff@yandex.ru, gabdraxmanova2001@yandex.ru';
$subject = 'from AirBook user comment';
$message = $text;
$headers = 'From: ruzal.gabdrahmanoff@yandex.ru' . "\r\n" .
    'Reply-To: ruzal.gabdrahmanoff@yandex.ru' . "\r\n" .
    'X-Mailer: PHP/' . phpversion().' null, -fruzal.gabdrahmanoff@yandex.ru';


mail($to, $subject, $message, $headers);

}

?>