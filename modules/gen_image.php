<div id="gen_image" class="content" data-url="php-logogen">

    <div class="card card-primary">
        <h1 class="card-header"><?= icon("image", 1, 2) ?> Image Generator</h1>
        <div class="card-body">
            <?php
                do {
                    $logoGenUrl = SITE_BASE_URL . "/" . "php-logogen";
                    if (!is_dir($logoGenUrl)) {
                        echo "<span class='description'>Logogen not found ($logoGenUrl)</span>";
                        break;
                    }
                    echo '
                    <a href="'.$logoGenUrl.'" target="_blank" class="btn btn-primary mb-3">
                        <i class="fa fa-arrow-left"></i> Open Image Generator
                    </a>
                    ';
                } while (False);
            ?>
        </div>
    </div>

</div>