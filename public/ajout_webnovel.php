<?php
require_once __DIR__ . '/../back/connexion_bdd.php';
require_once __DIR__ . '/../back/function.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $resultat = ajouterWebnovel($pdo, $_POST);

    if ($resultat['ok'] && isset($resultat['id'])) {
        header('Location: fiche_webnovel.php?id=' . (int) $resultat['id']);
        exit;
    }

    http_response_code($resultat['code']);
    echo $resultat['message'];
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="asset/output.css" rel="stylesheet">
    <title>Ajouter une webnovel</title>
</head>
<body class="min-h-screen bg-base-200 text-base-content">
    <?php include __DIR__ . '/header.php'; ?>

    <main class="mx-auto max-w-3xl px-4 py-10">
        <div class="card bg-base-100 shadow-xl">
            <div class="card-body">
                <div class="mb-2">
                    <p class="text-xs uppercase tracking-[0.28em] text-base-content/60">Nouvelle série</p>
                    <h1 class="mt-2 text-3xl font-bold">Ajouter une webnovel</h1>
                </div>

                <form class="mx-auto mt-4 grid w-full max-w-xl gap-4" method="POST" action="ajout_webnovel.php">
                    <label class="form-control w-full">
                        <span class="label-text mb-2">Nom</span>
                        <input class="input input-bordered input-primary w-full" type="text" id="Wnom" name="Wnom" placeholder="Ex. Solo Leveling" required>
                    </label>

                    <label class="form-control w-full">
                        <span class="label-text mb-2">Résumé</span>
                        <textarea class="textarea textarea-bordered textarea-primary h-28 w-full" id="Wresume" name="Wresume" placeholder="Résumé de la série..."></textarea>
                    </label>

                    <label class="form-control w-full">
                        <span class="label-text mb-2">Vignette</span>
                        <input class="input input-bordered input-primary w-full" type="text" id="Wvignette" name="Wvignette" placeholder="https://...">
                    </label>

                    <label class="form-control w-full">
                        <span class="label-text mb-2">Date de sortie</span>
                        <input class="input input-bordered input-primary w-full" type="date" id="Wdate" name="Wdate">
                    </label>

                    <div class="mt-2 flex justify-center sm:justify-end">
                        <button class="btn btn-primary px-8" type="submit">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</body>
</html>