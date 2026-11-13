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



## 구현 전에 확인할 항목

- Notion은 측정값을 **온도·습도·토양 수분·측정일**로 정의합니다. Figma에는 **토양 온도·일사량·CO₂**도 보이므로 측정 항목과 단위는 DB 설계 전에 맞춰야 합니다.
- Figma에 **센서 연결** 화면과 가지치기·영양제 공급 같은 **작업 기록** 예시가 있습니다. 연결 방식과 작업 기록의 입력·저장 범위가 정해지면 해당 파일을 추가할 수 있습니다.
