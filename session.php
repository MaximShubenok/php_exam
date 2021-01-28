<?php
include 'functions.php';
$session_id = $_GET['id'];
$questions = mysqli_fetch_all(mysqli_query($database, "SELECT * FROM questions WHERE session_id = '$session_id' ORDER BY id"), MYSQLI_BOTH);
if (empty($questions) && (!isset($_COOKIE['admin']))) {
    header('Location: index.php');
    exit();
}
$ok = false;
if (isset($_POST["submit"])) {
    $ip = $_SERVER['REMOTE_ADDR'];
    $number_1 = [];
    $i = 0;
    if (isset($_POST['number_1-1']) && !empty($_POST['number_1-1'])) {
        $i = 1;
        while (isset($_POST['number_1-' . $i])) {
            array_push($number_1, $_POST['number_1-' . $i]);
            $i++;
        }
    }
    $number_2 = [];
    if (isset($_POST['number_2-1']) && !empty($_POST['number_2-1'])) {
        $i = 1;
        while (isset($_POST['number_2-' . $i])) {
            array_push($number_2, $_POST['number_2-' . $i]);
            $i++;
        }
    }
    $text_3 = [];
    if (isset($_POST['text_3-1']) && !empty($_POST['text_3-1'])) {
        $i = 1;
        while (isset($_POST['text_3-' . $i])) {
            array_push($text_3, $_POST['text_3-' . $i]);
            $i++;
        }
    }
    $text_4 = [];
    if (isset($_POST['text_4-1']) && !empty($_POST['text_4-1'])) {
        $i = 1;
        while (isset($_POST['text_4-' . $i])) {
            array_push($text_4, $_POST['text_4-' . $i]);
            $i++;
        }
    }
    $radio_5 = [];
    if (isset($_POST['radio_5-1']) && !empty($_POST['radio_5-1'])) {
        $i = 1;
        while (isset($_POST['radio_5-' . $i])) {
            array_push($radio_5, $_POST['radio_5-' . $i]);
            $i++;
        }
    }
    $checkbox_6 = array(array());
    if (isset($_POST['checkbox_6-1']) && !empty($_POST['checkbox_6-1'])) {
        $j = 1;
        while (isset($_POST['checkbox_6-' . $j])) {
            $i = 1;
            $checkboxes = $_POST['checkbox_6-' . $j];
            while (isset($checkboxes[$i - 1])) {
                array_push($checkbox_6[$j - 1], $checkboxes[$i - 1]);
                $i++;
            }
            $j++;
        }

    }

//    print_r($number_1);
//    echo '<br>';
//    print_r($number_2);
//    echo '<br>';
//    print_r($text_3);
//    echo '<br>';
//    print_r($text_4);
//    echo '<br>';
//    print_r($radio_5);
//    echo '<br>';
//    print_r($checkbox_6);
    $counter_1 = 0;
    $counter_2 = 0;
    $counter_3 = 0;
    $counter_4 = 0;
    $counter_5 = 0;
    $counter_6 = 0;
    for ($i = 0; $i < count($questions); $i++) {
        $question_id = $questions[$i]['id'];
        $answer = '';
        $points = 0;
        switch ($questions[$i]['question_type']) {
            case 1:
                $answer = $number_1[$counter_1];
                $counter_1++;
                break;
            case 2:
                $answer = $number_2[$counter_2];
                $counter_2++;
                break;
            case 3:
                $answer = $text_3[$counter_3];
                $counter_3++;
                break;
            case 4:
                $answer = $text_4[$counter_4];
                $counter_4++;
                break;
            case 5:
                $values = explode(",", $questions[$i]['answer']);
                $answer = $radio_5[$counter_5];
                for ($j = 0; $j < count($values); $j++) {
                    if (stristr($values[$j], '-', true) == $answer) {
                        $points = preg_replace("/[^0-9]/", '', $values[$j]);
                    }
                }
                $counter_5++;
                break;
            case 6:
                $values = explode(",", $questions[$i]['answer']);
                for ($j = 0; $j < count($values); $j++) {
                    $cut_value[$j] = stristr($values[$j], '-', true);
                }
                $array = array_uintersect($cut_value, $checkbox_6[$counter_6], "strcasecmp");
                for ($j = 0; $j < count($values); $j++) {
                    if (stristr($values[$j], '-', true) == $array[$j]) {
                        $points += preg_replace("/[^0-9]/", '', $values[$j]);
                    }
                }
                $answer = implode(", ", $checkbox_6[$counter_6]);
                $counter_6++;
                break;
        }
//        echo $answer.'<br>';
//        echo $points.'<br>';
//        echo $ip . '<br>';
//        echo $session_id . '<br>';
//        echo $question_id . '<br>';
        mysqli_query($database, "INSERT INTO user_answers (ip, datetime, session_id, question_id, answer, points) VALUES ('$ip', NOW(), $session_id, $question_id, '$answer', $points)");
    }
    $ok = true;
}
?>
    <!doctype html>
    <html lang="ru">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Exam</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <link rel="stylesheet" href="css/style.css">
    </head>
    <body>
    <?php
    if (!empty($questions)) {
        echo '<form action="" method="post">';
        $counter_1 = 1;
        $counter_2 = 1;
        $counter_3 = 1;
        $counter_4 = 1;
        $counter_5 = 1;
        $counter_6 = 1;
        for ($i = 0; $i < count($questions); $i++) {
            $question = $questions[$i]['question'];
            $count = 0;
            $values = [];
            switch ($questions[$i]['question_type']) {
                case 1:
                    echo "<label>$question</label>" . '<br>';
                    echo "<input class='form-control' required type='number' name='number_1-$counter_1'>";
                    $counter_1++;
                    echo '<br>';
                    break;
                case 2:
                    echo "<label>$question</label>" . '<br>';
                    echo "<input class='form-control' required type='number' name='number_2-$counter_2' min='0'>";
                    $counter_2++;
                    echo '<br>';
                    break;
                case 3:
                    echo "<label>$question</label>" . '<br>';
                    echo "<input class='form-control' required type='text' name='text_3-$counter_3' minlength='1' maxlength='30'>";
                    $counter_3++;
                    echo '<br>';
                    break;
                case 4:
                    echo "<label>$question</label>" . '<br>';
                    echo "<textarea class='form-control' required style='resize: none; width: 200px; height: 100px' name='text_4-$counter_4' minlength='1' maxlength='30'></textarea>";
                    $counter_4++;
                    echo '<br>';
                    break;
                case 5:
                    $count = substr_count($questions[$i]['answer'], ',') + 1;
                    $values = explode(",", $questions[$i]['answer']);
                    echo "<label>$question</label>" . '<br>';
                    for ($j = 0; $j < $count; $j++) {
                        echo "<input required type='radio' name='radio_5-$counter_5' value='" . stristr($values[$j], '-', true) . "'>";
                        echo "<span>" . stristr($values[$j], '-', true) . "</span>" . '<br>';
                    }
                    $counter_5++;
                    echo '<br>';
                    break;
                case 6:
                    $count = substr_count($questions[$i]['answer'], ',') + 1;
                    $values = explode(",", $questions[$i]['answer']);
                    echo "<label>$question</label>" . '<br>';
                    for ($j = 0; $j < $count; $j++) {
                        echo "<input type='checkbox' name='checkbox_6-" . $counter_6 . "[]' value='" . stristr($values[$j], '-', true) . "'>";
                        echo "<span>" . stristr($values[$j], '-', true) . "</span>" . '<br>';
                    }
                    $counter_6++;
                    echo '<br>';
                    break;
            }
        }
        echo '<input type="submit" value="Отправить" name="submit" class="btn btn-primary">';
        echo '</form>';
        if ($ok == true) {
            echo '<br>';
            echo '<span>Форма отправлена!</span>';
        }
    }
    ?>
    <?php if (empty($questions)): ?>
        <form action="" method="post" name="add_question">
            <label for="question_type">Выберите тип вопроса</label>
            <br>
            <select class="form-control" name="question_type" id="question_type">
                <option value="1" selected>1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
                <option value="5">6</option>
            </select>
            <br>
            <label for="add_question_text">Введите фурмулеровку вопроса</label>
            <br>
            <input type="text" name="add_question_text" class="form-control" id="add_question_text">
            <br>
            <input type="submit" name="add_question_submit" class="btn btn-primary" value="Добавить вопрос"
        </form>
    <?php endif; ?>
    <script src="js/jquery-3.4.1.min.js"></script>
    <script src="js/main.js"></script>
    </body>
    </html>
<?php
mysqli_close($database);
?>