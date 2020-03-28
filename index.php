<?php
$error_message = array();

// 名前の入力チェック
if(empty($_POST['name'])) {
$error_message[] = '名前を入力してください。';
}
// メッセージの入力チェック
if(empty($_POST['message'])) {
$error_message[] = 'メッセージを入力してください。';
}

?>

<!DOCTYPE html>

<html lang="ja">
<head>
  <meta charaset="utf-8" />
  <title>チャット</title>
  <style>
h1{
  margin-bottom: 20px;
  padding: 20px 0;
  color: #209eff;
  font-size: 122%;
  border-top: 1px solid #999;
  border-bottom: 1px solid #999;
}
label {
    display: block;
    margin-bottom: 7px;
    font-size: 86%;
}

input[type="text"],
textarea {
	margin-bottom: 20px;
	padding: 10px;
	font-size: 86%;
    border: 1px solid #ddd;
    border-radius: 3px;
    background: #fff;
}

input[type="text"] {
	width: 200px;
}
textarea {
	width: 50%;
	max-width: 50%;
	height: 70px;
}
input[type="submit"] {
	appearance: none;
    -webkit-appearance: none;
    padding: 10px 20px;
    color: #fff;
    font-size: 86%;
    line-height: 1.0em;
    cursor: pointer;
    border: none;
    border-radius: 5px;
    background-color: #37a1e5;
}
input[type=submit]:hover,
button:hover {
    background-color: #2392d8;
}

hr {
	margin: 20px 0;
	padding: 0;
}
.error_message{
  color:#ff0000;
}
  </style>
</head>
<body>

  <h1>チャット</h1>

  <?php if( !empty($error_message) ): ?>
	<ul class="error_message">
		<?php foreach( $error_message as $value ): ?>
			<li>・<?php echo $value; ?></li>
		<?php endforeach; ?>
	</ul>
  <?php endif; ?>

  <form method="post" action="index.php">
    <div>
  		<label for="name">名前</label>
  		<input id="name" type="text" name="name" value="">
  	</div>
  	<div>
  		<label for="message">メッセージ</label>
  		<textarea id="message" name="message"></textarea>
  	</div>

    <button name="send" type="submit">送信</button> <br>

  </form>
  <hr>
  <div>チャット履歴</div>


  <section>
    <?php
    //投稿内容の登録
    if (empty($error_message)){
      insert();
    }
    //投稿した内容の表示
    $stmt = select_new();
    foreach($stmt -> fetchAll(PDO::FETCH_ASSOC) as $message){
      $abx = htmlentities($message['message']);
      $abc = htmlentities($message['name']);
      echo $message['time'],": ",$abc,":",$abx;
      echo nl2br("\n");
    }

    //DB接続
    function connectDB(){
      $dbh = new PDO('mysql:host=localhost;dbname=chat','root','');
      return $dbh;
    }

    //DB内容の取得
    function select(){
      $dbh = connectDB();
      $sql = "SELECT * FROM message ORDER BY time";
      $stmt = $dbh -> prepare($sql);
      $stmt -> execute();
      return $stmt;
    }

    //DBから投稿内容を取得
    function select_new(){
      $dbh = connectDB();
      $sql = "SELECT * FROM message ORDER BY time desc ";
      $stmt = $dbh ->prepare($sql);
      $stmt ->execute();
      return $stmt;
    }

    // DBから投稿内容を登録
    function insert() {
      $dbh = connectDB();
      $sql = "INSERT INTO message (name, message, time) VALUES (:name, :message, now())";
      $stmt = $dbh->prepare($sql);
      $params = array(':name'=>$_POST['name'], ':message'=>$_POST['message']);
      $stmt->execute($params);
    }

    ?>

  </section>
</body>
</html>
