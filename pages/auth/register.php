<?php
    session_start();

    $_SESSION['register_csrf'] ??= bin2hex(random_bytes(32));
    //register_csrf 값이 없으면 무작위로 32바이트 토큰 생성, 재접속 등의 경우 이전에 생성한 토큰 유지

    $error = $_SESSION['register_error'] ?? '';
    unset($_SESSION['register_error']);
    //에러 발생 시 에러 메시지 저장 후 세션 메시지 삭제
?>

<!DOCTYPE html>
<html lang = "ko">
    <head>
        <meta charset="UTF-8">
        <title>Nuvia - 회원가입</title>
    </head>
    <body>
        <h1>회원가입</h1>

        <!-- 에러 메시지 없을 때 동작 -->
        <?php if($error!==''):?>     
            <p role="alert"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
        <?php endif; ?>

        <form method="post" action="/Nuvia/actions/auth/register.php">
            <input type="hidden" name="csrf_token"
                value="<?= htmlspecialchars($_SESSION['register_csrf'], ENT_QUOTES, 'UTF-8') ?>">

            <label>
                ID <br>
                <input name="username" placeholder="아이디를 입력해주세요." autocomplete="username" maxlength="50" required>
            </label>

            <label>
                비밀번호 <br>
                <input type="password" name="password" placeholder="비밀번호를 입력해주세요." autocomplete="new-password" required>
            </label>

            <label>
                비밀번호 확인 <br>
                <input type="password" name="password_confirm" placeholder="비밀번호를 한 번 더 입력해주세요." autocomplete="new-password" required>
            </label>

            <button type="submit">회원가입</button>

            <a class="login-link" href="./login.php">로그인</a>
        </form>

    </body>
</html>