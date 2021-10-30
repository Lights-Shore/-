<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title></title>
  </head>
  <body>
    <form action="" method="post">
        <?php
        //枠組みは同じもの
        //送信・削除・編集後に受け取るものをDBへ
        //DBから取り出すことも考える
        //DBとの送受信は別々かつ同時に行う
        
        //データベース名：tb230647db
        //MySQLホスト名：localhost
        //ユーザー名：tb-230647
        //パスワード：E32VdSyYrX

        $dsn = 'データベース名';
        $user = 'ユーザー名';
        $password = 'パスワード';
        $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
        
        $sql = "CREATE TABLE IF NOT EXISTS tbtest"
        ."("
        ."id INT AUTO_INCREMENT PRIMARY KEY,"
        ."name char(32),"
        ."comment TEXT,"
        ."time TEXT"
        .");";
        $stmt = $pdo->query($sql);


        if(isset($_POST['hidden_edit'])){
            $hidden_edit = $_POST['hidden_edit'];
        }    

        if(isset($_POST['delete'])){
            $delete = $_POST['delete'];
        }    

        if(isset($_POST['edit'])){
            $edit = $_POST['edit'];
        }    
        
        if(isset($_POST['post_PW'])){
            $post_PW = $_POST['post_PW'];
        }    

        if(isset($_POST['delete_PW'])){
            $delete_PW = $_POST['delete_PW'];
        }    

        if(isset($_POST['edit_PW'])){
            $edit_PW = $_POST['edit_PW'];
        } 

        //データの入力
        if(!empty($_POST['name']) && !empty($_POST['comment'])){
            if(empty($hidden_edit) && !empty($post_PW)){
                if(isset($_POST['name']) && isset($_POST['comment'])){
                    $sql = $pdo -> prepare("INSERT INTO tbtest (name, comment, time) VALUE (:name, :comment, :time)");
                    $sql -> bindParam(':name', $name, PDO::PARAM_STR);
                    $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
                    $sql -> bindParam(':time', $time, PDO::PARAM_STR);
                    $name = $_POST['name'];
                    $comment = $_POST['comment'];
                    $time = date("Y年m月d日 H時i分s秒");
                    $sql -> execute();
                }    
            }
        //データの編集
            elseif(!empty($hidden_edit) && !empty($post_PW)){
                $id = $hidden_edit;
                if(isset($_POST['name']) && isset($_POST['comment'])){
                    $name = $_POST['name'];
                    $comment = $_POST['comment'];
                }
                $time = date("Y年m月d日 H時i分s秒");
                $sql = 'UPDATE tbtest SET name=:name,comment=:comment,time=:time WHERE id=:id';
                $stmt = $pdo->prepare($sql);
                $stmt -> bindParam(':name', $name, PDO::PARAM_STR);
                $stmt -> bindParam(':comment', $comment, PDO::PARAM_STR);
                $stmt -> bindParam(':time', $time, PDO::PARAM_STR);
                $stmt -> bindParam(':id', $id, PDO::PARAM_INT);
                $stmt -> execute();
            }
        }
        
        //データの削除
        if(!empty($delete) && !empty($delete_PW)){
            $id = $delete;
            $sql = 'delete from tbtest where id=:id';
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
        }
        
        //データの編集読み取り
        if(!empty($edit) && !empty($edit_PW)){
            $sql = 'SELECT * FROM tbtest';
            $stmt = $pdo->query($sql);
            $results = $stmt->fetchAll();
            foreach($results as $row){
                if($row['id'] == $edit){
                    $edit_name = $row['name'];
                    $edit_comment = $row['comment'];
                    $edit_id = $row['id'];
                }
            }
        }
        ?>
        <p>
            <input type="text" name="name" placeholder="名前" value="<?php if(!empty($edit_name)){echo $edit_name;}?>">
            <input type="text" name="comment" placeholder="コメント" value="<?php if(!empty($edit_comment)){echo $edit_comment;}?>">
        </p>    
        <p><input type="password" name="post_PW" placeholder="パスワード"></p>
        <p><input type="submit" value="送信"></p><hr>
        <p><input type="number" name="delete" placeholder="削除対象番号"></p>
        <p><input type="password" name="delete_PW" placeholder="パスワード"></p>
        <p><input type="submit" value="削除"></p><hr>
        <p><input type="number" name="edit" placeholder="編集対象番号"></p>
        <p><input type="password" name="edit_PW" placeholder="パスワード"></p>
        <p><input type="submit" value="編集"></p><hr>
        <p><input type="hidden" name="hidden_edit" value="<?php if(!empty($edit_id)){echo $edit_id;}?>"></p>
        
        <?php
        //データの表示
        $sql = 'SELECT * FROM tbtest';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        foreach($results as $row){
            echo $row['id'].'<br>';
            echo $row['name'].'<br>';
            echo $row['comment'].'<br>';
            echo $row['time'].'<br>';
        }
        ?>
    </form>
  </body>
</html>