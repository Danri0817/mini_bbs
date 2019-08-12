<?php
session_start();
require('dbconnect.php');
require('intro.php');

?>

<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8"
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>ひとこと掲示板</title>

	<link rel="stylesheet" href="style.css" />
</head>

<body>
<div id="wrap">
  <div id="head">
    <h1>ひとこと掲示板</h1>
  </div>
  <div id="content">
  	<div style="text-align: right"><a href="logout.php">ログアウト</a></div>
    <form action="" method="post" enctype="multipart/form-data">
      <dl>
        <dt><?php print(htmlspecialchars($member['name'], ENT_QUOTES)) ?>さん、メッセージをどうぞ</dt>
				<dt>写真</dt>
				<dd>
		      <input type="file" name="image" size="35" value="test"  />
					<?php if ($error['image'] === 'type'): ?>
		  			<p class="error">*写真などは「.gif」または、「.jpg」「.png」の画像を指定してください</p>
					<?php endif; ?>
					<?php if (!empty($error)): ?>
						<p class="error">*恐れ入りますが、画像を改めて指定してください</p>
					<?php endif; ?>
		    </dd>
        <dd>
					<p style="color: red;">*140文字を超えてはいけません</p>
          <textarea name="message" cols="50" rows="5"><?php print(htmlspecialchars($message, ENT_QUOTES)); ?></textarea>
					<?php if ($error['message'] === 'length'): ?>
		  			<p class="error">*140文字を超えています</p>
					<?php endif; ?>
          <input type="hidden" name="reply_post_id" value="<?php print(htmlspecialchars($_REQUEST['res'], ENT_QUOTES)); ?>" />
        </dd>
      </dl>
      <div>
        <p>
          <input type="submit" value="投稿する" />
        </p>
      </div>
    </form>

	<?php foreach ($posts as $post): ?>
		<div class="msg">
			<?php if ($post['picture']): ?>
	    	<img src="member_picture/<?php print(htmlspecialchars($post['picture'], ENT_QUOTES)); ?>" width="48" height="48"  />
			<?php endif ?>
			<p>
				<?php print(htmlspecialchars($post['message'], ENT_QUOTES)); ?>
				<span class="name">
					（<?php print(htmlspecialchars($post['name'], ENT_QUOTES)); ?>）
				</span>
				[<a href="index.php?res=<?php print(htmlspecialchars($post['id'], ENT_QUOTES)); ?>">Re</a>]
			</p>
	    <p class="day"><a href="view.php?id=<?php print(htmlspecialchars($post['id'], ENT_QUOTES)); ?>"><?php print(htmlspecialchars($post['created'], ENT_QUOTES)); ?></a>
			<?php if($post['reply_message_id'] > 0): ?>
				<a href="view.php?id=<?php print(htmlspecialchars($post['reply_message_id'], ENT_QUOTES)); ?>">
				返信元のメッセージ</a>
			<?php endif; ?>
			<?php if ($_SESSION['id'] == $post['member_id']): ?>
				[<a href="delete.php?id=<?php print(htmlspecialchars($post['id'], ENT_QUOTES)); ?>"
				style="color: #F33;">削除</a>]
			<?php endif; ?>
	    </p>
	  </div>
	<?php endforeach; ?>


<ul class="paging">
	<?php if($page > 1): ?>
	  <li><a href="index.php?page=<?php print($page - 1); ?>">前のページへ</a></li>
	<?php else: ?>
	  <li>前のページへ</li>
	<?php endif; ?>
	<?php if($page < $maxPage): ?>
	  <li><a href="index.php?page=<?php print($page + 1); ?>">次のページへ</a></li>
	<?php else: ?>
	  <li>次のページへ</li>
	<?php endif; ?>
</ul>
  </div>
</div>
</body>
</html>
