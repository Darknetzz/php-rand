<style>
/*
 The code used here was borrowed from https://github.com/olimorris/spin-the-wheel
*/

.spin_the_wheel_canvas {
    display: inline-block;
    position: relative;
    overflow: hidden;
    width: 100%;
    max-width: 500px;
    padding: 0;
    border: none;
    outline: none;
}

.spin_the_wheel_wheel {
    display: block;
    width: 100%;
    height: auto;
    border: none;
    outline: none;
}

.spin_the_wheel_spinbtn {
    font:
        1.5em/0 "Lato",
        sans-serif;
    user-select: none;
    cursor: pointer;
    display: flex;
    justify-content: center;
    align-items: center;
    position: absolute;
    top: 50%;
    left: 50%;
    width: 30%;
    height: 30%;
    margin: -15%;
    background: #fff;
    color: #fff;
    box-shadow:
        0 0 0 8px currentColor,
        0 0px 15px 5px rgba(0, 0, 0, 0.6);
    border-radius: 50%;
    transition: 0.8s;
}

.spin_the_wheel_spinbtn::after {
    content: "";
    position: absolute;
    top: -17px;
    border: 10px solid transparent;
    border-bottom-color: currentColor;
    border-top: none;
}

.wheelitem-weight {
    width: 72px;
    flex-shrink: 0;
    display: none;
}

.wheel-weights-enabled .wheelitem-weight {
    display: block;
}

.wheel-distribute-evenly-option {
    display: none;
}

.wheel-items-panel.wheel-weights-enabled .wheel-distribute-evenly-option {
    display: block;
}
</style>


<div class="content" id="spin_the_wheel">
    <div class="card">
        <h1 class="card-header mb-3">
            Spin The Wheel
        </h1>

        <div class="card-body">

            <form class="form" method="POST" action="gen.php" id="spinwheel" data-action="spinwheel">

                <div class="row g-4">
                    <!-- Canvas Section -->
                    <div class="col-12 col-lg-6 d-flex justify-content-center align-items-center" style="min-height: 500px;">
                        <div class="spin_the_wheel_canvas">
                            <canvas class="spin_the_wheel_wheel" width="500" height="500" style="display: block; max-width: 100%; height: auto;"></canvas>
                            <button type="button" class="btn btn-success spin_the_wheel_spinbtn">SPIN</button>
                        </div>
                    </div>

                    <!-- Items Management Section -->
                    <div class="col-12 col-lg-6 d-flex flex-column wheel-items-panel">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h4 class="mb-0"><strong>Wheel Items</strong></h4>
                            <div class="d-flex flex-column align-items-end gap-1">
                                <div class="form-check form-switch mb-0">
                                    <input class="form-check-input" type="checkbox" id="wheelUseWeights" value="1">
                                    <label class="form-check-label" for="wheelUseWeights">Use weights</label>
                                </div>
                                <div class="form-check form-switch mb-0 wheel-distribute-evenly-option">
                                    <input class="form-check-input" type="checkbox" id="wheelDistributeEvenly" value="1">
                                    <label class="form-check-label" for="wheelDistributeEvenly" title="Each weight unit becomes its own equal slice on the wheel">Split into slices</label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="wheelitems mb-3" style="overflow-y: auto; max-height: 400px; min-height: 400px; padding-right: 10px; border: 1px solid #dee2e6; border-radius: 0.25rem; padding: 15px; background-color: rgba(0,0,0,0.05);">
                            <div class="input-group mb-3 wheelitem" style="gap: 8px;">
                                <span class="badge bg-primary" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; font-size: 1.1rem;"><strong>1</strong></span>
                                <input type="text" name="wheelitem[0]" class="form-control wheelitem-input" placeholder="Item #1" value="" style="flex: 1;">
                                <input type="number" name="wheelweight[0]" class="form-control wheelitem-weight" min="1" step="1" value="1" title="Weight" aria-label="Weight">
                                <button type="button" class="btn btn-sm btn-outline-danger remove-item" title="Remove item"><?= icon("trash") ?></button>
                            </div>
                            <div class="input-group mb-3 wheelitem" style="gap: 8px;">
                                <span class="badge bg-primary" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; font-size: 1.1rem;"><strong>2</strong></span>
                                <input type="text" name="wheelitem[1]" class="form-control wheelitem-input" placeholder="Item #2" value="" style="flex: 1;">
                                <input type="number" name="wheelweight[1]" class="form-control wheelitem-weight" min="1" step="1" value="1" title="Weight" aria-label="Weight">
                                <button type="button" class="btn btn-sm btn-outline-danger remove-item" title="Remove item"><?= icon("trash") ?></button>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-grid gap-2">
                            <button type="button" class="btn btn-success btn-lg" id="addtowheel"><?= icon("plus-circle") ?> Add Item</button>
                            <button type="button" class="btn btn-danger btn-lg clear"><?= icon("trash") ?> Clear All</button>
                        </div>
                    </div>
                </div>

                <hr>

                <!-- Result Display -->
                <div class="responseDiv" id="spinwheelresponse" style="border: 1px solid #dee2e6; padding: 15px; min-height: 60px; background-color: rgba(0,0,0,0.1); border-radius: 0.25rem; font-family: monospace; text-align: center; font-size: 1.2rem; font-weight: bold;">Result will appear here...</div>

            </form>

        </div>
    </div>
</div>

<script>
/* ===================================================================== */

/*
 The code used here was borrowed from https://github.com/olimorris/spin-the-wheel
*/
let spinthewheel_items = [];
let spinthewheel_sectors = [];

const rand = (m, M) => Math.random() * (M - m) + m;
let tot = 0;
const spinEl = document.querySelector(".spin_the_wheel_spinbtn");
const ctx = document.querySelector(".spin_the_wheel_wheel").getContext("2d");
const dia = ctx.canvas.width;
const rad = dia / 2;
const PI = Math.PI;
const TAU = 2 * PI;
let arc = TAU / spinthewheel_sectors.length;
let sectorStarts = [];
let sectorArcs = [];
let useWeights = false;
let splitIntoSlices = false;
let spinWinnerItemIndex = null;

function weightsEnabled() {
    return document.getElementById("wheelUseWeights")?.checked ?? false;
}

function splitSlicesEnabled() {
    return weightsEnabled() && (document.getElementById("wheelDistributeEvenly")?.checked ?? false);
}

function parseWeight(value) {
    const n = parseInt(value, 10);
    return Number.isFinite(n) && n >= 1 ? n : 1;
}

function buildDisplaySectors(items, useWeightsMode, splitSlices) {
    const sectors = [];
    if (useWeightsMode && splitSlices) {
        const maxWeight = Math.max(...items.map((item) => item.weight), 1);
        for (let round = 0; round < maxWeight; round++) {
            for (const item of items) {
                if (round < item.weight) {
                    sectors.push({
                        label: item.label,
                        color: item.color,
                        text: item.text,
                        itemIndex: item.index,
                        weight: 1,
                    });
                }
            }
        }
        return sectors;
    }
    for (const item of items) {
        sectors.push({
            label: item.label,
            color: item.color,
            text: item.text,
            itemIndex: item.index,
            weight: useWeightsMode ? item.weight : 1,
        });
    }
    return sectors;
}

function rebuildSectorGeometry() {
    const totalWeight = spinthewheel_sectors.reduce((sum, sector) => sum + sector.weight, 0) || 1;
    let cursor = 0;
    sectorStarts = [];
    sectorArcs = spinthewheel_sectors.map((sector) => {
        sectorStarts.push(cursor);
        const slice = (sector.weight / totalWeight) * TAU;
        cursor += slice;
        return slice;
    });
    arc = tot > 0 ? TAU / tot : TAU;
}

function pointerAngle() {
    return ((-(ang % TAU)) + TAU) % TAU;
}

function getIndex() {
    if (useWeights || splitIntoSlices) {
        const pointer = pointerAngle();
        for (let i = 0; i < tot; i++) {
            if (pointer >= sectorStarts[i] && pointer < sectorStarts[i] + sectorArcs[i]) {
                return i;
            }
        }
        return tot - 1;
    }
    return Math.floor(tot - (ang / TAU) * tot) % tot;
}

function itemIndexFromDisplayIndex(displayIndex) {
    return spinthewheel_sectors[displayIndex]?.itemIndex ?? displayIndex;
}

function weightedRandomItemIndex() {
    const totalWeight = spinthewheel_items.reduce((sum, item) => sum + item.weight, 0);
    let roll = Math.random() * totalWeight;
    for (let i = 0; i < spinthewheel_items.length; i++) {
        roll -= spinthewheel_items[i].weight;
        if (roll <= 0) {
            return i;
        }
    }
    return spinthewheel_items.length - 1;
}

function targetAngleForItemIndex(itemIndex) {
    const sliceIndices = [];
    spinthewheel_sectors.forEach((sector, i) => {
        if (sector.itemIndex === itemIndex) {
            sliceIndices.push(i);
        }
    });
    const sectorIndex = sliceIndices[Math.floor(Math.random() * sliceIndices.length)] ?? itemIndex;
    const offset = rand(0.15, 0.85) * sectorArcs[sectorIndex];
    const pointerTarget = sectorStarts[sectorIndex] + offset;
    return ((-(pointerTarget % TAU)) + TAU) % TAU;
}

function wheelItemRowHtml(index, placeholder) {
    return `
        <div class="input-group mb-3 wheelitem" style="gap: 8px;">
            <span class="badge bg-primary" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; font-size: 1.1rem;"><strong>${index}</strong></span>
            <input type="text" name="wheelitem[]" class="form-control wheelitem-input" placeholder="${placeholder}" value="" style="flex: 1;">
            <input type="number" name="wheelweight[]" class="form-control wheelitem-weight" min="1" step="1" value="1" title="Weight" aria-label="Weight">
            <button type="button" class="btn btn-sm btn-outline-danger remove-item" title="Remove item"><?= icon("trash") ?></button>
        </div>
    `;
}

// Initialize on DOM ready
$(document).ready(function() {
    // Populate sectors from the form inputs
    updateWheelFromInputs();
    init();
});

function updateWheelFromInputs() {
    useWeights = weightsEnabled();
    splitIntoSlices = splitSlicesEnabled();
    const wheelItems = document.querySelectorAll(".wheelitem");
    spinthewheel_items = Array.from(wheelItems).map((row, index) => {
        const input = row.querySelector(".wheelitem-input");
        const weightInput = row.querySelector(".wheelitem-weight");
        const weight = useWeights ? parseWeight(weightInput?.value) : 1;
        if (weightInput && weightInput.value !== String(weight)) {
            weightInput.value = weight;
        }
        return {
            index,
            color: `hsl(${(index * 360) / wheelItems.length}, 70%, 50%)`,
            text: "#fff",
            label: input?.value.trim() || `Item #${index + 1}`,
            weight,
        };
    });
    spinthewheel_sectors = buildDisplaySectors(spinthewheel_items, useWeights, splitIntoSlices);
    tot = spinthewheel_sectors.length;
    rebuildSectorGeometry();

    ctx.clearRect(0, 0, dia, dia);
    spinthewheel_sectors.forEach(drawSector);
    rotate();
}

const events = {
    listeners: {},
    addListener: function(eventName, fn) {
        this.listeners[eventName] = this.listeners[eventName] || [];
        this.listeners[eventName].push(fn);
    },
    fire: function(eventName, ...args) {
        if (this.listeners[eventName]) {
            for (let fn of this.listeners[eventName]) {
                fn(...args);
            }
        }
    },
};

const friction = 0.991;
let angVel = 0;
let ang = 0;
let isSpinning = false;
let spinRotated = 0;
let spinTargetRotation = 0;
let spinFinalAng = 0;

function drawSector(sector, i) {
    const sectorStart = useWeights || splitIntoSlices ? sectorStarts[i] : arc * i;
    const sectorArc = useWeights || splitIntoSlices ? sectorArcs[i] : arc;
    ctx.save();

    ctx.beginPath();
    ctx.fillStyle = sector.color;
    ctx.moveTo(rad, rad);
    ctx.arc(rad, rad, rad, sectorStart, sectorStart + sectorArc);
    ctx.lineTo(rad, rad);
    ctx.fill();

    ctx.translate(rad, rad);
    ctx.rotate(sectorStart + sectorArc / 2);
    ctx.textAlign = "right";
    ctx.fillStyle = sector.text;
    
    // Adjust font size based on number of sectors and slice size
    let fontSize = 28;
    const displayText = sector.label;
    
    if (tot <= 4) {
        fontSize = 28;
    } else if (tot <= 6) {
        fontSize = 22;
    } else if (tot <= 10) {
        fontSize = 16;
    } else if (tot <= 16) {
        fontSize = 12;
    } else {
        fontSize = 10;
    }

    if ((useWeights || splitIntoSlices) && sectorArc < TAU / 12) {
        fontSize = Math.min(fontSize, 10);
    }
    
    ctx.font = `bold ${fontSize}px 'Lato', sans-serif`;
    ctx.fillText(displayText, rad - 15, 8);

    ctx.restore();
}

function rotate() {
    const sector = spinthewheel_sectors[getIndex()];
    ctx.canvas.style.transform = `rotate(${ang - PI / 2}rad)`;

    spinEl.style.background = sector.color ? sector.color : "#fff";
    spinEl.style.color = sector.text ? sector.text : "#000";
}

function finishSpin() {
    angVel = 0;
    isSpinning = false;
    spinRotated = 0;
    spinTargetRotation = 0;
    const itemIndex = spinWinnerItemIndex !== null
        ? spinWinnerItemIndex
        : itemIndexFromDisplayIndex(getIndex());
    spinWinnerItemIndex = null;
    const winner = spinthewheel_items[itemIndex];
    $("#spinwheelresponse").html(`<div class="alert alert-success mb-0">✓ Winner: <strong>${winner.label}</strong></div>`);
    spinEl.textContent = "SPIN";
}

function frame() {
    if (isSpinning) {
        const step = angVel;
        angVel *= friction;
        if (useWeights && spinTargetRotation > 0) {
            spinRotated += step;
            ang += step;
            ang %= TAU;
            if (angVel < 0.001 || spinRotated >= spinTargetRotation) {
                ang = spinFinalAng;
                finishSpin();
            }
        } else if (angVel < 0.001) {
            finishSpin();
        } else {
            ang += step;
            ang %= TAU;
        }
    }
    
    ctx.clearRect(0, 0, dia, dia);
    spinthewheel_sectors.forEach(drawSector);
    rotate();
}

function engine() {
    frame();
    requestAnimationFrame(engine);
}

function init() {
    spinEl.addEventListener("click", (e) => {
        e.preventDefault();
        e.stopPropagation();
        if (!isSpinning && angVel < 0.001) {
            $("#spinwheelresponse").html("Spinning...");
            useWeights = weightsEnabled();
            splitIntoSlices = splitSlicesEnabled();
            if (useWeights) {
                spinWinnerItemIndex = weightedRandomItemIndex();
                spinFinalAng = targetAngleForItemIndex(spinWinnerItemIndex);
                const delta = ((spinFinalAng - (ang % TAU)) + TAU) % TAU;
                spinTargetRotation = Math.floor(rand(5, 8)) * TAU + delta;
                spinRotated = 0;
            } else {
                spinWinnerItemIndex = null;
                spinTargetRotation = 0;
                spinRotated = 0;
            }
            angVel = rand(0.25, 0.45);
            isSpinning = true;
        }
    });
    
    engine();
}

// Call init from DOMContentLoaded

/* ===================================================================== */
/*                       Update wheel when items change                  */
/* ===================================================================== */
$(document).on("input", ".wheelitem-input, .wheelitem-weight", function() {
    updateWheelFromInputs();
    updateItemNumbers();
});

$("#wheelUseWeights").on("change", function() {
    $(".wheel-items-panel, .wheelitems").toggleClass("wheel-weights-enabled", this.checked);
    if (!this.checked) {
        $("#wheelDistributeEvenly").prop("checked", false);
    }
    updateWheelFromInputs();
});

$("#wheelDistributeEvenly").on("change", function() {
    updateWheelFromInputs();
});

function updateItemNumbers() {
    document.querySelectorAll(".wheelitem").forEach((item, index) => {
        const badge = item.querySelector(".badge strong");
        if (badge) badge.textContent = index + 1;
    });
}

/* ===================================================================== */
/*                            Add new item                               */
/* ===================================================================== */
$("#addtowheel").on("click", function(e) {
    e.preventDefault();
    var inputCount = $(".wheelitem").length;
    var placeholder = "Item #" + (inputCount + 1);
    $(".wheelitems").append(wheelItemRowHtml(inputCount + 1, placeholder));
    updateWheelFromInputs();
    updateItemNumbers();
    // Add random button to the newly added input (small delay to ensure DOM is ready)
    setTimeout(function() {
        if (typeof addRandomDataButtons === 'function') {
            addRandomDataButtons();
        }
    }, 10);
});

/* ===================================================================== */
/*                          Remove item                                  */
/* ===================================================================== */
$(document).on("click", ".remove-item", function() {
    var inputCount = $(".wheelitem").length;
    
    if (inputCount > 2) {
        $(this).closest(".wheelitem").remove();
        updateItemNumbers();
        updateWheelFromInputs();
    } else {
        $("#spinwheelresponse").html(`<div class="alert alert-danger mb-0">Must have at least 2 items.</div>`);
    }
});

/* ===================================================================== */
/*                           Clear all items                             */
/* ===================================================================== */
$(".clear").on("click", function() {
    $(".wheelitem:gt(1)").remove();
    $(".wheelitem").each(function(index) {
        $(this).find(".wheelitem-input").val("").attr("placeholder", "Item #" + (index + 1));
        $(this).find(".wheelitem-weight").val(1);
    });
    updateItemNumbers();
    updateWheelFromInputs();
});

</script>