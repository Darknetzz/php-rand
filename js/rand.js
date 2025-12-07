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

/* ===================================================================== */
/*                           FUNCTION: navigate                          */
/* ===================================================================== */
function navigate(to) {

    console.log("[navigate] Navigating to: " + to)

    // Reset all nav links
    var navLinks = $(".link.nav-link");
    navLinks.prop("class", "link nav-link");

    // Set this nav link as active
    var navLink = $(`.link.nav-link[href='${to}']`);
    navLink.prop("class", "link nav-link link-success active");

    $(".content").hide();
    $(to).fadeIn();

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
    // hljs.highlightAll();
    codeInput.registerTemplate("default", codeInput.templates.hljs(hljs, [
        new codeInput.plugins.Autodetect(),
        new codeInput.plugins.Indent(true, 2) // 2 spaces indentation
    ] /* Array of plugins (see below) */ ));
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
    $(".form").submit(function(e) {
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

        // Send form
        var url = form.attr('action');
        var responseObj = form.find(".responseDiv");

        var btnName = $("button[clicked=true]").prop("name");
        var btnValue = $("button[clicked=true]").val();

        var serializeForm = form.serialize() + "&" + btnName + "=" + btnValue;
        console.log("[submitForm] Sending form: " + serializeForm);

        if (responseObj.length == 0) {
            console.log("[submitForm] No response object found.");
            $("#error").html("<br><div class='alert alert-danger'>No response object found.</div>");
            $("#error").show();
            return;
        } else {
            $("#error").hide();
        }

        // AJAX call

        $.ajax({
            type: "POST",
            url: url,
            data: serializeForm, // serializes the form's elements.
            beforeSend: function() {
                showData(responseObj, '<div class="text-center py-5"><div class="spinner-border text-primary mb-3" role="status" style="width: 3rem; height: 3rem;"><span class="visually-hidden">Loading...</span></div><p class="text-muted">Generating...</p></div>'); // show loading spinner
            },
            error: function(data) {
                console.log(data);
                if (responsetype == "html") {
                    showData(responseObj, "<div class='alert alert-danger'>Error: " + data
                        .statusText + "</div>");
                } else {
                    showData(responseObj, "Error: " + data.statusText);
                }
            },
            success: function(data) {
                showData(responseObj, data);
            }
        });
        randomizeDice();
    });

    /* ===================================================================== */
    /*                      Navigation (and hash check)                      */
    /* ===================================================================== */
    if (window.location.hash != '' && window.location.hash != undefined) {
        // Hash is set on page load, so navigate to it
        var hash = window.location.hash;
        var afterhash = hash.replace('#', '');
        navigate(hash);
        console.log("Hash detected: " + hash + ", setting nav" + afterhash + " parent as the active tab.");
    } else {
        navigate("#dashboard");
    }

    /* ===================================================================== */
    /*                               Click link                              */
    /* ===================================================================== */
    $(".link").click(function() {
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


    // turn off all autocomplete
    $(".form-control").prop("autocomplete", "off");
    $("input[type=checkbox]").addClass("form-check-input");


    /* ===================================================================== */
    /*                            Changelog modal                            */
    /* ===================================================================== */
    var changelog = $("#changelogMarkdown");
    
    // Get the raw markdown text and decode HTML entities so raw <details> tags are visible to marked
    let markdownText = changelog.text();
    const decodeHtml = (str) => $('<textarea/>').html(str).text();
    markdownText = decodeHtml(markdownText);

    // Parse with marked (GFM with line breaks)
    marked.setOptions({
        breaks: true,
        gfm: true
    });

    changelog.html(marked.parse(markdownText));

    /* ===================================================================== */
    /*                      Add Random Data Buttons                          */
    /* ===================================================================== */
    addRandomDataButtons();

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
function addRandomDataButtons() {
    // Find all text inputs, number inputs, and textareas that don't already have a random button
    const selectors = [
        'input[type="text"]:not([readonly]):not([disabled])',
        'input[type="number"]:not([readonly]):not([disabled])',
        'textarea:not([readonly]):not([disabled])'
    ];

    $(selectors.join(',')).each(function() {
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

        // Get input details
        const inputType = $input.is('textarea') ? 'textarea' : $input.attr('type');
        const placeholder = $input.attr('placeholder') || '';
        const inputId = $input.attr('id') || 'input_' + Math.random().toString(36).substr(2, 9);
        
        if (!$input.attr('id')) {
            $input.attr('id', inputId);
        }

        // Wrap the input if not already wrapped
        if (!$input.parent().hasClass('input-with-random-btn')) {
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