<?php
namespace model;
use PDO;
use PDOException;
class UserDAO extends BaseDao {
    private static $instance;

    private function __construct()
    {
    }

    public static function getInstance(){
        if (self::$instance == null){
            self::$instance = new UserDAO();
        }
        return self::$instance;
    }

    public function getAll() {
        $pdo = $this->getPDO();
        $sql = "SELECT id, username, email, name, registration_date FROM users";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $rows;
    }
    public function checkUser($email)
    {
        $pdo = $this->getPDO();
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array($email));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (empty($row)) {
            return false;
        }
        else {
            return $row;
        }
    }

    public function registerUser(User $user)
    {
        $username = $user->getUsername();
        $email = $user->getEmail();
        $password = $user->getPassword();
        $full_name = $user->getFullName();
        $date = $user->getRegistrationDate();
        $avatar_url = $user->getAvatarUrl();
        $pdo = $this->getPDO();
        $sql = "INSERT INTO users (username,  email, password, name, registration_date, avatar_url)
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array($username, $email, $password, $full_name, $date, $avatar_url));
        $user->setId($pdo->lastInsertId());
        if ($pdo->lastInsertId() > 0) {
            return true;
        }
        else {
            return false;
        }
    }

    public function getById($id){
        $pdo = $this->getPDO();
        $sql = "SELECT username, name, registration_date, avatar_url FROM users WHERE id = ?;";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array($id));
        $rows = $stmt->fetch(PDO::FETCH_ASSOC);
        return $rows;
    }

    public function editUser(User $user)
    {
        $username = $user->getUsername();
        $email  = $user->getEmail();
        $password   = $user->getPassword();
        $full_name = $user->getFullName();
        $avatar_url = $user->getAvatarUrl();
        $id = $user->getId();
        $pdo = $this->getPDO();
        $sql = "UPDATE users SET username = ? , email = ?, password = ?, name = ?, avatar_url = ? WHERE id=?;";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array($username, $email, $password, $full_name, $avatar_url, $id));
    }

    public function followUser($follower_id, $followed_id){
        $pdo = $this->getPDO();
        $sql = "INSERT INTO users_follow_users (follower_id, followed_id)
                VALUES (?, ?);";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array($follower_id, $followed_id));
    }

    public function unfollowUser($follower_id, $followed_id){
        $pdo = $this->getPDO();
        $sql = "DELETE FROM users_follow_users WHERE follower_id = ? AND followed_id = ?;";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array($follower_id, $followed_id));
    }

    public function isFollowing($follower_id, $followed_id){
        $pdo = $this->getPDO();
        $sql = "SELECT followed_id FROM users_follow_users WHERE follower_id = ? AND followed_id = ?;";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array($follower_id, $followed_id));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row){
            return true;
        }
        else {
            return false;
        }
    }

    public function isReacting($user_id, $video_id){
        $pdo = $this->getPDO();
        $sql = "SELECT status FROM users_react_videos WHERE user_id = ? AND video_id = ?;";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array($user_id, $video_id));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row){
            return $row["status"];
        }
        else {
            return -1;
        }
    }

    public function reactVideo($user_id, $video_id, $status){
        $pdo = $this->getPDO();
        $sql = "INSERT INTO users_react_videos (user_id, video_id, status)
                VALUES (?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array($user_id, $video_id, $status));
    }

    public function unreactVideo($user_id, $video_id){
        $pdo = $this->getPDO();
        $sql = "DELETE FROM users_react_videos WHERE user_id = ? AND video_id = ?;";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array($user_id, $video_id));
    }

    public function isReactingComment($user_id, $comment_id){
        $pdo = $this->getPDO();
        $sql = "SELECT status FROM users_react_comments WHERE user_id = ? AND comment_id = ?;";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array($user_id, $comment_id));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row){
            return $row["status"];
        }
        else {
            return -1;
        }
    }

    public function reactComment($user_id, $comment_id, $status){
        $pdo = $this->getPDO();
        $sql = "INSERT INTO users_react_comments (user_id, comment_id, status)
                VALUES (?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array($user_id, $comment_id, $status));
    }

    public function unreactComment($user_id, $comment_id){
        $pdo = $this->getPDO();
        $sql = "DELETE FROM users_react_comments WHERE user_id = ? AND comment_id = ?;";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array($user_id, $comment_id));
    }
}