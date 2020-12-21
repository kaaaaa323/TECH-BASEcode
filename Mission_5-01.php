<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_5-01</title>
</head>
<body>
    
    <?php
    session_start();
    
    ////////////////////////////////////データベース起動!///////////////////////////////////////////
    $dsn = 'データベース';
    $user = 'ID';
    $password = 'パスワード';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
    
    //どんなデータが入っているのか。
    $sql = "CREATE TABLE IF NOT EXISTS chatter"
    ." ("
    . "id INT AUTO_INCREMENT PRIMARY KEY,"
    . "name char(32),"
    . "comment TEXT,"
    . "date TEXT"
    .");";
    $stmt = $pdo->query($sql);
    $_SESSION["next"] = 8;
    $write = 3;
    ///////////////////////////////////////////////////////////////////////////////////////////
    
    if(!empty($_POST["form"])){
        if(!empty($_POST["password3"])){
            if($_POST["password3"] == "pass"){
                $id = $_POST["form"]; // idがこの値のデータだけを抽出したい、とする
                $sql = 'SELECT * FROM chatter WHERE id=:id';
                $stmt = $pdo->prepare($sql);                  // ←差し替えるパラメータを含めて記述したSQLを準備し、
                $stmt->bindParam(':id', $id, PDO::PARAM_INT); // ←その差し替えるパラメータの値を指定してから、
                $stmt->execute();                             // ←SQLを実行する。
                $results = $stmt->fetchAll(); 
                
	            foreach ($results as $row){
		            //$rowの中にはテーブルのカラム名が入る
	                $name1 = $row['name'];
	                $comment1 = $row['comment'];
	                $_SESSION["name"] = $name1;
                    $_SESSION["comment"] = $comment1;
                    $form = $_POST["form"];
                    $_SESSION["form"] = $form;
                    $_SESSION["next"] = 1;
                    $write = 1;
	            }
            }
        }
    }
    
    ?>
    <form action="" method="post">
        【 投稿フォーム 】<BR>
        名前　　：　　<input type="text" name="name" value="<?php if(!empty($name1)){ echo $name1;} ?>" placeholder="名前"><br>
        コメント：　　<input type="text" name="comment" value="<?php if(!empty($name1)){ echo $comment1;} ?>" placeholder="コメント">
        <input type="hidden" name="form" value="<?php echo $form; ?>"><BR>
        パスワード：　<input type="password" name="password1">
        <BR><input type="submit" name="submit"><br><br>
        【 削除フォーム 】<BR>
        投稿番号：　　<input type="number" name="number" value="" placeholder="削除対象番号"><BR>
        パスワード：　<input type="password" name="password2">
        <BR><input type="submit" value="削除"><br><br>
        【 編集フォーム 】<BR>
        投稿番号：　　<input type="number" name="form" value="" placeholder="編集対象番号"><BR>
        パスワード：　<input type="password" name="password3">
        <BR><input type="submit" value="編集"><br>
        </form>
    <?php
    
    if(!empty($_POST["name"]) && !empty($_POST["comment"]) && $_SESSION["next"] != 1){ //投稿モード
        if(!empty($_POST["password1"])){
            if($_POST["password1"] == "pass"){
                $sql = $pdo -> prepare("INSERT INTO chatter (name, comment, date) VALUES (:name, :comment, :date)");
                $sql -> bindParam(':name', $name, PDO::PARAM_STR);
                $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
                $sql -> bindParam(':date', $date, PDO::PARAM_STR);
                $name = $_POST["name"];
                $comment = $_POST["comment"]; //好きな名前、好きな言葉は自分で決めること
                $date = date("Y年m月d日 H時i分s秒");
                $sql -> execute();
                $write = 1;
            }else{
                echo "パスワードが違います。"."<br>";
            }
        }else{
            echo "パスワードを入れてください。"."<br>";
        }
    }
    
    
    if(!empty($_POST["number"])){ //削除モード
        if(!empty($_POST["password2"])){
            if($_POST["password2"] == "pass"){
            
            $id = $_POST["number"];
            $sql = 'delete from chatter where id=:id';
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $write = 1;
            
            }else{
                echo "パスワードが違います。"."<br>";
            }
        }else{
            echo "パスワードを入れてください。"."<br>";
        }
    }
  
    if(!empty($_POST["name"]) && !empty($_POST["comment"]) && $_SESSION["next"] == 1){ //編集モード
    
        if(!empty($_POST["password1"])){
            if($_POST["password1"] == "pass"){
                
            $id = $_SESSION["form"];
	        $name = $_POST["name"];
	        $comment = $_POST["comment"];
	        $sql = 'UPDATE chatter SET name=:name,comment=:comment WHERE id=:id';
	        $stmt = $pdo->prepare($sql);
	        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
	        $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
	        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
	        $stmt->execute();
	        $write = 1;
            $_SESSION["next"] = 0;
                
            }else{
                echo "パスワードが違います。"."<br>";
            }
        }else{
            echo "パスワードを入れてください。"."<br>";
        }
    }
    if($write == 1){
        $id = 1 ; // idがこの値のデータだけを抽出したい、とする
        $sql = 'SELECT * FROM chatter';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        foreach ($results as $row){
	        //$rowの中にはテーブルのカラム名が入る
	        echo $row['id'].' ';
	        echo $row['name'].' ';
	        echo $row['comment'].' ';
	        echo $row['date'].'<br>';
	        echo "<hr>";
        }
    }else if($write != 1){
        if(!empty($_POST["form"])){
            if(!empty($_POST["password3"])){
                if($_POST["password3"] == "pass"){
                    $id = 1 ; // idがこの値のデータだけを抽出したい、とする
                    $sql = 'SELECT * FROM chatter';
                    $stmt = $pdo->query($sql);
                    $results = $stmt->fetchAll();
                    foreach ($results as $row){
	                    //$rowの中にはテーブルのカラム名が入る
	                    echo $row['id']." ";
	                    echo $row['name']." ";
	                    echo $row['comment']." ";
	                    echo $row['date']."<br>";
	                    echo "<hr>";
                    }
                }else{
                    echo "パスワードが違います。"."<br>";
                }
            }else{
                echo "パスワードを入れてください。"."<br>";
            }
        }
    }
    
    ?>
</body>
</html>