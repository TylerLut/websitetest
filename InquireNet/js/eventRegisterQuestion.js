/*
    Tyler Luterbach
    Date: 2024-02-26
    Assignment 3
    CS 215
*/


var form = document.getElementById("questionForm");
form.addEventListener("submit", validateQuestionForm);
setupDynamicQuestionForm();
event.preventDefault();
