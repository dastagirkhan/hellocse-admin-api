<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>API Documentation</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Spinner styles */
        .spinner {
            border: 4px solid rgba(0, 0, 0, 0.1);
            border-top: 4px solid #3498db;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            animation: spin 1s linear infinite;
            display: none; /* Hidden by default */
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body class="bg-gray-100 p-6">
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-md">
        <h1 class="text-2xl font-bold mb-4">HelloCSE API Documentation</h1>

        <!-- View All Active Profiles Button -->
        <div class="mb-6">
            <h2 class="text-xl font-semibold">View All Active Profiles</h2>
            <button onclick="viewProfiles()" class="bg-blue-500 text-white px-4 py-2 mt-2">Fetch Profiles</button>
            <div id="profilesContainer" class="mt-4"></div>
            <div class="spinner" id="spinnerView"></div>
        </div>
        
        <!-- Login Form -->
        <div class="mb-6">
            <h2 class="text-xl font-semibold">Admin Login</h2>
            <input id="email" type="email" placeholder="Email" class="border p-2 w-full mt-2">
            <input id="password" type="password" placeholder="Password" class="border p-2 w-full mt-2">
            <button onclick="login()" class="bg-blue-500 text-white px-4 py-2 mt-2">Login</button>
            <p id="loginResponse" class="text-sm text-gray-600 mt-2"></p>
            <div class="spinner" id="spinnerLogin"></div>
        </div>

        <!-- Create Profile Form -->
        <div class="mb-6">
            <h2 class="text-xl font-semibold">Create Profile</h2>
            <input id="name" type="text" placeholder="First Name" class="border p-2 w-full mt-2">
            <input id="surname" type="text" placeholder="Last Name" class="border p-2 w-full mt-2">
            <input id="image" type="file" class="border p-2 w-full mt-2">
            <button onclick="createProfile()" class="bg-green-500 text-white px-4 py-2 mt-2">Create</button>
            <p id="createProfileResponse" class="text-sm text-gray-600 mt-2"></p>
            <div class="spinner" id="spinnerCreate"></div>
        </div>        

        <!-- Update Profile Form -->
        <div class="mb-6">
            <h2 class="text-xl font-semibold">Update Profile</h2>
            <input id="updateId" type="text" placeholder="Profile ID" class="border p-2 w-full mt-2">
            <input id="updateName" type="text" placeholder="First Name" class="border p-2 w-full mt-2">
            <input id="updateSurname" type="text" placeholder="Last Name" class="border p-2 w-full mt-2">

            <label for="updateStatut" class="mt-2 block">Status</label>
            <select id="updateStatut" class="border p-2 w-full mt-2">
                <option value="actif">Actif</option>
                <option value="inactif">Inactif</option>
                <option value="en attente">En Attente</option>
            </select>

            <button onclick="updateProfile()" class="bg-blue-500 text-white px-4 py-2 mt-2">Update</button>
            <p id="updateProfileResponse" class="text-sm text-gray-600 mt-2"></p>
            <div class="spinner" id="spinnerUpdate"></div>
        </div>


        <!-- Delete Profile Form -->
        <div class="mb-6">
            <h2 class="text-xl font-semibold">Delete Profile</h2>
            <input id="deleteId" type="text" placeholder="Profile ID" class="border p-2 w-full mt-2">
            <button onclick="deleteProfile()" class="bg-red-500 text-white px-4 py-2 mt-2">Delete</button>
            <p id="deleteProfileResponse" class="text-sm text-gray-600 mt-2"></p>
            <div class="spinner" id="spinnerDelete"></div>
        </div>
    </div>

    <script>
    let token = '';
    
    // Get CSRF token from meta tag
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    function showSpinner(spinnerId) {
        document.getElementById(spinnerId).style.display = 'block';
    }

    function hideSpinner(spinnerId) {
        document.getElementById(spinnerId).style.display = 'none';
    }

    async function login() {
        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;
        
        showSpinner('spinnerLogin'); // Show spinner
        const response = await fetch('/api/login', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken // Add CSRF token here
            },
            body: JSON.stringify({ email, password })
        });
        hideSpinner('spinnerLogin'); // Hide spinner

        const data = await response.json();
        if (data.token) {
            token = data.token;
            document.getElementById('loginResponse').innerText = 'Login successful! Token stored.';
        } else {
            document.getElementById('loginResponse').innerText = 'Login failed!';
        }
    }

    async function createProfile() {
        const name = document.getElementById('name').value;
        const surname = document.getElementById('surname').value;
        const image = document.getElementById('image').files[0];

        const formData = new FormData();
        formData.append('nom', name);
        formData.append('prenom', surname);
        formData.append('image', image);

        showSpinner('spinnerCreate'); // Show spinner
        const response = await fetch('/api/profiles', {
            method: 'POST',
            headers: {
                'Authorization': 'Bearer ' + token,
                'X-CSRF-TOKEN': csrfToken // Add CSRF token here
            },
            body: formData
        });
        hideSpinner('spinnerCreate'); // Hide spinner

        const data = await response.json();
        document.getElementById('createProfileResponse').innerText = JSON.stringify(data);
    }

    async function viewProfiles() {
        showSpinner('spinnerView'); // Show spinner
        const response = await fetch('/api/profiles');
        const profiles = await response.json();
        hideSpinner('spinnerView'); // Hide spinner
        const profilesContainer = document.getElementById('profilesContainer');
        profilesContainer.innerHTML = profiles.map(profile => `
            <div class="border p-4 mb-2">
                <img src="${profile.image}" alt="Profile Image" class="w-16 h-16 object-cover rounded-full">
                <h3 class="font-semibold">${profile.prenom} ${profile.nom}</h3>
            </div>
        `).join('');
    }

    async function updateProfile() {
    const id = document.getElementById('updateId').value;
    const name = document.getElementById('updateName').value;
    const surname = document.getElementById('updateSurname').value;
    const statut = document.getElementById('updateStatut').value; // Get statut value

    showSpinner('spinnerUpdate'); // Show spinner
    const response = await fetch(`/api/profiles/${id}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'Authorization': 'Bearer ' + token,
            'X-CSRF-TOKEN': csrfToken // Add CSRF token here
        },
        body: JSON.stringify({ nom: name, prenom: surname, statut: statut }) // Include statut
    });
    hideSpinner('spinnerUpdate'); // Hide spinner

    const data = await response.json();
    document.getElementById('updateProfileResponse').innerText = JSON.stringify(data);
}


    async function deleteProfile() {
        const id = document.getElementById('deleteId').value;

        showSpinner('spinnerDelete'); // Show spinner
        const response = await fetch(`/api/profiles/${id}`, {
            method: 'DELETE',
            headers: {
                'Authorization': 'Bearer ' + token,
                'X-CSRF-TOKEN': csrfToken // Add CSRF token here
            }
        });
        hideSpinner('spinnerDelete'); // Hide spinner

        const data = await response.json();
        document.getElementById('deleteProfileResponse').innerText = JSON.stringify(data);
    }
</script>

</body>
</html>
