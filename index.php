<?php
$error_message = array();

if(!empty($_POST['send'])){
// 名前の入力チェック
if(empty($_POST['name'])) {
$error_message[] = '名前を入力してください。';
}
// メッセージの入力チェック
if(empty($_POST['message'])) {
$error_message[] = 'メッセージを入力してください。';
}
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

h2{
  color:#209eff;
}

article{
  display:block;
  margin-top: 10px;
  padding: 10px;
  border-radius: 10px;
  background: radial-gradient( circle at 0% 0%, #ffc4d2, #7bc6e2);;
}
	.info {
		margin-bottom: 5px;
	}
	.info h3 {
    display: inline-block;
		margin-right: 10px;
		color: #222;
		font-size: 86%;
	}
	.info time {
		color: black;
		font-size: 72%;
    text-align: right;
    display: inline-block;
	}
article p {
    color: #555;
    }

  </style>
</head>
<body>

  <h1>チャット</h1>

  <?php if( !empty($error_message) ): ?>
	<ul class="error_message">
		<?php foreach( $error_message as $value ): ?>
			<li><?php echo $value; ?></li>
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

    <input name="send" type="submit" value='送信'> <br>

  </form>
  <hr>
  <section>
    <h2>チャット履歴</h2>

    <?php
    //投稿内容の登録
    if(!empty($_POST['send'])){
    if (empty($error_message)){
      insert();
    }
  }
    ?>
    <?php
    //投稿した内容の表示
    $stmt = select_new();
    ?>
    <?php foreach($stmt -> fetchAll(PDO::FETCH_ASSOC) as $message) :?>
      <?php $abx = htmlentities($message['message']);
      $abc = htmlentities($message['name']);?>

      <article>
          <div class="info">
              <h3><?php echo $abc; ?></h3>
              <time><?php echo $message['time']; ?></time>
          </div>
          <p><?php echo $abx; ?></p>
      </article>

    <?php endforeach; ?>

  </section>
</body>
</html>
