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
function initSshKeyOutputFormatUi($root) {
    $root.find(".crypto-ssh-key-output-card").each(function() {
        const $card = $(this);
        const $sel = $card.find(".crypto-ssh-output-format");
        if (!$sel.length) {
            return;
        }
        function syncFormat() {
            const v = $sel.val();
            $card.find(".crypto-ssh-output-panels .crypto-ssh-output-panel").each(function() {
                $(this).toggleClass("d-none", $(this).attr("data-format") !== v);
            });
        }
        $sel.off("change.sshKeyOutFmt").on("change.sshKeyOutFmt", syncFormat);
        syncFormat();
    });
}

function showData(obj, data) {
    if (obj.is("div")) {
        obj.html(data);
        if (typeof window.refreshCopyUiAvailability === "function") {
            window.refreshCopyUiAvailability(obj[0]);
        }
        if (obj.find(".crypto-ssh-key-output-card").length) {
            initSshKeyOutputFormatUi(obj);
        }
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
/*                    FUNCTION: ensureEnhancersForScope                   */
/* ===================================================================== */
function ensureEnhancersForScope($scope) {
    return $.Deferred().resolve().promise();
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
    html += "<h5 class='card-header'><span>Raw JSON</span></h5>";
    html += "<div class='card-body'>";
    html += "<div class='copyable-content'>";
    html += "<pre class='copyable-body' id='browserInfoJson' style='margin: 0; font-family: inherit; font-size: inherit;'>" + htmlEscape(rawJson) + "</pre>";
    html += "<div class='copyable-actions'>";
    html += "<button type='button' class='btn btn-sm btn-outline-light' onclick='copyToClipboard(\"browserInfoJson\", this)' style='white-space: nowrap; border: 1px solid #e9ecef;'><i class='bi bi-clipboard'></i> Copy JSON</button>";
    html += "</div></div>";
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

function newClientCopyableElementId() {
    if (window.crypto && typeof window.crypto.randomUUID === "function") {
        return "copy_" + window.crypto.randomUUID().replace(/-/g, "");
    }
    return "copy_cli_" + Date.now().toString(36) + "_" + Math.random().toString(36).slice(2, 12);
}

function buildClientKeyOutput(items, title) {
    let html = "<div class='card border-info mb-3'><h5 class='card-header'>" + htmlEscape(title) + "</h5><div class='card-body'>";
    items.forEach(function(item) {
        const copyId = newClientCopyableElementId();
        const encoded = "data:text/plain;charset=utf-8," + encodeURIComponent(item.content);
        html += "<div style='margin-bottom:15px;'>";
        html += "<strong class='copyable-label'>" + htmlEscape(item.label) + "</strong>";
        html += "<div class='copyable-content'>";
        html += "<div class='copyable-body' id='" + htmlEscape(copyId) + "'>" + htmlEscape(item.content) + "</div>";
        html += "<div class='copyable-actions'>";
        html += "<button type='button' class='btn btn-sm btn-outline-light' onclick=\"copyToClipboard('" + htmlEscape(copyId) + "', this)\" style='white-space: nowrap; border: 1px solid #e9ecef;\">";
        html += "<i class='bi bi-files'></i> Copy";
        html += "</button>";
        html += "<a class='btn btn-outline-light btn-sm' download='" + htmlEscape(item.filename) + "' href='" + encoded + "' style='white-space: nowrap; border: 1px solid #e9ecef;'>";
        html += "<i class='bi bi-download'></i> Download " + htmlEscape(item.label);
        html += "</a></div></div></div>";
    });
    html += "</div></div>";
    return html;
}

/** SSH client output: public only in panels; private always visible (matches server when no OpenSSH). */
function buildClientSshKeyOutput(slotItems, title) {
    const publicSlotLabels = {
        "pem-public": "PEM",
        "openssh-public": "OpenSSH (one-line)"
    };
    const publicOrder = ["pem-public", "openssh-public"];
    const bySlot = {};
    slotItems.forEach(function(it) {
        bySlot[it.slot] = it;
    });

    const presentPublic = [];
    publicOrder.forEach(function(slot) {
        if (bySlot[slot]) {
            presentPublic.push(slot);
        }
    });
    const publicCount = presentPublic.length;

    let selectRow = "";
    if (publicCount > 1) {
        let optionsHtml = "";
        presentPublic.forEach(function(slot) {
            optionsHtml += "<option value=\"" + htmlEscape(slot) + "\">" + htmlEscape(publicSlotLabels[slot]) + "</option>";
        });
        selectRow = "<div class=\"mb-3 crypto-ssh-public-format-row d-flex flex-wrap align-items-center gap-2\">"
            + "<label class=\"form-label mb-0 me-1\"><strong>Public key output</strong></label>"
            + "<select class=\"form-select form-select-lg crypto-ssh-output-format\" style=\"max-width:22rem\">"
            + optionsHtml
            + "</select></div>";
    }

    let panelsHtml = "";
    let isFirst = true;
    presentPublic.forEach(function(slot) {
        const item = bySlot[slot];
        const copyId = newClientCopyableElementId();
        const encoded = "data:text/plain;charset=utf-8," + encodeURIComponent(item.content);
        let panelClass = "crypto-ssh-output-panel";
        if (publicCount > 1 && !isFirst) {
            panelClass += " d-none";
        }
        isFirst = false;
        panelsHtml += "<div class=\"" + panelClass + "\" data-format=\"" + htmlEscape(slot) + "\">";
        panelsHtml += "<div style='margin-bottom:15px;'>";
        panelsHtml += "<strong class='copyable-label'>" + htmlEscape(item.label) + "</strong>";
        panelsHtml += "<div class='copyable-content'>";
        panelsHtml += "<div class='copyable-body' id='" + htmlEscape(copyId) + "'>" + htmlEscape(item.content) + "</div>";
        panelsHtml += "<div class='copyable-actions'>";
        panelsHtml += "<button type='button' class='btn btn-sm btn-outline-light' onclick=\"copyToClipboard('" + htmlEscape(copyId) + "', this)\" style='white-space: nowrap; border: 1px solid #e9ecef;\">";
        panelsHtml += "<i class='bi bi-files'></i> Copy</button>";
        panelsHtml += "<a class='btn btn-outline-light btn-sm' download='" + htmlEscape(item.filename) + "' href='" + encoded + "' style='white-space: nowrap; border: 1px solid #e9ecef;'>";
        panelsHtml += "<i class='bi bi-download'></i> Download " + htmlEscape(item.label);
        panelsHtml += "</a></div></div></div></div>";
    });

    let privateHtml = "";
    const priv = bySlot["private-pem"];
    if (priv) {
        const copyId = newClientCopyableElementId();
        const encoded = "data:text/plain;charset=utf-8," + encodeURIComponent(priv.content);
        privateHtml = "<div class=\"crypto-ssh-private-block mt-3 pt-3 border-top border-secondary\">";
        privateHtml += "<div style='margin-bottom:15px;'>";
        privateHtml += "<strong class='copyable-label'>" + htmlEscape(priv.label) + "</strong>";
        privateHtml += "<div class='copyable-content'>";
        privateHtml += "<div class='copyable-body' id='" + htmlEscape(copyId) + "'>" + htmlEscape(priv.content) + "</div>";
        privateHtml += "<div class='copyable-actions'>";
        privateHtml += "<button type='button' class='btn btn-sm btn-outline-light' onclick=\"copyToClipboard('" + htmlEscape(copyId) + "', this)\" style='white-space: nowrap; border: 1px solid #e9ecef;\">";
        privateHtml += "<i class='bi bi-files'></i> Copy</button>";
        privateHtml += "<a class='btn btn-outline-light btn-sm' download='" + htmlEscape(priv.filename) + "' href='" + encoded + "' style='white-space: nowrap; border: 1px solid #e9ecef;'>";
        privateHtml += "<i class='bi bi-download'></i> Download " + htmlEscape(priv.label);
        privateHtml += "</a></div></div></div></div>";
    }

    return "<div class=\"card border-info mb-3 crypto-ssh-key-output-card\" data-crypto-ssh-output>"
        + "<h5 class=\"card-header\">" + htmlEscape(title) + "</h5>"
        + "<div class=\"card-body\">"
        + selectRow
        + "<div class=\"crypto-ssh-output-panels\">" + panelsHtml + "</div>"
        + privateHtml
        + "</div></div>";
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
            if (isSsh) {
                output += "<div class='alert alert-warning'>OpenSSH one-line public keys are not generated in the browser—public PEM and private PEM are shown; use server mode for OpenSSH + PEM public together.</div>";
                const slotItems = [
                    { slot: "pem-public", label: algorithm.toUpperCase() + " Public Key (PEM)", content: pair.publicPem, filename: "public-" + algorithm + suffix + ".pem" },
                    { slot: "private-pem", label: algorithm.toUpperCase() + " Private Key (PEM)", content: pair.privatePem, filename: "private-" + algorithm + suffix + ".pem" }
                ];
                output += buildClientSshKeyOutput(slotItems, algorithm.toUpperCase() + " Client-side SSH Key Material");
            } else {
                const items = [
                    { label: algorithm.toUpperCase() + " Public Key (PEM)", content: pair.publicPem, filename: "public-" + algorithm + suffix + ".pem" },
                    { label: algorithm.toUpperCase() + " Private Key (PEM)", content: pair.privatePem, filename: "private-" + algorithm + suffix + ".pem" }
                ];
                output += buildClientKeyOutput(items, algorithm.toUpperCase() + " Client-side Keypair");
            }
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
/*                        FUNCTION: initCsrFormUi                         */
/* ===================================================================== */
function initCsrFormUi($scope) {
    if (!$scope.find("#csrForm").length) {
        return;
    }
    const algEl = document.getElementById("csrAlgorithm");
    const rsaWrap = document.getElementById("csrOptRsa");
    const ecdsaWrap = document.getElementById("csrOptEcdsa");
    if (!algEl || !rsaWrap || !ecdsaWrap) {
        return;
    }
    function syncCsrKeyOptions() {
        const v = algEl.value;
        rsaWrap.classList.toggle("d-none", v !== "rsa");
        ecdsaWrap.classList.toggle("d-none", v !== "ecdsa");
    }
    $(algEl).off("change.csrKeyOpts").on("change.csrKeyOpts", syncCsrKeyOptions);
    syncCsrKeyOptions();
}

/* ===================================================================== */
/*                      FUNCTION: initKeypairSignFormUi                   */
/* ===================================================================== */
function initKeypairSignFormUi($scope) {
    if (!$scope.find("#keypairSignForm").length) {
        return;
    }
    const modeEl = document.getElementById("keypairSignMode");
    if (!modeEl) {
        return;
    }
    function syncKeypairSignVisibility() {
        const mode = (modeEl.value || "sign").toLowerCase();
        $scope.find(".keypair-sign-only").toggleClass("d-none", mode !== "sign");
        $scope.find(".keypair-verify-only").toggleClass("d-none", mode !== "verify");
    }
    $(modeEl).off("change.keypairSign").on("change.keypairSign", syncKeypairSignVisibility);
    syncKeypairSignVisibility();
}

/* ===================================================================== */
/*           Logo generator: localStorage (survives refresh)            */
/* ===================================================================== */
var RAND_LOGO_GENERATOR_STORAGE_KEY = "randLogoGenerator_v1";
var RAND_LOGO_GENERATOR_STORAGE_VER = 1;

function collectLogoGeneratorState(form) {
    var payload = { v: RAND_LOGO_GENERATOR_STORAGE_VER };
    var seen = new Set();
    for (var i = 0; i < form.elements.length; i++) {
        var el = form.elements[i];
        var name = el.name;
        if (!name || name.indexOf("logo_") !== 0) {
            continue;
        }
        if (el.type === "button" || el.type === "submit") {
            continue;
        }
        if (seen.has(name)) {
            continue;
        }
        if (el.type === "radio") {
            seen.add(name);
            var rnodes = form.querySelectorAll("[name=\"" + name + "\"]");
            for (var j = 0; j < rnodes.length; j++) {
                if (rnodes[j].checked) {
                    payload[name] = rnodes[j].value;
                    break;
                }
            }
            continue;
        }
        if (el.type === "checkbox") {
            seen.add(name);
            payload[name] = el.checked;
            continue;
        }
        seen.add(name);
        payload[name] = el.value;
    }
    return payload;
}

function applyLogoGeneratorState(form, payload, setVal) {
    if (!payload || payload.v !== RAND_LOGO_GENERATOR_STORAGE_VER) {
        return;
    }
    var skipFont = false;
    var fontSel = form.querySelector("[name=\"logo_font\"]");
    if (fontSel && payload.logo_font) {
        var fontOk = false;
        for (var fi = 0; fi < fontSel.options.length; fi++) {
            if (fontSel.options[fi].value === payload.logo_font) {
                fontOk = true;
                break;
            }
        }
        if (!fontOk) {
            skipFont = true;
        }
    }
    Object.keys(payload).forEach(function(key) {
        if (key === "v" || key.indexOf("logo_") !== 0) {
            return;
        }
        if (skipFont && key === "logo_font") {
            return;
        }
        setVal(key, payload[key]);
    });
}

function tryLoadLogoGeneratorState(form, setVal) {
    try {
        var raw = localStorage.getItem(RAND_LOGO_GENERATOR_STORAGE_KEY);
        if (!raw) {
            return;
        }
        applyLogoGeneratorState(form, JSON.parse(raw), setVal);
    } catch (e) {
        /* ignore */
    }
}

function trySaveLogoGeneratorState(form) {
    try {
        localStorage.setItem(RAND_LOGO_GENERATOR_STORAGE_KEY, JSON.stringify(collectLogoGeneratorState(form)));
    } catch (e) {
        /* quota / private mode */
    }
}

/* ===================================================================== */
/*                    FUNCTION: initLogoGeneratorUi                      */
/* ===================================================================== */
function initLogoGeneratorUi($scope) {
    const $form = $scope.find("#logoGeneratorForm");
    if (!$form.length) {
        return;
    }
    if ($form.data("randLogoGenBound")) {
        return;
    }
    $form.data("randLogoGenBound", true);

    const form = $form[0];
    const $hint = $scope.find("#logoHintText");
    let debounceTimer = null;
    let sliderPreviewTimer = null;
    let persistTimer = null;
    let activeXhr = null;
    const DEBOUNCE_MS = 0;
    const SLIDER_PREVIEW_DEBOUNCE_MS = 120;
    const PERSIST_DEBOUNCE_MS = 400;

    const setVal = (name, value) => {
        const nodes = form.querySelectorAll("[name=\"" + name + "\"]");
        if (!nodes.length) {
            return;
        }
        const first = nodes[0];
        if (first.type === "checkbox") {
            first.checked = !!value;
            return;
        }
        if (first.type === "radio") {
            const str = String(value);
            nodes.forEach((r) => {
                r.checked = r.value === str;
            });
            return;
        }
        first.value = value;
    };

    const syncGradientSwitchesFromHidden = () => {
        const bg = form.querySelector("#logo_style");
        const bgSw = form.querySelector("#logoGradientSwitch");
        if (bg && bgSw) {
            bgSw.checked = String(bg.value) === "gradient";
        }
        const tx = form.querySelector("#logo_text_style");
        const txSw = form.querySelector("#logoTextGradientSwitch");
        if (tx && txSw) {
            txSw.checked = String(tx.value) === "gradient";
        }
    };

    tryLoadLogoGeneratorState(form, setVal);
    syncGradientSwitchesFromHidden();

    const $borderToggle = $form.find("#logoBorderEnabled");
    const $borderInput = $form.find("#logo_border");
    const $borderWidthWrap = $form.find("#logoBorderWidthWrap");
    const $borderColorWrap = $form.find("#logoBorderColorWrap");
    const $borderColorInput = $form.find("#logo_border_color");
    const $borderColorRandomBtn = $form.find(".logo-color-random[data-target='logo_border_color']");
    const $textAccentWrap = $form.find("#logoTextAccentWrap");
    const $textAccentInput = $form.find("#logo_text_accent_color");
    const $textAccentRandomBtn = $form.find(".logo-color-random[data-target='logo_text_accent_color']");
    const $bgAccentWrap = $form.find("#logoBgAccentWrap");
    const $bgAccentInput = $form.find("#logo_accent_color");
    const $bgAccentRandomBtn = $form.find(".logo-color-random[data-target='logo_accent_color']");
    const $bgGradStrengthWrap = $form.find("#logoBgGradientStrengthWrap");
    const $bgGradStrengthInput = $form.find("#logo_bg_gradient_strength");
    const $textGradStrengthWrap = $form.find("#logoTextGradientStrengthWrap");
    const $textGradStrengthInput = $form.find("#logo_text_gradient_strength");
    const clampBorderWidth = (value, fallback) => {
        let parsed = parseInt(value, 10);
        if (Number.isNaN(parsed)) {
            parsed = fallback;
        }
        return Math.max(0, Math.min(24, parsed));
    };
    const setBorderEnabled = (enabled) => {
        if ($borderToggle.length) {
            $borderToggle.prop("checked", !!enabled);
        }
    };
    const syncBorderUi = () => {
        if (!$borderToggle.length || !$borderInput.length) {
            return;
        }
        const enabled = $borderToggle.prop("checked");
        const currentWidth = clampBorderWidth($borderInput.val(), 0);
        if (currentWidth > 0) {
            $form.data("logoLastBorderWidth", currentWidth);
        }
        if ($borderColorWrap.length) {
            $borderColorWrap.toggleClass("d-none", !enabled);
        }
        if (enabled) {
            const restoredWidth = clampBorderWidth($form.data("logoLastBorderWidth"), 4);
            $borderWidthWrap.removeClass("d-none");
            $borderInput.prop("disabled", false);
            $borderColorInput.prop("disabled", false);
            $borderColorRandomBtn.prop("disabled", false);
            $borderInput.val(String(currentWidth > 0 ? currentWidth : Math.max(1, restoredWidth)));
            return;
        }
        $borderWidthWrap.addClass("d-none");
        $borderInput.val("0").prop("disabled", true);
        $borderColorInput.prop("disabled", true);
        $borderColorRandomBtn.prop("disabled", true);
    };

    const syncBgGradStrengthLabel = () => {
        const r = form.querySelector("#logo_bg_gradient_strength");
        const sp = form.querySelector("#logo_bg_gradient_strength_val");
        if (r && sp) {
            sp.textContent = r.value + "%";
            r.setAttribute("aria-valuenow", r.value);
        }
    };
    const syncTextGradStrengthLabel = () => {
        const r = form.querySelector("#logo_text_gradient_strength");
        const sp = form.querySelector("#logo_text_gradient_strength_val");
        if (r && sp) {
            sp.textContent = r.value + "%";
            r.setAttribute("aria-valuenow", r.value);
        }
    };

    const syncBackgroundStyleUi = () => {
        if (!$bgAccentInput.length) {
            return;
        }
        const styleEl = form.querySelector("[name=\"logo_style\"]");
        const grad = styleEl && String(styleEl.value) === "gradient";
        if ($bgAccentWrap.length) {
            $bgAccentWrap.toggleClass("d-none", !grad);
        }
        $bgAccentInput.prop("disabled", !grad);
        if ($bgAccentRandomBtn.length) {
            $bgAccentRandomBtn.prop("disabled", !grad);
        }
        if ($bgGradStrengthWrap.length) {
            $bgGradStrengthWrap.toggleClass("d-none", !grad);
        }
        if ($bgGradStrengthInput.length) {
            $bgGradStrengthInput.prop("disabled", !grad);
        }
        syncBgGradStrengthLabel();
    };

    const syncTextFillUi = () => {
        if (!$textAccentInput.length) {
            return;
        }
        const styleEl = form.querySelector("[name=\"logo_text_style\"]");
        const grad = styleEl && String(styleEl.value) === "gradient";
        if ($textAccentWrap.length) {
            $textAccentWrap.toggleClass("d-none", !grad);
        }
        $textAccentInput.prop("disabled", !grad);
        if ($textAccentRandomBtn.length) {
            $textAccentRandomBtn.prop("disabled", !grad);
        }
        if ($textGradStrengthWrap.length) {
            $textGradStrengthWrap.toggleClass("d-none", !grad);
        }
        if ($textGradStrengthInput.length) {
            $textGradStrengthInput.prop("disabled", !grad);
        }
        syncTextGradStrengthLabel();
    };

    const $sizeInput = $form.find("#logo_font_size");
    const $sizeRange = $form.find("#logo_font_size_range");
    const syncFontSizeUi = () => {
        if (!$sizeInput.length || !$sizeRange.length) {
            return;
        }
        let v = parseInt($sizeInput.val(), 10);
        if (Number.isNaN(v)) {
            v = 96;
        }
        v = Math.max(12, Math.min(400, v));
        $sizeInput.val(String(v));
        $sizeRange.val(String(v));
    };
    $sizeInput.off("input.randLogoGen").on("input.randLogoGen", syncFontSizeUi);
    $sizeRange.off("input.randLogoGen").on("input.randLogoGen", function() {
        $sizeInput.val($sizeRange.val());
    });

    $form.find("#logo_bg_gradient_strength, #logo_text_gradient_strength").off("input.randLogoGradStr").on("input.randLogoGradStr", function() {
        if (this.id === "logo_bg_gradient_strength") {
            syncBgGradStrengthLabel();
        } else {
            syncTextGradStrengthLabel();
        }
    });

    const runLogoPreview = () => {
        if (typeof setFormVal !== "function" || typeof showData !== "function") {
            return;
        }
        clearTimeout(sliderPreviewTimer);
        sliderPreviewTimer = null;
        syncBackgroundStyleUi();
        syncTextFillUi();
        if (activeXhr) {
            activeXhr.abort();
            activeXhr = null;
        }
        const $response = $form.find(".responseDiv");
        setFormVal($form, "responsetype", "html");
        setFormVal($form, "action", $form.data("action") || "logo_generate");
        activeXhr = $.ajax({
            type: "POST",
            url: $form.attr("action") || "gen.php",
            data: $form.serialize(),
            success: function(html) {
                activeXhr = null;
                showData($response, html);
            },
            error: function(jqXHR, textStatus) {
                activeXhr = null;
                if (textStatus === "abort") {
                    return;
                }
                const msg = jqXHR && jqXHR.statusText ? jqXHR.statusText : "request failed";
                showData($response, "<div class='alert alert-danger'>Preview error: " + msg + "</div>");
            }
        });
    };

    const scheduleLogoPreview = () => {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(runLogoPreview, DEBOUNCE_MS);
    };

    const scheduleLogoPreviewSoon = () => {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(runLogoPreview, 0);
    };

    const scheduleLogoPersist = () => {
        clearTimeout(persistTimer);
        persistTimer = setTimeout(function() {
            trySaveLogoGeneratorState(form);
        }, PERSIST_DEBOUNCE_MS);
    };

    $form.find("#logoGradientSwitch").off("change.randLogoGrad").on("change.randLogoGrad", function(e) {
        e.stopPropagation();
        setVal("logo_style", this.checked ? "gradient" : "solid");
        syncBackgroundStyleUi();
        syncTextFillUi();
        clearTimeout(debounceTimer);
        clearTimeout(sliderPreviewTimer);
        sliderPreviewTimer = null;
        runLogoPreview();
        scheduleLogoPersist();
    });
    $form.find("#logoTextGradientSwitch").off("change.randLogoGrad").on("change.randLogoGrad", function(e) {
        e.stopPropagation();
        setVal("logo_text_style", this.checked ? "gradient" : "solid");
        syncBackgroundStyleUi();
        syncTextFillUi();
        clearTimeout(debounceTimer);
        clearTimeout(sliderPreviewTimer);
        sliderPreviewTimer = null;
        runLogoPreview();
        scheduleLogoPersist();
    });

    $form.off(".randLogoLive").on("input.randLogoLive change.randLogoLive", "input:not([type='hidden']), select, textarea", function(ev) {
        const tid = ev.target && ev.target.id;
        if (tid === "logo_bg_gradient_strength" || tid === "logo_text_gradient_strength") {
            clearTimeout(sliderPreviewTimer);
            sliderPreviewTimer = setTimeout(function() {
                sliderPreviewTimer = null;
                scheduleLogoPreview();
            }, SLIDER_PREVIEW_DEBOUNCE_MS);
            scheduleLogoPersist();
            return;
        }
        scheduleLogoPreview();
        scheduleLogoPersist();
    });
    $form.off("submit.randLogoLive").on("submit.randLogoLive", function(e) {
        e.preventDefault();
        e.stopPropagation(); /* keep document’s .form delegate from also submitting */
        scheduleLogoPreviewSoon();
        return false;
    });

    const setPreset = (preset) => {
        if (preset === "app-icon") {
            setVal("logo_width", 512);
            setVal("logo_height", 512);
            setVal("logo_shape", "rounded");
            setVal("logo_style", "gradient");
            setVal("logo_font_size", 120);
            setBorderEnabled(false);
            setVal("logo_border", 0);
            setVal("logo_initials", true);
            setVal("logo_uppercase", true);
            if ($hint.length) {
                $hint.text("App icon: square canvas, rounded shape, initials + caps — good for launcher icons.");
            }
            syncGradientSwitchesFromHidden();
            syncFontSizeUi();
            syncBorderUi();
            scheduleLogoPreviewSoon();
            scheduleLogoPersist();
            return;
        }
        if (preset === "banner") {
            setVal("logo_width", 1200);
            setVal("logo_height", 400);
            setVal("logo_shape", "rectangle");
            setVal("logo_style", "gradient");
            setVal("logo_font_size", 110);
            setBorderEnabled(false);
            setVal("logo_border", 0);
            setVal("logo_initials", false);
            setVal("logo_uppercase", false);
            if ($hint.length) {
                $hint.text("Banner: wide rectangle with full text — headers and cover images.");
            }
            syncGradientSwitchesFromHidden();
            syncFontSizeUi();
            syncBorderUi();
            scheduleLogoPreviewSoon();
            scheduleLogoPersist();
            return;
        }
        if (preset === "initials-badge") {
            setVal("logo_width", 384);
            setVal("logo_height", 384);
            setVal("logo_shape", "circle");
            setVal("logo_style", "solid");
            setVal("logo_font_size", 132);
            setBorderEnabled(true);
            setVal("logo_border", 8);
            setVal("logo_initials", true);
            setVal("logo_uppercase", true);
            if ($hint.length) {
                $hint.text("Initials badge: circle, solid fill, visible border — avatars and seals.");
            }
            syncGradientSwitchesFromHidden();
            syncFontSizeUi();
            syncBorderUi();
            scheduleLogoPreviewSoon();
            scheduleLogoPersist();
        }
    };

    const rndHex = () => "#" + Math.floor(Math.random() * 16777215).toString(16).padStart(6, "0");
    const randomizePalette = () => {
        setVal("logo_bg_color", rndHex());
        setVal("logo_accent_color", rndHex());
        setVal("logo_text_color", "#ffffff");
        setVal("logo_text_accent_color", rndHex());
        setVal("logo_border_color", rndHex());
        if ($hint.length) {
            $hint.text("Colors shuffled — preview updating.");
        }
        scheduleLogoPreviewSoon();
        scheduleLogoPersist();
    };

    $form.find(".logo-preset-btn").off("click.randLogoPreset").on("click.randLogoPreset", function() {
        setPreset($(this).data("preset") || "");
    });

    $form.find("#logoRandomizeBtn").off("click.randLogoRand").on("click.randLogoRand", randomizePalette);

    $form.find(".logo-color-random").off("click.randLogoColor").on("click.randLogoColor", function() {
        const id = this.getAttribute("data-target");
        const el = id ? document.getElementById(id) : null;
        if (el && el.type === "color") {
            el.value = rndHex();
            scheduleLogoPreviewSoon();
            scheduleLogoPersist();
        }
    });

    $form.find("#logoOffsetReset").off("click.randLogoOff").on("click.randLogoOff", function() {
        setVal("logo_text_offset_x", 0);
        setVal("logo_text_offset_y", 0);
        scheduleLogoPreviewSoon();
        scheduleLogoPersist();
    });

    $borderToggle.off("change.randLogoBorder").on("change.randLogoBorder", function(e) {
        e.stopPropagation();
        syncBorderUi();
        clearTimeout(debounceTimer);
        clearTimeout(sliderPreviewTimer);
        sliderPreviewTimer = null;
        runLogoPreview();
        scheduleLogoPersist();
    });

    $borderInput.off("input.randLogoBorder").on("input.randLogoBorder", function(e) {
        e.stopPropagation();
        const width = clampBorderWidth($borderInput.val(), 0);
        setBorderEnabled(width > 0);
        syncBorderUi();
        clearTimeout(debounceTimer);
        clearTimeout(sliderPreviewTimer);
        sliderPreviewTimer = null;
        runLogoPreview();
        scheduleLogoPersist();
    });

    syncFontSizeUi();
    setBorderEnabled(clampBorderWidth($borderInput.val(), 0) > 0);
    syncBorderUi();
    syncBackgroundStyleUi();
    syncTextFillUi();
    scheduleLogoPreviewSoon();
}

/* ===================================================================== */
/*                    FUNCTION: initCrontabLiveAnalyzeUi                  */
/* ===================================================================== */
function initCrontabLiveAnalyzeUi($scope) {
    const $form = $scope.find("#crontabForm");
    if (!$form.length) {
        return;
    }

    const $expression = $form.find("#crontabExpression");
    const $responseDiv = $form.find(".responseDiv");
    if (!$expression.length || !$responseDiv.length) {
        return;
    }

    if ($form.data("crontabPlaceholderHtml") === undefined) {
        $form.data("crontabPlaceholderHtml", $responseDiv.html());
    }

    let debounceTimer = null;
    let analyzeSeq = 0;

    function getPlaceholderHtml() {
        return $form.data("crontabPlaceholderHtml") || "";
    }

    function runFullAnalyze() {
        const expr = ($expression.val() || "").trim();
        if (!expr) {
            showData($responseDiv, getPlaceholderHtml());
            return;
        }

        const myId = ++analyzeSeq;
        setFormVal($form, "responsetype", "html");
        setFormVal($form, "action", "crontab");
        showData($responseDiv, buildLoadingHtml("Analyzing schedule…"));

        $.ajax({
            type: "POST",
            url: $form.attr("action") || "gen.php",
            data: $form.serialize()
        }).done(function(html) {
            if (myId !== analyzeSeq) {
                return;
            }
            showData($responseDiv, html);
        }).fail(function(xhr) {
            if (myId !== analyzeSeq) {
                return;
            }
            const message = "<div class='alert alert-danger'>Error: " + xhr.statusText + "</div>";
            showData($responseDiv, message);
        });
    }

    function scheduleAnalyze() {
        if (debounceTimer) {
            clearTimeout(debounceTimer);
        }

        const expr = ($expression.val() || "").trim();
        if (!expr) {
            showData($responseDiv, getPlaceholderHtml());
            return;
        }

        debounceTimer = setTimeout(runFullAnalyze, 400);
    }

    $expression.off(".crontabLiveAnalyze").on("input.crontabLiveAnalyze", scheduleAnalyze);
    $form.find("[name='cron_timezone'], [name='cron_run_count'], [name='cron_reference_time']")
        .off(".crontabLiveAnalyze")
        .on("change.crontabLiveAnalyze input.crontabLiveAnalyze", scheduleAnalyze);
    $form.find("[name='cron_include_current']")
        .off(".crontabLiveAnalyze")
        .on("change.crontabLiveAnalyze", scheduleAnalyze);

    scheduleAnalyze();
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

    // Reset all navbar link states
    const $navbarLinks = $(".navbar .nav-link, .navbar .dropdown-item.link");
    $navbarLinks.removeClass("active link-success");

    // Set direct (top-level) nav link active
    const $navLink = $(`.link.nav-link[data-show='${moduleName}']`);
    if ($navLink.length) {
        $navLink.addClass("link-success active");
    }

    // Set dropdown item active and highlight its parent dropdown toggle
    const $dropdownItem = $(`.dropdown-item.link[data-show='${moduleName}']`);
    if ($dropdownItem.length) {
        $dropdownItem.addClass("active");
        $dropdownItem
            .closest(".nav-item.dropdown")
            .find("> .nav-link.dropdown-toggle")
            .addClass("active link-success");
    }

    const showTarget = function() {
        $(".content").hide();
        $(normalizedTo).fadeIn();
        addRandomDataButtons($(normalizedTo));
        initLogoGeneratorUi($(normalizedTo));
        initCsrFormUi($(normalizedTo));
        initKeypairSignFormUi($(normalizedTo));
        initCrontabLiveAnalyzeUi($(normalizedTo));
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
            const $mod = $(selector);
            ensureEnhancersForScope($mod).always(function() {
                addRandomDataButtons($mod);
            });
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

    if (window.randUiPrefs) {
        var p0 = window.randUiPrefs.read();
        $("#randPrefTheme").val(p0.theme);
        $("#randPrefUiScale").val(String(p0.uiScale));
        $("#randPrefSpaceScale").val(String(p0.spaceScale));
        $("#randPrefTheme").on("change.randUiPrefs", function () {
            var p = window.randUiPrefs.read();
            p.theme = ($(this).val() === "light") ? "light" : "dark";
            window.randUiPrefs.save(p);
        });
        $("#randPrefUiScale").on("change.randUiPrefs", function () {
            var p = window.randUiPrefs.read();
            p.uiScale = parseFloat($(this).val(), 10);
            window.randUiPrefs.save(p);
            $("#randPrefUiScale").val(String(window.randUiPrefs.read().uiScale));
        });
        $("#randPrefSpaceScale").on("change.randUiPrefs", function () {
            var p = window.randUiPrefs.read();
            p.spaceScale = parseFloat($(this).val(), 10);
            window.randUiPrefs.save(p);
            $("#randPrefSpaceScale").val(String(window.randUiPrefs.read().spaceScale));
        });
    }

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

    $(document).on("click", ".syntax-validate-random-sample", function() {
        var $form = $(this).closest("#syntaxValidateForm");
        if (!$form.length) {
            return;
        }
        var $ta = $form.find("#syntaxValidateInput");
        if (!$ta.length) {
            return;
        }
        randomDataGetCompatibleFormBundle($form);
        var text = generateRandomData("textarea", "Paste content to validate...", $ta);
        $ta.val(text).trigger("change").trigger("input");
    });

    $(document).on("click", ".serialization-random-sample", function() {
        var $form = $(this).closest("#serializationForm");
        if (!$form.length) {
            return;
        }
        var $ta = $form.find("#serializationInput");
        if (!$ta.length) {
            return;
        }
        randomDataGetCompatibleFormBundle($form);
        var text = generateRandomData("textarea", "Paste JSON, YAML, or XML here...", $ta);
        $ta.val(text).trigger("change").trigger("input");
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
    /*                         About / Changelog modal                         */
    /* ===================================================================== */
    var aboutInfoPanel = $("#aboutInfoPanel");
    var changelog = $("#changelogMarkdown");
    var aboutInfoLoaded = false;
    var changelogLoaded = false;

    function escapeHtml(text) {
        return $("<div>").text(text == null ? "" : String(text)).html();
    }

    function renderAboutInfo(data) {
        var isDocker = data.environment === "docker";
        var envLabel = isDocker ? "Docker container" : "Native (non-Docker)";
        var envBadgeClass = isDocker ? "bg-azure-lt text-azure" : "bg-secondary-lt text-secondary";

        var rows = [
            ["php-rand version", data.php_rand_version],
            ["PHP version", data.php_version],
            ["PHP SAPI", data.php_sapi],
            ["Environment", '<span class="badge ' + envBadgeClass + '">' + escapeHtml(envLabel) + "</span>"],
            ["Operating system", data.os]
        ];

        if (data.docker_image_version) {
            rows.push(["Docker image label", data.docker_image_version]);
        }
        if (data.server_software) {
            rows.push(["Server software", data.server_software]);
        }

        var html = "";
        if (data.demo_url) {
            html += '<p class="mb-3"><a class="btn btn-outline-primary btn-sm" href="' + escapeHtml(data.demo_url) + '" target="_blank" rel="noopener noreferrer">';
            html += '<i class="bi bi-box-arrow-up-right me-1" aria-hidden="true"></i>Live demo</a></p>';
        }
        html += '<div class="about-info-summary mb-4">';
        html += '<dl class="row about-info-dl mb-0">';
        rows.forEach(function(row) {
            html += '<dt class="col-sm-4 col-lg-3">' + escapeHtml(row[0]) + '</dt>';
            html += '<dd class="col-sm-8 col-lg-9">' + row[1] + "</dd>";
        });
        html += "</dl>";
        if (data.has_unreleased_changes && !isDocker) {
            html += '<p class="text-muted small mb-0 mt-2">This install includes unreleased changes from <code>CHANGELOG.md</code>.</p>';
        }
        html += "</div>";

        html += '<h3 class="h5 mb-2">Key PHP extensions</h3>';
        html += '<p class="text-muted small">Extensions commonly used by php-rand tools.</p>';
        html += '<div class="about-key-extensions mb-4">';
        (data.key_extensions || []).forEach(function(ext) {
            var badgeClass = ext.loaded ? "bg-green-lt text-green" : "bg-red-lt text-red";
            var iconClass = ext.loaded ? "bi-check-circle-fill" : "bi-x-circle-fill";
            html += '<span class="badge ' + badgeClass + ' about-ext-badge me-1 mb-1" title="' + escapeHtml(ext.label) + '">';
            html += '<i class="bi ' + iconClass + ' me-1" aria-hidden="true"></i>';
            html += escapeHtml(ext.name);
            html += "</span>";
        });
        html += "</div>";

        var loaded = data.loaded_extensions || [];
        html += '<details class="about-extensions-all">';
        html += '<summary class="h6 mb-0">All loaded PHP extensions (' + (data.loaded_extension_count || loaded.length) + ")</summary>";
        html += '<div class="about-extensions-list small text-muted mt-2">';
        if (loaded.length) {
            html += escapeHtml(loaded.join(", "));
        } else {
            html += "No extensions reported.";
        }
        html += "</div></details>";

        return html;
    }

    function loadAboutInfo() {
        if (aboutInfoLoaded) {
            return;
        }
        aboutInfoPanel.html(buildLoadingHtml("Loading environment details…"));
        $.ajax({
            type: "GET",
            url: "about.php",
            dataType: "json",
            cache: false
        }).done(function(data) {
            aboutInfoPanel.html(renderAboutInfo(data));
            aboutInfoLoaded = true;
        }).fail(function() {
            aboutInfoPanel.html("<div class='alert alert-danger'>Failed to load environment details.</div>");
        });
    }

    function loadChangelog() {
        if (changelogLoaded) {
            return;
        }
        changelog.html(buildLoadingHtml("Loading changelog…"));
        $.ajax({
            type: "GET",
            url: "changelog.php",
            cache: false
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
    }

    $("#aboutModal").on("show.bs.modal", function() {
        loadAboutInfo();
    });

    $("#changelogTabBtn").on("shown.bs.tab", function() {
        loadChangelog();
    });

    /* ===================================================================== */
    /*                      Add Random Data Buttons                          */
    /* ===================================================================== */
    addRandomDataButtons($(".content:visible").first());

}); // document.ready

/* ===================================================================== */
/*           SCENARIO DATA + randomPickAvoidRepeatFromForm              */
/* ===================================================================== */
function randomPickAvoidRepeatFromForm($form, items, storageSubkey) {
    const n = items.length;
    if (n === 0) {
        return null;
    }
    if (n === 1) {
        return items[0];
    }
    const randomInt = (min, max) => Math.floor(Math.random() * (max - min + 1)) + min;
    const key = "randomPickLast_" + storageSubkey;
    const lastIdx = $form.data(key);
    let idx = randomInt(0, n - 1);
    let tries = 0;
    while (idx === lastIdx && tries < 64) {
        idx = randomInt(0, n - 1);
        tries++;
    }
    $form.data(key, idx);
    return items[idx];
}

const CRONTAB_RANDOM_SCENARIOS = [
    { expression: "*/15 9-17 * * MON-FRI", timezone: "Europe/Stockholm" },
    { expression: "0 2 1 * *", timezone: "UTC" },
    { expression: "30 6 * * 1-5", timezone: "America/New_York" },
    { expression: "@daily", timezone: "Asia/Tokyo" },
    { expression: "*/5 * * * *", timezone: "UTC" },
    { expression: "0 * * * *", timezone: "Europe/London" },
    { expression: "0 0 * * 0", timezone: "America/Los_Angeles" },
    { expression: "0 0 * * 6", timezone: "Australia/Sydney" },
    { expression: "15 14 * * MON-FRI", timezone: "America/Chicago" },
    { expression: "0 9 * * 1", timezone: "Europe/Berlin" },
    { expression: "45 23 * * *", timezone: "Pacific/Auckland" },
    { expression: "0 0 1,15 * *", timezone: "America/Toronto" },
    { expression: "0 12 * * SUN", timezone: "Africa/Johannesburg" },
    { expression: "*/10 8-18 * * *", timezone: "Asia/Singapore" },
    { expression: "0 3 * * 2,4", timezone: "America/Denver" },
    { expression: "@hourly", timezone: "UTC" },
    { expression: "@weekly", timezone: "Europe/Paris" },
    { expression: "@monthly", timezone: "America/Sao_Paulo" },
    { expression: "0 0 1 1 *", timezone: "UTC" },
    { expression: "30 2 * * *", timezone: "Asia/Kolkata" },
    { expression: "0 0 * * 1-5", timezone: "America/Mexico_City" },
    { expression: "5,25,45 * * * *", timezone: "Europe/Warsaw" },
    { expression: "0 0-23/2 * * *", timezone: "UTC" },
    { expression: "0 0 * * SUN#2", timezone: "America/New_York" },
    { expression: "0 0 L * *", timezone: "UTC" },
    { expression: "0 0 15W * *", timezone: "America/Vancouver" }
];

const SYNTAX_VALIDATE_KIND_OPTIONS = [
    { kind: "json" },
    { kind: "yaml" },
    { kind: "xml" },
    { kind: "ini" },
    { kind: "jsonl" },
    { kind: "cron" },
    { kind: "php" },
    { kind: "python" },
    { kind: "ruby" },
    { kind: "javascript" },
    { kind: "shell" }
];

const SYNTAX_VALIDATE_SCENARIOS = [
    {
        kind: "json",
        content: '{\n  "service": "demo",\n  "port": 8080,\n  "enabled": true\n}'
    },
    {
        kind: "yaml",
        content: "app:\n  name: phprand\n  env: production\nlisten:\n  host: 127.0.0.1\n  port: 9000\n"
    },
    {
        kind: "xml",
        content: '<?xml version="1.0" encoding="UTF-8"?>\n<config>\n  <service name="demo" port="8080"/>\n</config>\n'
    },
    {
        kind: "ini",
        content: "; sample ini\n[app]\nname = phprand\nenv = production\n\n[listen]\nhost = 127.0.0.1\nport = 9000\n"
    },
    {
        kind: "jsonl",
        content: '{"id":1,"msg":"alpha"}\n{"id":2,"msg":"beta"}\n{"id":3,"msg":"gamma"}\n'
    },
    {
        kind: "cron",
        content: "# daily at 02:00 (five fields only; command goes in crontab UI elsewhere)\n0 2 * * *\n"
    },
    {
        kind: "php",
        content: "<?php\n\ndeclare(strict_types=1);\n\n$items = [1, 2, 3];\necho array_sum($items);\n"
    },
    {
        kind: "python",
        content: "def total(values: list[int]) -> int:\n    return sum(values)\n\nprint(total([10, 20, 12]))\n"
    },
    {
        kind: "ruby",
        content: "# frozen_string_literal: true\n\ndef total(values)\n  values.sum\nend\n\nputs total([10, 20, 12])\n"
    },
    {
        kind: "javascript",
        content: "function total(values) {\n  return values.reduce((a, b) => a + b, 0);\n}\n\nconsole.log(total([10, 20, 12]));\n"
    },
    {
        kind: "shell",
        content: "#!/usr/bin/env bash\nset -euo pipefail\nfor f in *.log; do\n  echo \"processing $f\"\ndone\n"
    }
];

const SHELLCHECK_RANDOM_SCENARIOS = [
    {
        filename: "deploy.sh",
        shell: "bash",
        script: "#!/usr/bin/env bash\nfor file in *.log; do\n  echo Processing $file\n  grep ERROR $file\n  rm $file\ndone\n"
    },
    {
        filename: "backup.sh",
        shell: "sh",
        script: "#!/bin/sh\nfor archive in $(ls /var/backups); do\n  echo \"$archive\"\ndone\n"
    },
    {
        filename: "check-users.sh",
        shell: "bash",
        script: "#!/usr/bin/env bash\nif [ $USER = root ]; then\n  echo admin mode\nfi\n"
    },
    {
        filename: "parse-json.sh",
        shell: "bash",
        script: "#!/usr/bin/env bash\nname=`jq -r .name < config.json`\necho $name\n"
    },
    {
        filename: "read-lines.sh",
        shell: "bash",
        script: "#!/usr/bin/env bash\nwhile read line; do\n  echo \"$line\" | wc -c\ndone < input.txt\n"
    },
    {
        filename: "paths.sh",
        shell: "bash",
        script: "#!/usr/bin/env bash\npath=\"/tmp/my files/report.txt\"\ncat $path\n"
    },
    {
        filename: "legacy-echo.sh",
        shell: "sh",
        script: "#!/bin/sh\necho -n \"done\"\n"
    },
    {
        filename: "find-exec.sh",
        shell: "bash",
        script: "#!/usr/bin/env bash\nfind . -name \"*.tmp\" -exec rm {} \\;\n"
    },
    {
        filename: "sudo-tee.sh",
        shell: "bash",
        script: "#!/usr/bin/env bash\necho \"config line\" | sudo tee -a /etc/app.conf\n"
    },
    {
        filename: "subshell-cd.sh",
        shell: "bash",
        script: "#!/usr/bin/env bash\n(cd /var/log && ls)\npwd\n"
    },
    {
        filename: "array-ish.sh",
        shell: "sh",
        script: "#!/bin/sh\nitems=(one two three)\necho ${items[0]}\n"
    },
    {
        filename: "double-grep.sh",
        shell: "bash",
        script: "#!/usr/bin/env bash\ncat access.log | grep GET | grep -v health\n"
    },
    {
        filename: "curl-pipe.sh",
        shell: "bash",
        script: "#!/usr/bin/env bash\nurl=\"https://example.com/data.json\"\ncurl $url | jq .\n"
    },
    {
        filename: "test-brackets.sh",
        shell: "bash",
        script: "#!/usr/bin/env bash\nif [[ $1 == \"yes\" ]]; then\n  echo ok\nfi\n"
    },
    {
        filename: "dash-printf.sh",
        shell: "dash",
        script: "#!/bin/dash\nfor i in 1 2 3; do\n  echo $i\ndone\n"
    },
    {
        filename: "ksh-typo.sh",
        shell: "ksh",
        script: "#!/bin/ksh\nset -e\ncd /maybe/missing/dir\nls\n"
    },
    {
        filename: "source-args.sh",
        shell: "bash",
        script: "#!/usr/bin/env bash\n. ./lib.sh $1\n"
    },
    {
        filename: "seq-printf.sh",
        shell: "bash",
        script: "#!/usr/bin/env bash\nfor i in $(seq 1 10); do\n  printf \"%s\\n\" $i\ndone\n"
    },
    {
        filename: "local-wrong.sh",
        shell: "sh",
        script: "#!/bin/sh\nfoo() {\n  local x=1\n  echo $x\n}\nfoo\n"
    },
    {
        filename: "mkdir-cd.sh",
        shell: "bash",
        script: "#!/usr/bin/env bash\nmkdir -p /tmp/build\ncd /tmp/build || exit 1\nrm -f *.o\n"
    },
    {
        filename: "wget-string.sh",
        shell: "bash",
        script: "#!/usr/bin/env bash\nname=\"report 2026.pdf\"\nwget -O \"$name\" https://example.com/file\n"
    },
    {
        filename: "eval-dynamic.sh",
        shell: "bash",
        script: "#!/usr/bin/env bash\ncmd=\"echo hello\"\neval $cmd\n"
    },
    {
        filename: "sleep-rand.sh",
        shell: "bash",
        script: "#!/usr/bin/env bash\nsleep $RANDOM % 5\necho done\n"
    },
    {
        filename: "docker-run.sh",
        shell: "bash",
        script: "#!/usr/bin/env bash\ndocker run --rm -it myimage:latest /bin/bash -c \"apt update && apt install -y curl\"\n"
    },
    {
        filename: "strict-assign.sh",
        shell: "bash",
        script: "#!/usr/bin/env bash\nset -u\necho $UNSET_VAR\n"
    },
    {
        filename: "heredoc-tabs.sh",
        shell: "bash",
        script: "#!/usr/bin/env bash\ncat <<-EOF\n\tindented line\nEOF\n"
    }
];

function randomDataGetCompatibleFormBundle($form) {
    if (!$form || !$form.length) {
        return null;
    }
    let bundle = $form.data("randomDataBundle");
    if (bundle && typeof bundle === "object") {
        return bundle;
    }

    const formAction = String($form.attr("data-action") || "").toLowerCase();
    const formId = String($form.attr("id") || "").toLowerCase();
    const randomInt = (min, max) => Math.floor(Math.random() * (max - min + 1)) + min;
    const randomStr = (len) => {
        const chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
        let out = "";
        for (let i = 0; i < len; i++) out += chars.charAt(Math.floor(Math.random() * chars.length));
        return out;
    };
    const randomPick = (items) => items[randomInt(0, items.length - 1)];
    /** Prefer a different item than last time (reduces repeat streaks when shuffling). */

    // Shared cryptographic samples used by multiple crypto modules.
    const sample = {
        jwtSecret: "your-256-bit-secret",
        jwtToken: "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiaWF0IjoxNTE2MjM5MDIyfQ.SflKxwRJSMeKKF2QT4fwpMeJf36POk6yJV_adQssw5c",
        jwtPayload: JSON.stringify({
            sub: "1234567890",
            name: "John Doe",
            iat: 1516239022
        }, null, 2),
        jwtHeader: JSON.stringify({
            typ: "JWT",
            alg: "HS256"
        }, null, 2),
        privatePem: "-----BEGIN PRIVATE KEY-----\nMIICdwIBADANBgkqhkiG9w0BAQEFAASCAmEwggJdAgEAAoGBAPAiPsgyjDSODZlQ\nwn753DTvSplSqnzGcqP3pO20yECSlxXO0wM0wsMe6IxH6w5v2ydKd6ZmV0Y4TurD\nBHGtMeqbiRfoSAiJJvORiEZFI7hJEbEKewoErBeFALimkB0HPF+TNW9uMd1/Ok1q\nuIpK3E17lkatAHzdKzlWh/WRD9tdAgMBAAECgYA7/vJcpnRtNQikw460lsyz1Q14\nXTUHU7WUzezBDyfxKi7hXflOlcILag+D7PwHcV755Bsc0fkALFVbRjo4BKOxlCGh\nBTviXkJQpXdebdxnrfGpzcjVK6HC37Zakc92B/3Cx7I8rVr9zGnbSu5MUwwyxn4R\n0Dvy+10BNP/i6SOUaQJBAPhViJT+FlX2d0anU+P6DbZIhAH3VpzHSCYAhoDTzVnq\noVAbNvh9Pgh0Fn0wcwGPsv5AI+zIL0TvDCOdFhB/ltsCQQD3i+iFxjc1ZhkMPxmC\nW4QzZOZfrsZQ/YE05LS5e0YmBW9A7dAHBKdaYyigJaLhulRXnNyUClmM2nOl6Hd8\nNaAnAkEAyhRASo3g+x7OvM3Y9EE8+0JTOY5eCsIXseTnjtnL1wmZLyiWOOshmZtt\n2X2deH3I+CCVm07jOEMWK7zegZpx1QJBAMx6ljTCWeJTFsel67VhUR9+7kkFPq2x\n6aO+c4ZvTK+ld5PDnT3e2zpvhCRdUmFxH7BLU20562TNIhBeqSxBw6sCQBEMwBcj\nn+Brt59NhqVGg9tEZYLkmbs7LRGFuGPT4JfLk1RmU4s1mi6oCptQjgO+24c+Y6op\n23uswjXgpji6Yh8=\n-----END PRIVATE KEY-----",
        publicPem: "-----BEGIN PUBLIC KEY-----\nMIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDwIj7IMow0jg2ZUMJ++dw070qZ\nUqp8xnKj96TttMhAkpcVztMDNMLDHuiMR+sOb9snSnemZldGOE7qwwRxrTHqm4kX\n6EgIiSbzkYhGRSO4SRGxCnsKBKwXhQC4ppAdBzxfkzVvbjHdfzpNariKStxNe5ZG\nrQB83Ss5Vof1kQ/bXQIDAQAB\n-----END PUBLIC KEY-----",
        opensshPublic: "ssh-rsa AAAAB3NzaC1yc2EAAAADAQABAAAAgQDwIj7IMow0jg2ZUMJ++dw070qZUqp8xnKj96TttMhAkpcVztMDNMLDHuiMR+sOb9snSnemZldGOE7qwwRxrTHqm4kX6EgIiSbzkYhGRSO4SRGxCnsKBKwXhQC4ppAdBzxfkzVvbjHdfzpNariKStxNe5ZGrQB83Ss5Vof1kQ/bXQ==",
        signatureB64: "EcbiXUIHfKBIw83cJb/KA2jrNeSpD3IgO2KBvb2McXvQgWN9J/xBxqNcjbqyratl7pKzI+PhSWVEHVBoCTDf4X4HIV5VReCRmaI6/cAqn09mNvyS9+6DVQlRbruWJUqa6+gMkKSCGwVGpdOVS1fnXGDVewVwR1/MjIG1jn6S5VU=",
        signMessage: "sample message for signature"
    };
    const regexScenarios = [
        {
            pattern: "([a-z0-9._%+-]+)@([a-z0-9.-]+\\.[a-z]{2,})",
            testString: "Contact alice.smith@example.com or dev-team@test.org for support.",
            replacement: "$1 at $2"
        },
        {
            pattern: "(\\d{4})-(\\d{2})-(\\d{2})",
            testString: "Release dates: 2026-04-02, 2025-12-18, and 2024-07-09.",
            replacement: "$3/$2/$1"
        },
        {
            pattern: "\\b([A-Z][a-z]+)\\s+([A-Z][a-z]+)\\b",
            testString: "Assigned to John Smith, Jane Miller, and Robert Brown.",
            replacement: "$2, $1"
        },
        {
            pattern: "\\b(item)-(\\d{3})\\b",
            testString: "Queued item-104, item-287, and item-931 for review.",
            replacement: "$1#$2"
        }
    ];

    bundle = {};
    if (formAction === "jwt" || formId === "jwtform") {
        bundle = {
            kind: "jwt",
            jwtSecret: sample.jwtSecret,
            jwtToken: sample.jwtToken,
            jwtPayload: sample.jwtPayload,
            jwtHeader: sample.jwtHeader
        };
    } else if (formAction === "keypair_sign_verify") {
        bundle = {
            kind: "keypair_sign_verify",
            keypairMessage: sample.signMessage,
            keypairPrivatePem: sample.privatePem,
            keypairPublicPem: sample.publicPem,
            keypairSignatureB64: sample.signatureB64
        };
    } else if (formAction === "ssh_key_verify") {
        bundle = {
            kind: "ssh_key_verify",
            verifyPublicPem: sample.publicPem,
            verifyPublicOpenSsh: sample.opensshPublic + " random-check@" + randomStr(6).toLowerCase(),
            verifyPrivatePem: sample.privatePem
        };
    } else if (formAction === "pem_openssh_convert") {
        bundle = {
            kind: "pem_openssh_convert",
            publicPem: sample.publicPem,
            opensshPublic: sample.opensshPublic + " generated-by-phprand",
            sshComment: "generated-by-phprand-" + randomStr(4).toLowerCase()
        };
    } else if (formId === "range2cidr") {
        const a = randomInt(10, 172);
        const b = randomInt(0, 255);
        const c = randomInt(0, 255);
        bundle = {
            kind: "range2cidr",
            startIp: a + "." + b + "." + c + ".0",
            endIp: a + "." + b + "." + c + ".255"
        };
    } else if (formId === "subnetmask") {
        const a = randomInt(10, 172);
        const b = randomInt(0, 255);
        const c = randomInt(0, 255);
        bundle = {
            kind: "subnetmask",
            ip: a + "." + b + "." + c + "." + randomInt(2, 253),
            subnet: "255.255.255.0"
        };
    } else if (formAction === "numgen" || formId === "numgen") {
        const from = randomInt(1, 500);
        const to = randomInt(from + 1, from + 1000);
        const minDigits = randomInt(1, 4);
        const maxDigits = randomInt(minDigits, 8);
        bundle = {
            kind: "numgen",
            from: String(from),
            to: String(to),
            minDigits: String(minDigits),
            maxDigits: String(maxDigits),
            fixedDigits: String(randomInt(minDigits, maxDigits)),
            qty: String(randomInt(1, 32)),
            seed: randomStr(12)
        };
    } else if (formAction === "regex" || formId === "regexform") {
        const regexSample = randomPick(regexScenarios);
        bundle = {
            kind: "regex",
            regexPattern: regexSample.pattern,
            regexTestString: regexSample.testString,
            regexReplacement: regexSample.replacement
        };
    } else if (formAction === "crontab" || formId === "crontabform") {
        const cronSample = randomPickAvoidRepeatFromForm($form, CRONTAB_RANDOM_SCENARIOS, "crontabScenario");
        bundle = {
            kind: "crontab",
            cronExpression: cronSample.expression,
            cronTimezone: cronSample.timezone
        };
    } else if (formAction === "shellcheck" || formId === "shellcheckform") {
        const shellcheckSample = randomPickAvoidRepeatFromForm($form, SHELLCHECK_RANDOM_SCENARIOS, "shellcheckScenario");
        bundle = {
            kind: "shellcheck",
            shellcheckFilename: shellcheckSample.filename,
            shellcheckShell: shellcheckSample.shell,
            shellcheckScript: shellcheckSample.script
        };
    } else if (formAction === "syntax_validate" || formId === "syntaxvalidateform") {
        const svSample = randomPickAvoidRepeatFromForm($form, SYNTAX_VALIDATE_SCENARIOS, "syntaxValidateScenario");
        if (svSample) {
            bundle = {
                kind: "syntax_validate",
                syntaxValidateKind: svSample.kind,
                syntaxValidateInput: svSample.content
            };
        }
    }

    $form.data("randomDataBundle", bundle);
    return bundle;
}

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
 * - String tools: Short lorem ipsum (2–3 sentences for generic textareas), emails
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

    const randomText = (sentences = 3, wordMin = 8, wordMax = 15) => {
        const words = ['lorem', 'ipsum', 'dolor', 'sit', 'amet', 'consectetur', 'adipiscing', 'elit', 
                      'sed', 'do', 'eiusmod', 'tempor', 'incididunt', 'ut', 'labore', 'et', 'dolore',
                      'magna', 'aliqua', 'enim', 'ad', 'minim', 'veniam', 'quis', 'nostrud', 'exercitation'];
        let result = [];
        for (let i = 0; i < sentences; i++) {
            let sentence = [];
            const wordCount = randomInt(wordMin, wordMax);
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
    let inputName = '';
    let inputId = '';
    let $form = null;
    if ($input && $input.length) {
        $form = $input.closest('form');
        if ($form.length) {
            formAction = ($form.attr('data-action') || '').toLowerCase();
            formId = ($form.attr('id') || '').toLowerCase();
        }
        inputName = ($input.attr("name") || "").toLowerCase();
        inputId = ($input.attr("id") || "").toLowerCase();
    }
    if ($form && $form.length) {
        if (formAction === "shellcheck" || formId === "shellcheckform") {
            if (inputName === "shellcheck_script" || inputName === "shellcheck_filename") {
                const sample = randomPickAvoidRepeatFromForm($form, SHELLCHECK_RANDOM_SCENARIOS, "shellcheckScenario");
                if (sample) {
                    return inputName === "shellcheck_script" ? sample.script : sample.filename;
                }
            }
        }
        if (formAction === "crontab" || formId === "crontabform") {
            if (inputName === "cron_expression" || inputName === "cron_timezone") {
                const sample = randomPickAvoidRepeatFromForm($form, CRONTAB_RANDOM_SCENARIOS, "crontabScenario");
                if (sample) {
                    return inputName === "cron_expression" ? sample.expression : sample.timezone;
                }
            }
        }
        if (formAction === "syntax_validate" || formId === "syntaxvalidateform") {
            if (inputName === "syntax_validate_kind") {
                const picked = randomPickAvoidRepeatFromForm($form, SYNTAX_VALIDATE_KIND_OPTIONS, "syntaxValidateKindOnly");
                return picked ? picked.kind : "json";
            }
            if (inputName === "syntax_validate_input") {
                const kind = String($form.find("[name='syntax_validate_kind']").val() || "json").toLowerCase();
                const pool = SYNTAX_VALIDATE_SCENARIOS.filter((s) => s.kind === kind);
                const usePool = pool.length > 0 ? pool : SYNTAX_VALIDATE_SCENARIOS;
                const sample = randomPickAvoidRepeatFromForm($form, usePool, "syntaxValidateContent_" + kind);
                return sample ? sample.content : "";
            }
        }
    }
    const bundle = randomDataGetCompatibleFormBundle($form);
    // Strict compatibility generators for modules that require related fields.
    if (bundle && bundle.kind === "jwt") {
        if (inputName === "jwt_secret") return bundle.jwtSecret;
        if (inputName === "jwt_token") return bundle.jwtToken;
        if (inputName === "jwt_payload") return bundle.jwtPayload;
        if (inputName === "jwt_header") return bundle.jwtHeader;
    }

    if (bundle && bundle.kind === "keypair_sign_verify") {
        if (inputName === "keypair_message") return bundle.keypairMessage;
        if (inputName === "keypair_private_pem") return bundle.keypairPrivatePem;
        if (inputName === "keypair_public_pem") return bundle.keypairPublicPem;
        if (inputName === "keypair_signature_b64") return bundle.keypairSignatureB64;
        if (inputName === "keypair_private_passphrase") return "";
    }

    if (bundle && bundle.kind === "ssh_key_verify") {
        if (inputName === "verify_private_pem") return bundle.verifyPrivatePem;
        if (inputName === "verify_public_input") {
            const mode = String(($form.find("[name='verify_public_format']").val() || "auto")).toLowerCase();
            return mode === "pem" ? bundle.verifyPublicPem : bundle.verifyPublicOpenSsh;
        }
        if (inputName === "verify_private_passphrase") return "";
    }

    if (bundle && bundle.kind === "pem_openssh_convert") {
        if (inputName === "public_pem") return bundle.publicPem;
        if (inputName === "openssh_public") return bundle.opensshPublic;
        if (inputName === "ssh_comment") return bundle.sshComment;
    }

    if (bundle && bundle.kind === "range2cidr") {
        if (inputName === "startip" || inputId.includes("start")) return bundle.startIp;
        if (inputName === "endip" || inputId.includes("end")) return bundle.endIp;
    }

    if (bundle && bundle.kind === "subnetmask") {
        if (inputName === "ip") return bundle.ip;
        if (inputName === "subnet") return bundle.subnet;
    }

    if (bundle && bundle.kind === "numgen") {
        if (inputName === "numgenfrom") return bundle.from;
        if (inputName === "numgento") return bundle.to;
        if (inputName === "numgenmindig") return bundle.minDigits;
        if (inputName === "numgenmaxdig") return bundle.maxDigits;
        if (inputName === "numgendigits") return bundle.fixedDigits;
        if (inputName === "numgenqty") return bundle.qty;
        if (inputName === "numgenseed") return bundle.seed;
    }

    if (bundle && bundle.kind === "regex") {
        if (inputName === "pattern") return bundle.regexPattern;
        if (inputName === "teststring") return bundle.regexTestString;
        if (inputName === "replacement") return bundle.regexReplacement;
    }

    if (bundle && bundle.kind === "syntax_validate") {
        if (inputName === "syntax_validate_input") return bundle.syntaxValidateInput;
    }

    // Detect what type of data to generate based on context
    const placeholderLower = placeholder.toLowerCase();
    const typeLower = type.toLowerCase();

    // =====================================================================
    // CONTEXT-AWARE DETECTION BY FORM/MODULE
    // =====================================================================

    if (formAction === "logo_generate" || formId === "logogeneratorform") {
        if (type === "textarea" && inputName === "logo_text") {
            const brands = [
                "Acme Co", "Northwind", "Harbor Labs", "Atlas Works", "Rand Studio",
                "Cedar & Co.", "Pixel Foundry", "Blue Oak", "Signal Nine", "Kestrel"
            ];
            const pick = brands[randomInt(0, brands.length - 1)];
            return pick + (Math.random() > 0.45 ? "\n" + randomText(randomInt(1, 2), 3, 8) : "");
        }
    }

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

    // Serialization — sample matches selected output format (JSON / YAML / XML)
    if (formAction === "serialization" || formId === "serializationform") {
        if (inputName === "input" || inputId === "serializationinput") {
            const t = String(($form && $form.find("[name='type']").val()) || "JSON").toUpperCase();
            if (t === "YAML") {
                return randomYAML();
            }
            if (t === "XML") {
                return randomXML();
            }
            return randomJSON();
        }
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
        if ($input && $input.length) {
            const minAttr = $input.attr("min");
            const maxAttr = $input.attr("max");
            const hasMin = minAttr !== undefined && minAttr !== "";
            const hasMax = maxAttr !== undefined && maxAttr !== "";
            if (hasMin || hasMax) {
                const min = hasMin ? parseInt(minAttr, 10) : 0;
                const max = hasMax ? parseInt(maxAttr, 10) : (min + 1000);
                const safeMax = max >= min ? max : min;
                return randomInt(min, safeMax).toString();
            }
        }
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

    /* Avoid "multiple" → false positive on substring "ip" (e.g. logo generator textarea). */
    const looksLikeIpPlaceholder = /\bipv6\b/.test(placeholderLower)
        || /\bipv4\b/.test(placeholderLower)
        || /\b(ip\s+address|ip-address|host\s*ip|enter\s+ip)\b/.test(placeholderLower)
        || /(^|[^a-z0-9])ip([-:\s/]|$)/.test(placeholderLower);
    if (looksLikeIpPlaceholder) {
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
        return randomText(randomInt(2, 3), 5, 10);
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
        'textarea:not([readonly]):not([disabled])',
        '#syntaxValidateKind:not([disabled])',
        '#crontabTimezone:not([disabled])'
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

        if ($input.closest('[data-no-random-buttons]').length > 0) {
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
        const inputType = $input.is('textarea')
            ? 'textarea'
            : ($input.is('select') ? 'select' : $input.attr('type'));
        const placeholder = $input.attr('placeholder') || '';
        const inputId = $input.attr('id') || 'input_' + Math.random().toString(36).substr(2, 9);
        
        if (!$input.attr('id')) {
            $input.attr('id', inputId);
        }

        // Wrap the input if not already wrapped (but not for wheel items - they're already in a flex container)
        if (!isWheelItemInput && !$input.parent().hasClass('input-with-random-btn')) {
            $input.wrap('<div class="input-with-random-btn" style="position: relative; display: flex; gap: 8px; align-items: flex-start;"></div>');
        }

        const inputIdForTitle = $input.attr('id') || '';
        let randomBtnTitle = 'Generate random data';
        if (inputIdForTitle === 'syntaxValidateKind') {
            randomBtnTitle = 'Random language';
        }

        // Create the random button
        const $btn = $('<button>', {
            type: 'button',
            class: 'btn btn-sm btn-outline-secondary random-data-btn',
            title: randomBtnTitle,
            html: '<i class="bi bi-shuffle"></i>',
            css: {
                'flex-shrink': '0',
                'height': $input.is('textarea') ? 'auto' : 'fit-content',
                'align-self': $input.is('textarea') ? 'flex-start' : 'center'
            }
        });

        // Add click handler
        $btn.on('click', function() {
            const $form = $input.closest('form');
            const formAction = (($form.attr('data-action') || '') + '').toLowerCase();
            const randomData = generateRandomData(inputType, placeholder, $input);
            $input.val(randomData).trigger('change').trigger('input');
            if (formAction === 'crontab') {
                const inputName = ($input.attr('name') || '').toLowerCase();
                if (inputName === 'cron_timezone') {
                    const det = document.getElementById('crontabMoreDetails');
                    if (det) {
                        det.open = true;
                    }
                }
            }

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