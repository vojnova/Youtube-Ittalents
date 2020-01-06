<?php


namespace model;
use PDO;
use PDOException;

class PlaylistDAO extends BaseDao {
    private static $instance;

    private function __construct()
    {
    }

    public static function getInstance(){
        if (self::$instance == null){
            self::$instance = new PlaylistDAO();
        }
        return self::$instance;
    }

    public function getAllByUserId($userid) {
        $pdo = $this->getPDO();
        $sql = "SELECT id, playlist_title, owner_id, date_created FROM playlists WHERE owner_id = ?;";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array($userid));
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $rows;
    }

    public function getAll() {
        $pdo = $this->getPDO();
        $sql = "SELECT id, playlist_title, owner_id, date_created FROM playlists WHERE owner_id = ?;";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $rows;
    }

    public function create(Playlist $playlist){
        $title = $playlist->getTitle();
        $owner_id = $playlist->getOwnerId();
        $date_created = $playlist->getDateCreated();
        $pdo = $this->getPDO();
        $sql = "INSERT INTO playlists (playlist_title, owner_id, date_created) VALUES (?, ?, ?);";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array($title, $owner_id, $date_created));
        $playlist_id = $pdo->lastInsertId();
        $playlist->setId($playlist_id);
    }

    public function delete(Playlist $playlist){
        $playlist_id = $playlist->getId();
        $owner_id = $playlist->getOwnerId();
        $pdo = $this->getPDO();
        $sql = "DELETE FROM playlists WHERE id = ? AND owner_id = ?;";
        $params = [];
        $params[] = $playlist_id;
        $params[] = $owner_id;
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
    }

    public function getVideosFromPlaylist($playlist_id){
        $pdo = $this->getPDO();
        $sql = "SELECT v.id, v.title, v.date_uploaded, p.playlist_title, u.username, v.thumbnail_url FROM videos AS v 
                JOIN users AS u ON v.owner_id = u.id
                JOIN added_to_playlist AS atp ON v.id = atp.video_id
                JOIN playlists AS p ON p.id = atp.playlist_id
                WHERE atp.playlist_id = ?;";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array($playlist_id));
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $rows;
    }
}