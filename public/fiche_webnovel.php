<?php
require_once __DIR__ . '/../back/connexion_bdd.php';
require_once __DIR__ . '/../back/function.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$webnovel = null;

if ($id !== false && $id !== null) {
    $webnovel = ficheWebnovel($pdo, $id);
    $tomes = affichez_tome($pdo, $id);
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $webnovelId = (int)($_POST['webnovel_id'] ?? 0);
    $resultat = ajout_saison($pdo, $webnovelId, $_POST);

    if ($resultat['ok']) {
        $webnovel = ficheWebnovel($pdo, $webnovelId);
        $tomes = affichez_tome($pdo, $webnovelId);
    } else {
        http_response_code($resultat['code']);
        $messageErreur = $resultat['message'];
    }
}
?>
<!DOCTYPE html>
<html lang="fr" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="asset/output.css" rel="stylesheet">
    <title>Fiche webnovel</title>
</head>
<body class="min-h-screen bg-base-200 text-base-content">
    <?php include __DIR__ . '/header.php'; ?>

    <main class="mx-auto max-w-5xl px-4 py-10">
        <?php if (!empty($messageErreur)): ?>
            <div class="alert alert-error mb-4">
                <span><?= htmlspecialchars($messageErreur) ?></span>
            </div>
        <?php endif; ?>

        <?php if (!$webnovel): ?>
            <div class="alert alert-error">
                <span>Webnovel introuvable.</span>
            </div>
        <?php else: ?>
            <div class="card overflow-hidden bg-base-100 shadow-xl">
                <figure class="p-4">
                    <img
                        src="<?= htmlspecialchars($webnovel['vignette']) ?>"
                        alt="<?= htmlspecialchars($webnovel['nom']) ?>"
                        class="h-80 w-full rounded-box object-cover shadow-md"
                    >
                </figure>

                <div class="card-body">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <p class="text-xs uppercase tracking-[0.28em] text-base-content/60">Webnovel</p>
                            <h1 class="mt-2 text-3xl font-bold"><?= htmlspecialchars($webnovel['nom']) ?></h1>
                        </div>
                        <a href="list_webnovel.php" class="btn btn-primary">Retour à la liste</a>
                    </div>

                    <p class="text-sm text-base-content/70">Date de sortie : <?= htmlspecialchars($webnovel['date_sortie']) ?></p>
                    <p class="mt-4 whitespace-pre-line text-base-content/80"><?= htmlspecialchars($webnovel['resume']) ?></p>
                </div>

                <div class="border-t border-base-300 p-4">
                    <h2 class="mb-4 text-xl font-bold">Tomes</h2>

                    <?php if (empty($tomes)): ?>
                        <p class="text-sm text-base-content/70">Aucun tome pour cette webnovel.</p>
                    <?php else: ?>
                        <div class="space-y-3">
                            <?php foreach ($tomes as $tome): ?>
                                <article class="rounded-box border border-base-300 bg-base-100 p-3 shadow-sm">
                                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                        <div class="flex items-center gap-3">
                                            <?php if (!empty($tome['vignette'])): ?>
                                                <img src="<?= htmlspecialchars($tome['vignette']) ?>" alt="<?= htmlspecialchars($tome['nom']) ?>" class="size-16 rounded-box object-cover">
                                            <?php endif; ?>
                                            <div>
                                                <h3 class="font-semibold"><?= htmlspecialchars($tome['nom']) ?></h3>
                                                <p class="text-xs text-base-content/60"><?= htmlspecialchars($tome['date_sortie']) ?></p>
                                                <p class="mt-1 text-sm text-base-content/70"><?= htmlspecialchars($tome['resume']) ?></p>
                                            </div>
                                        </div>

                                        <a href="fiche_tome.php?id=<?= (int) $tome['id'] ?>" class="btn btn-primary btn-sm">
                                            Voir
                                        </a>
                                    </div>
                                </article>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <form class="mx-auto grid w-full max-w-2xl gap-4 border-t border-base-300 p-4" method="POST" action="fiche_webnovel.php?id=<?= (int) $webnovel['id'] ?>">
                    <input type="hidden" name="webnovel_id" value="<?= (int) $webnovel['id'] ?>">

                    <div class="grid gap-4">
                        <label class="form-control w-full">
                            <span class="label-text mb-2">Nom</span>
                            <input class="input input-bordered input-primary w-full" type="text" id="Tnom" name="Tnom" placeholder="Nom du tome">
                        </label>

                        <label class="form-control w-full">
                            <span class="label-text mb-2">Résumé</span>
                            <textarea class="textarea textarea-bordered textarea-primary h-28 w-full" id="Tresume" name="Tresume" placeholder="Résumé du tome..."></textarea>
                        </label>

                        <div class="grid gap-4 md:grid-cols-2">
                            <label class="form-control w-full">
                                <span class="label-text mb-2">Vignette</span>
                                <input class="input input-bordered input-primary w-full" type="text" id="Tvignette" name="Tvignette" placeholder="https://...">
                            </label>

                            <label class="form-control w-full">
                                <span class="label-text mb-2">Date de sortie</span>
                                <input class="input input-bordered input-primary w-full" type="date" id="Tdate" name="Tdate">
                            </label>
                        </div>
                    </div>

                    <div class="flex justify-center sm:justify-end">
                        <button class="btn btn-primary px-8" type="submit">Enregistrer</button>
                    </div>
                </form>
            </div>
        <?php endif; ?>
    </main>
</body>
</html>