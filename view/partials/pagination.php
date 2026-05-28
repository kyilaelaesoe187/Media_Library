<?php if ($totalPages > 1): ?>
<div class="pagination">

    <?php for ($i = 1; $i <= $totalPages; $i++): ?>

        <?php
        $query = $_GET; // IMPORTANT: preserve all params

        $query['page'] = 'catalog';
        $query['pg'] = $i;

        $url = 'index.php?' . http_build_query($query);
        ?>

        <?php if ((int)$currentPage === $i): ?>
            <span class="active"><?= $i ?></span>
        <?php else: ?>
            <a href="<?= $url ?>"><?= $i ?></a>
        <?php endif; ?>

    <?php endfor; ?>

</div>
<?php endif; ?>