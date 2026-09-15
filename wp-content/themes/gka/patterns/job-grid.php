<?php
/**
 * Title: Grid lowongan
 * Slug: gka/job-grid
 * Categories: gka
 * Inserter: no
 */
?>
<!-- wp:query {"queryId":15,"query":{"postType":"gka_lowongan","perPage":6,"inherit":false},"className":"gka-jobs"} -->
<div class="wp-block-query gka-jobs"><!-- wp:post-template {"layout":{"type":"grid","columnCount":3}} -->
<!-- wp:group {"className":"gka-job","layout":{"type":"default"}} --><div class="wp-block-group gka-job">
<!-- wp:paragraph {"metadata":{"bindings":{"content":{"source":"core/post-meta","args":{"key":"departemen"}}}},"className":"gka-dept"} --><p class="gka-dept"></p><!-- /wp:paragraph -->
<!-- wp:post-title {"level":3,"isLink":true} /-->
<!-- wp:paragraph {"metadata":{"bindings":{"content":{"source":"core/post-meta","args":{"key":"lokasi"}}}},"className":"gka-job-meta"} --><p class="gka-job-meta"></p><!-- /wp:paragraph -->
<!-- wp:paragraph {"metadata":{"bindings":{"content":{"source":"core/post-meta","args":{"key":"tipe"}}}},"className":"gka-job-meta"} --><p class="gka-job-meta"></p><!-- /wp:paragraph -->
<!-- wp:paragraph {"metadata":{"bindings":{"content":{"source":"gka/deadline"}}},"className":"gka-job-meta gka-deadline"} --><p class="gka-job-meta gka-deadline"></p><!-- /wp:paragraph -->
</div><!-- /wp:group -->
<!-- /wp:post-template -->
<!-- wp:query-no-results --><!-- wp:paragraph --><p>Belum ada lowongan aktif. Kirim CV umum ke ita@pt-gka.com.</p><!-- /wp:paragraph --><!-- /wp:query-no-results --></div>
<!-- /wp:query -->
