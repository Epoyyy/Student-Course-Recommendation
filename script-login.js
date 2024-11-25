document.getElementById('loginForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;

    const data = new FormData();
    data.append('email', email);
    data.append('password', password);

    fetch('login.php', {
        method: 'POST',
        body: data
    })
    .then(response => response.json())
    .then(result => {
        const errorMessage = document.getElementById('errorMessage');
        if (result.success) {
            errorMessage.textContent = result.message;
            errorMessage.style.color = 'green';
            errorMessage.style.display = 'block';
            // Redirect to homepage
            window.location.href = "homepage.html";
        } else {
            errorMessage.textContent = result.message;
            errorMessage.style.color = 'red';
            errorMessage.style.display = 'block';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        const errorMessage = document.getElementById('errorMessage');
        errorMessage.textContent = 'An error occurred. Please try again.';
        errorMessage.style.color = 'red';
        errorMessage.style.display = 'block';
    });
});