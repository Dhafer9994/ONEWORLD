<?php
include __DIR__ . '/../config.php';
include __DIR__ . '/../model/historique.php';

class HistoriqueC {

    public function addHistorique(Historique $h) {
        $db = config::getConnexion();
        try {
            $req = $db->prepare('
                INSERT INTO historique (action, date, id_user) 
                VALUES (:action, :date, :id_user)
            ');
            $req->execute([
                'action' => $h->getAction(),
                'date' => $h->getDateAction(),
                'id_user' => $h->getIdUser()
            ]);
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }

   
    public function ListeHistorique() {
        $db = config::getConnexion();
        try {
            $req = $db->query('
                SELECT h.*, u.nom, u.prenom, u.role, u.email
                FROM historique h
                JOIN user u ON h.id_user = u.id
                ORDER BY h.date ASC
            ');
            return $req->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }

   
    public function delete($id) {
        $db = config::getConnexion();
        try {
            $req = $db->prepare('DELETE FROM historique WHERE id = :id');
            $req->bindValue(':id', $id, PDO::PARAM_INT);
            return $req->execute();
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }

  

}

  