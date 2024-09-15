// original code
$("button.clickable").on("click", function () {
    console.log("Button Clicked:", this);
}); // defines the click handler for all buttons
$.get({...}).success(function (res) {
    $("body").append("<button id=`btn_${res.id}` class="clickable">Click Alert!</button>");
}; // dynamically add another button to the page

// first of all, this code cannot work correctly because it has syntax errors:
// incorrect use of quotes that breaks the string, and ` should be used for the entire string, not just for inserting a variable
 $("body").append(`<button id="btn_${res.id}" class="clickable">Click Alert!</button>`);
// also, this code is missing a closing ) for the success() function

// to solve the event handling problem, there are several solutions:
// 1. assign a handler to the parent element instead of directly targeting the element,
// which the script cannot find because it was added after the script was initialized
$("body").on("click", "button.clickable", function() {
    console.log("Button Clicked:", this);
});

// 2. I wouldn't use this option, but you can add an onclick attribute in the HTML element
<button id="btn_${res.id}" class="clickable" onclick="btnClick">Click Alert!</button>
// and add the function in JS
function btnClick() {
    console.log("Button Clicked:", this);
}

// 3. you can first create the element and then add an event handler to it (i would use this variant)
const button = $("<button>", {
    id: `btn_${res.id}`,
    class: "clickable",
    text: "Click Alert!"
});

button.on("click", function() {
    console.log("Button Clicked:", this);
});

$("body").append(button);

