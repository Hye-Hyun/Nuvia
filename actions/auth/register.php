<?php
    session_start();

    if($_SERVER['REQUEST_METHOD'] !== 'POST'){
        header('Allow: POST'); //응답 허용 범위 post임을 알림
        http_response_code(405); //허용되지 않은 접근 방식
        exit;
    }//POST 요청만 받도록 확인

    function registrationError(string $message): void
    {
        $_SESSION['register_error'] = $message;
        header('Location: /Nuvia/pages/auth/register.php', true, 303);
        exit;
    }

    $token = $_POST['csrf_token'] ?? null;
    $expectedToken = $_SESSION['register_csrf'] ?? null;

    if (!is_string($token) || !is_string($expectedToken) ||
        !hash_equals($expectedToken, $token)){
            registrationError('에러가 발생했습니다. 다시 시도해주세요.');
    } //token 불일치 에러

    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $passwordConfirm = $_POST['password_confirm'] ?? '';

    if (!is_string($username) || !is_string($password)
        || !is_string($passwordConfirm)){
        registrationError('입력값을 확인해 주세요.');
    } //입력값 에러

    $username = trim($username);

    if ($password !== $passwordConfirm){
        registrationError('비밀번호가 일치하지 않아요.');
    } //비밀번호, 비밀번호 확인 검사

    if (!preg_match('/\A[A-Za-z0-9_]{3,50}\z/', $username)){
        registrationError('아이디는 3~50자로 영문, 숫자, _만 사용해 입력해 주세요.');
    } //아이디 형식 에러

    $passwordBytes = strlen($password);

    if (!preg_match('/\A[A-Za-Z0-9!@#_-]{12,72}\z/', $password)){
        registrationError('비밀번호는 12~72자로 영문, 숫자, 특수기호(!, @, #, -, _)만 사용해 입력해 주세요.');
    } //비밀번호 형식 에러

    try{
        require __DIR__ . '/../../app/db.php'; //db 연결 파일을 불러와 $pdo 준비

        $passwordHash = password_hash($password, PASSWORD_DEFAULT); //비밀번호 해시화
        $statement = $pdo -> prepare(
            'INSERT INTO users (username, password_hash)
            values (:username, :password_hash)'
        ); //insert 쿼리 준비

        $statement -> execute([
            'username' => $username,
            'password_hash'  => $passwordHash,
        ]); //users table에 데이터 저장
    } catch (PDOException $exception){
        if ((int) ($exception->errorInfo[1] ?? 0) === 1062){
            registrationError('이미 사용 중인 아이디예요.');
        }//에러코드 1062 

        error_log($exception -> getMessage());
        registrationError('잠시 후 다시 시도해 주세요.');
    }

    unset($_SESSION['register_csrf']);
    header('Location: /Nuvia/pages/auth/login.php', true, 303);
    exit;
?>
