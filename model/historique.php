<?php
class Historique {
    private int $id;
    private string $action;
    private string $date;
    private int $id_user;

    public function __construct(string $action, string $date, int $id_user){
        $this->action = $action;
        $this->date = $date;
        $this->id_user = $id_user;
    }

    public function getIdHistorique(): int { return $this->id; }
    public function setIdHistorique(int $id){ $this->id = $id; }

    public function getAction(): string { return $this->action; }
    public function setAction(string $action){ $this->action = $action; }

    public function getDateAction(): string { return $this->date; }
    public function setDateAction(string $date){ $this->date = $date; }

    public function getIdUser(): int { return $this->id_user; }
    public function setIdUser(int $id_user){ $this->id_user = $id_user; }
}