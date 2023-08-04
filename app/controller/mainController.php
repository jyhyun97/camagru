<?php
include_once 'app/model/mainModel.php';
class MainController
{
    private static $model = null;

    public static function getModel()
    {
        if (self::$model === null) {
            self::$model = new MainModel('camagru');
        }
        return self::$model;
    }

    public static function getMain()
    {
        include_once 'app/view/main.php';
    }
    public static function getPost()
    {
        $currentUrl = $_SERVER['REQUEST_URI'];
        $urlExplode = explode("/", $currentUrl);
        if (count($urlExplode) === 3 && self::getPostByPostId($urlExplode[2]) !== false) {
            include_once 'app/view/post.php';
        } else {
            http_response_code(404);
            include_once('app/view/404.php');
        }
    }
    public static function getMypage()
    {
        $currentUrl = $_SERVER['REQUEST_URI'];
        $urlExplode = explode("/", $currentUrl);
        if (count($urlExplode) === 2) {
            include_once 'app/view/mypage.php';
        } else {
            http_response_code(404);
            include_once('app/view/404.php');
        }
    }
    public static function getUpload()
    {
        $currentUrl = $_SERVER['REQUEST_URI'];
        $urlExplode = explode("/", $currentUrl);
        if (count($urlExplode) === 2) {
            include_once 'app/view/upload.php';
        } else {
            http_response_code(404);
            include_once('app/view/404.php');
        }
    }
    /**
     * [영숫자-_.]*@[영숫자-_.]*.[영숫자]
     */
    public static function validateEmail($email)
    {
        $regex = "/^[a-zA-Z0-9]([-_.]?[a-zA-Z0-9])*@[a-zA-Z0-9]([-_.]?[a-zA-Z0-9])*[.][a-zA-Z0-9]*$/";
        return preg_match($regex, $email);
    }
    /**
     * 5글자 이상 20글자 이하의 영숫자만 허용
     */
    public static function validateUsername($username)
    {
        $regex = "/^[a-zA-Z0-9]{5,20}$/";
        return preg_match($regex, $username);
    }
    /**
     * 8글자 이상 20글자 이하의 숫자, 소문자, 대문자 특문 허용
     * 위 4가지 요소 중 최소 두 가지 조합 사용하도록 할 것
     */
    public static function validatePassword($password)
    {
        $regex = "/^[a-zA-Z0-9`~!@#$%^&*|\\\'\";:\/?]{8,20}$/";
        $combCount = 0;
        if (self::is_num($password) === true)
            $combCount++;
        if (self::is_lower($password) === true)
            $combCount++;
        if (self::is_upper($password) === true)
            $combCount++;
        if (self::is_special($password) === true)
            $combCount++;
        return (preg_match($regex, $password) && $combCount >= 2);
    }
    private static function is_num($text) {
        for ($i = 0; $i < strlen($text); $i++) {
            if (ctype_digit($text[$i]))
                return true;
        }
        return false;
    }
    private static function is_upper($text) {
        for ($i = 0; $i < strlen($text); $i++) {
            if (ctype_upper($text[$i]))
                return true;
        }
        return false;
    }
    private static function is_lower($text){
        for ($i = 0; $i < strlen($text); $i++) {
            if (ctype_lower($text[$i]))
                return true;
        }
        return false;
    }

    private static function is_special($text)
    {
        $flag = false;
        if (self::containSpecial($text, '`~!@#$%^&*|\\\'\";:\/?') === true)
            $flag = true;
        return $flag;
    }
    private static function containSpecial($haystack, $needles)
    {
        for ($i = 0; $i < strlen($needles); $i++) {
            $needle = $needles[$i];
            if (strpos($haystack, $needle) !== false)
              return true;
          }
          return false;
    }
    

    /**
     * query 결과에 따라 중복된 이메일, 유저네임 알리기
     * [테스트]
     */
    public static function postSignup()
    {
        $data = json_decode(strip_tags(file_get_contents("php://input")));

        $email = $data->email;
        $username = $data->username;
        $password = $data->password;
        $authCode = $data->authCode;

        $response = self::postSignupProcess($email, $username, $password, $authCode);
        return print_r(json_encode($response));
    }

    public static function postSignupProcess($email, $username, $password, $authCode)
    {
        if ($authCode !== $_SESSION['auth_code']) {
            http_response_code(400);
            header('Content-type: application/json; charset=utf-8');
            $body = array();
            $body['message'] = '인증번호가 올바른지 확인해주세요.';
            return $body;
        }
        $dupCheck = self::getModel()->checkDupSignup($email, $username);
        if ($dupCheck['success'] === false) {
            http_response_code(409);
            header('Content-type: application/json; charset=utf-8');
            $body = array();
            $body['message'] = $dupCheck['message'];
            return $body;
        }
        self::getModel()->postSignup($email, $username, $password);
        http_response_code(201);
        unset($_SESSION['auth_code']);
        return;
    }

    public static function postSignupAuth()
    {
        $data = json_decode(strip_tags(file_get_contents("php://input")));

        $email = $data->email;
        $username = $data->username;
        $password = $data->password;

        if (
            !self::validateEmail($email) || !self::validateUsername($username) ||
            !self::validatePassword($password)
        ) {
            http_response_code(400);
            header('Content-type: application/json; charset=utf-8');
            $body = array();
            if (!self::validateEmail($email))
                $body['message'] = '이메일 규칙을 확인해주세요.';
            else if (!self::validateUsername($username))
                $body['message'] = '닉네임 규칙을 확인해주세요.';
            else
                $body['message'] = '비밀번호 규칙을 확인해주세요';
            return print_r(json_encode($body));
        }
        $dupCheck = self::getModel()->checkDupSignup($email, $username);
        if ($dupCheck['success'] === false) {
            http_response_code(409);
            header('Content-type: application/json; charset=utf-8');
            $body = array();
            $body['message'] = $dupCheck['message'];
            return print_r(json_encode($body));
        }
        $subject = 'camagru 회원 가입 메일';
        $_SESSION['auth_code'] = sprintf('%06d', rand(000000, 999999));
        ;
        $mailBody = $_SESSION['auth_code'] . ' 코드를 입력해 회원가입을 완료해주세요.';

        $body = array();
        $body['success'] = self::sendMail($email, $subject, $mailBody);
        $body['email'] = $email;
        $body['subject'] = $subject;
        $body['body'] = $mailBody;
        http_response_code(200);
        return print_r(json_encode($body));
    }

    private static function sendMail($email, $subject, $mailBody)
    {
        $subject = "=?UTF-8?B?" . base64_encode($subject) . "?=";
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= sprintf('Content-Type: text/plain; charset=utf-8' . "\r\n");
        $headers .= sprintf('From: wjddus2005@naver.com');
        $additionalHeaders = "-f wjddus2005@naver.com";
        $result = mail($email, $subject, $mailBody, $headers, $additionalHeaders);
        return $result;
    }

    /**
     * query 결과에 따라 로그인 실패 이유, 로그인 성공 및 세션 저장 수행
     * [테스트]
     */
    public static function postSignin()
    {
        $data = json_decode(strip_tags(file_get_contents("php://input")));

        $email = $data->email;
        $password = $data->password;

        $response = self::postSigninProcess($email, $password);
        return print_r(json_encode($response));
    }

    public static function postSigninProcess($email, $password)
    {
        if (!self::validateEmail($email) || !self::validatePassword($password)) {
            http_response_code(400);
            return;
        }
        $result = self::getModel()->postSignin($email, $password);
        if ($result['success'] == false) {
            http_response_code(401);
            return;
        } else if ($result['message'] === '인증 필요') {
            $_SESSION['auth_code'] = sprintf('%06d', rand(000000, 999999));
            $subject = 'camagru 로그인 인증 메일';
            $mailBody = $_SESSION['auth_code'] . ' 코드를 입력해 로그인을 완료해주세요.';
            self::sendMail($email, $subject, $mailBody);
            http_response_code(202);
            $body = array();
            $body['message'] = '메일을 확인해 인증코드를 입력해주세요.';
            return $body;
        }
        //postSignin에서 인증 활성화 여부 확인해서,
        //인증이 필요한 거면 다른 status코드와 바디를 보낼 것
        else {
            $_SESSION['username'] = $result['data'];
            $_SESSION['email'] = $email;
            header('Content-type: application/json; charset=utf-8');
            http_response_code(200);
            $body = array();
            $body['username'] = $result['data'];
            return $body;
        }
    }
    /**
     * 인증 코드를 확인해서 로그인 진행.
     */
    public static function postSigninAuth()
    {
        $data = json_decode(strip_tags(file_get_contents("php://input")));

        $email = $data->email;
        $password = $data->password;
        $authCode = $data->authCode;

        if (!self::validateEmail($email) || !self::validatePassword($password)) {
            http_response_code(400);
            return;
        }
        $result = self::getModel()->postSignin($email, $password);
        if ($result['success'] === false || $authCode !== $_SESSION['auth_code']) {
            http_response_code(401);
            $body = array();
            if ($result['success'] === true)
                $body['message'] = '올바른 인증 코드를 입력해주세요.';
            else
                $body['message'] = '비밀번호를 확인해주세요.';
            return print_r(json_encode($body));
        } else {
            unset($_SESSION['auth_code']);
            $_SESSION['username'] = $result['data'];
            $_SESSION['email'] = $email;
            header('Content-type: application/json; charset=utf-8');
            http_response_code(200);
            $body = array();
            $body['username'] = $result['data'];
            return print_r(json_encode($body));
        }
    }
    /**
     * 메인 화면에서 갤러리 이미지 객체와 페이지 데이터를 보내주는 역할
     * 현재 페이지, 한 번에 불러오는 이미지 개수를 가지고 조회를 한다
     * [테스트]
     */
    public static function postGallary()
    {
        $data = json_decode(file_get_contents("php://input"));

        $currentPage = $data->currentPage;
        $size = $data->size;

        $result = self::postGallaryProcess($currentPage, $size);
        return print_r($result);
    }

    public static function postGallaryProcess($currentPage, $size)
    {
        $result = self::getModel()->postGallary($currentPage, $size);
        if (!$result['data']['rownum']) {
            http_response_code(204);
            return;
        }

        // 마지막 페이지인지 판단
        if ($currentPage * $size >= $result['data']['rownum'])
            $result['data']['lastPage'] = true;
        else
            $result['data']['lastPage'] = false;
        http_response_code(200);
        return json_encode($result['data']);
    }
    /**
     * 사용자가 캡처한 이미지를 받아서 파일을 /img 경로에 쓰고 db에 저장함
     * 이미지 합성 과정이 추가될 예정
     * 일방적으로 저장하는 내용이 대부분이라 굳이 테스트는 만들지 않아도 될 듯
     */
    public static function postCapture()
    {
        $data = json_decode(file_get_contents("php://input"));

        $username = $_SESSION['username'];
        $baseImage = $data->baseImage;
        $stickyImages = $data->stickyImages;

        //파일 만들기
        $userId = self::getUserIdbyUsername($username);
        $newFileName = "img/" . $userId . "_" . date("Y-m-d_H:i:s") . ".png";

        foreach(new DirectoryIterator('img/') as $fileInfo)
        {
            $fileName = $fileInfo->getPathname();
            $newFileNameTrimed = substr($newFileName, 0, strlen($newFileName) - 4);
            $fileNameTrimed = substr($fileName, 0, strlen($newFileName) - 4);
            if ($newFileNameTrimed === $fileNameTrimed)
            {
                $bracket1 = strpos($fileName, '[');
                $bracket2 = strpos($fileName, ']');
                if ($bracket1 === false)
                    $newFileName = "img/" . $userId . "_" . date("Y-m-d_H:i:s") . '[0]' . ".png";
                else
                {
                    $number = substr($fileName, $bracket1 + 1, $bracket2 - $bracket1 - 1);
                    $newFileName = "img/" . $userId . "_" . date("Y-m-d_H:i:s") . '['. $number + 1 .']' . ".png";
                }
            }
        }
        $newImage = str_replace('data:image/png;base64,', '', $baseImage);
        $newImage = str_replace(' ', '+', $newImage);

        $newFile = new SplFileObject($newFileName, "w");
        $newFile->fwrite(base64_decode($newImage));
        $newFile = null; //close가 없어서 이렇게 닫아줘야 한다..

        //합성 과정
        if (isset($stickyImages)) {
            foreach ($stickyImages as $key) {
                $img = imagecreatefrompng($newFileName);
                $sticky = imagecreatefrompng($key);

                imagecopy($img, $sticky, 0, 0, 0, 0, 640, 480);
                imagepng($img, $newFileName);
                imagedestroy($img);
                imagedestroy($sticky);
            }
        }
        //db에 저장하고 올린 이미지 목록 받아옴
        $result = self::getModel()->postCapture($newFileName, $username);
        http_response_code(201);
        $body = array();
        $body['data'] = $result['data'];
        return print_r(json_encode($body));
    }

    /**
     * 마이페이지에서 내가 올린 이미지들을 가져올 때 사용
     */
    public static function getImagesByUsername($username)
    {
        return self::getModel()->getImagesByUsername($username)['data'];
    }

    /**
     * 선택한 이미지를 게시물로 업로드할 때 사용
     */
    public static function postImage()
    {
        $data = json_decode(file_get_contents("php://input"));

        $imageId = str_replace("captured-image-", "", $data->imageId);
        $result = self::getModel()->postImage($imageId);
        $body = array();

        if ($result['message'] === '중복') {
            http_response_code(409);
            $body['message'] = '이미 업로드한 이미지입니다.';
        } else {
            http_response_code(201);
            $body['postId'] = $result['data'];
        }
        return print_r(json_encode($body));
    }

    /**
     * 각각의 게시물 페이지를 조회할 때 사용
     */
    public static function getPostByPostId($postId)
    {
        $rst = self::getModel()->getPostByPostId($postId);
        if ($rst['success'] === false)
            return false;
        else
            return $rst['data'];
    }

    /**
     * 좋아요를 눌렀을 경우 사용됨
     * [테스트]
     */
    public static function postLikes()
    {
        $data = json_decode(file_get_contents("php://input"));
        $postId = $data->postId;
        
        if (!isset($_SESSION['username']))
            http_response_code(400);
        else
        {
            $username = $_SESSION['username'];
            self::postLikesProcess($postId, $username);
        }
        return;
    }
    public static function postLikesProcess($postId, $username)
    {
        $statusCode = 0;
        if (!$postId || !$username)
            $statusCode = 400;
        else if (self::getModel()->postLikes($postId, $username)['success'] === true)
            $statusCode = 201;
        else
            $statusCode = 409;
        http_response_code($statusCode);
        return $statusCode;
    }

    /**
     * 댓글 남길 때 요청
     */
    public static function postComment()
    {
        $data = json_decode(file_get_contents("php://input"));

        $comment = htmlspecialchars($data->comment);
        $postId = $data->postId;
        $username = $_SESSION['username'];

        self::getModel()->postComment($comment, $postId, $username);
        http_response_code(201);
        self::sendNotice($username, $comment, $postId);
        return;
    }
    /**
     * 댓글 달렸을 경우 상대방에게 알림 메일 보내는 기능
     */
    private static function sendNotice($commentUsername, $comment, $sendUserPostId)
    {
        $sendUserPost = self::getPostByPostId($sendUserPostId);
        $sendUsername = self::getUsernameByUserId($sendUserPost['userId']);
        $sendUser = self::getUserbyUsername($sendUsername);
        if ($sendUser['notice'] === 'always') {
            $email = $sendUser['email'];
            $subject = 'camagru 댓글 알림 메일';
            $mailBody = '당신의 ' . $sendUserPostId . '번 게시물에 다음과 같은 댓글이 달렸습니다' . "\r\n";
            $mailBody .= $commentUsername . " : " . $comment;
            self::sendMail($email, $subject, $mailBody);
        }
    }
    private static function getUsernameByUserId($userId)
    {
        return self::getModel()->getUsernameByUserId($userId)['data'];
    }

    /**
     * 게시물에 달린 댓글을 조회할 때 사용
     */
    public static function getCommentbyPostId($postId)
    {
        return self::getModel()->getCommentByPostId($postId)['data'];
    }

    /**
     * 마이페이지에서 내가 올린 게시물 확인할 때 사용
     */
    public static function getPostsByUsername($username)
    {
        return self::getModel()->getPostsByUsername($username)['data'];
    }

    /**
     * 마이페이지에서 닉네임 수정할 때 사용
     * [테스트]
     */
    public static function patchUsername()
    {
        $data = json_decode(strip_tags(file_get_contents("php://input")));
        $change = $data->change;
        $username = $_SESSION['username'];

        $body = self::patchUsernameProcess($change, $username);
        return print_r(json_encode($body));
    }
    public static function patchUsernameProcess($change, $username)
    {
        $body = array();
        if (!self::validateUsername($change)) {
            http_response_code(400);
            $body['message'] = '닉네임 규칙을 확인해주세요';
            return $body;
        }

        $result = self::getModel()->patchUsername($username, $change);
        if ($result['success'] === false)
            http_response_code(409);
        else {
            http_response_code(200);
            $_SESSION['username'] = $change;
        }
        return;
    }

    /**
     * 마이페이지에서 이메일 수정할 때 사용
     * [테스트]
     */
    public static function patchEmail()
    {
        $data = json_decode(strip_tags(file_get_contents("php://input")));
        $change = $data->email;
        $email = $_SESSION['email'];

        $body = self::patchEmailProcess($change, $email);
        return print_r(json_encode($body));
    }
    public static function patchEmailProcess($change, $email)
    {
        $body = array();
        if (!self::validateEmail($change)) {
            http_response_code(400);
            $body['message'] = '이메일 규칙을 확인해주세요';
            return $body;
        }
        $result = self::getModel()->patchEmail($email, $change);
        if ($result['success'] === false)
            http_response_code(409);
        else {
            http_response_code(200);
            $_SESSION['email'] = $change;
        }
        return;
    }

    /**
     * 마이페이지에서 비밀번호 수정할 때 사용
     * [테스트]
     */
    public static function patchPassword()
    {
        $data = json_decode(strip_tags(file_get_contents("php://input")));
        $originPassword = $data->originPassword;
        $newPassword = $data->newPassword;
        $checkPassword = $data->checkPassword;
        
        $body = self::patchPasswordProcess($originPassword, $newPassword, $checkPassword);
        return print_r(json_encode($body));
    }

    public static function patchPasswordProcess($originPassword, $newPassword, $checkPassword)
    {
        $username = $_SESSION['username'];
        $body = array();
        if ($originPassword === $newPassword) {
            http_response_code(400);
            $body['message'] = '현재 비밀번호와 같습니다.';
            return $body;
        } else if ($newPassword !== $checkPassword) {
            http_response_code(400);
            $body['message'] = '변경할 비밀번호와 비밀번호 확인이 일치하지 않습니다.';
            return $body;
        }

        if (!self::validatePassword($newPassword)) {
            http_response_code(400);
            $body['message'] = '비밀번호 규칙을 확인해주세요';
            return $body;
        }

        $result = self::getModel()->patchPassword($originPassword, $newPassword, $username);
        if ($result['success'])
            http_response_code(200);
        else {
            http_response_code(400);
            $body['message'] = '기존 비밀번호가 틀렸습니다.';
        }
        return $body;
    }

    /**
     * 로그아웃 시 세션 파괴
     * 추후 기능 구현에 따라 무언가 더 추가될 수도 있음
     */
    public static function postLogout()
    {
        session_destroy();
        return print_r('성공');
    }

    /**
     * 댓글 삭제 요청
     */
    public static function deleteComment()
    {
        $data = json_decode(file_get_contents("php://input"));

        $commentId = $data->commentId;

        $result = self::getModel()->deleteComment($commentId);
        http_response_code(200);
        return;
    }

    /**
     * 댓글 수정 요청
     */
    public static function patchComment()
    {
        $data = json_decode(file_get_contents("php://input"));

        $commentId = $data->commentId;
        $newComment = htmlspecialchars($data->newComment);
        
        $result = self::getModel()->patchComment($commentId, $newComment);
        http_response_code(200);
        return;
    }

    /**
     * 닉네임으로 userId찾기 주로 클라이언트에서 username으로 날아오는 데이터를 조회하기 편하게 하기 위해 사용
     */
    public static function getUserIdbyUsername($username)
    {
        return self::getModel()->getUserIdbyUsername($username)['data'];
    }

    /**
     * 닉네임으로 유저 정보 가져오기.
     */
    public static function getUserbyUsername($username)
    {
        return self::getModel()->getUserbyUsername($username)['data'];
    }
    /**
     * 게시물 삭제 요청
     */
    public static function deletePost()
    {
        $data = json_decode(file_get_contents("php://input"));

        $postId = $data->postId;

        $result = self::getModel()->deletePost($postId);
        http_response_code(200);
        return;
    }

    /**
     * 이미지 삭제 요청. 이미지 파일 삭제도 이루어진다
     */
    public static function deleteImage()
    {
        $data = json_decode(file_get_contents("php://input"));

        $imageId = $data->imageId;
        $image = self::getModel()->getImageByImageId($imageId)['data'];
        if (file_exists($image))
            unlink($image);
        $result = self::getModel()->deleteImage($imageId);
        http_response_code(200);
        return;
    }

    /**
     * 마이페이지에서 좋아요 한 게시물을 확인할 때 사용
     */
    public static function getLikesPostsByUsername($username)
    {
        $result = self::getModel()->getLikesPostsByUsername($username);
        return $result['data'];
    }

    public static function patchUser()
    {
        $data = json_decode(file_get_contents("php://input"));
        $username = $_SESSION['username'];

        if (isset($data->auth))
            self::getModel()->patchUserAuth($username, $data->auth);
        else if (isset($data->notice))
            self::getModel()->patchUserNotice($username, $data->notice);
        http_response_code(200);
        return;
    }
    public static function postPasswordRecovery()
    {
        $data = json_decode(file_get_contents("php://input"));
        $email = $data->email;
        $tmpPassword = self::makeTmpPassword();
        $mailBody = '임시 비밀번호 ' . $tmpPassword . ' 를 입력해 로그인하세요' . "\r\n";
        $mailBody .= '로그인 후 비밀번호를 반드시 변경해주세요.';

        self::postLogout();
        self::getModel()->postPasswordRecovery($email, $tmpPassword);
        self::sendMail($email, 'camagru 임시 비밀번호 발급', $mailBody);

        http_response_code(200);
        return;
    }
    private static function makeTmpPassword()
    {
        $str = '';
        $alphaNum = 'abcdefghijklnmopqrstuvwxyz0123456789';

        for ($i = 0; $i < 8; $i++) {
            $str .= $alphaNum[rand(0, 35)];
        }
        if (self::validatePassword($str) === false)
            $str = self::makeTmpPassword();
        return $str;
    }
}

?>