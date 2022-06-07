<?php
include("funcs.php");
// ポスト
$url = 'php://input';
// セレクト
// // 接続
// $db_name = 'app_p2p';
// $pdo = local_conn_db($db_name);
// $rm_db_name = 'rahydyn_app_p2p';
// $rm_db_mysql = 'mysql57.rahydyn.sakura.ne.jp';
// $rm_db_user = 'rahydyn';
// $rm_db_pw = 'test2sakura';
// $pdo = sakura_conn_db($rm_db_name, $rm_db_mysql, $rm_db_user, $rm_db_pw);
$pdo = db_conn();

// // SQL作成
// $tbl_users = "users";
// $tbl_discussions = "discussions";

// $sql = "SELECT * FROM ".$tbl_users.";";
// $stmt = $pdo->prepare($sql);
// $status = $stmt->execute();

// // // データ表示
// $view="";
// if($status==false) {
//     //execute（SQL実行時にエラーがある場合）
//   $error = $stmt->errorInfo();
//   exit("SQL Error:".$error[2]);
// }else{
//   //Selectデータの数だけ自動でループしてくれる
//   //FETCH_ASSOC=http://php.net/manual/ja/pdostatement.fetch.php
//   while( $res = $stmt->fetch(PDO::FETCH_ASSOC)){
//     $view .= "<p>";
//     $view .= $res["id"].", ".$res["name"]; // $res["id"]や$res["name"]
//     $view .= "</p>";
//   }
// }

// // インサート
// // // POSTデータ取得
// // discussions=>
// // id
// // owner_id
// // title
// // likes
// // speaker_id
// // speaker_date
// // speaker_time
// // created_at

// //$name = filter_input( INPUT_GET, ","name" ); //こういうのもあるよ
// //$email = filter_input( INPUT_POST, "email" ); //こういうのもあるよ
// $name = $_POST["name"];
// $email = $_POST["email"];
// $naiyou = $_POST["naiyou"];
// // // 接続
// // 省略
// // // データ登録SQL作成
// $sql = "INSERT INTO gs_an_table(name, email, naiyou, indate) VALUES(:name, :email, :naiyou, sysdate());";

// $stmt = $pdo->prepare($sql);
// $stmt->bindValue(':name', $name, PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
// $stmt->bindValue(':email', $email, PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
// $stmt->bindValue(':naiyou', $naiyou, PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
// $status = $stmt->execute();  // $statusには成否がbooleanで入る

// // // データ登録処理後
// if($status==false){
//   //SQL実行時にエラーがある場合（エラーオブジェクト取得して表示）
//   $error = $stmt->errorInfo();
//   exit("SQL_Error:".$error[2]);
// }else{
//   //５．index.phpへリダイレクト
//   header("Location: index.php");
//   exit();
// }
// ここまで

if($data = @file_get_contents($url)){
    // 読み込み
    $id = json_decode($data); // json形式をphp変数に変換
    $tmp = (int) $id->id;

    // $tbl_users = "users";
    $tbl_discussions = "discussions";

    $sql = "SELECT * FROM ".$tbl_discussions." WHERE id =".$tmp.";";
    $stmt = $pdo->prepare($sql);
    $status = $stmt->execute();

    if($status==false) {
        //execute（SQL実行時にエラーがある場合）
      $error = $stmt->errorInfo();
      exit("SQL Error:".$error[2]);
    }else{
      //Selectデータの数だけ自動でループしてくれる
      //FETCH_ASSOC=http://php.net/manual/ja/pdostatement.fetch.php
      while( $res = $stmt->fetch(PDO::FETCH_ASSOC)){
        // テスト！
        $discussion = $res;
      }
    }
    $tmpid = $discussion["id"];
    $tmplikes = $discussion["likes"];
    $tmplikes += 1;
    $json_data = ["id"=>$tmpid, "likes"=>$tmplikes];

    // ここでupdateのSQL
    $sql = "UPDATE ".$tbl_discussions." SET likes = ".$tmplikes."  WHERE id = ".$tmpid.";";
    $stmt = $pdo->prepare($sql);
    $status = $stmt->execute();
    if($status==false){
        //SQL実行時にエラーがある場合（エラーオブジェクト取得して表示）
        $error = $stmt->errorInfo();
        exit("SQL_Error:".$error[2]);
    }else{
        //５．index.phpへリダイレクト
        $json_data = json_encode($json_data);
        echo $json_data; // json形式にして返す
        // header("Location: index.php");
        // exit();
    }

}else{
    //エラー処理
    if(count($http_response_header) > 0){
        //「$http_response_header[0]」にはステータスコードがセットされている
        $status_code = explode(' ', $http_response_header[0]);  //「$status_code[1]」にステータスコードの数字だけが入る
 
        //エラーの判別
        switch($status_code[1]){
            //404エラーの場合
            case 404:
                echo "指定したページが見つかりませんでした";
                break;
            //500エラーの場合
            case 500:
                echo "指定したページがあるサーバーにエラーがあります";
                break;
            //その他のエラーの場合
            default:
                echo "何らかのエラーによって指定したページのデータを取得できませんでした";
        }
    }else{
        //タイムアウトの場合 or 存在しないドメインだった場合
        echo "タイムエラー or URLが間違っています";
    }
}
 
//「$http_response_header」の初期化
$http_response_header = array();
?>
