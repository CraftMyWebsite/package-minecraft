<?php

use CMW\Utils\Website;

/* @var \CMW\Entity\Minecraft\MinecraftHistoryEntity[] $userHistories */

Website::setTitle('Minecraft');
Website::setDescription("Vos historiques d'achats et récompenses en jeux");

$total = is_array($userHistories) ? count($userHistories) : 0;
?>

<style>
    :root{
        --mc-4545-bg: rgba(255, 255, 255, 0.07);
        --mc-4545-surface: rgba(255,255,255,.75);
        --mc-4545-surface-2: rgba(255,255,255,.55);
        --mc-4545-text: #111827;
        --mc-4545-text-2: rgba(17,24,39,.75);
        --mc-4545-border: rgba(17,24,39,.12);
        --mc-4545-shadow: 0 10px 30px rgba(0,0,0,.08);
        --mc-4545-accent: #22c55e;
        --mc-4545-accent-2: rgba(34,197,94,.12);
        --mc-4545-muted: rgba(17,24,39,.06);
    }

    .mc-4545-wrap{
        var(--mc-4545-bg);
        padding: 28px 0;
    }

    .mc-4545-container{
        width: min(1100px, calc(100% - 32px));
        margin: 0 auto;
    }

    .mc-4545-header{
        display: flex;
        gap: 16px;
        border-radius: 15px;
        background: var(--mc-4545-surface);
        border: 1px solid var(--mc-4545-border);
        box-shadow: var(--mc-4545-shadow);
        align-items: flex-start;
        justify-content: space-between;
        margin-bottom: 18px;
        padding: .2rem .5rem .2rem .5rem;
    }

    .mc-4545-title{
        margin: 0;
        font-size: 26px;
        letter-spacing: -0.02em;
        color: var(--mc-4545-text);
        line-height: 1.15;
    }

    .mc-4545-subtitle{
        margin: 6px 0 0 0;
        color: var(--mc-4545-text-2);
        font-size: 14px;
    }

    .mc-4545-pill{
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 12px;
        border-radius: 999px;
        background: var(--mc-4545-surface);
        border: 1px solid var(--mc-4545-border);
        box-shadow: var(--mc-4545-shadow);
        color: var(--mc-4545-text);
        font-size: 13px;
        white-space: nowrap;
    }

    .mc-4545-pill-dot{
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: var(--mc-4545-accent);
        box-shadow: 0 0 0 4px var(--mc-4545-accent-2);
        flex: 0 0 auto;
    }

    .mc-4545-card{
        border: 1px solid var(--mc-4545-border);
        background: var(--mc-4545-surface);
        border-radius: 18px;
        box-shadow: var(--mc-4545-shadow);
        overflow: hidden;
    }

    .mc-4545-toolbar{
        display: flex;
        gap: 10px;
        align-items: center;
        justify-content: space-between;
        padding: 14px 14px;
        border-bottom: 1px solid var(--mc-4545-border);
        background: linear-gradient(to bottom, var(--mc-4545-surface), var(--mc-4545-surface-2));
    }

    .mc-4545-search{
        display: flex;
        align-items: center;
        gap: 10px;
        width: 100%;
    }

    .mc-4545-search-input{
        width: 100%;
        padding: 11px 12px;
        border-radius: 12px;
        border: 1px solid var(--mc-4545-border);
        background: var(--mc-4545-muted);
        color: var(--mc-4545-text);
        outline: none;
        font-size: 14px;
    }
    .mc-4545-search-input::placeholder{ color: var(--mc-4545-text-2); }

    .mc-4545-meta{
        display: flex;
        gap: 10px;
        align-items: center;
        flex: 0 0 auto;
        color: var(--mc-4545-text-2);
        font-size: 13px;
    }

    .mc-4545-list{
        padding: 14px;
        display: grid;
        gap: 10px;
    }

    .mc-4545-item{
        display: grid;
        grid-template-columns: 1fr auto;
        gap: 12px;
        padding: 14px 14px;
        border-radius: 14px;
        border: 1px solid var(--mc-4545-border);
        background: rgba(255,255,255,.02);
    }

    .mc-4545-item-title{
        margin: 0;
        color: var(--mc-4545-text);
        font-size: 15px;
        line-height: 1.25;
    }

    .mc-4545-item-desc{
        margin: 6px 0 0 0;
        color: var(--mc-4545-text-2);
        font-size: 13px;
        line-height: 1.45;
    }

    .mc-4545-badges{
        display: inline-flex;
        gap: 8px;
        align-items: center;
        justify-content: flex-end;
        flex-wrap: wrap;
        height: fit-content;
    }

    .mc-4545-badge{
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 10px;
        border-radius: 999px;
        border: 1px solid var(--mc-4545-border);
        background: var(--mc-4545-muted);
        color: var(--mc-4545-text);
        font-size: 12px;
        white-space: nowrap;
    }

    .mc-4545-badge-strong{
        border-color: rgba(34,197,94,.30);
        background: var(--mc-4545-accent-2);
    }

    .mc-4545-empty{
        padding: 24px 14px;
        text-align: center;
        color: var(--mc-4545-text-2);
        font-size: 14px;
    }

    .mc-4545-empty-title{
        margin: 0 0 6px 0;
        color: var(--mc-4545-text);
        font-size: 16px;
    }

    @media (max-width: 720px){
        .mc-4545-header{ flex-direction: column; align-items: stretch; }
        .mc-4545-toolbar{ flex-direction: column; align-items: stretch; }
        .mc-4545-meta{ justify-content: space-between; }
        .mc-4545-item{ grid-template-columns: 1fr; }
        .mc-4545-badges{ justify-content: flex-start; }
    }
</style>

<section class="mc-4545-wrap">
    <div class="mc-4545-container">

        <header class="mc-4545-header">
            <div>
                <h1 class="mc-4545-title">Historique Minecraft</h1>
                <p class="mc-4545-subtitle">Achats et récompenses obtenus en jeu, regroupés dans une seule catégorie.</p>
            </div>

            <div class="mc-4545-pill" aria-label="Résumé">
                <span class="mc-4545-pill-dot" aria-hidden="true"></span>
                <span><strong><?= (int)$total ?></strong> entrée<?= $total > 1 ? 's' : '' ?></span>
            </div>
        </header>

        <div class="mc-4545-card">
            <div class="mc-4545-toolbar">
                <div class="mc-4545-search">
                    <input
                        id="mc-4545-search"
                        class="mc-4545-search-input"
                        type="search"
                        placeholder="Rechercher (serveur, titre, description, date)…"
                        autocomplete="off"
                    />
                </div>

                <div class="mc-4545-meta">
                    <span id="mc-4545-count"><?= (int)$total ?></span>
                    <span>affiché<?= $total > 1 ? 's' : '' ?></span>
                </div>
            </div>

            <?php if (empty($userHistories)) : ?>
                <div class="mc-4545-empty">
                    <p class="mc-4545-empty-title">Aucun historique pour le moment</p>
                    <div>Les achats et récompenses apparaîtront ici dès qu’ils seront enregistrés.</div>
                </div>
            <?php else : ?>
                <div class="mc-4545-list" id="mc-4545-list">
                    <?php foreach ($userHistories as $history) :
                        $server = $history->getServerName();
                        $title = $history->getTitle() ?? 'Événement';
                        $desc = $history->getDescription();
                        $created = $history->getCreatedAt();
                        $updated = $history->getUpdatedAt();

                        // Texte de recherche (sans HTML)
                        $searchHaystack = mb_strtolower(trim($server.' '.$title.' '.($desc ?? '').' '.$created.' '.$updated));
                        ?>
                        <article class="mc-4545-item" data-mc-4545-search="<?= htmlspecialchars($searchHaystack, ENT_QUOTES) ?>">
                            <div>
                                <h3 class="mc-4545-item-title"><?= htmlspecialchars($title) ?></h3>

                                <?php if (!empty($desc)) : ?>
                                    <p class="mc-4545-item-desc"><?= nl2br(htmlspecialchars($desc)) ?></p>
                                <?php else : ?>
                                    <p class="mc-4545-item-desc"><em>Aucune description.</em></p>
                                <?php endif; ?>
                            </div>

                            <div class="mc-4545-badges">
                <span class="mc-4545-badge mc-4545-badge-strong" title="Serveur">
                  <?= htmlspecialchars($server) ?>
                </span>
                                <span class="mc-4545-badge" title="Créé le">
                  <?= htmlspecialchars($created) ?>
                </span>
                                <?php if ($updated !== $created) : ?>
                                    <span class="mc-4545-badge" title="Mis à jour le">
                    <?= htmlspecialchars($updated) ?>
                  </span>
                                <?php endif; ?>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

    </div>
</section>

<script>
    (function(){
        const input = document.getElementById('mc-4545-search');
        const list = document.getElementById('mc-4545-list');
        const count = document.getElementById('mc-4545-count');

        if (!input || !list || !count) return;

        const items = Array.from(list.querySelectorAll('.mc-4545-item'));
        const total = items.length;

        function applyFilter(){
            const q = (input.value || '').trim().toLowerCase();
            let shown = 0;

            for (const item of items){
                const hay = item.getAttribute('data-mc-4545-search') || '';
                const ok = !q || hay.includes(q);
                item.style.display = ok ? '' : 'none';
                if (ok) shown++;
            }

            count.textContent = String(shown);
        }

        input.addEventListener('input', applyFilter);
        applyFilter();
    })();
</script>
