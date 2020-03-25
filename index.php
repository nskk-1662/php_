<!DOCTYPE html>

<html lang="ja">
<head>
  <meta charaset="utf-8" />
  <title>チャット</title>
</head>
<body>

  <h1>チャット</h1>

  <form method="post" action="index.php">
    名前　<input type="text" name="name">　<br>
    メッセージ　<input type="text" name="message"> <br>

    <button name="send" type="submit">送信</button> <br>


    チャット履歴
  </form>

  <section>
    <?php

    //投稿内容の登録
      insert();
      //投稿した内容の表示
      $stmt = select_new();
      foreach($stmt -> fetchAll(PDO::FETCH_ASSOC) as $message){
        $abx = htmlentities($message['message']);
        echo $message['time'],": ",$message['name'],":",$abx;
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
