/*
    Tyler Luterbach
    Date: 2024-02-26
    Assignment 3
    CS 215
*/


var form = document.getElementById("answerForm");
form.addEventListener("submit", validateAnswerForm);
setupDynamicQuestionForm();
event.preventDefault();