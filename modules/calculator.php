<div id="calculator" class="content">
    <div class="card card-primary calc-card">
        <div class="calc-header">
            <span class="calc-mode">Standard</span>
        </div>
        <div class="card-body calc-body">
            <form class="calc-form" action="gen.php" method="POST" id="calc" data-action="calc">
                <input type="hidden" name="calcinput" id="calcInput" value="">
                <div class="calc-display-wrap">
                    <div class="calc-expression" id="calcExpression" aria-hidden="true"></div>
                    <div class="calc-display" id="calcDisplay" role="status" aria-live="polite">0</div>
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

    container.querySelectorAll('.calc-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const action = this.getAttribute('data-action');
            if (action) handleAction(action);
        });
    });

    document.addEventListener('keydown', function(e) {
        if (!container.closest('.content') || getComputedStyle(container).display === 'none') return;
        const key = e.key;
        if (key >= '0' && key <= '9') { handleAction(key); e.preventDefault(); return; }
        if (key === '.') { handleAction('.'); e.preventDefault(); return; }
        if (key === 'Backspace') { handleAction('backspace'); e.preventDefault(); return; }
        if (key === 'Escape') { handleAction('C'); e.preventDefault(); return; }
        const opKeys = { '/': '÷', '*': '×', '-': '−', '+': '+' };
        if (opKeys[key]) { handleAction(opKeys[key]); e.preventDefault(); return; }
        if (key === 'Enter' || key === '=') { handleAction('='); e.preventDefault(); return; }
    });

    updateDisplay();
})();
</script>
