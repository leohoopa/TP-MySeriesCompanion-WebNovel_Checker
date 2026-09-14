<?php
require_once __DIR__ . '/connexion_bdd.php';

function ajouterWebnovel(PDO $pdo, array $webnovel): array
{
    $nom = trim($webnovel['Wnom'] ?? '');
    $resume = trim($webnovel['Wresume'] ?? '');
    $vignette = trim($webnovel['Wvignette'] ?? '');
    $date = trim($webnovel['Wdate'] ?? '');

    if ($nom === '' || $date === '') {
        return [
            'ok' => false,
            'code' => 400,
            'message' => 'Le nom et la date sont obligatoires.',
        ];
    }

    try {
        $sql = "INSERT INTO webnovel (nom, resume, vignette, date_sortie)
                VALUES (:nom, :resume, :vignette, :date_sortie)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':nom' => $nom,
            ':resume' => $resume,
            ':vignette' => $vignette,
            ':date_sortie' => $date,
        ]);

        $id = (int) $pdo->lastInsertId();

        return [
            'ok' => true,
            'code' => 302,
            'message' => '',
            'id' => $id,
        ];
    } catch (PDOException $e) {
        return [
            'ok' => false,
            'code' => 500,
            'message' => 'Erreur lors de l\'insertion : ' . $e->getMessage(),
        ];
    }
}

function listWebnovel(PDO $pdo)
{
    $sql = "SELECT id, nom, resume, vignette, date_sortie FROM webnovel";
    $stmt = $pdo->query($sql);
    $resultats = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $resultats;
}

function ficheWebnovel(PDO $pdo, int $id): ?array
{
    $sql = "SELECT id, nom, resume, vignette, date_sortie FROM webnovel WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id]);

    $webnovel = $stmt->fetch(PDO::FETCH_ASSOC);

    return $webnovel ?: null;
}

function ajout_saison(PDO $pdo, int $webnovelId, array $tome): array
{
    $nom = trim($tome['Tnom'] ?? '');
    $resume = trim($tome['Tresume'] ?? '');
    $vignette = trim($tome['Tvignette'] ?? '');
    $date = trim($tome['Tdate'] ?? '');

    if ($nom === '' || $date === '') {
        return [
            'ok' => false,
            'code' => 400,
            'message' => 'Le nom et la date sont obligatoires.',
        ];
    }

    try {
        $sql = "INSERT INTO tome (nom, resume, vignette, date_sortie, webnovel_id)
                VALUES (:nom, :resume, :vignette, :date_sortie, :webnovel_id)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':nom' => $nom,
            ':resume' => $resume,
            ':vignette' => $vignette,
            ':date_sortie' => $date,
            ':webnovel_id' => $webnovelId,
        ]);

        return [
            'ok' => true,
            'code' => 200,
            'message' => 'Enregistrement réussi !',
        ];
    } catch (PDOException $e) {
        return [
            'ok' => false,
            'code' => 500,
            'message' => 'Erreur lors de l\'insertion : ' . $e->getMessage(),
        ];
    }
}

function affichez_tome(PDO $pdo, int $webnovelId): array
{
    $sql = "SELECT id, nom, resume, vignette, date_sortie
            FROM tome
            WHERE webnovel_id = :webnovel_id
            ORDER BY date_sortie ASC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([':webnovel_id' => $webnovelId]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function ficheTome(PDO $pdo, int $id): ?array
{
    $sql = "SELECT t.id, t.nom, t.resume, t.vignette, t.date_sortie, t.webnovel_id,
                   w.nom AS webnovel_nom, w.resume AS webnovel_resume,
                   w.vignette AS webnovel_vignette, w.date_sortie AS webnovel_date_sortie
            FROM tome t
            JOIN webnovel w ON w.id = t.webnovel_id
            WHERE t.id = :id";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id]);
    $tome = $stmt->fetch(PDO::FETCH_ASSOC);
    return $tome ?: null;
}

function ajout_chapitre(PDO $pdo, int $tomeId, array $chapitre): array
{
    $nom = trim($chapitre['Cnom'] ?? '');
    $resume = trim($chapitre['Cresume'] ?? '');
    $vignette = trim($chapitre['Cvignette'] ?? '');
    $date = trim($chapitre['Cdate'] ?? '');
    $nbrMots = isset($chapitre['CnbrMots']) ? (int) $chapitre['CnbrMots'] : 0;

    if ($nom === '' || $date === '') {
        return [
            'ok' => false,
            'code' => 400,
            'message' => 'Le nom et la date sont obligatoires.',
        ];
    }
    try {
        $sql = "INSERT INTO chapitre (nom, resume, vignette, date_sortie, nbr_mot, tome_id)
                VALUES (:nom, :resume, :vignette, :date_sortie, :nbr_mot, :tome_id)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':nom' => $nom,
            ':resume' => $resume,
            ':vignette' => $vignette,
            ':date_sortie' => $date,
            ':nbr_mot' => $nbrMots,
            ':tome_id' => $tomeId,
        ]);
        return [
            'ok' => true,
            'code' => 200,
            'message' => 'Enregistrement réussi !',
        ];
    } catch (PDOException $e) {
        return [
            'ok' => false,
            'code' => 500,
            'message' => 'Erreur lors de l\'insertion : ' . $e->getMessage(),
        ];
    }
}

function affichez_chapitre(PDO $pdo, int $tomeId): array
{
    $sql = "SELECT id, nom, resume, vignette, date_sortie, nbr_mot
            FROM chapitre
            WHERE tome_id = :tome_id
            ORDER BY date_sortie ASC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([':tome_id' => $tomeId]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>