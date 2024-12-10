// Get the modal
var modal = document.getElementById("addTaskModal");

// Get the button that opens the modal
var btn = document.getElementById("addTaskBtn");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close-btn")[0];

// Open the modal when the user clicks the "Add Task" button
btn.onclick = function() {
    modal.style.display = "block";
}

// Close the modal when the user clicks the close button (x)
span.onclick = function() {
    modal.style.display = "none";
}

// Close the modal if the user clicks anywhere outside the modal
window.onclick = function(event) {
    if (event.target === modal) {
        modal.style.display = "none";
    }
}

// Switch between views
const compactBtn = document.getElementById("compactViewBtn");
const detailedBtn = document.getElementById("detailedViewBtn");
const tileBtn = document.getElementById("tileViewBtn");
const taskContainer = document.getElementById("taskContainer");

compactBtn.addEventListener("click", function() {
    taskContainer.className = "task-container compact-view";
});

detailedBtn.addEventListener("click", function() {
    taskContainer.className = "task-container detailed-view";
});

tileBtn.addEventListener("click", function() {
    taskContainer.className = "task-container tile-view";
});
