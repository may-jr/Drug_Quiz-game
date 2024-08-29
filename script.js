let currentQuestion = null;
let score = 0;

function loadQuestion() {
    fetch('get_question.php')
        .then(response => response.json())
        .then(data => {
            currentQuestion = data;
            displayQuestion(data);
        })
        .catch(error => console.error('Error:', error));
}

function displayQuestion(question) {
    const questionElement = document.getElementById('question');
    const optionsElement = document.getElementById('options');

    questionElement.textContent = question.question;
    optionsElement.innerHTML = '';

    question.options.forEach((option, index) => {
        const button = document.createElement('button');
        button.textContent = option;
        button.classList.add('option');
        button.addEventListener('click', () => selectOption(index));
        optionsElement.appendChild(button);
    });
}

function selectOption(index) {
    const options = document.querySelectorAll('.option');
    options.forEach(option => option.classList.remove('selected'));
    options[index].classList.add('selected');
}

function submitAnswer() {
    const selectedOption = document.querySelector('.option.selected');
    if (!selectedOption) {
        alert('Please select an answer');
        return;
    }

    const answer = selectedOption.textContent;
    
    fetch('check_answer.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `question_id=${currentQuestion.id}&answer=${answer}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.correct) {
            score++;
            document.getElementById('score-value').textContent = score;
            document.getElementById('result').textContent = 'Correct!';
        } else {
            document.getElementById('result').textContent = `Incorrect. The correct answer is ${data.correct_answer}`;
        }
        setTimeout(loadQuestion, 2000);
    })
    .catch(error => console.error('Error:', error));
}

document.getElementById('submit-btn').addEventListener('click', submitAnswer);

loadQuestion();