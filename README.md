# Nuvia

농장 구역, 작물, 환경 측정 기록을 관리하는 PHP 웹앱입니다. 현재 XAMPP 프로젝트를 기준으로, PHP 페이지에서 HTML을 렌더링하고 데스크톱과 모바일은 같은 페이지에 반응형 CSS를 적용하는 구조를 제안합니다. DB는 XAMPP의 MariaDB/MySQL 사용을 가정했습니다.

참고 자료 [Figma 화면](https://www.figma.com/design/wnm4yIsNTDIVZMuwMdiGGk/Nuvia?node-id=0-1) · [Notion 기능명세서](https://app.notion.com/p/4081ca2835ff809ca68ceb0697aff782)

## 파일 구조

아래 PHP·CSS·SQL 파일은 구현할 때 만들 파일입니다. 현재 진입점은 `index.php`입니다.

```text
Nuvia/
├── index.php                       # 진입점, 로그인 여부에 따라 화면 이동
├── pages/                          # 브라우저에서 여는 화면
│   ├── auth/
│   │   ├── login.php
│   │   └── register.php
│   ├── dashboard.php
│   ├── crops/
│   │   ├── index.php                # 작물 목록, 이름·구역·즐겨찾기 필터
│   │   ├── detail.php               # 작물 정보, 환경 요약, 최근 기록
│   │   └── form.php                 # 작물 등록·수정 공용 화면
│   ├── measurements/
│   │   ├── index.php                # 측정 이력, 작물·날짜 검색, 기간별 차트
│   │   └── form.php                 # 측정값 등록·수정
│   ├── environment/
│   │   ├── index.php                # 구역별 환경과 적정 범위
│   │   └── alerts.php               # 환경 이상 이력
│   └── account/
│       ├── index.php                # 마이페이지
│       ├── edit.php                 # 회원 정보 수정
│       └── help.php
├── actions/                         # 폼 요청과 파일 다운로드 처리
│   ├── auth/                        # login.php, register.php, logout.php
│   ├── crops/                       # save.php, toggle-favorite.php
│   ├── measurements/                # save.php, delete.php, export.php
│   ├── environment/                 # save-thresholds.php
│   └── account/                     # update.php
├── app/                             # 화면과 요청 처리 코드가 호출하는 서버 로직
│   ├── bootstrap.php                # 공통 초기화
│   ├── db.php                       # PDO 연결
│   ├── auth.php                     # 비밀번호 검증, 세션, 로그인 확인
│   ├── accounts.php                 # 계정 생성·조회·회원 정보 변경
│   ├── csrf.php                     # 폼 요청 검증
│   ├── crops.php                    # 작물과 즐겨찾기 저장·조회
│   ├── measurements.php             # 측정 기록 저장·조회·검색
│   └── environment.php              # 적정 범위 비교와 이상 이력 기록
├── includes/                        # 공통 HTML 조각
│   ├── head.php
│   ├── navigation.php               # 데스크톱 사이드바·모바일 하단 메뉴
│   └── flash.php                    # 처리 결과 안내
├── assets/
│   ├── css/                         # app.css, auth.css
│   ├── js/                          # navigation.js, charts.js
│   └── images/                      # Figma에서 내보낸 로고·아이콘
├── database/
│   └── schema.sql                   # 테이블 정의
└── README.md
```

## 백엔드 작업 순서

1. **회원가입 → 로그인 → 로그아웃.** `users` 테이블을 만들고 아이디 중복 확인, 비밀번호 해시, 세션 생성·종료를 연결합니다.
2. **작물 등록 → 목록·상세 → 즐겨찾기.** `zones`, `crops`, `favorites`를 추가합니다. Figma의 작물 등록 완료 화면은 등록 직후 상세 화면에 표시하는 완료 상태로 구성할 수 있습니다. 대시보드에서 등록 작물과 즐겨찾기 개수를 조회합니다.
3. **측정 기록 등록 → 검색.** `measurements`에 작물, 측정일, 온도, 습도, 토양 수분을 저장하고 작물 이름과 날짜로 검색합니다. 이후 같은 조회 로직을 수정·삭제 화면, CSV/Excel 다운로드, 기간별 차트에 활용합니다.
4. **환경 범위 설정 → 이상 감지·이력.** `environment_thresholds`와 `environment_alerts`를 추가하고, 측정값 저장 시 설정한 범위를 벗어나는지 확인합니다.

작물·측정 기록 등 로그인 후 접근하는 데이터는 조회와 수정 시 계정의 접근 범위를 확인합니다. 폼의 저장·수정·삭제 요청은 POST로 처리합니다. 현재 프로젝트가 XAMPP의 `htdocs` 아래에 있으므로 내부 PHP와 SQL 파일을 추가할 때 `app/`, `database/`, `includes/`의 직접 HTTP 접근을 차단합니다. DB 접속 정보는 환경 변수로 전달합니다.

## 구현 전에 확인할 항목

- Notion은 측정값을 **온도·습도·토양 수분·측정일**로 정의합니다. Figma에는 **토양 온도·일사량·CO₂**도 보이므로 측정 항목과 단위는 DB 설계 전에 맞춰야 합니다.
- Figma에 **센서 연결** 화면과 가지치기·영양제 공급 같은 **작업 기록** 예시가 있습니다. 연결 방식과 작업 기록의 입력·저장 범위가 정해지면 해당 파일을 추가할 수 있습니다.
