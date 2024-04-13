/*
    Tyler Luterbach
    Date: 2024-02-26
    Assignment 3
    CS 215
*/


function showError(element, message) {
    element.classList.add('error');
    var errorMessage = element.nextElementSibling;
    if (!errorMessage || !errorMessage.classList.contains('errorMessage')) {
        errorMessage = document.createElement('span');
        errorMessage.classList.add('errorMessage');
        element.parentNode.insertBefore(errorMessage, element.nextSibling);
    }
    errorMessage.textContent = message;
}

function resetFormErrors() {
    document.querySelectorAll('.errorMessage').forEach(function(errorMessage) {
        errorMessage.remove();
    });
    document.querySelectorAll('.error').forEach(function(inputElement) {
        inputElement.classList.remove('error');
    });
}

function validateUsername(username) {
    var regex = /^\w+$/;
    return regex.test(username);
}

function validateEmail(email) {
    var regex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
    return regex.test(email);
}

function validatePassword(password) {
    return password.length >= 6 && /[A-Z]/.test(password) && /[0-9]/.test(password);
}

function validateQuestion(question) {
    return question.length > 0 && question.length <= 1500;
}

function validateAnswer(answer) {
    return answer.length > 0 && answer.length <= 1500;
}

function validateScreenName(screenName) {
    var regex = /^\w+$/;
    return regex.test(screenName);
}

function validateAvatar(avatar) {
    return avatar.files && avatar.files.length > 0;
}

function validateLoginForm(event) {
    var username = document.getElementById('username');
    var password = document.getElementById('password');
    var isValid = true;

    resetFormErrors();

    if (!validateUsername(username.value)) {
        showError(username, 'Invalid username format');
        isValid = false;
    }

    if (!validatePassword(password.value)) {
        showError(password, 'Password must be at least 6 characters long and contain at least one uppercase letter and one number');
        isValid = false;
    }

    if (!isValid) {
        event.preventDefault();
    }
}

function validateSignupForm() {
    var email = document.getElementById('email');
    var password = document.getElementById('password');
    var confirmPassword = document.getElementById('confirmPassword');
    var avatar = document.getElementById('avatar');
    var screenName = document.getElementById('screenName'); // Updated to match ID
    var isValid = true;

    resetFormErrors();

    if (!validateEmail(email.value)) {
        showError(email, 'Invalid email format');
        isValid = false;
    }

    if (!validateScreenName(screenName.value)) {
        showError(screenName, 'Screen name must not contain spaces or special characters');
        isValid = false;
    }

    if (!validateAvatar(avatar)) {
        showError(avatar, 'Please select an avatar image');
        isValid = false;
    }

    if (!validatePassword(password.value)) {
        showError(password, 'Password must contain at least one uppercase letter and one number');
        isValid = false;
    }

    if (password.value !== confirmPassword.value) {
        showError(confirmPassword, 'Passwords do not match');
        isValid = false;
    }

    return isValid;
}

function validateQuestionForm() {
    var question = document.getElementById('question');
    var isValid = true;

    resetFormErrors();

    if (!validateQuestion(question.value)) {
        showError(question, 'Question must be between 1 and 1500 characters');
        isValid = false;
    }

    return isValid;
}

function validateAnswerForm() {
    var answer = document.getElementById('answer');
    var isValid = true;

    resetFormErrors();

    if (!validateAnswer(answer.value)) {
        showError(answer, 'Answer must be between 1 and 1500 characters');
        isValid = false;
    }

    return isValid;
}

function setupDynamicCharacterCounter(textAreaId, counterId, maxCharacters) {
    const textArea = document.getElementById(textAreaId);
    const counter = document.getElementById(counterId);

    textArea.addEventListener('input', () => {
        const currentLength = textArea.value.length;
        counter.textContent = `${currentLength}/${maxCharacters}`;

        if (currentLength > maxCharacters) {
            counter.style.color = 'red';
        } else {
            counter.style.color = 'black';
        }
    });
}

document.addEventListener('DOMContentLoaded', () => {
    setupDynamicCharacterCounter('question', 'questionCounter', 1500);
    setupDynamicCharacterCounter('answer', 'answerCounter', 1500);
});


function vote(answerId, voteType) {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {

            document.getElementById("votes-" + answerId).innerText = this.responseText;
        }
    };
    xhttp.open("POST", "vote.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("answerId=" + answerId + "&voteType=" + voteType);
}

