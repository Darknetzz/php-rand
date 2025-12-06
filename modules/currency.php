<div id="currency" class="content">

    <div class="card card-primary">
        <h1 class="card-header">Currency Converter</h1>
        <div class="card-body">
            <form class="form" action="gen.php" method="POST" id="currency" data-action="currency">
                <div class="row">
                    <div class="col-md-6">
                        <label for="currency_amount">Amount:</label>
                        <input type="number" id="currency_amount" name="currency_amount" class="form-control mb-2" value="1" step="0.01" min="0" required>
                    </div>
                    <div class="col-md-6">
                        <label for="currency_from">From:</label>
                        <select name="currency_from" id="currency_from" class="form-select mb-2" required>
                            <option value="">-- Select currency --</option>
                            <option value="USD">USD - US Dollar</option>
                            <option value="EUR">EUR - Euro</option>
                            <option value="GBP">GBP - British Pound</option>
                            <option value="JPY">JPY - Japanese Yen</option>
                            <option value="AUD">AUD - Australian Dollar</option>
                            <option value="CAD">CAD - Canadian Dollar</option>
                            <option value="CHF">CHF - Swiss Franc</option>
                            <option value="CNY">CNY - Chinese Yuan</option>
                            <option value="SEK">SEK - Swedish Krona</option>
                            <option value="NZD">NZD - New Zealand Dollar</option>
                            <option value="MXN">MXN - Mexican Peso</option>
                            <option value="SGD">SGD - Singapore Dollar</option>
                            <option value="HKD">HKD - Hong Kong Dollar</option>
                            <option value="NOK">NOK - Norwegian Krone</option>
                            <option value="KRW">KRW - South Korean Won</option>
                            <option value="INR">INR - Indian Rupee</option>
                            <option value="BRL">BRL - Brazilian Real</option>
                            <option value="ZAR">ZAR - South African Rand</option>
                            <option value="RUB">RUB - Russian Ruble</option>
                            <option value="TRY">TRY - Turkish Lira</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <label for="currency_to">To:</label>
                        <select name="currency_to" id="currency_to" class="form-select mb-2" required>
                            <option value="">-- Select currency --</option>
                            <option value="USD">USD - US Dollar</option>
                            <option value="EUR">EUR - Euro</option>
                            <option value="GBP">GBP - British Pound</option>
                            <option value="JPY">JPY - Japanese Yen</option>
                            <option value="AUD">AUD - Australian Dollar</option>
                            <option value="CAD">CAD - Canadian Dollar</option>
                            <option value="CHF">CHF - Swiss Franc</option>
                            <option value="CNY">CNY - Chinese Yuan</option>
                            <option value="SEK">SEK - Swedish Krona</option>
                            <option value="NZD">NZD - New Zealand Dollar</option>
                            <option value="MXN">MXN - Mexican Peso</option>
                            <option value="SGD">SGD - Singapore Dollar</option>
                            <option value="HKD">HKD - Hong Kong Dollar</option>
                            <option value="NOK">NOK - Norwegian Krone</option>
                            <option value="KRW">KRW - South Korean Won</option>
                            <option value="INR">INR - Indian Rupee</option>
                            <option value="BRL">BRL - Brazilian Real</option>
                            <option value="ZAR">ZAR - South African Rand</option>
                            <option value="RUB">RUB - Russian Ruble</option>
                            <option value="TRY">TRY - Turkish Lira</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="currency_rate" style="opacity: 0.6;">Exchange Rate (advanced):</label>
                        <input type="number" id="currency_rate" name="currency_rate" class="form-control mb-2" placeholder="Optional: override with custom rate" step="0.0001" style="opacity: 0.7;">
                    </div>
                </div>

                <br>
                <?= submitBtn("currency", "action", "Convert", "arrow-repeat") ?>
                <div class="responseDiv" id="currencyresponse"></div>
            </form>
        </div>
    </div>

    <div class="card card-secondary mt-4">
        <h2 class="card-header">About</h2>
        <div class="card-body">
            <p class="description"><strong>Live Exchange Rates:</strong> This converter fetches real-time exchange rates from exchangerate-api.com (free tier).</p>
            <p class="text-muted"><small>Exchange rates are updated regularly. Rates shown are indicative and may vary slightly from actual trading prices.</small></p>
        </div>
    </div>

</div>

<script>
    // Currency form handling
    $("#currency").on("submit", function(e) {
        e.preventDefault();
        
        var formData = $(this).serialize();
        
        axios.post('gen.php', formData, {
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            }
        })
        .then(function(response) {
            $("#currencyresponse").html(response.data);
        })
        .catch(function(error) {
            console.error(error);
            $("#currencyresponse").html('<div class="alert alert-danger">Error processing request</div>');
        });
    });
</script>
