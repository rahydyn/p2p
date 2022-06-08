<?php
include("funcs.php");
$pdo = db_conn();
$tbl_users = "users";

$user_id = $_POST["edit_id"];
$user_name = $_POST["edit_name"];

$sql = "UPDATE ".$tbl_users." SET name='".$user_name."' WHERE id =".$user_id.";";
$stmt = $pdo->prepare($sql);
$status = $stmt->execute();
// // データ表示
if($status==false) {
    //execute（SQL実行時にエラーがある場合）
  $error = $stmt->errorInfo();
  exit("SQL Error:".$error[2]);
}else{
  //Selectデータの数だけ自動でループしてくれる
  //FETCH_ASSOC=http://php.net/manual/ja/pdostatement.fetch.php
  header("Location: index.php");
}

?>