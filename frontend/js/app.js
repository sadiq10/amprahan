const app = document.getElementById('app');

function showMessage(message, type = 'success') {
    const messageElement = document.createElement('div');
    messageElement.className = `message ${type}`;
    messageElement.textContent = message;
    app.prepend(messageElement);

    setTimeout(() => {
        messageElement.remove();
    }, 3000);
}

app.innerHTML = '<h2>Welcome to the Amprahan Application</h2>';
showMessage('Page loaded successfully.');
