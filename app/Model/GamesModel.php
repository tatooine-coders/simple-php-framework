<?php
class GamesModel {
    protected $_id;
    protected $_turn;
    protected $_active_character;

    public static function getList(PDO $db) {
        $query = "SELECT * FROM games";
        $statement = $db->prepare($query);
        $statement->execute();
        $statement->setFetchMode(PDO::FETCH_OBJ);
        $games = array();
        $row = $statement->fetch();
        do {
            $object = new GamesModel();
            $object->setId($row->id);
            $object->setTurn($row->turn);
            $object->setActive_character($row->active_character);
            $games[] = $object;
        } while (!empty($row = $statement->fetch()));
        return ($games);
    }

    public static function getObject(PDO $db, $id) {
        $query = "SELECT * FROM games WHERE id = :id";
        $statement = $db->prepare($query);
        $statement->bindValue('id', $id, PDO::PARAM_INT);
        $statement->execute();
        $statement->setFetchMode(PDO::FETCH_OBJ);
        $object = null;
        if (!empty($row = $statement->fetch())) {
            $object = new GamesModel();
            $object->setId($row->id);
            $object->setTurn($row->turn);
            $object->setActive_character($row->active_character);
        }
        return ($object);
    }


    function __construct($id, $turn, $active_character) {
        $this->setId($id);
        $this->setTurn($turn);
        $this->setActive_character($active_character);
    }

    public function exists(PDO $db) {
        $query = "SELECT * FROM games WHERE id = :id LIMIT 1";
        $statement = $db->prepare($query);
        $statement->bindValue('id', $this->getId(), PDO::PARAM_INT);
        $statement->execute();
        $statement->setFetchMode(PDO::FETCH_OBJ);
        return ($statement);
    }

    public function load(PDO $db) {
        $statement = $this->exists($db);
        if (!empty($row = $statement->fetch())) {
            $this->setId($row->id);
            $this->setTurn($row->turn);
            $this->setActive_character($row->active_character);
        }
    }

    public function add(PDO $db) {
        $statement = $this->exists($db);
        if (empty($row = $statement->fetch())) {
            $query = "INSERT INTO games (id, turn, active_character) VALUES (:id, :turn, :active_character)";
            $statement=$db->prepare($query);
            $statement->bindValue('id', $this->getId(), PDO::PARAM_STR);
            $statement->bindValue('turn', $this->getTurn(), PDO::PARAM_STR);
            $statement->bindValue('active_character', $this->getActive_character(), PDO::PARAM_STR);
        }
    }

    public function update(PDO $db) {
        $statement = $this->exists($db);
        if (empty($row = $statement->fetch())) {
            $query = "UPDATE games SET id = :id, turn = :turn, active_character = :active_character
            WHERE id = :id";
            $statement=$db->prepare($query);
            $statement->bindValue('id', $this->getId(), PDO::PARAM_STR);
            $statement->bindValue('turn', $this->getTurn(), PDO::PARAM_STR);
            $statement->bindValue('active_character', $this->getActive_character(), PDO::PARAM_STR);
            $statement->execute();
        }
    }

    public function delete(PDO $db) {
        $statement = $this->exists($db);
        if (!empty($row = $statement->fetch())) {
            $query = "DELETE FROM games WHERE id = :id";
            $statement=$db->prepare($query);
            $statement->bindValue('id', $this->getId(), PDO::PARAM_STR);
            $statement->execute();
        }
    }

    public function getId() {
        return ($this->_id);
    }
    public function setId($id) {
        $this->_id = $id;
    }
    public function getTurn() {
        return ($this->_turn);
    }
    public function setTurn($turn) {
        $this->_turn = $turn;
    }
    public function getActive_character() {
        return ($this->_active_character);
    }
    public function setActive_character($active_character) {
        $this->_active_character = $active_character;
    }
}
?>