document.addEventListener("DOMContentLoaded", function () {
    let db;

    let request = indexedDB.open("SecurePickupDB", 1);

    request.onupgradeneeded = function (event) {
        db = event.target.result;

        if (!db.objectStoreNames.contains("pickup_requests")) {
            let store = db.createObjectStore("pickup_requests", { keyPath: "id", autoIncrement: true });

            store.createIndex("child_name", "child_name", { unique: false });
            store.createIndex("pickup_person", "pickup_person", { unique: false });
            store.createIndex("phone_number", "phone_number", { unique: false });
            store.createIndex("relation", "relation", { unique: false });
            store.createIndex("pickup_date", "pickup_date", { unique: false });
            store.createIndex("pickup_time", "pickup_time", { unique: false });
            store.createIndex("otp", "otp", { unique: false });

            console.log("Table 'pickup_requests' created successfully.");
        }
    };

    request.onsuccess = function (event) {
        db = event.target.result;
        console.log("Database setup completed!");
    };

    request.onerror = function (event) {
        console.error("Database setup failed:", event.target.error);
    };
});