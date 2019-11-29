<?php
    require_once ('config.php');

    class Database{
        public function connect(){
            $this->mysqli = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
            $this->mysqli->query("SET NAMES 'utf8'");

            if ($this->mysqli->connect_errno) {
                echo "Не удалось подключиться к MySQL";
            }
        }
    }