<h1>
    <?= htmlspecialchars(
        $language->get('system_administration')
    ) ?>
</h1>


<p>
    <?= htmlspecialchars(
        $language->get('system_administration_description')
    ) ?>
</p>


<div class="system-grid">


    <div class="system-card">

        <h2>
            <a href="/administration/system/database">
                <?= htmlspecialchars(
                    $language->get('database')
                ) ?>
            </a>
        </h2>

        <p>
            <?= htmlspecialchars(
                $language->get('database_description')
            ) ?>
        </p>

    </div>


    <div class="system-card">

        <h2>
            <?= htmlspecialchars(
                $language->get('settings')
            ) ?>
        </h2>

        <p>
            <?= htmlspecialchars(
                $language->get('settings_description')
            ) ?>
        </p>

    </div>


    <div class="system-card">

        <h2>
            <?= htmlspecialchars(
                $language->get('maintenance')
            ) ?>
        </h2>

        <p>
            <?= htmlspecialchars(
                $language->get('maintenance_description')
            ) ?>
        </p>

    </div>


    <div class="system-card">

        <h2>
            <?= htmlspecialchars(
                $language->get('system_information')
            ) ?>
        </h2>

        <p>
            <?= htmlspecialchars(
                $language->get('system_information_description')
            ) ?>
        </p>

    </div>


</div>
