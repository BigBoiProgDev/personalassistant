// DOM Elements
const taskList = document.getElementById('task-list');
const addTaskBtn = document.getElementById('add-task-btn');
const newTaskInput = document.getElementById('new-task');
const weatherUpdate = document.getElementById('weather-update');
const dailyQuote = document.getElementById('daily-quote');
const toggleThemeBtn = document.getElementById('toggle-theme');
const reminderList = document.getElementById('reminder-list');
const setReminderBtn = document.getElementById('set-reminder-btn');
const reminderText = document.getElementById('reminder-text');
const reminderTime = document.getElementById('reminder-time');

// Sample Data for Weather and Quotes
const weatherSamples = [
    "Sunny with a light breeze",
    "Cloudy with a chance of rain",
    "Partly cloudy with temperatures around 25°C",
    "Thunderstorms expected later today",
    "Clear skies and warm temperatures"
];

const quotes = [
    "The best way to get started is to quit talking and begin doing.",
    "Don’t let yesterday take up too much of today.",
    "The pessimist sees difficulty in every opportunity. The optimist sees opportunity in every difficulty.",
    "You learn more from failure than from success. Don’t let it stop you.",
    "Whether you think you can or think you can’t, you’re right."
];

// Add Task via AJAX
addTaskBtn.addEventListener('click', () => {
    const taskText = newTaskInput.value.trim();
    if (taskText === '') return;

    fetch('add_task.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `task_text=${encodeURIComponent(taskText)}`
    })
    .then(response => response.json()) // Expecting JSON with task_id and task_text
    .then(task => {
        // Add task to the task list
        const li = document.createElement('li');
        const taskTextSpan = document.createElement('span');
        taskTextSpan.textContent = task.task_text;
        taskTextSpan.style.marginRight = '10px';

        const deleteBtn = document.createElement('button');
        deleteBtn.textContent = 'Delete';
        deleteBtn.addEventListener('click', () => deleteTask(task.task_id));

        li.appendChild(taskTextSpan);
        li.appendChild(deleteBtn);
        taskList.appendChild(li);

        newTaskInput.value = ''; // Clear input after adding the task
    })
    .catch(err => console.error('Error:', err));
});

// Load Tasks from Database
function loadTasks() {
    fetch('get_tasks.php')
        .then(response => response.json())
        .then(tasks => {
            taskList.innerHTML = ''; // Clear current tasks before loading
            tasks.forEach(task => {
                const li = document.createElement('li');
                const taskText = document.createElement('span');
                taskText.textContent = task.task_text;
                taskText.style.marginRight = '10px';
                taskText.addEventListener('click', () => editTask(task.task_id, taskText));

                const deleteBtn = document.createElement('button');
                deleteBtn.textContent = 'Delete';
                deleteBtn.addEventListener('click', () => deleteTask(task.task_id));

                li.appendChild(taskText);
                li.appendChild(deleteBtn);
                taskList.appendChild(li);
            });
        })
        .catch(err => console.error('Error fetching tasks:', err));
}

// Edit Task Functionality
function editTask(taskId, taskElement) {
    const newTaskText = prompt("Edit task:", taskElement.textContent);
    if (newTaskText === null || newTaskText.trim() === '') return;

    fetch('update_task.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `task_id=${taskId}&task_text=${encodeURIComponent(newTaskText)}`
    })
    .then(() => loadTasks())
    .catch(err => console.error('Error updating task:', err));
}

// Delete Task Functionality
function deleteTask(taskId) {
    fetch('delete_task.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `task_id=${taskId}`
    })
    .then(() => loadTasks())
    .catch(err => console.error('Error deleting task:', err));
}

// Display Random Weather Update
function updateWeather() {
    const randomWeather = weatherSamples[Math.floor(Math.random() * weatherSamples.length)];
    weatherUpdate.textContent = randomWeather;
}

// Display Daily Quote
function displayQuote() {
    const randomQuote = quotes[Math.floor(Math.random() * quotes.length)];
    dailyQuote.textContent = randomQuote;
}

// Toggle Light/Dark Theme (unchanged from your original code)
toggleThemeBtn.addEventListener('click', () => {
    document.body.classList.toggle('dark-mode');

    if (document.body.classList.contains('dark-mode')) {
        localStorage.setItem('theme', 'dark');
    } else {
        localStorage.setItem('theme', 'light');
    }
});

document.addEventListener("DOMContentLoaded", function() {
    if (localStorage.getItem('theme') === 'dark') {
        document.body.classList.add('dark-mode');
    }
});

// Set Reminder
setReminderBtn.addEventListener('click', () => {
    const text = reminderText.value.trim();
    const time = reminderTime.value;
    if (text === '' || time === '') return;

    const reminderItem = document.createElement('li');
    reminderItem.textContent = `${text} at ${time}`;
    reminderList.appendChild(reminderItem);

    const [hours, minutes] = time.split(':');
    const now = new Date();
    const reminderDate = new Date();
    reminderDate.setHours(hours, minutes, 0, 0);

    const timeDifference = reminderDate.getTime() - now.getTime();
    if (timeDifference > 0) {
        setTimeout(() => {
            alert(`Reminder: ${text}`);
            reminderItem.remove();
        }, timeDifference);
    }

    reminderText.value = '';
    reminderTime.value = '';
});

// Initialize the app
updateWeather();
displayQuote();
loadTasks(); // Load tasks when the page is loaded
