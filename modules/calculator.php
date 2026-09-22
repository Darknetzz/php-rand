<div id="calculator" class="content">
    <div class="card card-primary calc-card">
        <div class="calc-header">
            <span class="calc-mode">Standard</span>
        </div>
        <div class="card-body calc-body">
            <form class="calc-form" action="gen.php" method="POST" id="calc" data-action="calc">
                <input type="hidden" name="calcinput" id="calcInput" value="">
                <div class="calc-display-wrap" title="Ctrl/Cmd+C copy · Ctrl/Cmd+V paste · click result to copy">
                    <div class="calc-expression" id="calcExpression" aria-hidden="true"></div>
                    <div class="calc-display" id="calcDisplay" role="status" aria-live="polite" tabindex="0" title="Click to copy">0</div>
                </div>
                <div class="calc-memory-row">
                    <button type="button" class="calc-btn calc-mem" data-action="MC" title="Memory Clear">MC</button>
                    <button type="button" class="calc-btn calc-mem" data-action="MR" title="Memory Recall">MR</button>
                    <button type="button" class="calc-btn calc-mem" data-action="M+" title="Memory Add">M+</button>
                    <button type="button" class="calc-btn calc-mem" data-action="M-" title="Memory Subtract">M-</button>
                    <button type="button" class="calc-btn calc-mem" data-action="MS" title="Memory Store">MS</button>
                </div>
                <div class="calc-keypad">
                    <button type="button" class="calc-btn" data-action="%">%</button>
                    <button type="button" class="calc-btn" data-action="CE">CE</button>
                    <button type="button" class="calc-btn" data-action="C">C</button>
                    <button type="button" class="calc-btn" data-action="backspace" title="Backspace"><i class="bi bi-backspace"></i></button>
                    <button type="button" class="calc-btn" data-action="1/x">1/x</button>
                    <button type="button" class="calc-btn" data-action="x²">x²</button>
                    <button type="button" class="calc-btn" data-action="√">²√x</button>
                    <button type="button" class="calc-btn calc-op" data-action="÷">÷</button>
                    <button type="button" class="calc-btn calc-num" data-action="7">7</button>
                    <button type="button" class="calc-btn calc-num" data-action="8">8</button>
                    <button type="button" class="calc-btn calc-num" data-action="9">9</button>
                    <button type="button" class="calc-btn calc-op" data-action="×">×</button>
                    <button type="button" class="calc-btn calc-num" data-action="4">4</button>
                    <button type="button" class="calc-btn calc-num" data-action="5">5</button>
                    <button type="button" class="calc-btn calc-num" data-action="6">6</button>
                    <button type="button" class="calc-btn calc-op" data-action="−">−</button>
                    <button type="button" class="calc-btn calc-num" data-action="1">1</button>
                    <button type="button" class="calc-btn calc-num" data-action="2">2</button>
                    <button type="button" class="calc-btn calc-num" data-action="3">3</button>
                    <button type="button" class="calc-btn calc-op" data-action="+">+</button>
                    <button type="button" class="calc-btn" data-action="±">+/-</button>
                    <button type="button" class="calc-btn calc-num" data-action="0">0</button>
                    <button type="button" class="calc-btn" data-action=".">.</button>
                    <button type="button" class="calc-btn calc-equals" data-action="=">=</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
(function() {
    const displayEl = document.getElementById('calcDisplay');
    const expressionEl = document.getElementById('calcExpression');
    const inputEl = document.getElementById('calcInput');
    const container = document.getElementById('calculator');
    if (!displayEl || !container) return;

    let display = '0';
    let expression = '';
    let previousValue = null;
    let pendingOp = null;
    let memory = 0;
    let justComputed = false;

    const OP_MAP = { '÷': '/', '×': '*', '−': '-', '+': '+' };

    function updateDisplay() {
        displayEl.textContent = formatDisplay(display);
        expressionEl.textContent = expression || '';
        if (inputEl) inputEl.value = expression || display;
    }

    function formatDisplay(s) {
        if (s === '' || s === '-') return s || '0';
        const n = parseFloat(s);
        if (isNaN(n)) return s;
        if (Math.abs(n) >= 1e12 || (Math.abs(n) < 1e-6 && n !== 0)) return n.toExponential(6);
        const fixed = Number(n).toFixed(10).replace(/\.?0+$/, '');
        return fixed;
    }

    function getDisplayNum() {
        const n = parseFloat(display);
        return isNaN(n) ? 0 : n;
    }

    function setDisplay(v) {
        if (typeof v === 'number') {
            if (!Number.isFinite(v)) display = 'Error';
            else display = String(v);
        } else {
            display = String(v);
        }
        justComputed = true;
    }

    function doBinary(op, a, b) {
        const o = OP_MAP[op] || op;
        if (o === '/') return b === 0 ? NaN : a / b;
        if (o === '*') return a * b;
        if (o === '-') return a - b;
        if (o === '+') return a + b;
        return NaN;
    }

    function computeResult() {
        if (previousValue === null || pendingOp === null) return getDisplayNum();
        const a = previousValue;
        const b = getDisplayNum();
        const result = doBinary(pendingOp, a, b);
        previousValue = null;
        pendingOp = null;
        expression = '';
        return result;
    }

    function applyUnary(fn) {
        const x = getDisplayNum();
        setDisplay(fn(x));
        updateDisplay();
    }

    function handleAction(action) {
        const numActions = ['0','1','2','3','4','5','6','7','8','9'];
        const opActions = ['÷','×','−','+'];

        if (numActions.includes(action)) {
            if (justComputed) {
                display = action;
                expression = (pendingOp ? (expression || '') : '') + action;
                justComputed = false;
            } else if (display === '0' && action !== '.') {
                display = action;
                expression = (expression || '') + action;
            } else if (display !== '0') {
                display += action;
                expression += action;
            }
        } else if (action === '.') {
            if (justComputed) { display = '0.'; expression = (pendingOp ? (expression || '') : '') + '0.'; justComputed = false; }
            else if (!display.includes('.')) { display += '.'; expression += '.'; }
        } else if (action === '±') {
            const n = getDisplayNum();
            setDisplay(n === 0 ? '0' : -n);
            if (expression && expression !== display) expression = expression.replace(/[-+]?[\d.]+$/, String(display));
            else expression = display;
        } else if (action === 'CE') {
            display = '0';
            expression = expression.replace(/[\d.]+$/, '') || '';
            justComputed = false;
        } else if (action === 'C') {
            display = '0';
            expression = '';
            previousValue = null;
            pendingOp = null;
            justComputed = false;
        } else if (action === 'backspace') {
            if (display.length <= 1) { display = '0'; expression = expression.slice(0, -1) || ''; }
            else { display = display.slice(0, -1); expression = expression.slice(0, -1); }
            justComputed = false;
        } else if (action === '%') {
            const x = getDisplayNum();
            if (pendingOp === '+' || pendingOp === '−') {
                const pct = previousValue * (x / 100);
                setDisplay(pendingOp === '+' ? previousValue + pct : previousValue - pct);
                expression = '';
                previousValue = null;
                pendingOp = null;
            } else {
                setDisplay(x / 100);
                expression = expression.replace(/[\d.]+$/, display) || display;
            }
        } else if (action === '1/x') {
            const x = getDisplayNum();
            if (x === 0) setDisplay('Error');
            else setDisplay(1 / x);
            expression = '';
        } else if (action === 'x²') {
            const x = getDisplayNum();
            setDisplay(x * x);
            expression = '';
        } else if (action === '√') {
            const x = getDisplayNum();
            if (x < 0) setDisplay('Error');
            else setDisplay(Math.sqrt(x));
            expression = '';
        } else if (opActions.includes(action)) {
            if (pendingOp !== null) {
                const result = computeResult();
                if (!Number.isFinite(result)) { setDisplay('Error'); updateDisplay(); return; }
                display = String(result);
            }
            previousValue = getDisplayNum();
            pendingOp = action;
            expression = (expression || display) + ' ' + action + ' ';
            justComputed = true;
        } else if (action === '=') {
            const result = computeResult();
            if (!Number.isFinite(result)) { setDisplay('Error'); updateDisplay(); return; }
            setDisplay(result);
        } else if (action === 'MC') {
            memory = 0;
        } else if (action === 'MR') {
            display = String(memory);
            expression = '';
            justComputed = true;
        } else if (action === 'M+') {
            memory += getDisplayNum();
        } else if (action === 'M-') {
            memory -= getDisplayNum();
        } else if (action === 'MS') {
            memory = getDisplayNum();
        }
        updateDisplay();
    }

    /** Safe math eval (mirrors includes/functions.php safeMathEval). No eval(). */
    function safeMathEval(expr) {
        expr = String(expr).replace(/\s+/g, '');
        if (!expr || expr.length > 1000) return null;

        const tokens = [];
        let number = '';
        for (let i = 0; i < expr.length; i++) {
            const ch = expr[i];
            if ((ch >= '0' && ch <= '9') || ch === '.') {
                number += ch;
            } else if (ch === '+' || ch === '-' || ch === '*' || ch === '/') {
                if (number !== '') {
                    if (!isFinite(Number(number))) return null;
                    tokens.push(Number(number));
                    number = '';
                }
                tokens.push(ch);
            } else {
                return null;
            }
        }
        if (number !== '') {
            if (!isFinite(Number(number))) return null;
            tokens.push(Number(number));
        }
        if (!tokens.length) return null;

        if (tokens[0] === '-' || tokens[0] === '+') {
            if (tokens.length < 2 || typeof tokens[1] !== 'number') return null;
            if (tokens[0] === '-') tokens[1] = -tokens[1];
            tokens.shift();
        }

        const processed = [];
        let i = 0;
        while (i < tokens.length) {
            if (typeof tokens[i] === 'number') {
                processed.push(tokens[i]);
                i++;
            } else if (tokens[i] === '*' || tokens[i] === '/') {
                if (!processed.length || typeof processed[processed.length - 1] !== 'number') return null;
                const left = processed.pop();
                if (i + 1 >= tokens.length || typeof tokens[i + 1] !== 'number') return null;
                const right = tokens[i + 1];
                if (tokens[i] === '*') processed.push(left * right);
                else {
                    if (right === 0) return null;
                    processed.push(left / right);
                }
                i += 2;
            } else {
                processed.push(tokens[i]);
                i++;
            }
        }

        if (!processed.length || typeof processed[0] !== 'number') return null;
        let result = processed[0];
        for (let j = 1; j < processed.length; j += 2) {
            if (j + 1 >= processed.length) return null;
            const op = processed[j];
            const val = processed[j + 1];
            if (typeof val !== 'number') return null;
            if (op === '+') result += val;
            else if (op === '-') result -= val;
            else return null;
        }
        return Number.isFinite(result) ? result : null;
    }

    function normalizePasteExpr(text) {
        return String(text || '')
            .trim()
            .replace(/,/g, '')
            .replace(/[×xX]/g, '*')
            .replace(/[÷]/g, '/')
            .replace(/[−–—]/g, '-')
            .replace(/\s+/g, '');
    }

    function isCalcVisible() {
        return !!(container.closest('.content') && getComputedStyle(container).display !== 'none');
    }

    function isTypingTarget(el) {
        if (!el || el === displayEl) return false;
        const tag = (el.tagName || '').toUpperCase();
        return tag === 'INPUT' || tag === 'TEXTAREA' || tag === 'SELECT' || el.isContentEditable;
    }

    function getCopyText() {
        if (display === 'Error') return '';
        return String(display);
    }

    function flashCopied() {
        displayEl.classList.add('calc-copied');
        clearTimeout(flashCopied._t);
        flashCopied._t = setTimeout(function() {
            displayEl.classList.remove('calc-copied');
        }, 900);
    }

    function copyDisplayValue() {
        const text = getCopyText();
        if (text === '') return Promise.resolve(false);
        if (navigator.clipboard && navigator.clipboard.writeText) {
            return navigator.clipboard.writeText(text).then(function() {
                flashCopied();
                return true;
            }).catch(function() {
                return fallbackCopyText(text);
            });
        }
        return Promise.resolve(fallbackCopyText(text));
    }

    function fallbackCopyText(text) {
        const ta = document.createElement('textarea');
        ta.value = text;
        ta.setAttribute('readonly', '');
        ta.style.position = 'fixed';
        ta.style.top = '-1000px';
        document.body.appendChild(ta);
        ta.select();
        let ok = false;
        try { ok = document.execCommand('copy'); } catch (err) { ok = false; }
        document.body.removeChild(ta);
        if (ok) flashCopied();
        return ok;
    }

    function applyPastedText(raw) {
        const normalized = normalizePasteExpr(raw);
        if (!normalized) return;

        // Plain number (optionally scientific)
        if (/^-?\d*\.?\d+(e[+-]?\d+)?$/i.test(normalized)) {
            display = normalized;
            expression = '';
            previousValue = null;
            pendingOp = null;
            justComputed = true;
            updateDisplay();
            return;
        }

        const result = safeMathEval(normalized);
        if (result === null) {
            setDisplay('Error');
            expression = '';
            previousValue = null;
            pendingOp = null;
            updateDisplay();
            return;
        }

        expression = normalized.replace(/\*/g, '×').replace(/\//g, '÷').replace(/-/g, '−');
        setDisplay(result);
        previousValue = null;
        pendingOp = null;
        updateDisplay();
    }

    container.querySelectorAll('.calc-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const action = this.getAttribute('data-action');
            if (action) handleAction(action);
        });
    });

    let pointerDown = null;
    displayEl.addEventListener('pointerdown', function(e) {
        pointerDown = { x: e.clientX, y: e.clientY };
    });
    displayEl.addEventListener('click', function(e) {
        if (pointerDown) {
            const dx = e.clientX - pointerDown.x;
            const dy = e.clientY - pointerDown.y;
            pointerDown = null;
            if ((dx * dx + dy * dy) > 25) return; // drag-select: don't steal copy
        }
        const sel = window.getSelection && window.getSelection();
        if (sel && String(sel) && displayEl.contains(sel.anchorNode)) return;
        copyDisplayValue();
    });

    document.addEventListener('keydown', function(e) {
        if (!isCalcVisible()) return;
        if (isTypingTarget(e.target)) return;

        const mod = e.ctrlKey || e.metaKey;
        if (mod && (e.key === 'c' || e.key === 'C')) {
            const sel = window.getSelection && window.getSelection();
            const selected = sel && String(sel);
            if (selected && container.contains(sel.anchorNode)) return; // native selection copy
            e.preventDefault();
            copyDisplayValue();
            return;
        }
        if (mod) return; // leave Ctrl/Cmd+V to the paste handler; ignore other shortcuts

        const key = e.key;
        if (key >= '0' && key <= '9') { handleAction(key); e.preventDefault(); return; }
        if (key === '.') { handleAction('.'); e.preventDefault(); return; }
        if (key === 'Backspace') { handleAction('backspace'); e.preventDefault(); return; }
        if (key === 'Escape') { handleAction('C'); e.preventDefault(); return; }
        const opKeys = { '/': '÷', '*': '×', '-': '−', '+': '+' };
        if (opKeys[key]) { handleAction(opKeys[key]); e.preventDefault(); return; }
        if (key === 'Enter' || key === '=') { handleAction('='); e.preventDefault(); return; }
    });

    document.addEventListener('paste', function(e) {
        if (!isCalcVisible()) return;
        if (isTypingTarget(e.target)) return;
        const text = (e.clipboardData || window.clipboardData).getData('text');
        if (text == null) return;
        e.preventDefault();
        applyPastedText(text);
    });

    document.addEventListener('copy', function(e) {
        if (!isCalcVisible()) return;
        if (isTypingTarget(e.target)) return;
        const sel = window.getSelection && window.getSelection();
        if (sel && String(sel) && container.contains(sel.anchorNode)) return;
        const text = getCopyText();
        if (!text || !e.clipboardData) return;
        e.clipboardData.setData('text/plain', text);
        e.preventDefault();
        flashCopied();
    });

    updateDisplay();
})();
</script>
