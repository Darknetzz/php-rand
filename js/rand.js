/* ===================================================================== */
/*                         FUNCTION: getTimeZone                         */
/* ===================================================================== */
function getTimeZone() {
    var date    = new Date();
    var offset  = -date.getTimezoneOffset();
    var hours   = Math.floor(Math.abs(offset) / 60);
    var minutes = Math.abs(offset) % 60;
    var sign    = offset >= 0 ? '+' : '-';
    var tz      = 'UTC' + sign + (hours < 10 ? '0' : '') + hours + ':' + (minutes < 10 ? '0' : '') + minutes;
    return tz
}

/* ===================================================================== */
/*                         FUNCTION: setTimeZone                         */
/* ===================================================================== */
function setTimeZone(tz = null) {
    if (tz == null) {
        tz = getTimeZone();
    }
    console.log("[setTimeZone] Setting timezone to: " + tz);
    $(".timezone").text(tz);
    // Set the timezone in the datetime object
    var date = new Date();
    date.toLocaleString("en-US", { timeZone: tz });
    updateTime(tz);
}

/* ===================================================================== */
/*                          FUNCTION: updateTime                         */
/* ===================================================================== */
function updateTime(tz = null) {
    const obj = $(".datetime");
    let now;
    if (tz && tz.match(/^[A-Za-z_/\-]+$/)) {
        // Only use toLocaleString if tz is a valid IANA time zone name
        now = new Date(new Date().toLocaleString("en-US", { timeZone: tz }));
    } else {
        now = new Date();
    }
    const pad = n => n.toString().padStart(2, '0');
    const formatted = now.getFullYear() + '-' +
        pad(now.getMonth() + 1) + '-' +
        pad(now.getDate()) + ' ' +
        pad(now.getHours()) + ':' +
        pad(now.getMinutes()) + ':' +
        pad(now.getSeconds());
    obj.text(formatted)
}

/* ===================================================================== */
/*                           FUNCTION: showData                          */
/* ===================================================================== */
function showData(obj, data) {
    if (obj.is("div")) {
        obj.html(data);
    } else if (obj.is("code-input")) {
        obj.val(data.trim());
    } else {
        data = data.replace(/<(.|\n)*?>/g, '');
        obj.val(data.trim());
    }
}

/* ===================================================================== */
/*                       FUNCTION: buildLoadingHtml                       */
/* ===================================================================== */
function buildLoadingHtml(message = "Generating...") {
    return '<div class="tool-loading"><div class="spinner-border text-primary tool-loading-spinner" role="status"><span class="visually-hidden">Loading...</span></div><p class="tool-loading-text">' + message + '</p></div>';
}

/* ===================================================================== */
/*                      FUNCTION: loadScriptOnce                          */
/* ===================================================================== */
window._assetPromises = window._assetPromises || {};
function loadScriptOnce(src) {
    const key = "script:" + src;
    if (window._assetPromises[key]) {
        return window._assetPromises[key];
    }
    const deferred = $.Deferred();
    const existing = document.querySelector('script[src="' + src + '"]');
    if (existing) {
        deferred.resolve();
    } else {
        const script = document.createElement("script");
        script.src = src;
        script.async = true;
        script.onload = () => deferred.resolve();
        script.onerror = () => deferred.reject(new Error("Failed to load script: " + src));
        document.head.appendChild(script);
    }
    window._assetPromises[key] = deferred.promise();
    return window._assetPromises[key];
}

/* ===================================================================== */
/*                       FUNCTION: loadStyleOnce                          */
/* ===================================================================== */
function loadStyleOnce(href) {
    const key = "style:" + href;
    if (window._assetPromises[key]) {
        return window._assetPromises[key];
    }
    const deferred = $.Deferred();
    const existing = document.querySelector('link[rel="stylesheet"][href="' + href + '"]');
    if (existing) {
        deferred.resolve();
    } else {
        const link = document.createElement("link");
        link.rel = "stylesheet";
        link.href = href;
        link.onload = () => deferred.resolve();
        link.onerror = () => deferred.reject(new Error("Failed to load stylesheet: " + href));
        document.head.appendChild(link);
    }
    window._assetPromises[key] = deferred.promise();
    return window._assetPromises[key];
}

/* ===================================================================== */
/*                    FUNCTION: ensureMarkdownAssets                      */
/* ===================================================================== */
function ensureMarkdownAssets() {
    return $.when(
        loadScriptOnce("https://cdn.jsdelivr.net/npm/marked/lib/marked.umd.js"),
        loadStyleOnce("https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.11.1/styles/dark.min.css"),
        loadScriptOnce("https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.11.1/highlight.min.js")
    );
}

/* ===================================================================== */
/*                    FUNCTION: ensureCodeInputAssets                     */
/* ===================================================================== */
function ensureCodeInputAssets() {
    return $.when(
        loadStyleOnce("https://cdn.jsdelivr.net/npm/@webcoder49/code-input@2.7.1/code-input.min.css"),
        loadScriptOnce("https://cdn.jsdelivr.net/npm/@webcoder49/code-input@2.7.1/code-input.min.js"),
        loadScriptOnce("js/hljs_autodetect.js"),
        loadScriptOnce("js/hljs_indent.js"),
        loadStyleOnce("https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.11.1/styles/dark.min.css"),
        loadScriptOnce("https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.11.1/highlight.min.js")
    );
}

/* ===================================================================== */
/*                     FUNCTION: initCodeInputTemplate                    */
/* ===================================================================== */
window._codeInputTemplateReady = window._codeInputTemplateReady || false;
function initCodeInputTemplate() {
    if (window._codeInputTemplateReady) {
        return;
    }
    if (typeof codeInput === "undefined" || typeof hljs === "undefined") {
        return;
    }
    codeInput.registerTemplate("default", codeInput.templates.hljs(hljs, [
        new codeInput.plugins.Autodetect(),
        new codeInput.plugins.Indent(true, 2)
    ]));
    window._codeInputTemplateReady = true;
}

/* ===================================================================== */
/*                    FUNCTION: ensureEnhancersForScope                   */
/* ===================================================================== */
function ensureEnhancersForScope($scope) {
    const hasCodeInput = $scope.find("code-input").length > 0;
    if (!hasCodeInput) {
        return $.Deferred().resolve().promise();
    }
    return ensureCodeInputAssets().done(function() {
        initCodeInputTemplate();
    });
}

/* ===================================================================== */
/*                        FUNCTION: submitToolForm                        */
/* ===================================================================== */
function submitToolForm($form, options = {}) {
    var form = $form;
    var responseObj = options.responseObj || form.find(".responseDiv");
    var responseType = options.responseType || form.data("responsetype") || "html";
    var action = options.action || form.data("action") || form.find("input[name=action]").val();
    var url = options.url || form.attr("action") || "gen.php";
    var data = options.data || form.serialize();
    var loadingMessage = options.loadingMessage || "Generating...";
    var onSuccess = options.onSuccess || function(dataOut) { showData(responseObj, dataOut); };
    var onError = options.onError || function(xhr) {
        var message = responseType === "html"
            ? "<div class='alert alert-danger'>Error: " + xhr.statusText + "</div>"
            : "Error: " + xhr.statusText;
        showData(responseObj, message);
    };

    setFormVal(form, "responsetype", responseType);
    if (action) {
        setFormVal(form, "action", action);
    }

    if (responseObj.length === 0) {
        $("#error").html("<br><div class='alert alert-danger'>No response object found.</div>").show();
        return;
    }
    $("#error").hide();

    showData(responseObj, buildLoadingHtml(loadingMessage));

    $.ajax({
        type: "POST",
        url: url,
        data: data,
        success: onSuccess,
        error: onError
    });
}

function arrayBufferToBase64(buffer) {
    const bytes = new Uint8Array(buffer);
    let binary = "";
    for (let i = 0; i < bytes.byteLength; i++) {
        binary += String.fromCharCode(bytes[i]);
    }
    return btoa(binary);
}

function derToPem(buffer, label) {
    const base64 = arrayBufferToBase64(buffer);
    const wrapped = base64.match(/.{1,64}/g)?.join("\n") || base64;
    return "-----BEGIN " + label + "-----\n" + wrapped + "\n-----END " + label + "-----\n";
}

function htmlEscape(text) {
    return $("<div>").text(text).html();
}

function capabilityAvailable(value) {
    return value !== undefined && value !== null;
}

function normalizeCapabilityValue(value) {
    if (value === undefined || value === null || value === "") {
        return "N/A";
    }
    if (typeof value === "boolean") {
        return value ? "Yes" : "No";
    }
    if (Array.isArray(value)) {
        return value.length ? value.join(", ") : "N/A";
    }
    if (typeof value === "object") {
        try {
            return JSON.stringify(value);
        } catch (error) {
            return "N/A";
        }
    }
    return String(value);
}

function mapToCollectorItems(entries) {
    return entries.map(function(entry) {
        return {
            key: entry.key,
            label: entry.label,
            value: normalizeCapabilityValue(entry.value),
            available: capabilityAvailable(entry.value),
            error: entry.error || null
        };
    });
}

function collectBaseBrowserInfo() {
    const nav = navigator || {};
    const tzName = (Intl && Intl.DateTimeFormat) ? Intl.DateTimeFormat().resolvedOptions().timeZone : null;
    const conn = nav.connection || nav.mozConnection || nav.webkitConnection || null;

    const userAgentDataBrands = (nav.userAgentData && Array.isArray(nav.userAgentData.brands))
        ? nav.userAgentData.brands.map(function(brand) {
            return brand.brand + " " + brand.version;
        })
        : null;

    return {
        generatedAt: new Date().toISOString(),
        sections: [
            {
                key: "browser",
                title: "Browser",
                items: mapToCollectorItems([
                    { key: "userAgent", label: "User Agent", value: nav.userAgent || null },
                    { key: "platform", label: "Platform", value: nav.platform || null },
                    { key: "vendor", label: "Vendor", value: nav.vendor || null },
                    { key: "product", label: "Product", value: nav.product || null },
                    { key: "cookieEnabled", label: "Cookies Enabled", value: nav.cookieEnabled },
                    { key: "doNotTrack", label: "Do Not Track", value: nav.doNotTrack || null },
                    { key: "webdriver", label: "Webdriver", value: nav.webdriver },
                    { key: "onLine", label: "Online", value: nav.onLine }
                ])
            },
            {
                key: "uaHints",
                title: "User-Agent Hints",
                items: mapToCollectorItems([
                    { key: "brands", label: "Brands", value: userAgentDataBrands },
                    { key: "mobile", label: "Mobile", value: nav.userAgentData ? nav.userAgentData.mobile : null },
                    { key: "platform", label: "UA Platform", value: nav.userAgentData ? nav.userAgentData.platform : null }
                ])
            },
            {
                key: "localeTime",
                title: "Locale & Time",
                items: mapToCollectorItems([
                    { key: "language", label: "Language", value: nav.language || null },
                    { key: "languages", label: "Languages", value: nav.languages || null },
                    { key: "timezone", label: "Timezone", value: tzName || null },
                    { key: "timezoneOffsetMinutes", label: "Timezone Offset (minutes)", value: new Date().getTimezoneOffset() },
                    { key: "currentLocalTime", label: "Current Local Time", value: new Date().toString() }
                ])
            },
            {
                key: "screen",
                title: "Screen & Viewport",
                items: mapToCollectorItems([
                    { key: "screenWidth", label: "Screen Width", value: window.screen ? window.screen.width : null },
                    { key: "screenHeight", label: "Screen Height", value: window.screen ? window.screen.height : null },
                    { key: "availWidth", label: "Available Width", value: window.screen ? window.screen.availWidth : null },
                    { key: "availHeight", label: "Available Height", value: window.screen ? window.screen.availHeight : null },
                    { key: "colorDepth", label: "Color Depth", value: window.screen ? window.screen.colorDepth : null },
                    { key: "pixelDepth", label: "Pixel Depth", value: window.screen ? window.screen.pixelDepth : null },
                    { key: "innerWidth", label: "Viewport Width", value: window.innerWidth || null },
                    { key: "innerHeight", label: "Viewport Height", value: window.innerHeight || null },
                    { key: "devicePixelRatio", label: "Device Pixel Ratio", value: window.devicePixelRatio || null }
                ])
            },
            {
                key: "hardware",
                title: "Hardware",
                items: mapToCollectorItems([
                    { key: "hardwareConcurrency", label: "CPU Threads", value: nav.hardwareConcurrency || null },
                    { key: "deviceMemory", label: "Device Memory (GB, hint)", value: nav.deviceMemory || null },
                    { key: "maxTouchPoints", label: "Max Touch Points", value: nav.maxTouchPoints || null }
                ])
            },
            {
                key: "network",
                title: "Network Hints",
                items: mapToCollectorItems([
                    { key: "effectiveType", label: "Effective Type", value: conn ? conn.effectiveType : null },
                    { key: "downlink", label: "Downlink (Mbps)", value: conn ? conn.downlink : null },
                    { key: "rtt", label: "RTT (ms)", value: conn ? conn.rtt : null },
                    { key: "saveData", label: "Save-Data", value: conn ? conn.saveData : null }
                ])
            },
            {
                key: "capabilities",
                title: "Capabilities",
                items: mapToCollectorItems([
                    { key: "clipboard", label: "Clipboard API", value: !!(nav.clipboard && nav.clipboard.writeText) },
                    { key: "serviceWorker", label: "Service Worker", value: !!nav.serviceWorker },
                    { key: "geolocation", label: "Geolocation API", value: !!nav.geolocation },
                    { key: "notifications", label: "Notifications API", value: ("Notification" in window) },
                    { key: "bluetooth", label: "Web Bluetooth", value: !!nav.bluetooth },
                    { key: "usb", label: "WebUSB", value: !!nav.usb },
                    { key: "nfc", label: "WebNFC", value: !!nav.nfc },
                    { key: "webgpu", label: "WebGPU", value: !!nav.gpu },
                    { key: "webAssembly", label: "WebAssembly", value: ("WebAssembly" in window) },
                    { key: "sharedWorker", label: "SharedWorker", value: ("SharedWorker" in window) },
                    { key: "indexedDb", label: "IndexedDB", value: ("indexedDB" in window) },
                    { key: "localStorage", label: "localStorage", value: ("localStorage" in window) },
                    { key: "sessionStorage", label: "sessionStorage", value: ("sessionStorage" in window) },
                    { key: "crypto", label: "WebCrypto", value: !!(window.crypto && window.crypto.subtle) }
                ])
            }
        ]
    };
}

function collectWebGlInfo() {
    const section = { key: "webgl", title: "WebGL", items: [] };
    const canvas = document.createElement("canvas");
    const gl = canvas.getContext("webgl") || canvas.getContext("experimental-webgl");
    if (!gl) {
        section.items = mapToCollectorItems([
            { key: "supported", label: "WebGL Supported", value: false }
        ]);
        return section;
    }

    let renderer = null;
    let vendor = null;
    try {
        const ext = gl.getExtension("WEBGL_debug_renderer_info");
        if (ext) {
            renderer = gl.getParameter(ext.UNMASKED_RENDERER_WEBGL);
            vendor = gl.getParameter(ext.UNMASKED_VENDOR_WEBGL);
        }
    } catch (error) {
        renderer = null;
        vendor = null;
    }

    section.items = mapToCollectorItems([
        { key: "supported", label: "WebGL Supported", value: true },
        { key: "version", label: "WebGL Version", value: gl.getParameter(gl.VERSION) || null },
        { key: "shadingLanguageVersion", label: "Shading Language Version", value: gl.getParameter(gl.SHADING_LANGUAGE_VERSION) || null },
        { key: "renderer", label: "Renderer", value: renderer },
        { key: "vendor", label: "Vendor", value: vendor }
    ]);
    return section;
}

async function collectPermissionsSection() {
    const section = { key: "permissions", title: "Permissions", items: [] };
    if (!navigator.permissions || !navigator.permissions.query) {
        section.items = mapToCollectorItems([
            { key: "supported", label: "Permissions API", value: false }
        ]);
        return section;
    }

    const permissionNames = [
        "geolocation",
        "notifications",
        "microphone",
        "camera",
        "clipboard-read",
        "clipboard-write"
    ];
    const items = [{ key: "supported", label: "Permissions API", value: true }];

    for (const name of permissionNames) {
        try {
            const status = await navigator.permissions.query({ name: name });
            items.push({
                key: name,
                label: name,
                value: status && status.state ? status.state : null
            });
        } catch (error) {
            items.push({
                key: name,
                label: name,
                value: null,
                error: "Unsupported or blocked"
            });
        }
    }

    section.items = mapToCollectorItems(items);
    return section;
}

async function collectWebRtcSection() {
    const section = { key: "webrtc", title: "WebRTC Candidates", items: [] };
    if (typeof RTCPeerConnection === "undefined") {
        section.items = mapToCollectorItems([
            { key: "supported", label: "RTCPeerConnection", value: false }
        ]);
        return section;
    }

    const localCandidates = [];
    let candidateError = null;

    try {
        const pc = new RTCPeerConnection({ iceServers: [] });
        pc.createDataChannel("probe");
        pc.onicecandidate = function(event) {
            if (!event || !event.candidate || !event.candidate.candidate) {
                return;
            }
            const candidate = event.candidate.candidate;
            const parts = candidate.split(" ");
            if (parts.length >= 8) {
                localCandidates.push({
                    protocol: parts[2] || null,
                    address: parts[4] || null,
                    port: parts[5] || null,
                    type: parts[7] || null
                });
            }
        };

        const offer = await pc.createOffer();
        await pc.setLocalDescription(offer);
        await new Promise(function(resolve) { setTimeout(resolve, 1200); });
        pc.close();
    } catch (error) {
        candidateError = error && error.message ? error.message : "Failed to collect WebRTC candidates.";
    }

    section.items = mapToCollectorItems([
        { key: "supported", label: "RTCPeerConnection", value: true },
        { key: "candidateCount", label: "Candidate Count", value: localCandidates.length },
        { key: "candidates", label: "Candidates", value: localCandidates.length ? localCandidates : null },
        { key: "error", label: "Probe Error", value: candidateError }
    ]);
    return section;
}

async function collectPublicIpSection() {
    const section = { key: "publicIp", title: "Public IP (External)", items: [] };
    try {
        const response = await fetch("https://api.ipify.org?format=json", { method: "GET" });
        const data = await response.json();
        section.items = mapToCollectorItems([
            { key: "ip", label: "Public IP", value: data && data.ip ? data.ip : null }
        ]);
    } catch (error) {
        section.items = mapToCollectorItems([
            { key: "ip", label: "Public IP", value: null, error: "Lookup failed" }
        ]);
    }
    return section;
}

async function collectBrowserInfo(options) {
    const opts = options || {};
    const info = collectBaseBrowserInfo();
    info.sections.push(collectWebGlInfo());

    if (opts.includePermissions) {
        info.sections.push(await collectPermissionsSection());
    }
    if (opts.includeWebRtc) {
        info.sections.push(await collectWebRtcSection());
    }
    if (opts.includePublicIp) {
        info.sections.push(await collectPublicIpSection());
    }

    return info;
}

function getClientCryptoEnvironment() {
    const subtle = window.crypto && window.crypto.subtle;
    return {
        hasSubtle: !!subtle,
        secure: !!window.isSecureContext
    };
}

/** True when browser client-side key generation (auto/client) can run. */
function clientCryptoClientGenerationAvailable() {
    const env = getClientCryptoEnvironment();
    return env.secure && env.hasSubtle;
}

/**
 * Single HTTPS / WebCrypto warning for key generators and crypto diagnostics.
 * @param {string} marginClass - Bootstrap margin on the alert (e.g. mb-2, mb-3).
 * @returns {string} Empty when client generation is available; otherwise one alert with icon.
 */
function buildClientCryptoWarningBannerHtml(marginClass) {
    if (clientCryptoClientGenerationAvailable()) {
        return "";
    }
    marginClass = marginClass || "mb-3";
    const env = getClientCryptoEnvironment();
    let detail = "";
    if (!env.secure && !env.hasSubtle) {
        detail = "This page is not a secure context (use HTTPS or <code>localhost</code>) and <code>window.crypto.subtle</code> is unavailable.";
    } else if (!env.secure) {
        detail = "This page is not a secure context. Browsers restrict WebCrypto on plain HTTP; use HTTPS or <code>localhost</code>.";
    } else {
        detail = "<code>window.crypto.subtle</code> is not available in this browser.";
    }
    return "<div class=\"alert alert-warning " + marginClass + " d-flex align-items-start gap-2\" role=\"alert\">" +
        "<i class=\"bi bi-exclamation-triangle-fill flex-shrink-0 mt-1\" aria-hidden=\"true\"></i>" +
        "<div><strong>Client-side generation unavailable.</strong> " + detail +
        " <strong>Generation mode is set to server-side only.</strong> Auto and client-only are disabled until this environment supports WebCrypto.</div>" +
        "</div>";
}

/** Force server mode and disable auto/client when WebCrypto cannot be used. */
function applyKeygenGenerationModeConstraints($scope) {
    const $forms = ($scope && $scope.length)
        ? $scope.find("form[data-action='keypair_generate'], form[data-action='ssh_keygen']")
        : $("form[data-action='keypair_generate'], form[data-action='ssh_keygen']");
    const canUseClient = clientCryptoClientGenerationAvailable();
    $forms.each(function() {
        const $form = $(this);
        const $sel = $form.find("select[name='generation_mode']");
        if (!$sel.length) {
            return;
        }
        const $auto = $sel.find("option[value='auto']");
        const $client = $sel.find("option[value='client']");
        if (canUseClient) {
            $auto.prop("disabled", false);
            $client.prop("disabled", false);
        } else {
            $auto.prop("disabled", true);
            $client.prop("disabled", true);
            $sel.val("server");
        }
        updatePassphraseStateForForm($form);
    });
}

function refreshClientCryptoGeneratorUi($scope) {
    const $targets = ($scope && $scope.length)
        ? $scope.find(".client-crypto-generator-banner")
        : $(".client-crypto-generator-banner");
    const html = buildClientCryptoWarningBannerHtml("mb-2");
    $targets.each(function() {
        $(this).html(html);
    });
    applyKeygenGenerationModeConstraints($scope);
}

function buildClientCryptoDiagnosticsHtml() {
    const env = getClientCryptoEnvironment();

    const items = [
        { label: "WebCrypto available", value: env.hasSubtle ? "Yes" : "No" },
        { label: "Secure context (HTTPS / localhost)", value: env.secure ? "Yes" : "No" },
        { label: "User agent", value: (navigator && navigator.userAgent) || "N/A" }
    ];

    let html = "<div class='card border-info mt-3 mb-3'><h5 class='card-header'>Client-side Crypto Diagnostics (Browser)</h5><div class='card-body'>";
    html += "<p class='mb-3 text-muted'>Basic browser-side crypto/runtime signals. Detailed browser diagnostics are available in the Browser Inspector tool.</p>";

    html += buildClientCryptoWarningBannerHtml("mb-3");

    html += "<div class='table-responsive'><table class='table table-dark table-striped mb-0'><thead><tr><th>Field</th><th>Value</th></tr></thead><tbody>";
    items.forEach(function(item) {
        html += "<tr><td style='width: 30%;'><code>" + htmlEscape(item.label) + "</code></td><td>" + htmlEscape(item.value) + "</td></tr>";
    });
    html += "</tbody></table></div>";

    if (env.secure && env.hasSubtle) {
        html += "<div class='alert alert-success mb-0' role='alert'><strong>Client-side crypto OK.</strong> HTTPS (or localhost) and WebCrypto are available; browser key generation can run when you choose client or auto mode.</div>";
    }

    html += "</div></div>";
    return html;
}

function buildBrowserInspectorStatusBadgeHtml(item) {
    if (item.error) {
        const full = String(item.error);
        const short = full.length > 56 ? full.slice(0, 53) + "…" : full;
        return "<span class='badge bg-danger text-white' title=\"" + htmlEscape(full) + "\">" + htmlEscape(short) + "</span>";
    }
    if (!item.available) {
        return "<span class='badge bg-secondary text-white'>N/A</span>";
    }
    const raw = item.value === undefined || item.value === null ? "" : String(item.value).trim();
    const lower = raw.toLowerCase();
    if (lower === "granted") {
        return "<span class='badge bg-success text-white'>Granted</span>";
    }
    if (lower === "denied") {
        return "<span class='badge bg-danger text-white'>Denied</span>";
    }
    if (lower === "prompt") {
        return "<span class='badge bg-warning text-dark'>Prompt</span>";
    }
    if (raw === "Yes") {
        return "<span class='badge bg-success text-white'>Yes</span>";
    }
    if (raw === "No") {
        return "<span class='badge bg-secondary text-white'>No</span>";
    }
    return "<span class='badge bg-success text-white'>OK</span>";
}

function renderBrowserSections(info) {
    const sections = Array.isArray(info.sections) ? info.sections : [];
    let html = "<div class='mb-3'><strong>Generated:</strong> " + htmlEscape(info.generatedAt || "") + "</div>";

    sections.forEach(function(section) {
        html += "<div class='card border-info mb-3'>";
        html += "<h5 class='card-header'>" + htmlEscape(section.title || section.key || "Section") + "</h5>";
        html += "<div class='card-body p-0'>";
        html += "<div class='table-responsive'><table class='table table-dark table-striped mb-0'><thead><tr><th>Field</th><th>Value</th><th>Status</th></tr></thead><tbody>";
        (section.items || []).forEach(function(item) {
            html += "<tr>";
            html += "<td style='width: 30%;'><code>" + htmlEscape(item.label || item.key || "") + "</code></td>";
            html += "<td style='white-space: pre-wrap; word-break: break-word;'>" + htmlEscape(item.value) + "</td>";
            html += "<td style='width: 18%; white-space: nowrap;'>" + buildBrowserInspectorStatusBadgeHtml(item) + "</td>";
            html += "</tr>";
        });
        html += "</tbody></table></div></div></div>";
    });

    return html;
}

function renderBrowserInfoHtml(info) {
    const rawJson = JSON.stringify(info, null, 2);
    let html = renderBrowserSections(info);
    html += "<div class='card border-secondary mt-3'>";
    html += "<h5 class='card-header d-flex justify-content-between align-items-center'>";
    html += "<span>Raw JSON</span>";
    html += "<button type='button' class='btn btn-sm btn-outline-light' onclick='copyToClipboard(\"browserInfoJson\", this)'><i class='bi bi-clipboard'></i> Copy JSON</button>";
    html += "</h5>";
    html += "<div class='card-body'>";
    html += "<pre id='browserInfoJson' style='white-space: pre-wrap; word-break: break-word; margin-bottom: 0;'>" + htmlEscape(rawJson) + "</pre>";
    html += "</div></div>";
    return html;
}

async function maybeHandleBrowserInspector(form, responseObj) {
    const action = (form.data("action") || "").toLowerCase();
    if (action !== "browser_inspect") {
        return false;
    }

    showData(responseObj, buildLoadingHtml("Detecting browser details..."));

    try {
        const info = await collectBrowserInfo({
            includePermissions: form.find("[name='include_permissions']").is(":checked"),
            includeWebRtc: form.find("[name='include_webrtc']").is(":checked"),
            includePublicIp: form.find("[name='include_public_ip']").is(":checked")
        });
        showData(responseObj, renderBrowserInfoHtml(info));
    } catch (error) {
        const message = error && error.message ? error.message : "Failed to inspect browser details.";
        showData(responseObj, "<div class='alert alert-danger'>" + htmlEscape(message) + "</div>");
    }

    return true;
}

function buildClientKeyOutput(items, title) {
    let html = "<div class='card border-info mb-3'><h5 class='card-header'>" + htmlEscape(title) + "</h5><div class='card-body'>";
    items.forEach(function(item) {
        const encoded = "data:text/plain;charset=utf-8," + encodeURIComponent(item.content);
        html += "<div style='margin-bottom:14px;'><strong>" + htmlEscape(item.label) + "</strong></div>";
        html += "<textarea class='form-control' style='min-height:120px; font-family:monospace; margin-bottom:8px;' readonly>" + htmlEscape(item.content) + "</textarea>";
        html += "<a class='btn btn-outline-light btn-sm mb-3' download='" + htmlEscape(item.filename) + "' href='" + encoded + "'><i class='bi bi-download'></i> Download " + htmlEscape(item.label) + "</a>";
    });
    html += "</div></div>";
    return html;
}

async function clientGeneratePemPair(algorithm, rsaBits, ecdsaCurve) {
    const subtle = window.crypto && window.crypto.subtle;
    if (!subtle) throw new Error("WebCrypto is unavailable in this browser.");

    let algo;
    if (algorithm === "rsa") {
        algo = {
            name: "RSA-PSS",
            modulusLength: rsaBits,
            publicExponent: new Uint8Array([1, 0, 1]),
            hash: "SHA-256"
        };
    } else if (algorithm === "ecdsa") {
        const namedCurve = ecdsaCurve === "secp384r1" ? "P-384" : (ecdsaCurve === "secp521r1" ? "P-521" : "P-256");
        algo = { name: "ECDSA", namedCurve: namedCurve };
    } else if (algorithm === "ed25519") {
        algo = { name: "Ed25519" };
    } else {
        throw new Error("Unsupported client algorithm: " + algorithm);
    }

    const keyPair = await subtle.generateKey(algo, true, ["sign", "verify"]);
    const privatePkcs8 = await subtle.exportKey("pkcs8", keyPair.privateKey);
    const publicSpki = await subtle.exportKey("spki", keyPair.publicKey);

    return {
        privatePem: derToPem(privatePkcs8, "PRIVATE KEY"),
        publicPem: derToPem(publicSpki, "PUBLIC KEY")
    };
}

async function maybeHandleClientSideKeygen(form, responseObj) {
    const action = form.data("action") || "";
    if (action !== "keypair_generate" && action !== "ssh_keygen") {
        return false;
    }

    const mode = (form.find("[name='generation_mode']").val() || "server").toLowerCase();
    if (mode === "server") {
        return false;
    }

    const passphrase = (form.find("[name='passphrase']").val() || "").trim();
    const isSsh = action === "ssh_keygen";
    if (passphrase !== "") {
        if (mode === "auto") return false;
        showData(responseObj, "<div class='alert alert-warning'>Client-side mode does not support private-key passphrase encryption yet. Use server mode.</div>");
        return true;
    }

    if (isSsh && mode === "auto") {
        // Prefer server for SSH in auto mode so OpenSSH output is always included.
        return false;
    }

    const subtle = window.crypto && window.crypto.subtle;
    if (!subtle) {
        if (mode === "auto") return false;
        showData(responseObj, "<div class='alert alert-danger'>WebCrypto is unavailable in this browser. Use server mode.</div>");
        return true;
    }

    const selected = form.find("[name='algorithm']").val() || "ed25519";
    const rsaBits = parseInt(form.find("[name='rsa_bits']").val() || "4096", 10);
    const ecdsaCurve = form.find("[name='ecdsa_curve']").val() || "prime256v1";
    const list = selected === "all-available" ? ["rsa", "ecdsa", "ed25519"] : [selected];

    let output = "";
    let generatedCount = 0;
    for (const algorithm of list) {
        try {
            const pair = await clientGeneratePemPair(algorithm, rsaBits, ecdsaCurve);
            const suffix = algorithm === "rsa" ? ("-" + rsaBits) : (algorithm === "ecdsa" ? ("-" + ecdsaCurve) : "");
            const items = [
                { label: algorithm.toUpperCase() + " Private Key (PEM)", content: pair.privatePem, filename: "private-" + algorithm + suffix + ".pem" },
                { label: algorithm.toUpperCase() + " Public Key (PEM)", content: pair.publicPem, filename: "public-" + algorithm + suffix + ".pem" }
            ];
            if (isSsh) {
                output += "<div class='alert alert-warning'>OpenSSH public-key line generation is server-backed for best compatibility. Switch to server mode for full SSH output.</div>";
            }
            output += buildClientKeyOutput(items, algorithm.toUpperCase() + " Client-side Keypair");
            generatedCount++;
        } catch (err) {
            if (mode === "auto") {
                continue;
            }
            output += "<div class='alert alert-warning'>" + htmlEscape((algorithm.toUpperCase() + ": " + (err && err.message ? err.message : "Client generation failed."))) + "</div>";
        }
    }

    if (generatedCount === 0) {
        if (mode === "auto") return false;
        showData(responseObj, output || "<div class='alert alert-danger'>No keys were generated client-side.</div>");
        return true;
    }

    output = "<div class='alert alert-info'>Generated via browser WebCrypto (client-side).</div>" + output;
    showData(responseObj, output);
    return true;
}

function updatePassphraseStateForForm($form) {
    const mode = ($form.find("[name='generation_mode']").val() || "").toLowerCase();
    const $pass = $form.find("input[name='passphrase']");
    if (!$pass.length) return;

    const isClientOnly = mode === "client";
    if (isClientOnly) {
        $pass.val("");
        $pass.prop("disabled", true);
        $pass.attr("placeholder", "Disabled in client-side mode; use server/auto for passphrase protection");
    } else {
        $pass.prop("disabled", false);
        if ($pass.attr("data-original-placeholder")) {
            $pass.attr("placeholder", $pass.attr("data-original-placeholder"));
        }
    }
}

/* ===================================================================== */
/*                         FUNCTION: setFormVal                          */
/* ===================================================================== */
function setFormVal(form, name = "action", value = "") {
    console.log("[setFormVal] Setting form value " + name + " to: " + value);
    $(form).find(".setFormVal[name='" + name + "']").remove();
    var hiddenInput = $("<input>")
        .attr("class", "setFormVal")
        .attr("type", "hidden")
        .attr("name", name).val(value);
    $(form).append(hiddenInput);
}

const ACTIVE_MODULE_STORAGE_KEY = "rand.activeModule";

function normalizeModuleHash(value) {
    const moduleName = (value || "").replace(/^#/, "").trim();
    return moduleName ? ("#" + moduleName) : "";
}

function persistActiveModule(hashValue) {
    const normalizedHash = normalizeModuleHash(hashValue);
    if (!normalizedHash) {
        return;
    }
    try {
        window.localStorage.setItem(ACTIVE_MODULE_STORAGE_KEY, normalizedHash);
    } catch (error) {
        // localStorage can be unavailable in privacy-restricted contexts.
    }
}

function getPreferredInitialModule() {
    const hashModule = normalizeModuleHash(window.location.hash);
    if (hashModule) {
        return hashModule;
    }
    try {
        const storedModule = normalizeModuleHash(window.localStorage.getItem(ACTIVE_MODULE_STORAGE_KEY));
        if (storedModule) {
            return storedModule;
        }
    } catch (error) {
        // Ignore storage read errors and fallback to dashboard.
    }
    return "#dashboard";
}

/* ===================================================================== */
/*                           FUNCTION: navigate                          */
/* ===================================================================== */
function navigate(to) {
    console.log("[navigate] Navigating to: " + to);

    const moduleName = (to || "").replace(/^#/, "");
    if (!moduleName) {
        return;
    }
    const normalizedTo = "#" + moduleName;

    persistActiveModule(normalizedTo);
    if (window.location.hash !== normalizedTo) {
        history.replaceState(null, "", normalizedTo);
    }

    // Reset all nav links
    var navLinks = $(".link.nav-link");
    navLinks.prop("class", "link nav-link");

    // Set this nav link as active
    var navLink = $(`.link.nav-link[href='${normalizedTo}']`);
    navLink.prop("class", "link nav-link link-success active");

    const showTarget = function() {
        $(".content").hide();
        $(normalizedTo).fadeIn();
        addRandomDataButtons($(normalizedTo));
        refreshClientCryptoGeneratorUi($(normalizedTo));
    };

    if ($(normalizedTo).length) {
        showTarget();
        return;
    }

    loadModule(moduleName)
        .done(function() {
            showTarget();
        })
        .fail(function(xhr) {
            const message = xhr && xhr.responseText
                ? xhr.responseText
                : "<div class='alert alert-danger'>Failed to load module: " + moduleName + ".</div>";
            $("#error").html("<br>" + message).show();
        });
}

/* ===================================================================== */
/*                         FUNCTION: loadModule                           */
/* ===================================================================== */
function loadModule(moduleName) {
    const selector = "#" + moduleName;
    if ($(selector).length) {
        return ensureEnhancersForScope($(selector));
    }

    const $container = $(".container.pt-5").first();
    const $placeholder = $("<div>", {
        id: moduleName + "_loading",
        class: "content",
        html: buildLoadingHtml("Loading " + moduleName.replace(/_/g, " ") + "...")
    });
    $container.append($placeholder);

    const beforeLoad = moduleName === "markdown"
        ? ensureMarkdownAssets()
        : $.Deferred().resolve().promise();

    return beforeLoad.then(function() {
        return $.ajax({
            type: "GET",
            url: "load_module.php",
            data: { module: moduleName },
            cache: true
        }).done(function(html) {
            $placeholder.replaceWith(html);
            addRandomDataButtons($(selector));
            ensureEnhancersForScope($(selector));
        }).fail(function() {
            $placeholder.remove();
        });
    }).fail(function() {
        $placeholder.remove();
    });
}

/* ===================================================================== */
/*                        FUNCTION: axiosNavigate                        */
/* ===================================================================== */
function axiosNavigate(to, responseSelector = ".responseDiv") {
    console.log("[axiosNavigate] Navigating to: " + to);

    function runAxios() {
        if (typeof axios === "undefined") {
            setTimeout(runAxios, 100);
            return;
        }
        axios({
            method: 'get',
            url: to,
            responseType: 'stream'
        })
        .then(function (response) {
            $(responseSelector).html(response.data);
        })
        .catch(function (error) {
            $(responseSelector).html("<div class='alert alert-danger'>Error: " + error + "</div>");
        });
    }

    runAxios();
}


/* ===================================================================== */
/*                        FUNCTION: randomizeDice                        */
/* ===================================================================== */
function randomizeDice() {
    var dice = [1, 2, 3, 4, 5, 6];
    var diceIcon = dice[Math.floor(Math.random() * dice.length)];
    $(".dice").html('<i class="bi bi-dice-' + diceIcon + '"></i>');
}


/* ===================================================================== */
/*                        FUNCTION: document.ready                       */
/* ===================================================================== */
$(document).ready(function() {

    var tz = getTimeZone();
    $(".timezone").text(tz);

    console.log("[document.ready] Current timezone: " + tz);

    updateTime(tz);
    setInterval(function() {
        updateTime(tz);
    }, 1000);

    randomizeDice();

    /* ===================================================================== */
    /*                            Timezone update                            */
    /* ===================================================================== */
    $(".timezone").text(getTimeZone());
    $(".timezone-select").change(function() {
        tz = $(this).val();
        setTimeZone(tz);
        updateTime(tz);
    });

    /* ===================================================================== */
    /*                               Code Input                              */
    /* ===================================================================== */
    ensureEnhancersForScope($(".content:visible").first());
    $(".code").on("paste", function() {
        this.style.height = "auto";
    });

    /* =====================================================================───── */
    /*                               Copy to clipboard                            */
    /* =====================================================================───── */
    $(".copyText").click(function() {
        var copyText = $(this).closest(".responseDiv");
        copyText.select();
        // copyText[0].setSelectionRange(0, 99999); /* For mobile devices */
        document.execCommand("copy");
        $(this).html("Copied!");
        $(this).addClass("btn-success");
    });

    /* ===================================================================== */
    /*                              Form submit                              */
    /* ===================================================================== */
    //function submitForm(formname, responseid) {
    $(document).on("submit", ".form", async function(e) {
        e.preventDefault(); // avoid to execute the actual submit of the form.

        var form = $(this);

        // Set clicken `.genBtn` as form value
        var clickedGenBtn = $('.genBtn:focus');

        // Set response type
        if (form.data("responsetype")) {
            var responsetype = form.data("responsetype");
        } else {
            var responsetype = "html";
        }
        setFormVal(form, "responsetype", responsetype);

        // Set form action
        if (form.data("action")) {
            var action = form.data("action");
        }
        if (form.find("input[name=action]").length) {
            var action = form.find("input[name=action]").val();
        }
        setFormVal(form, "action", action)

        // Set clicked button as form value
        if (clickedGenBtn.length) {
            var name = clickedGenBtn.length ? clickedGenBtn.attr("name") : "";
            var value = clickedGenBtn.length ? clickedGenBtn.attr("value") : "";
            if (name != "action" && name != "") {
                setFormVal(form, name, value);
            }
        }

        // NOTE: This is not needed because we are checking for the response object type
        //       in the showData function.
        // Determine response type (text or HTML)
        // var responseType = form.data("responseType");
        // if (responseType == undefined) {
        //   responseType = "html";
        // }

        var btnName = $("button[clicked=true]").prop("name");
        var btnValue = $("button[clicked=true]").val();
        var serializeForm = form.serialize();
        if (btnName && btnValue !== undefined) {
            serializeForm += "&" + btnName + "=" + btnValue;
        }
        console.log("[submitForm] Sending form: " + serializeForm);
        const handledBrowserInspector = await maybeHandleBrowserInspector(form, form.find(".responseDiv"));
        if (handledBrowserInspector) {
            randomizeDice();
            return;
        }
        const handledClientSide = await maybeHandleClientSideKeygen(form, form.find(".responseDiv"));
        if (handledClientSide) {
            randomizeDice();
            return;
        }

        const responseDiv = form.find(".responseDiv");
        const submitOpts = {
            data: serializeForm,
            responseType: responsetype,
            loadingMessage: "Generating..."
        };
        if ((form.data("action") || "") === "crypto_diagnostics") {
            submitOpts.onSuccess = function(dataOut) {
                showData(responseDiv, dataOut);
                // After server HTML is in the response div, fill #clientCryptoDiagnosticsRoot (ajaxSuccess ran too early).
                setTimeout(function() {
                    const $root = $("#clientCryptoDiagnosticsRoot");
                    if ($root.length) {
                        $root.html(buildClientCryptoDiagnosticsHtml());
                    }
                }, 0);
            };
        }

        submitToolForm(form, submitOpts);
        randomizeDice();
    });

    /* ===================================================================== */
    /*                    Use as input (two-way converters)                  */
    /* ===================================================================== */
    window._useAsInputUndo = window._useAsInputUndo || {};
    $(document).on('click', '.btn-use-as-input', function() {
        var $btn = $(this);
        var $form = $btn.closest('form');
        if (!$form.length) return;
        var copyableId = $btn.data('copyable-id');
        var inputName = $btn.data('input-name');
        if (!copyableId || !inputName) return;
        var contentEl = document.getElementById(copyableId);
        if (!contentEl) return;
        var text = (contentEl.textContent || contentEl.innerText || '').replace(/\r\n/g, '\n');
        var $input = $form.find('[name="' + inputName + '"]');
        if (!$input.length) return;

        var stored = window._useAsInputUndo[copyableId];
        if (stored) {
            /* Undo: restore previous input and direction */
            $input.val(stored.input);
            var swapNames = $btn.data('swap-names');
            if (swapNames && Array.isArray(swapNames) && swapNames.length === 2) {
                var $a = $form.find('[name="' + swapNames[0] + '"]');
                var $b = $form.find('[name="' + swapNames[1] + '"]');
                if ($a.length && $b.length) {
                    var tmp = $a.val();
                    $a.val($b.val());
                    $b.val(tmp);
                }
            }
            var setSelectUndo = $btn.data('set-select-value-undo');
            if (setSelectUndo !== undefined && setSelectUndo !== '') {
                var setSelectName = $btn.data('set-select-name');
                if (setSelectName) {
                    var $sel = $form.find('[name="' + setSelectName + '"]');
                    if ($sel.length) $sel.val(setSelectUndo);
                }
            }
            delete window._useAsInputUndo[copyableId];
            return;
        }

        /* Use as input: save current state, then replace */
        var currentInput = $input.val();
        if (currentInput === text) return; /* already showing output, nothing to do */
        window._useAsInputUndo[copyableId] = { input: currentInput };

        $input.val(text);
        var swapNames = $btn.data('swap-names');
        if (swapNames && Array.isArray(swapNames) && swapNames.length === 2) {
            var $a = $form.find('[name="' + swapNames[0] + '"]');
            var $b = $form.find('[name="' + swapNames[1] + '"]');
            if ($a.length && $b.length) {
                var tmp = $a.val();
                $a.val($b.val());
                $b.val(tmp);
            }
        }
        var setSelectName = $btn.data('set-select-name');
        var setSelectValue = $btn.data('set-select-value');
        if (setSelectName && setSelectValue !== undefined) {
            var $sel = $form.find('[name="' + setSelectName + '"]');
            if ($sel.length) $sel.val(setSelectValue);
        }
    });

    /* ===================================================================== */
    /*                      Navigation (and hash check)                      */
    /* ===================================================================== */
    var initialModule = getPreferredInitialModule();
    var initialModuleName = initialModule.replace('#', '');
    navigate(initialModule);
    console.log("Initial module: " + initialModule + ", setting nav " + initialModuleName + " as the active tab.");

    /* ===================================================================== */
    /*                               Click link                              */
    /* ===================================================================== */
    $(".link").click(function(e) {
        e.preventDefault();
        var elementToShow = $(this).attr("href");

        navigate(elementToShow);

        if (elementToShow == undefined) {
            console.log("unable to show " + elementToShow);
            $("#error").html("<br><div class='alert alert-danger'>Failed to show page (" +
                elementToShow + ").</div>");
            $("#error").show();
        } else {
            $("#error").hide();
            console.log("Showing " + elementToShow);
        }
    });

    // Keep passphrase inputs in sync with generation mode for keypair/SSH forms
    $(document).on("change", "[name='generation_mode']", function() {
        const $form = $(this).closest("form");
        updatePassphraseStateForForm($form);
    });

    // Initialize state on any already-rendered forms
    $("form[data-action='keypair_generate'], form[data-action='ssh_keygen']").each(function() {
        updatePassphraseStateForForm($(this));
    });

    // turn off all autocomplete
    $(".form-control").prop("autocomplete", "off");
    $("input[type=checkbox]").addClass("form-check-input");


    /* ===================================================================== */
    /*                            Changelog modal                            */
    /* ===================================================================== */
    var changelog = $("#changelogMarkdown");
    var changelogLoaded = false;
    $("#changelogModal").on("show.bs.modal", function() {
        if (changelogLoaded) {
            return;
        }
        $.ajax({
            type: "GET",
            url: "CHANGELOG.md",
            cache: true
        }).done(function(markdownText) {
            ensureMarkdownAssets().done(function() {
                marked.setOptions({
                    breaks: true,
                    gfm: true
                });
                changelog.html(marked.parse(markdownText));
                changelogLoaded = true;
            }).fail(function() {
                changelog.html("<pre>" + $("<div>").text(markdownText).html() + "</pre>");
                changelogLoaded = true;
            });
        }).fail(function() {
            changelog.html("<div class='alert alert-danger'>Failed to load changelog.</div>");
        });
    });

    /* ===================================================================== */
    /*                      Add Random Data Buttons                          */
    /* ===================================================================== */
    addRandomDataButtons($(".content:visible").first());

}); // document.ready

/* ===================================================================== */
/*                    FUNCTION: generateRandomData                       */
/* ===================================================================== */
/**
 * Generate contextually appropriate random data for input fields
 * 
 * Detects the form/module context and generates relevant sample data:
 * - Calculator: Math expressions (e.g., "25+8*3")
 * - Networking: IP addresses, CIDR notations, domain names
 * - Hashing/Encoding: Text, JSON, code snippets
 * - String tools: Lorem ipsum text, emails
 * And many more context-specific generators
 * 
 * @param {string} type - Input type ('text', 'textarea', 'number', etc.)
 * @param {string} placeholder - Input placeholder text for context detection
 * @param {jQuery|string} $input - The input element or container form for additional context
 * @returns {string} Randomly generated data appropriate for the context
 */
function generateRandomData(type, placeholder = '', $input = null) {
    const randomStr = (len) => {
        const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        let result = '';
        for (let i = 0; i < len; i++) {
            result += chars.charAt(Math.floor(Math.random() * chars.length));
        }
        return result;
    };

    const randomInt = (min, max) => Math.floor(Math.random() * (max - min + 1)) + min;

    const randomText = (sentences = 3) => {
        const words = ['lorem', 'ipsum', 'dolor', 'sit', 'amet', 'consectetur', 'adipiscing', 'elit', 
                      'sed', 'do', 'eiusmod', 'tempor', 'incididunt', 'ut', 'labore', 'et', 'dolore',
                      'magna', 'aliqua', 'enim', 'ad', 'minim', 'veniam', 'quis', 'nostrud', 'exercitation'];
        let result = [];
        for (let i = 0; i < sentences; i++) {
            let sentence = [];
            const wordCount = randomInt(8, 15);
            for (let j = 0; j < wordCount; j++) {
                sentence.push(words[randomInt(0, words.length - 1)]);
            }
            result.push(sentence.join(' ').charAt(0).toUpperCase() + sentence.join(' ').slice(1) + '.');
        }
        return result.join(' ');
    };

    const randomCode = () => {
        const codeSnippets = [
            'function hello() {\n  console.log("Hello, World!");\n  return true;\n}',
            'const data = {\n  id: ' + randomInt(1, 1000) + ',\n  name: "' + randomStr(8) + '",\n  active: true\n};',
            'for (let i = 0; i < 10; i++) {\n  console.log(i);\n}',
            'if (condition) {\n  doSomething();\n} else {\n  doSomethingElse();\n}'
        ];
        return codeSnippets[randomInt(0, codeSnippets.length - 1)];
    };

    const randomEmail = () => {
        const domains = ['example.com', 'test.com', 'sample.org', 'demo.net'];
        return randomStr(8).toLowerCase() + '@' + domains[randomInt(0, domains.length - 1)];
    };

    const randomUrl = () => {
        const protocols = ['http', 'https'];
        const domains = ['example.com', 'test.org', 'sample.net', 'demo.io'];
        return protocols[randomInt(0, 1)] + '://' + domains[randomInt(0, domains.length - 1)] + '/' + randomStr(6).toLowerCase();
    };

    const randomIP = () => {
        return randomInt(1, 255) + '.' + randomInt(0, 255) + '.' + randomInt(0, 255) + '.' + randomInt(1, 254);
    };

    const randomIPv6 = () => {
        const hex = '0123456789abcdef';
        let result = '';
        for (let i = 0; i < 8; i++) {
            if (i > 0) result += ':';
            for (let j = 0; j < 4; j++) {
                result += hex.charAt(randomInt(0, 15));
            }
        }
        return result;
    };

    const randomHex = () => {
        const hex = '0123456789abcdef';
        let result = '';
        for (let i = 0; i < 32; i++) {
            result += hex.charAt(randomInt(0, 15));
        }
        return result;
    };

    const randomBase64 = () => {
        return btoa(randomStr(24));
    };

    const randomJSON = () => {
        return JSON.stringify({
            id: randomInt(1, 1000),
            name: randomStr(10),
            email: randomEmail(),
            active: Math.random() > 0.5,
            timestamp: new Date().toISOString()
        }, null, 2);
    };

    const randomCalculation = () => {
        const operations = ['+', '-', '*', '/'];
        const nums = [randomInt(1, 100), randomInt(1, 100), randomInt(1, 100)];
        const ops = [operations[randomInt(0, 3)], operations[randomInt(0, 3)]];
        return `${nums[0]}${ops[0]}${nums[1]}${ops[1]}${nums[2]}`;
    };

    const randomCIDR = () => {
        return randomInt(10, 172) + '.' + randomInt(0, 255) + '.' + randomInt(0, 255) + '.0/' + randomInt(16, 30);
    };

    const randomIPRange = () => {
        const start = randomInt(10, 172) + '.' + randomInt(0, 255) + '.' + randomInt(0, 255) + '.' + randomInt(1, 200);
        const end = randomInt(10, 172) + '.' + randomInt(0, 255) + '.' + randomInt(0, 255) + '.' + randomInt(200, 254);
        return start + ' to ' + end;
    };

    const randomSubnetMask = () => {
        const masks = ['255.255.255.0', '255.255.255.128', '255.255.255.192', '255.255.255.224', 
                      '255.255.0.0', '255.255.128.0', '255.255.192.0', '255.0.0.0'];
        return masks[randomInt(0, masks.length - 1)];
    };

    const randomDomain = () => {
        const domains = ['example.com', 'test.org', 'sample.net', 'demo.io', 'localhost', 'google.com'];
        return domains[randomInt(0, domains.length - 1)];
    };

    const randomYAML = () => {
        return 'name: ' + randomStr(8) + '\nversion: 1.0.0\nauthor: ' + randomStr(6) + '\ndescription: Sample configuration';
    };

    const randomXML = () => {
        return '<?xml version="1.0" encoding="UTF-8"?>\n<root>\n  <item id="' + randomInt(1, 100) + '">' + randomStr(8) + '</item>\n</root>';
    };

    // Detect form context via $input element
    let formAction = '';
    let formId = '';
    if ($input && $input.length) {
        const $form = $input.closest('form');
        if ($form.length) {
            formAction = ($form.attr('data-action') || '').toLowerCase();
            formId = ($form.attr('id') || '').toLowerCase();
        }
    }

    // Detect what type of data to generate based on context
    const placeholderLower = placeholder.toLowerCase();
    const typeLower = type.toLowerCase();

    // =====================================================================
    // CONTEXT-AWARE DETECTION BY FORM/MODULE
    // =====================================================================

    // OpenSSL encryption module - generate hex strings for IV and key
    if (formAction === 'openssl' || formId === 'openssl') {
        const inputName = $input ? $input.attr('name') : '';
        if (inputName === 'iv' || inputName === 'key') {
            return randomHex();
        }
    }

    // Calculator module - generate math expressions
    if (formAction === 'calc' || formId === 'calc' || placeholderLower.includes('calculation')) {
        return randomCalculation();
    }

    // Networking/IP tools
    if (formAction === 'ip' || formId.includes('ip') || formId.includes('dns') || formId.includes('cidr') || formId.includes('subnet')) {
        // CIDR to Range
        if (formId.includes('cidr2range')) {
            return randomCIDR();
        }
        // Range to CIDR
        if (formId.includes('range2cidr')) {
            // For range2cidr, alternate between start and end IP fields
            const inputName = $input ? $input.attr('name') : '';
            if (inputName.includes('end') || inputName.includes('to')) {
                return randomInt(10, 172) + '.' + randomInt(0, 255) + '.' + randomInt(0, 255) + '.' + randomInt(200, 254);
            }
            return randomInt(10, 172) + '.' + randomInt(0, 255) + '.' + randomInt(0, 255) + '.' + randomInt(1, 100);
        }
        // Subnet calculator
        if (formId.includes('subnet')) {
            const inputName = $input ? $input.attr('name') : '';
            if (inputName.includes('subnet')) {
                return randomSubnetMask();
            }
            return randomIP();
        }
        // DNS lookup
        if (formId.includes('dns')) {
            return Math.random() > 0.5 ? randomDomain() : randomIP();
        }
        // IP/Hex converter
        if (formId.includes('iphex')) {
            const inputName = $input ? $input.attr('name') : '';
            if (inputName === 'iphex') {
                return Math.random() > 0.5 ? randomIP() : randomHex();
            }
        }
        // Default for IP context
        return randomIP();
    }

    // Serialization/Encoding modules
    if (formAction === 'serialization' || formId.includes('serial')) {
        return randomJSON();
    }

    // Base/Encoding conversion
    if (formAction === 'base' || formId === 'base') {
        return randomStr(20);
    }

    // Diff viewer - generate multi-line text
    if (formAction === 'diff' || formId === 'diff') {
        const inputName = $input ? $input.attr('name') : '';
        if (inputName.includes('old') || inputName === 'diff1') {
            return randomText(3) + '\n' + randomText(2) + '\nOriginal line that stays';
        }
        if (inputName.includes('new') || inputName === 'diff2') {
            return randomText(3) + '\n' + randomText(2) + '\nModified line that changes';
        }
        return randomText(4);
    }

    // =====================================================================
    // PLACEHOLDER-BASED DETECTION (Fallback)
    // =====================================================================

    if (typeLower === 'number') {
        return randomInt(1, 1000).toString();
    }

    if (placeholderLower.includes('email')) {
        return randomEmail();
    }

    if (placeholderLower.includes('calculation')) {
        return randomCalculation();
    }

    if (placeholderLower.includes('url') || placeholderLower.includes('link')) {
        return randomUrl();
    }

    if (placeholderLower.includes('ip') || placeholderLower.includes('address')) {
        if (placeholderLower.includes('ipv6')) {
            return randomIPv6();
        }
        return randomIP();
    }

    if (placeholderLower.includes('cidr') || placeholderLower.includes('subnet')) {
        if (placeholderLower.includes('range')) {
            return randomCIDR();
        }
        return randomSubnetMask();
    }

    if (placeholderLower.includes('hex') || placeholderLower.includes('hash')) {
        return randomHex();
    }

    if (placeholderLower.includes('base64') || placeholderLower.includes('encoded')) {
        return randomBase64();
    }

    if (placeholderLower.includes('json')) {
        return randomJSON();
    }

    if (placeholderLower.includes('yaml')) {
        return randomYAML();
    }

    if (placeholderLower.includes('xml')) {
        return randomXML();
    }

    if (placeholderLower.includes('code')) {
        return randomCode();
    }

    if (placeholderLower.includes('domain')) {
        return randomDomain();
    }

    // For textareas or text inputs, generate appropriate content
    if (type === 'textarea') {
        if (placeholderLower.includes('yaml')) {
            return randomYAML();
        }
        if (placeholderLower.includes('xml')) {
            return randomXML();
        }
        if (placeholderLower.includes('json')) {
            return randomJSON();
        }
        return randomText(5) + '\n\n' + randomText(4);
    }

    // Default for text inputs
    if (type === 'text') {
        return randomStr(12);
    }

    // Fallback
    return randomStr(16);
}

/* ===================================================================== */
/*                   FUNCTION: addRandomDataButtons                      */
/* ===================================================================== */
function addRandomDataButtons($root = null) {
    const $scope = ($root && $root.length) ? $root : $(document);
    // Find all text inputs, number inputs, and textareas that don't already have a random button
    const selectors = [
        'input[type="text"]:not([readonly]):not([disabled])',
        'input[type="number"]:not([readonly]):not([disabled])',
        'textarea:not([readonly]):not([disabled])'
    ];

    $scope.find(selectors.join(',')).each(function() {
        const $input = $(this);
        
        // Skip if already has a random button
        if ($input.parent().hasClass('input-with-random-btn')) {
            return;
        }

        // Skip certain inputs (checkboxes, hidden, etc.)
        const skipIds = ['enablebordercheckbox', 'enablefilterscheckbox', 'enabledebugcheckbox'];
        if (skipIds.includes($input.attr('id'))) {
            return;
        }

        // Skip inputs that are part of special controls
        if ($input.closest('.form-selectgroup').length > 0) {
            return;
        }

        // Check if this is a wheel item input (handle without wrapping)
        const isWheelItemInput = $input.hasClass('wheelitem-input') || $input.closest('.wheelitem').length > 0;
        
        // Skip if already has a random button (for wheel items)
        if (isWheelItemInput && ($input.next(".random-data-btn").length > 0 || $input.siblings(".random-data-btn").length > 0)) {
            return;
        }

        // Get input details
        const inputType = $input.is('textarea') ? 'textarea' : $input.attr('type');
        const placeholder = $input.attr('placeholder') || '';
        const inputId = $input.attr('id') || 'input_' + Math.random().toString(36).substr(2, 9);
        
        if (!$input.attr('id')) {
            $input.attr('id', inputId);
        }

        // Wrap the input if not already wrapped (but not for wheel items - they're already in a flex container)
        if (!isWheelItemInput && !$input.parent().hasClass('input-with-random-btn')) {
            $input.wrap('<div class="input-with-random-btn" style="position: relative; display: flex; gap: 8px; align-items: flex-start;"></div>');
        }

        // Create the random button
        const $btn = $('<button>', {
            type: 'button',
            class: 'btn btn-sm btn-outline-secondary random-data-btn',
            title: 'Generate random data',
            html: '<i class="bi bi-shuffle"></i>',
            css: {
                'flex-shrink': '0',
                'height': $input.is('textarea') ? 'auto' : 'fit-content',
                'align-self': $input.is('textarea') ? 'flex-start' : 'center'
            }
        });

        // Add click handler
        $btn.on('click', function() {
            const randomData = generateRandomData(inputType, placeholder, $input);
            $input.val(randomData).trigger('change').trigger('input');
            
            // Visual feedback
            const originalHtml = $btn.html();
            $btn.html('<i class="bi bi-check"></i>').addClass('btn-success').removeClass('btn-outline-secondary');
            setTimeout(() => {
                $btn.html(originalHtml).removeClass('btn-success').addClass('btn-outline-secondary');
            }, 1000);
        });

        // Append button after input
        $input.after($btn);
    });
}

/* ===================================================================== */
/*                      FUNCTION: checkDuplicateIds                       */
/* ===================================================================== */
function checkDuplicateIds() {
    const idCount = {};
    $('[id]').each(function() {
        const id = this.id;
        idCount[id] = (idCount[id] || 0) + 1;
    });

    const duplicates = Object.keys(idCount).filter(id => idCount[id] > 1);
    if (duplicates.length > 0) {
        console.warn('[duplicate-id-check] Duplicate IDs found:', duplicates);
    }
}