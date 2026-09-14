<?php
require_once __DIR__ . '/../back/connexion_bdd.php';
require_once __DIR__ . '/../back/function.php';
$list_webnovel = listWebnovel($pdo);
?>
<!DOCTYPE html>
<html lang="fr" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="asset/output.css" rel="stylesheet">
    <title>WebNovel_Checker</title>
</head>
<body class="min-h-screen bg-base-200 text-base-content">
    <?php include __DIR__ . '/header.php'; ?>

    <main class="mx-auto max-w-5xl px-4 py-10">
        <section class="card bg-base-100 shadow-xl">
            <div class="card-body">
                <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="text-xs uppercase tracking-[0.28em] text-base-content/60">Bibliothèque</p>
                        <h1 class="mt-2 text-3xl font-bold">Liste des webnovels</h1>
                    </div>
                    <a href="ajout_webnovel.php" class="btn btn-primary">+ Ajouter</a>
                </div>

                <div class="space-y-3">
                    <?php foreach ($list_webnovel as $lw): ?>
                        <article class="flex flex-col gap-3 rounded-box border border-base-300 bg-base-100 p-3 shadow-sm sm:flex-row sm:items-center">
                            <img
                                class="size-16 rounded-box object-cover shadow-md"
                                alt="<?= htmlspecialchars($lw['nom']) ?>"
                                src="<?= htmlspecialchars($lw['vignette']) ?>"
                            >

                            <div class="min-w-0 flex-1">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <h2 class="text-lg font-semibold text-base-content"><?= htmlspecialchars($lw['nom']) ?></h2>
                                        <p class="text-xs uppercase tracking-wide text-base-content/60">
                                            <?= htmlspecialchars($lw['date_sortie']) ?>
                                        </p>
                                    </div>
                                    <span class="badge badge-outline badge-sm hidden md:inline-flex">Série</span>
                                </div>
                                <p class="mt-2 text-sm text-base-content/70">
                                    <?= htmlspecialchars(mb_strimwidth($lw['resume'], 0, 110, '...')) ?>
                                </p>
                            </div>

                            <a href="fiche_webnovel.php?id=<?= (int) $lw['id'] ?>" class="btn btn-primary btn-sm">
                                Voir
                            </a>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    </main>
</body>
</html>