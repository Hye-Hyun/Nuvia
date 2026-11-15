<?php
    if($_SERVER['REQUEST_METHOD'] !== 'POST'){
        header('Allow: POST');
        http_response_code(405);
        exit;
    }

    session_start();

    function loginError(string $message): void{
        $_SESSION['login_error'] = $message;
        header('Location: /Nuvia/pages/auth/login.php', true, 303);
        exit;
    }

    $token = $_POST['login_csrf'] ?? null;
    $expectedToken = $_SESSION['login_csrf'] ?? null;

    if (!is_string($token) || !is_string($expectedToken)
        || !hash_equals($expectedToken, $token))
    {
        loginError('에러가 발생했습니다. 다시 시도해주세요.');
    }

    $username = $_POST['username'] ?? null;
    $password = $_POST['password'] ?? null;

    if (!is_string($username) || !is_string($password)){
        loginError('입력값을 확인해주세요.');
    }

    $username = trim($username);

    if($username === '' || strlen($username) > 50
        || $password === '' || strlen($password) > 72 || strpos($password, "\0") !== false){
            loginError('입력값을 확인해주세요.');
        }

    try{
        require __DIR__ . '/../../app/db.php';

        $statement = $pdo -> prepare(
            'select *
            from users
            where username = :username;'
        ); //사용자 입력 username과 테이블에 저장된 username이 동일한 row 출력 준비

        $statement -> execute([
            'username' => $username
        ]); //유저 입력 username과 테이븝 내 username 비교

        $user = $statement -> fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $exception) {
        error_log($exception -> getMessage());
        loginError('잠시 후 다시 시도해주세요.');
    }

    //username, password_hash 일치 검사
    if($user === false || !password_verify($password, $user['password_hash'])){
        loginError('아이디 혹은 비밀번호를 확인해주세요.');
    }

    if(!session_regenerate_id(true)){
        loginError('다시 시도해주세요.');
    }

    $_SESSION['user_id'] = (int) $user['ID'];
    unset($_SESSION['login_csrf'], $_SESSION['login_error']);
    header('Location: /Nuvia/pages/dashboard.php', true, 303);
    exit;



