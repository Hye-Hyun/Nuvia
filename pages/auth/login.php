<?php
    session_start();

    $_SESSION['login_csrf'] ??= bin2hex(random_bytes(32));

    $error = $_SESSION['login_error'] ?? '';
    unset($_SESSION['login_error']);
?>

<!DOCTYPE html>
<html lang = "ko">
    <head>
        <meta charset="UTF-8">
        <title>Nuvia - 로그인</title>
    </head>
    <body>
        <h1>로그인</h1>

        <form method="POST" action="/Nuvia/actions/auth/login.php">

            <input type="hidden" name="login_csrf" value="<?=htmlspecialchars($_SESSION['login_csrf'], ENT_QUOTES, 'UTF-8') ?>">

            <?php if($error !== ''):?>
                <p role="alert"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8')?></p>
            <?php endif; ?>

            <label>
                ID <br>
                <input name="username" placeholder="아이디를 입력해주세요." maxlength="50" required>
            </label>

            <label>
                비밀번호 <br>
                <input type="password" name="password" placeholder="비밀번호를 입력해주세요." required>
            </label>

            <button type="submit">로그인</button>

            <a class="register-link" href="./register.php">회원가입</a>
        </form>
    </body>
</html>