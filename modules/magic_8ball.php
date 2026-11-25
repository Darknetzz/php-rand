<div id="magic_8ball" class="content">

    <div class="card card-primary">
        <h1 class="card-header">Magic 8-Ball</h1>
        <div class="card-body">
            <span class="description">Ask a yes-or-no question and shake the ball. Answers are picked on the server with <code>random_int()</code>.</span>
            <hr>
            <form class="form" action="gen.php" method="POST" id="magic_8ball_form" data-action="eightball" data-no-random-buttons>
                <div class="mb-3">
                    <label class="form-label" for="eightball_question">Your question <small class="text-muted">(optional)</small></label>
                    <input type="text" name="eightball_question" id="eightball_question" class="form-control form-control-lg" maxlength="<?= EIGHTBALL_MAX_QUESTION ?>" placeholder="Will it rain tomorrow?" autocomplete="off">
                </div>

                <div class="mb-3">
                    <label class="form-label d-block">Answers</label>
                    <div class="btn-group" role="group">
                        <input type="radio" class="btn-check" name="eightball_mode" id="eightball_mode_eightball" value="eightball" checked>
                        <label class="btn btn-outline-secondary" for="eightball_mode_eightball">8-ball (20 answers)</label>
                        <input type="radio" class="btn-check" name="eightball_mode" id="eightball_mode_yesno" value="yesno">
                        <label class="btn btn-outline-secondary" for="eightball_mode_yesno">Plain yes / no</label>
                    </div>
                </div>

                <?= seed_field_html("eightball_", "answers") ?>

                <?= submitBtn("eightball", text: "Shake", icon: "circle-question-mark") ?>

                <div class="responseDiv mt-3" id="magic_8ball_response"></div>
            </form>

            <?= result_history_html("eightball") ?>
        </div>
    </div>

</div>
