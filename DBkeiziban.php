<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title><データベース掲示板</title>
    </head>
    <body>
        
        
<?php
    $dsn='データベース名';
    $user='ユーザー名';
    $password="パスワード";
    $pdo=new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_WARNING));
       
    $sql="CREATE TABLE IF NOT EXISTS m51"."(
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name char(32) NOT NULL,
    comment text NOT NULL,
    day text NOT NULL,
    pass char(32) NOT NULL
    );";
    $stmt=$pdo->query($sql);
    

    //編集選択
    if(!empty($_POST["editnum"]) || !empty($_POST["edipass"])){
        $editnumber=filter_input(INPUT_POST,"editnum");
        $editpass=filter_input(INPUT_POST,"edipass");
        $sql = 'SELECT * FROM m51 WHERE id=:id';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $editnumber, PDO::PARAM_INT);
        $stmt->execute();
        $results = $stmt->fetchAll();
        
        foreach ($results as $row){
            $pass=$row['pass'];
            
            if($pass==$editpass){
                $editname=$row['name'];
                $editcomment=$row['comment'];
            }
        }
    }
       
?>

        
<form action="" method="post">
    <新規投稿用フォーム><br>
    <input type="text" name="name" placeholder="名前" value="<?php if(!empty($_POST["edit"])) {echo $editname;} ?>"><br>
    <input type="text" name="comment" placeholder="コメント" value="<?php if(!empty($_POST["edit"])) {echo $editcomment;} ?>"><br>
    <input type="hidden" name="edit-n" value="<?php if(!empty($_POST["edit"])) {echo $editnumber;} ?>">
    <input id="pass" name="password"  placeholder="パスワードを入力">
    <input type="submit" name="submit" value="送信"><br>
    
    <br><投稿削除用フォーム><br>
    <input type="text" name="deletenum" placeholder="削除したい投稿番号"><br>
    <input id="pass" name="delpass" placeholder="設定したパスワードを入力">
    <input type="submit" name="delete" value="削除"><br>
    
    <br><投稿編集用フォーム><br>
    <input type="text" name="editnum" placeholder="編集したい投稿番号"><br>
    <input id="pass" name="edipass" placeholder="設定したパスワードを入力">
    <input type="submit" name="edit" value="編集">
    </form><br>
    <hr>【投稿一覧】
    
    
<?php
    //新規投稿
    if(isset($_POST["submit"]) && empty($_POST["edit-n"])){
       $sql=$pdo->prepare("INSERT INTO m51(name, comment, day, pass) VALUES(:name, :comment, :day, :pass)");
       $sql->bindParam(':name', $name, PDO::PARAM_STR);
       $sql->bindParam(':comment', $comment, PDO::PARAM_STR);
       $sql->bindParam(':day', $day, PDO::PARAM_STR);
       $sql->bindParam(':pass', $pass, PDO::PARAM_STR);
       $name=filter_input(INPUT_POST, "name");
       $comment=filter_input(INPUT_POST, "comment");
       $day=date("Y/m/d H:i:s");
       $pass=filter_input(INPUT_POST, "password");
       $sql->execute();
    }
    
        
    //削除機能
    if(!empty($_POST["deletenum"]) && !empty($_POST["delpass"])){
        $delete = $_POST["deletenum"];
        $deletepass = $_POST["delpass"];
        $sql = 'SELECT * FROM m51 WHERE id=:id';
        $stmt = $pdo->prepare($sql);
        $stmt -> bindParam(':id', $delete, PDO::PARAM_INT);
        $stmt -> execute();
        $results = $stmt -> fetchAll();
        
        foreach ($results as $row){
            $pass=$row['pass'];
        }
            if($pass == $deletepass){
                $sql = 'delete from m51 where id=:id';
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':id', $delete, PDO::PARAM_INT);
                $stmt->execute();
            }else{
                
            }
        }
    
    
    //編集機能
    if(!empty($_POST["edit-n"])){
        $id=filter_input(INPUT_POST, "edit-n");
        $name = filter_input(INPUT_POST, "name");
        $comment = filter_input(INPUT_POST, "comment");
        $day=date("Y/m/d H:i:s");
        $sql = 'UPDATE m51 SET name=:name,comment=:comment,day=:day WHERE id=:id';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
        $stmt->bindParam(':day', $day, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }
    
    
    //表示
    $sql = 'SELECT * FROM m51';
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();
    foreach ($results as $row){
        echo "<br>";
        echo $row['id'].' ';
        echo $row['name'].' ';
        echo $row['comment'].' ';
        echo $row['day'].' ';
        echo "<br>";
        }
        ?>
    
        </form>
    </body>
</html>
