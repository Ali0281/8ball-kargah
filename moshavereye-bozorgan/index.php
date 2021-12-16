<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $question = ''; // soal
    $list_name = json_decode(file_get_contents("people.json")); // array of names -> key:eng value:farsi

    $messages_file = fopen("messages.txt", "r");
    $list_massages = array();
    $control = 0;
    while (1) {
        if (feof($messages_file)) {
            break;
        }
        $list_massages[$control] = fgets($messages_file);
        $control++;
    }


    $en_name = $_POST["person"]; // az label person html post migire
    $question = $_POST["question"]; // az label question html post migire

    $hashed = hexdec(hash("adler32", $question . $en_name));
    $mod_hash_16 = $hashed % 16;
    $msg = $list_massages[$mod_hash_16];

    foreach ($list_name as $temp_eng => $temp_fa) {
        if ($temp_eng == $en_name) {
            $fa_name = $temp_fa;
        }
    }

    $akhar_soal_eng = "/\?$/i";
    $aval_soal = "/^آیا/iu";
    $akhar_soal_fa = "/؟$/u";
    if (!(preg_match($aval_soal, $question)) || (!(preg_match($akhar_soal_fa, $question) || preg_match($akhar_soal_eng, $question)))) {
        $msg = "سوال درستی پرسیده نشده";
    }
} else {
    $question = ''; // soal
    $list_name = json_decode(file_get_contents("people.json")); // array of names -> key:eng value:farsi


    $list_name_backup = array();
    $control = 0;
    foreach ($list_name as $temp1 => $temp2) {
        $list_name_backup[$control] = $temp1;
        $control++;
    }

    $en_name = $list_name_backup[array_rand($list_name_backup)];
    foreach ($list_name as $temp_eng => $temp_fa) {
        if ($temp_eng == $en_name) {
            $fa_name = $temp_fa;
        }
    }
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="styles/default.css">
    <title>مشاوره بزرگان</title>
</head>


<body>
    <p id="copyright">تهیه شده برای درس کارگاه کامپیوتر،دانشکده کامییوتر، دانشگاه صنعتی شریف</p>
    <div id="wrapper">
        <div id="title">
            <span id="label">
                <?php
                if ($question != "") {
                    echo "پرسش:";
                }
                ?>
            </span>
            <span id="question"><?php echo $question ?></span>
        </div>
        <div id="container">
            <div id="message">
                <p><?php
                    if ($question != "") {
                        echo $msg;
                    } else {
                        echo "سوال خود را بپرس!";
                    }
                    ?>
                </p>
            </div>
            <div id="person">
                <div id="person">
                    <img src="images/people/<?php echo "$en_name.jpg" ?>" />
                    <p id="person-name"><?php echo $fa_name ?></p>
                </div>
            </div>
        </div>
        <div id="new-q">
            <form method="post">
                سوال
                <input type="text" name="question" value="<?php echo $question ?>" maxlength="150" placeholder="..." />
                را از
                <select name="person" value="<?php echo $fa_name ?>" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <?php
                    $arr_name = json_decode($temp);
                    foreach ($list_name as $temp_eng => $temp_fa) {
                        if ($temp_eng == $en_name) {
                            echo "<option value=$temp_eng selected> $temp_fa</option>";
                        } else {
                            echo "<option value=$temp_eng > $temp_fa</option>";
                        }
                    }
                    ?>
                </select>
                <input type="submit" value="بپرس" />
            </form>
        </div>
    </div>
</body>

</html>