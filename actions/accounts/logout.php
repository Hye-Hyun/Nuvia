<?php
    if($_SERVER['REQUEST_METHOD'] !== 'POST'){
        header('Allow: POST');
        http_response_code(405);
        exit;
    }

    session_start();

    $token = $_POST['logout_csrf'] ?? null;
    $expectedToken = $_SESSION['logout_csrf'] ?? null;

    if(!is_string($token) || !is_string($expectedToken)
        || !hash_equals($expectedToken, $token)){
        http_response_code(403);
        exit('로그아웃 요청을 확인할 수 없습니다. 다시 시도해주세요.');
    }

    $_SESSION = [];

    //브라우저 세션 쿠키 만료
    //ini_get: php가 세션 id를 쿠키에 저장하도록 설정되어 있는지 확인
    if(ini_get('session.use_cookies')){
        $params = session_get_cookie_params(); //현재 세션 쿠키 설정 읽어옴

        setcookie(session_name(), '', [
            'expires' => time() - 3600,
            'path' => $params['path'],
            'domain' => $params['domain'],
            'secure' => $params['secure'],
            'httponly' => $params['httponly'],
        ]);
    }

    session_destroy();

    header('Location: /Nuvia/pages/auth/login.php', true, 303);
    exit;