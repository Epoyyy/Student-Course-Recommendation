document.getElementById('course-form').addEventListener('submit', function(event) {
    event.preventDefault();

    const courseName = document.getElementById('courseName').value;
    const courseDescription = document.getElementById('courseDescription').value;
    const departmentID = document.getElementById('departmentID').value;
    const recommendedMinScore = document.getElementById('recommendedMinScore').value;

    // Simulate adding course to the list
    const courseList = document.getElementById('course-list');
    const courseItem = document.createElement('div');
    courseItem.textContent = `Course: ${courseName}, Description: ${courseDescription}, Department ID: ${departmentID}, Recommended Min Score: ${recommendedMinScore}`;
    courseList.appendChild(courseItem);

    // Clear the form
    document.getElementById('course-form').reset();
});

document.getElementById('college-form').addEventListener('submit', function(event) {
    event.preventDefault();

    const collegeName = document.getElementById('collegeName').value;
    const collegeDescription = document.getElementById('collegeDescription').value;

    // Simulate adding college to the list
    const collegeList = document.getElementById('college-list');
    const collegeItem = document.createElement('div');
    collegeItem.textContent = `College: ${collegeName}, Description: ${collegeDescription}`;
    collegeList.appendChild(collegeItem);

    // Clear the form
    document.getElementById('college-form').reset();
});

document.getElementById('student-requirement-form').addEventListener('submit', function(event) {
    event.preventDefault();

    const studentID = document.getElementById('studentID').value;
    const requirementID = document.getElementById('requirementID').value;
    const status = document.getElementById('status').value;
    const submissionDate = document.getElementById('submissionDate').value;

    // Simulate adding student requirement to the list
    const studentRequirementList = document.getElementById('student-requirement-list');
    const studentRequirementItem = document.createElement('div');
    studentRequirementItem.textContent = `Student ID: ${studentID}, Requirement ID: ${requirementID}, Status: ${status}, Submission Date: ${submissionDate}`;
    studentRequirementList.appendChild(studentRequirementItem);

    // Clear the form
    document.getElementById('student-requirement-form').reset();
});