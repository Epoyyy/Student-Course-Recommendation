document.getElementById('admission-form').addEventListener('submit', async function (e) {
    e.preventDefault();

    const formData = new FormData(this);

    try {
        const response = await fetch('fetch_recommendations.php', {
            method: 'POST',
            body: formData,
        });

        const result = await response.json();
        console.log(result); // Debugging output

        const courseList = document.getElementById('course-list');
        const studentInfo = document.getElementById('student-info');

        courseList.innerHTML = ''; // Clear previous results
        studentInfo.innerHTML = ''; // Clear previous student info

        if (result.error) {
            studentInfo.textContent = `Error: ${result.error}`;
        } else {
            // Display student info
            const info = document.createElement('p');
            info.textContent = `Name: ${result.name}, Exam Date: ${result.examDate}`;
            studentInfo.appendChild(info);

            // Display recommended courses
            if (result.courses.length > 0) {
                result.courses.forEach(course => {
                    const li = document.createElement('li');
                    li.textContent = `${course.courseName} - ${course.description}`;
                    courseList.appendChild(li);
                });
            } else {
                courseList.textContent = 'No courses available for this SASE score.';
            }
        }
    } catch (error) {
        console.error('Error:', error);
    }
});
