<nav id="navbar" class="fixed top-0 left-0 z-50 bg-[#1A4870] px-4 flex justify-between items-center w-full">

    <!-- Brand Name -->
    <a  href="./index.php" class="hover:cursor-pointer text-white text-lg font-semibold py-3 px-4 focus:outline-none">Food Bungalow</a>
    
    <!-- Right Section with Buttons -->
    <div class="flex items-center space-x-1">
        <!-- Add New Button -->
        <a href="./addnew.php" class="hover:cursor-pointer text-white px-4 py-3 hover:bg-gray-600 focus:outline-none">
            Add New
        </a>
        <a href="./profile.php" class="hover:cursor-pointer text-white px-4 py-3 hover:bg-gray-600 focus:outline-none">
            Profile
        </a>
        <!-- Account Dropdown -->
        <div class="relative">
            <button id="dropdownButton" class="hover:cursor-pointer text-white px-4 py-3 hover:bg-gray-600 focus:outline-none">
                <?php echo htmlspecialchars($_SESSION['email']); ?> ▼
            </button>
            <!-- Dropdown Content -->
            <div id="dropdownMenu" class="hover:cursor-pointer absolute right-0 w-48 bg-white rounded-lg shadow-lg hidden">
                <a href="./logout.php" class="block px-4 py-2 text-white bg-gray-700 hover:bg-gray-00">Logout</a>
            </div>
        </div>  

          <div class="relative">
            <button id="notification-icon">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14V11a6 6 0 10-12 0v3c0 .384-.146.768-.405 1.105L4 17h5m1 4h4a2 2 0 01-2 2 2 2 0 01-2-2z" />
                </svg>
            </button>
            <!-- Badge showing notification count -->
          
            <span id="notification-count" class="absolute top-0 right-0 hidden inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-red-100 bg-red-600 rounded-full"></span>

            <div id="notification-list" class="hover:cursor-pointer absolute right-0 w-48 bg-white rounded-lg shadow-lg hidden">
            
            </div>
        </div>
    </div>
</nav>

<script>
    const dropdownButton = document.getElementById('dropdownButton');
    const dropdownMenu = document.getElementById('dropdownMenu');

    dropdownButton.addEventListener('click', () => {
        dropdownMenu.classList.toggle('hidden');
    });

    // Close the dropdown if clicking outside of it
    document.addEventListener('click', (e) => {
        if (!dropdownButton.contains(e.target) && !dropdownMenu.contains(e.target)) {
            dropdownMenu.classList.add('hidden');
        }
    });

    
    const notifDropdownButton = document.getElementById('notification-icon');
    const notifDropdownMenu = document.getElementById('notification-list');

    notifDropdownButton.addEventListener('click', () => {
        notifDropdownMenu.classList.toggle('hidden');
    });

    // Close the dropdown if clicking outside of it
    document.addEventListener('click', (e) => {
        if (!notifDropdownButton.contains(e.target) && !notifDropdownMenu.contains(e.target)) {
            notifDropdownMenu.classList.add('hidden');
        }
    });

    async function fetchNotifications(markAsRead = false) {
    try {
        // Send the `markAsRead` parameter in the request
        const response = await fetch(
        `./api/getNotificationCount.php?markAsRead=${markAsRead}`,
        {
            method: 'GET',
        }
        );

        const data = await response.json();

        const countElement = document.getElementById('notification-count');
        if (data.length > 0) {
        countElement.textContent = data.length;
        countElement.classList.remove('hidden');
        } else {
        countElement.classList.add('hidden');
        }

        const notifElement = document.getElementById('notification-list');
        let html = '';
        for (let i = 0; i < data.length; i++) {
        if (data[i].post_count > 0) {
            html = html +
            `<a
                href="./profileVisit.php?id=${data[i].id}"
                class="block px-4 py-2 text-white bg-gray-700 hover:bg-gray-00"
                >${data[i].name} has ${data[i].post_count} new posts</a>`;
        }
        }

        notifElement.innerHTML = html;
    } catch (error) {
        console.error('Error fetching notifications:', error);
    }
    }

    // Fetch notifications on page load without marking them as read
    window.addEventListener('DOMContentLoaded', () => {
    fetchNotifications(false);
    });

    // Fetch notifications and mark as read when the icon is clicked
    document
    .getElementById('notification-icon')
    .addEventListener('click', () => fetchNotifications(true));
</script>