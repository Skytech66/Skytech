function loadStudents(callback) {
    fetch("fetch_students.php")
        .then(response => response.json())
        .then(students => {
            if (callback) {
                callback(students); // Call the function provided by each page
            }
        })
        .catch(error => console.error("Error fetching students:", error));
}