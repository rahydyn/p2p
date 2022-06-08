<?php
include("funcs.php");

$name = $_POST["create_user"];

$tbl_users = "users";

//2. DB接続します
try {
    $pdo = db_conn();
} catch (PDOException $e) {
  exit('DBConnection Error:'.$e->getMessage());
}

//３．データ登録SQL作成
$sql = "INSERT INTO ".$tbl_users."(name) VALUES(:name);";

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':name', $name, PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$status = $stmt->execute();  // $statusには成否がbooleanで入る

//４．データ登録処理後
if($status==false){
  //SQL実行時にエラーがある場合（エラーオブジェクト取得して表示）
  $error = $stmt->errorInfo();
  exit("SQL_Error:".$error[2]);
}else{
  //５．index.phpへリダイレクト
  redirect("index.php");
  exit();
}
?>