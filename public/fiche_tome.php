<?php
require_once __DIR__ . '/../back/connexion_bdd.php';
require_once __DIR__ . '/../back/function.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$tome = null;
$chapitres = [];
$messageErreur = null;

if ($id !== false && $id !== null) {
    $tome = ficheTome($pdo, $id);
    if ($tome) {
        $chapitres = affichez_chapitre($pdo, $tome['id']);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tome_id'])) {
    $tomeId = (int) ($_POST['tome_id'] ?? 0);
    $resultat = ajout_chapitre($pdo, $tomeId, $_POST);

    if ($resultat['ok']) {
        $tome = ficheTome($pdo, $tomeId);
        $chapitres = affichez_chapitre($pdo, $tomeId);
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
    <title>Fiche Tome</title>
</head>
<body class="min-h-screen bg-base-200 text-base-content">
    <?php include __DIR__ . '/header.php'; ?>

    <main class="mx-auto max-w-5xl px-4 py-10">
        <?php if (!empty($messageErreur)): ?>
            <div class="alert alert-error mb-4">
                <span><?= htmlspecialchars($messageErreur) ?></span>
            </div>
        <?php endif; ?>

        <?php if (!$tome): ?>
            <div class="alert alert-error">
                <span>Tome introuvable.</span>
            </div>
        <?php else: ?>
            <div class="card overflow-hidden bg-base-100 shadow-xl">
                <figure class="p-4">
                    <?php if (!empty($tome['vignette'])): ?>
                        <img
                            src="<?= htmlspecialchars($tome['vignette']) ?>"
                            alt="<?= htmlspecialchars($tome['nom']) ?>"
                            class="h-80 w-full rounded-box object-cover shadow-md"
                        >
                    <?php endif; ?>
                </figure>

                <div class="card-body">
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <div>
                            <p class="text-xs uppercase tracking-[0.28em] text-base-content/60">
                                <?= htmlspecialchars($tome['webnovel_nom'] ?? 'Webnovel') ?>
                            </p>
                            <h1 class="mt-2 text-3xl font-bold md:text-4xl">
                                <?= htmlspecialchars($tome['nom']) ?>
                            </h1>
                        </div>
                        <a href="fiche_webnovel.php?id=<?= (int) ($tome['webnovel_id'] ?? 0) ?>" class="btn btn-primary">
                            Retour à la webnovel
                        </a>
                    </div>

                    <p class="text-sm text-base-content/70">Date de sortie : <?= htmlspecialchars($tome['date_sortie']) ?></p>
                    <p class="mt-4 whitespace-pre-line text-justify text-base-content/80">
                        <?= htmlspecialchars($tome['resume']) ?>
                    </p>
                </div>

                <div class="border-t border-base-300 p-4">
                    <h2 class="mb-4 text-xl font-bold">Chapitres</h2>
                    <?php if (empty($chapitres)): ?>
                        <p class="text-sm text-base-content/70">Aucun chapitre pour ce tome.</p>
                    <?php else: ?>
                        <div class="space-y-3">
                            <?php foreach ($chapitres as $chapitre): ?>
                                <article class="rounded-box border border-base-300 bg-base-100 p-3 shadow-sm">
                                    <div class="flex items-center justify-between gap-3">
                                        <div class="flex items-center gap-3">
                                            <?php if (!empty($chapitre['vignette'])): ?>
                                                <img
                                                    src="<?= htmlspecialchars($chapitre['vignette']) ?>"
                                                    alt="<?= htmlspecialchars($chapitre['nom']) ?>"
                                                    class="size-14 rounded-box object-cover"
                                                >
                                            <?php endif; ?>
                                            <div>
                                                <h3 class="font-semibold"><?= htmlspecialchars($chapitre['nom']) ?></h3>
                                                <p class="text-xs text-base-content/60"><?= htmlspecialchars($chapitre['date_sortie']) ?></p>
                                                <p class="mt-1 text-xs text-base-content/70">Nbr mots : <?= htmlspecialchars((string) ($chapitre['nbr_mot'] ?? 0)) ?></p>
                                            </div>
                                        </div>
                                    </div>
                                </article>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <form class="mx-auto grid w-full max-w-2xl gap-4 border-t border-base-300 p-4" method="POST" action="fiche_tome.php?id=<?= (int) $tome['id'] ?>">
                    <input type="hidden" name="tome_id" value="<?= (int) $tome['id'] ?>">

                    <div class="grid gap-4">
                        <label class="form-control w-full">
                            <span class="label-text mb-2">Nom</span>
                            <input class="input input-bordered input-primary w-full" type="text" id="Cnom" name="Cnom" placeholder="Nom du chapitre">
                        </label>

                        <label class="form-control w-full">
                            <span class="label-text mb-2">Résumé</span>
                            <textarea class="textarea textarea-bordered textarea-primary h-28 w-full" id="Cresume" name="Cresume" placeholder="Résumé du chapitre..."></textarea>
                        </label>

                        <div class="grid gap-4 md:grid-cols-2">
                            <label class="form-control w-full">
                                <span class="label-text mb-2">Vignette</span>
                                <input class="input input-bordered input-primary w-full" type="text" id="Cvignette" name="Cvignette" placeholder="https://...">
                            </label>

                            <label class="form-control w-full">
                                <span class="label-text mb-2">Date de sortie</span>
                                <input class="input input-bordered input-primary w-full" type="date" id="Cdate" name="Cdate">
                            </label>
                        </div>

                        <label class="form-control w-full">
                            <span class="label-text mb-2">Nombre de mots</span>
                            <input class="input input-bordered input-primary w-full" type="number" id="CnbrMots" name="CnbrMots" min="0" step="1" value="0">
                        </label>
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