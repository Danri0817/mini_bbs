<?php
if (isset($_SESSION['id']) && $_SESSION['time'] + 3600 > time()) {
    $_SESSION['time'] = time();

    $members = $db->prepare('SELECT * FROM members WHERE id=?');
    $members ->execute(array($_SESSION['id']));

    $member = $members->fetch();
}else{
    header('Location: login.php');
    exit();
}

if (!empty($_POST)) {
    $fileName = $_FILES['image']['name'];

    if (!empty($fileName)) {
    $ext = substr($fileName, -3);

        if ($ext != 'jpg' && $ext != 'gif' && $ext != 'png') {
            $error['image'] = 'type';
        }
    }

    if ($_POST['message'] !== '' ) {

        if (strlen($_POST['message']) > 140){
            $error['message'] = 'length';
        }else {
            if (!empty($fileName)) {
                if (empty($error)) {
                    $image = date('YmdHis') . $_FILES['image']['name'];
                    move_uploaded_file($_FILES['image']['tmp_name'], 'member_picture/'. $image);
                    $_POST['image'] = $image;

                    $message = $db->prepare('INSERT INTO posts SET member_id=?, message=?, reply_message_id=?, picture=?, created=NOW()');

                    $message->execute(array(
                        $member['id'],
                        $_POST['message'],
                        $_POST['reply_post_id'],
                        $_POST['image']
                    ));

                    header('Location: index.php');
                    exit();
                }
            } else {
                $message = $db->prepare('INSERT INTO posts SET member_id=?, message=?, reply_message_id=?, created=NOW()');

                $message->execute(array(
                    $member['id'],
                    $_POST['message'],
                    $_POST['reply_post_id']
                ));

                header('Location: index.php');
                exit();
            }
        }
    } elseif(!empty($fileName)) {
        if (empty($error)) {
            $image = date('YmdHis') . $_FILES['image']['name'];

            move_uploaded_file($_FILES['image']['tmp_name'], 'member_picture/'. $image);
            $_POST['image'] = $image;

            $message = $db->prepare('INSERT INTO posts SET member_id=?, message=?, reply_message_id=?, picture=?, created=NOW()');
            $message->execute(array(
                $member['id'],
                $_POST['message'],
                $_POST['reply_post_id'],
                $_POST['image']
            ));

            header('Location: index.php');
            exit();
        }
    }
}

$page = $_REQUEST['page'];

if ($page == '') {
    $page = 1;
}
$page = max($page, 1);

$counts = $db->query('SELECT COUNT(*) AS cnt FROM posts');
$cnt = $counts->fetch();
$maxPage = ceil($cnt['cnt'] / 5);
$page = min($page, $maxPage);
$start = ($page - 1) * 5;

$posts = $db->prepare('SELECT m.name,p.* FROM members m, posts p WHERE m.id=p.member_id ORDER BY p.created DESC LIMIT ?,5');

$posts->bindParam(1, $start, PDO::PARAM_INT);
$posts->execute();


if (isset($_REQUEST['res'])) {
    $response = $db->prepare('SELECT m.name, p.* FROM members m, posts p WHERE m.id=p.member_id AND p.id=?');

    $response->execute(array($_REQUEST['res']));

    $table = $response->fetch();
    $message = '@' . $table['name'] . ' ' . $table['message'];

}
?>
