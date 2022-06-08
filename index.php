<?php
include("funcs.php");

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

// ここからプログラム開始！！// ここからプログラム開始！！// ここからプログラム開始！！
// ユーザー情報読み込み
$tbl_users = "users";
$tbl_discussions = "discussions";

$sql = "SELECT * FROM ".$tbl_users.";";
$stmt = $pdo->prepare($sql);
$status = $stmt->execute();

// // データ表示
$members = [];
if($status==false) {
    //execute（SQL実行時にエラーがある場合）
  $error = $stmt->errorInfo();
  exit("SQL Error:".$error[2]);
}else{
  //Selectデータの数だけ自動でループしてくれる
  //FETCH_ASSOC=http://php.net/manual/ja/pdostatement.fetch.php
  while( $res = $stmt->fetch(PDO::FETCH_ASSOC)){
      array_push($members, array(h($res["id"]), h($res["name"])));
  }
}
$json_members = json_encode($members);

// カード情報読み込み
$tbl_users = "users";
$tbl_discussions = "discussions";

$sql = "SELECT * FROM ".$tbl_discussions.";";
$stmt = $pdo->prepare($sql);
$status = $stmt->execute();

// // データ表示
$discussions = [];
if($status==false) {
    //execute（SQL実行時にエラーがある場合）
  $error = $stmt->errorInfo();
  exit("SQL Error:".$error[2]);
}else{
  //Selectデータの数だけ自動でループしてくれる
  //FETCH_ASSOC=http://php.net/manual/ja/pdostatement.fetch.php
  while( $res = $stmt->fetch(PDO::FETCH_ASSOC)){
    // テスト！
    array_push($discussions, $res);
  }
}
$discussions = json_encode($discussions);

// var_dump($discussions);

// ユーザ編集画面
$template_user_edit = "";
console_log($members[0]);
for($i=0; $i<count($members); $i++){
    $template_user_edit .= '<div class="edit_member_unit">';
    $template_user_edit .= '<form action="edit_member.php" method="post">';
    $template_user_edit .= '<ul>';
    $template_user_edit .= '<li>id: '.$members[$i][0].'</li>';
    $template_user_edit .= '<li><input type="hidden" name="edit_id" value="'.$members[$i][0].'"></li>';
    $template_user_edit .= '<li><input type="text" name="edit_name" value="'.$members[$i][1].'"></li>';
    $template_user_edit .= '<li><input type="submit" value="編集完了"></li>';
    $template_user_edit .= '</ul>';
    $template_user_edit .= '</form>';
    $template_user_edit .= '</div>';
}



// ポスト
if($_SERVER['REQUEST_METHOD']=="POST") {
	// フォームから"POST"で送信された情報に加える処理を記述
    // // POSTデータ取得
    // discussions=>
    // id *
    // owner_id *
    // title *
    // likes *
    // speaker_id
    // speaker_date
    // speaker_time
    // created_at *

    $owner_id = $_POST["user"];
    $title = $_POST["title"];
    $likes = 0;
    $created_at = date_micro();

    // console_log($user);
    // console_log($title);

    $sql = "INSERT INTO discussions(owner_id, title, likes, created_at) VALUES(:owner_id, :title, :likes, :created_at);";

    console_log($sql);
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':owner_id', $owner_id, PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
    $stmt->bindValue(':title', $title, PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
    $stmt->bindValue(':likes', $likes, PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
    $stmt->bindValue(':created_at', $created_at, PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
    console_log("ok");
    console_log($stmt);
    $status = $stmt->execute();  // $statusには成否がbooleanで入る
    console_log($status);
    // // データ登録処理後
    if($status==false){
      //SQL実行時にエラーがある場合（エラーオブジェクト取得して表示）
      $error = $stmt->errorInfo();
      exit("SQL_Error:".$error[2]);
    }else{
      //５．index.phpへリダイレクト
      header("Location: index.php");
    //   header("Location: ".$_SERVER['PHP_SELF']);
      exit();
    }
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/style.css" media="screen">
    <script src="./js/jquery-2.1.3.min.js"></script>
    <title>p2p</title>
</head>

<body>
    <div class="container">
        <div id="create_discussion">
            <div>
                <button class="icon" id="setting"><img src="./img/setting.png" alt="設定"></button>
                <form action="" method="post">
                    <div id="form-area">
                        <div id="form-title-area">
                            <textarea id="input_title" name="title" placeholder="話題を記入してください"></textarea>
                        </div>
                        <div id="form-user-area">
                            <select id="select_owner" name="user">
                                <option value="" selected>だれ？</option>
                            </select>
                        </div>
                        <div id="form-btn-area">
                            <input type="submit" value="送信">
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- <div id="members_area" class="hidden card"> -->
        <div id="members_area" class="show card">
            <div id="create_members" >
                <div>
                    <form action="create_member.php" method="post">
                        <ul>
                            <li>新規追加</li>
                            <li><input type="text" name="create_user"></li>
                            <li><input type="submit" value="追加"></li>
                        </ul>
                    </form>
                </div>
            </div>
            <div id="edit_members" >
                <?=$template_user_edit?>
            </div>
        </div>
        <div id="contents-area">
        </div>
    <script>

        //select要素を取得する
        const selectUser = document.getElementById('select_owner');
        let members = <?= $json_members ?>;
        // console.log("member");
        // console.log(member);

        for (let i=0; i<members.length; i++){
            //option要素を新しく作る
            let option = document.createElement('option');
            //option要素にvalueと表示名を設定
            option.value = members[i][0];
            option.text = members[i][1];
            //select要素にoption要素を追加する
            selectUser.appendChild(option);
        }

        // content unit
        let discussions = <?= $discussions ?>;
        let template = "";
        for (let j=discussions.length-1; j>=0; j--){
            let id = discussions[j].id;
            template += "<div class='content-unit card' id='disc"+ id +"'>";
            template += "<ul class='content_output'>";
            template += "<li class='title'><span>";
            template += discussions[j].title;
            template += "</span></li>";
            template += "<li class='likes'>";
            template += "<span class='icon icon-likes'></span>";
            template += "<span>✕</span>";
            template += "<span>";
            template += discussions[j].likes;
            template += "</span>";
            template += "</li>";
            template += "<li class='like'><span>";
            template += "<button class='like-btn' id='like"+ id +"' onclick='add_like(this)'>";
            template += "Like!</button>";
            template += "</span></li>";
            template += "</ul>";
            if (discussions[j].speaker_id){
                template += "<ul class='content_input'>";
                template += "<li class='speaker_name'>";
                template += "<span class='icon icon-user'></span><span>";
                template += members[discussions[j].speaker_id-1][1];
                template += "</span></li>";
                template += "<li class='speaker_date'>";
                template += "<span class='icon icon-calendar'></span><span>";
                template += String(discussions[j].speaker_date).slice(5,10);
                template += "</span></li>";
                template += "<li class='speaker_time'>";
                template += "<span class='icon icon-clock'></span><span>";
                template += String(discussions[j].speaker_time).slice(0,5);
                template += "</span></li>";
                template += "</ul>";
                template += "</div>";
            }else{
                template += "<div class='test-area'>";
                template += "<form action='update.php' method='post'>";
                template += "<div class='form'>";
                template += "<ul class='update-area'>";
                template += "<li class='update-user-area'>";
                template += "<span class='icon icon-user'></span><span>";
                template += "<select required class='select_speaker' name='speaker_id' id='speaker_id"+id+"'>";
                template += "<option value='' selected>だれ？</option>";
                template += "</select>";
                template += "</span></li>";
                template += "<li class='update-user-area'>";
                template += "<span class='icon icon-calendar'></span><span>";
                template += "<select required class='select_speaker_date' name='speaker_date' id='speaker_date"+id+"'>";
                template += "<option value='' selected>いつ？</option>";
                template += "</select>";
                template += "</span></li>";
                template += "<li class='update-user-area'>";
                template += "<span class='icon icon-clock'></span><span>";
                template += "<select required class='select_speaker_time' name='speaker_time' id='speaker_time"+id+"'>";
                template += "<option value='' selected>なんじ？</option>";
                template += "</select>";
                template += "</span></li>";
                template += "<li class='update-btn-area'>";
                template += "<input type='hidden' name='discussion_id' value='"+discussions[j].id+"' >";
                template += "<input class='update-btn' type='submit' value='✓' >";
                template += "</li>";
                template += "</ul>";
                template += "</div>";
                template += "</form>";
                template += "</div>";
            }
            template += "</div>";
        }
        $("#contents-area").html(template);
        let selectSpeakers = $('.select_speaker');
        for (let j=0, len=selectSpeakers.length|0; j<len; j+=1|0){
            for (let i=0; i<members.length; i++){
                //option要素を新しく作る
                option = document.createElement('option');
                //option要素にvalueと表示名を設定
                option.value = members[i][0];
                option.text = members[i][1];
                //select要素にoption要素を追加する
                selectSpeakers[j].appendChild(option);
            }
        }
        let date_options = [];
        for (let i=0; i<14; i++){
            let today = new Date();
            today.setDate(today.getDate()+i);
            let year = today.getFullYear();
            let month = today.getMonth()+1;
            let day = today.getDate();
            date_options.push(year+"/"+("00"+month).slice(-2)+"/"+("00"+day).slice(-2))
        }

        let selectSpeakerDates = $(".select_speaker_date");
        for (let j=0, len=selectSpeakerDates.length|0; j<len; j+=1|0){
            for (let i=0; i<date_options.length; i++){
                //option要素を新しく作る
                option = document.createElement('option');
                //option要素にvalueと表示名を設定
                option.value = date_options[i];
                option.text = date_options[i].slice(5,10);
                //select要素にoption要素を追加する
                selectSpeakerDates[j].appendChild(option);
            }
        }
        let time_options = [];
        for (let i=0; i<24; i++){
            let hour=("00"+i).slice(-2);
            time_options.push(hour+":00");
            time_options.push(hour+":30");
        }
        let selectSpeakerTimes = $(".select_speaker_time");
        for (let j=0, len=selectSpeakerTimes.length|0; j<len; j+=1|0){
            for (let i=0; i<time_options.length; i++){
                //option要素を新しく作る
                option = document.createElement('option');
                //option要素にvalueと表示名を設定
                option.value = time_options[i];
                option.text = time_options[i];
                //select要素にoption要素を追加する
                selectSpeakerTimes[j].appendChild(option);
            }
        }
        
        $(function(){
            $("#setting").on("click", function(){
                $("#members_area").toggleClass("hidden");
                $("#members_area").toggleClass("show");
                console.log("click");
            });
        });

        $(function(){
            $('#form-btn-area > input').on('click', function(){
                let msg = "";
                if($('#input_title').val() === ''){
                    msg += '文章を入力してください！';
                }
                if($('#select_owner').val() === ''){
                    msg += '名前を入力してください！';
                }
                if(msg != ""){
                    alert(msg);
                    return false;
                }else {
                    return true;
                }
            });
        });

        let targets = document.getElementsByClassName('update-btn');
        for(let i = 0; i < targets.length; i++){
            targets[i].addEventListener("click",() => {
                let msg = "";
                if((this.parentElement.parentElement .select_speaker).val() == ''){
                    msg += '名前を入力してください！';
                }
                if((this.parent().parent() .select_speaker_date).val() == ''){
                    msg += '日付を入力してください！';
                }
                if((this.parent().parent() .select_speaker_time).val() == ''){
                    msg += '時間を入力してください！';
                }
                if(msg != ""){
                    alert(msg);
                    return false;
                }else {
                    return true;
                }
            }, false);
        }

        function add_like(elem){
            const param = {
                id: elem.id.slice(4),
                dummy: "dummy"
            }
            console.log(param);
            const json_data = JSON.stringify(param);
            fetch('save.php', { // 第1引数に送り先
                method: 'POST', // メソッド指定
                headers: { 'Content-Type': 'application/json' }, // jsonを指定
                body: json_data // json形式に変換して添付
            })
            .then(response => {
                // console.log("response.json()");  // 返ってきたレスポンスをjsonで受け取って次のthenへ渡す
                // console.log(response.json());  // 返ってきたレスポンスをjsonで受け取って次のthenへ渡す
                return response.json()  // 返ってきたレスポンスをjsonで受け取って次のthenへ渡す
            })
            .then(res => {
                console.log("res"); // 返ってきたデータ
                console.log(res); // 返ってきたデータ
                return location.reload();
            });
        }
    </script>
</body>



</html>
<?php

?>