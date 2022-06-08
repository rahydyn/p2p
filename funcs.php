<?php
include("secret.php");
// 以下の$sakura_idと$sakura_pwを取得しています。今回は.gitignoreでこちらを見えなくおります。
// 直接書き換えてください。
$sakura_id = sakuraID();
$sakura_pw = sakuraPW();


// XSS対応関数
function h($s){
    return htmlspecialchars($s, ENT_QUOTES);
}
// console.log
function console_log($input){
    echo "<script>";
    echo "console.log(".json_encode($input).")";
    echo "</script>";
}

//DB接続関数：db_conn()
function db_conn(){
  try {
      //localhostの場合
      $db_name = "app_p2p";    //データベース名
      $db_id   = "root";      //アカウント名
      $db_pw   = "";          //パスワード：XAMPPはパスワード無しに修正してください。
      $db_host = "localhost"; //DBホスト

      //localhost以外＊＊自分で書き直してください！！＊＊
      if($_SERVER["HTTP_HOST"] != 'localhost'){
          $db_name = "rahydyn_app_p2p";  //データベース名
          $db_id   = $sakura_id;  //アカウント名（さくらコントロールパネルに表示されています）
          $db_pw   = $sakura_pw;  //パスワード(さくらサーバー最初にDB作成する際に設定したパスワード)
          $db_host = "mysql639.db.sakura.ne.jp"; //例）mysql**db.ne.jp...
      }
      return new PDO('mysql:dbname='.$db_name.';charset=utf8;host='.$db_host, $db_id, $db_pw);
  } catch (PDOException $e) {
      exit('DB Connection Error:'.$e->getMessage());
  }
}

//SQLエラー関数：sql_error($stmt)
function sql_error($stmt){
  $error = $stmt->errorInfo();
  exit("SQLError:".$error[2]);
}

//リダイレクト関数: redirect($file_name)
function redirect($file_name){
  header("Location: ".$file_name);
  header("Location: {$file_name}");  // テンプレートリテラルでも！
  exit();
}

function date_micro(){
    # microtimeからタイムスタンプを取得
    # リファレンス：http://php.net/manual/ja/function.microtime.php
    $dtStr = date("Y-m-d H:i:s") . "." . substr(explode(".", (microtime(true) . ""))[1], 0, 3);
    return $dtStr; # => 2016-04-12 12:40:46.049
}

?>
