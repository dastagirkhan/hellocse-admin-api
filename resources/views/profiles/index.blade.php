<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Active Profiles</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Spinner styles */
        .spinner {
            display: none; /* Hidden by default */
            border: 4px solid rgba(255, 255, 255, 0.3);
            border-top: 4px solid #3498db;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
    <script>
        document.addEventListener("DOMContentLoaded", async function () {
            const profilesContainer = document.getElementById("profiles");
            const spinner = document.getElementById("spinner");

            // Show the spinner
            spinner.style.display = "block";

            try {
                let response = await fetch("/api/profiles");
                let profiles = await response.json();
                
                profiles.length && (profilesContainer.innerHTML = profiles                    
                    .map(profile => `
                        <div class="bg-white shadow-lg p-4 rounded-lg">
                            <img src="${profile.image}" alt="Profile Image" class="w-full h-48 object-cover rounded-lg">
                            <h2 class="text-xl font-semibold mt-2">${profile.prenom} ${profile.nom}</h2>
                            ${profile.is_admin ? `
                                <div class="mt-2">
                                    <a href="/profiles/${profile.id}/edit" class="text-blue-500">Edit</a> |
                                    <button onclick="deleteProfile(${profile.id})" class="text-red-500">Delete</button>
                                </div>
                            ` : ''}
                        </div>
                    `).join(''));
            } catch (error) {
                console.error("Failed to fetch profiles:", error);
            } finally {
                // Hide the spinner after fetch is complete
                spinner.style.display = "none";
            }
        });

        function deleteProfile(id) {
            fetch(`/api/profiles/${id}`, {
                method: "DELETE",
                headers: { "X-CSRF-TOKEN": "{{ csrf_token() }}" }
            }).then(() => location.reload());
        }
    </script>
</head>
<body class="bg-gray-100 p-6">
    <div class="container mx-auto">
        <h1 class="text-3xl font-bold text-center mb-6">Active Profiles</h1>
        
        <!-- Loading Spinner -->
        <div id="spinner" class="spinner mx-auto"></div>

        <div id="profiles" class="grid grid-cols-1 md:grid-cols-3 gap-6"></div>
    </div>
</body>
</html>
