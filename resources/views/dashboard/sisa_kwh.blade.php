<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Total daya</title>
    <!-- Exo font from Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Exo:wght@400;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Firebase SDK -->
    <script type="module">
        import {
            initializeApp
        } from "https://www.gstatic.com/firebasejs/9.22.0/firebase-app.js";
        import {
            getDatabase,
            ref,
            get
        } from "https://www.gstatic.com/firebasejs/9.22.0/firebase-database.js";

        // Firebase configuration - REPLACE WITH YOUR ACTUAL CONFIG
        const firebaseConfig = {
            apiKey: "your-api-key",
            authDomain: "your-project.firebaseapp.com",
            projectId: "powergo-bismillah",
            databaseURL: "https://powergo-bismillah-default-rtdb.firebaseio.com/",
            storageBucket: "your-project.appspot.com",
            messagingSenderId: "123456789",
            appId: "your-app-id"
        };

        // Initialize Firebase
        const app = initializeApp(firebaseConfig);
        const db = getDatabase(app);

        // Function to fetch and update data
        async function updateDashboardData() {
            try {
                const dbRef = ref(db, 'sensor/-OakoQevOeQxnT0_7ydo');
                const snapshot = await get(dbRef);

                if (snapshot.exists()) {
                    const data = snapshot.val();
                    const wattage = parseFloat(data.wattage) || 0;
                    const maxWatt = 1300;
                    const dashArray = 295;

                    // Loop semua elemen dengan class total-power
                    document.querySelectorAll('.total-power').forEach(el => {
                        el.textContent = wattage.toFixed(2);
                    });

                    // Loop semua lingkaran
                    document.querySelectorAll('.total-power-circle').forEach(circle => {
                        // Hitung progress (0 = kosong, full circle = 1300W)
                        let offset = dashArray - (wattage / maxWatt) * dashArray;
                        if (offset < 0) offset = 0;
                        if (offset > dashArray) offset = dashArray;
                        circle.setAttribute('stroke-dashoffset', offset);

                        // Warna otomatis
                        if (wattage <= 900) {
                            circle.setAttribute('stroke', '#22c55e'); // hijau
                        } else if (wattage <= 1200) {
                            circle.setAttribute('stroke', '#facc15'); // kuning
                        } else {
                            circle.setAttribute('stroke', '#ef4444'); // merah
                        }
                    });

                    console.log("✅ Data fetched:", data);
                } else {
                    console.log("⚠️ No data available");
                }
            } catch (error) {
                console.error("❌ Error fetching data: ", error);
            }
        }


        // Call the function when DOM is loaded
        document.addEventListener('DOMContentLoaded', updateDashboardData);
    </script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'sans': ['Exo', 'sans-serif'],
                    },
                }
            }
        }
    </script>
</head>

<!-- <body class="bg-[#E1DFEC] text-gray-900 font-sans"> -->

    <x-header />
    <div class="bg-[#E1DFEC] mx-auto px-2 sm:px-4">
    <x-tab-navigation-home />
        <div class="container mx-auto flex flex-col md:flex-row gap-4 py-2">

        </div>
    </div>


</html>