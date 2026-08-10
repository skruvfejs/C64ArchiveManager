<p>
    <a href="/administration">
        <?= htmlspecialchars(
            $language->get('back_to_administration')
        ) ?>
    </a>
</p>

<h2>
    <?= htmlspecialchars(
        $language->get('settings')
    ) ?>
</h1>


<p>
    <?= htmlspecialchars(
        $language->get('settings_description')
    ) ?>
</p>


<form
    method="post"
    action="/administration/system/settings"
>

    <div class="form-group">

        <label for="site_name">
            <?= htmlspecialchars(
                $language->get('site_name')
            ) ?>
        </label>

        <input
            type="text"
            id="site_name"
            name="site_name"
            value="<?= htmlspecialchars(
                $settings['site_name'] ?? '',
                ENT_QUOTES,
                'UTF-8'
            ) ?>"
            required
        >

    </div>


    <div class="form-group">

        <label for="default_language">
            <?= htmlspecialchars(
                $language->get('default_language')
            ) ?>
        </label>

        <select
            id="default_language"
            name="default_language"
        >

            <option
                value="sv"
                <?= ($settings['default_language'] ?? '') === 'sv'
                    ? 'selected'
                    : '' ?>
            >
                Svenska
            </option>


            <option
                value="en"
                <?= ($settings['default_language'] ?? '') === 'en'
                    ? 'selected'
                    : '' ?>
            >
                English
            </option>

        </select>

    </div>


    <div class="form-group">

        <label for="date_format">
            <?= htmlspecialchars(
                $language->get('date_format')
            ) ?>
        </label>

        <select
            id="date_format"
            name="date_format"
        >

            <option
                value="Y-m-d"
                <?= ($settings['date_format'] ?? '') === 'Y-m-d'
                    ? 'selected'
                    : '' ?>
            >
                2026-08-08
            </option>


            <option
                value="d-m-Y"
                <?= ($settings['date_format'] ?? '') === 'd-m-Y'
                    ? 'selected'
                    : '' ?>
            >
                08-08-2026
            </option>


            <option
                value="Y-m-d H:i"
                <?= ($settings['date_format'] ?? '') === 'Y-m-d H:i'
                    ? 'selected'
                    : '' ?>
            >
                2026-08-08 23:30
            </option>

        </select>

    </div>


    <div class="form-group">

        <label for="items_per_page">
            <?= htmlspecialchars(
                $language->get('items_per_page')
            ) ?>
        </label>

        <select
            id="items_per_page"
            name="items_per_page"
        >

            <?php foreach ([10, 25, 50, 100] as $amount): ?>

                <option
                    value="<?= $amount ?>"
                    <?= (int) ($settings['items_per_page'] ?? 25) === $amount
                        ? 'selected'
                        : '' ?>
                >
                    <?= $amount ?>
                </option>

            <?php endforeach; ?>

        </select>

    </div>


    <div class="form-group">

        <label>

            <input
                type="checkbox"
                name="maintenance_mode"
                value="1"
                <?= ($settings['maintenance_mode'] ?? '0') === '1'
                    ? 'checked'
                    : '' ?>
            >

            <?= htmlspecialchars(
                $language->get('maintenance_mode')
            ) ?>

        </label>

    </div>


    <div class="form-group">

        <label>

            <input
                type="checkbox"
                name="registration_enabled"
                value="1"
                <?= ($settings['registration_enabled'] ?? '0') === '1'
                    ? 'checked'
                    : '' ?>
            >

            <?= htmlspecialchars(
                $language->get('registration_enabled')
            ) ?>

        </label>

    </div>


    <div class="form-actions">

        <button type="submit">
            <?= htmlspecialchars(
                $language->get('save_settings')
            ) ?>
        </button>

    </div>

</form>
