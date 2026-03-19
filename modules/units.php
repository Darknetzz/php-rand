<?php
/**
 * Unit Converter – one page for all 13 categories: Currency, Volume, Length,
 * Weight and mass, Temperature, Energy, Area, Speed, Time, Power, Data, Pressure, Angle.
 * Convert → Units
 */
$currencyList = [
    'USD' => 'USD - US Dollar', 'EUR' => 'EUR - Euro', 'GBP' => 'GBP - British Pound',
    'JPY' => 'JPY - Japanese Yen', 'AUD' => 'AUD - Australian Dollar', 'CAD' => 'CAD - Canadian Dollar',
    'CHF' => 'CHF - Swiss Franc', 'CNY' => 'CNY - Chinese Yuan', 'SEK' => 'SEK - Swedish Krona',
    'NZD' => 'NZD - New Zealand Dollar', 'MXN' => 'MXN - Mexican Peso', 'SGD' => 'SGD - Singapore Dollar',
    'HKD' => 'HKD - Hong Kong Dollar', 'NOK' => 'NOK - Norwegian Krone', 'KRW' => 'KRW - South Korean Won',
    'INR' => 'INR - Indian Rupee', 'BRL' => 'BRL - Brazilian Real', 'ZAR' => 'ZAR - South African Rand',
    'RUB' => 'RUB - Russian Ruble', 'TRY' => 'TRY - Turkish Lira',
];
?>
<div id="units" class="content">
    <div class="card card-primary">
        <h1 class="card-header"><?= icon('rulers') ?> Unit Converter</h1>
        <div class="card-body">
            <div class="alert alert-info mb-4">
                <strong>Convert between units</strong> — Choose a category, enter a value and source unit, then convert.
            </div>

            <ul class="nav nav-tabs mb-4" id="unitsTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="tab-currency" data-bs-toggle="tab" data-bs-target="#pane-currency" type="button" role="tab"><?= icon('currency-exchange') ?> Currency</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="tab-volume" data-bs-toggle="tab" data-bs-target="#pane-volume" type="button" role="tab"><?= icon('box') ?> Volume</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="tab-length" data-bs-toggle="tab" data-bs-target="#pane-length" type="button" role="tab"><?= icon('rulers') ?> Length</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="tab-weight" data-bs-toggle="tab" data-bs-target="#pane-weight" type="button" role="tab"><?= icon('speedometer2') ?> Weight & mass</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="tab-temperature" data-bs-toggle="tab" data-bs-target="#pane-temperature" type="button" role="tab"><?= icon('thermometer-half') ?> Temperature</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="tab-energy" data-bs-toggle="tab" data-bs-target="#pane-energy" type="button" role="tab"><?= icon('fire') ?> Energy</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="tab-area" data-bs-toggle="tab" data-bs-target="#pane-area" type="button" role="tab"><?= icon('grid-3x3') ?> Area</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="tab-speed" data-bs-toggle="tab" data-bs-target="#pane-speed" type="button" role="tab"><?= icon('speedometer2') ?> Speed</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="tab-time" data-bs-toggle="tab" data-bs-target="#pane-time" type="button" role="tab"><?= icon('clock') ?> Time</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="tab-power" data-bs-toggle="tab" data-bs-target="#pane-power" type="button" role="tab"><?= icon('lightning') ?> Power</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="tab-data" data-bs-toggle="tab" data-bs-target="#pane-data" type="button" role="tab"><?= icon('hdd') ?> Data</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="tab-pressure" data-bs-toggle="tab" data-bs-target="#pane-pressure" type="button" role="tab"><?= icon('speedometer2') ?> Pressure</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="tab-angle" data-bs-toggle="tab" data-bs-target="#pane-angle" type="button" role="tab"><?= icon('pentagon') ?> Angle</button>
                </li>
            </ul>

            <div class="tab-content" id="unitsTabContent">
                <!-- Currency: uses existing backend -->
                <div class="tab-pane fade show active" id="pane-currency" role="tabpanel">
                    <form class="form" action="gen.php" method="POST" id="units-currency-form" data-action="currency">
                        <input type="hidden" name="action" value="currency">
                        <div class="row g-3 mb-3">
                            <div class="col-12 col-md-4">
                                <label class="form-label"><strong>Amount</strong></label>
                                <input type="number" name="currency_amount" class="form-control form-control-lg" value="1" step="0.01" min="0" required style="font-family: monospace;">
                            </div>
                            <div class="col-12 col-md-4">
                                <label class="form-label"><strong>From</strong></label>
                                <select name="currency_from" class="form-select form-select-lg" required style="font-family: monospace;">
                                    <?php foreach ($currencyList as $code => $label): ?>
                                        <option value="<?= htmlspecialchars($code) ?>"><?= htmlspecialchars($label) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-12 col-md-4">
                                <label class="form-label"><strong>To</strong></label>
                                <select name="currency_to" class="form-select form-select-lg" required style="font-family: monospace;">
                                    <?php foreach ($currencyList as $code => $label): ?>
                                        <option value="<?= htmlspecialchars($code) ?>"><?= htmlspecialchars($label) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <?= submitBtn("currency", "action", "Convert", "arrow-repeat", "lg") ?>
                        <div class="responseDiv units-response"></div>
                    </form>
                </div>

                <!-- Math-based categories: value + from unit, convert to all (client-side) -->
                <?php
                $mathTabs = ['volume', 'length', 'weight', 'temperature', 'energy', 'area', 'speed', 'time', 'power', 'data', 'pressure', 'angle'];
                foreach ($mathTabs as $cat):
                ?>
                <div class="tab-pane fade" id="pane-<?= $cat ?>" role="tabpanel">
                    <form class="form units-math-form" data-category="<?= $cat ?>" data-action="units">
                        <input type="hidden" name="action" value="units">
                        <input type="hidden" name="units_category" value="<?= $cat ?>">
                        <div class="row g-3 mb-3">
                            <div class="col-12 col-md-4">
                                <label class="form-label"><strong>Value</strong></label>
                                <input type="number" name="units_value" class="form-control form-control-lg" step="any" placeholder="Enter number" required style="font-family: monospace;">
                            </div>
                            <div class="col-12 col-md-4">
                                <label class="form-label"><strong>From unit</strong></label>
                                <select name="units_from" class="form-select form-select-lg units-from-select" style="font-family: monospace;"></select>
                            </div>
                        </div>
                        <button type="submit" class="genBtn btn btn-success btn-lg my-3"><?= icon('arrow-repeat') ?> Convert</button>
                        <div class="responseDiv units-response"></div>
                    </form>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<script>
(function() {
    // Conversion data: each unit has [label, factor to base]. value_in_base = value * factor.
    var UNITS = {
        volume: { base: 'L', units: { 'L': ['liter (L)', 1], 'mL': ['milliliter (mL)', 0.001], 'gal': ['gallon (US)', 3.78541], 'qt': ['quart (US)', 0.946353], 'pt': ['pint (US)', 0.473176], 'cup': ['cup (US)', 0.236588], 'floz': ['fluid ounce (US)', 0.0295735], 'm3': ['cubic meter', 1000], 'ft3': ['cubic foot', 28.3168], 'in3': ['cubic inch', 0.0163871] }},
        length: { base: 'm', units: { 'm': ['meter', 1], 'km': ['kilometer', 1000], 'cm': ['centimeter', 0.01], 'mm': ['millimeter', 0.001], 'mi': ['mile', 1609.34], 'yd': ['yard', 0.9144], 'ft': ['foot', 0.3048], 'in': ['inch', 0.0254], 'nmi': ['nautical mile', 1852] }},
        weight: { base: 'kg', units: { 'kg': ['kilogram', 1], 'g': ['gram', 0.001], 'mg': ['milligram', 0.000001], 'lb': ['pound', 0.453592], 'oz': ['ounce', 0.0283495], 't_metric': ['ton (metric)', 1000], 't_us': ['ton (US)', 907.185] }},
        temperature: { special: true }, // handled separately
        energy: { base: 'J', units: { 'J': ['joule', 1], 'kJ': ['kilojoule', 1000], 'cal': ['calorie', 4.184], 'kcal': ['kilocalorie', 4184], 'kWh': ['kilowatt-hour', 3600000], 'eV': ['electronvolt', 1.602e-19], 'BTU': ['BTU', 1055.06] }},
        area: { base: 'm2', units: { 'm2': ['square meter', 1], 'km2': ['square kilometer', 1e6], 'ft2': ['square foot', 0.092903], 'in2': ['square inch', 0.00064516], 'ha': ['hectare', 10000], 'acre': ['acre', 4046.86] }},
        speed: { base: 'm/s', units: { 'm/s': ['meter/second', 1], 'km/h': ['kilometer/hour', 0.277778], 'mph': ['mile/hour', 0.44704], 'knot': ['knot', 0.514444], 'ft/s': ['foot/second', 0.3048] }},
        time: { base: 's', units: { 's': ['second', 1], 'min': ['minute', 60], 'h': ['hour', 3600], 'd': ['day', 86400], 'w': ['week', 604800], 'mo': ['month (30 d)', 2592000], 'y': ['year (365 d)', 31536000] }},
        power: { base: 'W', units: { 'W': ['watt', 1], 'kW': ['kilowatt', 1000], 'hp_metric': ['horsepower (metric)', 735.499], 'hp_us': ['horsepower (US)', 745.7], 'BTU/h': ['BTU/hour', 0.293071] }},
        data: { base: 'B', units: { 'b': ['bit', 0.125], 'B': ['byte', 1], 'KB': ['kilobyte', 1000], 'MB': ['megabyte', 1e6], 'GB': ['gigabyte', 1e9], 'TB': ['terabyte', 1e12], 'KiB': ['kibibyte', 1024], 'MiB': ['mebibyte', 1048576], 'GiB': ['gibibyte', 1073741824], 'TiB': ['tebibyte', 1099511627776] }},
        pressure: { base: 'Pa', units: { 'Pa': ['pascal', 1], 'kPa': ['kilopascal', 1000], 'bar': ['bar', 100000], 'psi': ['psi', 6894.76], 'atm': ['atmosphere', 101325], 'mmHg': ['mmHg', 133.322], 'inHg': ['inHg', 3386.39] }},
        angle: { base: 'deg', units: { 'deg': ['degree', 1], 'rad': ['radian', 57.2958], 'grad': ['gradian', 0.9], 'arcmin': ['arcminute', 0.0166667], 'arcsec': ['arcsecond', 0.000277778] }}
    };

    function fillSelect(selectEl, category) {
        var data = UNITS[category];
        if (!data || data.special) return;
        var opts = [];
        for (var k in data.units) {
            opts.push('<option value="' + k + '">' + data.units[k][0] + '</option>');
        }
        selectEl.innerHTML = opts.join('');
    }

    function copyableRow(val, id) {
        var id = 'uc_' + (id || Date.now() + '_' + Math.random().toString(36).slice(2));
        return '<div style="display:flex;gap:8px;align-items:center;"><span style="flex:1;font-family:monospace;" id="' + id + '">' + (typeof val === 'number' ? (Number.isInteger(val) ? val : Number(val.toPrecision(10))) : val) + '</span><button type="button" class="btn btn-sm btn-outline-light" onclick="copyToClipboard(\'' + id + '\', this)"><i class="bi bi-files"></i> Copy</button></div>';
    }

    function renderTable(caption, rows) {
        var html = '<div class="table-responsive"><table class="table table-dark table-striped table-hover align-middle mb-0" style="border: 1px solid #334155;">';
        html += '<caption class="text-start fw-bold" style="caption-side: top; color: var(--bs-body-color);">' + caption + '</caption>';
        html += '<thead><tr><th>Unit</th><th>Value</th></tr></thead><tbody>';
        for (var i = 0; i < rows.length; i++) {
            html += '<tr><td>' + rows[i][0] + '</td><td style="max-width: 280px;">' + copyableRow(rows[i][1], 'r' + i) + '</td></tr>';
        }
        html += '</tbody></table></div>';
        return html;
    }

    function convertMath(category, value, fromKey) {
        value = parseFloat(value);
        if (isNaN(value)) return '<div class="alert alert-danger">Enter a valid number.</div>';

        if (category === 'temperature') {
            var C = fromKey === 'C' ? value : fromKey === 'F' ? (value - 32) / 1.8 : value - 273.15;
            var rows = [
                ['Celsius', C],
                ['Fahrenheit', C * 1.8 + 32],
                ['Kelvin', C + 273.15]
            ];
            return renderTable(value + ' ' + fromKey, rows.map(function(r) { return [r[0], typeof r[1] === 'number' ? (r[1].toFixed(6)) : r[1]]; }));
        }

        var data = UNITS[category];
        if (!data || !data.units[fromKey]) return '<div class="alert alert-danger">Invalid unit.</div>';
        var baseValue = value * data.units[fromKey][1];
        var rows = [];
        for (var k in data.units) {
            if (k === fromKey) continue;
            var v = baseValue / data.units[k][1];
            rows.push([data.units[k][0], v]);
        }
        return renderTable(value + ' ' + data.units[fromKey][0], rows);
    }

    // Populate "from unit" when tab is shown
    document.querySelectorAll('#unitsTab button[data-bs-toggle="tab"]').forEach(function(btn) {
        btn.addEventListener('show.bs.tab', function(e) {
            var targetId = e.target.getAttribute('data-bs-target').replace('#pane-', '');
            var pane = document.querySelector(e.target.getAttribute('data-bs-target'));
            var sel = pane ? pane.querySelector('.units-from-select') : null;
            if (sel && targetId !== 'currency') {
                if (targetId === 'temperature') {
                    sel.innerHTML = '<option value="C">Celsius</option><option value="F">Fahrenheit</option><option value="K">Kelvin</option>';
                } else {
                    fillSelect(sel, targetId);
                }
            }
        });
    });

    // First tab (volume) select filled on load if not currency
    var firstMathPane = document.querySelector('#pane-volume');
    if (firstMathPane) {
        var firstSel = firstMathPane.querySelector('.units-from-select');
        if (firstSel) fillSelect(firstSel, 'volume');
    }
    var tempPane = document.querySelector('#pane-temperature');
    if (tempPane) {
        var tempSel = tempPane.querySelector('.units-from-select');
        if (tempSel) tempSel.innerHTML = '<option value="C">Celsius</option><option value="F">Fahrenheit</option><option value="K">Kelvin</option>';
    }

    // Math form submit: prevent default and stop propagation so global .form handler doesn't POST
    document.querySelectorAll('.units-math-form').forEach(function(form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            e.stopImmediatePropagation();
            var cat = form.getAttribute('data-category');
            var val = form.querySelector('[name="units_value"]').value;
            var from = form.querySelector('[name="units_from"]').value;
            var responseDiv = form.querySelector('.units-response');
            if (!responseDiv) return;
            responseDiv.innerHTML = '<div class="text-center py-3"><div class="spinner-border text-primary"></div><p class="text-muted mt-2">Converting...</p></div>';
            setTimeout(function() {
                responseDiv.innerHTML = convertMath(cat, val, from);
            }, 50);
        });
    });

    // Currency form uses the global .form submit handler (POST to gen.php, action=currency)
})();
</script>
