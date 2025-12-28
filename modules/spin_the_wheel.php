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
                    <div class="col-12 col-lg-6 d-flex flex-column">
                        <h4 class="mb-3"><strong>Wheel Items</strong></h4>
                        
                        <div class="wheelitems mb-3" style="overflow-y: auto; max-height: 400px; min-height: 400px; padding-right: 10px; border: 1px solid #dee2e6; border-radius: 0.25rem; padding: 15px; background-color: rgba(0,0,0,0.05);">
                            <div class="input-group mb-3 wheelitem" style="gap: 8px;">
                                <span class="badge bg-primary" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; font-size: 1.1rem;"><strong>1</strong></span>
                                <input type="text" name="wheelitem[0]" class="form-control wheelitem-input" placeholder="Item #1" value="Item #1" style="flex: 1;">
                                <button type="button" class="btn btn-sm btn-outline-danger remove-item" title="Remove item"><?= icon("trash") ?></button>
                            </div>
                            <div class="input-group mb-3 wheelitem" style="gap: 8px;">
                                <span class="badge bg-primary" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; font-size: 1.1rem;"><strong>2</strong></span>
                                <input type="text" name="wheelitem[1]" class="form-control wheelitem-input" placeholder="Item #2" value="Item #2" style="flex: 1;">
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
let spinthewheel_sectors = [
    {
        color: "#FFBC03",
        text: "#333333",
        label: "Item #1"
    },
    {
        color: "#FF5A10",
        text: "#333333",
        label: "Item #2"
    },
];


const rand = (m, M) => Math.random() * (M - m) + m;
let tot = spinthewheel_sectors.length;
const spinEl = document.querySelector(".spin_the_wheel_spinbtn");
const ctx = document.querySelector(".spin_the_wheel_wheel").getContext("2d");
const dia = ctx.canvas.width;
const rad = dia / 2;
const PI = Math.PI;
const TAU = 2 * PI;
let arc = TAU / spinthewheel_sectors.length;

// Initialize on DOM ready
$(document).ready(function() {
    // Populate sectors from the form inputs
    updateWheelFromInputs();
    init();
    // Add random buttons to existing wheel items
    addRandomButtonsToWheelItems();
});

function updateWheelFromInputs() {
    const wheelItems = document.querySelectorAll(".wheelitem-input");
    spinthewheel_sectors = Array.from(wheelItems).map((input, index) => ({
        color: `hsl(${(index * 360) / wheelItems.length}, 70%, 50%)`, // Evenly spaced colors
        text: "#fff",
        label: input.value || `Item #${index + 1}`,
    }));
    tot = spinthewheel_sectors.length;
    arc = TAU / spinthewheel_sectors.length;
    
    // Redraw the wheel
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

const getIndex = () => Math.floor(tot - (ang / TAU) * tot) % tot;

function drawSector(sector, i) {
    const ang = arc * i;
    ctx.save();

    ctx.beginPath();
    ctx.fillStyle = sector.color;
    ctx.moveTo(rad, rad);
    ctx.arc(rad, rad, rad, ang, ang + arc);
    ctx.lineTo(rad, rad);
    ctx.fill();

    ctx.translate(rad, rad);
    ctx.rotate(ang + arc / 2);
    ctx.textAlign = "right";
    ctx.fillStyle = sector.text;
    
    // Adjust font size based on number of sectors
    let fontSize = 28;
    let displayText = sector.label;
    
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

function frame() {
    if (isSpinning) {
        angVel *= friction;
        if (angVel < 0.001) {
            // Spin has finished
            angVel = 0;
            isSpinning = false;
            const finalSector = spinthewheel_sectors[getIndex()];
            $("#spinwheelresponse").html(`<div class="alert alert-success mb-0">✓ Winner: <strong>${finalSector.label}</strong></div>`);
            spinEl.textContent = "SPIN";
        } else {
            ang += angVel;
            ang %= TAU;
        }
    }
    
    // Always redraw the wheel
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
        // Only allow spin if wheel is not currently spinning
        if (!isSpinning && angVel < 0.001) {
            $("#spinwheelresponse").html("Spinning...");
            angVel = rand(0.25, 0.45);
            isSpinning = true;
        }
    });
    
    // Start the animation engine AFTER setting up the click handler
    engine();
}

// Call init from DOMContentLoaded

/* ===================================================================== */
/*                       Update wheel when items change                  */
/* ===================================================================== */
$(document).on("input", ".wheelitem-input", function() {
    updateWheelFromInputs();
    updateItemNumbers();
});

function updateItemNumbers() {
    document.querySelectorAll(".wheelitem").forEach((item, index) => {
        const badge = item.querySelector(".badge strong");
        if (badge) badge.textContent = index + 1;
    });
}

/* ===================================================================== */
/*                      Add random buttons to wheel items                */
/* ===================================================================== */
function addRandomButtonsToWheelItems() {
    // Check if jQuery is available
    if (typeof jQuery === 'undefined' || typeof $ === 'undefined') {
        console.warn('jQuery not available for random buttons');
        return;
    }
    
    var inputs = $(".wheelitem-input");
    if (inputs.length === 0) {
        console.log('No wheelitem-input elements found');
        return;
    }
    
    inputs.each(function() {
        const $input = $(this);
        
        // Skip if already has a random button
        if ($input.next(".random-data-btn").length > 0 || $input.siblings(".random-data-btn").length > 0) {
            return;
        }
        
        // Create the random button
        const $btn = $('<button>', {
            type: 'button',
            class: 'btn btn-sm btn-outline-secondary random-data-btn',
            title: 'Generate random data',
            html: '<i class="bi bi-shuffle"></i>',
            css: {
                'flex-shrink': '0'
            }
        });
        
        // Add click handler
        $btn.on("click", function() {
            const placeholder = $input.attr("placeholder") || "";
            let randomData;
            
            // Use the global generateRandomData function if available
            if (typeof generateRandomData === 'function') {
                randomData = generateRandomData('text', placeholder, $input);
            } else {
                // Fallback: simple random string
                const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
                let result = '';
                for (let i = 0; i < 12; i++) {
                    result += chars.charAt(Math.floor(Math.random() * chars.length));
                }
                randomData = result;
            }
            
            $input.val(randomData).trigger('input').trigger('change');
            
            // Visual feedback
            const originalHtml = $btn.html();
            $btn.html('<i class="bi bi-check"></i>').removeClass('btn-outline-secondary').addClass('btn-success');
            setTimeout(function() {
                $btn.html(originalHtml).removeClass('btn-success').addClass('btn-outline-secondary');
            }, 1000);
        });
        
        // Insert button after the input (before the remove button)
        $input.after($btn);
    });
}

/* ===================================================================== */
/*                            Add new item                               */
/* ===================================================================== */
$("#addtowheel").on("click", function(e) {
    e.preventDefault();
    var inputCount = $(".wheelitem").length;
    var placeholder = "Item #" + (inputCount + 1);
    var input = `
        <div class="input-group mb-3 wheelitem" style="gap: 8px;">
            <span class="badge bg-primary" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; font-size: 1.1rem;"><strong>${inputCount + 1}</strong></span>
            <input type="text" name="wheelitem[]" class="form-control wheelitem-input" placeholder="${placeholder}" value="${placeholder}" style="flex: 1;">
            <button type="button" class="btn btn-sm btn-outline-danger remove-item" title="Remove item"><?= icon("trash") ?></button>
        </div>
    `;
    $(".wheelitems").append(input);
    updateWheelFromInputs();
    updateItemNumbers();
    // Add random data button to the newly added input
    setTimeout(function() {
        addRandomButtonsToWheelItems();
    }, 50);
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
        $(this).find(".wheelitem-input").val("Item #" + (index + 1));
    });
    updateItemNumbers();
    updateWheelFromInputs();
});

</script>