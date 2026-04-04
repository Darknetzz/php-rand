/**
 * Applies saved UI preferences before the rest of the page renders.
 * Loaded synchronously at the top of <body>; exposes window.randUiPrefs for rand.js.
 */
(function () {
    var KEY = "randUiPrefs";
    var DEFAULTS = { theme: "dark", uiScale: 0.92 };
    var SCALE_OPTIONS = [0.8, 0.85, 0.92, 1, 1.08, 1.16];

    function snapScale(s) {
        var best = SCALE_OPTIONS[0];
        for (var i = 0; i < SCALE_OPTIONS.length; i++) {
            if (Math.abs(SCALE_OPTIONS[i] - s) < Math.abs(best - s)) {
                best = SCALE_OPTIONS[i];
            }
        }
        return Math.max(0.75, Math.min(1.25, best));
    }

    function readPrefs() {
        try {
            var p = JSON.parse(localStorage.getItem(KEY) || "{}");
            if (p.theme !== "light") {
                p.theme = "dark";
            }
            var s = parseFloat(p.uiScale, 10);
            if (isNaN(s)) {
                s = DEFAULTS.uiScale;
            }
            p.uiScale = snapScale(s);
            return p;
        } catch (e) {
            return { theme: DEFAULTS.theme, uiScale: DEFAULTS.uiScale };
        }
    }

    function applyNavbarTheme(theme) {
        var nav = document.querySelector("nav.navbar");
        if (!nav) {
            return;
        }
        nav.classList.remove("navbar-dark", "bg-dark", "navbar-light", "bg-white", "border-bottom");
        if (theme === "light") {
            nav.classList.add("navbar-light", "bg-white", "border-bottom");
        } else {
            nav.classList.add("navbar-dark", "bg-dark");
        }
    }

    function apply(p) {
        p = p || readPrefs();
        var doc = document.documentElement;
        doc.setAttribute("data-bs-theme", p.theme);
        doc.style.fontSize = (p.uiScale * 100) + "%";
        if (document.body) {
            document.body.classList.remove("theme-dark", "theme-light");
            document.body.classList.add(p.theme === "light" ? "theme-light" : "theme-dark");
        }
        applyNavbarTheme(p.theme);
    }

    function save(p) {
        p.theme = p.theme === "light" ? "light" : "dark";
        var s = parseFloat(p.uiScale, 10);
        p.uiScale = snapScale(isNaN(s) ? DEFAULTS.uiScale : s);
        localStorage.setItem(KEY, JSON.stringify(p));
        apply(p);
    }

    window.randUiPrefs = {
        KEY: KEY,
        DEFAULTS: DEFAULTS,
        SCALE_OPTIONS: SCALE_OPTIONS.slice(),
        read: readPrefs,
        apply: apply,
        save: save
    };

    apply(readPrefs());
    document.addEventListener("DOMContentLoaded", function () {
        applyNavbarTheme(readPrefs().theme);
    });
})();
