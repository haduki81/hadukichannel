<!DOCTYPE html>
<html lang = "ja">
<head>
    <meta charset = "UTF-8">
    <title>mission_5-1</title>
</head>
<body>
<!--簡易掲示板-->
   
    <?php
        //データベース接続設定
        $dsn = 'データベース名';
        $user = 'ユーザーネーム';
        $password = 'パスワード';
        $pdo = new PDO($dsn,$user,$password,array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

        //データベースにデータテーブル作成
        $sql = "CREATE TABLE IF NOT EXISTS mission5_1"
        ."("
        //idは自動で登録されいくナンバリング
        ."id INT AUTO_INCREMENT PRIMARY KEY,"
        //名前を入れる
        ."name char(32),"
        //コメントを入れる
        ."comment TEXT,"
        //投稿日時を入れる
        ."time TEXT,"
        //パスワードを入れる
        ."pass TEXT"
        .");";
        $stmt = $pdo -> query($sql);


        //投稿フォーム処理
        //名前・コメント・パスワードが空の時は処理しない
        if(empty($_POST["name"]) && empty($_POST["comment"]) && empty($_POST["pass"])){
            //echo "空白";//デバッグ
        }elseif(empty($_POST["name"])){
            //echo "k";//デバッグ
        }elseif(empty($_POST["comment"])){
            //echo "l";//デバッグ
        }elseif(empty($_POST["pass"])){
            //echo "m";//デバッグ
        }else{
            //echo "はいってるよ〜";//デバッグ
            //"投稿"ボタンが押された時
            if($_POST["send"]){
                //echo "送信";//デバッグ
                //post受け取り
                //名前
                $name = $_POST["name"];
                //コメント
                $comment = $_POST["comment"];
                //パスワード
                $password = $_POST["pass"];
                //投稿日時
                $time = date("Y/m/d H:m:s");
                //データベースに各データを格納
                $sql = $pdo -> prepare("INSERT INTO mission5_1(name,comment,time,pass) VALUES(:name,:comment,:time,:pass)");
                $sql -> bindParam(":name",$name,PDO::PARAM_STR);
                $sql -> bindParam(":comment",$comment,PDO::PARAM_STR);
                $sql -> bindParam(":time",$time,PDO::PARAM_STR);
                $sql -> bindParam(":pass",$password,PDO::PARAM_STR);
                $sql -> execute();
            }
       }

        //削除フォーム処理
        //投稿番号・パスワードが空の時は処理しない
        if(empty($_POST["deletenumber"]) && empty($_POST["delete_pass"])){
           //echo "empty";//デバッグ
        }elseif(empty($_POST["deletenumber"])){
            //echo "em";//デバッグ
        }elseif(empty($_POST["delete_pass"])){
            //echo "pty";//デバッグ
        }else{
            //echo "full";//デバッグ
            //"削除"が押された時
            if($_POST["delete"]){
                //echo "4649"."<br>";//デバッグ
                //削除番号
                $deletenumber = $_POST["deletenumber"];
                //削除パス
                $delete_pass = $_POST["delete_pass"];
                //レコードを削除
                $sql = "SELECT * FROM mission5_1";
                $stmt = $pdo->query($sql);
                $results = $stmt->fetchAll();
                foreach($results as $row){
                    //var_dump($row);
                    //echo "<br>";                    
                    if($row[4] == $delete_pass){
                        //var_dump($row[4]);
                        $id = $deletenumber;
                        $sql = "delete from mission5_1 where id=:id";
                        $stmt = $pdo->prepare($sql);
                        $stmt->bindParam(":id",$id,PDO::PARAM_INT);
                        $stmt->execute();
                    }
                }
            }
        }

        //編集フォーム処理
        //編集番号、名前、コメント、パスワードが空の時は処理しない
        if(empty($_POST["editnumber"])){
            //echo "く";//デバッグ
        }elseif(empty($_POST["edit_name"])){
            //echo "う";//デバッグ
        }elseif(empty($_POST["edit_comment"])){
            //echo "は";//デバッグ
        }elseif(empty($_POST["edit_pass"])){
            //echo "ク";//デバッグ
        }else{
            //echo "満タン";//デバッグ
            //"編集"がおされた時
            if($_POST["edit"]){
                //echo "4946"."<br>";
                //編集番号
                $editnumber = $_POST["editnumber"];
                //名前編集
                $edit_name = $_POST["edit_name"];
                //コメント編集
                $edit_comment = $_POST["edit_comment"];
                //編集パス
                $edit_pass = $_POST["edit_pass"];
                //投稿日時
                $time = date("Y/m/d H:m:s");
                //レコードを上書き
                $sql = "SELECT * FROM mission5_1";
                $stmt = $pdo->query($sql);
                $results = $stmt->fetchAll();
                foreach($results as $row){
                    //var_dump($row);
                    if($row[4] == $edit_pass){
                        //var_dump($row[4]);
                        $id = $editnumber;//変更する投稿番号
                        $sql = "UPDATE mission5_1 SET name=:name,comment=:comment,time=:time,pass=:pass WHERE id=:id";
                        $stmt = $pdo->prepare($sql);
                        $stmt->bindParam(":name",$edit_name,PDO::PARAM_STR);
                        $stmt->bindParam(":comment",$edit_comment,PDO::PARAM_STR);
                        $stmt->bindParam(":time",$time,PDO::PARAM_STR);
                        $stmt->bindParam(":pass",$edit_pass,PDO::PARAM_STR);
                        $stmt->bindParam(":id",$id,PDO::PARAM_INT);
                        $stmt->execute();
                    }
                }    
            }
        }


    ?>

 <!--名前・コメント・パスワードフォーム作成-->
 <form action = "" method = "post">
    【投稿フォーム】<br>
    ◎ハンドルネームを入力してください<br>
    <input type = "text" name = "name" value = ""><br>
    ◎コメント<br>
    <textarea name = "comment" value = ""></textarea><br>
    ◎パスワード<br>
    <input type = "password" name = "pass" size = "10" maxlength = "8" value = ""><br>
    <!--編集の時の番号表示用-->
    <!--<input hidden = "text" name = "edithelp" value = "">-->
    <input type = "submit" name = "send" value = "投稿"><br><br>

    <!--削除フォーム作成-->
    【削除フォーム】<br>
    ◎削除したい投稿の投稿番号を入力してください<br>
    <input type = "text" name = "deletenumber" value = ""><br>
    ◎パスワード<br>
    <input type = "password" name = "delete_pass" size = "10" maxlength = "8" value = ""><br>
    <input type = "submit" name = "delete" value = "削除"><br><br>

    <!--編集フォーム作成-->
    【編集フォーム】<br>
    ◎編集した投稿の投稿番号を入力してください<br>
    <input type = "text" name = "editnumber" value = ""><br>
    ◎ハンドルネームを入力してください<br>
    <input type = "text" name = "edit_name" value = ""><br>
    ◎コメント<br>
    <textarea name = "edit_comment" value = ""></textarea><br>
    ◎パスワード<br>
    <input type = "password" name = "edit_pass" size = "10" maxlength = "8" value = "" ><br>
    <input type = "submit" name = "edit" value = "編集"><br><br>
    
    <?php
    //画面表示
    //レコード全てを表示する
    $sql = "SELECT * FROM mission5_1";
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();
    foreach($results as $row){
        echo $row["id"].",";
        echo $row["name"].",";
        echo $row["comment"].",";
        echo $row["time"]."<br>";
        echo "<hr>";
    }
    ?>
</body>
</html>
