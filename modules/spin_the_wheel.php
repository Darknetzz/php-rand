<style>
/*
 The code used here was borrowed from https://github.com/olimorris/spin-the-wheel
*/

.spin_the_wheel_canvas {
    display: inline-block;
    position: relative;
    overflow: hidden;
}

.spin_the_wheel_wheel {
    display: block;
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

                <div class="d-flex justify-content-center">
                    <div class="spin_the_wheel_canvas">
                        <canvas class="spin_the_wheel_wheel" width="800" height="800"></canvas>
                        <button class="btn btn-success spin_the_wheel_spinbtn">SPIN</button>
                    </div>
                </div>

                <div class="wheelitems">
                    <div class="form-floating mb-3 wheelitem">
                        <input type="text" name="wheelitem[0]" class="form-control" placeholder="Item #1">
                        <label for="floatingInput">Item #1</label>
                    </div>
                    <div class="form-floating mb-3 wheelitem">
                        <input type="text" name="wheelitem[1]" class="form-control" placeholder="Item #2">
                        <label for="floatingInput">Item #2</label>
                    </div>
                </div>

                <!-- OPTIONS -->
                <!-- <div class="card boder border-secondary">
                    <h4 class="card-header text-bg-secondary">Options</h4>
                    <div class="card-body">
                        <div class="form-group mb-3">

                            <div class="form-check form-switch">
                                <input type="hidden" name="morespins" value="0">
                                <input id="morespins" name="morespins" value="1" type="checkbox"
                                    class="form-check-input mb-3">
                                <label for="morespins" class="form-check-label">Spin more than once</label>
                            </div>

                            <div id="spinsopt" style="display:none;">

                                <div class="form-floating">
                                    <input id="spinsamt" name="spinsamt" type="number" class="form-control mb-3"
                                        placeholder="Number of spins" min="1" max="100">
                                    <label for="spinsamt">Number of spins</label>
                                </div>

                                <div class="form-check form-switch">
                                    <input type="hidden" name="unique" value="0">
                                    <input type="checkbox" id="unique" name="unique" value="1"
                                        class="form-check-input mb-3">
                                    <label for="unique" class="form-check-label">Force unique</label>
                                </div>

                            </div>

                        </div>
                    </div>
                </div> -->
                <!-- END OPTIONS -->

                <hr>

                <!-- <div class="btn-group mb-3">
                    <?= submitBtn("spinwheel", text: "Spin") ?>
                </div> -->

                <div class="responseDiv" id="spinwheelresponse"></div>

                <div class="row d-flex justify-content-center">
                    <h5>Items</h5>
                    <div class="input-group col-3" style="width:30%">
                        <button type="button" class="btn btn-secondary"
                            id="removefromwheel"><?= icon("dash-circle") ?></button>
                        <!-- <div class="form-floating"> -->
                        <input type="number" id="itemsamt" class="form-control" placeholder="Number of items" min="1"
                            max="100" value="2">
                        <!-- <label for="floatingInput">Number of items</label> -->
                        <!-- </div> -->
                        <button type="button" class="btn btn-success"
                            id="addtowheel"><?= icon("plus-circle") ?></button>
                    </div>
                    <div class="col">
                        <button type="button" class="btn btn-danger" class="clear"><?= icon("trash") ?></button>
                    </div>
                </div>
            </form>


            <hr>
        </div>
    </div>
</div>

<script>
/* ===================================================================== */

/*
 The code used here was borrowed from https://github.com/olimorris/spin-the-wheel
*/
let spinthewheel_sectors = [
    // Leave this empty for now, we'll add sectors dynamically
    //   { color: "#FFBC03", text: "#333333", label: "Sweets" },
    //   { color: "#FF5A10", text: "#333333", label: "Prize draw" },
    //   { color: "#FFBC03", text: "#333333", label: "Sweets" },
    //   { color: "#FF5A10", text: "#333333", label: "Prize draw" },
    //   { color: "#FFBC03", text: "#333333", label: "Sweets + Prize draw" },
    //   { color: "#FF5A10", text: "#333333", label: "You lose" },
    //   { color: "#FFBC03", text: "#333333", label: "Prize draw" },
    //   { color: "#FF5A10", text: "#333333", label: "Sweets" },
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
const tot = spinthewheel_sectors.length;
const spinEl = document.querySelector(".spin_the_wheel_spinbtn");
const ctx = document.querySelector(".spin_the_wheel_wheel").getContext("2d");
const dia = ctx.canvas.width;
const rad = dia / 2;
const PI = Math.PI;
const TAU = 2 * PI;
const arc = TAU / spinthewheel_sectors.length;


document.addEventListener("DOMContentLoaded", () => {
    // Populate sectors from the form inputs
    const wheelItems = document.querySelectorAll(".wheelitem input");
    spinthewheel_sectors = Array.from(wheelItems).map((input, index) => ({
        color: `hsl(${Math.random() * 360}, 70%, 50%)`, // Random color
        text: "#fff", // Text color
        label: input.value || `Item #${index + 1}`, // Use input value or default label
    }));
});

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


const friction = 0.991; // 0.995=soft, 0.99=mid, 0.98=hard
let angVel = 0; // Angular velocity
let ang = 0; // Angle in radians

let spinButtonClicked = false;

const getIndex = () => Math.floor(tot - (ang / TAU) * tot) % tot;

function drawSector(sector, i) {
    const ang = arc * i;
    ctx.save();

    // COLOR
    ctx.beginPath();
    ctx.fillStyle = sector.color;
    ctx.moveTo(rad, rad);
    ctx.arc(rad, rad, rad, ang, ang + arc);
    ctx.lineTo(rad, rad);
    ctx.fill();

    // TEXT
    ctx.translate(rad, rad);
    ctx.rotate(ang + arc / 2);
    ctx.textAlign = "right";
    ctx.fillStyle = sector.text;
    ctx.font = "bold 30px 'Lato', sans-serif";
    ctx.fillText(sector.label, rad - 10, 10);
    //

    ctx.restore();
}

function rotate() {
    const sector = spinthewheel_sectors[getIndex()];
    ctx.canvas.style.transform = `rotate(${ang - PI / 2}rad)`;

    spinEl.textContent = !angVel ? "SPIN" : sector.label;
    spinEl.style.background = sector.color ? sector.color : "#fff";
    spinEl.style.color = sector.text ? sector.text : "#000";
}

function frame() {
    // Fire an event after the wheel has stopped spinning
    if (!angVel && spinButtonClicked) {
        const finalSector = spinthewheel_sectors[getIndex()];
        events.fire("spinEnd", finalSector);
        spinButtonClicked = false; // reset the flag
        return;
    }

    angVel *= friction; // Decrement velocity by friction
    if (angVel < 0.002) angVel = 0; // Bring to stop
    ang += angVel; // Update angle
    ang %= TAU; // Normalize angle
    rotate();
}

function engine() {
    frame();
    requestAnimationFrame(engine);
}

function init() {
    spinthewheel_sectors.forEach(drawSector);
    rotate(); // Initial rotation
    engine(); // Start engine
    spinEl.addEventListener("click", () => {
        if (!angVel) angVel = rand(0.25, 0.45);
        spinButtonClicked = true;
    });
}

init();


/* ===================================================================== */







/* ===================================================================== */
/*                       FUNCTION: updateWheelItems                      */
/* ===================================================================== */
function updateWheelItems() {
    spinthewheel_sectors = Array.from(document.querySelectorAll(".wheelitem input")).map((input, index) => ({
        color: `hsl(${Math.random() * 360}, 70%, 50%)`, // Random color
        text: "#fff", // Text color
        label: input.value || `Item #${index + 1}`, // Use input value or default label
    }));
}

/* ───────────────────────────────────────────────────────────────────── */
/*                               addtowheel                              */
/* ───────────────────────────────────────────────────────────────────── */
$("#addtowheel").on("click", function(e) {

    var inputCount = ($(".wheelitem").length);
    var placeholder = "Item #" + (inputCount + 1);
    var input = `
        <div class="form-floating mb-3 wheelitem">
            <input type="text" name="wheelitem[]" class="form-control" name="item[]" placeholder="` + placeholder + `">
            <label for="floatingInput">` + placeholder + `</label>
        </div>
        `;
    $(".wheelitems").append(input);
    inputCount += 1;

    $("#itemsamt").val(inputCount);

    // Update the wheel items after adding a new one
    updateWheelItems();
});


/* ───────────────────────────────────────────────────────────────────── */
/*                           remove from wheel                           */
/* ───────────────────────────────────────────────────────────────────── */
$("#removefromwheel").on("click", function() {

    var inputCount = ($(".wheelitem").length);

    if (inputCount > 2) {
        $(".wheelitem").last().remove();
        inputCount -= 1;
    } else {
        $("#spinwheelresponse").html("<div class='alert alert-danger'>Must have at least 2 items.</div>");
    }

    $("#itemsamt").val(inputCount);

    // Update the wheel items after removing one
    updateWheelItems();

});

/* ───────────────────────────────────────────────────────────────────── */
/*                                 clear                                 */
/* ───────────────────────────────────────────────────────────────────── */
$(".clear").on("click", function() {
    $(".wheelitem:gt(1)").remove();

    $("#itemsamt").val($(".wheelitem").length);
});

/* ───────────────────────────────────────────────────────────────────── */
/*                               morespins                               */
/* ───────────────────────────────────────────────────────────────────── */
// $("#morespins").on("click", function() {
//     if ($(this).is(":checked")) {
//         $("#spinsopt").fadeIn();
//     } else {
//         $("#spinsopt").fadeOut();
//     }
// });


/* ───────────────────────────────────────────────────────────────────── */
/*                                itemsamt                               */
/* ───────────────────────────────────────────────────────────────────── */
$("#itemsamt").on("change keyup", function() {
    var itemsamt = $(this).val();
    var inputCount = ($(".wheelitem").length);
    console.log("Itemsamt: " + itemsamt);
    console.log("Inputcount: " + inputCount);

    if (itemsamt > inputCount) {
        var diff = itemsamt - inputCount;
        console.log("Diff: " + diff);
        for (var i = 0; i < diff; i++) {
            $("#addtowheel").trigger("click");
        }
    } else if (itemsamt < inputCount) {
        var diff = inputCount - itemsamt;
        console.log("Diff: " + diff);
        for (var i = 0; i < diff; i++) {
            $("#removefromwheel").trigger("click");
        }
    }
});


</script>