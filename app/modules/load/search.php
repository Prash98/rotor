<?php
view(setting('themes').'/index');

$act = (isset($_GET['act'])) ? check($_GET['act']) : 'index';

//show_title('Поиск в файлах');

if (getUser()) {
switch ($action):
############################################################################################
##                                    Главная поиска                                      ##
############################################################################################
case "index":

    //setting('newtitle') = 'Поиск в файлах';

    echo '<div class="form"><form action="/load/search?act=search" method="get">';
    echo '<input type="hidden" name="act" value="search">';

    echo 'Запрос:<br>';
    echo '<input type="text" name="find"><br>';

    echo 'Искать:<br>';
    echo '<input name="where" type="radio" value="0" checked="checked"> В названии<br>';
    echo '<input name="where" type="radio" value="1"> В описании<br><br>';

    echo 'Тип запроса:<br>';
    echo '<input name="type" type="radio" value="0" checked="checked"> И<br>';
    echo '<input name="type" type="radio" value="1"> Или<br>';
    echo '<input name="type" type="radio" value="2"> Полный<br><br>';

    echo '<input type="submit" value="Поиск"></form></div><br>';

break;

############################################################################################
##                                          Поиск                                         ##
############################################################################################
case "search":

    $find = check(strval($_GET['find']));
    $type = abs(intval($_GET['type']));
    $where = abs(intval($_GET['where']));

    $find = str_replace(['@', '+', '-', '*', '~', '<', '>', '(', ')', '"', "'"], '', $find);

    if (!isUtf($find)){
        $find = winToUtf($find);
    }

    if (utfStrlen($find) >= 3 && utfStrlen($find) <= 50) {

        $findmewords = explode(" ", utfLower($find));

        $arrfind = [];
        foreach ($findmewords as $val) {
            if (utfStrlen($val) >= 3) {
                $arrfind[] = (empty($type)) ? '+'.$val.'*' : $val.'*';
            }
        }

        $findme = implode(" ", $arrfind);

        if ($type == 2 && count($findmewords) > 1) {
            $findme = "\"$find\"";
        }

        //setting('newtitle') = $find.' - Результаты поиска';

        $wheres = (empty($where)) ? 'title' : 'text';

        $loadfind = ($type.$wheres.$find);

        // ----------------------------- Поиск в названии -------------------------------//
        if ($wheres == 'title') {
            echo 'Поиск запроса <b>&quot;'.$find.'&quot;</b> в названии<br>';

            if (empty($_SESSION['loadfindres']) || $loadfind!=$_SESSION['loadfind']) {

                $querysearch = DB::select("SELECT `id` FROM `downs` WHERE `active`=? AND MATCH (`title`) AGAINST ('".$findme."' IN BOOLEAN MODE) LIMIT 100;", [1]);
                $result = $querysearch -> fetchAll(PDO::FETCH_COLUMN);

                $_SESSION['loadfind'] = $loadfind;
                $_SESSION['loadfindres'] = $result;
            }

            $total = count($_SESSION['loadfindres']);
            $page = paginate(setting('downlist'), $total);

            if ($total > 0) {

                echo 'Найдено совпадений: <b>'.$total.'</b><br><br>';

                $result = implode(',', $_SESSION['loadfindres']);

                $querydown = DB::select("SELECT `downs`.*, `name`, folder FROM `downs` LEFT JOIN `cats` ON `downs`.`category_id`=`cats`.`id` WHERE downs.`id` IN (".$result.") ORDER BY `time` DESC LIMIT ".$page['offset'].", ".setting('downlist').";");

                while ($data = $querydown -> fetch()) {
                    $folder = $data['folder'] ? $data['folder'].'/' : '';

                    $filesize = (!empty($data['link'])) ? formatFileSize(UPLOADS.'/files/'.$folder.$data['link']) : 0;

                    echo '<div class="b"><i class="fa fa-file-o"></i> ';
                    echo '<b><a href="/load/down?act=view&amp;id='.$data['id'].'">'.$data['title'].'</a></b> ('.$filesize.')</div>';

                    echo '<div>Категория: <a href="/load/down?cid='.$data['id'].'">'.$data['name'].'</a><br>';
                    echo 'Скачиваний: '.$data['loads'].'<br>';
                    echo 'Добавил: '.profile($data['user']).' ('.dateFixed($data['time']).')</div>';
                }

                pagination($page);
            } else {
                showError('По вашему запросу ничего не найдено!');
            }
        }
        // --------------------------- Поиск в описании -------------------------------//
        if ($wheres == 'text') {
            echo 'Поиск запроса <b>&quot;'.$find.'&quot;</b> в описании<br>';

            if (empty($_SESSION['loadfindres']) || $loadfind!=$_SESSION['loadfind']) {

                $querysearch = DB::select("SELECT `id` FROM `downs` WHERE `active`=? AND MATCH (`text`) AGAINST ('".$findme."' IN BOOLEAN MODE) LIMIT 100;", [1]);
                $result = $querysearch -> fetchAll(PDO::FETCH_COLUMN);

                $_SESSION['loadfind'] = $loadfind;
                $_SESSION['loadfindres'] = $result;
            }

            $total = count($_SESSION['loadfindres']);
            $page = paginate(setting('downlist'), $total);

            if ($total > 0) {

                echo 'Найдено совпадений: <b>'.$total.'</b><br><br>';

                $result = implode(',', $_SESSION['loadfindres']);

                $querydown = DB::select("SELECT `downs`.*, `name`, folder FROM `downs` LEFT JOIN `cats` ON `downs`.`category_id`=`cats`.`id` WHERE downs.`id` IN (".$result.") ORDER BY `time` DESC LIMIT ".$page['offset'].", ".setting('downlist').";");

                while ($data = $querydown -> fetch()) {
                    $folder = $data['folder'] ? $data['folder'].'/' : '';

                    $filesize = (!empty($data['link'])) ? formatFileSize(UPLOADS.'/files/'.$folder.$data['link']) : 0;

                    echo '<div class="b"><i class="fa fa-file-o"></i> ';
                    echo '<b><a href="/load/down?act=view&amp;id='.$data['id'].'">'.$data['title'].'</a></b> ('.$filesize.')</div>';

                    if (utfStrlen($data['text']) > 300) {
                        $data['text'] = strip_tags(bbCode($data['text']), '<br>');
                        $data['text'] = utfSubstr($data['text'], 0, 300).'...';
                    }

                    echo '<div>'.$data['text'].'<br>';

                    echo 'Категория: <a href="/load/down?cid='.$data['id'].'">'.$data['name'].'</a><br>';
                    echo 'Добавил: '.profile($data['user']).' ('.dateFixed($data['time']).')</div>';
                }

                pagination($page);
            } else {
                showError('По вашему запросу ничего не найдено!');
            }
        }

    } else {
        showError('Ошибка! Запрос должен содержать от 3 до 50 символов!');
    }

    echo '<i class="fa fa-arrow-circle-left"></i> <a href="/load/search">Вернуться</a><br>';
break;

endswitch;

} else {
    showError('Вы не авторизованы, чтобы использовать поиск, необходимо');
}

echo '<i class="fa fa-arrow-circle-up"></i> <a href="/load">Категории</a><br>';

view(setting('themes').'/foot');
