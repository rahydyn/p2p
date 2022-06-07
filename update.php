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

$tbl_discussions = "discussions";

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
$id = $_POST["discussion_id"];
$speaker_id = $_POST["speaker_id"];
$speaker_date = $_POST["speaker_date"];
$speaker_time = $_POST["speaker_time"];

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

$json_data = $_POST;

if($data = @file_get_contents($url)){

    // ここでupdateのSQL
    $sql = "UPDATE ".$tbl_discussions." SET speaker_id='".$speaker_id."', speaker_date='".$speaker_date."', speaker_time='".$speaker_time."' WHERE id =".$id.";";

    $stmt = $pdo->prepare($sql);
    $status = $stmt->execute();
    if($status==false){
        //SQL実行時にエラーがある場合（エラーオブジェクト取得して表示）
        $error = $stmt->errorInfo();
        exit("SQL_Error:".$error[2]);
    }else{
        header("Location: index.php");
        exit();
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
