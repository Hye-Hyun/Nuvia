<?php
    session_start();

    $userId = $_SESSION['user_id'] ?? null;
    
    if(!is_int($userId) || $userId<1){
        header('Location: /Nuvia/pages/auth/login.php', true, 303);
        exit;
    }

    try{
        require __DIR__ . '/../app/db.php';

        $statement = $pdo -> prepare('select username from users where ID=:id');

        $statement -> execute([
            'id' => $userId
        ]);

        $user = $statement -> fetch(PDO::FETCH_ASSOC);

    } catch (PDOException $exception){
        error_log($exception -> getMessage());
        http_response_code(500);
        exit('회원 정보를 불러오지 못했습니다. 다시 시도해주세요.');
    }

    if($user === false){
        unset($_SESSION['user_id']);
        header('Location: /Nuvia/pages/auth/login.php', true, 303);
        exit;
    }

    $_SESSION['logout_csrf'] ??= bin2hex(random_bytes(32));
?>

<!DOCTYPE html>
<html lang="ko">
    <head>
        <meta charset='UTF-8'>
        <title>Nuvia - 마이페이지</title>
    </head>
    <body>
        <h1>마이페이지</h1>

        <a class="account_update" href="./account_update.php">
            <h5>회원 정보 수정</h5>
            <hr>
            <strong>아이디 및 비밀번호 변경</strong>
        </a>

        <form method="POST" action="/Nuvia/actions/accounts/logout.php">
            <input type="hidden" name="logout_csrf" value="<?= htmlspecialchars($_SESSION['logout_csrf'], ENT_QUOTES, 'UTF-8')?>">

            <button type="submit">로그아웃</button>
        </form>
    </body>
</html>